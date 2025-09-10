<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerAdminController extends Controller
{
    public function index(Request $r)
    {
        $q       = trim($r->get('q', ''));
        $perPage = (int) $r->get('per_page', 20) ?: 20;

        $users = User::query()
            ->where('role', 'khach_hang')
            ->when($q !== '', function ($s) use ($q) {
                $s->where(function ($x) use ($q) {
                    $x->where('name', 'like', "%$q%")
                        ->orWhere('email', 'like', "%$q%")
                        ->orWhere('phone', 'like', "%$q%");
                });
            })
            // 👇 gom thống kê theo user
            ->withCount('orders')
            ->withSum('orders', 'total')        // => orders_sum_total
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        $stats = [
            'total'     => User::count(),
            'admins'    => User::where('role', 'admin')->count(),
            'staff'     => User::where('role', 'nhan_vien')->count(),
            'customers' => User::where('role', 'khach_hang')->count(),
        ];

        return view('admin.QL_khachhang', compact('users', 'q', 'stats'));
    }

    public function destroy(User $user)
    {
        // chỉ cho xóa khách hàng
        if ($user->role !== 'khach_hang') {
            return back()->with('error', 'Chỉ được xóa khách hàng.');
        }

        $user->loadCount('orders');
        if ($user->orders_count > 0) {
            return back()->with('error', 'Không thể xóa: khách hàng đã có đơn hàng.');
        }

        $user->delete(); // nếu muốn an toàn hơn: soft delete
        return back()->with('success', 'Đã xóa khách hàng.');
    }
}
