@extends('layouts.master')

@section('title', 'Sửa bài viết | Góc Nhỏ')

@section('main-class', 'main-page')

@section('content')

<h2>Sửa bài viết</h2>

@include('posts._form', [
'action' => route('post.update', $post),
'button' => 'Lưu thay đổi',
])

@endsection