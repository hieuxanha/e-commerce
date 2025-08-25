<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items'; // đổi đúng tên bảng
    public $timestamps = false;       // đổi theo thực tế

    protected $fillable = ['order_id', 'product_id', 'price', 'qty'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
