# ⭐ Hệ Thống Đánh Giá Sản Phẩm (Product Review System)

## 📋 Tổng Quan

Watch Store có hệ thống đánh giá sản phẩm hoàn chỉnh cho phép khách hàng đánh giá các sản phẩm sau khi đã mua và đơn hàng hoàn thành. Admin duyệt trước khi hiển thị công khai.

### ✨ Tính Năng Chính

#### 👥 Cho Khách Hàng
- ✅ Xem danh sách đơn hàng của mình
- ✅ Đánh giá từng sản phẩm trong đơn hàng (chỉ khi đơn hàng hoàn thành)
- ✅ Chọn mức xếp hạng từ 1-5 sao
- ✅ Viết bình luận miêu tả trải nghiệm (tùy chọn)
- ✅ Xem các đánh giá của khách hàng khác trên trang sản phẩm
- ✅ Kiểm tra xem đã review hay chưa (không review 2 lần 1 sản phẩm)

#### 👨‍💼 Cho Admin
- ✅ Xem danh sách tất cả đánh giá
- ✅ Duyệt/phê duyệt đánh giá trước khi hiển thị
- ✅ Xem chi tiết từng đánh giá
- ✅ Xóa/từ chối đánh giá không phù hợp
- ✅ Quản lý trạng thái đơn hàng để khách hàng có thể review

#### 🔧 Trên Trang Sản Phẩm
- ✅ Hiển thị điểm xếp hạng trung bình (1-5 sao)
- ✅ Hiển thị 3 đánh giá mới nhất
- ✅ Nút "Xem tất cả đánh giá" để xem đầy đủ
- ✅ Thống kê số lượng đánh giá mỗi sao (1-5)

---

## 🔄 Quy Trình Đánh Giá (Workflow)

```
1. 👤 Khách hàng mua sản phẩm
        ↓
2. 🛒 Tạo đơn hàng (trạng thái: pending/đang chờ)
        ↓
3. 👨‍💼 Admin xác nhận (trạng thái: processing/đang xử lý)
        ↓
4. 📦 Admin cập nhật (trạng thái: completed/hoàn thành)
        ↓
5. 👤 Khách hàng vào "Đơn Hàng Của Tôi" (/orders)
        ↓
6. ⭐ Nhấp nút "⭐ Đánh Giá Sản Phẩm" trên đơn hàng hoàn thành
        ↓
7. 🎯 Khách hàng chọn sao + viết bình luận
        ↓
8. ✍️ Gửi đánh giá (chưa được duyệt - is_approved = false)
        ↓
9. 👨‍💼 Admin duyệt đánh giá (is_approved = true)
        ↓
10. 🌐 Đánh giá hiển thị trên trang sản phẩm công khai
```

---

## 🚀 Khởi Động Nhanh

### 1️⃣ Database Setup
Chạy migration để tạo bảng reviews:
```bash
php artisan migrate
```

### 2️⃣ Chạy Server
```bash
php artisan serve
```

---

## 📊 Cấu Trúc Database

### Bảng: reviews

| Cột | Kiểu | Mô Tả |
|-----|------|-------|
| id | INT | ID duy nhất |
| user_id | INT | ID khách hàng đánh giá |
| product_id | INT | ID sản phẩm được đánh giá |
| order_id | INT | ID đơn hàng (liên kết để biết đơn nào) |
| rating | INT | Xếp hạng 1-5 sao |
| comment | TEXT | Bình luận/mô tả |
| is_approved | BOOLEAN | Đã được admin duyệt? |
| created_at | TIMESTAMP | Lúc tạo đánh giá |
| updated_at | TIMESTAMP | Lúc cập nhật lần cuối |

**Constraints:**
- Một user chỉ được review một sản phẩm một lần từ một đơn hàng
- Chỉ review được khi đơn hàng có status = "completed"

---

## 🛣️ Routes (Routing)

### 🌐 Public Routes (Không cần đăng nhập)
```
GET  /product/{id}                      # Xem chi tiết sản phẩm + hiển thị 3 review gần nhất
GET  /product/{product}/reviews         # Xem tất cả review của sản phẩm
```

