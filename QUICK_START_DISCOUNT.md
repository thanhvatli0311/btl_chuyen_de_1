# Hướng Dẫn Nhanh - Hệ Thống Mã Giảm Giá

## 📋 Tóm Tắt

Đã thêm hệ thống mã giảm giá (Discount Code System) hoàn chỉnh cho Watch Store bao gồm:
- ✅ API kiểm tra mã giảm giá
- ✅ Giao diện nhập mã giảm giá trong giỏ hàng
- ✅ Admin panel quản lý mã
- ✅ Database migrations
- ✅ Seeders (mã mặc định)
- ✅ Bảo mật xác thực server-side

---

## 🚀 Cài Đặt & Khởi Động

### Step 1: Chạy Migration
```bash
php artisan migrate
```

### Step 2: Seed Database (Tạo mã mặc định)
```bash
php artisan db:seed --class=DiscountCodeSeeder
```

Hoặc seed toàn bộ:
```bash
php artisan db:seed
```

### Step 3: Khởi Động Server
```bash
php artisan serve
```

---

## 🧪 Test Hệ Thống

### Mã Giảm Giá Có Sẵn
| Mã | Loại | Giá Trị | Tối Thiểu |
|---|---|---|---|
| SAVE10 | Giảm % | 10% | 0₫ |
| SAVE20 | Giảm % | 20% | 1,000,000₫ |
| NEWYEAR500K | Giảm ₫ | 500,000₫ | 2,000,000₫ |
| SUMMER15 | Giảm % | 15% | 500,000₫ |

### Hướng Dẫn Test:
1. **Truy cập giỏ hàng**: https://localhost:8000/cart
2. **Thêm sản phẩm** nếu chưa có
3. **Nhập mã giảm giá** (vd: SAVE10)
4. **Nhấp "Áp Dụng"**
5. **Kiểm tra** số tiền được giảm
6. **Checkout** với giá đã giảm

---

## 🔐 Bảo Mật

| Tính Năng | Mô Tả |
|--|--|
| Server-side validation | Xác thực lại trên server khi checkout |
| Usage limit | Kiểm tra số lần sử dụng từ database |
| Time-based | Kiểm tra ngày hết hạn |
| Minimum amount | Kiểm tra tổng đơn tối thiểu |
| Activation status | Kiểm tra mã có bị vô hiệu hóa |

---

## 👨‍💼 Admin Panel

### Truy Cập
- URL: `/admin/discount-codes`
- Yêu cầu: Đăng nhập với tài khoản admin

### Chức Năng
- ✅ Xem danh sách mã
- ✅ Tạo mã mới
- ✅ Chỉnh sửa mã
- ✅ Vô hiệu hóa/kích hoạt mã
- ✅ Xóa mã
- ✅ Xem số lần đã dùng

### Ví Dụ Tạo Mã
1. Vào `/admin/discount-codes`
2. Nhấp "➕ Tạo Mã Mới"
3. Nhập thông tin:
   - Mã: BLACKFRIDAY50
   - Loại: Giảm Phần Trăm
   - Giá Trị: 50
   - Tối Thiểu: 1,000,000
   - Giới Hạn: 100
   - Từ: Ngày hôm nay
   - Đến: 30 ngày sau
4. Nhấp "Tạo Mã"

---

## 📡 API Endpoint

### POST /api/discount/verify

**Request:**
```json
{
  "code": "SAVE10",
  "total": 2000000
}
```

**Response Success:**
```json
{
  "success": true,
  "message": "✅ Áp dụng mã giảm giá thành công!",
  "discount_amount": 200000,
  "final_total": 1800000,
  "discount_type": "percentage",
  "discount_value": 10
}
```

**Response Error:**
```json
{
  "success": false,
  "message": "❌ Mã giảm giá không tồn tại"
}
```

---

## 📁 File Cấu Trúc

### Controllers
```
app/Http/Controllers/
├── DiscountController.php          # API verify discount
└── Admin/
    └── DiscountCodeController.php  # Admin CRUD
```

### Models
```
app/Models/
└── DiscountCode.php  # Model + methods
```

