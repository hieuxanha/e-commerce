<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
</head>

<body>

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
                        @guest
                        <div class="nav-item">
                            <svg class="icon" viewBox="0 0 20 20">
                                <path
                                    fill-rule="evenodd"
                                    d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span><a href="{{ route('register') }} ">Đăng ký</a>
                                <a href="{{ route('login') }}"> / Đăng nhập</a></span>
                        </div>
                        @endguest
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
                        <img src="../img/logo.png" alt="Lắc Đầu Logo" />
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
                    <!-- Shopping Cart -->
                    <div class="cart">
                        <div class="cart-icon-container">
                            <svg class="cart-icon" viewBox="0 0 20 20">
                                <path
                                    d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                            </svg>
                            <span class="cart-badge">0</span>
                        </div>
                        <span class="cart-text">Giỏ hàng</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- LOGIN FORM -->
    <div class="login-container">
        <div class="login-form">
            <h2>Thông tin khách hàng đăng nhập hệ thống</h2>

            {{-- Hiển thị lỗi --}}
            @if (session('error'))
            <div style=" color: red;">{{ session('error') }}
            </div>
            @endif

            @if ($errors->any())
            <div style="color: red;">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <label for="email">Email đăng nhập</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Nhập email của bạn"
                    required />

                <label for="password">Mật khẩu</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Nhập mật khẩu"
                    required />

                <button type="submit" class="login-btn">Đăng nhập</button>
            </form>
        </div>
    </div>
</body>

</html>