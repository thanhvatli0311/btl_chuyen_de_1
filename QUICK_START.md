# ⚡ QUICK START - Khởi Động Nhanh

## 🚀 Chạy Web Trong 5 Phút

### 1️⃣ Terminal / PowerShell

```bash
cd f:\xampp\htdocs\watch_store-main
```

### 2️⃣ Cài Dependencies

```bash
composer install
```

### 3️⃣ Cấu Hình .env

Mở file `f:\xampp\htdocs\watch_store-main\.env`  
Tìm và sửa:

```env
DB_HOST=127.0.0.1
DB_DATABASE=watch_store
DB_USERNAME=root
DB_PASSWORD=

# Nếu MySQL có mật khẩu:
# DB_PASSWORD=your_password
```

### 4️⃣ Generate Key

```bash
php artisan key:generate
```

### 5️⃣ Tạo Database

1. **Mở phpMyAdmin**: `http://localhost/phpmyadmin`
2. Đăng nhập (mặc định: `root` / không có mật khẩu)
3. Click **"New"** hoặc **"Tạo cơ sở dữ liệu"**
4. Nhập tên: `watch_store`
5. Chọn collation: `utf8mb4_unicode_ci`
6. Click **"Create"** / **"Tạo"**

### 6️⃣ Chạy Migrations

```bash
php artisan migrate
```

**Output thành công:**
```
Migration table created successfully.
users ................................. DONE
password_resets ........................ DONE
failed_jobs ........................... DONE
personal_access_tokens ................. DONE
categories ............................ DONE
products .............................. DONE
orders ............................... DONE
order_items .......................... DONE
```

### 7️⃣ Chạy Server

```bash
php artisan serve
```

**Output:**
```
Laravel development server started: http://127.0.0.1:8000
```

### 8️⃣ Truy Cập Website

```
http://127.0.0.1:8000
```

---

## ✅ Kiểm Tra Chức Năng

| Tính năng | URL | Cách thử |
|-----------|-----|---------|
| 🏠 Trang chủ | `/` | Xem sản phẩm, không cần đăng nhập |
| 💡 Sản phẩm | `/product/1` | Click "Xem chi tiết" trên trang chủ |
| 🔓 Đăng ký | `/register` | Điền thông tin, click "Đăng ký" |
| 🔐 Đăng nhập | `/login` | Email + password (sau khi đăng ký) |
| 🛒 Thêm vào giỏ | `/cart` | Click "Thêm vào giỏ" (cần đăng nhập) |
| 🛍️ Giỏ hàng | `/cart` | Xem sản phẩm, cập nhật số lượng |
| 💳 Thanh toán | `/cart` | Click "Tiến hành thanh toán", điền info |
| ✅ Thành công | `/order/success/1` | Sau khi hoàn thành checkout |
| 📋 Đơn hàng | `/orders` | Xem tất cả đơn hàng của mình |
| ⚙️ Admin | `/admin/products` | Quản lý sản phẩm |

---

## 🧪 Tạo Tài Khoản Test

### Tùy chọn 1: Tạo thủ công

1. Truy cập: `http://127.0.0.1:8000/register`
2. Điền:
   - **Họ và tên**: John Doe
   - **Email**: john@example.com
   - **Mật khẩu**: password12345
   - **Xác nhận mật khẩu**: password12345
3. Click "Đăng ký"

### Tùy chọn 2: Dùng Tinker (PHP interactive shell)

```bash
php artisan tinker

# Copy & paste:
App\Models\User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password123')
]);

exit
```

---

## 🎁 Tạo Sản Phẩm Test

```bash
php artisan tinker
```

Chạy:
```php
// Tạo category
$cat = App\Models\Category::create(['name' => 'Đồng hồ nam']);

// Tạo sản phẩm
App\Models\Product::create([
    'name' => 'Rolex Submariner',
    'price' => 5000000,
    'quantity' => 10,
    'description' => 'Đồng hồ sang trọng',
    'image' => 'watch1.jpg',
    'category_id' => $cat->id
]);

exit
```

---

## ❌ Gặp Lỗi?

### "SQLSTATE[HY000]: General error"
```bash
php artisan migrate:fresh
```
⚠️ **Cảnh báo**: Xóa tất cả dữ liệu!

### "No application encryption key"
```bash
php artisan key:generate
```

### "Class not found"
```bash
composer dump-autoload
```

### "Connection refused"
1. MySQL có chạy không?
2. Thông tin DB đúng không?
3. Database tồn tại chưa?

---

## 📁 Tải Ảnh Sản Phẩm

1. Lưu ảnh vào: `public/images/`
2. Tên ảnh: `watch1.jpg`, `watch2.jpg`, ...
3. Cập nhật đường dẫn trong database

**Ví dụ:**
- `public/images/watch1.jpg`
- `public/images/watch2.jpg`
- `public/images/watch3.jpg`

---

## 🔧 Các Lệnh Hữu Ích

```bash
# Xem tất cả routes
php artisan route:list

# Tạo model mới
php artisan make:model ModelName -m

# Tạo controller mới
php artisan make:controller ControllerName

# Tạo migration mới
php artisan make:migration create_table_name

# Clear cache
php artisan config:cache
php artisan view:cache
php artisan cache:clear

# Xem logs
tail -f storage/logs/laravel.log
```

---

## 📞 Support

- **Logs**: `storage/logs/laravel.log`
- **Database**: phpMyAdmin `http://localhost/phpmyadmin`
- **Routes**: `php artisan route:list`
- **Config**: `.env` file

---

**Chúc mừng! ✨ Bạn đã sẵn sàng.**
