<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;

class CartController extends Controller
{


    public function add(Request $request)
    {
        $id = $request->input('product_id');
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }

        $userId = Auth::id();
        if (!$userId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn cần đăng nhập để thêm sản phẩm'
            ], 401);
        }

        // Kiểm tra xem có sẵn chưa
        $cartItem = CartItem::where('user_id', $userId)
            ->where('product_id', $id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            CartItem::create([
                'user_id' => $userId,
                'product_id' => $id,
                'quantity' => 1,
            ]);
        }

        // Trả về tổng số lượng luôn cho JS cập nhật badge
        $totalQuantity = CartItem::where('user_id', $userId)->sum('quantity');

        return response()->json([
            'status' => 'success',
            'message' => 'Đã thêm sản phẩm vào giỏ hàng!',
            'totalQuantity' => $totalQuantity,
        ]);
    }
    public function remove($id)
    {
        $item = CartItem::findOrFail($id);
        $item->delete();

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng');
    }
}
