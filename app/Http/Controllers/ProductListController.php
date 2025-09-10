<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;

class ProductListController extends Controller
{
    public function show($id)
    {
        // Lấy danh mục
        $category = Category::findOrFail($id);
        $brands   = Brand::all();
        // Lấy sản phẩm thuộc danh mục
        $products = Product::where('category_id', $id)->paginate(12);

        // Truyền cả 3 biến sang view
        return view('layouts.danhsachsanpham', compact('category', 'products', 'brands'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'ten_san_pham' => 'required|string|max:255',
            'hinh_anh' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Lưu ảnh vào thư mục storage/app/public/products
        $path = $request->file('hinh_anh')->store('products', 'public');

        // Chỉ lưu tên file (hoặc cả path tùy bạn chọn)
        $product = new Product();
        $product->ten_san_pham = $request->ten_san_pham;
        $product->hinh_anh = $path; // lưu cả 'products/abc.jpg'
        $product->category_id = $request->category_id;
        $product->save();

        return redirect()->route('products.index')->with('success', 'Thêm sản phẩm thành công!');
    }
}
