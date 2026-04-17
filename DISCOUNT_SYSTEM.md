# Hệ Thống Mã Giảm Giá (Discount Code System)

## Tổng Quan
Hệ thống mã giảm giá cho phép admin tạo và quản lý các mã giảm giá để khách hàng sử dụng khi thanh toán.

## Tính Năng

### 1. Loại Giảm Giá
- **Giảm Phần Trăm (Percentage)**: Giảm theo phần trăm của tổng giá trị đơn hàng
  - Ví dụ: SAVE10 giảm 10% mọi đơn
  
- **Giảm Số Cố Định (Fixed Amount)**: Giảm một số tiền cụ thể
  - Ví dụ: NEWYEAR500K giảm 500,000₫

### 2. Điều Kiện Áp Dụng
- **Tối Thiểu Đơn Hàng**: Yêu cầu đơn hàng phải đạt tối thiểu bao nhiêu tiền mới được dùng mã
- **Thời Hạn**: Mã giảm giá chỉ có hiệu lực trong khoảng thời gian xác định
- **Giới Hạn Sử Dụng**: Mã chỉ có thể dùng tối đa bao nhiêu lần
- **Kích Hoạt**: Admin có thể vô hiệu hóa mã bất cứ lúc nào

### 3. Xác Thực Mã
- Hệ thống xác thực mã giảm giá qua API trước khi checkout
- Kiểm tra:
  - Mã có tồn tại không
  - Mã có được kích hoạt không
  - Mã còn hiệu lực không
  - Tổng đơn có đạt tối thiểu không
  - Mã còn lượt sử dụng không

## Mã Giảm Giá Mặc Định

### SAVE10
- **Loại**: Giảm phần trăm
- **Giá trị**: 10%
- **Tối Thiểu**: 0₫ (áp dụng với mọi đơn)
- **Giới Hạn**: 100 lần
- **Hạn**: Có hiệu lực trong 1 tháng

### SAVE20
- **Loại**: Giảm phần trăm
- **Giá trị**: 20%
- **Tối Thiểu**: 1,000,000₫
- **Giới Hạn**: 50 lần

### NEWYEAR500K
- **Loại**: Giảm số cố định
- **Giá trị**: 500,000₫
- **Tối Thiểu**: 2,000,000₫
- **Giới Hạn**: 30 lần

### SUMMER15
- **Loại**: Giảm phần trăm
- **Giá trị**: 15%
- **Tối Thiểu**: 500,000₫
- **Giới Hạn**: 200 lần

## Hướng Dẫn Sử Dụng

### Cho Khách Hàng
1. Thêm sản phẩm vào giỏ hàng
2. Vào trang giỏ hàng
3. Nhập mã giảm giá vào trường "Mã giảm giá"
4. Nhấp nút "Áp dụng"
5. Hệ thống sẽ hiển thị số tiền được giảm
6. Tiến hành thanh toán với tổng tiền đã giảm

### Cho Admin (Quản Lý Mã)
1. Truy cập bảng `discount_codes` trong database
2. Tạo mã mới:
   ```sql
   INSERT INTO discount_codes 
   (code, description, type, value, minimum_order_amount, usage_limit, usage_count, valid_from, valid_until, is_active, created_at, updated_at) 
   VALUES 
   ('MYCODE', 'Mô tả mã', 'percentage', 10, 0, 100, 0, NOW(), DATE_ADD(NOW(), INTERVAL 1 MONTH), 1, NOW(), NOW());
   ```

3. Cập nhật mã:
   ```sql
   UPDATE discount_codes 
   SET value = 15, is_active = 1 
   WHERE code = 'MYCODE';
   ```

4. Vô hiệu hóa mã:
   ```sql
   UPDATE discount_codes 
   SET is_active = 0 
   WHERE code = 'OLDCODE';
   ```

## Cấu Trúc Database

### Bảng: discount_codes
| Cột | Kiểu | Mô Tả |
|-----|------|-------|
| id | INT | ID duy nhất |
| code | VARCHAR | Mã giảm giá (duy nhất) |
| description | TEXT | Mô tả |
| type | ENUM | Loại: 'percentage' hoặc 'fixed_amount' |
| value | DECIMAL | Giá trị giảm |
| minimum_order_amount | DECIMAL | Tối thiểu đơn hàng |
| usage_limit | INT | Giới hạn sử dụng |
| usage_count | INT | Lần đã dùng |
| valid_from | TIMESTAMP | Ngày bắt đầu |
| valid_until | TIMESTAMP | Ngày kết thúc |
| is_active | BOOLEAN | Có hoạt động không |
| created_at | TIMESTAMP | Lúc tạo |
| updated_at | TIMESTAMP | Lúc cập nhật |

## API Endpoints

### POST /api/discount/verify
Kiểm tra và xác thực mã giảm giá

**Request:**
```json
{
    "code": "SAVE10",
    "total": 1000000
}
```

**Response (Thành Công):**
```json
{
    "success": true,
    "message": "✅ Áp dụng mã giảm giá thành công!",
    "discount_amount": 100000,
    "final_total": 900000,
    "discount_type": "percentage",
    "discount_value": 10
}
```

**Response (Thất Bại):**
```json
{
    "success": false,
    "message": "❌ Mã giảm giá không tồn tại"
}
```

## Bảo Mật
- Mã giảm giá được xác thực từ server side trước khi tạo đơn hàng
- Số lượng sử dụng được kiểm soát từ database
- Thời hạn được kiểm tra realtime
- Không thể bypass giới hạn sử dụng từ client side

## Troubleshooting

### Lỗi "Mã giảm giá không tồn tại"
- Kiểm tra mã đã nhập đúng không (phân biệt chữ hoa/thường)
- Kiểm tra mã có tồn tại trong bảng `discount_codes` không
- Kiểm tra mã có được kích hoạt không (is_active = 1)

### Lỗi "Mã giảm giá đã hết hiệu lực"
- Kiểm tra ngày hiện tại có nằm trong khoảng valid_from và valid_until không
- Kiểm tra usage_count có vượt quá usage_limit không
- Kiểm tra is_active có phải là 1 không

### Lỗi "Mã giảm giá cần tối thiểu XXX₫"
- Tổng đơn chưa đạt minimum_order_amount
- Thêm sản phẩm vào giỏ để tăng tổng đơn

## Ví Dụ Sử Dụng

### Tạo mã giảm dịp Black Friday
```sql
INSERT INTO discount_codes 
(code, description, type, value, minimum_order_amount, usage_limit, usage_count, valid_from, valid_until, is_active, created_at, updated_at)
VALUES
('BLACKFRIDAY30', 'Giảm 30% nhân dịp Black Friday', 'percentage', 30, 500000, 500, 0, '2024-11-01', '2024-11-30', 1, NOW(), NOW());
```

### Tạo mã cho khách hàng VIP
```sql
INSERT INTO discount_codes 
(code, description, type, value, minimum_order_amount, usage_limit, usage_count, valid_from, valid_until, is_active, created_at, updated_at)
VALUES
('VIP100K', 'Giảm 100k cho khách VIP', 'fixed_amount', 100000, 0, 999, 0, '2024-01-01', '2025-12-31', 1, NOW(), NOW());
```

---

*Cập nhật: 2024* | *Liên hệ: Admin*
