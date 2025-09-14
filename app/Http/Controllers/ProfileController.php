<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Order;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Trang hồ sơ: hiển thị thông tin người dùng + danh sách đơn.
     */
    public function show()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var User $user */
        $user = Auth::user();

        // 👉 Cập nhật & lấy thống kê cấp bậc
        $loyalty = $user->syncMembershipLevel();

        $orders = Order::visibleForUser($user)
            ->latest()
            ->paginate(10);

        return view('layouts.profile', compact('user', 'orders', 'loyalty'));
    }


    /**
     * Cập nhật thông tin cá nhân (họ tên, email, sđt, địa chỉ).
     * Lưu ý: routes của bạn hiện đang dùng PUT cho profile.update.
     */
    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var User $user */
        $user = Auth::user();

        // Validate
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => [
                'required',
                'string',
                'email',        // bỏ 'lowercase' để tránh lỗi nếu framework chưa hỗ trợ
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone'   => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        // Chuẩn hoá email về lowercase (thay cho rule 'lowercase')
        if (isset($data['email'])) {
            $data['email'] = strtolower($data['email']);
        }

        // Lưu
        $user->fill($data)->save();

        // Hỗ trợ AJAX
        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'user' => $user]);
        }

        // QUAN TRỌNG: tên route phải khớp với routes/web.php (profile.index)
        return redirect()
            ->route('profile.index')
            ->with('status', 'Cập nhật thông tin thành công!');
    }

    /**
     * Xem chi tiết đơn hàng (thuộc user hiện tại).
     */
    public function orderShow(int $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var User $user */
        $user = Auth::user();

        $order = Order::visibleForUser($user)
            ->with(['items.product'])
            ->where('id', $id) // AND với scope
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }
}
