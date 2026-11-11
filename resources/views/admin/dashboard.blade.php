@extends('layouts.master')

@section('title', 'Thống kê | Quản trị')

@section('main-class', 'main-page')

@section('content')

<h2>Quản trị: Thống kê</h2>
@include('admin._nav')

<div class="stat-grid">
    <div class="stat-box"><strong>{{ $memberCount }}</strong> Thành viên</div>
    <div class="stat-box"><strong>{{ $postCount }}</strong> Bài đã đăng</div>
    <div class="stat-box"><strong>{{ $commentCount }}</strong> Bình luận</div>
</div>

<h2>5 bài nhiều bình luận nhất</h2>

@if ($topPosts->isEmpty())
<p>Chưa có bài viết nào.</p>
@else
<div class="table-wrap">
    <table class="post-table">
        <thead>
            <tr>
                <th>Tiêu đề</th>
                <th>Bình luận</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($topPosts as $post)
            <tr>
                <td><a href="{{ route('article.show', $post) }}">{{ $post->title }}</a></td>
                <td>{{ $post->comments_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@endsection