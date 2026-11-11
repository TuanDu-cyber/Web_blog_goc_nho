@extends('layouts.master')

@section('title', 'Bình luận | Quản trị')

@section('main-class', 'main-page')

@section('content')

<h2>Quản trị: Bình luận</h2>
@include('admin._nav')

@if ($comments->isEmpty())
<p>Chưa có bình luận nào.</p>
@else
<div class="table-wrap">
    <table class="post-table">
        <thead>
            <tr>
                <th>Người viết</th>
                <th>Nội dung</th>
                <th>Bài viết</th>
                <th>Thời gian</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($comments as $comment)
            <tr>
                <td>{{ $comment->user->name }}</td>
                <td>{{ Str::limit($comment->content, 80) }}</td>
                <td>
                    @if ($comment->post)
                    <a href="{{ route('article.show', $comment->post) }}">{{ Str::limit($comment->post->title, 30) }}</a>
                    @else
                    (bài đã bị xóa)
                    @endif
                </td>
                <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                <td class="actions">
                    <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" class="inline-form"
                        onsubmit="return confirm('Xóa bình luận này?')">
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

{{ $comments->links() }}

@endsection