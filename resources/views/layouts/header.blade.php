{{-- ===== Header (Top bar + Main header) ===== --}}

<div class="site-header">
    <div class="top-nav">
        <div class="container_header">
            <div class="top-nav-content">
                {{-- Left: địa chỉ + hotline --}}
                <div class="nav-left">
                    <div class="nav-item">
                        <svg class="icon" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        <span>Địa chỉ liên hệ</span>
                    </div>
                    <div class="nav-item">
                        <svg class="icon" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                        </svg>
                        <span>Hotline trực tuyến</span>
                    </div>
                </div>

                {{-- Right: social + auth --}}
                <div class="nav-right">
                    <div class="social-links">
                        <a href="#">Shopee</a><a href="#">Lazada</a><a href="#">Instagram</a>
                        <a href="#">Tiktok</a><a href="#">Youtube</a><a href="#">Facebook</a>
                    </div>

                    <div class="auth-section">
                        <div class="nav-item">
                            <svg class="icon" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                            </svg>
                            <span>Tin tức</span>
                            <svg class="icon" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                            </svg>
                            <span><a href="{{ url('/profile') }}">Hồ sơ</a></span>
                        </div>

                        @auth
                        <div class="nav-item">
                            <svg class="icon" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16z" />
                            </svg>
                            <span>Xin chào, {{ Auth::user()->name }}</span>
                        </div>
                        <div class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" style="display:inline">
                                @csrf
                                <button type="submit" style="background:none;border:none;color:inherit;cursor:pointer;">Đăng xuất</button>
                            </form>
                        </div>
                        @else
                        <div class="nav-item">
                            <svg class="icon" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16z" />
                            </svg>
                            <span><a href="{{ route('register') }}">Đăng ký</a><a href="{{ route('login') }}"> / Đăng nhập</a></span>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Main Header ===== --}}
    <div class="main-header">
        <div class="container_header">
            <div class="header-content">
                {{-- Logo --}}
                <div class="logo">
                    <div class="logo-icon"><img src="{{ asset('img/logo_web.jpg') }}" alt="Lắc Đầu Logo"></div>
                </div>

                {{-- Search + Danh mục --}}
                <div class="search-section">
                    <button class="category-btn">
                        <svg class="icon" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span>DANH MỤC</span>
                    </button>
                    <div class="search-input-container">
                        <input type="text" placeholder="Bạn cần tìm gì?" class="search-input">
                        <button class="search-btn">
                            <svg class="icon" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Right: hotline + tư vấn + giỏ hàng --}}
                <div class="right-section">
                    <div class="contact-item">
                        <div class="contact-icon hotline-icon">
                            <svg class="icon" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                            </svg>
                        </div>
                        <div class="contact-text">
                            <div class="contact-label">Hotline</div>
                            <div class="contact-value">0349.296.461</div>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon consultation-icon">
                            <svg class="icon" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z" />
                            </svg>
                        </div>
                        <div class="contact-text">
                            <div class="contact-label">Tư vấn trực tiếp</div>
                        </div>
                    </div>

                    {{-- Mini cart --}}
                    @php
                    use Illuminate\Support\Facades\Auth;
                    $user = Auth::user();
                    $cartItems = \App\Models\CartItem::with('product')->where('user_id', optional($user)->id)->get();
                    $totalQuantity = (int) $cartItems->sum('quantity');
                    @endphp

                    <div class="cart-wrapper" style="position: relative">
                        <div class="cart" onclick="toggleCartDropdown()">
                            <span>Giỏ hàng</span>
                            <span id="cartBadge" class="cart-badge">{{ $totalQuantity }}</span>
                        </div>

                        <div id="cart-dropdown" class="cart-dropdown">
                            {{-- truyền dữ liệu để view hiển thị ngay --}}
                            @include('layouts.cart_dropdown', ['cartItems' => $cartItems, 'totalQuantity' => $totalQuantity])
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
{{-- Styles mini cart + toast --}}
<style>
    .cart-dropdown {
        display: none;
        position: absolute;
        right: 0;
        top: 100%;
        z-index: 1000;
    }

    .cart-dropdown.show {
        display: block;
    }

    /* Toast */
    .toast {
        padding: 12px 16px;
        border-radius: 6px;
        color: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .18);
        margin-top: 10px;
        opacity: 0;
        transform: translateX(100%);
        transition: all .35s ease;
        font-size: 14px;
        max-width: 320px;
        word-break: break-word;
    }

    .toast.show {
        opacity: 1;
        transform: translateX(0);
    }

    .toast-success {
        background: #0d9488;
    }

    .toast-error {
        background: #dc2626;
    }
