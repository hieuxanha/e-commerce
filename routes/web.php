<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;

// === Alias rõ ràng ===
use App\Http\Controllers\ProductController as FrontProductController;          // khách xem
use App\Http\Controllers\NhanVien\ProductController as NhanVienProductController;    // admin/nhân viên
use App\Http\Controllers\NhanVien\BrandController;
use App\Http\Controllers\NhanVien\CategoryController;
use App\Http\Controllers\NhanVien\ProductController;

/* Public */

Route::redirect('/', '/home');

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



// --- Chi tiết sản phẩm bằng slug ---
Route::get('/san-pham/{slug}', [FrontProductController::class, 'show'])
    ->where('slug', '[A-Za-z0-9\-._]+')
    ->name('sanpham.chitiet');

// Dự phòng theo id (khi sản phẩm chưa có slug)
Route::get('/san-pham/id/{id}', [FrontProductController::class, 'showById'])
    ->whereNumber('id')
    ->name('sanpham.chitiet.id');



Route::get('/admin/dashboard', fn() => view('admin.dashboard'))
    ->name('admin.dashboard')
    ->middleware('auth');
// ✅ Lý do thật sự: Bạn khai báo route trong group prefix('nhanvien') rồi lại thêm 'nhanvien' trong URL nữa →
//  đặt tên đúng nhưng Laravel không nhận vì bạn bị gán prefix 2 lần.
Route::prefix('nhanvien')->name('nhanvien.')->middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('nhanvien.dashboard'))->name('dashboard');

    // Route::get('/danh-sach-san-pham', fn() => view('nhanvien.QL_sanpham'))->name('danhsachsanpham');
    // Giao diện form thêm sản phẩm

    // Giao diện danh sách sản phẩm
    Route::get('/danh-sach-san-pham', [ProductController::class, 'index'])
        ->name('danhsachsanpham');

    // Giao diện form thêm sản phẩm
    Route::get('/san-pham/{id}/edit', [ProductController::class, 'edit'])->name('sanpham.edit');
    Route::put('/san-pham/{id}', [ProductController::class, 'update'])->name('sanpham.update');
    Route::delete('/san-pham/{id}', [ProductController::class, 'destroy'])->name('sanpham.destroy');
    // Xử lý lưu sản phẩm

    // Route::get('/san-pham/them', fn() => view('nhanvien.Them_thongtin_sp'))->name('sanpham.them');

    Route::get('/san-pham/them', fn() => view('nhanvien.Them_thongtin_sp'))->name('sanpham.them');

    // DÙNG ALIAS AdminProductController
    Route::get('/san-pham', [NhanVienProductController::class, 'index'])->name('sanpham');
    Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::post('/products', [NhanVienProductController::class, 'store'])->name('products.store');
});
