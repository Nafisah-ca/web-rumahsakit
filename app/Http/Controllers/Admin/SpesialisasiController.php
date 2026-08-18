<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Spesialisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpesialisasiController extends Controller
{
    public function index(Request $request)
    {
        $spesialisasis = Spesialisasi::when(
                $request->search,
                fn ($q) => $q->where('nama_spesialis', 'like', "%{$request->search}%")
            )
            ->orderBy('nama_spesialis')
            ->paginate(20)
            ->withQueryString();

        return view('admin.spesialisasi.index', compact('spesialisasis'));
    }

    public function create()
    {
        return view('admin.spesialisasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_spesialis'  => 'required|string|max:100',
            'deskripsi'       => 'nullable|string',
            'icon'            => 'nullable|string|max:60',
            'warna'           => 'nullable|string|max:30',
            'estimasi_menit'  => 'nullable|integer|min:1|max:300',
        ]);

        Spesialisasi::create([
            'nama_spesialis' => $request->nama_spesialis,
            'deskripsi'      => $request->deskripsi,
            'icon'           => $request->icon           ?? 'fa-stethoscope',
            'warna'          => $request->warna          ?? 'blue',
            'estimasi_menit' => $request->estimasi_menit ?? 15,
            'created_by'     => Auth::id(),
        ]);

        return redirect()->route('admin.spesialisasi')->with('success', 'Spesialisasi berhasil disimpan.');
    }

    public function edit(Spesialisasi $spesialisasi)
    {
        return view('admin.spesialisasi.edit', compact('spesialisasi'));
    }

    public function update(Request $request, Spesialisasi $spesialisasi)
    {
        $request->validate([
            'nama_spesialis'  => 'required|string|max:100',
            'deskripsi'       => 'nullable|string',
            'icon'            => 'nullable|string|max:60',
            'warna'           => 'nullable|string|max:30',
            'estimasi_menit'  => 'nullable|integer|min:1|max:300',
        ]);

        $spesialisasi->update([
            'nama_spesialis' => $request->nama_spesialis,
            'deskripsi'      => $request->deskripsi,
            'icon'           => $request->icon           ?? 'fa-stethoscope',
            'warna'          => $request->warna          ?? 'blue',
            'estimasi_menit' => $request->estimasi_menit ?? 15,
            'updated_by'     => Auth::id(),
        ]);

        return redirect()->route('admin.spesialisasi')->with('success', 'Spesialisasi berhasil diperbarui.');
    }

    public function destroy(Spesialisasi $spesialisasi)
    {
        $spesialisasi->update(['deleted_by' => Auth::id()]);
        $spesialisasi->delete();
        return back()->with('success', 'Spesialisasi berhasil dihapus.');
    }
}
