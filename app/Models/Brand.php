<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = ['ten_thuong_hieu', 'logo_url', 'mo_ta'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
