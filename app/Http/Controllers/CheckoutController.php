<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;
use App\Models\Product;

class CheckoutController extends Controller
{
    /**
     * Hiển thị trang thông tin giao hàng (lấy data từ CartItem DB).
     */
    public function index()
    {
        // Bắt đăng nhập (nếu muốn thì bỏ if, nhưng giỏ DB của bạn đang theo user_id)
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục đặt hàng.');
        }

        $userId = Auth::id();
        $items = CartItem::with('product')->where('user_id', $userId)->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống, vui lòng chọn sản phẩm.');
        }

        // Chuyển về mảng theo đúng cấu trúc mà view thongtingiaohang.blade.php đang expect
        $cartItems = $items->map(function ($ci) {
            $p = $ci->product;
            if (!$p) return null;

            // Ảnh chính
            $img = $p->hinh_anh_chinh
                ? (preg_match('/^https?:\/\//', $p->hinh_anh_chinh)
                    ? $p->hinh_anh_chinh
                    : asset('storage/' . $p->hinh_anh_chinh))
                : asset('images/no-image.png');

            $price = (int)($p->gia_khuyen_mai ?? $p->gia ?? 0);

            return [
                'name'    => $p->ten_san_pham,
                'image'   => $img,
                'price'   => $price,
                'qty'     => (int)$ci->quantity,
                // các trường tùy chọn để view không lỗi
                'variant' => null,
                'color'   => null,
                'size'    => null,
            ];
        })->filter()->values()->toArray();

        $subtotal = array_reduce($cartItems, fn($c, $it) => $c + ($it['price'] * $it['qty']), 0);
        $shipping = 0; // tuỳ logic tính phí ship của bạn
        $total    = $subtotal + $shipping;

        // Nếu bạn có danh mục tỉnh/quận/phường thì truyền thêm $provinces/$districts/$wards ở đây
        return view('layouts.thongtingiaohang', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    public function placed(Request $request)
    {
        $data = $request->session()->get('placed_order');
        if (!$data) {
            return redirect()->route('home')->with('info', 'Không có đơn vừa đặt.');
        }
        return view('layouts.dadathang', $data);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'fullname'        => 'required|string|max:255',
            'email'           => 'required|email',
            'phone'           => 'required|string|max:30',
            'address'         => 'required|string|max:255',
            'province_id'     => 'required',
            'district_id'     => 'required',
            'ward_id'         => 'required',
            'payment_method'  => 'required|in:cod,vnpay',
        ]);

        if ($request->payment_method !== 'cod') {
            // tạm thời chỉ hiển thị trang đã đặt hàng cho COD
            return back()->with('info', 'Hiện tại chỉ hỗ trợ COD.');
        }

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập.');
        }

        $userId = Auth::id();
        $items = CartItem::with('product')->where('user_id', $userId)->get();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống.');
        }

        // Chuẩn bị dữ liệu hiển thị
        $lines = [];
        $subtotal = 0;

        foreach ($items as $ci) {
            $p = $ci->product;
            if (!$p) continue;
            $price = (int)($p->gia_khuyen_mai ?? $p->gia ?? 0);
            $qty   = (int)$ci->quantity;
            $line  = $price * $qty;

            $subtotal += $line;
            $lines[] = [
                'name'  => $p->ten_san_pham ?? ('SP #' . $p->id),
                'qty'   => $qty,
                'price' => $price,
                'total' => $line,
                'image' => $p->hinh_anh_chinh
                    ? (preg_match('#^https?://#', $p->hinh_anh_chinh)
                        ? $p->hinh_anh_chinh
                        : asset('storage/' . str_replace('\\', '/', $p->hinh_anh_chinh)))
                    : asset('images/no-image.png'),
            ];
        }

        $shipping = 0;
        $total    = $subtotal + $shipping;

        $orderCode = 'COD' . now()->format('ymdHis');
        $etaFrom   = now()->addDays(2)->format('d/m');
        $etaTo     = now()->addDays(3)->format('d/m');

        $shippingInfo = [
            'fullname'      => $request->fullname,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'address'       => $request->address,
            'province_name' => $request->province_name,
            'district_name' => $request->district_name,
            'ward_name'     => $request->ward_name,
            'note'          => $request->note,
        ];

        // (nếu muốn gửi email ở đây bạn có thể gọi Mail::to(...)->send(...))

        // chuyển dữ liệu qua trang "Đã đặt hàng"
        return redirect()->route('order.placed')->with('placed_order', [
            'orderCode'    => $orderCode,
            'eta_from'     => $etaFrom,
            'eta_to'       => $etaTo,
            'shippingInfo' => $shippingInfo,
            'items'        => $lines,
            'subtotal'     => $subtotal,
            'shipping'     => $shipping,
            'total'        => $total,
        ]);
    }
}
