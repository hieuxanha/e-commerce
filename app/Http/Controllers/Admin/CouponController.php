<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CouponController extends Controller
{
    /** Lấy options cho select (id, name) từ 1 bảng/model. */
    private function pickOptions(string $table, string $modelClass, string $fallbackPrefix = '#')
    {
        if (!class_exists($modelClass) || !Schema::hasTable($table)) {
            return collect();
        }

        $q = $modelClass::query()->select('id');

        $candidates = [
            'ten_san_pham', // products
            'ten_danh_muc', // categories
            'ten_thuong_hieu', // brands
            'name',
            'ten',
            'title',
            'label',
            'product_name',
            'brand_name',
            'category_name',
            'code',
            'sku',
            'slug',
        ];

        $picked = null;
        foreach ($candidates as $col) {
            if (Schema::hasColumn($table, $col)) {
                $picked = $col;
                break;
            }
        }

        if ($picked) {
            $q->addSelect(DB::raw("$picked as name"))->orderBy($picked);
        } else {
            $prefix = trim($fallbackPrefix) !== '' ? $fallbackPrefix . ' ' : '';
            $q->addSelect(DB::raw("CONCAT('$prefix#', id) as name"))->orderBy('id');
        }

        if ($table === 'products') {
            $q->limit(300);
        }

        return $q->get();
    }

    public function index(Request $req)
    {
        $q = trim((string)$req->get('q', ''));

        $coupons = Coupon::query()
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->where('code', 'like', "%$q%")
                        ->orWhere('note', 'like', "%$q%");
                });
            })
            ->latest()
            ->paginate(10);

        $products   = $this->pickOptions('products',   \App\Models\Product::class,  'SP');
        $categories = $this->pickOptions('categories', \App\Models\Category::class, 'DM');
        $brands     = $this->pickOptions('brands',     \App\Models\Brand::class,    'TH');

        // Lưu ý: view của bạn tên 'admin.QL_magiamgia'
        return view('admin.QL_magiamgia', compact('q', 'coupons', 'products', 'categories', 'brands'));
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'code'         => 'required|string|unique:coupons,code',
            'type'         => 'required|in:percent,fixed,free_shipping',
            'value'        => 'nullable|integer|min:0',
            'max_discount' => 'nullable|integer|min:0',
            'min_subtotal' => 'nullable|integer|min:0',
            'apply_scope'  => 'required|in:all,cart,product,category,brand',
            'starts_at'    => 'nullable|date',
            'ends_at'      => 'nullable|date|after_or_equal:starts_at',
            'status'       => 'required|in:active,inactive',
            'note'         => 'nullable|string|max:255',
            'product_ids'  => 'array',
            'category_ids' => 'array',
            'brand_ids'    => 'array',

            // NEW: hạng áp dụng
            'eligible_levels'   => 'nullable|array',
            'eligible_levels.*' => 'in:dong,bac,vang,kim_cuong',
        ]);

        $data['starts_at'] = $data['starts_at'] ? Carbon::parse($data['starts_at']) : null;
        $data['ends_at']   = $data['ends_at']   ? Carbon::parse($data['ends_at'])   : null;

        if ($data['type'] === 'free_shipping') {
            $data['value'] = null;
            $data['max_discount'] = null;
        }

        // NEW: rỗng => null (áp dụng mọi hạng)
        $levels = $req->input('eligible_levels', []);
        $data['eligible_levels'] = (is_array($levels) && count($levels)) ? array_values($levels) : null;

        DB::transaction(function () use ($data, $req) {
            $coupon = Coupon::create($data);

            if ($coupon->apply_scope === 'product' && $req->filled('product_ids')) {
                $coupon->products()->sync($req->input('product_ids', []));
            }
            if ($coupon->apply_scope === 'category' && $req->filled('category_ids')) {
                $coupon->categories()->sync($req->input('category_ids', []));
            }
            if ($coupon->apply_scope === 'brand' && $req->filled('brand_ids')) {
                $coupon->brands()->sync($req->input('brand_ids', []));
            }
        });

        return redirect()->route('admin.coupons.index')->with('success', 'Tạo mã thành công');
    }

    public function update(Request $req, Coupon $coupon)
    {
        $data = $req->validate([
            'code'         => 'required|string|unique:coupons,code,' . $coupon->id,
            'type'         => 'required|in:percent,fixed,free_shipping',
            'value'        => 'nullable|integer|min:0',
            'max_discount' => 'nullable|integer|min:0',
            'min_subtotal' => 'nullable|integer|min:0',
            'apply_scope'  => 'required|in:all,cart,product,category,brand',
            'starts_at'    => 'nullable|date',
            'ends_at'      => 'nullable|date|after_or_equal:starts_at',
            'status'       => 'required|in:active,inactive',
            'note'         => 'nullable|string|max:255',
            'product_ids'  => 'array',
            'category_ids' => 'array',
            'brand_ids'    => 'array',

            // NEW: hạng áp dụng
            'eligible_levels'   => 'nullable|array',
            'eligible_levels.*' => 'in:dong,bac,vang,kim_cuong',
        ]);

        $data['starts_at'] = $data['starts_at'] ? Carbon::parse($data['starts_at']) : null;
        $data['ends_at']   = $data['ends_at']   ? Carbon::parse($data['ends_at'])   : null;

        if ($data['type'] === 'free_shipping') {
            $data['value'] = null;
            $data['max_discount'] = null;
        }

        // NEW
        $levels = $req->input('eligible_levels', []);
        $data['eligible_levels'] = (is_array($levels) && count($levels)) ? array_values($levels) : null;

        DB::transaction(function () use ($coupon, $data, $req) {
            $coupon->update($data);

            // reset các pivots
            $coupon->products()->detach();
            $coupon->categories()->detach();
            $coupon->brands()->detach();

            if ($coupon->apply_scope === 'product' && $req->filled('product_ids')) {
                $coupon->products()->sync($req->input('product_ids', []));
            }
            if ($coupon->apply_scope === 'category' && $req->filled('category_ids')) {
                $coupon->categories()->sync($req->input('category_ids', []));
            }
            if ($coupon->apply_scope === 'brand' && $req->filled('brand_ids')) {
                $coupon->brands()->sync($req->input('brand_ids', []));
            }
        });

        return redirect()->route('admin.coupons.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Xóa thành công');
    }
}
