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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('ten_san_pham', 255);
            $table->string('sku', 100)->unique();

            $table->string('mo_ta_ngan', 500)->nullable();
            $table->text('mo_ta_chi_tiet')->nullable();

            $table->decimal('gia', 12, 2);
            $table->decimal('gia_khuyen_mai', 12, 2)->nullable();

            $table->integer('so_luong_ton_kho')->default(0);

            $table->string('hinh_anh_chinh', 255)->nullable();

            $table->foreignId('category_id')->nullable()
                ->constrained('categories')->nullOnDelete();

            $table->foreignId('brand_id')->nullable()
                ->constrained('brands')->nullOnDelete();

            $table->enum('trang_thai', ['con_hang', 'het_hang', 'sap_ve', 'an'])
                ->default('con_hang');

            // Theo yêu cầu của bạn:
            $table->timestamp('ngay_tao')->useCurrent();
            $table->timestamp('ngay_cap_nhat')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
