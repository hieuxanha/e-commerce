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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('code', 50)->unique(); // VD: COD2408291230001
            $table->enum('status', ['da_dat', 'cho_chuyen_phat', 'dang_trung_chuyen', 'da_giao'])
                ->default('da_dat');

            // Thông tin nhận hàng
            $table->string('fullname');
            $table->string('email');
            $table->string('phone', 30);
            $table->string('address');
            $table->unsignedBigInteger('province_id');
            $table->unsignedBigInteger('district_id');
            $table->unsignedBigInteger('ward_id');
            $table->string('province_name')->nullable();
            $table->string('district_name')->nullable();
            $table->string('ward_name')->nullable();
            $table->text('note')->nullable();

            // Thanh toán
            $table->enum('payment_method', ['cod', 'vnpay'])->default('cod');

            // Tiền tệ (nên dùng DECIMAL để chính xác)
            $table->decimal('subtotal', 12, 0)->default(0);
            $table->decimal('shipping_fee', 12, 0)->default(0);
            $table->decimal('total', 12, 0)->default(0);

            $table->timestamps();
            $table->index(['status']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
