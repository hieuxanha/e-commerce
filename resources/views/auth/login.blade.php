<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/header.css') }}" />


</head>

<body>

    @include('layouts.header')

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <div class="container_header">
            <span> <a href="{{asset('/home')}}">TRANG CHỦ</a> </span>
            <span class="separator">/</span>
            <span>ĐĂNG NHẬP</span>
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