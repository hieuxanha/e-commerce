<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Hiển thị chi tiết sản phẩm theo slug
     */
    public function show(string $slug)
    {


        $q = Product::query()->where('slug', $slug);
        if (Schema::hasColumn('products', 'is_active')) {
            $q->where('is_active', 1);
        }
        $product = $q->firstOrFail();

        // tăng lượt xem nếu có cột
        if (Schema::hasColumn('products', 'luot_xem')) {
            $product->increment('luot_xem');
        }

        // sản phẩm liên quan
        $related = Product::query()
            ->when(Schema::hasColumn('products', 'category_id') && !empty($product->category_id), function ($q) use ($product) {
                $q->where('category_id', $product->category_id)
                    ->where('id', '<>', $product->id);
            })
            ->limit(8)
            ->get();

        // reviews (chỉ lấy đã duyệt nếu có cột approved)
        $reviews = Review::with('user')
            ->where('product_id', $product->id)
            ->when(Schema::hasColumn('reviews', 'approved'), fn($q) => $q->where('approved', 1))
            ->latest()
            ->paginate(5);

        $avgRating    = Review::where('product_id', $product->id)->avg('rating');
        $reviewsCount = Review::where('product_id', $product->id)->count();

        return view('layouts.chitietsanpham', compact(
            'product',
            'related',
            'reviews',
            'avgRating',
            'reviewsCount'
        ));
    }

    /**
     * Hiển thị chi tiết sản phẩm theo id
     */
    public function showById(int $id)
    {
        $product = Product::findOrFail($id);

        if (Schema::hasColumn('products', 'luot_xem')) {
            $product->increment('luot_xem');
        }

        $related = Product::query()
            ->when(Schema::hasColumn('products', 'category_id') && !empty($product->category_id), function ($q) use ($product) {
                $q->where('category_id', $product->category_id)
                    ->where('id', '<>', $product->id);
            })
            ->limit(8)
            ->get();

        $reviews = Review::with('user')
            ->where('product_id', $product->id)
            ->when(Schema::hasColumn('reviews', 'approved'), fn($q) => $q->where('approved', 1))
            ->latest()
            ->paginate(5);

        $avgRating    = Review::where('product_id', $product->id)->avg('rating');
        $reviewsCount = Review::where('product_id', $product->id)->count();

        return view('layouts.chitietsanpham', compact(
            'product',
            'related',
            'reviews',
            'avgRating',
            'reviewsCount'
        ));
    }
}
