{{-- ===== Header (Top bar + Main header) ===== --}}
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
                    <a href="#">Shopee</a>
                    <a href="#">Lazada</a>
                    <a href="#">Instagram</a>
                    <a href="#">Tiktok</a>
                    <a href="#">Youtube</a>
                    <a href="#">Facebook</a>
                </div>

                <div class="auth-section">
                    <div class="nav-item">
                        <svg class="icon" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                        </svg>
                        <span>Tin tức</span>
                    </div>

                    @auth
                    <div class="nav-item">
                        <svg class="icon" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="..." />
                        </svg>
                        <span>Xin chào, {{ Auth::user()->name }}</span>
                    </div>
                    <div class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" style="display:inline">
                            @csrf
                            <button type="submit" style="background:none;border:none;color:inherit;cursor:pointer;">
                                Đăng xuất
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="nav-item">
                        <svg class="icon" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="..." />
                        </svg>
                        <span>
                            <a href="{{ route('register') }}">Đăng ký</a>
                            <a href="{{ route('login') }}"> / Đăng nhập</a>
                        </span>
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
                <div class="logo-icon">
                    <img src="{{ asset('img/logo_web.jpg') }}" alt="Lắc Đầu Logo">
                </div>
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

                {{-- Mini cart đọc từ DB --}}
                @php
                use Illuminate\Support\Facades\Auth;
                $user = Auth::user();
                $cartItems = \App\Models\CartItem::with('product')->where('user_id', optional($user)->id)->get();
                $totalQuantity = $cartItems->sum('quantity');
                $totalAmount = $cartItems->reduce(function ($carry, $item) {
                $price = $item->product->gia_khuyen_mai ?? $item->product->gia;
                return $carry + $price * $item->quantity;
                }, 0);
                @endphp

                <div class="cart-wrapper" style="position: relative">
                    <div class="cart" onclick="toggleCartDropdown()">
                        <span>Giỏ hàng</span>
                        <span class="cart-badge">{{ $totalQuantity }}</span>
                    </div>

                    <div id="cart-dropdown" class="cart-dropdown">
                        @forelse($cartItems as $item)
                        @php
                        $price = $item->product->gia_khuyen_mai ?? $item->product->gia;
                        $itemTotal = $price * $item->quantity;
                        $img = $item->product->hinh_anh_chinh
                        ? asset('storage/' . $item->product->hinh_anh_chinh)
                        : asset('img/placeholder-product.jpg');
                        @endphp

                        <div class="cart-item d-flex align-items-center justify-content-between" style="gap: 10px; padding: 8px 0; border-bottom: 1px solid #eee;">
                            <img src="{{ $img }}" width="60" height="60" alt="{{ $item->product->ten_san_pham }}">
                            <div style="flex:1; padding: 0 10px;">
                                <strong>{{ $item->product->ten_san_pham }}</strong><br>
                                x{{ $item->quantity }}<br>
                                <strong>{{ number_format($itemTotal, 0, ',', '.') }}đ</strong>
                            </div>
                            <form method="POST" action="{{ route('cart.remove', $item->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" style="padding: 4px 8px;" title="Xóa sản phẩm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                        @empty
                        <p style="padding:10px">Chưa có sản phẩm trong giỏ hàng.</p>
                        @endforelse

                        @if($totalQuantity > 0)
                        <div class="cart-total" style="padding: 10px 0; font-size: 14px;">
                            Tổng tiền hàng ({{ $totalQuantity }} sản phẩm):
                            <strong>{{ number_format($totalAmount, 0, ',', '.') }}đ</strong>
                        </div>
                        <a href="{{ route('cart.checkout') }}" class="btn btn-primary" style="display:block; text-align:center;">THANH TOÁN NGAY</a>
                        @endif
                    </div>
                </div>


            </div>

        </div>
    </div>
</div>
{{-- (Tuỳ bạn) function toggleCartDropdown() có thể để trong layout chung --}}
<script>
    // Simple JavaScript for interactivity
    const searchInput = document.querySelector(".search-input");

    searchInput.addEventListener("focus", function() {
        this.style.borderColor = "#0d9488";
        this.style.boxShadow = "0 0 0 2px rgba(13, 148, 136, 0.2)";
    });

    searchInput.addEventListener("blur", function() {
        this.style.borderColor = "#d1d5db";
        this.style.boxShadow = "none";
    });



    // Product card click handlers







    function toggleCartDropdown() {
        const dropdown = document.getElementById('cart-dropdown');
        dropdown.classList.toggle('visible');
        dropdown.classList.toggle('show'); // thêm .show { display: block }

    }
</script>
<script>
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.id;

            fetch("{{ route('cart.add') }}", {

                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        product_id: productId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);

                        document.querySelector('.cart-badge').textContent = data.totalQuantity;
                    } else {
                        alert('Lỗi: ' + data.message);
                    }
                })
                .catch(err => console.error(err));
        });
    });
</script>