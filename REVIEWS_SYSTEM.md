# Hệ Thống Đánh Giá Sản Phẩm (Product Review System)

## 📋 Tổng Quan

Hệ thống đánh giá sản phẩm cho phép khách hàng đánh giá các sản phẩm sau khi đã mua hàng và đơn hàng được admin chuyển sang trạng thái **"completed"** (hoàn thành).

## ✨ Tính Năng Chính

### 1. **Cho Khách Hàng (Customer)**

- ✅ Xem danh sách đơn hàng của mình
- ✅ Đánh giá sản phẩm khi đơn hàng hoàn thành
- ✅ Chọn mức xếp hạng (1-5 sao)
- ✅ Viết bình luận (tùy chọn)
- ✅ Xem các đánh giá của khách hàng khác

### 2. **Cho Admin**

- ✅ Xem danh sách đánh giá chờ duyệt
- ✅ Xem chi tiết từng đánh giá
- ✅ Phê duyệt hoặc xóa đánh giá
- ✅ Quản lý trạng thái đơn hàng
- ✅ Thống kê số đánh giá

### 3. **Trên Trang Sản Phẩm**

- ✅ Hiển thị điểm đánh giá trung bình
- ✅ Hiển thị 3 đánh giá mới nhất
- ✅ Nút "Xem tất cả đánh giá"
- ✅ Thống kê sao (1-5 sao có bao nhiêu)

---

## 🔄 Quy Trình Đánh Giá

```
1. Khách hàng mua sản phẩm
        ↓
2. Tạo đơn hàng (status = "pending")
        ↓
3. Admin xác nhận (status = "processing")
        ↓
4. Admin giao hàng
        ↓
5. Admin cập nhật (status = "completed") ← CÓ THỂ ĐỀ GIÁO TẠI ĐÂY
        ↓
6. Khách hàng vào "Đơn hàng của tôi"
        ↓
7. Nấn button "⭐ Đánh Giá Sản Phẩm"
        ↓
8. Khách hàng chọn sao + viết bình luận
        ↓
9. Gửi đánh giá (is_approved = false)
        ↓
10. Admin duyệt đánh giá (is_approved = true)
        ↓
11. Đánh giá hiển thị trên trang sản phẩm
```

---

## 📊 Database Schema

### Bảng: reviews

| Cột | Kiểu | Mô Tả |
|-----|------|-------|
| id | INT | ID duy nhất |
| user_id | INT | ID khách hàng |
| product_id | INT | ID sản phẩm |
| **order_id** | INT | ID đơn hàng *(mới)* |
| rating | INT | Số sao (1-5) |
| comment | TEXT | Bình luận |
| is_approved | BOOLEAN | Đã duyệt? |
| created_at | TIMESTAMP | Lúc tạo |
| updated_at | TIMESTAMP | Lúc cập nhật |

---

## 🛣️ Routes (Routing)

### Public Routes
```php
GET  /product/{id}                      // Xem chi tiết sản phẩm
GET  /product/{product}/reviews         // Xem tất cả đánh giá sản phẩm
```

### Customer Routes (require auth)
```php
GET  /order/{order}/review              // Form đánh giá
POST /order/{order}/review              // Lưu đánh giá
```

### Admin Routes (require admin)
```php
GET  /admin/reviews                     // Danh sách đánh giá
GET  /admin/reviews/{review}            // Chi tiết đánh giá
POST /admin/reviews/{review}/approve    // Phê duyệt
DELETE /admin/reviews/{review}          // Xóa/từ chối
```

---

## 📁 File Structure

### Controllers
```
app/Http/Controllers/
├── ReviewController.php              # Customer: create, store, show
└── Admin/
    └── ReviewController.php          # Admin: index, show, approve, reject
```

### Models
```
app/Models/
├── Review.php      # Model với relationships
├── Product.php     # Cập nhật: hasMany reviews
├── Order.php       # Cập nhật: hasMany reviews
└── User.php        # Cập nhật: hasMany reviews
```

### Views
```
resources/views/
├── product_detail.blade.php          # Hiển thị 3 reviews gần nhất
├── review/
│   ├── create.blade.php              # Form đánh giá cho khách
│   └── show.blade.php                # Xem tất cả reviews sản phẩm
├── admin/reviews/
│   ├── index.blade.php               # Danh sách reviews (admin)
│   └── show.blade.php                # Chi tiết review (admin)
└── my_orders.blade.php               # Cập nhật: thêm button đánh giá
```

### Migrations
```
database/migrations/
└── 2026_04_13_000000_add_order_id_to_reviews_table.php
```

---

## 🎯 Hướng Dẫn Sử Dụng

### Cho Khách Hàng

#### Bước 1: Xem Đơn Hàng
- Vào menu → "Đơn hàng của tôi" hoặc `/orders`
- Xem danh sách đơn hàng

