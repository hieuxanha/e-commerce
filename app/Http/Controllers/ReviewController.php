<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * ================= ADMIN =================
     * Danh sách đánh giá (bộ lọc: trạng thái, user, từ khoá).
     */
    public function adminIndex(Request $request)
    {
        $status  = $request->get('status');
        $userId  = $request->get('user_id');
        $keyword = trim((string) $request->get('kw'));

        $q = Review::with([
            'product:id,ten_san_pham',
            'user:id,name,email',
            'replies.admin',
        ])->latest();

        if ($status === 'pending') {
            $q->where('approved', 0);
        } elseif ($status === 'approved') {
            $q->where('approved', 1);
        }

        if (!empty($userId)) {
            $q->where('user_id', (int) $userId);
        }

        if ($keyword !== '') {
            $q->where(function ($w) use ($keyword) {
                $w->whereHas('product', function ($p) use ($keyword) {
                    $p->where('ten_san_pham', 'like', '%' . $keyword . '%');
                })->orWhereHas('user', function ($u) use ($keyword) {
                    $u->where('name', 'like', '%' . $keyword . '%')
                        ->orWhere('email', 'like', '%' . $keyword . '%');
                })->orWhere('comment', 'like', '%' . $keyword . '%');
            });
        }

        $countAll      = Review::count();
        $countPending  = Review::where('approved', 0)->count();
        $countApproved = Review::where('approved', 1)->count();

        $reviews = $q->paginate(15)->withQueryString();

        return view('admin.danhgia', compact(
            'reviews',
            'status',
            'userId',
            'keyword',
            'countAll',
            'countPending',
            'countApproved'
        ));
    }

    /**
     * Duyệt hiển thị đánh giá.
     */
    public function approve(Review $review)
    {
        $review->approved = 1;
        $review->save();

        return back()->with('success', 'Đã duyệt đánh giá.');
    }

    /**
     * Ẩn đánh giá (giữ nguyên trong DB, không hiển thị ngoài trang khách).
     */
    public function hide(Review $review)
    {
        $review->approved = 0;
        $review->save();

        return back()->with('success', 'Đã ẩn đánh giá.');
    }

    /**
     * Xoá đánh giá.
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Đã xoá đánh giá.');
    }

    /**
     * ================= FRONT =================
     * Lưu đánh giá từ người dùng.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->withErrors('Vui lòng đăng nhập để đánh giá.');
        }

        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'rating'     => ['required', 'integer', 'min:1', 'max:5'],
            'comment'    => ['nullable', 'string', 'max:1000'],
        ]);

        $product = \App\Models\Product::findOrFail($data['product_id']);

        // Không cho đánh giá trùng của cùng user cho cùng sản phẩm
        $exists = Review::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->exists();

        if ($exists) {
            $backUrl = route('sanpham.chitiet.id', ['id' => $product->id]) . '#review-form';
            return redirect($backUrl)->withErrors('Bạn đã đánh giá sản phẩm này rồi.');
        }

        Review::create([
            'product_id' => $product->id,
            'user_id'    => $user->id,
            'rating'     => (int) $data['rating'],
            'comment'    => $data['comment'] ?? null,
            'approved'   => 0, // chờ duyệt
        ]);

        return redirect(route('sanpham.chitiet.id', ['id' => $product->id]) . '#reviews')
            ->with('success', 'Cảm ơn bạn! Đánh giá đã được ghi nhận và sẽ hiển thị sau khi được duyệt.');
    }

    /**
     * ================= ADMIN REPLY =================
     * Admin phản hồi đánh giá.
     */
    public function reply(Request $request, Review $review)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        ReviewReply::create([
            'review_id' => $review->id,
            'admin_id'  => Auth::id(),
            'content'   => $request->input('content'),
        ]);

        return back()->with('success', 'Phản hồi đã được gửi.');
    }
}
