<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\JanjiTemu;
use App\Models\Layanan;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // middleware auth di-handle oleh Route middleware, jadi tidak diperlukan di constructor


    /** Halaman form booking */
    public function create(Request $request)
    {
        $dokter   = $request->dokter_id ? Dokter::with(['spesialisasi', 'jadwalAktif'])->find($request->dokter_id) : null;
        $dokters  = Dokter::with('spesialisasi')->where('status', 'aktif')->orderBy('nama_dokter')->get();
        $pasien   = Auth::user()->pasien;
        return view('portal.booking.create', compact('dokter', 'dokters', 'pasien'));
    }

    /** API: Ambil jadwal dokter berdasarkan dokter dan tanggal */
    public function jadwal(Request $request)
    {
        $tanggal = $request->tanggal_kunjungan ?: today()->toDateString();

        // Dapatkan nama hari dari tanggal yang dipilih
        $hariPilihan = \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd');
        // isoFormat dddd = Senin, Selasa, Rabu, Kamis, Jumat, Sabtu, Minggu

        // Ambil jadwal aktif dokter yang harinya sesuai dengan tanggal dipilih
        $jadwals = JadwalDokter::where('dokter_id', $request->dokter_id)
            ->where('status', 'aktif')
            ->where('hari', $hariPilihan)
            ->get()
            ->map(function ($j) use ($tanggal) {
                $terisi = JanjiTemu::where('jadwal_dokter_id', $j->id)
                    ->whereDate('tanggal_booking', $tanggal)
                    ->whereNotIn('status', ['cancelled'])
                    ->count();

                return [
                    'id'          => $j->id,
                    'hari'        => $j->hari,
                    'hari_label'  => $j->hari,
                    'jam_mulai'   => substr($j->jam_mulai, 0, 5),
                    'jam_selesai' => substr($j->jam_selesai, 0, 5),
                    'kuota'       => $j->kuota,
                    'sisa_kuota'  => max(0, $j->kuota - $terisi),
                ];
            });

        // Jika tidak ada jadwal di hari itu, kembalikan array kosong
        return response()->json($jadwals);
    }




    /** Simpan booking */
    public function store(Request $request)
    {
        $request->validate([
            'jadwal_dokter_id'  => 'required|exists:jadwal_dokter,id',
            'tanggal_kunjungan' => 'required|date|after_or_equal:today',
            'keluhan'           => 'required|string|min:3|max:1000',
        ], [
            'jadwal_dokter_id.required' => 'Pilih jadwal dokter terlebih dahulu.',
            'tanggal_kunjungan.required'=> 'Tanggal kunjungan wajib diisi.',
            'keluhan.required'          => 'Keluhan wajib diisi.',
            'keluhan.min'               => 'Keluhan minimal 3 karakter.',
        ]);

        $pasien = Auth::user()->pasien;
        if (!$pasien) {
            return back()->with('error', 'Lengkapi profil pasien Anda terlebih dahulu.');
        }

        $nomor = JanjiTemu::where('jadwal_dokter_id', $request->jadwal_dokter_id)
                    ->whereDate('tanggal_booking', $request->tanggal_kunjungan)
                    ->count() + 1;

        JanjiTemu::create([
            'pasien_id'         => $pasien->id,
            'jadwal_dokter_id'  => $request->jadwal_dokter_id,
            'tanggal_booking'   => $request->tanggal_kunjungan,
            'nomor_antrian'     => $nomor,
            'keluhan'           => $request->keluhan ?? '-',
            'status'            => 'pending',
            'created_by'        => Auth::id(),
        ]);

        return redirect()->route('portal.booking.riwayat')->with('success', 'Booking berhasil! Menunggu konfirmasi dari admin.');
    }

    /** Riwayat booking pasien */
    public function riwayat()
    {
        $pasien = Auth::user()->pasien;
        if (!$pasien) return redirect()->route('portal.profil');
        
        $bookings = JanjiTemu::with(['jadwalDokter.dokter.spesialisasi'])
            ->where('pasien_id', $pasien->id)
            ->orderByDesc('tanggal_booking')
            ->paginate(10);
            
        return view('portal.booking.riwayat', compact('bookings', 'pasien'));
    }

    /** Batalkan booking */
    public function cancel(JanjiTemu $janjiTemu)
    {
        $pasien = Auth::user()->pasien;
        abort_unless($pasien && $janjiTemu->pasien_id === $pasien->id, 403);
        abort_unless(in_array($janjiTemu->status, ['pending', 'approved']), 422, 'Booking tidak dapat dibatalkan.');
        
        $janjiTemu->update([
            'status'     => 'cancelled', // DB baru: dibatalkan → cancelled
            'updated_by' => Auth::id(),
        ]);
        
        return back()->with('success', 'Booking berhasil dibatalkan.');
    }
}
