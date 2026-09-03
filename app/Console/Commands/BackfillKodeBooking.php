<?php

namespace App\Console\Commands;

use App\Models\JanjiTemu;
use Illuminate\Console\Command;

class BackfillKodeBooking extends Command
{
    protected $signature   = 'booking:backfill-kode';
    protected $description = 'Isi kolom kode_booking untuk semua data janji_temu yang masih NULL';

    public function handle(): int
    {
        // Ambil semua record yang kode_booking masih NULL, termasuk soft-deleted
        $records = JanjiTemu::withTrashed()
            ->whereNull('kode_booking')
            ->orderBy('tanggal_booking')
            ->orderBy('id')
            ->get();

        if ($records->isEmpty()) {
            $this->info('Semua data sudah punya kode_booking. Tidak ada yang perlu diupdate.');
            return self::SUCCESS;
        }

        $this->info("Ditemukan {$records->count()} data yang perlu diisi kode_booking...");

        // Kelompokkan per tanggal untuk hitung nomor urut yang benar
        $counterPerTanggal = [];

        $bar = $this->output->createProgressBar($records->count());
        $bar->start();

        foreach ($records as $record) {
            $tanggal = $record->tanggal_booking?->format('Ymd') ?? now()->format('Ymd');

            // Hitung berapa yang sudah punya kode di tanggal yang sama (DB maupun sudah di-set di iterasi ini)
            if (!isset($counterPerTanggal[$tanggal])) {
                // Ambil count yang sudah punya kode_booking di tanggal itu dari DB
                $counterPerTanggal[$tanggal] = JanjiTemu::withTrashed()
                    ->whereDate('tanggal_booking', $record->tanggal_booking)
                    ->whereNotNull('kode_booking')
                    ->count();
            }

            $counterPerTanggal[$tanggal]++;
            $urut = $counterPerTanggal[$tanggal];

            $kode = 'RS-' . $tanggal . '-' . str_pad($urut, 5, '0', STR_PAD_LEFT);

            // Update langsung ke DB tanpa trigger event/observer, hindari konflik unique
            JanjiTemu::withTrashed()
                ->where('id', $record->id)
                ->update(['kode_booking' => $kode]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Selesai! Semua kode_booking sudah terisi.');

        return self::SUCCESS;
    }
}
