<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;

// Khách (frontend)
use App\Http\Controllers\ProductController as FrontProductController;
use App\Http\Controllers\CartDetailController; // <— thêm dòng này
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController; // <— thêm dòng này
use App\Http\Controllers\ProductListController;
use App\Http\Controllers\ChatbotController;


// Nhân viên / Admin
use App\Http\Controllers\NhanVien\NhanvienDashboardController;
use App\Http\Controllers\NhanVien\ProductController as NhanVienProductController;
use App\Http\Controllers\NhanVien\BrandController;
use App\Http\Controllers\NhanVien\CategoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\NhanVien\BrandFrontController;

use App\Http\Controllers\Admin\VanChuyenController;
use App\Http\Controllers\Admin\phanquyenController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CustomerAdminController;
use App\Http\Controllers\Admin\DonHangController;
use App\Http\Controllers\Admin\TonKhoController;
use App\Http\Controllers\Admin\ThongKeController;


use Illuminate\Support\Facades\Mail;






/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
// Route::get('/dashboard', fn() => view('nhanvien.dashboard'))->name('dashboard');

// Mail::raw('hieu1123', function ($m) {
//     $m->to('22111061159@hunre.edu.vn')->subject('Ping');
// });
Route::redirect('/', '/home');

Route::get('/home', [HomeController::class, 'index'])->name('home');
// Route::get('/danhmucsanpham', fn() => view('layouts.danhmucsanpham'))->name('danhmucsanpham');
Route::get('/danh-muc/{id}', [ProductListController::class, 'show'])->name('danhmuc.show');

/** Cart AJAX */
// AJAX
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart/mini', [CartController::class, 'mini'])->name('cart.mini');
Route::patch('/cart/{product}/qty', [CartController::class, 'updateQuantity'])->name('cart.qty');
Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');

// Pages (giữ 1 nhóm)
Route::get('/cart',     [CartDetailController::class, 'index'])->name('cart.index');
Route::get('/checkout', [CartDetailController::class, 'checkout'])->name('cart.checkout');
Route::post('/cart/update', [CartDetailController::class, 'updateQuantityAjax'])->name('cart.update');
Route::post('/cart/remove-by-product', [CartDetailController::class, 'removeByProduct'])->name('cart.remove.product');
Route::post('/cart/clear', [CartDetailController::class, 'clear'])->name('cart.clear');

Route::post('/reviews', [ReviewController::class, 'store'])
    ->name('reviews.store')
    ->middleware('auth');
Route::post('/chat/ask', [ChatbotController::class, 'ask'])->name('chat.ask');


// routes/web.php
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.index');
Route::get('/orders/{id}', [ProfileController::class, 'orderShow'])
    ->whereNumber('id')
    ->name('orders.show');
Route::match(['put', 'patch'], '/profile', [ProfileController::class, 'update'])->name('profile.update');

// CHECKOUT (thông tin giao hàng + đặt hàng)
// Route::get('/checkout', [CheckoutController::class, 'index'])
//     ->name('checkout.index');
// Route::post('/checkout/submit', [CheckoutController::class, 'submit'])
//     ->name('checkout.submit');
// Route::get('/order/placed', [CheckoutController::class, 'placed'])
//     ->name('order.placed');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Chi tiết sản phẩm (slug)
Route::get('/san-pham/{slug}', [FrontProductController::class, 'show'])
    ->where('slug', '[A-Za-z0-9\-._]+')
    ->name('sanpham.chitiet');

// Dự phòng theo id
Route::get('/san-pham/id/{id}', [FrontProductController::class, 'showById'])
    ->whereNumber('id')
    ->name('sanpham.chitiet.id');

Route::get('/thong-tin-giao-hang', [CheckoutController::class, 'index'])
    ->name('checkout.info');
Route::post('/checkout/submit', [CheckoutController::class, 'submit'])->name('checkout.submit');

