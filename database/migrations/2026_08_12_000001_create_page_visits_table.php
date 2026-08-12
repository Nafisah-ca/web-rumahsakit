<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_visits', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->nullable()->index();
            $table->string('page_title')->nullable();
            $table->string('page_url', 1000);
            $table->string('user_agent', 500)->nullable();
            $table->string('referer', 1000)->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->timestamp('visited_at')->useCurrent()->index();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_visits');
    }
};
