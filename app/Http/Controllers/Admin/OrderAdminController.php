<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderAdminController extends Controller
{
    public function index(Request $request)
    {
        $q = Order::query()->latest();

        if ($request->filled('code')) {
            $q->where('code', 'like', '%' . $request->code . '%');
        }
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }
        if ($request->filled('payment_status')) {
            $q->where('payment_status', $request->payment_status);
        }

        $orders = $q->paginate(12);

        return view('admin.ql_vanchuyen', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:da_dat,cho_chuyen_phat,dang_trung_chuyen,da_giao',
        ]);

        $data = ['status' => $request->status];

        // Nếu cập nhật sang "Đã giao" thì set luôn thanh toán
        if ($request->status === 'da_giao') {
            $data['payment_status'] = 'da_thanh_toan';
            $data['paid_at']        = now();
        }

        $order->update($data);   // dùng Eloquent -> sẽ kích hoạt observer (nếu có)

        return back()->with('success', 'Cập nhật trạng thái thành công.');
    }
}
