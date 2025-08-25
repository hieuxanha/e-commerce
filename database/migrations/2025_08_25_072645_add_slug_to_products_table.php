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
        Schema::table('products', function (Blueprint $table) {
            // thêm cột slug sau cột ten_san_pham
            $table->string('slug')
                ->nullable()
                ->unique()
                ->after('ten_san_pham');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // rollback: xoá cột slug nếu tồn tại
            $table->dropColumn('slug');
        });
    }
};
