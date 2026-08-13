<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ulasan')) {
            Schema::create('ulasan', function (Blueprint $table) {
                $table->id();
                $table->string('nama', 150);
                $table->string('email', 150)->nullable();
                $table->tinyInteger('rating')->unsigned()->default(5); // 1–5 bintang
                $table->string('judul', 200)->nullable();
                $table->text('isi');
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
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
        Schema::dropIfExists('ulasan');
    }
};
