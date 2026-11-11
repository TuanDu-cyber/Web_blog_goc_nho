@extends('layouts.master')

@section('title', 'Bài viết của tôi | Góc Nhỏ')

@section('main-class', 'main-page')

@section('content')

<h2>Bài viết của tôi</h2>

<div class="page-actions">
    <a class="btn" href="{{ route('post.create') }}">+ Viết bài mới</a>
    <a class="btn" href="{{ route('post.trash') }}">Thùng rác ({{ $trashedCount }})</a>
</div>

@if ($posts->isEmpty())

<p>Bạn chưa có bài viết nào.</p>

@else

<div class="table-wrap">
    <table class="post-table">
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Tiêu đề</th>
                <th>Chuyên mục</th>
                <th>Trạng thái</th>
                <th>Ngày đăng</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
            <tr>
                <td><img src="{{ asset($post->image) }}" alt="{{ $post->title }}"></td>
                <td><a href="{{ route('article.show', $post) }}">{{ $post->title }}</a></td>
                <td>{{ $post->category?->name }}</td>
                <td>{{ $post->statusLabel() }}</td>
                <td>{{ $post->published_at->format('d/m/Y') }}</td>
                <td class="actions">
                    <a class="btn" href="{{ route('post.edit', $post) }}">Sửa</a>
                    <form action="{{ route('post.destroy', $post) }}" method="POST" class="inline-form"
                        onsubmit="return confirm('Chuyển bài viết này vào thùng rác?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endif

@endsection