# 🏷️ Hệ Thống Mã Giảm Giá (Discount Code System)

## 📋 Tổng Quan

Watch Store có hệ thống mã giảm giá hoàn chỉnh cho phép admin tạo, quản lý các mã giảm giá và khách hàng áp dụng trong giỏ hàng trước khi thanh toán.

### ✨ Tính Năng Chính

#### 👥 Cho Khách Hàng
- ✅ Nhập mã giảm giá trong giỏ hàng
- ✅ Xem ngay số tiền được giảm
- ✅ Áp dụng mã và thanh toán với giá khuyến mãi
- ✅ Kiểm tra lỗi mã (hết hạn, hết lượt, tối thiểu không đạt...)

#### 👨‍💼 Cho Admin
- ✅ Xem danh sách tất cả mã giảm giá
- ✅ Tạo mã mới (% hoặc số tiền cố định)
- ✅ Chỉnh sửa thông tin mã
- ✅ Kích hoạt/vô hiệu hóa mã
- ✅ Xóa mã giảm giá
- ✅ Xem số lần đã sử dụng

#### 🔧 Tính Năng Kỹ Thuật
- Xác thực bên server an toàn (không thể bypass từ client)
- Giới hạn số lần sử dụng (usage_limit)
- Ngày hiệu lực (valid_from - valid_until)
- Tối thiểu đơn hàng (minimum_order_amount)
- Hai loại giảm giá: Phần trăm (%) hoặc số tiền cố định (₫)

---

## 🚀 Khởi Động Nhanh

### 1️⃣ Database Setup
Chạy migration để tạo bảng discount_codes:
```bash
php artisan migrate
```

### 2️⃣ Tạo Dữ Liệu Mẫu (Tùy Chọn)
```bash
php artisan db:seed --class=DiscountCodeSeeder
```

### 3️⃣ Chạy Server
```bash
php artisan serve
```

---

## 🧪 Hướng Dẫn Sử Dụng

### Cho Khách Hàng: Áp Dụng Mã

1. **Thêm sản phẩm vào giỏ hàng**
   - Xem sản phẩm → Nhấp "Thêm vào giỏ" (cần đăng nhập)
   
2. **Vào giỏ hàng**
   - Truy cập: http://127.0.0.1:8000/cart
   
3. **Nhập mã giảm giá**
   - Tìm ô nhập "Mã giảm giá" trong giỏ hàng
   - Nhập mã (ví dụ: SAVE10)
   
4. **Nhấp "Áp Dụng"**
   - Hệ thống sẽ kiểm tra mã (bên server)
   - Hiển thị số tiền được giảm
   - Cập nhật tổng tiền
   
5. **Checkout**
   - Tiếp tục thanh toán với giá đã giảm

### Mã Giảm Giá Ví Dụ

| Mã | Loại | Giá Trị | Tối Thiểu | Hạn |
|---|---|---|---|---|
| SAVE10 | Giảm % | 10% | 0₫ | Không hạn |
| SAVE20 | Giảm % | 20% | 1,000,000₫ | Không hạn |
| NEWYEAR500K | Giảm ₫ | 500,000₫ | 2,000,000₫ | Không hạn |
| SUMMER15 | Giảm % | 15% | 500,000₫ | Không hạn |

---

### Cho Admin: Quản Lý Mã

#### Truy Cập
- URL: http://127.0.0.1:8000/admin/discount-codes
- Yêu cầu: Đăng nhập với tài khoản admin

#### Các Chức Năng

**1. Xem Danh Sách Mã**
- Truy cập `/admin/discount-codes`
- Xem tất cả mã, trạng thái, số lần sử dụng

**2. Tạo Mã Mới**
- Nhấp nút "➕ Tạo Mã Mới"
- Nhập thông tin:
  - **Mã**: BLACKFRIDAY50 (không có ký tự đặc biệt)
  - **Mô Tả**: (tuỳ chọn) Ví dụ: "Black Friday - Giảm 50%"
  - **Loại**: Chọn "percentage" (%) hoặc "fixed_amount" (₫)
  - **Giá Trị**: 50 (nếu %, là 50%; nếu ₫, là 50,000₫)
  - **Tối Thiểu**: 1,000,000 (đơn hàng phải từ số tiền này)
  - **Giới Hạn Sử Dụng**: 100 (null = vô hạn)
  - **Từ**: Ngày bắt đầu
  - **Đến**: Ngày hết hạn
  - **Kích Hoạt**: Tích chọn để bật

**3. Chỉnh Sửa Mã**
- Nhấp vào mã cần sửa
- Cập nhật thông tin
- Lưu thay đổi

**4. Kích Hoạt/Vô Hiệu Hóa**
- Click nút toggle bên cạnh mã
- Trạng thái sẽ thay đổi ngay

