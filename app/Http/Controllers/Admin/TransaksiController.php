<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\JanjiTemu;
use App\Models\Pasien;
use App\Models\Penjamin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    // ─────────────── LIST ───────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Transaksi::with(['pasien.user', 'penjamin', 'janjiTemu.jadwalDokter.dokter']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_transaksi', 'like', "%{$request->search}%")
                  ->orWhereHas('pasien.user', fn($u) => $u->where('nama', 'like', "%{$request->search}%"));
            });
        }
        if ($request->status_pembayaran)  $query->where('status_pembayaran', $request->status_pembayaran);
        if ($request->status_transaksi)   $query->where('status_transaksi', $request->status_transaksi);
        if ($request->tanggal)            $query->whereDate('tanggal_transaksi', $request->tanggal);

        $transaksis = $query->orderByDesc('tanggal_transaksi')->paginate(15)->withQueryString();

        // Summary stats
        $stats = [
            'total'          => Transaksi::count(),
            'lunas'          => Transaksi::where('status_pembayaran', 'lunas')->count(),
            'belum_bayar'    => Transaksi::where('status_pembayaran', 'belum_bayar')->count(),
            'total_pendapatan'=> Transaksi::where('status_pembayaran', 'lunas')->sum('total_biaya'),
        ];

        return view('admin.transaksi.index', compact('transaksis', 'stats'));
    }

    // ─────────────── BUAT TRANSAKSI DARI JANJI TEMU ─────────────────

    public function create(Request $request)
    {
        // Hanya dari janji temu approved atau completed
        $janjiTemuList = JanjiTemu::with(['pasien.user', 'jadwalDokter.dokter'])
            ->whereIn('status', ['approved', 'completed'])
            ->whereDoesntHave('transaksi')
            ->orderByDesc('tanggal_booking')
            ->get();

        $penjamins = Penjamin::where('status', 'aktif')->with('tipePenjamin')->get();

        $selectedJanji = null;
        if ($request->janji_temu_id) {
            $selectedJanji = JanjiTemu::with(['pasien.user','pasien.penjamin','jadwalDokter.dokter'])->find($request->janji_temu_id);
        }

        return view('admin.transaksi.create', compact('janjiTemuList', 'penjamins', 'selectedJanji'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'janji_temu_id'      => 'required|exists:janji_temu,id',
            'metode_pembayaran'  => 'required|in:tunai,transfer,qris',
            'status_pembayaran'  => 'required|in:belum_bayar,menunggu_verifikasi,lunas,gagal',
            'status_transaksi'   => 'required|in:menunggu,diproses,selesai,dibatalkan',
            'tanggal_transaksi'  => 'required|date',
            'nama_biaya'         => 'required|array|min:1',
            'nama_biaya.*'       => 'required|string|max:150',
            'qty.*'              => 'required|integer|min:1',
            'harga.*'            => 'required|numeric|min:0',
            'penjamin_id'        => 'nullable|exists:penjamin,id',
        ]);

        $janji  = JanjiTemu::with('pasien')->findOrFail($request->janji_temu_id);
        $kode   = 'TRX-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        // Hitung total
        $total = 0;
        $items = [];
        foreach ($request->nama_biaya as $i => $nama) {
            $qty      = $request->qty[$i] ?? 1;
            $harga    = $request->harga[$i] ?? 0;
            $subtotal = $qty * $harga;
            $total   += $subtotal;
            $items[]  = compact('nama', 'qty', 'harga', 'subtotal');
        }

        DB::beginTransaction();
        try {
            $trx = Transaksi::create([
                'janji_temu_id'     => $janji->id,
                'pasien_id'         => $janji->pasien_id,
                'penjamin_id'       => $request->penjamin_id ?: null,
                'kode_transaksi'    => $kode,
                'total_biaya'       => $total,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status_pembayaran' => $request->status_pembayaran,
                'status_transaksi'  => $request->status_transaksi,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'keterangan'        => $request->keterangan,
                'created_by'        => Auth::id(),
            ]);

            foreach ($items as $item) {
                DetailTransaksi::create([
                    'transaksi_id' => $trx->id,
                    'nama_biaya'   => $item['nama'],
                    'qty'          => $item['qty'],
                    'harga'        => $item['harga'],
                    'subtotal'     => $item['subtotal'],
                    'created_by'   => Auth::id(),
                ]);
            }

            // Update status janji temu ke completed jika transaksi lunas
            if ($request->status_pembayaran === 'lunas') {
                $janji->update(['status' => 'completed', 'updated_by' => Auth::id()]);
            }

            DB::commit();
            return redirect()->route('admin.transaksi.show', $trx)->with('success', "Transaksi $kode berhasil dibuat.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }

    // ─────────────── DETAIL ─────────────────────────────────────────

    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['pasien.user', 'penjamin.tipePenjamin', 'janjiTemu.jadwalDokter.dokter.spesialisasi', 'detailTransaksis']);
        return view('admin.transaksi.show', compact('transaksi'));
    }

    // ─────────────── UPDATE STATUS ──────────────────────────────────

    public function updateStatus(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:belum_bayar,menunggu_verifikasi,lunas,gagal',
            'status_transaksi'  => 'required|in:menunggu,diproses,selesai,dibatalkan',
        ]);

        $transaksi->update([
            'status_pembayaran' => $request->status_pembayaran,
            'status_transaksi'  => $request->status_transaksi,
            'keterangan'        => $request->keterangan ?? $transaksi->keterangan,
            'updated_by'        => Auth::id(),
        ]);

        // Sinkronisasi status janji temu
        if ($request->status_pembayaran === 'lunas') {
            $transaksi->janjiTemu?->update(['status' => 'completed', 'updated_by' => Auth::id()]);
        }

        return back()->with('success', 'Status transaksi berhasil diperbarui.');
    }

    // ─────────────── HAPUS ──────────────────────────────────────────

    public function destroy(Transaksi $transaksi)
    {
        $transaksi->update(['deleted_by' => Auth::id()]);
        $transaksi->detailTransaksis()->each(fn($d) => $d->delete());
        $transaksi->delete();
        return redirect()->route('admin.transaksi')->with('success', 'Transaksi berhasil dihapus.');
    }
}
