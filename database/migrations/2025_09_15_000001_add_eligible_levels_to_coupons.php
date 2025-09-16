<?php

// database/migrations/2025_09_15_000001_add_eligible_levels_to_coupons.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->json('eligible_levels')->nullable()->after('status');
        });
    }
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn('eligible_levels');
        });
    }
};
