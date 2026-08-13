<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom yang mungkin belum ada di kategori_layanan
        Schema::table('kategori_layanan', function (Blueprint $table) {
            if (!Schema::hasColumn('kategori_layanan', 'icon')) {
                $table->string('icon', 50)->nullable()->default('fa-hospital')->after('nama_kategori');
            }
            if (!Schema::hasColumn('kategori_layanan', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('icon');
            }
            if (!Schema::hasColumn('kategori_layanan', 'urutan')) {
                $table->integer('urutan')->default(0)->after('deskripsi');
            }
            if (!Schema::hasColumn('kategori_layanan', 'status')) {
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('urutan');
            }
            if (!Schema::hasColumn('kategori_layanan', 'deleted_tm')) {
                $table->timestamp('deleted_tm')->nullable()->after('updated_tm');
            }
            if (!Schema::hasColumn('kategori_layanan', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('kategori_layanan', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('kategori_layanan', 'deleted_by')) {
                $table->unsignedBigInteger('deleted_by')->nullable();
                $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kategori_layanan', function (Blueprint $table) {
            $table->dropColumn(array_filter([
                Schema::hasColumn('kategori_layanan', 'urutan')   ? 'urutan'   : null,
                Schema::hasColumn('kategori_layanan', 'icon')     ? 'icon'     : null,
                Schema::hasColumn('kategori_layanan', 'deskripsi')? 'deskripsi': null,
            ]));
        });
    }
};
