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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products'); // bảng products đã có
            $table->string('product_name');       // lưu tên tại thời điểm mua
            $table->decimal('price', 12, 0);      // đơn giá
            $table->unsignedInteger('quantity');  // SL
            $table->decimal('total', 12, 0);      // price * quantity
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.  
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
