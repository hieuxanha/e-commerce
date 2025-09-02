<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'status',
        'fullname',
        'email',
        'phone',
        'address',
        'province_id',
        'district_id',
        'ward_id',
        'province_name',
        'district_name',
        'ward_name',
        'note',
        'payment_method',
        'payment_status',
        'paid_at',
        'payment_ref',
        'payment_note',
        'subtotal',
        'shipping_fee',
        'total',
    ];

    protected $casts = [
        'subtotal'     => 'decimal:0',
        'shipping_fee' => 'decimal:0',
        'total'        => 'decimal:0',
        'paid_at'      => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
