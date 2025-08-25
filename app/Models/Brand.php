<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    protected $fillable = ['ten_thuong_hieu', 'logo_url', 'mo_ta'];

    protected $table = 'brands';



    public function products(): HasMany
    {
        // giả định bảng products có cột brand_id
        return $this->hasMany(Product::class, 'brand_id');
    }
}
