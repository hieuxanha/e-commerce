<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'max_discount',
        'min_subtotal',
        'apply_scope',
        'starts_at',
        'ends_at',
        'status',
        'note'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'coupon_products');
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'coupon_categories');
    }
    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'coupon_brands');
    }
    public function scopeRunning($q)
    {
        $now = now();
        return $q->where('status', 'active')
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            });
    }

    public function getLabelAttribute()
    {
        return match ($this->type) {
            'percent' => ($this->value ? $this->value . '%' : '—'),
            'fixed'   => ($this->value ? number_format($this->value, 0, ',', '.') . 'đ' : '—'),
            'free_shipping' => 'Free ship',
        };
    }
}
