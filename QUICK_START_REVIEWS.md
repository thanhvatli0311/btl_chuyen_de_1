# Quick Start - Hệ Thống Đánh Giá Sản Phẩm

## 🚀 Setup Nhanh

### Step 1: Chạy Migration
```bash
php artisan migrate
```

### Step 2: Khởi Động Server
```bash
php artisan serve
```

---

## 🧪 Test Hệ Thống

### Kịch Bản Test:

#### 1️⃣ **Tạo Đơn Hàng**
- Đăng nhập bằng tài khoản khách
- Thêm sản phẩm vào giỏ
- Checkout → tạo đơn hàng
- Status sẽ là "pending"

#### 2️⃣ **Admin Phê Duyệt Đơn**
- Đăng nhập bằng tài khoản admin
- Vào `/admin/orders`
- Cập nhật status thành "completed"

#### 3️⃣ **Khách Hàng Đánh Giá**
- Đăng nhập lại tài khoản khách
- Vào "Đơn hàng của tôi" (`/orders`)
- Click "👁️ Xem chi tiết"
- Sẽ thấy button "⭐ Đánh Giá Sản Phẩm" (chỉ nếu status = completed)
- Click vào
- Chọn sao + viết bình luận
- Gửi

#### 4️⃣ **Admin Duyệt Đánh Giá**
- Vào `/admin/reviews`
- Thấy đánh giá chờ duyệt (⏳ Chờ Duyệt)
- Click "✅ Duyệt"
- Hoặc "🗑️ Xóa" nếu không phù hợp

#### 5️⃣ **Khách Hàng Xem Đánh Giá**
- Vào trang sản phẩm (`/product/{id}`)
- Thấy đánh giá hiển thị
- Hoặc click "Xem tất cả X đánh giá" để xem đầy đủ

---

## 📊 Key Points

| Vấn đề | Giải Pháp |
|--------|----------|
| Button "Đánh Giá" không hiển thị | Kiểm tra status = "completed"? |
| Đánh giá không hiển thị | Chờ admin duyệt (is_approved = true) |
| Không thể đánh giá 2 lần | Đúng - mỗi sản phẩm/đơn 1 đánh giá |

---

## 🔗 Key Routes

```
Customer:
GET  /orders                           # Danh sách đơn
GET  /order/{order}/review             # Form đánh giá
POST /order/{order}/review             # Lưu đánh giá
GET  /product/{product}/reviews        # Xem tất cả reviews

Admin:
GET  /admin/reviews                    # Danh sách reviews
GET  /admin/reviews/{review}           # Chi tiết
POST /admin/reviews/{review}/approve   # Phê duyệt
DELETE /admin/reviews/{review}         # Xóa
```

---

## 🎬 UI Flow

### Customer View:
```
Đơn Hàng của Tôi
├─ Pending (không có button)
├─ Processing (không có button)
└─ Completed ← ⭐ BUTTON ĐÁNH GIÁ HIỂN THỊ TẠI ĐÂY
   └─ Click button
      └─ Form: chọn sao + bình luận
         └─ Gửi → chờ duyệt
            └─ Hiển thị trên trang sản phẩm (sau khi admin duyệt)
```

### Product Page:
```
Trang Sản Phẩm
├─ ⭐ 4.5/5 (12 đánh giá)
├─ 3 Đánh giá mới nhất
│  ├─ ⭐⭐⭐⭐⭐ "Rất tốt!" - User A
│  ├─ ⭐⭐⭐⭐ "Hài lòng" - User B
│  └─ ⭐⭐⭐⭐⭐ "Xuất sắc" - User C
└─ 👁️ Xem tất cả 12 đánh giá
   └─ Full Reviews Page
      ├─ Rating distribution chart
      └─ Tất cả reviews (pagination)
```

---

## 📋 Checklist

- ✅ Migration chạy xong
- ✅ Models update (Review, Product, Order, User)
- ✅ Controllers tạo (ReviewController, AdminReviewController)
- ✅ Views tạo (create, show cho customer / index, show cho admin)
- ✅ Routes thêm
- ✅ Order Model có reviews relationship
- ✅ Product Model có reviews relationship
- ✅ my_orders view có button đánh giá
- ✅ product_detail view có reviews section

---

## 🆘 Debug

Nếu có vấn đề:

1. **Check routes:**
```bash
php artisan route:list | grep review
```

2. **Check database:**
```bash
php artisan tinker
# Review::count()
# Review::where('is_approved', false)->get()
```

3. **Clear cache:**
```bash
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

4. **Check migrations:**
```bash
php artisan migrate:status
```

---

## 📞 Support

Nếu button "Đánh Giá" không hiển thị:
1. Kiểm tra status đơn = "completed"?
2. Refresh page
3. Ctrl+Shift+Delete → xóa cache browser
4. Thử browser khác

---

**Status: ✅ Ready to Use**

Hệ thống đã sẵn sàng! Hãy test theo kịch bản phía trên. 🎉
