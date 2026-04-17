# 🎯 HƯỚNG DẪN HOÀN CHỈNH - WEB BÁN ĐỒNG HỒ LARAVEL

## 📋 BƯỚC CHUẨN BỊ TRƯỚC

### 1️⃣ Yêu cầu hệ thống:
- **PHP**: >= 8.1
- **MySQL/MariaDB**: 5.7 trở lên
- **Composer**: Công cụ quản lý package PHP
- **XAMPP/WAMP/LAMP**: Máy chủ localhost

### 2️⃣ Cài đặt nếu chưa có:

#### Windows:
```bash
# Tải XAMPP từ https://www.apachefriends.org/
# Cài đặt vào C:\xampp

# Khởi động XAMPP Control Panel
# Start Apache và MySQL
```

---

## 🚀 BƯỚC CHẠY PROJECT

### Bước 1: Mở Terminal/PowerShell

```bash
# Đi tới thư mục project của bạn
cd f:\xampp\htdocs\watch_store-main

# Hoặc trên Linux/Mac:
cd /var/www/watch_store-main
```

### Bước 2: Cài đặt Dependencies

```bash
# Cài đặt PHP dependencies
composer install

# Cài đặt JavaScript dependencies (nếu cần)
npm install
```

### Bước 3: Cấu hình .env

```bash
# Copy file .env
cp .env.example .env

# Hoặc Windows PowerShell:
Copy-Item .env.example .env
```

**Chỉnh sửa file `.f:\xampp\htdocs\watch_store-main\.env`:**

```env
APP_NAME="Watch Store"
APP_ENV=local
APP_DEBUG=true
APP_KEY=
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=watch_store
DB_USERNAME=root
DB_PASSWORD=

# Nếu bạn đặt mật khẩu cho MySQL, thêm nó vào
# DB_PASSWORD=your_password
```

### Bước 4: Tạo Application Key

```bash
php artisan key:generate
```

### Bước 5: Tạo Database

**Cách 1: Dùng phpMyAdmin (Gợi ý - Dễ nhất)**

1. Mở http://localhost/phpmyadmin
2. Đăng nhập (mặc định: root, không có mật khẩu)
3. Click "Nieuwe database"
4. Nhập tên: `watch_store`
5. Click "Aanmaken"

**Cách 2: Dùng MySQL Command Line**

```bash
# Mở MySQL Command Line Client
mysql -u root -p

# Nhập mật khẩu (nếu có, nếu không thì bỏ qua)
# Sau đó chạy:
CREATE DATABASE watch_store;
EXIT;
```

### Bước 6: Chạy Migrations (Tạo bảng)

```bash
php artisan migrate
```

**Output sẽ tương tự:**
```
  users ................................................... DONE    0.06s
  password_resets ........................................... DONE    0.00s
  failed_jobs ............................................. DONE    0.00s
  personal_access_tokens .................................. DONE    0.01s
  categories ............................................. DONE    0.01s
  products .............................................. DONE    0.01s
  orders ................................................ DONE    0.01s
  order_items .......................................... DONE    0.01s
```

### Bước 7: Tạo dữ liệu mẫu (Optional - Gợi ý)

```bash
# Tạo một số category và product mẫu
php artisan tinker
```

Sau đó nhập các lệnh sau vào:

