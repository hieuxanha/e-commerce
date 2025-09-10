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
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
    // app/Models/Order.php
    // app/Models/Order.php
    public function scopeVisibleForUser($query, \App\Models\User $user)
    {
        // Gộp toàn bộ OR vào 1 nhóm để các where khác AND vào cả nhóm
        return $query->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhere('email', $user->email)
                ->orWhere('phone', $user->phone);
        });
    }
}
