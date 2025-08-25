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

class CartController extends Controller
{
    /* =======================
     |  Helpers (private)
     * ======================= */

    /** Lấy toàn bộ item của user hiện tại (kèm product) */
    private function getItems(int $userId): Collection
    {
        return CartItem::with('product')->where('user_id', $userId)->get();
    }

    /** Tính tổng SL & subtotal từ danh sách */
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

    /** Render mini-cart (HTML + totalQty + subtotal) */
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

    /** Lấy tồn khả dụng (có cột stock thì check, không có xem như vô hạn) */
    private function availableStock(Product $p): int
    {
        if (Schema::hasColumn('products', 'stock')) {
            return max(0, (int) $p->stock);
        }
        return 999999;
    }

    /** Tìm product có thể bán (nếu có cột is_active thì phải =1) + lock */
    private function mustFindSalableProduct(int $productId): Product
    {
        $q = Product::query()->whereKey($productId);
        if (Schema::hasColumn('products', 'is_active')) {
            $q->where('is_active', 1);
        }
        $p = $q->lockForUpdate()->first();
        if (!$p) {
            abort(404, 'Không tìm thấy sản phẩm hoặc đã ngừng bán');
        }
        return $p;
    }

    /**
     * Tự động trả response:
     * - AJAX: JSON {status, message?, totalQuantity, html}
     * - Non-AJAX: redirect back + flash open_cart để header tự mở dropdown
     */
    private function respondCart(Request $request, int $userId, ?string $message = null)
    {
        [$html, $totalQty] = $this->renderMini($userId);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status'        => 'success',
                'message'       => $message,
                'totalQuantity' => $totalQty,
                'html'          => $html,
            ]);
        }

        // Non-AJAX: reload + mở dropdown
        return redirect()->back()->with([
            'open_cart'     => true,
            'cart_message'  => $message,
        ]);
    }

    /* =======================
     |  Actions (public)
     * ======================= */

    // POST /cart/add
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'qty'        => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $userId = Auth::id();
        if (!$userId) {
            return ($request->ajax() || $request->wantsJson())
                ? response()->json(['status' => 'error', 'message' => 'Bạn cần đăng nhập để thêm sản phẩm'], 401)
                : redirect()->route('login');
        }

        $qtyReq = (int) $request->input('qty', 1);

        try {
            DB::transaction(function () use ($request, $userId, $qtyReq) {
                $product = $this->mustFindSalableProduct((int) $request->product_id);
                $stock   = $this->availableStock($product);
                if ($stock <= 0) abort(422, 'Sản phẩm tạm hết hàng');

                $item = CartItem::where('user_id', $userId)
                    ->where('product_id', $product->id)
                    ->lockForUpdate()
                    ->first();

                $newQty = $item ? min(99, $item->quantity + $qtyReq) : $qtyReq;
                if ($newQty > $stock) {
                    // Nếu đã đầy kho rồi thì báo lỗi
                    if (!$item || $item->quantity >= $stock) {
                        abort(422, 'Số lượng yêu cầu vượt quá tồn kho');
                    }
                    $newQty = $stock;
                }

                if ($item) {
                    $item->update(['quantity' => $newQty]);
                } else {
                    CartItem::create([
                        'user_id'    => $userId,
                        'product_id' => $product->id,
                        'quantity'   => $newQty,
                    ]);
                }
            });

            return $this->respondCart($request, $userId, 'Đã thêm sản phẩm vào giỏ hàng!');
        } catch (HttpException $e) {
            $code = $e->getStatusCode();
            $msg  = $e->getMessage();
            return ($request->ajax() || $request->wantsJson())
                ? response()->json(['status' => 'error', 'message' => $msg], $code)
                : back()->with('error', $msg);
        } catch (\Throwable $e) {
            return ($request->ajax() || $request->wantsJson())
                ? response()->json(['status' => 'error', 'message' => 'Có lỗi xảy ra, vui lòng thử lại'], 500)
                : back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại');
        }
    }

    // PATCH /cart/{product}/qty
    public function updateQuantity(Request $request, Product $product)
    {
        $request->validate([
            'qty' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        $userId = Auth::id();
        if (!$userId) {
            return ($request->ajax() || $request->wantsJson())
                ? response()->json(['status' => 'error', 'message' => 'Bạn cần đăng nhập'], 401)
                : redirect()->route('login');
        }

        try {
            DB::transaction(function () use ($userId, $product, $request) {
                $stock = $this->availableStock($product);
                $qty   = (int) $request->qty;

                $item = CartItem::where('user_id', $userId)
                    ->where('product_id', $product->id)
                    ->lockForUpdate()
                    ->first();

                if ($qty === 0) {
                    if ($item) $item->delete();
                    return;
                }

                if ($qty > $stock) abort(422, 'Số lượng yêu cầu vượt quá tồn kho');

                if ($item) {
                    $item->update(['quantity' => $qty]);
                } else {
                    CartItem::create([
                        'user_id'    => $userId,
                        'product_id' => $product->id,
                        'quantity'   => $qty
                    ]);
                }
            });

            return $this->respondCart($request, $userId, 'Đã cập nhật số lượng');
        } catch (HttpException $e) {
            return ($request->ajax() || $request->wantsJson())
                ? response()->json(['status' => 'error', 'message' => $e->getMessage()], $e->getStatusCode())
                : back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return ($request->ajax() || $request->wantsJson())
                ? response()->json(['status' => 'error', 'message' => 'Có lỗi xảy ra'], 500)
                : back()->with('error', 'Có lỗi xảy ra');
        }
    }

    // DELETE /cart/{id}
    public function remove(Request $request, $id)
    {
        $userId = Auth::id();
        if (!$userId) {
            return ($request->ajax() || $request->wantsJson())
                ? response()->json(['status' => 'error', 'message' => 'Bạn cần đăng nhập'], 401)
                : redirect()->route('login');
        }

        $item = CartItem::where('id', $id)->where('user_id', $userId)->firstOrFail();
        $item->delete();

        if ($request->ajax() || $request->wantsJson()) {
            [$html, $totalQty] = $this->renderMini($userId);
            return response()->json([
                'status'        => 'success',
                'message'       => 'Đã xóa sản phẩm khỏi giỏ',
                'totalQuantity' => $totalQty,
                'html'          => $html,
            ]);
        }

        return back()->with([
            'success'    => 'Đã xóa sản phẩm khỏi giỏ hàng',
            'open_cart'  => true,
        ]);
    }

    // GET /cart/mini  -> trả HTML cho dropdown
    public function mini()
    {
        $userId = Auth::id();
        $items = $userId ? $this->getItems($userId) : collect();
        [$totalQty, $subtotal] = $this->summarize($items);

        return view('layouts.cart_dropdown', [
            'cartItems'     => $items,
            'totalQuantity' => $totalQty,
            'subtotal'      => $subtotal,
        ]);
    }

    // GET /cart/summary -> số liệu cho badge hoặc header
    public function summary()
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['items_count' => 0, 'subtotal' => 0]);
        }
        $items = $this->getItems($userId);
        [$count, $subtotal] = $this->summarize($items);
        return response()->json(['items_count' => $count, 'subtotal' => $subtotal]);
    }

    /* Tuỳ dự án có hay không:
       public function index() { ... }
       public function checkout() { ... }
    */
}
