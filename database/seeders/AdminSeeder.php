<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo tài khoản admin mặc định
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123456'),
            'role' => 'admin'
        ]);

        // Tạo tài khoản customer test
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer'
        ]);

        echo "✅ Tài khoản admin và customer đã được tạo!\n";
        echo "📧 Email: admin@example.com | Mật khẩu: admin123456\n";
        echo "📧 Email: john@example.com | Mật khẩu: password123\n";
    }
}