### 🔐 Customer Routes (Cần đăng nhập)
```
GET  /order/{order}/review              # Form đánh giá sản phẩm
POST /order/{order}/review              # Lưu đánh giá
```

### 👨‍💼 Admin Routes (Cần quyền admin)
```
GET  /admin/reviews                     # Danh sách tất cả đánh giá (phân trang 10/trang)
GET  /admin/reviews/{review}            # Xem chi tiết 1 đánh giá
POST /admin/reviews/{review}/approve    # Phê duyệt đánh giá (is_approved = true)
DELETE /admin/reviews/{review}          # Xóa/từ chối đánh giá
```

---

## 📁 Cấu Trúc File Code

### Controllers
```
app/Http/Controllers/
├── ReviewController.php
│   ├── create(Order)      # Hiển thị form đánh giá
│   ├── store(Request, Order) # Lưu đánh giá
│   └── showByProduct($productId) # Xem tất cả review sản phẩm
│
└── Admin/
    └── ReviewController.php
        ├── index()        # Danh sách review cần duyệt
        ├── show(Review)   # Chi tiết review
        ├── approve(Review) # Duyệt review
        └── reject(Review) # Xóa review (PATCH -> DELETE)
```

### Models
```
app/Models/
├── Review.php      # Review model với relationships
├── Product.php     # Cập nhật: hasMany('reviews')
├── Order.php       # Cập nhật: hasMany('reviews')
└── User.php        # Cập nhật: hasMany('reviews')
```

### Migrations
```
database/migrations/
├── 2026_04_12_000005_create_reviews_table.php       # Tạo bảng reviews
└── 2026_04_13_000000_add_order_id_to_reviews_table.php # Thêm cột order_id
```

---

## 👥 Hướng Dẫn Sử Dụng - Khách Hàng

### Bước 1: Xem Đơn Hàng Của Mình
1. Đăng nhập vào tài khoản
2. Vào menu → **"Đơn Hàng Của Tôi"** hoặc truy cập: `/orders`
3. Xem danh sách đơn hàng

### Bước 2: Chọn Đơn Hàng Hoàn Thành
- Tìm đơn hàng có trạng thái **"completed"** (hoàn thành)
- Chỉ những đơn hàng hoàn thành mới có nút đánh giá

### Bước 3: Nhấp Nút Đánh Giá
- Nhấp vào nút **"⭐ Đánh Giá Sản Phẩm"** trên đơn hàng

### Bước 4: Chọn Sản Phẩm & Xếp Hạng
1. Chọn sản phẩm (nếu đơn hàng có nhiều sản phẩm)
2. Chọn số sao: ⭐ (1 sao) → ⭐⭐⭐⭐⭐ (5 sao)
3. Viết bình luận (tuỳ chọn):
   - Chia sẻ trải nghiệm
   - Mô tả chất lượng sản phẩm
   - Gợi ý cho khách hàng khác
4. Nhấp **"Gửi Đánh Giá"**

### Bước 5: Chờ Admin Duyệt
- Đánh giá sẽ hiển thị "Chờ duyệt" ban đầu
- Admin sẽ kiểm tra và duyệt
- Sau khi duyệt → Đánh giá xuất hiện trên trang sản phẩm

### Lưu Ý
- ⚠️ Mỗi sản phẩm chỉ được review **1 lần** từ 1 đơn hàng
- ⚠️ Chỉ có thể review khi đơn hàng **hoàn thành**
- ⚠️ Admin sẽ **duyệt** trước khi hiển thị công khai

---

## 👨‍💼 Hướng Dẫn Sử Dụng - Admin

### Bước 1: Quản Lý Trạng Thái Đơn Hàng
1. Vào `/admin/orders`
2. Chọn đơn hàng khách hàng
3. Cập nhật trạng thái thành **"completed"** (hoàn thành)
4. Lưu thay đổi
   - **Lúc này** khách hàng mới có thể đánh giá sản phẩm

### Bước 2: Duyệt Đánh Giá
1. Vào `/admin/reviews`
2. Xem danh sách tất cả đánh giá (hiển thị mới nhất trước)
3. Nhấp vào đánh giá để xem chi tiết

