<?php

namespace App\Http\Controllers\NhanVien;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class NhanvienDashboardController extends Controller
{
    public function index()
    {
        // Tổng quan
        $totalProducts   = Product::count();
        $lowStockCount   = Product::where('so_luong_ton_kho', '<', 5)->count();
        $totalBrands     = Brand::count();
        $totalCategories = Category::count();

        // Sản phẩm mới cập nhật/ thêm gần đây
        $latestProducts = Product::select([
            'id',
            'ten_san_pham',
            'sku',
            'gia',
            'gia_khuyen_mai',
            'hinh_anh_chinh',
            'so_luong_ton_kho'
        ])
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        // Chart: số sản phẩm thêm theo ngày trong 7 ngày gần đây (dùng cột ngay_tao)
        $days   = collect(range(6, 0))->map(fn($i) => Carbon::today()->subDays($i));
        $labels = $days->map(fn($d) => $d->format('d/m'));
        $data   = $days->map(function (Carbon $d) {
            return Product::whereDate('ngay_tao', $d->toDateString())->count();
        });

        return view('nhanvien.dashboard', [
            'totalProducts'   => $totalProducts,
            'lowStockCount'   => $lowStockCount,
            'totalBrands'     => $totalBrands,
            'totalCategories' => $totalCategories,
            'latestProducts'  => $latestProducts,
            'chartLabels'     => $labels,
            'chartData'       => $data,
        ]);
    }
}
