<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Order;
use App\Models\User;
use App\Models\Coupon;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Trang hồ sơ: hiển thị thông tin người dùng + danh sách đơn + coupon theo hạng.
     */
    public function show()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var User $user */
        $user = Auth::user();

        // Nếu có hàm tính hạng thành viên thì đồng bộ; nếu chưa có thì bỏ qua để tránh lỗi.
        $loyalty = null;
        if (method_exists($user, 'syncMembershipLevel')) {
            try {
                $loyalty = $user->syncMembershipLevel();
            } catch (\Throwable $e) {
                // ignore
            }
        }

        $orders = Order::visibleForUser($user)
            ->latest()
            ->paginate(10);

        // --- Lấy coupon đang chạy & hợp lệ cho hạng hiện tại ---
        $level  = $user->membership_level ?: 'dong';
        $coupons = $this->queryCouponsForLevel($level);

        return view('layouts.profile', compact('user', 'orders', 'loyalty', 'coupons'));
    }

    /**
     * Cập nhật thông tin cá nhân (họ tên, email, sđt, địa chỉ).
     */
    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var User $user */
        $user = Auth::user();

        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone'   => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        if (isset($data['email'])) {
            $data['email'] = strtolower($data['email']);
        }

        $user->fill($data)->save();

        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'user' => $user]);
        }

        // Tên route hiển thị trang hồ sơ: profile.index (điều chỉnh nếu bạn đặt tên khác)
        return redirect()
            ->route('profile.index')
            ->with('status', 'Cập nhật thông tin thành công!');
    }

    /**
     * Xem chi tiết đơn hàng (phải thuộc về user hiện tại).
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
            ->where('id', $id)
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }

    /**
     * Helper: Truy vấn coupon đang chạy và hợp lệ cho hạng.
     * Ưu tiên dùng scope running() và eligibleForLevel(); nếu model chưa có, fallback điều kiện thủ công.
     */
    private function queryCouponsForLevel(string $level)
    {
        $q = Coupon::query();

        $hasRunningScope = method_exists(Coupon::class, 'scopeRunning');
        $hasEligibleScope = method_exists(Coupon::class, 'scopeEligibleForLevel');

        if ($hasRunningScope) {
            $q->running();
        } else {
            $now = Carbon::now();
            $q->where('status', 'active')
                ->where(function ($x) use ($now) {
                    $x->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
                })
                ->where(function ($x) use ($now) {
                    $x->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
                });
        }

        if ($hasEligibleScope) {
            $q->eligibleForLevel($level);
        } else {
            // cột eligible_levels dạng JSON hoặc NULL (NULL = mọi hạng)
            $q->where(function ($x) use ($level) {
                $x->whereNull('eligible_levels')
                    ->orWhereJsonContains('eligible_levels', $level);
            });
        }

        return $q->orderByDesc('starts_at')->limit(50)->get();
    }
}
