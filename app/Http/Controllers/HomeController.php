<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coupon;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::whereHas('products')
            ->with(['products' => function ($q) {
                $q->orderByDesc('ngay_tao')->take(8);
            }])
            ->get();

        $now = now();

        // Toàn bộ coupon còn hiệu lực (để render strip)
        $coupons = Coupon::query()
            ->where('status', 'active')
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->orderByRaw("CASE WHEN apply_scope='all' THEN 0 ELSE 1 END")
            ->orderByDesc('value')
            ->get();

        // Lấy 1 mã all-shop để gắn tooltip (nếu cần)
        $globalCoupon = $coupons->firstWhere('apply_scope', 'all');

        return view('layouts.trangchu', compact('categories', 'coupons', 'globalCoupon'));
    }
}
