<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Mã (bắt buộc, unique)
            $table->enum('type', ['percent', 'fixed', 'free_shipping']); // Loại
            $table->unsignedInteger('value')->nullable(); // Giá trị
            $table->unsignedInteger('max_discount')->nullable(); // Trần giảm tối đa (cho %)
            $table->unsignedInteger('min_subtotal')->default(0); // Giá trị tối thiểu đơn
            $table->enum('apply_scope', ['all', 'cart', 'product', 'category', 'brand'])->default('cart'); // Phạm vi
            $table->timestamp('starts_at')->nullable(); // Bắt đầu
            $table->timestamp('ends_at')->nullable();   // Kết thúc
            $table->enum('status', ['active', 'inactive'])->default('active'); // Trạng thái
            $table->string('note')->nullable(); // Ghi chú
            $table->timestamps();
        });

        Schema::create('coupon_products', function (Blueprint $t) {
            $t->foreignId('coupon_id')->constrained('coupons')->cascadeOnDelete();
            $t->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $t->primary(['coupon_id', 'product_id']);
        });

        Schema::create('coupon_categories', function (Blueprint $t) {
            $t->foreignId('coupon_id')->constrained('coupons')->cascadeOnDelete();
            $t->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $t->primary(['coupon_id', 'category_id']);
        });

        Schema::create('coupon_brands', function (Blueprint $t) {
            $t->foreignId('coupon_id')->constrained('coupons')->cascadeOnDelete();
            $t->foreignId('brand_id')->constrained('brands')->cascadeOnDelete();
            $t->primary(['coupon_id', 'brand_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_brands');
        Schema::dropIfExists('coupon_categories');
        Schema::dropIfExists('coupon_products');
        Schema::dropIfExists('coupons');
    }
};
