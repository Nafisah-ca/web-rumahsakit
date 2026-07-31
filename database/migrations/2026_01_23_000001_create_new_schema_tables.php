<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Migration ini dibuat berdasarkan database baru yang sudah di-import.
     * Hanya untuk dokumentasi Laravel, karena tabel sudah ada di database.
     */
    public function up(): void
    {
        // NOTE: Migration ini TIDAK akan dijalankan karena tabel sudah ada di database
        // Ini hanya untuk dokumentasi schema Laravel
        
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('username', 50)->unique();
                $table->string('email', 100)->unique();
                $table->string('password');
                $table->string('nama', 100);
                $table->string('no_hp', 20)->nullable();
                $table->string('foto')->nullable();
                $table->enum('role', ['cms', 'admin', 'pasien']);
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
                $table->datetime('last_login')->nullable();
                $table->timestamp('created_tm')->useCurrent();
                $table->timestamp('updated_tm')->useCurrent()->useCurrentOnUpdate();
                $table->timestamp('deleted_tm')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->unsignedBigInteger('deleted_by')->nullable();

                $table->foreign('created_by')->references('id')->on('users');
                $table->foreign('updated_by')->references('id')->on('users');
                $table->foreign('deleted_by')->references('id')->on('users');
            });
        }

        // Tipe Penjamin
        if (!Schema::hasTable('tipe_penjamin')) {
            Schema::create('tipe_penjamin', function (Blueprint $table) {
                $table->id();
                $table->string('nama_tipe', 100)->unique();
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

        // Penjamin
        if (!Schema::hasTable('penjamin')) {
            Schema::create('penjamin', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tipe_penjamin_id')->constrained('tipe_penjamin')->onDelete('restrict')->onUpdate('cascade');
                $table->string('nama_penjamin', 100);
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

        // Spesialis
        if (!Schema::hasTable('spesialis')) {
            Schema::create('spesialis', function (Blueprint $table) {
                $table->id();
                $table->string('nama_spesialis', 100);
                $table->text('deskripsi')->nullable();
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

        // Dokter
        if (!Schema::hasTable('dokter')) {
            Schema::create('dokter', function (Blueprint $table) {
                $table->id();
                $table->foreignId('spesialis_id')->constrained('spesialis')->onDelete('restrict')->onUpdate('cascade');
                $table->string('nama_dokter', 100);
                $table->string('sip', 100)->unique();
                $table->string('email', 100)->unique();
                $table->string('no_hp', 20);
                $table->string('foto')->nullable();
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

        // Jadwal Dokter
        if (!Schema::hasTable('jadwal_dokter')) {
            Schema::create('jadwal_dokter', function (Blueprint $table) {
                $table->id();
                $table->foreignId('dokter_id')->constrained('dokter')->onDelete('cascade')->onUpdate('cascade');
                $table->foreignId('spesialis_id')->constrained('spesialis')->onDelete('restrict')->onUpdate('cascade');
                $table->foreignId('penjamin_id')->nullable()->constrained('penjamin')->onDelete('set null')->onUpdate('cascade');
                $table->date('tanggal_praktek');
                $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
                $table->time('jam_mulai');
                $table->time('jam_selesai');
                $table->unsignedInteger('kuota');
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

        // Pasien
        if (!Schema::hasTable('pasien')) {
            Schema::create('pasien', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
                $table->string('no_rekam_medis', 30)->unique();
                $table->string('nik', 16)->unique();
                $table->enum('jenis_kelamin', ['L', 'P']);
                $table->string('tempat_lahir', 100);
                $table->date('tanggal_lahir');
                $table->text('alamat');
                $table->enum('golongan_darah', ['A', 'B', 'AB', 'O'])->nullable();
                $table->string('agama', 30)->nullable();
                $table->string('pekerjaan', 100)->nullable();
                $table->foreignId('penjamin_id')->nullable()->constrained('penjamin')->onDelete('set null')->onUpdate('cascade');
                $table->string('nomor_penjamin', 100)->nullable();
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

        // Janji Temu
        if (!Schema::hasTable('janji_temu')) {
            Schema::create('janji_temu', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pasien_id')->constrained('pasien')->onDelete('cascade')->onUpdate('cascade');
                $table->foreignId('jadwal_dokter_id')->constrained('jadwal_dokter')->onDelete('cascade')->onUpdate('cascade');
                $table->date('tanggal_booking');
                $table->text('keluhan');
                $table->unsignedInteger('nomor_antrian');
                $table->enum('status', ['pending', 'approved', 'completed', 'cancelled'])->default('pending');
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

        // Transaksi
        if (!Schema::hasTable('transaksi')) {
            Schema::create('transaksi', function (Blueprint $table) {
                $table->id();
                $table->foreignId('janji_temu_id')->constrained('janji_temu')->onDelete('restrict')->onUpdate('cascade');
                $table->foreignId('pasien_id')->constrained('pasien')->onDelete('restrict')->onUpdate('cascade');
                $table->foreignId('penjamin_id')->nullable()->constrained('penjamin')->onDelete('set null')->onUpdate('cascade');
                $table->string('kode_transaksi', 30)->unique();
                $table->decimal('total_biaya', 12, 2);
                $table->enum('metode_pembayaran', ['tunai', 'transfer', 'qris']);
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

        // Detail Transaksi
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

        // Guest Book
        if (!Schema::hasTable('guest_book')) {
            Schema::create('guest_book', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->string('email');
                $table->string('no_hp', 20)->nullable();
                $table->text('pesan');
                $table->enum('status', ['baru', 'dibaca', 'selesai'])->default('baru');
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

        // Informasi
        if (!Schema::hasTable('informasi')) {
            Schema::create('informasi', function (Blueprint $table) {
                $table->id();
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

        // Website Setting
        if (!Schema::hasTable('website_setting')) {
            Schema::create('website_setting', function (Blueprint $table) {
                $table->id();
                $table->string('nama_rumahsakit', 150);
                $table->string('logo')->nullable();
                $table->string('favicon')->nullable();
                $table->longText('tentang_kami')->nullable();
                $table->text('visi')->nullable();
                $table->longText('misi')->nullable();
                $table->longText('sejarah')->nullable();
                $table->string('motto')->nullable();
                $table->longText('sambutan_direktur')->nullable();
                $table->text('alamat')->nullable();
                $table->string('telepon', 20)->nullable();
                $table->string('email', 100)->nullable();
                $table->longText('google_maps')->nullable();
                $table->string('facebook')->nullable();
                $table->string('instagram')->nullable();
                $table->string('youtube')->nullable();
                $table->string('jam_operasional')->nullable();
                $table->text('footer')->nullable();
                $table->string('copyright')->nullable();
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
        Schema::dropIfExists('detail_transaksi');
        Schema::dropIfExists('transaksi');
        Schema::dropIfExists('janji_temu');
        Schema::dropIfExists('jadwal_dokter');
        Schema::dropIfExists('pasien');
        Schema::dropIfExists('dokter');
        Schema::dropIfExists('spesialis');
        Schema::dropIfExists('penjamin');
        Schema::dropIfExists('tipe_penjamin');
        Schema::dropIfExists('guest_book');
        Schema::dropIfExists('informasi');
        Schema::dropIfExists('website_setting');
    }
};
