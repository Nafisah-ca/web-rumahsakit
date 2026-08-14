<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranMcu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class McuController extends Controller
{
    // Tampilkan form pendaftaran MCU
    public function create(Request $request)
    {
        $paket  = $request->query('paket', 'basic');
        $pakets = PendaftaranMcu::$pakets;

        // Jika paket tidak valid, fallback ke basic
        if (!array_key_exists($paket, $pakets)) {
            $paket = 'basic';
        }

        $setting = \App\Models\WebsiteSetting::getSetting();
        $banner  = \App\Models\PageBanner::getForPage('mcu');

        return view('mcu-daftar', compact('paket', 'pakets', 'setting', 'banner'));
    }

    // Simpan pendaftaran MCU
    public function store(Request $request)
    {
        $request->validate([
            'paket'           => 'required|in:basic,standard,executive,corporate',
            'nama_lengkap'    => 'required|string|max:150',
            'nik'             => 'nullable|digits:16',
            'no_hp'           => 'required|string|max:20',
            'email'           => 'nullable|email|max:150',
            'jenis_kelamin'   => 'required|in:L,P',
            'tanggal_lahir'   => 'required|date|before:today',
            'alamat'          => 'nullable|string|max:500',
            'tanggal_pilihan' => 'required|date|after_or_equal:today',
            'sesi'            => 'required|in:pagi,siang',
            'catatan'         => 'nullable|string|max:500',
        ], [
            'paket.required'             => 'Paket wajib dipilih.',
            'nama_lengkap.required'      => 'Nama lengkap wajib diisi.',
            'no_hp.required'             => 'Nomor HP wajib diisi.',
            'jenis_kelamin.required'     => 'Jenis kelamin wajib dipilih.',
            'tanggal_lahir.required'     => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before'       => 'Tanggal lahir harus sebelum hari ini.',
            'tanggal_pilihan.required'   => 'Tanggal pemeriksaan wajib dipilih.',
            'tanggal_pilihan.after_or_equal' => 'Tanggal pemeriksaan tidak boleh di masa lalu.',
            'sesi.required'              => 'Sesi wajib dipilih.',
            'nik.digits'                 => 'NIK harus 16 digit angka.',
        ]);

        $pendaftaran = PendaftaranMcu::create([
            'kode_pendaftaran' => PendaftaranMcu::generateKode(),
            'paket'            => $request->paket,
            'nama_lengkap'     => $request->nama_lengkap,
            'nik'              => $request->nik,
            'no_hp'            => $request->no_hp,
            'email'            => $request->email,
            'jenis_kelamin'    => $request->jenis_kelamin,
            'tanggal_lahir'    => $request->tanggal_lahir,
            'alamat'           => $request->alamat,
            'tanggal_pilihan'  => $request->tanggal_pilihan,
            'sesi'             => $request->sesi,
            'catatan'          => $request->catatan,
            'status'           => 'menunggu',
            'user_id'          => Auth::id(),
        ]);

        return redirect()->route('mcu.sukses', ['kode' => $pendaftaran->kode_pendaftaran])
            ->with('success', 'Pendaftaran MCU berhasil! Kode Anda: ' . $pendaftaran->kode_pendaftaran);
    }

    // Halaman sukses setelah daftar
    public function sukses(Request $request)
    {
        $kode        = $request->query('kode');
        $pendaftaran = PendaftaranMcu::where('kode_pendaftaran', $kode)->firstOrFail();
        $setting     = \App\Models\WebsiteSetting::getSetting();
        return view('mcu-sukses', compact('pendaftaran', 'setting'));
    }
}
