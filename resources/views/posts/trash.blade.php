@extends('layouts.master')

@section('title', 'Thùng rác | Góc Nhỏ')

@section('main-class', 'main-page')

@section('content')

<h2>Thùng rác</h2>

<div class="page-actions">
    <a class="btn" href="{{ route('post.index') }}">&larr; Quay lại bài viết của tôi</a>
</div>

@if ($posts->isEmpty())

<p>Thùng rác trống.</p>

@else

<div class="table-wrap">
    <table class="post-table">
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Tiêu đề</th>
                <th>Ngày xóa</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
            <tr>
                <td><img src="{{ asset($post->image) }}" alt="{{ $post->title }}"></td>
                <td>{{ $post->title }}</td>
                <td>{{ $post->deleted_at->format('d/m/Y H:i') }}</td>
                <td class="actions">
                    <form action="{{ route('post.restore', $post) }}" method="POST" class="inline-form">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn">Khôi phục</button>
                    </form>
                    <form action="{{ route('post.force-delete', $post) }}" method="POST" class="inline-form"
                        onsubmit="return confirm('Xóa vĩnh viễn? Hành động này không thể hoàn tác!')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete">Xóa vĩnh viễn</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endif

@endsection