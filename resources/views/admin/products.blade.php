@extends('layout')

@section('content')

<h2>

quản lý sản phẩm

</h2>

<a href="/admin/add" class="add-btn">
Thêm sản phẩm
</a>

<table class="table">

<tr>

<th>Tên</th>

<th>Giá</th>

</tr>

@foreach($products as $p)

<tr>

<td>

{{$p->name}}

</td>

<td>

{{$p->price}}

</td>

</tr>

@endforeach

</table>

@endsection