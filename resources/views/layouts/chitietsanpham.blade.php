{{-- resources/views/layouts/chitietsanpham.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $product->ten_san_pham ?? 'Chi tiết sản phẩm' }}</title>

    {{-- CSRF cho AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- CSS dùng chung --}}
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/breadcrumb.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .product-detail .main-image img {
            object-fit: contain;
            max-height: 450px;
        }

        .thumb-images img {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }

        .text-small {
            font-size: .9rem;
        }

        .product-card {
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, .15);
        }

        .policy-box ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .policy-box ul li {
            display: flex;
            align-items: center;
            padding: 6px 0;
            font-size: 14px;
            color: #333;
        }

        .policy-box ul li i {
            font-size: 18px;
            color: #28a745;
            margin-right: 8px;
        }

        #ratingStars i {
            cursor: pointer;
        }

        #ratingStars i:not(.text-warning) {
            color: #adb5bd !important;
        }

        .reply-admin {
            background: #f8f9fa;
            border-left: 3px solid #0d6efd;
            padding: 5px 10px;
            margin-top: 5px;
        }

        .product-detail-row {
            box-shadow: 0 4px 12px rgba(0, 0, 0, .1);
            border-radius: 10px;
            background: #fff;
            padding-bottom: 30px;
            margin-bottom: 30px;
        }

        /* chi tiết nội dung */
        .product-description {
            max-height: 80px;
            overflow: hidden;
            position: relative;
            transition: max-height 0.3s ease;
        }

        .product-description.expanded {
            max-height: none;
        }

        /* cảnh báo tồn kho thấp */
        .low-stock {
            color: #dc2626;
            font-weight: 600;
        }
    </style>
</head>

