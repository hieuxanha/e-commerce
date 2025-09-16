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
        // NEW
        'stock_applied',
    ];

    protected $casts = [
        'subtotal'      => 'decimal:0',
        'shipping_fee'  => 'decimal:0',
        'total'         => 'decimal:0',
        'paid_at'       => 'datetime',
        // NEW
        'stock_applied' => 'boolean',
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

    public function scopeVisibleForUser($query, \App\Models\User $user)
    {
        return $query->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhere('email', $user->email)
                ->orWhere('phone', $user->phone);
        });
    }
}
