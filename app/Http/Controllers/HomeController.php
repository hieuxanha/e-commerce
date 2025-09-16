<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coupon;

class HomeController extends Controller
{
    public function index()
    {
        // Danh mục + sản phẩm mới
        $categories = Category::whereHas('products')
            ->with(['products' => function ($q) {
                $q->orderByDesc('ngay_tao')->take(8);
            }])
            ->get();

        // ==== COUPONS hiển thị ở trang chủ ====
        $q = Coupon::query()->running(); // dùng scope running() bạn đã có

        if (auth()->check()) {
            $level = auth()->user()->membership_level ?: 'dong';
            // chỉ hiện coupon hợp lệ với hạng hiện tại (có cả "mọi hạng")
            $q->eligibleForLevel($level); // dùng scope eligibleForLevel() đã sửa trong model
        } else {
            // guest: chỉ hiện "mọi hạng"
            $q->where(function ($w) {
                $w->whereNull('eligible_levels')
                    ->orWhereRaw("TRIM(COALESCE(eligible_levels,'')) = ''")
                    ->orWhere(function ($x) {
                        $x->whereRaw('JSON_VALID(eligible_levels) = 1')
                            ->whereRaw('JSON_LENGTH(eligible_levels) = 0'); // []
                    });
            });
        }

        $coupons = $q->orderByRaw("CASE WHEN apply_scope='all' THEN 0 ELSE 1 END")
            ->orderByDesc('value')
            ->get();

        // ==== Banner mã toàn shop ====
        $gq = Coupon::query()->running()->where('apply_scope', 'all');

        if (auth()->check()) {
            $level = auth()->user()->membership_level ?: 'dong';
            $gq->eligibleForLevel($level);
        } else {
            $gq->where(function ($w) {
                $w->whereNull('eligible_levels')
                    ->orWhereRaw("TRIM(COALESCE(eligible_levels,'')) = ''")
                    ->orWhere(function ($x) {
                        $x->whereRaw('JSON_VALID(eligible_levels) = 1')
                            ->whereRaw('JSON_LENGTH(eligible_levels) = 0');
                    });
            });
        }

        $globalCoupon = $gq->orderByDesc('value')->first();

        return view('layouts.trangchu', compact('categories', 'coupons', 'globalCoupon'));
    }
}
