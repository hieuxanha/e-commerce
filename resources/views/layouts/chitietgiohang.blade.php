{{-- resources/views/cart/chitietgiohang.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Thông tin giỏ hàng</title>

    {{-- CSRF cho AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- CSS dùng chung --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">

    <style>
        .cart-page .card {
            border: none;
        }

        .cart-page .table> :not(caption)>*>* {
            vertical-align: middle;
        }

        .qty-btn {
            width: 36px;
            height: 36px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e5e7eb;
            background: #fff;
        }

        .qty-value {
            display: inline-block;
            width: 36px;
            text-align: center;
            font-weight: 600;
        }

        .remove-link {
            color: #6b7280;
            text-decoration: none;
        }

        .remove-link:hover {
            color: #ef4444;
            text-decoration: underline;
        }

        .thumb {
            width: 96px;
            height: 96px;
            object-fit: cover;
            border-radius: 6px;
        }

        .breadcrumb-area {
            background: #f7f7f7;
            padding: 12px 0;
            font-size: .95rem;
            color: #6b7280;
        }

        .cart-summary {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
        }

        .cart-title {
            font-weight: 700;
        }

        .btn-outline-danger {
            border-color: #ef4444;
            color: #ef4444;
        }

        .btn-outline-danger:hover {
            background: #ef4444;
            color: #fff;
        }

        /* Toast */
        #toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 3000;
        }

        .toast-x {
            padding: 12px 14px;
            margin-top: 10px;
            border-radius: 8px;
            color: #fff;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .15);
            opacity: 0;
            transform: translateX(100%);
            transition: all .3s ease;
            max-width: 340px;
            font-size: 14px;
        }

        .toast-x.show {
            opacity: 1;
            transform: translateX(0);
        }

        .toast-ok {
            background: #0d9488;
        }

        .toast-err {
            background: #dc2626;
        }

        @media (max-width: 768px) {
            .thumb {
                width: 76px;
                height: 76px;
            }

            .qty-btn,
            .qty-value {
                width: 32px;
                height: 32px;
            }
        }
    </style>
</head>

<body class="cart-page">
    {{-- Header --}}
    @include('layouts.header')

    {{-- Breadcrumb --}}
    <div class="breadcrumb-area">
        <div class="container">
            <a href="{{ url('/') }}">Trang chủ</a> / <strong>Thông tin giỏ hàng</strong>
        </div>
    </div>

    @php
    // Dữ liệu từ controller: $cartItems (CartItem::with('product'))
    $calcTotal = 0;
    foreach(($cartItems ?? []) as $ci){
    $price = $ci->price ?? ($ci->product->gia_khuyen_mai ?? $ci->product->gia ?? 0);
    $calcTotal += $price * (int)$ci->quantity;
    }
    @endphp

    <div class="container my-4">
        <div class="row g-4">
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="cart-title m-0">THÔNG TIN GIỎ HÀNG</h4>
                    <div class="d-flex gap-3">
                        <a class="text-decoration-none" href="{{ url('/home') }}">← Chọn tiếp sản phẩm khác</a>
                        @if(($cartItems ?? collect())->count())
                        <button id="btnClearCart" class="btn btn-sm btn-outline-danger">Xoá giỏ hàng</button>
                        @endif
                    </div>
                </div>

                <div class="card shadow-sm">
                    @if(!($cartItems ?? collect())->count())
                    <div class="p-4 text-center text-muted">
                        Giỏ hàng trống. <a href="{{ url('/home') }}">Tiếp tục mua sắm</a>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width:50%">Sản phẩm</th>
                                    <th class="text-end" style="width:12%">Đơn giá</th>
                                    <th class="text-center" style="width:18%">Số lượng</th>
                                    <th class="text-end" style="width:12%">Số tiền</th>
                                    <th class="text-center" style="width:8%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="cartTbody">
                                @foreach($cartItems as $ci)
                                @php
                                $p = $ci->product;
                                $pid = $p->id;
                                $img = $p->hinh_anh_chinh
                                ? (preg_match('/^https?:\/\//', $p->hinh_anh_chinh) ? $p->hinh_anh_chinh : asset('storage/'.$p->hinh_anh_chinh))
                                : asset('images/no-image.png');
                                $price = $ci->price ?? ($p->gia_khuyen_mai ?? $p->gia ?? 0);
                                $line = $price * (int)$ci->quantity;
                                @endphp
                                <tr id="row-{{ $pid }}" data-id="{{ $pid }}">
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img class="thumb" src="{{ $img }}" alt="{{ $p->ten_san_pham }}">
                                            <div>
                                                <div class="fw-semibold text-uppercase">{{ $p->ten_san_pham }}</div>
                                                <div class="text-muted small mt-1">
                                                    @if(!empty($p->sku))
                                                    Mã sản phẩm: {{ $p->sku }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="unit-price" data-price="{{ $price }}">{{ number_format($price,0,',','.') }} đ</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-inline-flex align-items-center gap-2">
                                            <button class="qty-btn btnDec" aria-label="Giảm"><i class="bi bi-dash"></i></button>
                                            <span class="qty-value" data-qty="{{ (int)$ci->quantity }}">{{ (int)$ci->quantity }}</span>
                                            <button class="qty-btn btnInc" aria-label="Tăng"><i class="bi bi-plus"></i></button>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="line-total">{{ number_format($line,0,',','.') }} đ</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="remove-link btnRemove"><i class="bi bi-trash"></i> Xoá</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Tóm tắt --}}
            <div class="col-lg-3">
                <div class="cart-summary p-3">
                    <h6 class="mb-3">TÓM TẮT ĐƠN HÀNG</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính</span>
                        <strong id="subtotalText">{{ number_format($calcTotal,0,',','.') }} đ</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-muted">
                        <span>Phí vận chuyển</span>
                        <span>—</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fs-5">
                        <span>Tổng</span>
                        <strong id="grandTotalText">{{ number_format($calcTotal,0,',','.') }} đ</strong>
                    </div>
                    <a href="{{ route('checkout.info') }}" class="btn btn-success w-100 mt-3">Tiến hành đặt hàng</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    @include('layouts.footer')

    {{-- Toast container --}}
    <div id="toast-container"></div>

    <script>
        (function() {
            const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const cartBadge = document.getElementById('cartBadge');
            const dropdown = document.getElementById('cart-dropdown');

            function showToast(msg, err = false) {
                const c = document.getElementById('toast-container');
                const t = document.createElement('div');
                t.className = 'toast-x ' + (err ? 'toast-err' : 'toast-ok');
                t.textContent = msg;
                c.appendChild(t);
                requestAnimationFrame(() => t.classList.add('show'));
                setTimeout(() => {
                    t.classList.remove('show');
                    setTimeout(() => t.remove(), 300);
                }, 1800);
            }

            async function refreshMiniCart() {
                if (!dropdown) return;
                try {
                    const res = await fetch(`{{ route('cart.mini') }}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const html = await res.text();
                    dropdown.innerHTML = html;

                    const totalText = dropdown.querySelector('.cart-total');
                    if (totalText && cartBadge) {
                        const m = totalText.textContent.match(/\((\d+)\s+sản phẩm\)/);
                        if (m && m[1]) cartBadge.textContent = m[1];
                    }
                } catch (e) {}
            }

            function fmt(n) {
                return new Intl.NumberFormat('vi-VN').format(n) + ' đ';
            }

            async function postJSON(url, body) {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(body || {})
                });
                let data = {};
                try {
                    data = await res.json();
                } catch (_) {}
                return {
                    res,
                    data
                };
            }

            // ====== Tăng/giảm/xoá ======
            document.addEventListener('click', async function(e) {
                const incBtn = e.target.closest('.btnInc');
                const decBtn = e.target.closest('.btnDec');
                const rmBtn = e.target.closest('.btnRemove');
                const clearBtn = e.target.closest('#btnClearCart');

                if (!incBtn && !decBtn && !rmBtn && !clearBtn) return;
                e.preventDefault();

                // Xoá hết
                if (clearBtn) {
                    const ok = confirm('Xoá toàn bộ giỏ hàng?');
                    if (!ok) return;
                    const {
                        res,
                        data
                    } = await postJSON(`{{ route('cart.clear') }}`);
                    if (res.ok && data.status === 'success') {
                        showToast(data.message || 'Đã xoá giỏ hàng');
                        location.reload();
                    } else {
                        showToast((data && data.message) || 'Không thể xoá giỏ', true);
                    }
                    return;
                }

                // Lấy row & product id
                const row = (incBtn || decBtn || rmBtn).closest('tr');
                const productId = row?.dataset?.id;
                if (!row || !productId) return;

                // Xoá 1 dòng
                if (rmBtn) {
                    const {
                        res,
                        data
                    } = await postJSON(`{{ route('cart.remove.product') }}`, {
                        product_id: productId
                    });
                    if (res.ok && data.status === 'success') {
                        row.remove();
                        showToast(data.message || 'Đã xoá sản phẩm');
                        if (typeof data.cartTotal !== 'undefined') {
                            document.getElementById('subtotalText').textContent = fmt(data.cartTotal);
                            document.getElementById('grandTotalText').textContent = fmt(data.cartTotal);
                        }
                        if (cartBadge && typeof data.totalQuantity !== 'undefined') {
                            cartBadge.textContent = data.totalQuantity;
                        }
                        await refreshMiniCart();
                    } else {
                        showToast((data && data.message) || 'Không thể xoá', true);
                    }
                    return;
                }

                // Tăng/giảm
                const qtyEl = row.querySelector('.qty-value');
                const unitEl = row.querySelector('.unit-price');
                const lineEl = row.querySelector('.line-total');
                let qty = parseInt(qtyEl.dataset.qty || qtyEl.textContent || '1', 10);
                if (incBtn) qty++;
                if (decBtn) qty--;

                if (qty <= 0) {
                    const ok = confirm('Số lượng về 0. Xoá sản phẩm này?');
                    if (!ok) return;
                    const {
                        res,
                        data
                    } = await postJSON(`{{ route('cart.remove.product') }}`, {
                        product_id: productId
                    });
                    if (res.ok && data.status === 'success') {
                        row.remove();
                        showToast(data.message || 'Đã xoá sản phẩm');
                        if (typeof data.cartTotal !== 'undefined') {
                            document.getElementById('subtotalText').textContent = fmt(data.cartTotal);
                            document.getElementById('grandTotalText').textContent = fmt(data.cartTotal);
                        }
                        if (cartBadge && typeof data.totalQuantity !== 'undefined') {
                            cartBadge.textContent = data.totalQuantity;
                        }
                        await refreshMiniCart();
                    } else {
                        showToast((data && data.message) || 'Không thể xoá', true);
                    }
                    return;
                }

                // Cập nhật số lượng
                const {
                    res,
                    data
                } = await postJSON(`{{ route('cart.update') }}`, {
                    product_id: productId,
                    quantity: qty
                });

                if (res.status === 401) {
                    showToast('Bạn cần đăng nhập để cập nhật giỏ hàng', true);
                    setTimeout(() => location.href = "{{ route('login') }}", 800);
                    return;
                }

                if (res.ok && data.status === 'success') {
                    qtyEl.dataset.qty = qty;
                    qtyEl.textContent = qty;

                    const unit = parseInt(unitEl.dataset.price || '0', 10);
                    const line = (typeof data.lineTotal !== 'undefined') ? data.lineTotal : unit * qty;
                    lineEl.textContent = fmt(line);

                    if (typeof data.cartTotal !== 'undefined') {
                        document.getElementById('subtotalText').textContent = fmt(data.cartTotal);
                        document.getElementById('grandTotalText').textContent = fmt(data.cartTotal);
                    }
                    if (cartBadge && typeof data.totalQuantity !== 'undefined') {
                        cartBadge.textContent = data.totalQuantity;
                    }

                    if (dropdown) {
                        if (data.html) dropdown.innerHTML = data.html;
                        else await refreshMiniCart();
                    }

                    showToast(data.message || 'Đã cập nhật số lượng');
                } else {
                    showToast((data && data.message) || 'Không thể cập nhật', true);
                }
            });
        })();
    </script>
</body>

</html>