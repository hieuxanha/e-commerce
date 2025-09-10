<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Mail\OrderPlacedMail;

class CheckoutController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục đặt hàng.');
        }

        $userId = Auth::id();
        $items  = CartItem::with('product')->where('user_id', $userId)->get();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống, vui lòng chọn sản phẩm.');
        }

        $cartItems = $items->map(function ($ci) {
            $p = $ci->product;
            if (!$p) return null;

            $img   = $this->resolveImageUrl($p->hinh_anh_chinh);
            $price = (int) ($p->gia_khuyen_mai ?? $p->gia ?? 0);

            return [
                'name'  => $p->ten_san_pham,
                'image' => $img,
                'price' => $price,
                'qty'   => (int) $ci->quantity,
            ];
        })->filter()->values()->toArray();

        $subtotal = array_reduce($cartItems, fn($c, $it) => $c + ($it['price'] * $it['qty']), 0);
        $shipping = 0;
        $total    = $subtotal + $shipping;

        return view('layouts.thongtingiaohang', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    public function placed(Request $request)
    {
        $data = $request->session()->get('placed_order');
        if (!$data) return redirect()->route('home')->with('info', 'Không có đơn vừa đặt.');
        return view('layouts.dadathang', $data);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'fullname'       => 'required|string|max:255',
            'email'          => 'required|email',
            'phone'          => 'required|string|max:30',
            'address'        => 'required|string|max:255',
            'province_id'    => 'required',
            'district_id'    => 'required',
            'ward_id'        => 'required',
            'payment_method' => 'required|in:cod',
            'province_name'  => 'nullable|string',
            'district_name'  => 'nullable|string',
            'ward_name'      => 'nullable|string',
            'note'           => 'nullable|string',
        ]);

        if (!Auth::check()) return redirect()->route('login')->with('error', 'Vui lòng đăng nhập.');

        $userId = Auth::id();
        $items  = CartItem::with('product')->where('user_id', $userId)->get();
        if ($items->isEmpty()) return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống.');

        // Build line items
        $lines = [];
        $subtotal = 0;

        foreach ($items as $ci) {
            $p = $ci->product;
            if (!$p) continue;

            $price = (int) ($p->gia_khuyen_mai ?? $p->gia ?? 0);
            $qty   = (int) $ci->quantity;
            $line  = $price * $qty;
            $subtotal += $line;

            // LƯU raw path (products/xxx.jpg) hoặc URL ngoài
            $raw = $this->normalizeImagePath($p->hinh_anh_chinh);

            $lines[] = [
                'product_id' => $p->id,
                'name'       => $p->ten_san_pham ?? ('SP #' . $p->id),
                'qty'        => $qty,
                'price'      => $price,
                'total'      => $line,
                'image'      => $raw,
            ];
        }

        $shipping  = 0;
        $total     = $subtotal + $shipping;
        $orderCode = 'COD' . now()->format('ymdHis');
        $etaFrom   = now()->addDays(2)->format('d/m');
        $etaTo     = now()->addDays(3)->format('d/m');

        $order = DB::transaction(function () use ($request, $userId, $orderCode, $subtotal, $shipping, $total, $lines) {
            $order = Order::create([
                'user_id'        => $userId,
                'code'           => $orderCode,
                'status'         => 'da_dat',
                'fullname'       => $request->fullname,
                'email'          => $request->email,
                'phone'          => $request->phone,
                'address'        => $request->address,
                'province_id'    => $request->province_id,
                'district_id'    => $request->district_id,
                'ward_id'        => $request->ward_id,
                'province_name'  => $request->province_name,
                'district_name'  => $request->district_name,
                'ward_name'      => $request->ward_name,
                'note'           => $request->note,
                'payment_method' => 'cod',
                'payment_status' => 'chua_thanh_toan',
                'subtotal'       => $subtotal,
                'shipping_fee'   => $shipping,
                'total'          => $total,
            ]);

            foreach ($lines as $li) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $li['product_id'] ?? null,
                    'product_name' => $li['name'],
                    'price'        => $li['price'],
                    'quantity'     => $li['qty'],
                    'total'        => $li['total'],
                    'image'        => $li['image'], // raw (products/xxx.jpg hoặc URL ngoài)
                ]);
            }

            CartItem::where('user_id', $userId)->delete();

            return $order->load('items');
        });

        // Gửi mail
        if (!empty($order->email)) {
            Log::info('SEND_ORDER_MAIL', ['order_code' => $order->code, 'to' => $order->email]);
            try {
                Mail::to($order->email)->send(new OrderPlacedMail($order));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        // Data cho trang “Đã đặt hàng” (raw -> URL public để hiển thị WEB)
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

        return redirect()->route('order.placed')->with('placed_order', [
            'orderCode'    => $order->code,
            'eta_from'     => $etaFrom,
            'eta_to'       => $etaTo,
            'shippingInfo' => $shippingInfo,
            'items'        => collect($order->items)->map(function ($i) {
                return [
                    'name'  => $i->product_name,
                    'qty'   => (int) $i->quantity,
                    'price' => (int) $i->price,
                    'total' => (int) $i->total,
                    'image' => $this->resolveImageUrl($i->image) ?: asset('images/no-image.png'),
                ];
            })->all(),
            'subtotal' => (int) $order->subtotal,
            'shipping' => (int) $order->shipping_fee,
            'total'    => (int) $order->total,
        ]);
    }

    // ---- helpers ----

    private function resolveImageUrl(?string $path): string
    {
        if (empty($path)) return asset('images/no-image.png');

        if (preg_match('#^https?://#i', $path)) return $path;

        if (preg_match('#^/?storage/#i', $path)) {
            $normalized = $path[0] === '/' ? $path : "/{$path}";
            return URL::to($normalized);
        }

        $normalized = ltrim(str_replace('\\', '/', $path), '/'); // products/xxx.png
        return Storage::disk('public')->url($normalized);
        //     Chạy được là được lỗi tôi đ quan tâm '-';
    }

    private function normalizeImagePath(?string $path): ?string
    {
        if (empty($path)) return null;

        if (preg_match('#^https?://#i', $path)) return $path; // URL ngoài

        if (preg_match('#^/?storage/#i', $path)) {
            $trimmed = preg_replace('#^/?storage/#i', '', $path);
            return ltrim(str_replace('\\', '/', $trimmed), '/'); // products/xxx.jpg
        }

        return ltrim(str_replace('\\', '/', $path), '/'); // products/xxx.jpg
    }
}
