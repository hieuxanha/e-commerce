<?php


// app/Http/Controllers/ProfileController.php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        // View hiển thị thông tin tài khoản (sidebar: Thông tin cá nhân/Đổi mật khẩu/Lịch sử…)
        return view('layouts.profile', compact('user'));
    }
}