### Views
```
resources/views/
├── cart.blade.php     # Discount input
└── admin/discount-codes/
    ├── index.blade.php
    ├── create.blade.php
    └── edit.blade.php
```

### Routes
```
routes/
├── api.php    # POST /api/discount/verify
└── web.php    # /admin/discount-codes/*
```

### Migrations
```
database/migrations/
├── 2026_04_12_000004_create_discount_codes_table.php
├── 2026_04_12_000005_add_minimum_order_amount_to_discount_codes_table.php
└── 2026_04_12_000010_add_discount_to_orders_table.php
```

---

## 🗄️ Database Schema

### Table: discount_codes
```sql
id                       INT PRIMARY KEY
code                     VARCHAR UNIQUE
description              TEXT
type                     ENUM('percentage', 'fixed_amount')
value                    DECIMAL(8,2)
minimum_order_amount     DECIMAL(12,2)
usage_limit              INT
usage_count              INT
valid_from               TIMESTAMP
valid_until              TIMESTAMP
is_active                BOOLEAN
created_at               TIMESTAMP
updated_at               TIMESTAMP
```

### Orders Table Updates
```sql
discount_code    VARCHAR (thêm mới)
discount_amount  DECIMAL(15,2) (thêm mới)
```

---

## ❓ Troubleshooting

### Lỗi: "Mã giảm giá không tồn tại"
**Giải pháp:**
1. Kiểm tra tên mã (chữ hoa/thường)
2. Kiểm tra mã có trong database
3. Kiểm tra mã có bị vô hiệu hóa

### Lỗi: "Mã giảm giá cần tối thiểu XXX₫"
**Giải pháp:**
1. Thêm sản phẩm để tăng tổng đơn
2. Hoặc chọn mã giảm khác

### Lỗi API 404
**Giải pháp:**
1. Kiểm tra route `/api/discount/verify` tồn tại
2. Chạy `php artisan route:list`
3. Kiểm tra CSRF token

---

## 📚 Tài Liệu Chi Tiết

- **DISCOUNT_SYSTEM.md**: Tài liệu hệ thống đầy đủ
- **CHANGELOG_DISCOUNT.md**: Danh sách thay đổi
- **Code comments**: Mỗi file đều có comments

---

## 🔄 Workflow Khách Hàng

```
1. Thêm sản phẩm vào giỏ
        ↓
2. Vào trang giỏ hàng (/cart)
        ↓
3. Nhập mã giảm giá
        ↓
4. Nhấp "Áp Dụng"
        ↓
5. [API] Server xác thực mã
        ↓
6. Hiển thị giảm giá realtime
        ↓
7. Nhấp "Tiến hành thanh toán"
        ↓
8. [Checkout Modal] 
   - Nhập thông tin giao hàng
   - Mã giảm tự động thêm vào form
   - Giá đã tính giảm
        ↓
9. [Server] Xác thực lại mã
        ↓
10. Tạo đơn hàng + lưu mã & số tiền giảm
        ↓
11. ✅ Đơn thành công!
```

---

## ✨ Features Chi Tiết

### ✅ Tính Năng Đã Thêm
1. Models & Database
   - Model DiscountCode với methods
   - Migrations tạo bảng
   - Cột discount lưu vào orders

2. Frontend
   - Input nhập mã giảm giá
   - Real-time validation via API
   - Hiển thị số tiền giảm
   - Auto-add mã vào checkout form

3. Backend
   - API endpoint verify
   - Server-side validation
   - Tính toán giảm giá an toàn
   - Increment usage count

4. Admin
   - CRUD mã giảm giá
   - Xem danh sách + pagination
   - Bật/tắt mã
   - Xem lịch sử sử dụng

---

## 🎯 Next Steps (Tuỳ Chọn)

- [ ] Thêm email notification khi hết lượt
- [ ] Thêm admin analytics chart
- [ ] Thêm QR code cho mã
- [ ] Thêm bulk import CSV
- [ ] Thêm user-specific codes
- [ ] Thêm usage history log

---

## 📞 Support

Nếu có vấn đề:
1. Kiểm tra file logs: `storage/logs/`
2. Xem database migrations: `php artisan migrate:status`
3. Clear cache: `php artisan cache:clear`

---

**Made with ❤️ for Watch Store**
