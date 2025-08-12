<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Lắc Đầu</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #0d9488;
            color: white;
            padding: 15px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
        }

        .nav {
            background-color: #333;
            overflow: hidden;
        }

        .nav a {
            float: left;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 16px;
        }

        .nav a:hover {
            background-color: #ddd;
            color: black;
        }

        .content {
            padding: 20px;
            background-color: white;
            margin-top: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .content h2 {
            color: #0d9488;
        }

        .logout-form {
            margin-top: 20px;
        }

        .logout-form button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        .logout-form button:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Admin Dashboard - Lắc Đầu</h1>
        <p>Xin chào, {{ Auth::user()->name }}</p>
    </div>
    <div class="nav">
        <a href="{{ route('home') }}">Trang chủ</a>
        <a href="#">Quản lý sản phẩm</a>
        <a href="#">Quản lý người dùng</a>
        <a href="#">Quản lý đơn hàng</a>
    </div>
    <div class="container">
        <div class="content">
            <h2>Chào mừng đến với giao diện quản trị</h2>
            <p>Đây là trang dashboard dành cho admin. Bạn có thể quản lý sản phẩm, người dùng, và đơn hàng từ đây.</p>
            <div class="logout-form">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">Đăng xuất</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>