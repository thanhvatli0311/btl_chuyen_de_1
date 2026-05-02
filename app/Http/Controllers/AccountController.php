<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    /**
     * Hiển thị form chỉnh sửa tài khoản.
     */
    public function edit()
    {
        return view('account.edit', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Cập nhật thông tin cá nhân (tên, email).
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($validated);

        return redirect()->route('account.edit')->with('success', 'Thông tin tài khoản đã được cập nhật!');
    }

    /**
     * Cập nhật mật khẩu người dùng.
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        // Sử dụng `validateWithBag` để tách biệt lỗi của form này
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('account.edit')->with('success', 'Mật khẩu đã được thay đổi thành công!');
    }
}