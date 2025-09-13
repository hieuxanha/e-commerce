<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminBrandFrontController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $brands = Brand::query()
            ->when(
                $q !== '',
                fn($qr) =>
                $qr->where('ten_thuong_hieu', 'like', "%{$q}%")
            )
            ->orderBy('ten_thuong_hieu')
            ->paginate(20)
            ->withQueryString();

        return view('admin.Ql_thuonghieu', compact('brands', 'q'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'ten_thuong_hieu' => 'required|string|max:100|unique:brands,ten_thuong_hieu',
            'logo_url'        => 'nullable|string|max:255',
            'mo_ta'           => 'nullable|string',
        ]);

        Brand::create($data);
        return back()->with('ok', 'Đã thêm thương hiệu');
    }

    public function update(Request $r, Brand $brand)
    {
        $data = $r->validate([
            'ten_thuong_hieu' => [
                'required',
                'string',
                'max:100',
                Rule::unique('brands', 'ten_thuong_hieu')->ignore($brand->id),
            ],
            'logo_url' => 'nullable|string|max:255',
            'mo_ta'    => 'nullable|string',
        ]);

        $brand->update($data);
        return back()->with('ok', 'Đã cập nhật thương hiệu');
    }

    // ➕ Xoá
    public function destroy(Brand $brand)
    {
        $brand->delete();
        return back()->with('ok', 'Đã xoá thương hiệu');
    }

    // (giữ nguyên store() của bạn nếu đã có)
}