```php
// Tạo 3 category
App\Models\Category::create(['name' => 'Đồng hồ nam', 'description' => 'Đồng hồ dành cho nam']);
App\Models\Category::create(['name' => 'Đồng hồ nữ', 'description' => 'Đồng hồ dành cho nữ']);
App\Models\Category::create(['name' => 'Đồng hồ thể thao', 'description' => 'Đồng hồ thể thao chuyên dụng']);

// Tạo 4 sản phẩm mẫu
App\Models\Product::create([
    'name' => 'Rolex Submariner',
    'price' => 5000000,
    'quantity' => 10,
    'description' => 'Đồng hồ cao cấp Rolex Submariner',
    'image' => 'watch1.jpg',
    'category_id' => 1
]);

App\Models\Product::create([
    'name' => 'Omega Seamaster',
    'price' => 4500000,
    'quantity' => 15,
    'description' => 'Đồng hồ Omega Seamaster sang trọng',
    'image' => 'watch2.jpg',
    'category_id' => 1
]);

App\Models\Product::create([
    'name' => 'Cartier Ballon Bleu',
    'price' => 3800000,
    'quantity' => 8,
    'description' => 'Đồng hồ nữ Cartier Ballon Bleu',
    'image' => 'watch3.jpg',
    'category_id' => 2
]);

App\Models\Product::create([
    'name' => 'Apple Watch Series 9',
    'price' => 2000000,
    'quantity' => 20,
    'description' => 'Smartwatch thể thao Apple Watch',
    'image' => 'watch4.jpg',
    'category_id' => 3
]);

exit
```

### Bước 8: Thêm hình ảnh sản phẩm (Optional)

1. Tải ảnh và lưu vào: `public/images/`
2. Đặt tên: `watch1.jpg`, `watch2.jpg`, v.v.

### Bước 9: Chạy Server Laravel

```bash
# Cách 1: Dùng PHP artisan serve
php artisan serve

# Output: Server is running at http://127.0.0.1:8000
```

**Hoặc Cách 2: Dùng Apache (XAMPP)**

1. Đặt project vào: `C:\xampp\htdocs\watch_store-main`
2. Mở file `httpd-vhosts.conf`:
   ```
   C:\xampp\apache\conf\extra\httpd-vhosts.conf
   ```
3. Thêm vào cuối file:
   ```apache
   <VirtualHost *:80>
       ServerName watch-store.local
       DocumentRoot "C:\xampp\htdocs\watch_store-main\public"
       <Directory "C:\xampp\htdocs\watch_store-main\public">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```
4. Mở file `C:\Windows\System32\drivers\etc\hosts`
5. Thêm dòng: `127.0.0.1  watch-store.local`
6. Restart Apache

---

## 🌐 TRUY CẬP WEBSITE

### Nếu dùng `php artisan serve`:
```
http://127.0.0.1:8000
```

### Nếu dùng Apache (XAMPP):
```
http://watch-store.local
hoặc
http://localhost/watch_store-main/public
```

---

## 🧪 KIỂM TRA CHỨC NĂNG

### 1️⃣ Trang chủ (Công khai)
```
✅ Có thể xem sản phẩm mà không đăng nhập
✅ Nút "Xem chi tiết" hoạt động
❌ Nút "Thêm vào giỏ" sẽ yêu cầu đăng nhập
```

### 2️⃣ Đăng ký tài khoản
```
http://127.0.0.1:8000/register

✅ Nhập thông tin đăng ký
✅ Click "Đăng ký"
✅ Tự động đăng nhập sau đó
```

### 3️⃣ Đăng nhập
```
http://127.0.0.1:8000/login

✅ Nhập email và mật khẩu
✅ Click "Đăng nhập"
✅ Được chuyển hướng về trang chủ
```

### 4️⃣ Thêm vào giỏ hàng
```
✅ Click "Thêm vào giỏ" (sau khi đăng nhập)
✅ Được chuyển hướng tới /cart
✅ Giỏ hiển thị sản phẩm đã thêm
```

### 5️⃣ Giỏ hàng
```
http://127.0.0.1:8000/cart

✅ Thấy danh sách sản phẩm
✅ Cập nhật số lượng
✅ Xóa sản phẩm
✅ Xem tổng tiền
✅ Click "Tiến hành thanh toán"
```

### 6️⃣ Checkout
```
✅ Modal hiện lên yêu cầu thông tin giao hàng
✅ Nhập số điện thoại, địa chỉ
✅ Click "Xác nhận đặt hàng"
✅ Chuyển tới trang thành công
```

