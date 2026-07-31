<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['user', 'admin', 'cms'])->default('user')->after('email');
            $table->string('phone', 20)->nullable()->after('role');
            $table->string('no_rm', 20)->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('no_rm');
            $table->string('avatar')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'no_rm', 'is_active', 'avatar']);
        });
    }
};
