<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = 'products';

    const CREATED_AT = 'ngay_tao';
    const UPDATED_AT = 'ngay_cap_nhat';
    // public $timestamps = false; // bật nếu chưa có 2 cột trên

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
        // 'slug',
    ];

    protected $casts = [
        'gia'              => 'decimal:2',
        'gia_khuyen_mai'   => 'decimal:2',
        'so_luong_ton_kho' => 'integer',
    ];

    protected $attributes = [
        'so_luong_ton_kho' => 0,
        'trang_thai'       => 'con_hang',
    ];

    protected $appends = ['detail_url', 'image_url'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function getDetailUrlAttribute()
    {
        // Nếu có route theo slug thì đổi lại cho phù hợp
        return route('sanpham.chitiet.id', ['id' => $this->id]);
    }

    public function getImageUrlAttribute()
    {
        return $this->hinh_anh_chinh
            ? asset('storage/' . $this->hinh_anh_chinh)
            : asset('img/placeholder.png');
    }

    // Scopes tiện dụng
    public function scopeActive($q)
    {
        return $q->where('trang_thai', '!=', 'an');
    }
    public function scopeInStock($q)
    {
        return $q->where('so_luong_ton_kho', '>', 0);
    }
}
