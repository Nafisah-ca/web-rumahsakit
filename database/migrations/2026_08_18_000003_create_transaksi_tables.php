<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('transaksi')) {
            Schema::create('transaksi', function (Blueprint $table) {
                $table->id();
                $table->foreignId('janji_temu_id')->constrained('janji_temu')->onDelete('restrict')->onUpdate('cascade');
                $table->foreignId('pasien_id')->constrained('pasien')->onDelete('restrict')->onUpdate('cascade');
                $table->foreignId('penjamin_id')->nullable()->constrained('penjamin')->onDelete('set null')->onUpdate('cascade');
                $table->string('kode_transaksi', 30)->unique();
                $table->decimal('total_biaya', 12, 2)->default(0);
                $table->enum('metode_pembayaran', ['tunai', 'transfer', 'qris'])->default('tunai');
                $table->enum('status_pembayaran', ['belum_bayar', 'menunggu_verifikasi', 'lunas', 'gagal'])->default('belum_bayar');
                $table->enum('status_transaksi', ['menunggu', 'diproses', 'selesai', 'dibatalkan'])->default('menunggu');
                $table->datetime('tanggal_transaksi');
                $table->text('keterangan')->nullable();
                $table->timestamp('created_tm')->useCurrent();
                $table->timestamp('updated_tm')->useCurrent()->useCurrentOnUpdate();
                $table->timestamp('deleted_tm')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->unsignedBigInteger('deleted_by')->nullable();

                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
                $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
            });
        }

        if (!Schema::hasTable('detail_transaksi')) {
            Schema::create('detail_transaksi', function (Blueprint $table) {
                $table->id();
                $table->foreignId('transaksi_id')->constrained('transaksi')->onDelete('cascade')->onUpdate('cascade');
                $table->string('nama_biaya', 150);
                $table->unsignedInteger('qty')->default(1);
                $table->decimal('harga', 12, 2);
                $table->decimal('subtotal', 12, 2);
                $table->text('keterangan')->nullable();
                $table->timestamp('created_tm')->useCurrent();
                $table->timestamp('updated_tm')->useCurrent()->useCurrentOnUpdate();
                $table->timestamp('deleted_tm')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->unsignedBigInteger('deleted_by')->nullable();

                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
                $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi');
        Schema::dropIfExists('transaksi');
    }
};
