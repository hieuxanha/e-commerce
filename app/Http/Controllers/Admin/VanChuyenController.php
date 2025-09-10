<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VanChuyenController extends Controller
{
    public function index(Request $request)
    {
        // Gom filter gọn lại, paginate kèm query string
        $q = Order::query()->latest();

        if ($code = $request->code)           $q->where('code', 'like', "%{$code}%");
        if ($s = $request->status)            $q->where('status', $s);
        if ($p = $request->payment_status)    $q->where('payment_status', $p);

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
                $oldStatus        = $order->status;
                $oldPaymentStatus = $order->payment_status;

                // 1) Cập nhật trạng thái vận chuyển
                $order->status = $request->status;

                // 2) Nếu chọn "Đã giao" => coi như đã thanh toán (đúng luồng COD của bạn)
                if ($order->status === 'da_giao') {
                    if ($order->payment_status !== 'da_thanh_toan') {
                        $order->payment_status = 'da_thanh_toan'; // enum có sẵn:contentReference[oaicite:1]{index=1}
                        $order->paid_at        = now();
                    }
                }

                $order->save();

                // 3) Trừ tồn kho chỉ khi chuyển sang đã giao và trước đó CHƯA "đã thanh toán"
                //    -> tránh trừ lặp khi admin bấm Cập nhật nhiều lần
                if (
                    $order->status === 'da_giao'
                    && $oldPaymentStatus !== 'da_thanh_toan'
                ) {
                    // Lấy chi tiết đơn (order_items):contentReference[oaicite:2]{index=2}
                    $order->loadMissing('items');

                    foreach ($order->items as $item) {
                        $product = Product::find($item->product_id);
                        if (!$product) {
                            continue;
                        }

                        // Không cho âm kho (tuỳ bạn, có thể đổi sang throw nếu muốn chặn)
                        $newQty = max(0, $product->so_luong_ton_kho - $item->quantity); // cột tồn kho:contentReference[oaicite:3]{index=3}
                        $product->so_luong_ton_kho = $newQty;
                        $product->save();
                    }
                }
            });

            return back()->with('success', 'Cập nhật trạng thái thành công.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Có lỗi khi cập nhật: ' . $e->getMessage());
        }
    }
}
