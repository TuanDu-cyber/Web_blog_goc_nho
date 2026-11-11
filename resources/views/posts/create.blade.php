@extends('layouts.master')

@section('title', 'Viết bài mới | Góc Nhỏ')

@section('main-class', 'main-page')

@section('content')

<h2>Viết bài mới</h2>

@include('posts._form', [
'action' => route('post.store'),
'button' => 'Đăng bài',
])

@endsection