<?php

namespace App\Http\Controllers;

use App\Models\BookingEvent;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventBookingController extends Controller
{
    /** Halaman konfirmasi booking event */
    public function create(Event $event)
    {
        $pasien = Auth::user()->pasien;

        if (!$pasien) {
            return redirect()->route('portal.profil')
                ->with('error', 'Lengkapi profil pasien Anda terlebih dahulu.');
        }

        // Event sudah lewat
        if ($event->tanggal_event->isPast()) {
            return redirect()->route('event.detail', $event)
                ->with('error', 'Event ini sudah berlangsung, pendaftaran ditutup.');
        }

        // Event nonaktif
        if ($event->status !== 'aktif') {
            abort(404);
        }

        // Kuota penuh
        if ($event->kuota_penuh) {
            return redirect()->route('event.detail', $event)
                ->with('error', 'Maaf, kuota event ini sudah penuh.');
        }

        // Sudah pernah booking
        $existing = BookingEvent::where('event_id', $event->id)
            ->where('pasien_id', $pasien->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        return view('portal.booking-event.form', compact('event', 'pasien', 'existing'));
    }

    /** Simpan booking event */
    public function store(Request $request, Event $event)
    {
        $pasien = Auth::user()->pasien;

        if (!$pasien) {
            return redirect()->route('portal.profil')
                ->with('error', 'Lengkapi profil pasien Anda terlebih dahulu.');
        }

        if ($event->tanggal_event->isPast() || $event->status !== 'aktif') {
            return redirect()->route('event.detail', $event)
                ->with('error', 'Event tidak dapat diikuti saat ini.');
        }

        // Cek kuota
        if ($event->kuota_penuh) {
            return redirect()->route('event.detail', $event)
                ->with('error', 'Maaf, kuota event ini sudah penuh.');
        }

        // Cek sudah booking
        $exists = BookingEvent::where('event_id', $event->id)
            ->where('pasien_id', $pasien->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($exists) {
            return redirect()->route('portal.booking-event.riwayat')
                ->with('info', 'Anda sudah terdaftar di event ini.');
        }

        $request->validate([
            'catatan' => 'nullable|string|max:500',
        ]);

        BookingEvent::create([
            'event_id'     => $event->id,
            'pasien_id'    => $pasien->id,
            'kode_booking' => BookingEvent::generateKode(),
            'status'       => 'pending',
            'catatan'      => $request->catatan,
            'created_by'   => Auth::id(),
        ]);

        return redirect()->route('portal.booking-event.riwayat')
            ->with('success', 'Berhasil mendaftar ke event! Menunggu konfirmasi dari panitia.');
    }

    /** Riwayat booking event pasien */
    public function riwayat()
    {
        $pasien = Auth::user()->pasien;
        if (!$pasien) return redirect()->route('portal.profil');

        $bookings = BookingEvent::with('event')
            ->where('pasien_id', $pasien->id)
            ->orderByDesc('created_tm')
            ->paginate(10);

        return view('portal.booking-event.riwayat', compact('bookings', 'pasien'));
    }

    /** Batalkan booking event */
    public function cancel(BookingEvent $bookingEvent)
    {
        $pasien = Auth::user()->pasien;
        abort_unless($pasien && $bookingEvent->pasien_id === $pasien->id, 403);
        abort_unless(in_array($bookingEvent->status, ['pending', 'confirmed']), 422, 'Booking tidak dapat dibatalkan.');

        $bookingEvent->update([
            'status'     => 'cancelled',
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', 'Pendaftaran event berhasil dibatalkan.');
    }
}
