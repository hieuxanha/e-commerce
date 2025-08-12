<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'nullable|string|max:255',
            'phone' => 'required|unique:users,phone',
            'gender' => ['nullable', Rule::in(['nam', 'nu'])],
            'day' => 'nullable|integer|min:1|max:31',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer|min:1900|max:' . now()->year,
            'password' => 'required|min:6|confirmed',
            'address' => 'required|string|max:255',
        ]);

        $dob = null;
        if ($request->day && $request->month && $request->year) {
            $dob = "{$request->year}-{$request->month}-{$request->day}";
        }

        User::create([
            'email' => $request->email,
            'name' => $request->name,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'dob' => $dob,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'role' => 'khach_hang',
        ]);

        return redirect()->route('login')->with('success', 'Đăng ký thành công!');
    }
}
