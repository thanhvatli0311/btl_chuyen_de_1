@extends('layout')

@section('content')
<div class="container-fluid py-3">
    @php
        $customerName = $message->visitor_name ?? ($message->user->name ?? 'Khách hàng');
        $customerEmail = $message->visitor_email ?? ($message->user->email ?? '-');
        $threadMessages = collect([$message]);

        if (isset($conversation) && $conversation instanceof \Illuminate\Support\Collection) {
            $threadMessages = $conversation;
        } elseif (isset($conversation) && is_iterable($conversation)) {
            $threadMessages = collect($conversation);
        } elseif (isset($messages) && $messages instanceof \Illuminate\Support\Collection) {
            $threadMessages = $messages;
        } elseif (isset($messages) && is_iterable($messages)) {
            $threadMessages = collect($messages);
        }

        $pendingMessages = $threadMessages->where('status', 'pending');
        $selectedPendingMessage = $pendingMessages->firstWhere('id', $message->id) ?? $pendingMessages->first();
    @endphp

    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4">
        <div>
            <a href="{{ route('admin.messages') }}" class="btn btn-outline-secondary btn-sm mb-3">← Quay lại hộp thư</a>
            <h2 class="mb-1">Chi tiết hội thoại khách hàng</h2>
            <p class="text-muted mb-0">Theo dõi toàn bộ tiến trình trao đổi và phản hồi trực tiếp cho tin nhắn đang chờ xử lý.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <span class="badge text-bg-dark px-3 py-2">Khách hàng: {{ $customerName }}</span>
            @if($customerEmail !== '-')
                <span class="badge text-bg-light border text-dark px-3 py-2">{{ $customerEmail }}</span>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                        <div>
                            <h5 class="mb-1">Timeline hội thoại</h5>
                            <div class="text-muted small">Hiển thị theo thứ tự thời gian tạo từng tin nhắn trong cùng cuộc trò chuyện.</div>
                        </div>
                        <div class="text-muted small text-md-end">
                            <div>Tổng tin nhắn: <strong>{{ $threadMessages->count() }}</strong></div>
                            <div>Đang chờ trả lời: <strong>{{ $pendingMessages->count() }}</strong></div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($threadMessages as $threadMessage)
                        <div class="border rounded-3 p-3 mb-4 {{ $threadMessage->id === $message->id ? 'border-primary shadow-sm' : 'border-light' }}">
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3">
                                <div>
                                    <div class="fw-semibold">Yêu cầu #{{ $threadMessage->id }}</div>
                                    <div class="small text-muted">
                                        {{ $threadMessage->created_at->format('d/m/Y H:i:s') }}
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    @if($threadMessage->status === 'pending')
                                        <span class="badge text-bg-warning">Đang chờ admin</span>
                                    @elseif($threadMessage->status === 'answered')
                                        <span class="badge text-bg-success">Đã trả lời</span>
                                    @else
                                        <span class="badge text-bg-secondary">Lưu trữ</span>
                                    @endif

                                    @if($threadMessage->response)
                                        @if($threadMessage->is_auto_reply)
                                            <span class="badge text-bg-info">Nguồn phản hồi: Tự động</span>
                                        @else
                                            <span class="badge text-bg-primary">Nguồn phản hồi: Admin</span>
                                        @endif
                                    @else
                                        <span class="badge text-bg-light border text-dark">Chưa phản hồi</span>
                                    @endif

                                    @if($threadMessage->id === $message->id)
                                        <span class="badge text-bg-dark">Đang xem</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="small text-uppercase text-muted mb-2">Khách hàng</div>
                                <div class="bg-light rounded-3 p-3">
                                    <div class="fw-semibold mb-1">{{ $threadMessage->visitor_name ?? ($threadMessage->user->name ?? 'Khách hàng') }}</div>
                                    <div class="small text-muted mb-2">
                                        @php
                                            $threadEmail = $threadMessage->visitor_email ?? ($threadMessage->user->email ?? '-');
                                        @endphp
                                        @if($threadEmail !== '-')
                                            <a href="mailto:{{ $threadEmail }}" class="text-decoration-none">{{ $threadEmail }}</a>
                                        @else
                                            Không có email
                                        @endif
                                    </div>
                                    <div class="mb-0">{{ $threadMessage->message }}</div>
                                </div>
                            </div>

                            <div>
                                <div class="small text-uppercase text-muted mb-2">Phản hồi hệ thống / admin</div>
                                @if($threadMessage->response)
                                    <div class="rounded-3 p-3 {{ $threadMessage->is_auto_reply ? 'bg-info bg-opacity-10 border border-info-subtle' : 'bg-success bg-opacity-10 border border-success-subtle' }}">
                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                            @if($threadMessage->is_auto_reply)
                                                <span class="badge text-bg-info">Tự động</span>
                                            @else
                                                <span class="badge text-bg-success">Admin</span>
                                            @endif
                                            <span class="small text-muted">Cập nhật: {{ $threadMessage->updated_at->format('d/m/Y H:i:s') }}</span>
                                        </div>
                                        <div>{{ $threadMessage->response }}</div>
                                    </div>
                                @else
                                    <div class="rounded-3 border border-dashed p-3 text-muted">
                                        Chưa có phản hồi cho tin nhắn này.
                                    </div>
                                @endif
                            </div>

                            @if($threadMessage->status === 'pending')
                                <div class="mt-3">
                                    <a href="{{ route('admin.messages.detail', $threadMessage) }}" class="btn btn-sm btn-outline-primary">
                                        Trả lời tin nhắn này
                                    </a>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            Không tìm thấy dữ liệu hội thoại.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <h5 class="mb-1">Thông tin khách hàng</h5>
                    <div class="small text-muted">Thông tin từ tài khoản hoặc dữ liệu khách đã cung cấp.</div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="small text-uppercase text-muted">Tên khách hàng</div>
                        <div class="fw-semibold">{{ $customerName }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="small text-uppercase text-muted">Email</div>
                        <div>
                            @if($customerEmail !== '-')
                                <a href="mailto:{{ $customerEmail }}" class="text-decoration-none">{{ $customerEmail }}</a>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="small text-uppercase text-muted">Tin nhắn đang chọn</div>
                        <div>#{{ $message->id }}</div>
                    </div>
                    <div class="mb-0">
                        <div class="small text-uppercase text-muted">Trạng thái hiện tại</div>
                        @if($message->status === 'pending')
                            <span class="badge text-bg-warning">Đang chờ admin</span>
                        @elseif($message->status === 'answered')
                            <span class="badge text-bg-success">Đã trả lời</span>
                        @else
                            <span class="badge text-bg-secondary">Lưu trữ</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header {{ $selectedPendingMessage ? 'bg-warning bg-opacity-25' : 'bg-light' }} border-0 pt-4 pb-3">
                    <h5 class="mb-1">Khung phản hồi admin</h5>
                    <div class="small text-muted">
                        @if($selectedPendingMessage)
                            Bạn đang phản hồi cho yêu cầu #{{ $selectedPendingMessage->id }}.
                        @else
                            Hội thoại này hiện không còn tin nhắn chờ xử lý.
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($selectedPendingMessage)
                        <form action="{{ route('admin.messages.reply', $selectedPendingMessage) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="response" class="form-label">Nội dung phản hồi</label>
                                <textarea class="form-control @error('response') is-invalid @enderror" id="response" name="response" rows="7" placeholder="Nhập phản hồi cho khách hàng..." required>{{ old('response') }}</textarea>
                                @error('response')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="rounded-3 bg-light p-3 mb-3">
                                <div class="small text-uppercase text-muted mb-2">Đang trả lời cho</div>
                                <div class="fw-semibold mb-1">Tin nhắn #{{ $selectedPendingMessage->id }}</div>
                                <div class="small text-muted mb-2">{{ $selectedPendingMessage->created_at->format('d/m/Y H:i:s') }}</div>
                                <div>{{ $selectedPendingMessage->message }}</div>
                            </div>

                            <button type="submit" class="btn btn-success w-100">
                                Gửi phản hồi
                            </button>
                        </form>
                    @else
                        <div class="text-center py-3">
                            <div class="display-6 mb-2">✅</div>
                            <div class="fw-semibold">Không còn tin nhắn chờ trả lời</div>
                            <div class="text-muted small">Bạn có thể xem lại timeline hoặc quay về hộp thư.</div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <h5 class="mb-1">Hành động khác</h5>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('admin.messages') }}" class="btn btn-outline-secondary">
                        Quay lại danh sách
                    </a>
                    <form action="{{ route('admin.messages.delete', $message) }}" method="POST" onsubmit="return confirm('Chắc chắn xóa tin nhắn này?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger w-100" type="submit">
                            Xóa tin nhắn đang chọn
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
