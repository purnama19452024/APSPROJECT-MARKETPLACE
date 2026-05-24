<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->after('name')->unique()->nullable();
            $table->string('phone')->after('email')->nullable();
            $table->string('photo')->after('password')->nullable();
            $table->boolean('is_active')->after('photo')->default(true);
            $table->foreignId('role_id')->after('is_active')->default(3)->constrained('roles')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'phone', 'photo', 'is_active', 'role_id']);
        });
    }
};
