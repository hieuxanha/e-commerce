<?php

namespace App\Http\Controllers\NhanVien;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $r)
    {
        $data = $r->validate([
            'ten_danh_muc'    => 'required|string|max:100',
            'danh_muc_cha_id' => 'nullable|integer|exists:categories,id',
            'mo_ta'           => 'nullable|string',
        ]);

        Category::create($data);
        return back()->with('ok', 'Đã thêm danh mục');
    }

    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $categories = \App\Models\Category::query()
            ->when(
                $q !== '',
                fn($qr) =>
                $qr->where('ten_danh_muc', 'like', "%{$q}%")
                // ->orWhere('name', 'like', "%{$q}%") // ❌ bỏ vì không có cột name
            )
            ->orderBy('ten_danh_muc') // ❌ bỏ orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('nhanvien.Ql_danhmuc', compact('categories', 'q'));
    }
}
