@extends('layout')

@section('content')
<div class="container-fluid py-3">
    @php
        $answeredCount = $messages->where('status', 'answered')->count();
        $archivedCount = $messages->where('status', 'archived')->count();
    @endphp

    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="mb-1">Hộp thư hỗ trợ khách hàng</h2>
            <p class="text-muted mb-0">Theo dõi các cuộc trò chuyện chatbot, ưu tiên tin nhắn đang chờ admin phản hồi.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <span class="badge rounded-pill text-bg-warning px-3 py-2">Chờ xử lý: {{ $pendingCount }}</span>
            <span class="badge rounded-pill text-bg-success px-3 py-2">Đã trả lời: {{ $answeredCount }}</span>
            <span class="badge rounded-pill text-bg-secondary px-3 py-2">Lưu trữ: {{ $archivedCount }}</span>
            <span class="badge rounded-pill text-bg-dark px-3 py-2">Tổng: {{ $messages->total() }}</span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 pt-4 pb-2">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="p-3 rounded-3 border bg-warning bg-opacity-10 h-100">
                        <div class="small text-uppercase text-muted mb-1">Ưu tiên</div>
                        <div class="fw-semibold">Tin nhắn chờ phản hồi</div>
                        <div class="display-6 fw-bold text-warning mb-0">{{ $pendingCount }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded-3 border bg-light h-100">
                        <div class="small text-uppercase text-muted mb-1">Theo dõi</div>
                        <div class="fw-semibold">Khách hàng cần hỗ trợ</div>
                        <div class="text-muted small mb-0">Mỗi dòng tương ứng một tin nhắn trong chuỗi hội thoại của khách.</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded-3 border bg-light h-100">
                        <div class="small text-uppercase text-muted mb-1">Hành động</div>
                        <div class="fw-semibold">Mở chi tiết để xem timeline</div>
                        <div class="text-muted small mb-0">Tại trang chi tiết bạn có thể trả lời ngay tin nhắn đang được chọn.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body pt-3">
            @if($messages->count() > 0)
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th style="min-width: 90px;">Mã</th>
                                <th style="min-width: 220px;">Khách hàng</th>
                                <th style="min-width: 260px;">Nội dung</th>
                                <th style="min-width: 150px;">Trạng thái</th>
                                <th style="min-width: 150px;">Nguồn phản hồi</th>
                                <th style="min-width: 180px;">Thời gian</th>
                                <th style="min-width: 180px;" class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($messages as $message)
                                @php
                                    $customerName = $message->visitor_name ?? ($message->user->name ?? 'Khách hàng');
                                    $customerEmail = $message->visitor_email ?? ($message->user->email ?? '-');
                                    $rowClass = $message->status === 'pending' ? 'table-warning' : '';
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    <td>
                                        <div class="fw-bold">#{{ $message->id }}</div>
                                        @if($message->status === 'pending')
                                            <span class="badge text-bg-warning mt-1">Cần xử lý</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $customerName }}</div>
                                        <div class="small text-muted">
                                            @if($customerEmail !== '-')
                                                <a href="mailto:{{ $customerEmail }}" class="text-decoration-none">{{ $customerEmail }}</a>
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ \Illuminate\Support\Str::limit($message->message, 90) }}</div>
                                        <div class="small text-muted mt-1">
                                            @if($message->response)
                                                Đã có phản hồi: {{ \Illuminate\Support\Str::limit($message->response, 60) }}
                                            @else
                                                Chưa có phản hồi từ hệ thống hoặc admin.
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($message->status === 'pending')
                                            <span class="badge text-bg-warning">Đang chờ admin</span>
                                        @elseif($message->status === 'answered')
                                            <span class="badge text-bg-success">Đã trả lời</span>
                                        @else
                                            <span class="badge text-bg-secondary">Lưu trữ</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($message->response)
                                            @if($message->is_auto_reply)
                                                <span class="badge text-bg-info">Tự động</span>
                                            @else
                                                <span class="badge text-bg-primary">Admin</span>
                                            @endif
                                        @else
                                            <span class="badge text-bg-light border text-dark">Chưa phản hồi</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $message->created_at->format('d/m/Y') }}</div>
                                        <div class="small text-muted">{{ $message->created_at->format('H:i') }}</div>
                                        @if($message->updated_at && $message->updated_at->ne($message->created_at))
                                            <div class="small text-muted mt-1">Cập nhật: {{ $message->updated_at->format('d/m/Y H:i') }}</div>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.messages.detail', $message) }}" class="btn btn-sm btn-outline-primary">
                                                Xem hội thoại
                                            </a>
                                            <form action="{{ route('admin.messages.delete', $message) }}" method="POST" onsubmit="return confirm('Chắc chắn xóa tin nhắn này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" type="submit">
                                                    Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center justify-content-md-end mt-4">
                    {{ $messages->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="display-6 mb-3">💬</div>
                    <h5 class="mb-2">Chưa có tin nhắn nào</h5>
                    <p class="text-muted mb-0">Khi khách hàng gửi tin nhắn từ chatbot, danh sách sẽ hiển thị tại đây.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection