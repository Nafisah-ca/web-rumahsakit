<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Http\Resources\DokterResource;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\JanjiTemu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Daftar dokter aktif untuk pilihan booking.
     * GET /api/dokter
     */
    public function dokterList()
    {
        $dokters = Dokter::with('spesialisasi')
            ->where('status', 'aktif')
            ->orderBy('nama_dokter')
            ->get();

        return DokterResource::collection($dokters);
    }

    /**
     * Jadwal dokter pada tanggal tertentu + sisa kuota.
     * GET /api/booking/jadwal?dokter_id=&tanggal=
     */
    public function jadwal(Request $request)
    {
        $request->validate([
            'dokter_id' => 'required|exists:dokter,id',
            'tanggal'   => 'nullable|date',
        ]);

        $tanggal = $request->tanggal ?: today()->toDateString();

        $hariMap     = [0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'];
        $hariPilihan = $hariMap[\Carbon\Carbon::parse($tanggal)->dayOfWeek];

        // Ambil jadwal aktif dokter yang sesuai dengan tanggal dipilih
        $jadwals = JadwalDokter::where('dokter_id', $request->dokter_id)
            ->where('status', 'aktif')
            ->where(function ($q) use ($tanggal, $hariPilihan) {
                $q->whereDate('tanggal_praktek', $tanggal)
                  ->orWhere(function ($sub) use ($hariPilihan) {
                      $sub->whereNull('tanggal_praktek')->where('hari', $hariPilihan);
                  });
            })
            ->get()
            ->map(function ($j) use ($tanggal) {
                $terisi = JanjiTemu::where('jadwal_dokter_id', $j->id)
                    ->whereDate('tanggal_booking', $tanggal)
                    ->whereNotIn('status', ['cancelled'])
                    ->count();

                $today   = now()->toDateString();
                $nowTime = now()->format('H:i:s');

                $sudahSelesai = false;
                if ($tanggal < $today) {
                    $sudahSelesai = true;
                } elseif ($tanggal === $today && $nowTime >= $j->jam_selesai) {
                    $sudahSelesai = true;
                }

                return [
                    'id'            => $j->id,
                    'hari'          => $j->hari,
                    'jam_mulai'     => substr($j->jam_mulai, 0, 5),
                    'jam_selesai'   => substr($j->jam_selesai, 0, 5),
                    'kuota'         => $j->kuota,
                    'sisa_kuota'    => max(0, $j->kuota - $terisi),
                    'sudah_selesai' => $sudahSelesai,
                ];
            });

        return response()->json(['data' => $jadwals]);
    }

    /**
     * Buat booking baru.
     * POST /api/booking
     */
    public function store(Request $request)
    {
        $request->validate([
            'jadwal_dokter_id'  => 'required|exists:jadwal_dokter,id',
            'tanggal_kunjungan' => 'required|date|after_or_equal:today',
            'keluhan'           => 'required|string|min:3|max:1000',
        ], [
            'jadwal_dokter_id.required'  => 'Pilih jadwal dokter terlebih dahulu.',
            'tanggal_kunjungan.required' => 'Tanggal kunjungan wajib diisi.',
            'keluhan.required'           => 'Keluhan wajib diisi.',
            'keluhan.min'                => 'Keluhan minimal 3 karakter.',
        ]);

        $pasien = Auth::user()->pasien;
        if (!$pasien) {
            return response()->json([
                'message' => 'Lengkapi profil pasien Anda terlebih dahulu.',
            ], 422);
        }

        $jadwal = JadwalDokter::where('id', $request->jadwal_dokter_id)
            ->where('status', 'aktif')
            ->first();

        if (!$jadwal) {
            return response()->json([
                'message' => 'Jadwal dokter tidak ditemukan atau sudah tidak aktif.',
            ], 422);
        }

        $tanggal = $request->tanggal_kunjungan;
        $today   = now()->toDateString();
        $nowTime = now()->format('H:i:s');

        if ($tanggal === $today && $nowTime >= $jadwal->jam_selesai) {
            return response()->json([
                'message' => 'Pendaftaran tidak dapat dilakukan. Jam praktik dr. ' .
                    $jadwal->dokter?->nama_dokter .
                    ' (' . substr($jadwal->jam_mulai, 0, 5) . '–' . substr($jadwal->jam_selesai, 0, 5) . ') sudah selesai hari ini.',
            ], 422);
        }

        $terisi = JanjiTemu::where('jadwal_dokter_id', $jadwal->id)
            ->whereDate('tanggal_booking', $tanggal)
            ->whereNotIn('status', ['cancelled'])
            ->count();

        if ($terisi >= $jadwal->kuota) {
            return response()->json([
                'message' => 'Kuota jadwal dr. ' . $jadwal->dokter?->nama_dokter . ' untuk tanggal ini sudah penuh.',
            ], 422);
        }

        $nomor = $terisi + 1;

        $booking = JanjiTemu::create([
            'pasien_id'        => $pasien->id,
            'jadwal_dokter_id' => $jadwal->id,
            'tanggal_booking'  => $tanggal,
            'nomor_antrian'    => $nomor,
            'keluhan'          => $request->keluhan ?? '-',
            'status'           => 'pending',
            'created_by'       => Auth::id(),
        ]);

        $booking->load('jadwalDokter.dokter.spesialisasi');

        return response()->json([
            'message' => 'Booking berhasil! Menunggu konfirmasi dari admin.',
            'data'    => new BookingResource($booking),
        ], 201);
    }

    /**
     * Riwayat booking milik pasien yang sedang login.
     * GET /api/booking
     */
    public function riwayat()
    {
        $pasien = Auth::user()->pasien;
        if (!$pasien) {
            return response()->json(['message' => 'Profil pasien belum lengkap.'], 422);
        }

        $bookings = JanjiTemu::with(['jadwalDokter.dokter.spesialisasi'])
            ->where('pasien_id', $pasien->id)
            ->orderByDesc('tanggal_booking')
            ->paginate(10);

        return BookingResource::collection($bookings);
    }

    /**
     * Batalkan booking milik sendiri.
     * POST /api/booking/{janjiTemu}/batal
     */
    public function cancel(JanjiTemu $janjiTemu)
    {
        $pasien = Auth::user()->pasien;
        abort_unless($pasien && $janjiTemu->pasien_id === $pasien->id, 403, 'Anda tidak berhak membatalkan booking ini.');
        abort_unless(in_array($janjiTemu->status, ['pending', 'approved']), 422, 'Booking tidak dapat dibatalkan.');

        $janjiTemu->update([
            'status'     => 'cancelled',
            'updated_by' => Auth::id(),
        ]);

        return response()->json(['message' => 'Booking berhasil dibatalkan.']);
    }
}
