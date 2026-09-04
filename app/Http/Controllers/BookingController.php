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

    /** API: Ambil jadwal dokter — recurring weekly, tampilkan slot mendatang */
    public function jadwal(Request $request)
    {
        $dokterId = $request->dokter_id;
        if (!$dokterId) return response()->json([]);

        // Ambil semua jadwal aktif dokter (recurring — tanggal_praktek bisa NULL)
        $jadwals = JadwalDokter::where('dokter_id', $dokterId)
            ->where('status', 'aktif')
            ->orderByRaw("FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")
            ->get();

        if ($jadwals->isEmpty()) return response()->json([]);

        $hariMap  = ['Senin'=>1,'Selasa'=>2,'Rabu'=>3,'Kamis'=>4,'Jumat'=>5,'Sabtu'=>6,'Minggu'=>0];
        $hariMapR = [0=>'Minggu',1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu'];
        $today    = now()->toDateString();
        $nowTime  = now()->format('H:i:s');

        $result = [];

        foreach ($jadwals as $j) {
            $hariInt = $hariMap[$j->hari] ?? null;
            if ($hariInt === null) continue;

            // Cari tanggal berikutnya yang sesuai hari, mulai hari ini
            // Jika jadwal memiliki tanggal_praktek spesifik, gunakan itu
            if ($j->tanggal_praktek) {
                $targetDate = $j->tanggal_praktek->toDateString();
                if ($targetDate < $today) continue; // jadwal spesifik sudah lewat
            } else {
                // Recurring: cari hari terdekat ke depan
                $now     = now();
                $todayDow = $now->dayOfWeek; // 0=Minggu
                $diff     = ($hariInt - $todayDow + 7) % 7;

                // Jika hari ini dan jam belum selesai → pakai hari ini
                // Jika hari ini tapi jam sudah selesai → minggu depan
                if ($diff === 0 && $nowTime >= $j->jam_selesai) {
                    $diff = 7;
                }

                $targetDate = now()->addDays($diff)->toDateString();
            }

            // Hitung kuota terpakai di tanggal target
            $terisi = JanjiTemu::where('jadwal_dokter_id', $j->id)
                ->whereDate('tanggal_booking', $targetDate)
                ->whereNotIn('status', ['cancelled'])
                ->count();

            $sisaKuota    = max(0, $j->kuota - $terisi);
            $sudahSelesai = ($targetDate === $today && $nowTime >= $j->jam_selesai);

            $result[] = [
                'id'            => $j->id,
                'hari'          => $j->hari,
                'tanggal'       => $targetDate,
                'tanggal_label' => \Carbon\Carbon::parse($targetDate)->translatedFormat('d M Y'),
                'jam_mulai'     => substr($j->jam_mulai, 0, 5),
                'jam_selesai'   => substr($j->jam_selesai, 0, 5),
                'kuota'         => $j->kuota,
                'sisa_kuota'    => $sisaKuota,
                'sudah_selesai' => $sudahSelesai,
                'kuota_habis'   => $sisaKuota <= 0 && !$sudahSelesai,
            ];
        }

        return response()->json($result);
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

        // Hitung nomor urut booking di tanggal yang sama (semua status termasuk soft-deleted)
        $urutHariIni = JanjiTemu::withTrashed()
            ->whereDate('tanggal_booking', $tanggal)
            ->count() + 1;

        $kodeBooking = 'RS-' . \Carbon\Carbon::parse($tanggal)->format('Ymd') . '-' . str_pad($urutHariIni, 5, '0', STR_PAD_LEFT);

        JanjiTemu::create([
            'pasien_id'         => $pasien->id,
            'jadwal_dokter_id'  => $jadwal->id,
            'tanggal_booking'   => $tanggal,
            'nomor_antrian'     => $nomor,
            'keluhan'           => $request->keluhan ?? '-',
            'status'            => 'pending',
            'kode_booking'      => $kodeBooking,
            'created_by'        => Auth::id(),
        ]);

        return redirect()->route('portal.booking.riwayat')->with('success', 'Booking berhasil! Menunggu konfirmasi dari admin.');
    }

    /** Riwayat booking pasien */
    public function riwayat()
    {
        $pasien = Auth::user()->pasien;
        if (!$pasien) return redirect()->route('portal.profil');
        
        $bookings = JanjiTemu::with([
                'jadwalDokter' => fn($q) => $q->withTrashed(),
                'jadwalDokter.dokter',
                'jadwalDokter.dokter.spesialisasi',
            ])
            ->where('pasien_id', $pasien->id)
            ->orderByDesc('tanggal_booking')
            ->paginate(10);
            
        return view('portal.booking.riwayat', compact('bookings', 'pasien'));
    }

    /** Batalkan booking (pasien) — simpan alasan, jangan hapus */
    public function cancel(Request $request, JanjiTemu $janjiTemu)
    {
        $pasien = Auth::user()->pasien;
        abort_unless($pasien && $janjiTemu->pasien_id === $pasien->id, 403);
        abort_unless(in_array($janjiTemu->status, ['pending', 'approved']), 422, 'Booking tidak dapat dibatalkan.');

        $request->validate([
            'alasan_pembatalan' => 'required|string|min:3|max:500',
        ], [
            'alasan_pembatalan.required' => 'Alasan pembatalan wajib diisi.',
            'alasan_pembatalan.min'      => 'Alasan minimal 3 karakter.',
        ]);

        $janjiTemu->update([
            'status'             => 'cancelled',
            'alasan_pembatalan'  => $request->alasan_pembatalan,
            'tanggal_pembatalan' => now(),
            'dibatalkan_oleh'    => 'pasien',
            'updated_by'         => Auth::id(),
        ]);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }
}