**5. Xóa Mã**
- Nhấp nút "🗑️ Xóa" trên danh sách
- Xác nhận xóa

---

## 📊 Cấu Trúc Database

### Bảng: discount_codes

| Cột | Kiểu | Mô Tả |
|-----|------|-------|
| id | INT | ID duy nhất |
| code | VARCHAR | Mã giảm giá (VD: SAVE10) |
| description | TEXT | Mô tả mã |
| type | ENUM | percentage hoặc fixed_amount |
| value | DECIMAL | Giá trị (%) hoặc số tiền (₫) |
| minimum_order_amount | DECIMAL | Tối thiểu đơn hàng |
| usage_limit | INT | Giới hạn sử dụng (null = vô hạn) |
| usage_count | INT | Số lần đã sử dụng |
| valid_from | TIMESTAMP | Ngày bắt đầu |
| valid_until | TIMESTAMP | Ngày hết hạn |
| is_active | BOOLEAN | Trạng thái kích hoạt |
| created_at | TIMESTAMP | Lúc tạo |
| updated_at | TIMESTAMP | Lúc cập nhật |

---

## 📡 API Endpoint

### POST /api/discount/verify
Kiểm tra mã giảm giá từ frontend

**Request:**
```json
{
  "code": "SAVE10",
  "total": 2000000
}
```

**Response Thành Công:**
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

**Response Lỗi - Mã Không Tồn Tại:**
```json
{
  "success": false,
  "message": "❌ Mã giảm giá không tồn tại"
}
```

**Response Lỗi - Mã Hết Hạn/Vô Hiệu:**
```json
{
  "success": false,
  "message": "❌ Mã giảm giá đã bị vô hiệu hóa"
}
```

**Response Lỗi - Tối Thiểu Không Đạt:**
```json
{
  "success": false,
  "message": "❌ Mã giảm giá cần tối thiểu 1,000,000₫"
}
```

---

## 🔍 Quy Trình Kiểm Tra Mã

Khi khách hàng áp dụng mã, hệ thống sẽ kiểm tra theo thứ tự:

1. ✅ **Mã có tồn tại không?** → Nếu không → Lỗi
2. ✅ **Mã có được kích hoạt không?** → Nếu không → Lỗi
3. ✅ **Mã còn hợp lệ không?** (Kiểm tra ngày, lượt sử dụng) → Nếu không → Lỗi
4. ✅ **Tổng đơn có đạt tối thiểu không?** → Nếu không → Lỗi
5. ✅ **Tính toán giảm giá** → Trả về kết quả

---

## 📁 Cấu Trúc File Code

### Controllers
```
app/Http/Controllers/
├── DiscountController.php
│   └── verify()         # API kiểm tra mã
│
└── Admin/
    └── DiscountCodeController.php
        ├── index()      # Danh sách mã
        ├── create()     # Form tạo mã
        ├── store()      # Lưu mã mới
        ├── edit()       # Form sửa mã
        ├── update()     # Cập nhật mã
        ├── destroy()    # Xóa mã
        └── toggle()     # Kích hoạt/vô hiệu
```

### Models
```
app/Models/
└── DiscountCode.php
    ├── isValid()              # Kiểm tra hợp lệ
    ├── meetsMinimumAmount()   # Kiểm tra tối thiểu
    └── calculateDiscount()    # Tính tiền giảm
```

### Routes
```
POST   /api/discount/verify           # Verify mã (API)
GET    /admin/discount-codes          # Danh sách
GET    /admin/discount-codes/create   # Form tạo
POST   /admin/discount-codes          # Lưu mã mới
GET    /admin/discount-codes/{id}/edit # Form sửa
PUT    /admin/discount-codes/{id}     # Cập nhật mã
DELETE /admin/discount-codes/{id}     # Xóa mã
PATCH  /admin/discount-codes/{id}/toggle # Kích hoạt
```

---

## ✅ Kiểm Tra Chức Năng

| Tính Năng | Cách Test |
|-----------|-----------|
| 📝 Tạo mã mới | Admin → `/admin/discount-codes` → Tạo mã |
| 📋 Xem danh sách | Admin → `/admin/discount-codes` |
| ✏️ Sửa mã | Admin → Chọn mã → Edit |
| 🔄 Kích hoạt | Admin → Click toggle |
| 🗑️ Xóa mã | Admin → Click xóa → Xác nhận |
| 💳 Áp dụng mã | Customer → Giỏ hàng → Nhập mã → Áp dụng |
| ✅ Giảm giá đúng | Kiểm tra số tiền trong response API |
| ❌ Lỗi khi hết hạn | Áp dụng mã đã hết hiệu lực |
| ❌ Lỗi tối thiểu | Áp dụng mã với tổng < tối thiểu |
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
