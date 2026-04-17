# Changelog - Hệ Thống Mã Giảm Giá

## Version 1.0 - 2024

### Tính Năng Mới Được Thêm

#### 1. **Model DiscountCode**
   - Được cập nhật với:
     - Method `isValid()`: Kiểm tra mã có còn hợp lệ
     - Method `meetsMinimumAmount()`: Kiểm tra tổng đơn có đạt tối thiểu
     - Method `calculateDiscount()`: Tính toán số tiền giảm
     - Method `incrementUsageCount()`: Tăng số lần sử dụng

#### 2. **API Endpoints**
   - `POST /api/discount/verify`: Kiểm tra và xác thực mã giảm giá
     - Request: `{code, total}`
     - Response: Chi tiết giảm giá hoặc lỗi

#### 3. **DiscountController**
   - Method `verify()`: Xác thực mã giảm giá qua API
   - Kiểm tra hoàn toàn trước khi trả về response

#### 4. **OrderController Updates**
   - Cập nhật `checkout()` để xử lý discount codes từ frontend
   - Xác thực lại discount trên server side
   - Lưu discount_code và discount_amount vào orders table

#### 5. **Cart Page Updates (cart.blade.php)**
   - Thêm section nhập mã giảm giá
   - JavaScript logic để gọi API verify discount
   - Hiển thị số tiền được giảm realtime
   - Lưu mã giảm giá vào checkout form

#### 6. **Database Migrations**
   - `2026_04_12_000004_create_discount_codes_table.php`: Tạo bảng discount_codes
   - `2026_04_12_000005_add_minimum_order_amount_to_discount_codes_table.php`: Thêm cột minimum_order_amount
   - `2026_04_12_000010_add_discount_to_orders_table.php`: Thêm discount_code và discount_amount vào orders

#### 7. **Admin Panel**
   - `Admin/DiscountCodeController`: Quản lý mã giảm giá
   - Views:
     - `admin/discount-codes/index.blade.php`: Danh sách mã
     - `admin/discount-codes/create.blade.php`: Form tạo mã
     - `admin/discount-codes/edit.blade.php`: Form chỉnh sửa mã
   - Routes: `/admin/discount-codes/*`

#### 8. **Seeders**
   - `DiscountCodeSeeder`: Tạo mã giảm giá mặc định:
     - SAVE10: 10% cho mọi đơn
     - SAVE20: 20% cho đơn từ 1 triệu
     - NEWYEAR500K: 500k cho đơn từ 2 triệu
     - SUMMER15: 15% cho đơn từ 500k

#### 9. **Documentation**
   - `DISCOUNT_SYSTEM.md`: Tài liệu hệ thống mã giảm giá

### File Được Tạo/Cập Nhật

#### Tạo Mới:
- `app/Http/Controllers/DiscountController.php`
- `app/Http/Controllers/Admin/DiscountCodeController.php`
- `app/Models/DiscountCode.php` (mô hình đã tồn tại, chỉ cập nhật)
- `database/migrations/2026_04_12_000005_add_minimum_order_amount_to_discount_codes_table.php`
- `database/seeders/DiscountCodeSeeder.php`
- `resources/views/admin/discount-codes/index.blade.php`
- `resources/views/admin/discount-codes/create.blade.php`
- `resources/views/admin/discount-codes/edit.blade.php`
- `DISCOUNT_SYSTEM.md`

#### Cập Nhật:
- `app/Models/DiscountCode.php`: Thêm các method
- `app/Models/Order.php`: Thêm discount_code và discount_amount vào fillable
- `app/Http/Controllers/OrderController.php`: Cập nhật checkout logic
- `resources/views/cart.blade.php`: Thêm discount code input và JavaScript
- `routes/web.php`: Thêm API route và admin routes
- `routes/api.php`: Thêm discount verify endpoint
- `database/seeders/DatabaseSeeder.php`: Gọi DiscountCodeSeeder
- `database/migrations/2026_04_12_000004_create_discount_codes_table.php`: Cập nhật (nếu có)
- `database/migrations/2026_04_12_000010_add_discount_to_orders_table.php`: Cập nhật (nếu có)

### Cách Sử Dụng

#### Cho Khách Hàng:
1. Thêm sản phẩm vào giỏ hàng
2. Vào trang giỏ hàng
3. Nhập mã giảm giá
4. Nhấp "Áp dụng"
5. Tiến hành thanh toán với giá đã giảm

#### Cho Admin:
1. Vào `/admin/discount-codes`
2. Tạo mã mới
3. Chỉnh sửa hoặc vô hiệu hóa mã

### Database

#### Bảng: discount_codes
```sql
id              INT PRIMARY KEY
code            VARCHAR UNIQUE
description     TEXT
type            ENUM('percentage', 'fixed_amount')
value           DECIMAL
minimum_order_amount DECIMAL
usage_limit     INT
usage_count     INT
valid_from      TIMESTAMP
valid_until     TIMESTAMP
is_active       BOOLEAN
created_at      TIMESTAMP
updated_at      TIMESTAMP
```

#### Cột Thêm vào orders table:
```sql
discount_code   VARCHAR
discount_amount DECIMAL
```

### Lệnh Setup

```bash
# Chạy migration
php artisan migrate

# Chạy seeder
php artisan db:seed --class=DiscountCodeSeeder

# Hoặc seed tất cả
php artisan db:seed
```

### Mã Giảm Giá Mặc Định

| Mã | Loại | Giá Trị | Tối Thiểu | Giới Hạn |
|-----|------|--------|----------|----------|
| SAVE10 | % | 10 | 0 | 100 |
| SAVE20 | % | 20 | 1,000,000 | 50 |
| NEWYEAR500K | ₫ | 500,000 | 2,000,000 | 30 |
| SUMMER15 | % | 15 | 500,000 | 200 |

### Bảo Mật

✅ Xác thực mã từ server side
✅ Kiểm tra giới hạn sử dụng từ database
✅ Xác thực lại khi checkout
✅ Không thể bypass từ client

### Testing

Các mã giảm giá mặc định có thể test ngay sau khi seed database.

### Known Issues

- None currently

### Todo

- [ ] Thêm admin dashboard chart cho discount usage
- [ ] Thêm tính năng coupon code (mã riêng cho khách viêng)
- [ ] Thêm discount code history
- [ ] Thêm bulk import discount codes

---

*Last Updated: 2024*
