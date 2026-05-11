@extends('layout')

@section('content')

<div class="row mb-4">
    <div class="col-md-12">
        <h1 class="display-5">🛒 Giỏ hàng của bạn</h1>
    </div>
</div>

@if(empty($cart))
    <div class="alert alert-info">
        <h4>Giỏ hàng trống</h4>
        <p>Hãy thêm sản phẩm vào giỏ hàng của bạn.</p>
        <a href="/" class="btn btn-primary">← Tiếp tục mua sắm</a>
    </div>
@else
    <div class="row">
        <!-- Danh sách sản phẩm trong giỏ -->
        <div class="col-md-8">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart as $cartItem)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="/images/{{ $cartItem->product->image }}" width="60" class="me-3 rounded" alt="{{ $cartItem->product->name }}">
                                        <div>
                                            <strong>{{ $cartItem->product->name }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ number_format($cartItem->product->price, 0, ',', '.') }}₫</td>
                                <td>
                                    <form action="/cart/update/{{ $cartItem->product->id }}" method="POST" class="d-flex">
                                        @csrf
                                        <input type="number" name="quantity" value="{{ $cartItem->quantity }}" min="1" max="{{ $cartItem->product->quantity }}" class="form-control form-control-sm me-2" style="width: 80px;">
                                        <button type="submit" class="btn btn-sm btn-primary">🔄</button>
                                    </form>
                                </td>
                                <td class="price">{{ number_format($cartItem->product->price * $cartItem->quantity, 0, ',', '.') }}₫</td>
                                <td>
                                    <form action="/cart/remove/{{ $cartItem->product->id }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn?')">🗑️ Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <a href="/" class="btn btn-outline-dark">← Tiếp tục mua sắm</a>
            </div>
        </div>

        <!-- Tóm tắt và thanh toán -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5>📊 Tóm tắt đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tổng tiền:</span>
                        <strong class="price" id="subtotal">{{ number_format($total, 0, ',', '.') }}₫</strong>
                    </div>

                    <!-- Mã giảm giá -->
                    <div class="mb-3">
                        <label class="form-label">🎁 Mã giảm giá:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="discountCode" placeholder="Nhập mã giảm giá">
                            <button class="btn btn-outline-primary" type="button" id="applyDiscountBtn">Áp dụng</button>
                        </div>
                        <small id="discountMessage" class="text-muted d-block mt-2"></small>
                    </div>

                    <div id="discountInfo" style="display: none;">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Giảm giá:</span>
                            <strong class="text-danger" id="discountAmount">0₫</strong>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Vận chuyển:</span>
                        <strong>Miễn phí</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Tổng cộng:</strong>
                        <strong class="price fs-5" id="finalTotal">{{ number_format($total, 0, ',', '.') }}₫</strong>
                    </div>
                    <input type="hidden" id="finalTotalValue" value="{{ $total }}">
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-success btn-lg w-100" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                        💳 Tiến hành thanh toán
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Checkout -->
    <div class="modal fade" id="checkoutModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">🏠 Thông tin giao hàng</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="/order/checkout" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">👤 Tên người nhận:</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">📧 Email:</label>
                            <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">📱 Số điện thoại:</label>
                            <input type="tel" class="form-control" name="phone" placeholder="09xx xxx xxx" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">📍 Địa chỉ giao hàng:</label>
                            <textarea class="form-control" name="shipping_address" rows="3" placeholder="Nhập địa chỉ chi tiết" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">📝 Ghi chú (tùy chọn):</label>
                            <textarea class="form-control" name="note" rows="2" placeholder="Ghi chú thêm cho shop..."></textarea>
                        </div>

                        <div class="alert alert-info">
                            <strong>💳 Tổng thanh toán:</strong> <br>
                            <span class="price fs-5" id="checkoutTotal">{{ number_format($total, 0, ',', '.') }}₫</span>
                            <input type="hidden" name="final_total" id="checkoutFinalTotal" value="{{ $total }}">
                            <input type="hidden" name="discount_code" id="checkoutDiscountCode" value="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">❌ Hủy</button>
                        <button type="submit" class="btn btn-success btn-lg">✅ Xác nhận đặt hàng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const applyBtn = document.getElementById('applyDiscountBtn');
        const discountCodeInput = document.getElementById('discountCode');
        const subtotalElement = document.getElementById('subtotal');
        const finalTotalElement = document.getElementById('finalTotal');
        const discountAmountElement = document.getElementById('discountAmount');
        const discountInfoDiv = document.getElementById('discountInfo');
        const discountMessageElement = document.getElementById('discountMessage');
        const checkoutTotalElement = document.getElementById('checkoutTotal');
        const checkoutFinalTotalInput = document.getElementById('checkoutFinalTotal');
        
        const originalTotal = Number("{{ $total }}");
        let currentTotal = originalTotal;

        applyBtn.addEventListener('click', function() {
            const code = discountCodeInput.value.trim();
            
            if (!code) {
                discountMessageElement.textContent = '❌ Vui lòng nhập mã giảm giá';
                discountMessageElement.className = 'text-danger d-block mt-2';
                return;
            }

            // Gọi API để kiểm tra mã giảm giá
            fetch('/api/discount/verify', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    code: code,
                    total: originalTotal
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cập nhật UI với thông tin giảm giá
                    currentTotal = data.final_total;
                    
                    discountInfoDiv.style.display = 'block';
                    discountAmountElement.textContent = '-' + formatCurrency(data.discount_amount) + '₫';
                    finalTotalElement.textContent = formatCurrency(data.final_total) + '₫';
                    checkoutTotalElement.textContent = formatCurrency(data.final_total) + '₫';
                    checkoutFinalTotalInput.value = data.final_total;
                    
                    discountMessageElement.textContent = data.message;
                    discountMessageElement.className = 'text-success d-block mt-2';
                    
                    // Lưu mã giảm giá vào hidden input
                    document.getElementById('checkoutDiscountCode').value = code;
                    
                    // Vô hiệu hóa nút áp dụng
                    applyBtn.disabled = true;
                    discountCodeInput.disabled = true;
                } else {
                    discountMessageElement.textContent = data.message;
                    discountMessageElement.className = 'text-danger d-block mt-2';
                    discountInfoDiv.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                discountMessageElement.textContent = '❌ Có lỗi xảy ra. Vui lòng thử lại.';
                discountMessageElement.className = 'text-danger d-block mt-2';
            });
        });

        // Cho phép nhấn Enter để áp dụng mã giảm giá
        discountCodeInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyBtn.click();
            }
        });

        // Hàm định dạng tiền tệ
        function formatCurrency(value) {
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    });
</script>
@endsection