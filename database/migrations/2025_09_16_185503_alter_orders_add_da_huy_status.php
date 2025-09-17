<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // MySQL/MariaDB: thêm 'da_huy' vào enum hiện có
        DB::statement("
            ALTER TABLE `orders`
            MODIFY COLUMN `status`
            ENUM('da_dat','da_huy','cho_chuyen_phat','dang_trung_chuyen','da_giao')
            NOT NULL DEFAULT 'da_dat'
        ");
    }

    public function down(): void
    {
        // Revert: bỏ 'da_huy'
        DB::statement("
            ALTER TABLE `orders`
            MODIFY COLUMN `status`
            ENUM('da_dat','cho_chuyen_phat','dang_trung_chuyen','da_giao')
            NOT NULL DEFAULT 'da_dat'
        ");
    }
};
