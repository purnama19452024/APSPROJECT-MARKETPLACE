<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->string('province_code', 2)->nullable()->after('postal_code');
            $table->string('city_code', 5)->nullable()->after('province_code');
            $table->string('district_code', 8)->nullable()->after('city_code');
        });
    }

    public function down(): void
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->dropColumn(['province_code', 'city_code', 'district_code']);
        });
    }
};
