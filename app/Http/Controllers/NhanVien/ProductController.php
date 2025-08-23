<?php

namespace App\Http\Controllers\NhanVien;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;



class ProductController extends Controller
{
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('nhanvien.edit_sanpham', compact('product'));
    }

    // Cập nhật sản phẩm
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'ten_san_pham' => 'required|string|max:255',
            'gia' => 'required|numeric',
            'sku' => 'required|string|max:100',
            'so_luong_ton_kho' => 'required|integer',
        ]);

        $product->update([
            'ten_san_pham' => $request->ten_san_pham,
            'gia' => $request->gia,
            'sku' => $request->sku,
            'gia_khuyen_mai' => $request->gia_khuyen_mai,
            'so_luong_ton_kho' => $request->so_luong_ton_kho,
            'trang_thai' => $request->trang_thai,
        ]);

        return redirect()->route('nhanvien.QL_sanpham') // quay lại trang dashboard
            ->with('success', 'Cập nhật sản phẩm thành công!');
    }

    // Xóa sản phẩm
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('nhanvien.QL_sanpham')
            ->with('success', 'Xóa sản phẩm thành công!');
    }



    // Hiển thị danh sách sản phẩm
    public function index()
    {
        $products = Product::latest('id')->paginate(12);
        return view('nhanvien.QL_sanpham', compact('products'));
    }

    // Hiển thị form thêm sản phẩm
    public function create()
    {
        $brands = Brand::orderBy('ten_thuong_hieu')->get();
        $categories = Category::orderBy('ten_danh_muc')->get();
        return view('nhanvien.Them_thongtin_sp', compact('brands', 'categories'));
    }

    // Lưu sản phẩm
    public function store(Request $request)
    {
        $data = $request->validate([
            'ten_san_pham'      => 'required|string|max:255',
            'sku'               => 'required|string|max:100|unique:products,sku',
            'gia'               => 'required|numeric|min:0',
            'gia_khuyen_mai'    => 'nullable|numeric|min:0|lt:gia',
            'so_luong_ton_kho'  => 'nullable|integer|min:0',
            'trang_thai'        => 'nullable|in:con_hang,het_hang,sap_ve,an',
            'category_id'       => 'required|exists:categories,id',
            'brand_id'          => 'required|exists:brands,id',
            'mo_ta_ngan'        => 'nullable|string|max:500',
            'mo_ta_chi_tiet'    => 'nullable|string',
            'hinh_anh_chinh'    => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('hinh_anh_chinh')) {
            $path = $request->file('hinh_anh_chinh')->store('products', 'public');
            $data['hinh_anh_chinh'] = $path;
        }

        $data['so_luong_ton_kho'] = $data['so_luong_ton_kho'] ?? 0;
        $data['trang_thai']       = $data['trang_thai'] ?? 'con_hang';

        Product::create($data);

        return back()->with('ok', 'Đã lưu sản phẩm!');
    }
}
