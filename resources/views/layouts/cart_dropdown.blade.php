@php
$cartItems = \App\Models\CartItem::with('product')
->where('user_id', optional(auth()->user())->id)
->get();

$totalQuantity = $cartItems->sum('quantity');
$totalAmount = $cartItems->reduce(function ($carry, $item) {
$price = $item->product->gia_khuyen_mai ?? $item->product->gia;
return $carry + $price * $item->quantity;
}, 0);
@endphp

{{-- Danh sách sản phẩm trong giỏ hàng --}}
<div class="cart-items-wrapper" style="max-height: 300px; overflow-y: auto; scrollbar-width: thin; padding-right:5px;">
    @forelse($cartItems as $item)
    @php
    $price = $item->product->gia_khuyen_mai ?? $item->product->gia;
    $itemTotal = $price * $item->quantity;
    $img = $item->product->hinh_anh_chinh
    ? asset('storage/' . $item->product->hinh_anh_chinh)
    : asset('img/placeholder-product.jpg');
    @endphp

    <div class="cart-item d-flex align-items-center justify-content-between"
        style="gap:10px;padding:8px 0;border-bottom:1px solid #eee;">

        <img src="{{ $img }}" width="60" height="60" alt="{{ $item->product->ten_san_pham }}">
        <div style="flex:1;padding:0 10px;">
            <strong>{{ $item->product->ten_san_pham }}</strong><br>
            x{{ $item->quantity }}<br>
            <strong>{{ number_format($itemTotal, 0, ',', '.') }}đ</strong>
        </div>
        <form method="POST" action="{{ route('cart.remove', $item->id) }}" class="cart-remove-form">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger" style="padding:4px 8px;" title="Xóa sản phẩm"> <i class="bi bi-trash"></i> </button>
        </form>
    </div>
    @empty
    <p style="padding:10px">Chưa có sản phẩm trong giỏ hàng.</p>
    @endforelse
</div>

{{-- Tổng cộng và nút thao tác --}}
@if($totalQuantity > 0)
<div class="cart-total" style="padding:10px 0;font-size:14px;">
    Tổng tiền hàng ({{ $totalQuantity }} sản phẩm):
    <strong>{{ number_format($totalAmount, 0, ',', '.') }}đ</strong>
</div>
<a href="{{ route('cart.index') }}" class="btn btn-outline-secondary"
    style="display:block;text-align:center;margin-bottom:8px;">
    XEM GIỎ HÀNG
</a>
<a href="{{ route('checkout.info') }}" class="btn btn-primary"
    style="display:block;text-align:center;">
    THANH TOÁN NGAY
</a>
@endif