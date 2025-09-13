<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class AdminBrandController extends Controller
{
    public function store(Request $r)
    {
        $data = $r->validate([
            'ten_thuong_hieu' => 'required|string|max:100|unique:brands,ten_thuong_hieu',
            'logo_url'        => 'nullable|string|max:400',
            'mo_ta'           => 'nullable|string',
        ]);

        Brand::create($data);
        return back()->with('ok', 'Đã thêm thương hiệu');
    }
}
