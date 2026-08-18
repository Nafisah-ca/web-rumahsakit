<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Spesialisasi;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AntrianPoliController extends Controller
{
    /**
     * Halaman pengaturan Live Antrian di CMS.
     * CMS dapat mengubah estimasi waktu tunggu per poli, interval refresh, dan pesan tunggu.
     */
    public function index()
    {
        $spesialisasis = Spesialisasi::orderBy('nama_spesialis')->get();
        $setting       = WebsiteSetting::first() ?? new WebsiteSetting();
        $estimasi      = json_decode($setting->estimasi_antrian ?? '{}', true) ?? [];

        return view('cms.antrian-poli.index', compact('spesialisasis', 'setting', 'estimasi'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'interval_refresh' => 'required|integer|min:5|max:300',
            'pesan_tunggu'     => 'nullable|string|max:200',
            'estimasi.*'       => 'nullable|integer|min:1|max:300',
        ]);

        // Simpan estimasi_menit per spesialisasi langsung ke tabel spesialis
        if ($request->has('estimasi') && is_array($request->estimasi)) {
            foreach ($request->estimasi as $spId => $menit) {
                Spesialisasi::where('id', (int) $spId)->update([
                    'estimasi_menit' => (int) $menit,
                    'updated_by'     => Auth::id(),
                ]);
            }
        }

        // Simpan setting global antrian ke website_setting
        $setting = WebsiteSetting::first() ?? new WebsiteSetting();
        $payload = [
            'estimasi_antrian' => json_encode([
                'interval_refresh' => (int) $request->interval_refresh,
                'pesan_tunggu'     => $request->pesan_tunggu ?? 'Harap menunggu, nomor Anda akan segera dipanggil.',
            ]),
            'updated_by' => Auth::id(),
        ];
        if (!$setting->exists) {
            $payload['created_by'] = Auth::id();
        }
        $setting->fill($payload)->save();

        return redirect()->route('cms.antrian-poli')->with('success', 'Pengaturan antrian poli berhasil diperbarui.');
    }
}
