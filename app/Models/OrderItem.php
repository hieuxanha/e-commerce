<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    // Nếu tên bảng là order_items thì có thể bỏ dòng này,
    // nhưng khai báo tường minh cho chắc:
    protected $table = 'order_items';

    // Để TRUE nếu bảng có created_at/updated_at (theo migration mình đề xuất).
    // Nếu bảng KHÔNG có 2 cột đó thì đổi thành false.
    public $timestamps = true;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'price',
        'quantity',
        'total',
        'image',
    ];

    protected $casts = [
        'price'    => 'decimal:0', // VND
        'total'    => 'decimal:0',
        'quantity' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // (Tuỳ chọn) Nếu muốn luôn có line_total tính động:
    public function getLineTotalAttribute()
    {
        return (int)$this->quantity * (int)$this->price;
    }
}
