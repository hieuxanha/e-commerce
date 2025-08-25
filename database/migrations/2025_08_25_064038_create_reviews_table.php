<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating'); // 1..5
            $table->text('comment')->nullable();
            $table->boolean('approved')->default(0); // 0 = chờ duyệt, 1 = đã duyệt
            $table->timestamps();

            $table->unique(['product_id', 'user_id']); // mỗi user chỉ review 1 lần / 1 sản phẩm
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
