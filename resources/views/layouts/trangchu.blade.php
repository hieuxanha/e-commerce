<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lắc Đầu - E-commerce</title>
    <link rel="stylesheet" href="../css/header.css" />
    <!-- <link rel="stylesheet" href="../css/homepage.css" /> -->
    <link rel="stylesheet" href="../css/footer.css" />

    <style></style>
</head>

<body>
    <!-- Top Navigation Bar -->
    <div class="top-nav">
        <div class="container_header">
            <div class="top-nav-content">
                <!-- Left side - Location and Hotline -->
                <div class="nav-left">
                    <div class="nav-item">
                        <svg class="icon" viewBox="0 0 20 20">
                            <path
                                fill-rule="evenodd"
                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Địa chỉ liên hệ</span>
                    </div>
                    <div class="nav-item">
                        <svg class="icon" viewBox="0 0 20 20">
                            <path
                                d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                        </svg>
                        <span>Hotline trực tuyến</span>
                    </div>
                </div>
                <!-- Right side - Social Media and Auth -->
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
                                <path
                                    fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
                                    clip-rule="evenodd" />
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
                            <span><a href="{{ route('register') }} ">Đăng ký</a>
                                <a href="{{route('login') }} "> / Đăng nhập</a></span>
                        </div>
                        @endauth

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <div class="main-header">
        <div class="container_header">
            <div class="header-content">
                <!-- Logo -->
                <div class="logo">
                    <div class="logo-icon">
                        <img src="../img/logo_web.jpg" alt="Lắc Đầu Logo" />
                    </div>
                </div>
                <!-- Categories Button and Search -->
                <div class="search-section">
                    <button class="category-btn">
                        <svg class="icon" viewBox="0 0 20 20">
                            <path
                                fill-rule="evenodd"
                                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>DANH MỤC</span>
                    </button>
                    <div class="search-input-container">
                        <input
                            type="text"
                            placeholder="Bạn cần tìm gì?"
                            class="search-input" />
                        <button class="search-btn">
                            <svg class="icon" viewBox="0 0 20 20">
                                <path
                                    fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
                <!-- Right side - Contact and Cart -->
                <div class="right-section">
                    <!-- Hotline -->
                    <div class="contact-item">
                        <div class="contact-icon hotline-icon">
                            <svg class="icon" viewBox="0 0 20 20">
                                <path
                                    d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                            </svg>
                        </div>
                        <div class="contact-text">
                            <div class="contact-label">Hotline</div>
                            <div class="contact-value">0349.296.461</div>
                        </div>
                    </div>
                    <!-- Consultation -->
                    <div class="contact-item">
                        <div class="contact-icon consultation-icon">
                            <svg class="icon" viewBox="0 0 20 20">
                                <path
                                    d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z" />
                            </svg>
                        </div>
                        <div class="contact-text">
                            <div class="contact-label">
                                Tư vấn trực tiếp
                            </div>
                        </div>
                    </div>
                    <!-- GÓI TOÀN BỘ TRONG THẺ CHA -->
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

                        <!-- DROPDOWN GIỎ HÀNG -->
                        <div id="cart-dropdown" class="cart-dropdown">
                            @forelse($cartItems as $item)
                            @php
                            $price = $item->product->gia_khuyen_mai ?? $item->product->gia;
                            $itemTotal = $price * $item->quantity;
                            @endphp
                            <div class="cart-item">
                                <img src="{{ asset('storage/' . $item->product->hinh_anh_chinh) }}" width="60" height="60">
                                <div>
                                    <strong>{{ $item->product->ten_san_pham }}</strong>
                                    <br>
                                    x{{ $item->quantity }}<br>
                                    <strong>{{ number_format($itemTotal, 0, ',', '.') }}đ</strong>
                                </div>
                            </div>
                            @empty
                            <p style="padding: 10px">Chưa có sản phẩm trong giỏ hàng.</p>
                            @endforelse

                            @if($totalQuantity > 0)
                            <div class="cart-total">
                                Tổng tiền hàng ({{ $totalQuantity }} sản phẩm):
                                <strong>{{ number_format($totalAmount, 0, ',', '.') }}đ</strong>
                            </div>
                            <a href="{{ route('cart.checkout') }}" class="btn-primary" style="display:block; text-align:center">THANH TOÁN NGAY</a>
                            @endif
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="container">
        <div class="main-layout">
            <!-- Left Sidebar -->
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

            <!-- Main Content -->
            <main class="main-content">
                <!-- Hero: chỉ ảnh -->
                <section class="hero-banner image-only">
                    <img src="{{ asset('img/main_img.png') }}" alt="Lót chuột in theo yêu cầu" class="hero-img" loading="lazy" decoding="async">
                </section>

                <!-- Product Cards -->
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

            <!-- Right Sidebar -->
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


        <!-- LỚT CHUỘT Section -->
        <div class="container">
            @foreach($categories as $cat)
            <div class="product-section"> {{-- ✅ Tách mỗi danh mục thành 1 block riêng --}}
                <div class="section-header">
                    <h2 class="section-title">{{ strtoupper($cat->ten_danh_muc) }} 🐭 🐁</h2>
                    <div class="section-filters">
                        <a class="filter-tab" href="">XEM THÊM →</a>
                    </div>
                </div>

                <div class="products-grid">
                    @forelse($cat->products as $p)
                    @php
                    $percent = ($p->gia && $p->gia_khuyen_mai && $p->gia > 0)
                    ? round(100 - ($p->gia_khuyen_mai / $p->gia) * 100)
                    : null;

                    $img = $p->hinh_anh_chinh
                    ? (preg_match('/^https?:\/\//', $p->hinh_anh_chinh)
                    ? $p->hinh_anh_chinh
                    : asset('storage/' . $p->hinh_anh_chinh))
                    : asset('img/placeholder-product.jpg');
                    @endphp

                    <div class="product-item">
                        @if($percent)
                        <div class="discount-label">-{{ $percent }}%</div>
                        @endif

                        <img src="{{ $img }}" alt="{{ $p->ten_san_pham }}" class="product-image">

                        <div class="product-info">
                            <div class="product-code">Mã: {{ $p->sku }}</div>
                            <div class="product-name">{{ $p->ten_san_pham }}</div>
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

                        <button class="add-to-cart-btn" data-id="{{ $p->id }}">+</button>
                    </div>
                    @empty
                    <p>Chưa có sản phẩm trong danh mục này.</p>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>

        <div class="container">
            <div class="product-section">
                @foreach($categories as $cat)
                <div class="section-header">
                    <h2 class="section-title">{{ strtoupper($cat->ten_danh_muc) }} 🐭 🐁</h2>
                    <div class="section-filters">
                        <a class="filter-tab" href="">XEM THÊM →</a>
                    </div>
                </div>

                <div class="products-grid">
                    @forelse($cat->products as $p)
                    @php
                    $percent = ($p->gia && $p->gia_khuyen_mai && $p->gia > 0)
                    ? round(100 - ($p->gia_khuyen_mai / $p->gia) * 100)
                    : null;

                    $img = $p->hinh_anh_chinh
                    ? (preg_match('/^https?:\/\//', $p->hinh_anh_chinh)
                    ? $p->hinh_anh_chinh
                    : asset('storage/' . $p->hinh_anh_chinh))
                    : asset('img/placeholder-product.jpg');
                    @endphp

                    <div class="product-item">
                        @if($percent)
                        <div class="discount-label">-{{ $percent }}%</div>
                        @endif

                        <img src="{{ $img }}" alt="{{ $p->ten_san_pham }}" class="product-image">

                        <div class="product-info">
                            <div class="product-code">Mã: {{ $p->sku }}</div>
                            <div class="product-name">{{ $p->ten_san_pham }}</div>
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

                        <button class="add-to-cart-btn">+</button>
                    </div>
                    @empty
                    <p>Chưa có sản phẩm trong danh mục này.</p>
                    @endforelse
                </div>
                @endforeach
            </div>

        </div>


        <!-- GAMING GEAR Section -->
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
                    <div class="product-item">
                        <img
                            src="../img/lottchuot1.jpg"
                            alt="BÀN PHÍM CƠ READSON H108"
                            class="product-image" />
                        <div class="product-info">
                            <div class="product-code">Mã: CBBPH10837</div>
                            <div class="product-name">
                                BÀN PHÍM CƠ READSON H108 TRANSPARENT STREAM X
                                BLUE
                            </div>
                            <div class="product-status">Còn hàng</div>
                            <div class="product-pricing">
                                <div class="current-price">830.000đ</div>
                            </div>
                        </div>
                        <button class="add-to-cart-btn">+</button>
                    </div>
                    <div class="product-item">
                        <img
                            src="../img/lottchuot1.jpg"
                            alt="BÀN PHÍM CƠ AKESTER AK61 RGB"
                            class="product-image" />
                        <div class="product-info">
                            <div class="product-code">Mã: BPASE0014</div>
                            <div class="product-name">
                                BÀN PHÍM CƠ AKESTER AK61 RGB WHITE BLUE SWITCH
                            </div>
                            <div class="product-status">Còn hàng</div>
                            <div class="product-pricing">
                                <div class="current-price">360.000đ</div>
                            </div>
                        </div>
                        <button class="add-to-cart-btn">+</button>
                    </div>
                    <div class="product-item">
                        <img
                            src="../img/lottchuot1.jpg"
                            alt="BÀN PHÍM CƠ LANGTU LT75"
                            class="product-image" />
                        <div class="product-info">
                            <div class="product-code">Mã: BPLT0001</div>
                            <div class="product-name">
                                BÀN PHÍM CƠ LANGTU LT75 MULTI MODES GRADIENT
                                BLACK SILVER
                            </div>
                            <div class="product-status">Còn hàng</div>
                            <div class="product-pricing">
                                <div class="current-price">990.000đ</div>
                            </div>
                        </div>
                        <button class="add-to-cart-btn">+</button>
                    </div>
                    <div class="product-item">
                        <img
                            src="../img/lottchuot1.jpg"
                            alt="CHUỘT INPHIC IN99 PRO"
                            class="product-image" />
                        <div class="product-info">
                            <div class="product-code">Mã: CIP0017</div>
                            <div class="product-name">
                                CHUỘT INPHIC IN99 PRO MULTI MODES ĐEN
                            </div>
                            <div class="product-status">Còn hàng</div>
                            <div class="product-pricing">
                                <div class="current-price">690.000đ</div>
                            </div>
                        </div>
                        <button class="add-to-cart-btn">+</button>
                    </div>
                    <div class="product-item">
                        <img
                            src="../img/lottchuot1.jpg"
                            alt="BÀN PHÍM GIẢ CƠ EWEADN V87"
                            class="product-image" />
                        <div class="product-info">
                            <div class="product-code">Mã: BPK0064</div>
                            <div class="product-name">
                                BÀN PHÍM GIẢ CƠ EWEADN V87 MULTI MODES CREAM
                                GRAY RGB
                            </div>
                            <div class="product-status">Còn hàng</div>
                            <div class="product-pricing">
                                <div class="current-price">450.000đ</div>
                            </div>
                        </div>
                        <button class="add-to-cart-btn">+</button>
                    </div>
                    <div class="product-item">
                        <img
                            src="../img/lottchuot1.jpg"
                            alt="TAY CẦM AOLON ALN52286"
                            class="product-image" />
                        <div class="product-info">
                            <div class="product-code">Mã: TCALN002</div>
                            <div class="product-name">
                                TAY CẦM AOLON ALN52286 DARK PATTERN RGB MULTI
                                MODES
                            </div>
                            <div class="product-status">Còn hàng</div>
                            <div class="product-pricing">
                                <div class="current-price">490.000đ</div>
                            </div>
                        </div>
                        <button class="add-to-cart-btn">+</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <footer class="main-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <div class="footer-logo">
                        <img
                            src="../img/logo.png"
                            alt="Lắc Đầu Logo" />
                    </div>
                    <div class="contact-info">
                        <p>
                            <svg
                                class="contact-icon"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            66 Xã Đàn, Phường Phương Liên, Quận Đống Đa, Hà
                            Nội
                        </p>
                        <p>
                            <svg
                                class="contact-icon"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            0349.296.461
                        </p>
                        <p>
                            <svg
                                class="contact-icon"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline
                                    points="22,6 12,13 2,6"></polyline>
                            </svg>
                            lacdaushop@gmail.com
                        </p>
                    </div>
                    <div class="social-media-icons">
                        <a href="#" aria-label="Facebook"><img
                                src="/placeholder.svg?height=24&width=24"
                                alt="Facebook" /></a>
                        <a href="#" aria-label="Instagram"><img
                                src="/placeholder.svg?height=24&width=24"
                                alt="Instagram" /></a>
                        <a href="#" aria-label="TikTok"><img
                                src="/placeholder.svg?height=24&width=24"
                                alt="TikTok" /></a>
                        <a href="#" aria-label="Email"><img
                                src="/placeholder.svg?height=24&width=24"
                                alt="Email" /></a>
                        <a href="#" aria-label="Phone"><img
                                src="/placeholder.svg?height=24&width=24"
                                alt="Phone" /></a>
                        <a href="#" aria-label="YouTube"><img
                                src="/placeholder.svg?height=24&width=24"
                                alt="YouTube" /></a>
                    </div>
                    <div class="bocongthuong-badge">
                        <img
                            src="/placeholder.svg?height=60&width=150"
                            alt="Đã thông báo Bộ Công Thương" />
                    </div>
                </div>

                <div class="footer-col">
                    <h3>HỖ TRỢ KHÁCH HÀNG</h3>
                    <ul>
                        <li>
                            <a href="#">Hướng dẫn mua hàng trực tuyến</a>
                        </li>
                        <li><a href="#">Hướng dẫn thanh toán</a></li>
                        <li><a href="#">Góp ý, Khiếu Nại</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h3>CHÍNH SÁCH CHUNG</h3>
                    <ul>
                        <li><a href="#">Chính sách, quy định chung</a></li>
                        <li><a href="#">Chính sách vận chuyển</a></li>
                        <li><a href="#">Chính sách bảo hành</a></li>
                        <li>
                            <a href="#">Chính sách đổi trả và hoàn tiền</a>
                        </li>
                        <li><a href="#">Chính sách xử lý khiếu nại</a></li>
                        <li>
                            <a href="#">Bảo mật thông tin khách hàng</a>
                        </li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h3>FANPAGE FACEBOOK</h3>
                    <div class="facebook-widget">
                        <img
                            src="/placeholder.svg?height=150&width=250"
                            alt="Facebook Fanpage" />
                        <div class="facebook-overlay">
                            <p>Lắc Đầu</p>
                            <p>232.567 người theo dõi</p>
                            <button class="facebook-follow-btn">
                                <svg
                                    viewBox="0 0 24 24"
                                    width="16"
                                    height="16"
                                    fill="white">
                                    <path
                                        d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                                </svg>
                                Theo dõi Trang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
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


</body>

</html>