</style>
<script>
    (function() {
        if (window.__CART_BOUND__) return;
        window.__CART_BOUND__ = true;

        // === Toast helper: tự tạo container nếu thiếu ===
        function ensureToastContainer() {
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
            return c;
        }

        function showToast(message, type = 'success') {
            const container = ensureToastContainer();
            const toast = document.createElement('div');
            toast.className = 'toast ' + (type === 'error' ? 'toast-error' : 'toast-success');
            toast.textContent = message;

            // style inline để chắc chắn hiển thị
            toast.style.padding = '12px 16px';
            toast.style.borderRadius = '6px';
            toast.style.color = '#fff';
            toast.style.boxShadow = '0 2px 8px rgba(0,0,0,.18)';
            toast.style.marginTop = '10px';
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            toast.style.transition = 'all .35s ease';
            toast.style.maxWidth = '320px';
            toast.style.fontSize = '14px';
            toast.style.wordBreak = 'break-word';
            toast.style.background = (type === 'error' ? '#dc2626' : '#0d9488');

            container.appendChild(toast);
            requestAnimationFrame(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateX(0)';
            });

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => toast.remove(), 400);
            }, 2000);
        }

        // Toggle dropdown giỏ
        window.toggleCartDropdown = function() {
            const dd = document.getElementById("cart-dropdown");
            if (dd) dd.classList.toggle("show");
        };
        document.addEventListener("click", function(e) {
            const wrap = document.querySelector(".cart-wrapper");
            const dd = document.getElementById("cart-dropdown");
            if (wrap && dd && !wrap.contains(e.target)) dd.classList.remove("show");
        });

        // Xoá/bind lại nút xoá trong dropdown (khi thay HTML)
        function bindRemoveHandlers() {
            document.querySelectorAll("#cart-dropdown .cart-remove-form").forEach(f => {
                if (f.__bound__) return;
                f.__bound__ = true;
                f.addEventListener("submit", function(e) {
                    e.preventDefault();
                    fetch(this.getAttribute("action"), {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "X-Requested-With": "XMLHttpRequest"
                        },
                        body: new URLSearchParams(new FormData(this)),
                    }).then(() => refreshMiniCart());
                });
            });
        }

        function refreshMiniCart() {
            fetch(`{{ route('cart.mini') }}`, {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                })
                .then(res => res.text())
                .then(html => {
                    const dd = document.getElementById("cart-dropdown");
                    if (dd) {
                        dd.innerHTML = html;
                        bindRemoveHandlers();
                        const totalText = dd.querySelector(".cart-total");
                        const badge = document.getElementById("cartBadge");
                        if (totalText) {
                            const m = totalText.textContent.match(/\((\d+)\s+sản phẩm\)/);
                            if (m && m[1] && badge) badge.textContent = m[1];
                        }
                    }
                });
        }

        // CHỈ 1 handler delegation cho .add-to-cart-btn (listing + trang chi tiết)
        document.addEventListener("click", async function(e) {
            const btn = e.target.closest(".add-to-cart-btn");
            if (!btn) return;
            e.preventDefault();

            // chống bấm nhanh 2 lần
            if (btn.dataset.busy === '1') return;
            btn.dataset.busy = '1';

            let pid = btn.dataset.id;
            if (!pid) {
                const form = btn.closest(`form[action="{{ route('cart.add') }}"]`);
                if (form) {
                    const inp = form.querySelector('input[name="product_id"]');
                    if (inp) pid = inp.value;
                }
            }
            if (!pid) {
                btn.dataset.busy = '0';
                return;
            }

            try {
                const res = await fetch(`{{ route('cart.add') }}`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                    body: JSON.stringify({
                        product_id: pid
                    }),
                });
                const data = await res.json().catch(() => ({}));

                if (res.ok && data.status === "success") {
                    // 1) Cập nhật badge
                    const badge = document.getElementById("cartBadge");
                    if (badge && typeof data.totalQuantity !== 'undefined') {
                        badge.textContent = data.totalQuantity;
                    }
                    // 2) Cập nhật dropdown bằng HTML trả về (nếu có)
                    const dd = document.getElementById("cart-dropdown");
                    if (dd && data.html) {
                        dd.innerHTML = data.html;
                        bindRemoveHandlers();
                    }
                    // 3) Mở dropdown + hiện toast
                    if (dd) dd.classList.add("show");
                    showToast("🛒 Đã thêm vào giỏ hàng thành công!", "success");

                    // (tuỳ chọn) Reload sau 1.2s nếu bạn vẫn muốn đồng bộ toàn bộ giao diện
                    // setTimeout(() => location.reload(), 1200);

                } else {
                    if (res.status === 401) {
                        showToast("🔒 Bạn cần đăng nhập để thêm sản phẩm", "error");
                        setTimeout(() => location.href = `{{ route('login') }}`, 800);
                    } else {
                        showToast(`❌ ${(data && data.message) || 'Không thể thêm vào giỏ'}`, "error");
                    }
                }
            } catch (err) {
                console.error(err);
                showToast("❌ Có lỗi mạng. Vui lòng thử lại.", "error");
            } finally {
                setTimeout(() => {
                    btn.dataset.busy = '0';
                }, 600);
            }
        });

        // Nếu trước đó bạn từng dùng "mẹo" localStorage để mở dropdown sau reload, bỏ hẳn đoạn đó đi.

    })();
</script>