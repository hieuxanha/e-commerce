<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VanChuyenController extends Controller
{
    public function index(Request $request)
    {
        // Nạp kèm items để hiển thị tóm tắt sản phẩm trong bảng
        $q = Order::query()
            ->with(['items' => function ($q) {
                $q->select('id', 'order_id', 'product_name', 'quantity', 'price', 'total');
            }])
            ->latest();

        if ($code = $request->code)        $q->where('code', 'like', "%{$code}%");
        if ($s = $request->status)         $q->where('status', $s);
        if ($p = $request->payment_status) $q->where('payment_status', $p);

        $orders = $q->paginate(12)->withQueryString();

        return view('admin.ql_vanchuyen', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:da_dat,cho_chuyen_phat,dang_trung_chuyen,da_giao',
        ]);

        try {
            DB::transaction(function () use ($request, $order) {
                // Cập nhật trạng thái vận chuyển
                $order->status = $request->status;

                // Quy ước: nếu là luồng COD → Đã giao xem như đã thanh toán
                if ($order->status === 'da_giao' && $order->payment_status !== 'da_thanh_toan') {
                    $order->payment_status = 'da_thanh_toan';
                    $order->paid_at        = now();
                }
                // Nếu quay lại "Đã đặt" → trả về chưa thanh toán
                elseif ($order->status === 'da_dat') {
                    $order->payment_status = 'chua_thanh_toan';
                    $order->paid_at        = null;
                }

                // KHÔNG trừ tồn kho tại đây
                $order->save();
            });

            return back()->with('success', 'Cập nhật trạng thái thành công.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Có lỗi khi cập nhật: ' . $e->getMessage());
        }
    }
}
