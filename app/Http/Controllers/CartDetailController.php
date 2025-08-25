<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CartDetailController extends Controller
{
    /* ===== Helpers dùng nội bộ (giống cách tính của mini-cart) ===== */

    private function getItems(int $userId): Collection
    {
        return CartItem::with('product')->where('user_id', $userId)->get();
    }

    private function summarize(Collection $items): array
    {
        $totalQty = (int) $items->sum('quantity');
        $subtotal = 0;
        foreach ($items as $it) {
            $p = $it->product;
            if (!$p) continue;
            $price = $p->gia_khuyen_mai ?? $p->gia ?? 0;
            $subtotal += $price * $it->quantity;
        }
        return [$totalQty, $subtotal];
    }

    private function renderMini(int $userId): array
    {
        $items = $this->getItems($userId);
        [$totalQty, $subtotal] = $this->summarize($items);

        $html = View::make('layouts.cart_dropdown', [
            'cartItems'     => $items,
            'totalQuantity' => $totalQty,
            'subtotal'      => $subtotal,
        ])->render();

        return [$html, $totalQty, $subtotal];
    }

    private function availableStock(Product $p): int
    {
        if (Schema::hasColumn('products', 'stock')) {
            return max(0, (int) $p->stock);
        }
        return 999999;
    }

    /* ====== Pages ====== */

    // GET /cart
    public function index()
    {
        $userId = Auth::id();
        $cartItems = $userId
            ? CartItem::with('product')->where('user_id', $userId)->get()
            : collect();

        // ĐÚNG:
        return view('layouts.chitietgiohang', compact('cartItems'));
    }

    // GET /checkout (tùy bạn làm tiếp)
    public function checkout()
    {
        $userId = Auth::id();
        $cartItems = $userId
            ? CartItem::with('product')->where('user_id', $userId)->get()
            : collect();

        // ĐÚNG:
        return view('layouts.chitietgiohang', compact('cartItems'));
    }

    /* ====== AJAX cho trang chi tiết giỏ hàng ====== */

    // POST /cart/update  (body: {product_id, quantity})
    public function updateQuantityAjax(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['status' => 'error', 'message' => 'Bạn cần đăng nhập'], 401);
        }

        try {
            $lineTotal = 0;

            DB::transaction(function () use ($request, $userId, &$lineTotal) {
                $product = Product::lockForUpdate()->findOrFail((int)$request->product_id);

                $stock = $this->availableStock($product);
                $qty   = min((int)$request->quantity, 99);
                if ($qty > $stock) {
                    abort(422, 'Số lượng yêu cầu vượt quá tồn kho');
                }

                $item = CartItem::where('user_id', $userId)
                    ->where('product_id', $product->id)
                    ->lockForUpdate()
                    ->first();

                if ($item) {
                    $item->update(['quantity' => $qty]);
                } else {
                    CartItem::create([
                        'user_id'    => $userId,
                        'product_id' => $product->id,
                        'quantity'   => $qty,
                    ]);
                }

                $price     = (int) ($product->gia_khuyen_mai ?? $product->gia ?? 0);
                $lineTotal = $price * $qty;
            });

            $items = $this->getItems($userId);
            [$totalQty, $subtotal] = $this->summarize($items);
            [$miniHtml] = $this->renderMini($userId);

            return response()->json([
                'status'        => 'success',
                'message'       => 'Đã cập nhật số lượng',
                'lineTotal'     => $lineTotal,
                'cartTotal'     => $subtotal,
                'totalQuantity' => $totalQty,
                'html'          => $miniHtml,
            ]);
        } catch (HttpException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], $e->getStatusCode());
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => 'Có lỗi xảy ra'], 500);
        }
    }

    // POST /cart/remove  (body: {product_id})
    public function removeByProduct(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ]);

        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['status' => 'error', 'message' => 'Bạn cần đăng nhập'], 401);
        }

        CartItem::where('user_id', $userId)
            ->where('product_id', (int)$request->product_id)
            ->delete();

        $items = $this->getItems($userId);
        [$totalQty, $subtotal] = $this->summarize($items);
        [$miniHtml] = $this->renderMini($userId);

        return response()->json([
            'status'        => 'success',
            'message'       => 'Đã xoá sản phẩm khỏi giỏ',
            'cartTotal'     => $subtotal,
            'totalQuantity' => $totalQty,
            'html'          => $miniHtml,
        ]);
    }

    // POST /cart/clear
    public function clear()
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['status' => 'error', 'message' => 'Bạn cần đăng nhập'], 401);
        }

        CartItem::where('user_id', $userId)->delete();

        [$miniHtml] = $this->renderMini($userId);

        return response()->json([
            'status'        => 'success',
            'message'       => 'Đã xoá toàn bộ giỏ hàng',
            'cartTotal'     => 0,
            'totalQuantity' => 0,
            'html'          => $miniHtml,
        ]);
    }
}
