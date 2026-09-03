<?php

namespace App\Http\Controllers;

use App\Models\PageVisit;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    /**
     * Terima koordinat GPS dari browser geolocation API,
     * lalu simpan ke baris page_visits terbaru milik sesi ini.
     *
     * Endpoint: POST /track-location
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'city'      => 'nullable|string|max:150',
            'region'    => 'nullable|string|max:150',
            'country'   => 'nullable|string|max:100',
        ]);

        // Ambil kunjungan terbaru dari IP + user_id yang sama (maks 5 menit lalu)
        // Prioritaskan match berdasarkan user_id agar beda akun dari IP sama tetap terpisah
        $userId = auth()->id();

        $query = PageVisit::where('ip_address', $request->ip())
            ->where('visited_at', '>=', now()->subMinutes(5))
            ->orderByDesc('visited_at');

        // Kalau login, cari baris milik user tersebut spesifik
        if ($userId) {
            $visit = (clone $query)->where('user_id', $userId)->first()
                  ?? $query->whereNull('user_id')->first();
        } else {
            $visit = $query->whereNull('user_id')->first();
        }

        if ($visit) {
            $visit->update([
                'latitude'  => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'city'      => $validated['city']    ?? null,
                'region'    => $validated['region']  ?? null,
                'country'   => $validated['country'] ?? null,
            ]);
        }

        return response()->json(['ok' => true]);
    }
}