#### Bước 2: Chọn Đơn Hoàn Thành
- Chỉ đơn hàng có status **"✅ Hoàn thành"** mới có button "⭐ Đánh Giá Sản Phẩm"
- Nhấp button này

#### Bước 3: Đánh Giá
- Chọn sao (1-5)
- Viết bình luận (tuỳ chọn)
- Nhấp "Gửi Đánh Giá"

#### Bước 4: Chờ Duyệt
- Đánh giá sẽ chờ admin duyệt
- Sau khi duyệt sẽ hiển thị trên trang sản phẩm

### Cho Admin

#### Duyệt Đánh Giá
1. Vào `/admin/reviews`
2. Xem danh sách đánh giá chờ duyệt (⏳ Chờ Duyệt)
3. Nhấp "👁️ Xem" để xem chi tiết
4. Nhấp "✅ Phê Duyệt" để phê duyệt
5. Hoặc "🗑️ Xóa" để xóa

#### Quản Lý Trạng Thái Đơn
1. Vào `/admin/orders`
2. Cập nhật status đơn hàng thành "completed"
3. Khách hàng sẽ có thể đánh giá

---

## 💡 Ví Dụ Thực Tế

### Khách Hàng A:
1. Mua đồng hồ Apple Watch
2. Admin xác nhận → giao hàng → cập nhật "completed"
3. Khách A vào "Đơn hàng của tôi"
4. Thấy button "⭐ Đánh Giá Sản Phẩm" (chỉ có khi status = completed)
5. Nhấp button → form đánh giá
6. Chọn 5 sao → viết: "Sản phẩm tuyệt vời!"
7. Gửi → chờ admin duyệt
8. Admin duyệt → đánh giá hiển thị trên trang sản phẩm

### Khách Hàng B:
1. Xem trang sản phẩm Apple Watch
2. Thấy "⭐ 4.5/5 (12 đánh giá)"
3. Thấy 3 đánh giá mới nhất:
   - 5 sao: "Tuyệt vời"
   - 4 sao: "Tốt"
   - 5 sao: "Rất hài lòng"
4. Nhấp "Xem tất cả 12 đánh giá"
5. Xem toàn bộ đánh giá, thống kê sao

---

## 🔒 Bảo Mật

| Check | Mô Tả |
|-------|-------|
| Auth Check | Chỉ user đã login mới có thể đánh giá |
| Order Ownership | User chỉ có thể đánh giá đơn của mình |
| Status Check | Chỉ đơn "completed" mới được đánh giá |
| No Duplicate | Không thể đánh giá cùng sản phẩm 2 lần từ 1 đơn |
| Approval | Đánh giá phải được admin duyệt mới hiển thị |

---

## 📈 Thống Kê & Metrics

### Trên Trang Sản Phẩm
- ⭐ Điểm trung bình: `4.5/5`
- 📊 Tổng đánh giá: `12`
- 📈 Phân bố sao:
  - 5 sao: 8 (67%)
  - 4 sao: 3 (25%)
  - 3 sao: 1 (8%)
  - 2 sao: 0 (0%)
  - 1 sao: 0 (0%)

---

## 🚀 Migration & Setup

### Chạy Migration
```bash
php artisan migrate
```

### Seeding (tùy chọn)
Hiện tại chưa có seeder cho reviews, bạn cần:
1. Tạo đơn hàng
2. Cập nhật status thành "completed"
3. Đánh giá sản phẩm

---

## 📝 Notes

- Đánh giá cần được **admin phê duyệt** trước khi hiển thị
- Chỉ **1 đánh giá/đơn hàng/sản phẩm** (không thể review nhiều lần)
- Sao phải từ **1-5**
- Bình luận không bắt buộc
- Khách hàng có thể thấy đánh giá của mình ngay sau khi gửi (nhưng trạng thái là "chờ duyệt")

---

## 🐛 Troubleshooting

### Lỗi: "Không thể đánh giá đơn hàng này"
- ✓ Kiểm tra status đơn hàng có phải "completed"?
- ✓ Đơn hàng có thuộc về user hiện tại?

### Lỗi: "Sản phẩm không có trong đơn hàng này"
- ✓ Kiểm tra sản phẩm có trong đơn hàng?
- ✓ Không thể đánh giá sản phẩm khác

### Lỗi: "Bạn đã đánh giá sản phẩm này rồi"
- ✓ Mỗi sản phẩm/đơn chỉ được đánh giá 1 lần
- ✓ Liên hệ admin để cập nhật đánh giá

---

## 📞 Contact

Liên hệ admin để:
- Chỉnh sửa đánh giá
- Xóa đánh giá của mình
- Báo cáo đánh giá không phù hợp

---

**Last Updated:** 2026-04-13
**Version:** 1.0
