<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom kuota ke tabel event
        if (!Schema::hasColumn('event', 'kuota')) {
            Schema::table('event', function (Blueprint $table) {
                $table->unsignedSmallInteger('kuota')->nullable()->after('waktu_event')
                      ->comment('Maks peserta, null = tak terbatas');
            });
        }

        // Tabel booking peserta event
        if (!Schema::hasTable('booking_event')) {
            Schema::create('booking_event', function (Blueprint $table) {
                $table->id();
                $table->foreignId('event_id')->constrained('event')->onDelete('cascade');
                $table->foreignId('pasien_id')->constrained('pasien')->onDelete('cascade');
                $table->string('kode_booking', 20)->unique();
                $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
                $table->text('catatan')->nullable();
                $table->timestamp('created_tm')->useCurrent();
                $table->timestamp('updated_tm')->useCurrent()->useCurrentOnUpdate();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();

                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

                // Satu pasien hanya bisa booking satu kali per event
                $table->unique(['event_id', 'pasien_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_event');

        if (Schema::hasColumn('event', 'kuota')) {
            Schema::table('event', function (Blueprint $table) {
                $table->dropColumn('kuota');
            });
        }
    }
};
