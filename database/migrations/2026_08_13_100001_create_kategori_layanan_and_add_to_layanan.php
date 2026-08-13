<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel kategori_layanan
        if (!Schema::hasTable('kategori_layanan')) {
            Schema::create('kategori_layanan', function (Blueprint $table) {
                $table->id();
                $table->string('nama_kategori', 100);
                $table->string('icon', 50)->nullable()->default('fa-hospital');
                $table->text('deskripsi')->nullable();
                $table->integer('urutan')->default(0);
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
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

        // 2. Tambah kolom kategori_layanan_id ke tabel layanan (nullable agar data lama tidak rusak)
        if (Schema::hasTable('layanan') && !Schema::hasColumn('layanan', 'kategori_layanan_id')) {
            Schema::table('layanan', function (Blueprint $table) {
                $table->unsignedBigInteger('kategori_layanan_id')
                      ->nullable()
                      ->after('id');
                $table->foreign('kategori_layanan_id')
                      ->references('id')
                      ->on('kategori_layanan')
                      ->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('layanan') && Schema::hasColumn('layanan', 'kategori_layanan_id')) {
            Schema::table('layanan', function (Blueprint $table) {
                $table->dropForeign(['kategori_layanan_id']);
                $table->dropColumn('kategori_layanan_id');
            });
        }
        Schema::dropIfExists('kategori_layanan');
    }
};
