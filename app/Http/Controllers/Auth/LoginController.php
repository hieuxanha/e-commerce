<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Tìm user theo email
        $user = User::where('email', $request->email)->first();

        // Kiểm tra user tồn tại và mật khẩu đúng
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            // Điều hướng theo phân quyền
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard'); // route admin
                case 'nhan_vien':
                    return redirect()->route('nhanvien.dashboard'); // route nhân viên
                case 'khach_hang':
                default:
                    return redirect()->route('home');
            }
        }

        // Thất bại
        return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng']);
    }

    // Đăng xuất


    // Xử lý đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('home');
    }
}
