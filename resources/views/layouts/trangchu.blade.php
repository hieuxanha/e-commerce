<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lắc Đầu - E-commerce</title>

    {{-- CSRF cho AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">



    <link rel="stylesheet" href="{{ asset('css/header.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}" />
    <style></style>
</head>

<body>
    {{-- ✅ Chặn header auto-bind add-to-cart để tránh double handler --}}
    <script>
        window.DISABLE_HEADER_ADD_TO_CART = true;
    </script>

    {{-- Header --}}
    @include('layouts.header')

    <div class="container">
        <div class="main-layout">
            {{-- Sidebar trái --}}
            <nav class="sidebar" aria-label="Danh mục">
                <a class="sidebar-item" href="/lot-chuot">
                    <svg class="sidebar-icon" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2" fill="none" stroke="currentColor" stroke-width="2" />
                        <circle cx="9" cy="9" r="2" fill="none" stroke="currentColor" stroke-width="2" />
                        <path d="M21 15.5c-1.5-1.5-4-1.5-5.5 0" fill="none" stroke="currentColor" stroke-width="2" />
                    </svg>
                    <span class="sidebar-label">LÓT CHUỘT</span>
                </a>
                <a class="sidebar-item" href="/gaming-gear">
                    <svg class="sidebar-icon" viewBox="0 0 24 24">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" fill="none" stroke="currentColor" stroke-width="2" />
                        <line x1="3" y1="6" x2="21" y2="6" stroke="currentColor" stroke-width="2" />
                        <path d="M16 10a4 4 0 0 1-8 0" fill="none" stroke="currentColor" stroke-width="2" />
                    </svg>
                    <span class="sidebar-label">GAMING GEAR</span>
                </a>
                <a class="sidebar-item" href="/phu-kien-may-tinh">
                    <svg class="sidebar-icon" viewBox="0 0 24 24">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2" fill="none" stroke="currentColor" stroke-width="2" />
                        <line x1="8" y1="21" x2="16" y2="21" stroke="currentColor" stroke-width="2" />
                        <line x1="12" y1="17" x2="12" y2="21" stroke="currentColor" stroke-width="2" />
                    </svg>
                    <span class="sidebar-label">PHỤ KIỆN MÁY TÍNH</span>
                </a>
                <a class="sidebar-item" href="/mo-hinh">
                    <svg class="sidebar-icon" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5z" fill="none" stroke="currentColor" stroke-width="2" />
                        <path d="M2 17l10 5 10-5" fill="none" stroke="currentColor" stroke-width="2" />
                        <path d="M2 12l10 5 10-5" fill="none" stroke="currentColor" stroke-width="2" />
                    </svg>
                    <span class="sidebar-label">MÔ HÌNH</span>
                </a>
                <a class="sidebar-item" href="/phu-kien-trang-tri">
                    <svg class="sidebar-icon" viewBox="0 0 24 24">
                        <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26" fill="none" stroke="currentColor" stroke-width="2" />
                    </svg>
                    <span class="sidebar-label">PHỤ KIỆN TRANG TRÍ</span>
                </a>
                <a class="sidebar-item" href="/loa-micro-webcam">
                    <svg class="sidebar-icon" viewBox="0 0 24 24">
                        <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z" fill="none" stroke="currentColor" stroke-width="2" />
                        <path d="M19 10v2a7 7 0 0 1-14 0v-2" fill="none" stroke="currentColor" stroke-width="2" />
                        <line x1="12" y1="19" x2="12" y2="23" stroke="currentColor" stroke-width="2" />
                        <line x1="8" y1="23" x2="16" y2="23" stroke="currentColor" stroke-width="2" />
                    </svg>
                    <span class="sidebar-label">LOA, MICRO, WEBCAM</span>
                </a>
                <a class="sidebar-item" href="/ghe-gaming">
                    <svg class="sidebar-icon" viewBox="0 0 24 24">
                        <path d="M5 12V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v5" fill="none" stroke="currentColor" stroke-width="2" />
                        <path d="M5 12a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1" fill="none" stroke="currentColor" stroke-width="2" />
                        <path d="M9 12v4" stroke="currentColor" stroke-width="2" />
                        <path d="M15 12v4" stroke="currentColor" stroke-width="2" />
                    </svg>
                    <span class="sidebar-label">GHẾ GAMING</span>
                </a>
                <a class="sidebar-item" href="/ban-gaming">
                    <svg class="sidebar-icon" viewBox="0 0 24 24">
                        <path d="M3 6h18" stroke="currentColor" stroke-width="2" />
                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" stroke="currentColor" stroke-width="2" />
                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" stroke="currentColor" stroke-width="2" />
                    </svg>
                    <span class="sidebar-label">BÀN GAMING</span>
                </a>
                <a class="sidebar-item" href="/phu-kien-dien-thoai">
                    <svg class="sidebar-icon" viewBox="0 0 24 24">
                        <rect x="5" y="2" width="14" height="20" rx="2" ry="2" fill="none" stroke="currentColor" stroke-width="2" />
                        <line x1="12" y1="18" x2="12.01" y2="18" stroke="currentColor" stroke-width="2" />
                    </svg>
                    <span class="sidebar-label">PHỤ KIỆN ĐIỆN THOẠI</span>
                </a>
                <a class="sidebar-item" href="/linh-kien-may-tinh">
                    <svg class="sidebar-icon" viewBox="0 0 24 24">
                        <rect x="4" y="4" width="16" height="16" rx="2" ry="2" fill="none" stroke="currentColor" stroke-width="2" />
                        <rect x="9" y="9" width="6" height="6" fill="none" stroke="currentColor" stroke-width="2" />
                        <line x1="9" y1="1" x2="9" y2="4" stroke="currentColor" stroke-width="2" />
                        <line x1="15" y1="1" x2="15" y2="4" stroke="currentColor" stroke-width="2" />
                        <line x1="9" y1="20" x2="9" y2="23" stroke="currentColor" stroke-width="2" />
                        <line x1="15" y1="20" x2="15" y2="23" stroke="currentColor" stroke-width="2" />
                        <line x1="20" y1="9" x2="23" y2="9" stroke="currentColor" stroke-width="2" />
                        <line x1="20" y1="14" x2="23" y2="14" stroke="currentColor" stroke-width="2" />
                        <line x1="1" y1="9" x2="4" y2="9" stroke="currentColor" stroke-width="2" />
                        <line x1="1" y1="14" x2="4" y2="14" stroke="currentColor" stroke-width="2" />
                    </svg>
                    <span class="sidebar-label">LINH KIỆN MÁY TÍNH</span>
                </a>
                <a class="sidebar-item" href="/combo-uu-dai">
                    <svg class="sidebar-icon" viewBox="0 0 24 24">
                        <circle cx="9" cy="21" r="1" fill="none" stroke="currentColor" stroke-width="2" />
                        <circle cx="20" cy="21" r="1" fill="none" stroke="currentColor" stroke-width="2" />
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" fill="none" stroke="currentColor" stroke-width="2" />
                    </svg>
                    <span class="sidebar-label">COMBO ƯU ĐÃI</span>
                </a>
            </nav>

            {{-- Main --}}
            <main class="main-content">
                <section class="hero-banner image-only">
                    <img src="{{ asset('img/main_img.png') }}" alt="Lót chuột in theo yêu cầu" class="hero-img" loading="lazy" decoding="async">
                </section>

                <section class="product-cards">
                    <article class="product-card">
                        <div class="product-card-image">
                            <img src="{{ asset('img/cat-phu-kien-may-tinh.jpg') }}" alt="Phụ kiện máy tính">
                        </div>
                    </article>
                    <article class="product-card">
                        <div class="product-card-image">
                            <img src="{{ asset('img/cat-loa-micro.jpg') }}" alt="Loa, micro">
                        </div>
                    </article>
                    <article class="product-card">
                        <div class="product-card-image">
                            <img src="{{ asset('img/cat-tan-nhiet.jpg') }}" alt="Tản nhiệt máy tính">
                        </div>
                    </article>
                </section>
            </main>

            {{-- Sidebar phải --}}
            <aside class="right-sidebar">
                <div class="promo-card">
                    <div class="promo-card-image">
                        <img src="{{ asset('img/promo-mo-hinh-anime.jpg') }}" alt="Mô hình anime">
                    </div>
                </div>
                <div class="promo-card">
                    <div class="promo-card-image">
                        <img src="{{ asset('img/promo-gaming-gear.jpg') }}" alt="Gaming gear">
                    </div>
                </div>
                <div class="promo-card">
                    <div class="promo-card-image">
                        <img src="{{ asset('img/promo-ban-ghe-gaming.jpg') }}" alt="Bàn ghế gaming">
                    </div>
                </div>
            </aside>
        </div>

        {{-- Danh mục + sản phẩm --}}
        <div class="container">
            @foreach($categories as $cat)
            <div class="product-section">
                <div class="section-header">
                    <h2 class="section-title">{{ strtoupper($cat->ten_danh_muc) }} 🐭 🐁</h2>
                    <div class="section-filters">
                        <a class="filter-tab" href="{{ route('danhmuc.show', $cat->id) }}">XEM THÊM →</a>
                    </div>
                </div>


                <div class="products-grid">
                    @forelse($cat->products as $p)
                    @php
                    $percent = ($p->gia && $p->gia_khuyen_mai && $p->gia > 0)
                    ? round(100 - ($p->gia_khuyen_mai / $p->gia) * 100)
                    : null;
                    $img = $p->hinh_anh_chinh
                    ? (preg_match('/^https?:\/\//', $p->hinh_anh_chinh) ? $p->hinh_anh_chinh : asset('storage/' . $p->hinh_anh_chinh))
                    : asset('img/placeholder-product.jpg');
                    $detailUrl = !empty($p->slug) ? route('sanpham.chitiet', ['slug' => $p->slug]) : route('sanpham.chitiet.id', ['id' => $p->id]);
                    @endphp

                    <div class="product-item">
                        @if($percent)
                        <div class="discount-label">-{{ $percent }}%</div>
                        @endif

                        <img src="{{ $img }}" alt="{{ $p->ten_san_pham }}" class="product-image">

                        <div class="product-info">
                            <a href="{{ $detailUrl }}" class="product-name d-block">{{ $p->ten_san_pham }}</a>
                            <div class="product-status">
                                @switch($p->trang_thai)
                                @case('con_hang') Còn hàng @break
                                @case('het_hang') Hết hàng @break
                                @case('sap_ve') Sắp về @break
                                @default Đang cập nhật
                                @endswitch
                            </div>
                            <div class="product-pricing">
                                @if($p->gia_khuyen_mai)
                                <div class="original-price">{{ number_format($p->gia, 0, ',', '.') }}đ</div>
                                <div class="current-price">{{ number_format($p->gia_khuyen_mai, 0, ',', '.') }}đ</div>
                                @else
                                <div class="current-price">{{ number_format($p->gia, 0, ',', '.') }}đ</div>
                                @endif
                            </div>
                        </div>

                        <button class="add-to-cart-btn" data-id="{{ $p->id }}">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                    </div>
                    @empty
                    <p>Chưa có sản phẩm trong danh mục này.</p>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>

        {{-- Khối tĩnh GAMING GEAR (giữ nguyên) --}}
        <div class="container">
            <div class="product-section">
                <div class="section-header">
                    <h2 class="section-title">GAMING GEAR</h2>
                    <div class="section-filters">
                        <div class="filter-tab active">BÀN PHÍM GAMING</div>
                        <div class="filter-tab">CHUỘT GAMING</div>
                        <div class="filter-tab">TAI NGHE</div>
                        <div class="filter-tab">TAY CẦM CHƠI GAME</div>
                        <div class="filter-tab">XEM THÊM →</div>
                    </div>
                </div>
                <div class="products-grid">
                    {{-- … các product item tĩnh … --}}
                </div>
            </div>
        </div>
        @include('layouts.chatbot')

    </div>

    {{-- Footer --}}
    @include('layouts.footer')


    <script>
        // Search focus UI nho nhỏ (không liên quan giỏ)
        (function() {
            const input = document.querySelector('.search-input');
            if (!input) return;
            input.addEventListener('focus', function() {
                this.style.borderColor = '#0d9488';
                this.style.boxShadow = '0 0 0 2px rgba(13,148,136,.2)';
            });
            input.addEventListener('blur', function() {
                this.style.borderColor = '#d1d5db';
                this.style.boxShadow = 'none';
            });
        })();
    </script>

    <script>
        (function() {
            // Chống bind trùng nếu trang được include nhiều lần
            if (window.__HOME_ADD_TO_CART_BOUND__) return;
            window.__HOME_ADD_TO_CART_BOUND__ = true;

            const cartBadge = document.getElementById('cartBadge'); // từ header
            const dropdown = document.getElementById('cart-dropdown'); // từ header
            const CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            // Delegation: chỉ 1 handler cho tất cả nút .add-to-cart-btn
            document.addEventListener('click', async function(e) {
                const btn = e.target.closest('.add-to-cart-btn');
                if (!btn) return;

                e.preventDefault();

                // chặn spam
                if (btn.dataset.busy === '1') return;
                btn.dataset.busy = '1';

                const productId = btn.dataset.id;
                if (!productId) {
                    btn.dataset.busy = '0';
                    return;
                }

                try {
                    const res = await fetch(`{{ route('cart.add') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF,
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
                        // cập nhật badge
                        if (cartBadge && typeof data.totalQuantity !== 'undefined') {
                            cartBadge.textContent = data.totalQuantity;
                        }
                        // cập nhật mini cart
                        if (dropdown) {
                            if (data.html) {
                                dropdown.innerHTML = data.html;
                                bindRemoveHandlersInDropdown();
                            } else {
                                await refreshMiniCart(dropdown);
                            }
                            dropdown.classList.add('show'); // mở ra cho user thấy
                        }
                        showToast('🛒 Đã thêm vào giỏ hàng thành công!');
                    } else {
                        showToast((data && data.message) || 'Không thể thêm vào giỏ', true);
                    }
                } catch (err) {
                    console.error(err);
                    showToast('Lỗi mạng, vui lòng thử lại.', true);
                } finally {
                    setTimeout(() => {
                        btn.dataset.busy = '0';
                    }, 400);
                }
            });

            // Helpers
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

                    // nếu trong html có tổng (ví dụ: "Tổng: ... (12 sản phẩm)")
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
                                'X-CSRF-TOKEN': CSRF,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: new URLSearchParams(new FormData(this))
                        }).then(() => refreshMiniCart(document.getElementById('cart-dropdown')));
                    });
                });
            }

            function showToast(message, isError = false) {
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

            // Click ra ngoài đóng mini cart
            document.addEventListener('click', function(e) {
                const wrap = document.querySelector('.cart-wrapper');
                const dd = document.getElementById('cart-dropdown');
                if (wrap && dd && !wrap.contains(e.target)) dd.classList.remove('show');
            });

            // Nút “Giỏ hàng” trong header
            window.toggleCartDropdown = function() {
                const dd = document.getElementById('cart-dropdown');
                if (dd) dd.classList.toggle('show');
            };
        })();
    </script>
</body>

</html>