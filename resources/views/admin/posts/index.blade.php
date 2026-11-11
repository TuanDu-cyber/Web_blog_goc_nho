@extends('layouts.master')

@section('title', 'Bài viết | Quản trị')

@section('main-class', 'main-page')

@section('content')

<h2>Quản trị: Bài viết</h2>
@include('admin._nav')

<div class="table-wrap">
    <table class="post-table">
        <thead>
            <tr>
                <th>Tiêu đề</th>
                <th>Tác giả</th>
                <th>Chuyên mục</th>
                <th>Trạng thái</th>
                <th>Bình luận</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
            <tr>
                <td><a href="{{ route('article.show', $post) }}">{{ Str::limit($post->title, 40) }}</a></td>
                <td>{{ $post->user->name }}</td>
                <td>{{ $post->category?->name }}</td>
                <td>{{ $post->statusLabel() }}</td>
                <td>{{ $post->comments_count }}</td>
                <td class="actions">
                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline-form"
                        onsubmit="return confirm('Xóa vĩnh viễn bài viết này? Không thể khôi phục.')">
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

{{ $posts->links() }}

@endsection