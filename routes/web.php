<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;



// Cố hiinhf bị mẫ hóa

use App\Models\User;

Route::get('/fix-pass-once', function () {
    $u = User::where('email', 'b@example.com')->firstOrFail();
    $u->password = '123456'; // tự bcrypt nhờ casts
    $u->save();
    return 'OK';
});


// Trang welcome
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Đăng ký
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// Đăng nhập
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Đăng xuất
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Trang chủ sau khi đăng nhập
Route::get('/home', function () {
    return view('layouts.trangchu');
})->name('home');

// Route::get('/chi-tiet-san-pham', 'layouts.chitietsanpham')->name('sanpham.chitiet');



// Các route cho dashboard từng role (nếu có)
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard')->middleware('auth');

Route::get('/admin/dashboard', function () {
    return view('nhanvien.dashboard');
})->name('admin.dashboard')->middleware('auth');
