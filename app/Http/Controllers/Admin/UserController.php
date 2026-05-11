<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Hiển thị danh sách tài khoản
     */
    public function index()
    {
        $users = User::paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Hiển thị form chỉnh sửa tài khoản
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Cập nhật role cho tài khoản
     */
    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,customer'
        ]);

        // Không cho phép xóa tất cả admin
        $adminCount = User::where('role', 'admin')->count();
        if ($user->role === 'admin' && $validated['role'] === 'customer' && $adminCount === 1) {
            return redirect()->back()->with('error', 'Không thể hạ cấp admin cuối cùng! Phải có ít nhất một tài khoản admin.');
        }

        $user->update(['role' => $validated['role']]);

        $actionText = $validated['role'] === 'admin' ? 'nâng cấp lên Admin' : 'hạ cấp xuống Customer';
        return redirect()->back()->with('success', "Đã {$actionText} tài khoản '{$user->name}' thành công!");
    }

    /**
     * Xóa tài khoản
     */
    public function destroy(User $user)
    {
        // Không cho phép xóa tài khoản admin nếu chỉ có 1 admin
        if ($user->role === 'admin' && User::where('role', 'admin')->count() === 1) {
            return redirect()->back()->with('error', 'Không thể xóa admin cuối cùng!');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->back()->with('success', "Đã xóa tài khoản '{$userName}' thành công!");
    }
}