### 7️⃣ Đơn hàng của tôi
```
http://127.0.0.1:8000/orders

✅ Xem danh sách tất cả đơn hàng
✅ Click "Xem chi tiết" để xem chi tiết đơn hàng
✅ Hiển thị sản phẩm, giá, và thông tin giao hàng
```

---

## 🔧 TROUBLESHOOTING (SỬA LỖI)

### Lỗi: "Class 'AuthController' not found"
```bash
# Khắc phục: Chạy lại
composer dump-autoload
```

### Lỗi: "SQLSTATE[HY000]: General error: 1030 Got error"
```bash
# Khắc phục: Xóa migrations cũ và chạy lại
php artisan migrate:fresh
```

### Lỗi: "No application encryption key has been specified"
```bash
# Khắc phục: 
php artisan key:generate
```

### Lỗi: "Database connection failed"
```bash
# Kiểm tra:
1. MySQL đang chạy?
2. Thông tin DB trong .env đúng không?
3. Database "watch_store" đã tạo chưa?
```

---

## 📝 CƠ SỞ DỮ LIỆU - BẢNG MỘT CÁCH DỄ HIỂU

```
┌─────────────┐
│    users    │  (Người dùng)
├─────────────┤
│ id          │
│ name        │
│ email       │
│ password    │
│ created_at  │
└─────────────┘
        │
        │ (1 user có nhiều orders)
        │
        ▼
┌──────────────┐
│    orders    │  (Đơn hàng)
├──────────────┤
│ id           │
│ user_id      │◄─────── Liên kết với user
│ total_price  │
│ status       │
│ phone        │
│ address      │
│ created_at   │
└──────────────┘
        │
        │ (1 order có nhiều order_items)
        │
        ▼
┌────────────────┐
│  order_items   │  (Chi tiết đơn hàng)
├────────────────┤
│ id             │
│ order_id       │◄─────── Liên kết với order
│ product_id     │◄─────── Liên kết với product
│ quantity       │
│ price          │
└────────────────┘
        │
        └─────────────────┐
                          │
┌──────────────┐          │
│   products   │◄─────────┘
├──────────────┤
│ id           │
│ name         │
│ price        │
│ quantity     │
│ description  │
│ image        │
│ category_id  │◄─────── Liên kết với category
└──────────────┘
        │
        │ (1 category có nhiều products)
        │
        ▼
┌──────────────┐
│  categories  │  (Danh mục)
├──────────────┤
│ id           │
│ name         │
│ description  │
│ created_at   │
└──────────────┘
```

---

## 🎉 HOÀN TẤT!

Chúc mừng! Bạn đã hoàn thành xây dựng một web bán hàng hoàn chỉnh với:

✅ Trang chủ công khai (không cần đăng nhập)  
✅ Đăng ký & đăng nhập  
✅ Giỏ hàng (bắt buộc đăng nhập)  
✅ Thanh toán & tạo đơn hàng  
✅ Quản lý đơn hàng cá nhân  
✅ Quản lý sản phẩm (admin)  

---

## 💡 MẸO NÂNG CAO

### 1. Thêm các tính năng khác:

```bash
# Gửi email khi có đơn hàng mới
# Tích hợp thanh toán VNPay/Stripe
# Thêm review/rating sản phẩm
# Thêm wishlist
# Tìm kiếm sản phẩm
```

### 2. Bảo mật:

```php
// Thêm CSRF protection
// Validate input
// Hash password
// Use environment variables
```

### 3. Tối ưu hóa:

```bash
# Caching
php artisan config:cache

# Database indexing
# Image optimization
# Pagination
```

---

## 📞 HỖ TRỢ

Nếu gặp vấn đề, hãy kiểm tra:

1. **Log files**: `storage/logs/laravel.log`
2. **Database**: Kiểm tra bảng trong phpMyAdmin
3. **Routes**: `php artisan route:list`
4. **Models**: Kiểm tra relationships

---

**Tác giả**: Watch Store  
**Ngôn ngữ**: Laravel 10+, PHP 8.1+  
**Database**: MySQL 5.7+
