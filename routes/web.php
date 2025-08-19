<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

// Controllers đúng namespace
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NhanVien\ProductController;
use App\Http\Controllers\NhanVien\BrandController;
use App\Http\Controllers\NhanVien\CategoryController;
use App\Http\Controllers\CartController;


/*
|---------------------
| Public
|---------------------
*/

// Cho / trỏ về /home
Route::redirect('/', '/home');

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');


// Trang chủ: LẤY dữ liệu từ DB (HomeController@index)
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Auth
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Trang chi tiết sản phẩm tĩnh (nếu có)
Route::view('/chitietsanpham', 'layouts.chitietsanpham')->name('sanpham.chitiet');

/*
|---------------------
| Admin / Nhân viên
|---------------------
*/

Route::get('/admin/dashboard', fn() => view('admin.dashboard'))
    ->name('admin.dashboard')
    ->middleware('auth');

// NHỚ: KHÔNG dùng Route::view cho /nhanvien/san-pham nữa
Route::prefix('nhanvien')->name('nhanvien.')->middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('nhanvien.dashboard'))->name('dashboard');


    // Giao diện danh sách sản phẩm (LIST)
    Route::get('/danh-sach-san-pham', function () {
        return view('nhanvien.QL_sanpham');
    })->name('danhsachsanpham');

    Route::get('/san-pham/them', function () {
        return view('nhanvien.Them_thongtin_sp');
    })->name('sanpham.them');


    Route::get('/san-pham', [ProductController::class, 'index'])->name('sanpham');
    Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
});
