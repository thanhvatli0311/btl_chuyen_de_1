# 🏬 WATCH STORE - Web Bán Đồng Hồ Cao Cấp

Một ứng dụng web bán đồng hồ hoàn chỉnh được xây dựng bằng **Laravel 10** với các tính năng hiện đại.

## ✨ Tính Năng Chính

### 👥 Quản Lý Người Dùng
- ✅ Đăng ký tài khoản mới
- ✅ Đăng nhập / Đăng xuất
- ✅ Quáng lý hồ sơ cá nhân

### 🛍️ Mua Sắm
- ✅ Duyệt sản phẩm **không cần đăng nhập**
- ✅ Xem chi tiết sản phẩm
- ✅ Thêm vào giỏ hàng **(bắt buộc đăng nhập)**
- ✅ Cập nhật số lượng sản phẩm trong giỏ
- ✅ Xóa sản phẩm khỏi giỏ

### 💳 Thanh Toán
- ✅ Giỏ hàng với tính năng tính tổng tiền
- ✅ Form thanh toán với thông tin giao hàng
- ✅ Tạo đơn hàng
- ✅ Xác nhận đơn hàng thành công

### 📋 Quản Lý Đơn Hàng
- ✅ Xem danh sách đơn hàng cá nhân
- ✅ Xem chi tiết từng đơn hàng
- ✅ Theo dõi trạng thái đơn hàng

### ⚙️ Quản Lý (Admin)
- ✅ Quản lý danh sách sản phẩm
- ✅ Thêm sản phẩm mới
- ✅ Tải ảnh sản phẩm

## 🛠️ Công Nghệ Sử Dụng

| Công Nghệ | Phiên Bản |
|-----------|----------|
| **Laravel** | 10.x |
| **PHP** | 8.1+ |
| **MySQL** | 5.7+ |
| **Bootstrap** | 5.3.0 |
| **Blade** | Laravel Templating |

## 📦 Cài Đặt

### Yêu Cầu Hệ Thống
- PHP >= 8.1
- MySQL/MariaDB
- Composer
- XAMPP/WAMP/LAMP

### Hướng Dẫn Cài Đặt

1. **Clone/Download Project**
   ```bash
   cd f:\xampp\htdocs\watch_store-main
   ```

2. **Cài Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Cấu Hình .env**
   ```bash
   cp .env.example .env
   ```
   Chỉnh sửa:
   ```env
   DB_DATABASE=watch_store
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Tạo Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Tạo Database**
   ```bash
   # Dùng phpMyAdmin hoặc MySQL CLI
   CREATE DATABASE watch_store;
   ```

6. **Chạy Migrations**
   ```bash
   php artisan migrate
   ```

7. **Tạo Dữ Liệu Mẫu (Optional)**
   ```bash
   php artisan tinker
   ```

8. **Chạy Server**
   ```bash
   php artisan serve
   ```

9. **Truy Cập Website**
   ```
   http://127.0.0.1:8000
   ```

**Chi tiết đầy đủ xem: [SETUP_GUIDE.md](SETUP_GUIDE.md)**

## 🏗️ Cấu Trúc Thư Mục

```
watch_store-main/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php      (Xác thực)
│   │   │   ├── HomeController.php      (Trang chủ)
│   │   │   ├── ProductController.php   (Sản phẩm)
│   │   │   ├── CartController.php      (Giỏ hàng)
│   │   │   ├── OrderController.php     (Đơn hàng)
│   │   │   └── AdminController.php     (Quản lý)
│   │   └── Middleware/                 (Middleware)
│   └── Models/
│       ├── User.php
│       ├── Product.php
│       ├── Category.php
│       ├── Order.php
│       └── OrderItem.php
├── database/
│   ├── migrations/                     (Database schemas)
│   └── seeders/                        (Seed data)
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   │   ├── login.blade.php        (Đăng nhập)
│   │   │   └── register.blade.php     (Đăng ký)
│   │   ├── home.blade.php             (Trang chủ)
│   │   ├── product_detail.blade.php   (Chi tiết sản phẩm)
│   │   ├── cart.blade.php             (Giỏ hàng)
│   │   ├── order_success.blade.php    (Xác nhận đơn)
│   │   ├── my_orders.blade.php        (Đơn hàng của tôi)
│   │   └── layout.blade.php           (Layout chính)
│   └── css/
│       └── app.css
├── routes/
│   ├── web.php                        (Web routes)
│   └── api.php                        (API routes)
├── public/
│   ├── images/                        (Ảnh sản phẩm)
│   └── index.php                      (Entry point)
└── SETUP_GUIDE.md                     (Hướng dẫn cài đặt chi tiết)
```

## 📊 Sơ Đồ Database

```
┌─────────────┐         ┌──────────┐         ┌───────────────┐
│    users    │────────▶│  orders  │────────▶│  order_items  │
└─────────────┘         └──────────┘         └───────────────┘
                                                      │
                                                      │
                                                      ▼
