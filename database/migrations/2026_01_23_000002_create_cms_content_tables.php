<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Kategori Artikel
        if (!Schema::hasTable('kategori_artikel')) {
            Schema::create('kategori_artikel', function (Blueprint $table) {
                $table->id();
                $table->string('nama_kategori', 100);
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

        // Artikel
        if (!Schema::hasTable('artikel')) {
            Schema::create('artikel', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kategori_artikel_id')->constrained('kategori_artikel')->onDelete('restrict')->onUpdate('cascade');
                $table->string('judul', 200);
                $table->string('slug')->unique();
                $table->string('thumbnail')->nullable();
                $table->string('gambar')->nullable();
                $table->longText('isi');
                $table->enum('status', ['draft', 'publish'])->default('draft');
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

        // Banner
        if (!Schema::hasTable('banner')) {
            Schema::create('banner', function (Blueprint $table) {
                $table->id();
                $table->string('judul');
                $table->string('gambar');
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

        // Kategori Galeri
        if (!Schema::hasTable('kategori_galeri')) {
            Schema::create('kategori_galeri', function (Blueprint $table) {
                $table->id();
                $table->string('nama_kategori', 100);
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

        // Galeri
        if (!Schema::hasTable('galeri')) {
            Schema::create('galeri', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kategori_galeri_id')->constrained('kategori_galeri')->onDelete('restrict')->onUpdate('cascade');
                $table->string('judul', 150);
                $table->string('gambar');
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

        // Layanan
        if (!Schema::hasTable('layanan')) {
            Schema::create('layanan', function (Blueprint $table) {
                $table->id();
                $table->string('nama_layanan');
                $table->text('deskripsi')->nullable();
                $table->string('gambar')->nullable();
                $table->string('icon')->nullable();
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

        // Event
        if (!Schema::hasTable('event')) {
            Schema::create('event', function (Blueprint $table) {
                $table->id();
                $table->string('judul', 200);
                $table->string('thumbnail')->nullable();
                $table->string('gambar')->nullable();
                $table->longText('deskripsi');
                $table->string('lokasi')->nullable();
                $table->date('tanggal_event');
                $table->time('waktu_event');
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

        // Promo
        if (!Schema::hasTable('promo')) {
            Schema::create('promo', function (Blueprint $table) {
                $table->id();
                $table->string('judul', 200);
                $table->string('thumbnail')->nullable();
                $table->string('gambar')->nullable();
                $table->longText('deskripsi');
                $table->date('tanggal_mulai');
                $table->date('tanggal_selesai');
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo');
        Schema::dropIfExists('event');
        Schema::dropIfExists('layanan');
        Schema::dropIfExists('galeri');
        Schema::dropIfExists('kategori_galeri');
        Schema::dropIfExists('banner');
        Schema::dropIfExists('artikel');
        Schema::dropIfExists('kategori_artikel');
    }
};
