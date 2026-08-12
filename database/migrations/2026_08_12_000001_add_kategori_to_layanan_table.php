<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel kategori layanan
        if (!Schema::hasTable('kategori_layanan')) {
            Schema::create('kategori_layanan', function (Blueprint $table) {
                $table->id();
                $table->string('nama_kategori', 100);
                $table->string('icon', 50)->default('fa-stethoscope');
                $table->text('deskripsi')->nullable();
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

        // Tambah kolom ke layanan: kategori_layanan_id, slug, konten lengkap, urutan
        if (Schema::hasTable('layanan')) {
            Schema::table('layanan', function (Blueprint $table) {
                if (!Schema::hasColumn('layanan', 'kategori_layanan_id')) {
                    $table->unsignedBigInteger('kategori_layanan_id')->nullable()->after('id');
                    $table->foreign('kategori_layanan_id')->references('id')->on('kategori_layanan')->onDelete('set null');
                }
                if (!Schema::hasColumn('layanan', 'konten')) {
                    $table->longText('konten')->nullable()->after('deskripsi');
                }
                if (!Schema::hasColumn('layanan', 'urutan')) {
                    $table->smallInteger('urutan')->default(0)->after('status');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('layanan')) {
            Schema::table('layanan', function (Blueprint $table) {
                $table->dropForeign(['kategori_layanan_id']);
                $table->dropColumn(['kategori_layanan_id', 'konten', 'urutan']);
            });
        }
        Schema::dropIfExists('kategori_layanan');
    }
};
