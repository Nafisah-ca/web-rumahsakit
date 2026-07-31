<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penjamin;
use App\Models\TipePenjamin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenjaminController extends Controller
{
    // ─── TIPE PENJAMIN ─────────────────────────────────────────

    public function tipePenjamin()
    {
        $tipes = TipePenjamin::withCount('penjamins')->orderBy('nama_tipe')->get();
        return view('admin.penjamin.tipe', compact('tipes'));
    }

    public function storeTipePenjamin(Request $request)
    {
        $request->validate(['nama_tipe' => 'required|string|max:100|unique:tipe_penjamin,nama_tipe']);
        TipePenjamin::create(['nama_tipe' => $request->nama_tipe, 'status' => 'aktif', 'created_by' => Auth::id()]);
        return back()->with('success', 'Tipe penjamin berhasil ditambahkan.');
    }

    public function destroyTipePenjamin(TipePenjamin $tipePenjamin)
    {
        if ($tipePenjamin->penjamins()->count()) {
            return back()->with('error', 'Tidak bisa hapus — masih ada penjamin yang menggunakan tipe ini.');
        }
        $tipePenjamin->update(['deleted_by' => Auth::id()]);
        $tipePenjamin->delete();
        return back()->with('success', 'Tipe penjamin berhasil dihapus.');
    }

    // ─── PENJAMIN ──────────────────────────────────────────────

    public function index(Request $request)
    {
        $penjamins = Penjamin::with('tipePenjamin')
            ->when($request->tipe_id, fn($q) => $q->where('tipe_penjamin_id', $request->tipe_id))
            ->when($request->search, fn($q) => $q->where('nama_penjamin', 'like', "%{$request->search}%"))
            ->orderBy('nama_penjamin')->paginate(20)->withQueryString();
        $tipes = TipePenjamin::orderBy('nama_tipe')->get();
        return view('admin.penjamin.index', compact('penjamins', 'tipes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_penjamin'    => 'required|string|max:100',
            'tipe_penjamin_id' => 'required|exists:tipe_penjamin,id',
        ]);
        Penjamin::create([
            'nama_penjamin'    => $request->nama_penjamin,
            'tipe_penjamin_id' => $request->tipe_penjamin_id,
            'status'           => $request->status ?? 'aktif',
            'created_by'       => Auth::id(),
        ]);
        return back()->with('success', 'Penjamin berhasil ditambahkan.');
    }

    public function update(Request $request, Penjamin $penjamin)
    {
        $request->validate([
            'nama_penjamin'    => 'required|string|max:100',
            'tipe_penjamin_id' => 'required|exists:tipe_penjamin,id',
        ]);
        $penjamin->update([
            'nama_penjamin'    => $request->nama_penjamin,
            'tipe_penjamin_id' => $request->tipe_penjamin_id,
            'status'           => $request->status ?? 'aktif',
            'updated_by'       => Auth::id(),
        ]);
        return back()->with('success', 'Penjamin berhasil diperbarui.');
    }

    public function destroy(Penjamin $penjamin)
    {
        $penjamin->update(['deleted_by' => Auth::id()]);
        $penjamin->delete();
        return back()->with('success', 'Penjamin berhasil dihapus.');
    }
}
