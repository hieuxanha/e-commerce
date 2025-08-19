<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        // TEST: lấy tất cả sản phẩm mới nhất (không lọc danh mục)
        $categories = Category::whereHas('products')
            ->with(['products' => function ($q) {
                $q->orderByDesc('ngay_tao')->take(8);
            }])
            ->get();

        return view('layouts.trangchu', compact('categories'));
    }
}
