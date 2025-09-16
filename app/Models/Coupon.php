<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Arr;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',            // percent | fixed | free_shipping
        'value',
        'max_discount',
        'min_subtotal',
        'apply_scope',     // all | cart | product | category | brand
        'starts_at',
        'ends_at',
        'status',          // active | inactive
        'note',
        'eligible_levels', // JSON array hoặc CSV (đã xử lý bên dưới)
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
        // cast mặc định là array, nhưng ta còn viết accessor để fallback CSV
        'eligible_levels' => 'array',
    ];

    /* =========================
     |  Quan hệ
     |=========================*/
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

    /* =========================
     |  Scopes
     |=========================*/

    /** Coupon đang hiệu lực thời gian + status active */
    public function scopeRunning(Builder $q): Builder
    {
        $now = now();

        return $q->where('status', 'active')
            ->where(function ($x) use ($now) {
                $x->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($x) use ($now) {
                $x->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            });
    }

    /**
     * Lọc theo hạng thành viên:
     * - eligible_levels = NULL/''/[]  => coi như "mọi hạng" (hiển thị)
     * - JSON hợp lệ chứa $level       => hiển thị
     * - CSV (vd "dong,bac") chứa $lv  => hiển thị
     */
    public function scopeEligibleForLevel(Builder $q, string $level): Builder
    {
        return $q->where(function ($w) use ($level) {
            // 1) null/'' => mọi hạng
            $w->whereNull('eligible_levels')
                ->orWhereRaw("TRIM(COALESCE(eligible_levels,'')) = ''");

            // 2) JSON array hợp lệ và chứa level
            $w->orWhere(function ($y) use ($level) {
                $y->whereRaw('JSON_VALID(eligible_levels) = 1')
                    ->where(function ($z) use ($level) {
                        // một số collation cần cả 2 cách dưới để chắc ăn
                        $z->whereJsonContains('eligible_levels', $level)
                            ->orWhereJsonContains('eligible_levels', '"' . $level . '"');
                    });
            });

            // 3) CSV (không phải JSON) và có level
            $w->orWhere(function ($y) use ($level) {
                $y->whereRaw('JSON_VALID(eligible_levels) = 0')
                    ->whereRaw('FIND_IN_SET(?, REPLACE(eligible_levels, " ", ""))', [$level]); // bỏ khoảng trắng
            });
        });
    }

    /* =========================
     |  Accessor / Mutator
     |=========================*/

    /**
     * Chuẩn hoá eligible_levels về array:
     * - null/'' => null
     * - JSON hợp lệ => array
     * - CSV "dong,bac" => ['dong','bac']
     */
    protected function eligibleLevels(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value === null || trim((string)$value) === '') {
                    return null; // mọi hạng
                }

                // Nếu cast 'array' đã chạy ok, cứ dùng
                if (is_array($value)) {
                    return $value;
                }

                // Nếu là JSON hợp lệ
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return is_array($decoded) ? array_values(array_filter($decoded, 'strlen')) : null;
                }

                // Fallback CSV
                $parts = array_filter(array_map('trim', explode(',', (string)$value)), 'strlen');
                return $parts ? array_values($parts) : null;
            },

            // Cho phép gán array/CSV —> luôn lưu JSON gọn
            set: function ($value) {
                if ($value === null || $value === '' || $value === []) {
                    return null;
                }
                if (is_string($value)) {
                    // có thể là CSV hoặc JSON string
                    $try = json_decode($value, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($try)) {
                        $arr = $try;
                    } else {
                        $arr = array_filter(array_map('trim', explode(',', $value)), 'strlen');
                    }
                } else {
                    $arr = Arr::wrap($value);
                }
                // lưu JSON đẹp
                return json_encode(array_values(array_unique($arr)));
            }
        );
    }

    /* =========================
     |  Helpers
     |=========================*/

    public function getLabelAttribute(): string
    {
        return match ($this->type) {
            'percent'       => ($this->value ? rtrim(rtrim((string)$this->value, '0'), '.') . '%' : '—'),
            'fixed'         => ($this->value ? number_format((float)$this->value, 0, ',', '.') . 'đ' : '—'),
            'free_shipping' => 'Free ship',
            default         => '—',
        };
    }

    /** Kiểm tra user có đủ điều kiện hạng (không kiểm tra thời gian) */
    public function isEligibleFor(?\App\Models\User $user): bool
    {
        // null/[] => mọi hạng
        $levels = $this->eligible_levels; // accessor đã chuẩn hoá
        if (empty($levels)) return true;

        if (!$user) return false;

        $lv = $user->membership_level ?: 'dong';
        return in_array($lv, $levels, true);
    }
}
