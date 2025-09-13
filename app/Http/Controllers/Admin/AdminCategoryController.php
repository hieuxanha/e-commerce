<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $categories = Category::query()
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where('ten_danh_muc', 'like', "%{$q}%");
            })
            ->orderBy('ten_danh_muc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.Ql_danhmuc', compact('categories', 'q'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'ten_danh_muc'    => 'required|string|max:100',
            'danh_muc_cha_id' => 'nullable|integer|exists:categories,id',
            'mo_ta'           => 'nullable|string',
        ]);

        Category::create($data);
        return back()->with('success', 'Đã thêm danh mục.');
    }

    public function update(Request $r, Category $category)
    {
        $data = $r->validate([
            'ten_danh_muc'    => 'required|string|max:100',
            'danh_muc_cha_id' => 'nullable|integer|exists:categories,id|different:id',
            'mo_ta'           => 'nullable|string',
        ]);

        // Không cho chọn chính nó làm cha
        if (!empty($data['danh_muc_cha_id']) && (int)$data['danh_muc_cha_id'] === (int)$category->id) {
            return back()->withErrors('Danh mục cha không được là chính nó.');
        }

        $category->update($data);
        return back()->with('success', 'Đã cập nhật danh mục.');
    }

    public function destroy(Category $category)
    {
        // Tuỳ nghiệp vụ: chặn xoá nếu có con/sản phẩm
        $hasChildren = Category::where('danh_muc_cha_id', $category->id)->exists();
        if ($hasChildren) {
            return back()->withErrors('Không thể xoá: danh mục đang có danh mục con.');
        }

        // Nếu có ràng buộc sản phẩm: kiểm tra tại đây (ví dụ cột category_id trong bảng products)
        // if (\App\Models\Product::where('category_id', $category->id)->exists()) {
        //     return back()->withErrors('Không thể xoá: còn sản phẩm thuộc danh mục này.');
        // }

        $category->delete();
        return back()->with('success', 'Đã xoá danh mục.');
    }
}
