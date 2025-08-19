<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['ten_danh_muc', 'danh_muc_cha_id', 'mo_ta'];

    // Quan hệ tự tham chiếu: danh mục cha
    public function parent()
    {
        return $this->belongsTo(Category::class, 'danh_muc_cha_id');
    }

    // Danh mục con
    public function children()
    {
        return $this->hasMany(Category::class, 'danh_muc_cha_id');
    }

    // Sản phẩm thuộc danh mục
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
