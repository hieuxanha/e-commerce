<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $t) {
            $t->enum('payment_status', ['chua_thanh_toan', 'da_thanh_toan', 'that_bai', 'hoan_tien'])
                ->default('chua_thanh_toan')
                ->after('payment_method');
            $t->timestamp('paid_at')->nullable()->after('payment_status');
            $t->string('payment_ref')->nullable()->after('paid_at');
            $t->string('payment_note')->nullable()->after('payment_ref');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $t) {
            $t->dropColumn(['payment_status', 'paid_at', 'payment_ref', 'payment_note']);
        });
    }
};
