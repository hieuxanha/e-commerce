<?php

namespace App\Http\Controllers\NhanVien;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Danh sách sản phẩm + truyền brands/categories (cho dropdown ở view list hoặc dashboard).
     */
    public function index()
    {
        $products = Product::with([
            'category:id,ten_danh_muc',
            'brand:id,ten_thuong_hieu',
        ])->select([
            'id',
            'ten_san_pham',
            'sku',
            'gia',
            'gia_khuyen_mai',
            'hinh_anh_chinh',
            'so_luong_ton_kho',
            'trang_thai',
            'category_id',
            'brand_id',
            'mo_ta_ngan',
            'mo_ta_chi_tiet',
        ])
            ->latest('id')
            ->paginate(12);

        $brands = Brand::orderBy('ten_thuong_hieu')->get(['id', 'ten_thuong_hieu']);
        $categories = Category::orderBy('ten_danh_muc')->get(['id', 'ten_danh_muc']);

        // Trang danh sách (view tự bạn đặt). Nếu bạn dùng dashboard chung, đổi sang 'nhanvien.dashboard'
        return view('nhanvien.QL_sanpham', compact('products', 'brands', 'categories'));
    }

    /**
     * Form thêm sản phẩm (trang riêng).
     */
    public function create()
    {
        $brands = Brand::orderBy('ten_thuong_hieu')->get(['id', 'ten_thuong_hieu']);
        $categories = Category::orderBy('ten_danh_muc')->get(['id', 'ten_danh_muc']);

        return view('nhanvien.Them_thongtin_sp', compact('brands', 'categories'));
    }

    /**
     * Lưu sản phẩm mới.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'ten_san_pham'      => ['required', 'string', 'max:255'],
            'sku'               => ['required', 'string', 'max:100', 'unique:products,sku'],
            'gia'               => ['required', 'numeric', 'min:0'],
            'gia_khuyen_mai'    => ['nullable', 'numeric', 'min:0', 'lt:gia'],
            'so_luong_ton_kho'  => ['nullable', 'integer', 'min:0'],
            'trang_thai'        => ['nullable', Rule::in(['con_hang', 'het_hang', 'sap_ve', 'an'])],
            'category_id'       => ['required', 'exists:categories,id'],
            'brand_id'          => ['required', 'exists:brands,id'],
            'mo_ta_ngan'        => ['nullable', 'string', 'max:500'],
            'mo_ta_chi_tiet'    => ['nullable', 'string'],
            'hinh_anh_chinh'    => ['nullable', 'image', 'max:4096'],
        ]);

        $data['so_luong_ton_kho'] = $data['so_luong_ton_kho'] ?? 0;
        $data['trang_thai']       = $data['trang_thai'] ?? 'con_hang';

        DB::transaction(function () use ($request, &$data) {
            if ($request->hasFile('hinh_anh_chinh')) {
                // nhớ php artisan storage:link
                $path = $request->file('hinh_anh_chinh')->store('products', 'public');
                $data['hinh_anh_chinh'] = $path;
            }
            Product::create($data);
        });

        // Về lại form thêm (có brand/category) hoặc về list tùy bạn
        return redirect()->route('nhanvien.sanpham.them')->with('ok', 'Đã lưu sản phẩm!');
        // return redirect()->route('nhanvien.danhsachsanpham')->with('ok', 'Đã lưu sản phẩm!');
    }

    /**
     * Form sửa sản phẩm.
     */
    public function edit($id)
    {
        $product = Product::with([
            'category:id,ten_danh_muc',
            'brand:id,ten_thuong_hieu',
        ])->findOrFail($id);

        $brands = Brand::orderBy('ten_thuong_hieu')->get(['id', 'ten_thuong_hieu']);
        $categories = Category::orderBy('ten_danh_muc')->get(['id', 'ten_danh_muc']);

        return view('nhanvien.edit_sanpham', compact('product', 'brands', 'categories'));
    }

    /**
     * Cập nhật sản phẩm.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'ten_san_pham'     => ['required', 'string', 'max:255'],
            'gia'              => ['required', 'numeric', 'min:0'],
            'sku'              => ['required', 'string', 'max:100', Rule::unique('products', 'sku')->ignore($product->id)],
            'gia_khuyen_mai'   => ['nullable', 'numeric', 'min:0', 'lt:gia'],
            'so_luong_ton_kho' => ['required', 'integer', 'min:0'],
            'trang_thai'       => ['nullable', Rule::in(['con_hang', 'het_hang', 'sap_ve', 'an'])],
            'category_id'      => ['required', 'exists:categories,id'],
            'brand_id'         => ['required', 'exists:brands,id'],
            'mo_ta_ngan'       => ['nullable', 'string', 'max:500'],
            'mo_ta_chi_tiet'   => ['nullable', 'string'],
            'hinh_anh_chinh'   => ['nullable', 'image', 'max:4096'],
        ]);

        DB::transaction(function () use ($request, $product, $validated) {
            $data = $validated;

            if ($request->hasFile('hinh_anh_chinh')) {
                // Xoá ảnh cũ nếu có
                if ($product->hinh_anh_chinh && Storage::disk('public')->exists($product->hinh_anh_chinh)) {
                    Storage::disk('public')->delete($product->hinh_anh_chinh);
                }
                $path = $request->file('hinh_anh_chinh')->store('products', 'public');
                $data['hinh_anh_chinh'] = $path;
            }

            $product->update($data);
        });

        return redirect()->route('nhanvien.danhsachsanpham')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    /**
     * Xoá sản phẩm (và ảnh nếu có).
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        DB::transaction(function () use ($product) {
            if ($product->hinh_anh_chinh && Storage::disk('public')->exists($product->hinh_anh_chinh)) {
                Storage::disk('public')->delete($product->hinh_anh_chinh);
            }
            $product->delete();
        });

        return redirect()->route('nhanvien.danhsachsanpham')->with('success', 'Xóa sản phẩm thành công!');
    }
}
