@extends('layout')

@section('content')

<a href="/admin/add"
class="btn btn-primary">

Thêm sản phẩm

</a>
<form method="POST"
action="/admin/add"
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

<button class="btn btn-success">

Save

</button>

</form>

@endsection