### Bước 3: Xem Chi Tiết Đánh Giá
- Tên khách hàng
- Sản phẩm được đánh giá
- Số sao (rating)
- Bình luận
- Trạng thái duyệt (is_approved)

### Bước 4: Phê Duyệt Hoặc Xóa
- **Phê Duyệt**: Nhấp **"✅ Phê Duyệt"** → Đánh giá hiển thị công khai
- **Xóa/Từ Chối**: Nhấp **"🗑️ Xóa"** → Xoá đánh giá không phù hợp

### Admin Tasks
| Tác Vụ | URL | Mô Tả |
|--------|-----|-------|
| 📋 Danh sách review | `/admin/reviews` | Xem tất cả review, phân trang |
| 👁️ Chi tiết review | `/admin/reviews/{id}` | Xem đầy đủ thông tin |
| ✅ Phê duyệt | POST `/admin/reviews/{id}/approve` | Duyệt → hiển thị công khai |
| 🗑️ Xóa review | DELETE `/admin/reviews/{id}` | Xoá review không phù hợp |

---

## 📺 Hiển Thị Trên Trang Sản Phẩm

### Phần 1: Thông Tin Đánh Giá
- **Điểm trung bình**: ⭐⭐⭐⭐⭐ (4.5/5.0)
- **Số lượng đánh giá**: 24 reviews
- **Phân bố sao**:
  - ⭐⭐⭐⭐⭐ 5 sao: 15 đánh giá
  - ⭐⭐⭐⭐ 4 sao: 7 đánh giá
  - ⭐⭐⭐ 3 sao: 2 đánh giá
  - ⭐⭐ 2 sao: 0 đánh giá
  - ⭐ 1 sao: 0 đánh giá

### Phần 2: 3 Đánh Giá Mới Nhất
Hiển thị 3 review mới nhất (đã duyệt):
```
👤 Nguyễn Văn A
⭐⭐⭐⭐⭐ 5/5 - 2 ngày trước
"Sản phẩm rất đẹp, giao hàng nhanh, rất hài lòng!"

👤 Trần Thị B
⭐⭐⭐⭐ 4/5 - 5 ngày trước
"Tốt, nhưng pin có thể lâu hơn"

👤 Lê Văn C
⭐⭐⭐⭐⭐ 5/5 - 1 tuần trước
"Quá tuyệt vời, giá cả hợp lý!"
```

### Phần 3: Nút "Xem Tất Cả"
- Click → Chuyển đến `/product/{id}/reviews`
- Hiển thị tất cả review (phân trang)

---

## 🔐 Bảo Mật & Authorization

### Customer Authorization
- ✅ Chỉ có thể review sản phẩm từ **đơn hàng của chính mình**
- ✅ Chỉ review khi đơn hàng status = **"completed"**
- ✅ Không thể review **2 lần** cùng 1 sản phẩm từ 1 đơn hàng
- ✅ **Server-side** validation trước khi lưu

### Admin Authorization
- ✅ Chỉ tài khoản **admin** mới truy cập `/admin/reviews`
- ✅ Middleware `admin` kiểm tra quyền

### Database Constraints
```sql
UNIQUE(user_id, product_id, order_id)  -- Một user, một sản phẩm, một đơn hàng
```

---

## ✅ Kiểm Tra Chức Năng

| Tính Năng | Cách Test |
|-----------|-----------|
| 📝 Tạo đơn hàng | Customer → `/product/1` → Thêm giỏ → Checkout |
| 📋 Xem đơn hàng | Customer → `/orders` |
| 🔄 Cập nhật status | Admin → `/admin/orders` → Chọn đơn → completed |
| ⭐ Hiển thị form đánh giá | Customer → `/orders` → Nhấp nút review (đơn completed) |
| ✍️ Gửi đánh giá | Chọn sao + viết bình luận → Submit |
| 👁️ Admin xem review | Admin → `/admin/reviews` |
| ✅ Duyệt review | Admin → Review detail → Phê duyệt |
| 🌐 Xem review công khai | Customer → `/product/1` → Xem review |
| 🔍 Filter review | Customer → `/product/1/reviews` → Lọc theo sao |
| 🗑️ Xóa review | Admin → Chọn review → Xóa |

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
