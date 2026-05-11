@extends('layout')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Thêm sản phẩm mới</h2>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">← Quay lại</a>
</div>
<form method="POST"
action="{{ route('admin.products.store') }}"
enctype="multipart/form-data">

@csrf

<div class="mb-3">

<label>Tên</label>

<input type="text"
name="name"
class="form-control">

</div>

<div class="mb-3">

<label>Giá</label>

<input type="number"
name="price"
class="form-control">

</div>

<div class="mb-3">

<label>Số lượng</label>

<input type="number"
name="quantity"
class="form-control">

</div>

<div class="mb-3">

<label>Ảnh</label>

<input type="file"
name="image"
class="form-control">

</div>

<div class="mb-3">

<label>Mô tả</label>

<textarea
name="description"
class="form-control">

</textarea>

</div>

<div class="mb-3">

<label>Danh mục</label>

<select
name="category_id"
class="form-control">

@foreach($categories as $c)

<option
value="{{$c->id}}">

{{$c->name}}

</option>

@endforeach

</select>

</div>

<div class="mb-3">

<label>Thương hiệu</label>

<select name="brand_id" class="form-control">
    <option value="">-- Chọn thương hiệu --</option>
    @foreach($brands as $brand)
        <option value="{{ $brand->id }}">
            {{ $brand->name }}
        </option>
    @endforeach
</select>

</div>

<button class="btn btn-success">

Save

</button>

</form>

@endsection