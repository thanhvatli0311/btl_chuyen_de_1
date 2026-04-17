@extends('layout')

@section('content')

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>🤖 Huấn Luyện Chatbot</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.chatbot.create') }}" class="btn btn-success">
                ➕ Thêm Câu Hỏi
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>❓ Câu Hỏi</th>
                    <th>💬 Trả Lời</th>
                    <th>📁 Danh Mục</th>
                    <th>✅ Trạng Thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($responses as $response)
                    <tr>
                        <td><strong>#{{ $response->id }}</strong></td>
                        <td><strong>{{ Str::limit($response->question, 40) }}</strong></td>
                        <td>{{ Str::limit($response->answer, 50) }}</td>
                        <td><span class="badge bg-info">{{ $response->category }}</span></td>
                        <td>
                            @if($response->is_active)
                                <span class="badge bg-success">✅ Hoạt Động</span>
                            @else
                                <span class="badge bg-danger">❌ Tắt</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.chatbot.edit', $response) }}" class="btn btn-sm btn-primary">✏️</a>
                            <form action="{{ route('admin.chatbot.delete', $response) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn?')">🗑️</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $responses->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection
