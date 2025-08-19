<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    // DÙNG TÊN CỘT TÙY CHỈNH
    const CREATED_AT = 'ngay_tao';
    const UPDATED_AT = 'ngay_cap_nhat';

    // Nếu không muốn tự động timestamp, có thể dùng:
    // public $timestamps = false;

    protected $fillable = [
        'ten_san_pham',
        'sku',
        'mo_ta_ngan',
        'mo_ta_chi_tiet',
        'gia',
        'gia_khuyen_mai',
        'so_luong_ton_kho',
        'hinh_anh_chinh',
        'category_id',
        'brand_id',
        'trang_thai',
    ];

    // (tuỳ chọn) kiểu dữ liệu
    protected $casts = [
        'gia' => 'decimal:2',
        'gia_khuyen_mai' => 'decimal:2',
        'so_luong_ton_kho' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
