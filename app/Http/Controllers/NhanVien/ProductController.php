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
    public function index()
    {
        $brands = Brand::orderBy('ten_thuong_hieu')->get();
        $categories = Category::orderBy('ten_danh_muc')->get();

        // Nếu bạn muốn hiện danh sách sản phẩm ở dưới form, nạp luôn:
        $products = Product::latest('id')->paginate(12);

        return view('nhanvien.Them_thongtin_sp', compact('brands', 'categories', 'products'));
    }

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
            $path = $request->file('hinh_anh_chinh')->store('products', 'public'); // lưu vào storage/app/public/products
            $data['hinh_anh_chinh'] = $path;
        }

        if ($request->hasFile('hinh_anh_chinh')) {
            $data['hinh_anh_chinh'] = $request->file('hinh_anh_chinh')
                ->store('products', 'public');
        }

        $data['so_luong_ton_kho'] = $data['so_luong_ton_kho'] ?? 0;
        $data['trang_thai']       = $data['trang_thai'] ?? 'con_hang';

        Product::create($data);

        return back()->with('ok', 'Đã lưu sản phẩm!');
    }
}
