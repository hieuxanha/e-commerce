<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Schema; // dùng import này
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        // Lấy theo slug (nếu có cột is_active thì lọc, không thì bỏ qua)
        $q = Product::query()->where('slug', $slug);
        if (Schema::hasColumn('products', 'is_active')) {
            $q->where('is_active', 1);
        }
        $product = $q->firstOrFail();

        // Tăng lượt xem (nếu có cột)
        if (Schema::hasColumn('products', 'luot_xem')) {
            $product->increment('luot_xem');
        }

        // Sản phẩm liên quan (nếu có category_id)
        $related = Product::query()
            ->when(Schema::hasColumn('products', 'category_id') && !empty($product->category_id), function ($q) use ($product) {
                $q->where('category_id', $product->category_id)
                    ->where('id', '<>', $product->id);
            })
            ->limit(8)
            ->get();

        return view('layouts.chitietsanpham', compact('product', 'related'));
    }

    public function showById(int $id)
    {
        // Lấy theo id
        $product = Product::findOrFail($id);

        // Tăng lượt xem (nếu có cột)
        if (Schema::hasColumn('products', 'luot_xem')) {
            $product->increment('luot_xem');
        }

        // Sản phẩm liên quan (nếu có category_id)
        $related = Product::query()
            ->when(Schema::hasColumn('products', 'category_id') && !empty($product->category_id), function ($q) use ($product) {
                $q->where('category_id', $product->category_id)
                    ->where('id', '<>', $product->id);
            })
            ->limit(8)
            ->get();

        return view('layouts.chitietsanpham', compact('product', 'related'));
    }
}
