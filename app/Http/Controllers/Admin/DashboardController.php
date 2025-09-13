<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Review;
use App\Models\CartItem;

class DashboardController extends Controller
{
    public function index()
    {
        // KPIs
        $metrics = [
            'products'        => Product::count(),
            'categories'      => Category::count(),
            'brands'          => Brand::count(),
            'users'           => User::count(),
            'admins'          => User::where('role', 'admin')->count(),
            'staff'           => User::where('role', 'nhan_vien')->count(),
            'customers'       => User::where('role', 'khach_hang')->count(),
            'reviews'         => Review::count(),
            'pending_reviews' => Review::where('approved', 0)->count(),
            'cart_items'      => CartItem::count(),
        ];

        // Biểu đồ: số SP theo danh mục
        $byCategory = Category::withCount('products')
            ->get(['id', 'ten_danh_muc']);
        $catLabels = $byCategory->pluck('ten_danh_muc')->values(); // ['Áo', 'Quần', ...]
        $catCounts = $byCategory->pluck('products_count')->values(); // [12, 8, ...]

        // Biểu đồ: người dùng theo vai trò
        $roles = [
            'Admin'      => $metrics['admins'],
            'Nhân viên'  => $metrics['staff'],
            'Khách hàng' => $metrics['customers'],
        ];
        $roleLabels = array_values(array_keys($roles));   // ['Admin','Nhân viên','Khách hàng']
        $roleCounts = array_values($roles);               // [a,b,c]

        // SP sắp hết hàng
        $lowStocks = Product::select('id', 'ten_san_pham', 'sku', 'so_luong_ton_kho', 'gia', 'gia_khuyen_mai')
            ->where('so_luong_ton_kho', '<', 5)
            ->orderBy('so_luong_ton_kho')
            ->limit(8)
            ->get();

        // Đánh giá mới
        $latestReviews = Review::with(['user:id,name,email', 'product:id,ten_san_pham'])
            ->latest()->limit(5)->get();

        // Giảm giá mạnh
        $topDiscounts = Product::select('id', 'ten_san_pham', 'gia', 'gia_khuyen_mai', 'so_luong_ton_kho')
            ->whereNotNull('gia_khuyen_mai')
            ->get()
            ->map(function ($p) {
                $p->discount_percent = $p->gia > 0
                    ? round(100 * ($p->gia - $p->gia_khuyen_mai) / $p->gia)
                    : 0;
                return $p;
            })
            ->sortByDesc('discount_percent')
            ->take(5)->values();

        return view('admin.dashboard', compact(
            'metrics',
            'lowStocks',
            'latestReviews',
            'topDiscounts',
            // 4 mảng dành cho chart:
            'catLabels',
            'catCounts',
            'roleLabels',
            'roleCounts'
        ));
    }
}