┌──────────────┐                            ┌─────────────┐
│   products   │◀───────────────────────────│  categories │
└──────────────┘                            └─────────────┘
```

## 🔐 Bảo Mật

- ✅ Password hashing (bcrypt)
- ✅ CSRF protection
- ✅ Input validation
- ✅ Authentication middleware
- ✅ SQL injection prevention (Eloquent ORM)

## 🎨 Giao Diện

- **Framework CSS**: Bootstrap 5.3.0
- **Responsive Design**: Mobile-friendly
- **Icons**: Emoji & Unicode
- **Màu sắc**: Chủ đề đen & xám

## 🚀 Tính Năng Sắp Tới

- [ ] Tích hợp thanh toán (VNPay/Stripe)
- [ ] Gửi email xác nhận đơn hàng
- [ ] Review & Rating sản phẩm
- [ ] Wishlist (Yêu thích)
- [ ] Tìm kiếm & Filter sản phẩm
- [ ] Dashboard quản lý (Admin)
- [ ] Thống kê bán hàng
- [ ] Coupon/Discount codes

## 📝 Cách Sử Dụng

### Khách Hàng
1. **Truy cập trang chủ**: `http://localhost/`
2. **Duyệt sản phẩm**: Xem danh sách và chi tiết sản phẩm
3. **Đăng ký**: Click vào "Đăng ký" nếu chưa có tài khoản
4. **Thêm vào giỏ**: Click "Thêm vào giỏ" (cần đăng nhập)
5. **Thanh toán**: Điền thông tin giao hàng
6. **Theo dõi**: Xem danh sách đơn hàng của tôi

### Quản Trị Viên
1. **Truy cập admin**: `http://localhost/admin/products`
2. **Quản lý sản phẩm**: Xem, thêm sản phẩm
3. **Tải ảnh**: Upload ảnh cho sản phẩm

## 🐛 Troubleshooting

### Lỗi Đăng Nhập
- Kiểm tra email đã tồn tại
- Kiểm tra mật khẩu tối thiểu 8 ký tự

### Lỗi Thanh Toán
- Kiểm tra giỏ hàng không trống
- Kiểm tra thông tin giao hàng đầy đủ

### Lỗi Database
- Kiểm tra MySQL đang chạy
- Chạy lại: `php artisan migrate`

Xem: [SETUP_GUIDE.md - Troubleshooting](SETUP_GUIDE.md#-troubleshooting-sửa-lỗi)

## 📞 Hỗ Trợ & Liên Hệ

- 📧 Email: 
- 📱 Hotline: 
- 💬 Chat: 

## 📄 Giấy Phép

MIT License - Tự do sử dụng và phân phối

## ✍️ Tác Giả

Được phát triển với ❤️ bằng **Laravel**

---

**Phiên bản**: 1.0.0  
**Cập nhật lần cuối**: 2026-04-12  
**Trạng thái**: ✅ Stable