<body>
    {{-- Header --}}
    @include('layouts.header')

    {{-- Breadcrumb --}}
    @php
    $links = [
    'Trang chủ' => url('/'),
    'Sản phẩm' => url('/home'),
    $product->ten_san_pham ?? '' => ''
    ];
    @endphp
    @include('components.breadcrumb', ['links' => $links])

    {{-- Nội dung --}}
    <div class="product-detail container py-4">
        <div class="row g-4 align-items-start product-detail-row">
            {{-- Cột ảnh --}}
            <div class="col-lg-5">
                <div class="main-image border rounded p-2 mb-3 bg-white">
                    @php
                    $mainImg = $product->hinh_anh_chinh ? asset('storage/'.$product->hinh_anh_chinh) : asset('images/no-image.png');
                    @endphp
                    <img src="{{ $mainImg }}" alt="{{ $product->ten_san_pham }}" class="img-fluid w-100">
                </div>
                <div class="thumb-images d-flex gap-2">
                    <img src="{{ $mainImg }}" class="img-thumbnail" alt="thumb">
                </div>
            </div>

            {{-- Cột thông tin & sidebar bên phải --}}
            <div class="col-lg-7">
                <div class="row g-4 align-items-start">
                    {{-- Thông tin sản phẩm --}}
                    <div class="col-md-8">
                        <h2 class="mb-1">{{ $product->ten_san_pham }}</h2>
                        <div class="mb-2 text-muted text-small">
                            Mã SP: <strong>{{ $product->sku }}</strong>
                            @if(isset($product->luot_xem)) &nbsp;|&nbsp; Lượt xem: {{ (int) $product->luot_xem }} @endif
                        </div>

                        {{-- Tồn kho & Trạng thái --}}
                        @php
                        $qty = (int) ($product->so_luong_ton_kho ?? 0);
                        // ưu tiên cột "trang_thai" nếu có (con_hang/het_hang/sap_ve/an)
                        $status = $product->trang_thai ?? ($qty > 0 ? 'con_hang' : 'het_hang');

                        $statusText = match($status){
                        'con_hang' => 'Còn hàng',
                        'het_hang' => 'Hết hàng',
                        'sap_ve' => 'Sắp về',
                        default => 'Ẩn'
                        };
                        $statusClass = match($status){
                        'con_hang' => 'text-bg-success',
                        'het_hang' => 'text-bg-secondary',
                        'sap_ve' => 'text-bg-warning',
                        default => 'text-bg-light'
                        };

                        $isOut = ($status === 'het_hang' || $qty <= 0);
                            @endphp

                            <div class="d-flex align-items-center gap-3 mb-2">
                            <span class="badge {{ $statusClass }} px-3 py-2">{{ $statusText }}</span>
                            {{-- Hiển thị nhãn theo yêu cầu: "Số lương" --}}
                            <span class="text-muted">Số lương:
                                <strong class="{{ $qty > 0 && $qty < 5 ? 'low-stock' : '' }}">{{ $qty }}</strong>
                            </span>
                    </div>

                    {{-- Mô tả chi tiết --}}
                    @if(!empty($product->mo_ta_chi_tiet))
                    <div class="mt-4 mb-2">
                        <h5>Mô tả chi tiết</h5>
                        <div id="descBox" class="product-description text-secondary">{!! nl2br(e($product->mo_ta_chi_tiet)) !!}</div>
                        <a href="javascript:void(0)" id="toggleDesc" class="text-success">Xem thêm ⭢</a>
                    </div>
                    @endif

                    {{-- Giá --}}
                    <div class="mb-3">
                        @if($product->gia_khuyen_mai)
                        <div class="text-muted"><del>{{ number_format($product->gia,0,',','.') }}đ</del></div>
                        <div class="text-danger fs-4 fw-bold">{{ number_format($product->gia_khuyen_mai,0,',','.') }}đ</div>
                        <small class="text-success">(Tiết kiệm: {{ max(0, round(100 - $product->gia_khuyen_mai / max(1,$product->gia) * 100)) }}%)</small>
                        @else
                        <div class="text-danger fs-4 fw-bold">{{ number_format($product->gia,0,',','.') }}đ</div>
                        @endif
                    </div>

                    {{-- Mô tả ngắn --}}
                    <h5>Mô tả ngắn </h5>
                    @if(!empty($product->mo_ta_ngan))
                    <div class="product-desc mb-3">{!! nl2br(e($product->mo_ta_ngan)) !!}</div>
                    @endif

                    {{-- Nút hành động (AJAX add to cart) --}}
                    <div class="d-flex gap-3 mt-4">
                        <form id="addToCartForm" method="POST" action="{{ route('cart.add') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn btn-warning btn-lg" {{ $isOut ? 'disabled' : '' }}>
                                THÊM VÀO GIỎ
                            </button>
                        </form>

                        <a href="{{ route('cart.checkout') }}"
                            class="btn btn-success btn-lg {{ $isOut ? 'disabled' : '' }}"
                            @if($isOut) aria-disabled="true" onclick="return false;" @endif>
                            MUA NGAY
                        </a>
                    </div>

                    @if($isOut)
                    <div class="text-danger mt-2">
                        Sản phẩm hiện đã hết hàng. Vui lòng quay lại sau!
                    </div>
                    @endif
                </div>

                {{-- SIDEBAR bên phải --}}
                <div class="col-md-4">
                    <div class="policy-box bg-white border rounded p-3 mb-3">
                        <h5 class="mb-3 fw-bold text-success">CHÍNH SÁCH MUA HÀNG</h5>
                        <ul class="list-unstyled m-0">
                            <li><i class="bi bi-credit-card"></i> Thanh toán thuận tiện</li>
                            <li><i class="bi bi-shield-check"></i> Sản phẩm 100% chính hãng</li>
                            <li><i class="bi bi-truck"></i> Bảo hành nhanh chóng</li>
                            <li><i class="bi bi-box-seam"></i> Giao hàng toàn quốc</li>
                        </ul>
                    </div>

                    <div class="policy-box bg-white border rounded p-3">
                        <h5 class="mb-3 fw-bold text-success">HOTLINE HỖ TRỢ</h5>
                        <ul class="list-unstyled m-0">
                            <li><i class="bi bi-telephone"></i> Hotline CSKH: <span class="fw-bold text-danger">0349.296.461</span></li>
                            <li><i class="bi bi-telephone"></i> Tư vấn mua hàng: <span class="fw-bold text-danger">0349.296.461</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sản phẩm liên quan --}}
    @isset($related)
    @if($related->count())
    <hr class="my-5">
    <h4 class="mb-3">Sản phẩm liên quan</h4>
    <div class="row g-3">
        @foreach($related as $r)
        @php
        $rImg = !empty($r->hinh_anh_chinh) ? asset('storage/'.$r->hinh_anh_chinh) : asset('images/no-image.png');
        $rPrice = $r->gia_khuyen_mai ?? $r->gia;
        $rUrl = !empty($r->slug) ? route('sanpham.chitiet',['slug'=>$r->slug]) : route('sanpham.chitiet.id',['id'=>$r->id]);
        @endphp
        <div class="col-6 col-md-3">
            <a href="{{ $rUrl }}" class="text-decoration-none text-dark">
                <div class="card h-100 shadow-sm product-card" style="max-width:55%;">
                    <img class="card-img-top" src="{{ $rImg }}" alt="{{ $r->ten_san_pham }}" style="aspect-ratio:1/1;object-fit:cover">
                    <div class="card-body p-3">
                        <div class="small text-wrap"
                            style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:3rem;"
                            title="{{ $r->ten_san_pham }}">
                            {{ $r->ten_san_pham }}
                        </div>
                        <strong class="text-danger d-block mt-2">{{ number_format($rPrice,0,',','.') }}đ</strong>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
    @endif
    @endisset

    {{-- ==== ĐÁNH GIÁ & NHẬN XÉT ==== --}}
    <hr class="my-5" id="reviews">

    @php
    // Fallback nếu controller chưa truyền sẵn
    $avgRating = isset($avgRating) ? round($avgRating,1) : 0;
    $reviewsCount = isset($reviewsCount) ? (int)$reviewsCount : (isset($reviews) ? (int)$reviews->total() : 0);
    $fullStars = (int)floor($avgRating);
    $halfStar = ($avgRating - $fullStars) >= 0.5;
    @endphp

    <div class="bg-white border rounded p-3 mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-4 text-center">
                <div class="display-5 fw-bold text-warning">{{ number_format($avgRating,1) }}</div>
                <div class="mt-1" style="font-size:1.3rem;">
                    @for($i=1;$i<=5;$i++)
                        @if($i <=$fullStars)
                        <i class="bi bi-star-fill text-warning"></i>
                        @elseif($halfStar && $i == $fullStars+1)
                        <i class="bi bi-star-half text-warning"></i>
                        @else
                        <i class="bi bi-star text-warning"></i>
                        @endif
                        @endfor
                </div>
                <div class="text-muted small mt-1">{{ $reviewsCount }} đánh giá</div>
            </div>
            <div class="col-md-8">
                <div class="alert alert-info mb-0 py-2">
                    <i class="bi bi-info-circle"></i>
                    Chỉ cần <strong>đăng nhập</strong> là có thể viết đánh giá.
                </div>
            </div>
        </div>
    </div>

    {{-- Danh sách đánh giá (ưu tiên các đánh giá đã duyệt) --}}
    @if(!empty($reviews) && $reviews->count())
    <div class="list-group mb-4">
        @foreach($reviews as $rv)
        <div class="list-group-item">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <strong>{{ optional($rv->user)->name ?? 'Khách' }}</strong>
                    <div class="text-warning">
                        @for($i=1;$i<=5;$i++)
                            <i class="bi {{ $i <= (int)$rv->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                    </div>
                </div>
                <small class="text-muted">
                    {{ $rv->created_at?->format('d/m/Y H:i') }}
                    @if(isset($rv->approved) && !$rv->approved)
                    <span class="badge text-bg-secondary ms-2">Chờ duyệt</span>
                    @endif
                </small>
            </div>

            @if(!empty($rv->comment))
            <p class="mb-0 mt-2">{{ $rv->comment }}</p>
            @endif

            {{-- Phản hồi admin (nếu có) --}}
            @if(!empty($rv->replies) && $rv->replies->count())
            @foreach($rv->replies as $reply)
            <div class="reply-admin mt-2">
                <strong>Admin:</strong> {{ $reply->content }}
                <div class="text-muted small">{{ $reply->created_at?->format('d/m/Y H:i') }}</div>
            </div>
            @endforeach
            @endif
        </div>
        @endforeach
    </div>

    {{-- Phân trang --}}
    @if(method_exists($reviews,'links'))
    <div class="mb-4">{{ $reviews->links('pagination::bootstrap-5') }}</div>
    @endif
    @else
    <div class="alert alert-secondary">Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên!</div>
    @endif

    {{-- FORM GỬI ĐÁNH GIÁ --}}
    <div id="review-form" class="bg-white border rounded p-3">
        <h5 class="mb-3">Viết đánh giá của bạn</h5>

        {{-- Flash/Sai sót từ validate (nếu có) --}}
        @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger py-2">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
        @endif

        @auth
        <form method="POST" action="{{ route('reviews.store') }}" class="needs-validation" novalidate>
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div class="mb-3">
                <label class="form-label d-block">Chọn số sao <span class="text-danger">*</span></label>
                <input type="hidden" name="rating" id="ratingInput" value="5">
                <div id="ratingStars" class="fs-3">
                    {{-- 5 ngôi sao, JS sẽ điều khiển --}}
                    <i class="bi bi-star-fill text-warning" data-val="1"></i>
                    <i class="bi bi-star-fill text-warning" data-val="2"></i>
                    <i class="bi bi-star-fill text-warning" data-val="3"></i>
                    <i class="bi bi-star-fill text-warning" data-val="4"></i>
                    <i class="bi bi-star-fill text-warning" data-val="5"></i>
                </div>
                <span id="ratingLabel" class="ms-2 text-muted">Rất tốt</span>
            </div>

            <div class="mb-3">
                <label for="comment" class="form-label">Nhận xét của bạn</label>
                <textarea name="comment" id="comment" rows="4" class="form-control" placeholder="Chia sẻ trải nghiệm sử dụng sản phẩm..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-send"></i> Gửi đánh giá
            </button>
        </form>
        @else
        <div class="alert alert-light border d-flex align-items-center">
            <i class="bi bi-lock me-2"></i>
            Vui lòng <a href="{{ route('login') }}" class="ms-1">đăng nhập</a> để viết đánh giá.
        </div>
        @endauth
    </div>

    </div>

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toggle mô tả -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const descBox = document.getElementById("descBox");
            const toggleBtn = document.getElementById("toggleDesc");

            if (descBox && toggleBtn) {
                toggleBtn.addEventListener("click", function() {
                    descBox.classList.toggle("expanded");
                    toggleBtn.textContent = descBox.classList.contains("expanded") ?
                        "Thu gọn ⭡" : "Xem thêm ⭢";
                });
            }
        });
    </script>

    {{-- JS: chọn sao --}}
    <script>
        (function() {
            const stars = document.querySelectorAll('#ratingStars i');
            const input = document.getElementById('ratingInput');
            const label = document.getElementById('ratingLabel');
            if (!stars.length || !input || !label) return;

            const texts = {
                1: 'Tệ',
                2: 'Chưa tốt',
                3: 'Bình thường',
                4: 'Tốt',
                5: 'Rất tốt'
            };

            function paint(n) {
                stars.forEach((s, i) => {
                    if (i < n) {
                        s.classList.add('bi-star-fill', 'text-warning');
                        s.classList.remove('bi-star');
                    } else {
                        s.classList.remove('bi-star-fill', 'text-warning');
                        s.classList.add('bi-star');
                    }
                });
                label.textContent = texts[n] || '';
            }

            stars.forEach(star => {
                star.addEventListener('mouseenter', e => paint(+e.target.dataset.val));
                star.addEventListener('click', e => {
                    input.value = +e.target.dataset.val;
                    paint(+e.target.dataset.val);
                });
            });
            paint(+input.value || 5);
        })();
    </script>

    {{-- JS: Add to Cart AJAX (không rời trang + cập nhật badge) --}}
    <script>
        (function() {
            const form = document.getElementById('addToCartForm');
            if (!form) return;

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const cartBadge = document.getElementById('cartBadge');
            const dropdown = document.getElementById('cart-dropdown');

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                // chống double submit
                if (form.dataset.busy === '1') return;
                form.dataset.busy = '1';

                const url = form.getAttribute('action');
                const productId = form.querySelector('input[name="product_id"]').value;

                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            product_id: productId
                        })
                    });

                    if (res.status === 401) {
                        const back = encodeURIComponent(location.href);
                        location.href = "{{ route('login') }}?redirect=" + back;
                        return;
                    }

                    const data = await res.json().catch(() => ({}));

                    if (res.ok && data.status === 'success') {
                        // 1) Cập nhật badge
                        if (cartBadge && typeof data.totalQuantity !== 'undefined') {
                            cartBadge.textContent = data.totalQuantity;
                        }

                        // 2) Cập nhật mini cart
                        if (dropdown) {
                            if (data.html) {
                                dropdown.innerHTML = data.html;
                                bindRemoveHandlersInDropdown();
                            } else {
                                await refreshMiniCart(dropdown);
                            }
                            // 3) Mở dropdown
                            dropdown.classList.add('show');
                        }

                        showToast('🛒 Đã thêm vào giỏ hàng thành công!');
                    } else {
                        showToast((data && data.message) || 'Không thể thêm vào giỏ', true);
                    }
                } catch (err) {
                    console.error(err);
                    showToast('Lỗi mạng, vui lòng thử lại.', true);
                } finally {
                    // mở khoá sau 600ms để tránh spam
                    setTimeout(() => {
                        form.dataset.busy = '0';
                    }, 600);
                }
            });

            // --- Helpers ---
            async function refreshMiniCart(ddEl) {
                try {
                    const res = await fetch(`{{ route('cart.mini') }}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const html = await res.text();
                    ddEl.innerHTML = html;
                    bindRemoveHandlersInDropdown();
                    // cập nhật badge từ tổng trong dropdown nếu có
                    const totalText = ddEl.querySelector('.cart-total');
                    if (totalText && cartBadge) {
                        const m = totalText.textContent.match(/\((\d+)\s+sản phẩm\)/);
                        if (m && m[1]) cartBadge.textContent = m[1];
                    }
                } catch (e) {
                    console.error(e);
                }
            }

            function bindRemoveHandlersInDropdown() {
                document.querySelectorAll('#cart-dropdown .cart-remove-form').forEach(f => {
                    if (f.__bound__) return;
                    f.__bound__ = true;
                    f.addEventListener('submit', function(e) {
                        e.preventDefault();
                        fetch(this.getAttribute('action'), {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: new URLSearchParams(new FormData(this))
                        }).then(() => refreshMiniCart(document.getElementById('cart-dropdown')));
                    });
                });
            }

            function showToast(message, isError = false) {
                // tạo container nếu chưa có
                let c = document.getElementById('toast-container');
                if (!c) {
                    c = document.createElement('div');
                    c.id = 'toast-container';
                    c.style.position = 'fixed';
                    c.style.top = '20px';
                    c.style.right = '20px';
                    c.style.zIndex = '2000';
                    document.body.appendChild(c);
                }
                const t = document.createElement('div');
                t.textContent = message;
                t.style.padding = '10px 14px';
                t.style.marginTop = '10px';
                t.style.borderRadius = '8px';
                t.style.color = '#fff';
                t.style.boxShadow = '0 6px 18px rgba(0,0,0,.15)';
                t.style.maxWidth = '320px';
                t.style.fontSize = '14px';
                t.style.opacity = '0';
                t.style.transform = 'translateX(100%)';
                t.style.transition = 'all .3s ease';
                t.style.background = isError ? '#dc2626' : '#0d9488';
                c.appendChild(t);
                requestAnimationFrame(() => {
                    t.style.opacity = '1';
                    t.style.transform = 'translateX(0)';
                });
                setTimeout(() => {
                    t.style.opacity = '0';
                    t.style.transform = 'translateX(100%)';
                    setTimeout(() => t.remove(), 350);
                }, 1800);
            }
        })();
    </script>

</body>

</html>