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

        // Dapatkan nama hari dari tanggal yang dipilih — pakai mapping integer agar konsisten
        $hariMap     = [0=>'Minggu',1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu'];
        $hariPilihan = $hariMap[\Carbon\Carbon::parse($tanggal)->dayOfWeek];

        // Ambil jadwal aktif dokter yang harinya sesuai tanggal dipilih
        $jadwals = JadwalDokter::where('dokter_id', $request->dokter_id)
            ->where('status', 'aktif')
            ->where('hari', $hariPilihan)
            ->get()
            ->map(function ($j) use ($tanggal) {
                $terisi = JanjiTemu::where('jadwal_dokter_id', $j->id)
                    ->whereDate('tanggal_booking', $tanggal)
                    ->whereNotIn('status', ['cancelled'])
                    ->count();

                // Cek apakah jadwal sudah lewat untuk tanggal yang dipilih
                $today    = now()->toDateString();
                $nowTime  = now()->format('H:i:s');

                $sudahSelesai = false;
                // Jadwal selesai hanya jika tanggal YANG DIPILIH pasien sudah lewat,
                // atau tanggal yang dipilih = hari ini DAN jam_selesai sudah lewat.
                // tanggal_praktek di DB tidak dipakai untuk cek ini karena satu jadwal
                // bisa berlaku berulang di hari yang sama setiap minggu.
                if ($tanggal < $today) {
                    $sudahSelesai = true;
                } elseif ($tanggal === $today && $nowTime >= $j->jam_selesai) {
                    $sudahSelesai = true;
                }

                return [
                    'id'            => $j->id,
                    'hari'          => $j->hari,
                    'hari_label'    => $j->hari,
                    'jam_mulai'     => substr($j->jam_mulai, 0, 5),
                    'jam_selesai'   => substr($j->jam_selesai, 0, 5),
                    'kuota'         => $j->kuota,
                    'sisa_kuota'    => max(0, $j->kuota - $terisi),
                    'sudah_selesai' => $sudahSelesai,
                ];
            });

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

        // Cek jadwal masih aktif
        $jadwal = JadwalDokter::where('id', $request->jadwal_dokter_id)
            ->where('status', 'aktif')
            ->first();

        if (!$jadwal) {
            return back()->withInput()->with('error', 'Jadwal dokter tidak ditemukan atau sudah tidak aktif.');
        }

        // Cek apakah jam praktek hari ini sudah selesai
        $tanggal = $request->tanggal_kunjungan;
        $today   = now()->toDateString();
        $nowTime = now()->format('H:i:s');

        if ($tanggal === $today && $nowTime >= $jadwal->jam_selesai) {
            return back()->withInput()->with('error',
                'Pendaftaran tidak dapat dilakukan. Jam praktik dr. ' .
                $jadwal->dokter?->nama_dokter .
                ' (' . substr($jadwal->jam_mulai, 0, 5) . '–' . substr($jadwal->jam_selesai, 0, 5) . ') sudah selesai hari ini.'
            );
        }

        // Cek kuota
        $terisi = JanjiTemu::where('jadwal_dokter_id', $jadwal->id)
            ->whereDate('tanggal_booking', $tanggal)
            ->whereNotIn('status', ['cancelled'])
            ->count();

        if ($terisi >= $jadwal->kuota) {
            return back()->withInput()->with('error',
                'Kuota jadwal dr. ' . $jadwal->dokter?->nama_dokter . ' untuk tanggal ini sudah penuh.'
            );
        }

        // Hitung nomor antrian: max dari nomor yang sudah ada hari itu + 1
        // (bukan count, agar tidak duplikat jika ada yang dibatalkan di tengah)
        $maxNomor = JanjiTemu::where('jadwal_dokter_id', $jadwal->id)
            ->whereDate('tanggal_booking', $tanggal)
            ->max('nomor_antrian') ?? 0;

        $nomor = $maxNomor + 1;

        JanjiTemu::create([
            'pasien_id'         => $pasien->id,
            'jadwal_dokter_id'  => $jadwal->id,
            'tanggal_booking'   => $tanggal,
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
