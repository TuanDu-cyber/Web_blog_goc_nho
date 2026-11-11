@extends('layouts.master')

@section('title', $post->title . ' | Góc Nhỏ')

@section('main-class', 'main-article')

@section('content')

<article class="article">
    <h2>{{ $post->title }}</h2>
    <p>
        <small>
            {{ $post->published_at->format('d/m/Y') }}
            @if ($post->category)
            · <a href="{{ route('home', ['category' => $post->category->slug]) }}">{{ $post->category->name }}</a>
            @endif
        </small><br>
        Viết bởi {{ $post->user->name }}
    </p>
    @if ($post->tags->isNotEmpty())
    <p>
        @foreach ($post->tags as $tag)
        <a class="tag" href="{{ route('home', ['tag' => $tag->slug]) }}">#{{ $tag->name }}</a>
        @endforeach
    </p>
    @endif

    @can('update', $post)
    <div class="owner-actions">
        <a class="btn" href="{{ route('post.edit', $post) }}">Sửa bài viết</a>
        <form action="{{ route('post.destroy', $post) }}" method="POST" class="inline-form"
            onsubmit="return confirm('Chuyển bài viết này vào thùng rác?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-delete">Xóa</button>
        </form>
    </div>
    @endcan

    <div class="headline" style="background-image: url('{{ asset($post->image) }}')"></div>

    @foreach (preg_split('/\n\s*\n/', $post->content) as $paragraph)
    <p class="{{ $loop->first ? 'intro-text' : '' }}">{{ $paragraph }}</p>
    @endforeach

    <figure>
        <img src="{{ asset($post->image) }}" alt="{{ $post->title }}">
        <figcaption>{{ $post->title }}</figcaption>
    </figure>

    <div class="comments" id="comments">
        <p><a href="#comments">Bình luận ({{ $comments->count() }})</a></p>
        <h4>Bình luận mới nhất</h4>

        @forelse ($comments as $comment)
        <p class="comment-name">{{ $comment->user->name }}</p>
        <div class="comment-text">
            <p>{!! nl2br(e($comment->content)) !!}</p>
            <div class="comment-meta">
                <small>{{ $comment->created_at->format('d/m/Y H:i') }}</small>
                @can('delete', $comment)
                <form action="{{ route('comment.destroy', $comment) }}" method="POST" class="inline-form"
                    onsubmit="return confirm('Xóa bình luận này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-link">Xóa</button>
                </form>
                @endcan
            </div>
        </div>
        @empty
        <p>Chưa có bình luận nào, hãy là người đầu tiên!</p>
        @endforelse
    </div>

    <h3>Để lại bình luận</h3>

    @if ($post->status !== 'published')
    <p>Bài viết chưa được đăng nên chưa thể bình luận.</p>
    @else
    @auth
    <div class="form-container">
        <form action="{{ route('comment.store', $post) }}" method="POST">
            @csrf

            <label for="comment">Bình luận (đăng với tên {{ Auth::user()->name }})</label>
            <textarea id="comment" name="content" style="height:150px" placeholder="Viết bình luận..">{{ old('content') }}</textarea>
            @error('content')
            <p class="error">{{ $message }}</p>
            @enderror

            <input type="submit" value="Gửi bình luận">
        </form>
    </div>
    @else
    <p>Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để bình luận.</p>
    @endauth
    @endif
</article>

<aside class="sidebar" role="complementary">
    <h2>Bài mới nhất</h2>
    @foreach ($latest as $item)
    <a href="{{ route('article.show', $item) }}">
        <div class="sidebar-headline">
            <h3>{{ $item->title }}</h3>
            <p class="exact-time">{{ $item->published_at->format('d/m') }}</p>
        </div>
    </a>
    @endforeach

    @if ($related->isNotEmpty())
    <h2>Cùng chuyên mục</h2>
    @foreach ($related as $item)
    <a href="{{ route('article.show', $item) }}">
        <div class="sidebar-headline">
            <h3>{{ $item->title }}</h3>
            <p class="exact-time">{{ $item->published_at->format('d/m') }}</p>
        </div>
    </a>
    @endforeach
    @endif
</aside>

@endsection