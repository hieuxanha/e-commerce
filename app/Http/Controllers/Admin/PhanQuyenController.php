<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PhanQuyenController extends Controller
{
    // Danh sách + tìm kiếm + lọc + phân trang 10 dòng/trang
    public function index(Request $request)
    {
        $q    = trim((string) $request->input('q'));
        $role = $request->input('role');

        $users = User::query()
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%");
                });
            })
            ->when($role && in_array($role, ['khach_hang', 'nhan_vien', 'admin'], true), function ($qr) use ($role) {
                $qr->where('role', $role);
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->appends($request->query());

        return view('admin.phanquyen', [
            'users' => $users,
            'q'     => $q,
            'role'  => $role,
        ]);
    }

    // Cập nhật vai trò
    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => ['required', Rule::in(['khach_hang', 'nhan_vien', 'admin'])],
        ]);

        // Chống tự đổi vai trò của chính mình (tuỳ bạn, có thể bỏ)
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Bạn không thể thay đổi vai trò của chính mình.');
        }

        $user->update(['role' => $data['role']]);

        return back()->with('success', "Đã cập nhật vai trò cho {$user->email}.");
    }
}