Route::get('/da-dat-hang', [CheckoutController::class, 'placed'])
    ->name('order.placed');


/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // tĩnh

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/phan-quyen', [PhanQuyenController::class, 'index'])->name('phanquyen.index');
    Route::patch('/phan-quyen/{user}/role', [PhanQuyenController::class, 'updateRole'])->name('phanquyen.updateRole');

    Route::get('/reviews', [ReviewController::class, 'adminIndex'])->name('reviews.index');
    Route::post('/reviews/{review}/reply', [ReviewController::class, 'reply'])->name('reviews.reply');
    Route::post('/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{review}/hide',    [ReviewController::class, 'hide'])->name('reviews.hide');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/van-chuyen', [VanChuyenController::class, 'index'])->name('vanchuyen.index');
    Route::patch('/van-chuyen/{order}/status', [VanChuyenController::class, 'updateStatus'])->name('vanchuyen.updateStatus');

    Route::get('/don-hang', [DonHangController::class, 'index'])->name('orders.index');
    Route::patch('/don-hang/{order}/status', [DonHangController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/don-hang/bulk-update', [DonHangController::class, 'bulkUpdate'])->name('orders.bulkUpdate');
    Route::get('/don-hang/{order}', [DonHangController::class, 'show'])->name('orders.show'); // nếu dùng

    Route::get('/khach-hang', [CustomerAdminController::class, 'index'])->name('QL_khachhang.index');
    Route::delete('/khach-hang/{user}', [CustomerAdminController::class, 'destroy'])
        ->name('QL_khachhang.destroy');

    Route::get('/ton-kho', [TonKhoController::class, 'index'])->name('tonkho.index');
    Route::patch('/ton-kho/{product}/adjust', [TonKhoController::class, 'adjust'])->name('tonkho.adjust');
    Route::patch('/ton-kho/set-qty', [TonKhoController::class, 'setQty'])->name('tonkho.setQty');
    Route::get('/ton-kho/export', [TonKhoController::class, 'export'])->name('tonkho.export');
    Route::post('/ton-kho/bulk-adjust', [TonKhoController::class, 'bulkAdjust'])->name('tonkho.bulkAdjust');

    Route::get('/thong-ke', [ThongKeController::class, 'index'])->name('thongke.index');
});

/*
|--------------------------------------------------------------------------
| Nhân viên
|--------------------------------------------------------------------------
*/
Route::prefix('nhanvien')->name('nhanvien.')->middleware('auth')->group(function () {

    // Route::get('/dashboard', fn() => view('nhanvien.dashboard'))->name('dashboard');
    Route::get('/dashboard', [NhanvienDashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('/danh-sach-san-pham', [NhanVienProductController::class, 'index'])
        ->name('danhsachsanpham');

    Route::get('/san-pham/them', [NhanVienProductController::class, 'create'])
        ->name('sanpham.them');

    Route::post('/products', [NhanVienProductController::class, 'store'])
        ->name('products.store');

    Route::get('/san-pham/{id}/edit', [NhanVienProductController::class, 'edit'])
        ->whereNumber('id')
        ->name('sanpham.edit');

    Route::put('/san-pham/{id}', [NhanVienProductController::class, 'update'])
        ->whereNumber('id')
        ->name('sanpham.update');

    Route::delete('/san-pham/{id}', [NhanVienProductController::class, 'destroy'])
        ->whereNumber('id')
        ->name('sanpham.destroy');

    Route::get('/thuong-hieu', [BrandFrontController::class, 'index'])
        ->name('brands.index');
    Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
    Route::put('/brands/{brand}', [BrandFrontController::class, 'update'])->name('brands.update');
    Route::delete('/brands/{brand}', [BrandFrontController::class, 'destroy'])->name('brands.destroy');
    // Danh mục
    Route::get('/danh-muc', [CategoryController::class, 'index'])
        ->name('categories.index');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');   // NEW
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy'); // NEW
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
});
