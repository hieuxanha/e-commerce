<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema; // thêm import
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
use App\Models\Coupon;
use App\Models\Product; // <-- NHỚ IMPORT
use App\Mail\OrderPlacedMail;
use Carbon\Carbon;
use Illuminate\Support\Arr;

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
                'product_id'  => $p->id,
                'category_id' => $p->category_id ?? null,
                'brand_id'    => $p->brand_id ?? null,
                'name'        => $p->ten_san_pham,
                'image'       => $img,
                'price'       => $price,
                'qty'         => (int) $ci->quantity,
            ];
        })->filter()->values()->toArray();

        $subtotal = array_reduce($cartItems, fn($c, $it) => $c + ($it['price'] * $it['qty']), 0);
        $shipping = 0;

        $applied = session('applied_coupon'); // array|null
        [$discount, $finalShipping] = $this->computeDiscountAndShipping($applied, $cartItems, $subtotal, $shipping);
        $total = max(0, $subtotal + $finalShipping - $discount);

        return view('layouts.thongtingiaohang', [
            'cartItems'     => $cartItems,
            'subtotal'      => (int) $subtotal,
            'shipping'      => (int) $finalShipping,
            'total'         => (int) $total,
            'appliedCoupon' => $applied,
            'discount'      => (int) $discount,
        ]);
    }

    public function placed(Request $request)
    {
        $data = $request->session()->pull('placed_order');
        if (!$data) {
            return redirect()->route('home')->with('info', 'Không có đơn vừa đặt.');
        }
        return view('layouts.dadathang', $data);
    }

    /** Áp dụng mã giảm giá (AJAX) */
    public function applyCoupon(Request $request)
    {
        $request->validate(['coupon_code' => 'required|string']);

        if (!Auth::check()) {
            return response()->json(['ok' => false, 'message' => 'Bạn cần đăng nhập.'], 401);
        }

        $code = strtoupper(trim($request->input('coupon_code')));
        $now  = Carbon::now();

        $coupon = Coupon::where('code', $code)
            ->where('status', 'active')
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->first();

        if (!$coupon) {
            return response()->json(['ok' => false, 'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.'], 422);
        }

        $userId = Auth::id();
        $items  = CartItem::with('product')->where('user_id', $userId)->get();
        if ($items->isEmpty()) {
            return response()->json(['ok' => false, 'message' => 'Giỏ hàng trống.'], 422);
        }

        $cartItems = $items->map(function ($ci) {
            $p = $ci->product;
            if (!$p) return null;
            $price = (int) ($p->gia_khuyen_mai ?? $p->gia ?? 0);
            return [
                'product_id'  => $p->id,
                'category_id' => $p->category_id ?? null,
                'brand_id'    => $p->brand_id ?? null,
                'price'       => $price,
                'qty'         => (int) $ci->quantity,
            ];
        })->filter()->values()->toArray();

        $subtotal = array_reduce($cartItems, fn($c, $it) => $c + ($it['price'] * $it['qty']), 0);
        $shipping = 0;

        $couponArr = [
            'id'           => $coupon->id,
            'code'         => $coupon->code,
            'type'         => $coupon->type,
            'value'        => (int) $coupon->value,
            'max_discount' => (int) $coupon->max_discount,
            'min_subtotal' => (int) $coupon->min_subtotal,
            'apply_scope'  => $coupon->apply_scope,
        ];

        $eligibleSubtotal = $this->eligibleSubtotalByScope(
            $couponArr['apply_scope'] ?? 'cart',
            $couponArr['id'] ?? null,
            $cartItems,
            $subtotal
        );
        if ($eligibleSubtotal <= 0) {
            return response()->json(['ok' => false, 'message' => 'Mã giảm giá không áp dụng được.'], 422);
        }

        $min = (int) ($couponArr['min_subtotal'] ?? 0);
        if ($min > 0 && $subtotal < $min) {
            return response()->json([
                'ok' => false,
                'message' => 'Mã áp dụng cho đơn từ ' . number_format($min, 0, ',', '.') . 'đ. Tạm tính hiện tại: ' . number_format($subtotal, 0, ',', '.') . 'đ.'
            ], 422);
        }

        [$discount, $finalShipping] = $this->computeDiscountAndShipping($couponArr, $cartItems, $subtotal, $shipping);
        session(['applied_coupon' => $couponArr]);
        $total = max(0, $subtotal + $finalShipping - $discount);

        return response()->json([
            'ok'          => true,
            'message'     => 'Áp mã thành công.',
            'coupon_code' => $coupon->code,
            'subtotal'    => (int) $subtotal,
            'shipping'    => (int) $finalShipping,
            'discount'    => (int) $discount,
            'total'       => (int) $total,
        ]);
    }

    /** Gỡ mã giảm giá (AJAX) */
    public function removeCoupon(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['ok' => false, 'message' => 'Bạn cần đăng nhập.'], 401);
        }

        session()->forget('applied_coupon');

        $userId = Auth::id();
        $items  = CartItem::with('product')->where('user_id', $userId)->get();
        if ($items->isEmpty()) {
            return response()->json([
                'ok' => true,
                'message' => 'Đã gỡ mã.',
                'subtotal' => 0,
                'shipping' => 0,
                'discount' => 0,
                'total' => 0
            ]);
        }

        $cartItems = $items->map(function ($ci) {
            $p = $ci->product;
            if (!$p) return null;
            $price = (int) ($p->gia_khuyen_mai ?? $p->gia ?? 0);
            return [
                'product_id'  => $p->id,
                'category_id' => $p->category_id ?? null,
                'brand_id'    => $p->brand_id ?? null,
                'price'       => $price,
                'qty'         => (int) $ci->quantity,
            ];
        })->filter()->values()->toArray();

        $subtotal = array_reduce($cartItems, fn($c, $it) => $c + ($it['price'] * $it['qty']), 0);
        $shipping = 0;

        return response()->json([
            'ok'       => true,
            'message'  => 'Đã gỡ mã.',
            'subtotal' => (int) $subtotal,
            'shipping' => (int) $shipping,
            'discount' => 0,
            'total'    => (int) max(0, $subtotal + $shipping),
        ]);
    }

    /** Alias clearCoupon */
    public function clearCoupon(Request $request)
    {
        return $this->removeCoupon($request);
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
            'payment_method' => 'required|in:cod,vnpay',
            'province_name'  => 'nullable|string',
            'district_name'  => 'nullable|string',
            'ward_name'      => 'nullable|string',
            'note'           => 'nullable|string|max:1000',
        ], [
            'fullname.required' => 'Vui lòng nhập họ và tên.',
            'email.required'    => 'Vui lòng nhập email.',
            'email.email'       => 'Email không đúng định dạng.',
            'phone.required'    => 'Vui lòng nhập số điện thoại.',
            'address.required'  => 'Vui lòng nhập địa chỉ.',
            'province_id.required' => 'Vui lòng chọn tỉnh / thành.',
            'district_id.required' => 'Vui lòng chọn quận / huyện.',
            'ward_id.required'     => 'Vui lòng chọn phường / xã.',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán.',
            'payment_method.in'      => 'Phương thức thanh toán không hợp lệ.',
            'note.max'               => 'Ghi chú tối đa 1000 ký tự.',
        ]);

        if (!Auth::check()) return redirect()->route('login')->with('error', 'Vui lòng đăng nhập.');

        $userId = Auth::id();
        $items  = CartItem::with('product')->where('user_id', $userId)->get();
        if ($items->isEmpty()) return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống.');

        $lines = [];
        $subtotal = 0;

        foreach ($items as $ci) {
            $p = $ci->product;
            if (!$p) continue;

            $price = (int) ($p->gia_khuyen_mai ?? $p->gia ?? 0);
            $qty   = (int) $ci->quantity;
            $line  = $price * $qty;
            $subtotal += $line;

            $raw = $this->normalizeImagePath($p->hinh_anh_chinh);

            $lines[] = [
                'product_id'  => $p->id,
                'category_id' => $p->category_id ?? null,
                'brand_id'    => $p->brand_id ?? null,
                'name'        => $p->ten_san_pham ?? ('SP #' . $p->id),
                'qty'         => $qty,
                'price'       => $price,
                'total'       => $line,
                'image'       => $raw,
            ];
        }

        $shipping = 0;

        $applied = session('applied_coupon');
        if ($applied) {
            $cartItemsForCheck = array_map(function ($li) {
                return [
                    'product_id'  => $li['product_id'],
                    'category_id' => $li['category_id'],
                    'brand_id'    => $li['brand_id'],
                    'price'       => $li['price'],
                    'qty'         => $li['qty'],
                ];
            }, $lines);

            $eligibleSubtotal = $this->eligibleSubtotalByScope(
                $applied['apply_scope'] ?? 'cart',
                $applied['id'] ?? null,
                $cartItemsForCheck,
                $subtotal
            );
            $min = (int) ($applied['min_subtotal'] ?? 0);

            if ($eligibleSubtotal <= 0) {
                session()->forget('applied_coupon');
                return back()->withInput()->withErrors(['coupon' => 'Mã giảm giá không áp dụng cho được.']);
            }
            if ($min > 0 && $subtotal < $min) {
                session()->forget('applied_coupon');
                return back()->withInput()->withErrors(['coupon' => 'Mã áp dụng cho đơn từ ' . number_format($min, 0, ',', '.') . 'đ. Tạm tính hiện tại: ' . number_format($subtotal, 0, ',', '.') . 'đ.']);
            }
        }

        $cartItems = array_map(function ($li) {
            return [
                'product_id'  => $li['product_id'],
                'category_id' => $li['category_id'],
                'brand_id'    => $li['brand_id'],
                'price'       => $li['price'],
                'qty'         => $li['qty'],
            ];
        }, $lines);

        [$discount, $finalShipping] = $this->computeDiscountAndShipping($applied, $cartItems, $subtotal, $shipping);
        $total = max(0, $subtotal + $finalShipping - $discount);

        $orderCode = strtoupper(($request->payment_method === 'cod' ? 'COD' : 'PAY')) . now()->format('ymdHis');
        $etaFrom   = now()->addDays(2)->format('d/m');
        $etaTo     = now()->addDays(3)->format('d/m');

        try {
            $order = DB::transaction(function () use ($request, $userId, $orderCode, $subtotal, $finalShipping, $discount, $total, $lines) {

                // (1) Kiểm tra & trừ kho NGAY, khóa dòng để tránh race condition
                foreach ($lines as $li) {
                    $p = Product::lockForUpdate()->find($li['product_id']);
                    if (!$p) {
                        throw new \RuntimeException("Sản phẩm #{$li['product_id']} không tồn tại.");
                    }
                    $avai = (int) ($p->so_luong_ton_kho ?? 0);
                    if ($avai < (int) $li['qty']) {
                        throw new \RuntimeException("{$p->ten_san_pham} chỉ còn {$avai}, không đủ {$li['qty']}.");
                    }
                }
                foreach ($lines as $li) {
                    $p = Product::lockForUpdate()->find($li['product_id']);
                    $p->decrement('so_luong_ton_kho', (int) $li['qty']);
                }

                // (2) Tạo đơn
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
                    'payment_method' => $request->payment_method,
                    'payment_status' => 'chua_thanh_toan', // COD
                    'subtotal'       => (int) $subtotal,
                    'shipping_fee'   => (int) $finalShipping,
                    'total'          => (int) $total,
                    'stock_applied'  => true, // đã trừ kho
                ]);

                // (3) Items
                foreach ($lines as $li) {
                    OrderItem::create([
                        'order_id'     => $order->id,
                        'product_id'   => $li['product_id'] ?? null,
                        'product_name' => $li['name'],
                        'price'        => (int) $li['price'],
                        'quantity'     => (int) $li['qty'],
                        'total'        => (int) $li['total'],
                        'image'        => $li['image'],
                    ]);
                }

                // (4) Xóa giỏ
                CartItem::where('user_id', $userId)->delete();

                return $order->load('items');
            });
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors([
                'stock' => $e->getMessage() ?: 'Không đủ tồn kho cho một số sản phẩm.'
            ]);
        }

        if (!empty($order->email)) {
            Log::info('SEND_ORDER_MAIL', ['order_code' => $order->code, 'to' => $order->email]);
            try {
                Mail::to($order->email)->send(new OrderPlacedMail($order));
            } catch (\Throwable $e) {
                report($e);
            }
        }

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

        session()->forget('applied_coupon');

        session()->put('placed_order', [
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
            'subtotal'    => (int) $order->subtotal,
            'shipping'    => (int) $order->shipping_fee,
            'total'       => (int) $order->total,
            'discount'    => (int) ($discount ?? 0),
            'coupon_code' => $applied['code'] ?? null,
        ]);

        return redirect()->route('order.placed');
    }

    // ================= Helpers =================

    private function computeDiscountAndShipping(?array $coupon, array $cartItems, int $subtotal, int $shipping): array
    {
        if (!$coupon) return [0, (int) $shipping];

        $min = (int) ($coupon['min_subtotal'] ?? 0);

        $eligibleSubtotal = $this->eligibleSubtotalByScope(
            $coupon['apply_scope'] ?? 'cart',
            $coupon['id'] ?? null,
            $cartItems,
            $subtotal
        );

        if ($eligibleSubtotal <= 0 || $subtotal < $min) {
            return [0, (int) $shipping];
        }

        $discount = 0;
        $finalShipping = (int) $shipping;

        switch ($coupon['type']) {
            case 'percent':
                $val = max(0, min(100, (int) ($coupon['value'] ?? 0)));
                $discount = ($eligibleSubtotal * $val) / 100;
                $cap = (int) ($coupon['max_discount'] ?? 0);
                if ($cap > 0) $discount = min($discount, $cap);
                break;

            case 'fixed':
                $discount = (int) ($coupon['value'] ?? 0);
                $discount = min($discount, $eligibleSubtotal);
                break;

            case 'free_shipping':
                $finalShipping = 0;
                break;
        }

        return [(int) round($discount), (int) $finalShipping];
    }

    private function eligibleSubtotalByScope(string $scope, ?int $couponId, array $cartItems, int $subtotal): int
    {
        $scope = $scope ?: 'cart';
        if (in_array($scope, ['all', 'cart'], true)) return $subtotal;
        if (!$couponId) return $subtotal;

        try {
            switch ($scope) {
                case 'product':
                    $ids = DB::table('coupon_products')->where('coupon_id', $couponId)->pluck('product_id')->all();
                    if (!$ids) return 0;
                    return array_reduce($cartItems, function ($carry, $it) use ($ids) {
                        if (in_array($it['product_id'] ?? 0, $ids)) {
                            $carry += ($it['price'] * $it['qty']);
                        }
                        return $carry;
                    }, 0);

                case 'category':
                    $ids = DB::table('coupon_categories')->where('coupon_id', $couponId)->pluck('category_id')->all();
                    if (!$ids) return 0;
                    return array_reduce($cartItems, function ($carry, $it) use ($ids) {
                        if (in_array($it['category_id'] ?? 0, $ids)) {
                            $carry += ($it['price'] * $it['qty']);
                        }
                        return $carry;
                    }, 0);

                case 'brand':
                    $ids = DB::table('coupon_brands')->where('coupon_id', $couponId)->pluck('brand_id')->all();
                    if (!$ids) return 0;
                    return array_reduce($cartItems, function ($carry, $it) use ($ids) {
                        if (in_array($it['brand_id'] ?? 0, $ids)) {
                            $carry += ($it['price'] * $it['qty']);
                        }
                        return $carry;
                    }, 0);

                default:
                    return $subtotal;
            }
        } catch (\Throwable $e) {
            return $subtotal;
        }
    }

    private function resolveImageUrl(?string $path): string
    {
        if (empty($path)) return asset('images/no-image.png');
        if (preg_match('#^https?://#i', $path)) return $path;
        if (preg_match('#^/?storage/#i', $path)) {
            $normalized = $path[0] === '/' ? $path : "/{$path}";
            return URL::to($normalized);
        }
        $normalized = ltrim(str_replace('\\', '/', $path), '/');
        return Storage::disk('public')->url($normalized);
    }

    private function normalizeImagePath(?string $path): ?string
    {
        if (empty($path)) return null;
        if (preg_match('#^https?://#i', $path)) return $path;
        if (preg_match('#^/?storage/#i', $path)) {
            $trimmed = preg_replace('#^/?storage/#i', '', $path);
            return ltrim(str_replace('\\', '/', $trimmed), '/');
        }
        return ltrim(str_replace('\\', '/', $path), '/');
    }


    public function cancel(Request $request)
    {
        $request->validate(['order_code' => 'required|string']);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập.');
        }

        $order = Order::with('items')
            ->where('code', $request->order_code)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return back()->with('error', 'Không tìm thấy đơn hàng.');
        }

        // Chỉ hủy khi đơn còn sớm
        if (!in_array($order->status, ['da_dat', 'cho_chuyen_phat', 'dang_xu_ly'])) {
            return back()->with('error', 'Đơn không thể hủy ở trạng thái hiện tại.');
        }

        // Nếu đã thanh toán online, tùy chính sách của bạn
        if ($order->payment_status === 'da_thanh_toan' && $order->payment_method === 'vnpay') {
            return back()->with('error', 'Đơn đã thanh toán, vui lòng liên hệ CSKH để được hỗ trợ hủy.');
        }

        $restocked = [];

        try {
            DB::transaction(function () use ($order, &$restocked) {
                // Chỉ hoàn kho nếu trước đó đã trừ và chưa hoàn
                if ($order->stock_applied) {
                    foreach ($order->items as $it) {
                        if (!$it->product_id) continue;

                        // Khóa dòng để tránh race condition
                        $p = Product::lockForUpdate()->find($it->product_id);
                        if (!$p) continue;

                        $returned = (int) $it->quantity;
                        $p->increment('so_luong_ton_kho', $returned);
                        $p->refresh();

                        $restocked[] = [
                            'id'       => $p->id,
                            'name'     => $p->ten_san_pham,
                            'returned' => $returned,
                            'now'      => (int) ($p->so_luong_ton_kho ?? 0),
                        ];
                    }
                    // Đánh dấu đã hoàn để sau bấm lại không + kho lần nữa
                    $order->stock_applied = false;
                }

                $order->status = 'da_huy';
                if (Schema::hasColumn('orders', 'canceled_at')) {
                    $order->canceled_at = now();
                }
                $order->save();
            });
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Không hủy được đơn. Vui lòng thử lại.');
        }

        // Gửi thông tin hoàn kho về flash để bạn nhìn được kết quả
        return redirect()
            ->route('home')
            ->with('success', 'Đã hủy đơn ' . $order->code . ' và hoàn kho thành công.')
            ->with('restocked', $restocked);
    }
}
