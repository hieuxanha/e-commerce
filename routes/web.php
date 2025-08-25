<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;

// Khách (frontend)
use App\Http\Controllers\ProductController as FrontProductController;
use App\Http\Controllers\CartDetailController; // <— thêm dòng này

// Nhân viên / Admin
use App\Http\Controllers\NhanVien\ProductController as NhanVienProductController;
use App\Http\Controllers\NhanVien\BrandController;
use App\Http\Controllers\NhanVien\CategoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\NhanVien\BrandFrontController;



/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/home');

Route::get('/home', [HomeController::class, 'index'])->name('home');

/** Cart AJAX */
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart/mini', [CartController::class, 'mini'])->name('cart.mini');
Route::patch('/cart/{product}/qty', [CartController::class, 'updateQuantity'])->name('cart.qty'); // ✅ bổ sung

/** Cart pages */
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');     // (nếu có)
Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout'); // (nếu có)
Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');



// Trang chi tiết giỏ hàng
Route::get('/cart',        [CartDetailController::class, 'index'])->name('cart.index');
Route::get('/checkout',    [CartDetailController::class, 'checkout'])->name('cart.checkout');
Route::post('/cart/update', [CartDetailController::class, 'updateQuantityAjax'])->name('cart.update');
Route::post('/cart/remove-by-product', [CartDetailController::class, 'removeByProduct'])->name('cart.remove.product');
Route::post('/cart/clear', [CartDetailController::class, 'clear'])->name('cart.clear');

Route::post('/reviews', [ReviewController::class, 'store'])
    ->name('reviews.store')
    ->middleware('auth');

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

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

    Route::get('/reviews', [ReviewController::class, 'adminIndex'])->name('reviews.index');
    Route::post('/reviews/{review}/reply', [ReviewController::class, 'reply'])->name('reviews.reply');
    Route::post('/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{review}/hide',    [ReviewController::class, 'hide'])->name('reviews.hide');

    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

/*
|--------------------------------------------------------------------------
| Nhân viên
|--------------------------------------------------------------------------
*/
Route::prefix('nhanvien')->name('nhanvien.')->middleware('auth')->group(function () {

    Route::get('/dashboard', fn() => view('nhanvien.dashboard'))->name('dashboard');

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
    Route::put('/brands/{brand}', [BrandFrontController::class, 'update'])->name('brands.update');
    Route::delete('/brands/{brand}', [BrandFrontController::class, 'destroy'])->name('brands.destroy');
    // Danh mục
    Route::get('/danh-muc', [CategoryController::class, 'index'])
        ->name('categories.index');



    Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
});
