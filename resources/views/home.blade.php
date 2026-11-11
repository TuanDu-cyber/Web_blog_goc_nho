@extends('layouts.master')

@section('title', 'Trang chủ | Góc Nhỏ')

@section('content')

{{-- Thanh tìm kiếm và lọc --}}
<form class="search-bar" action="{{ route('home') }}" method="GET">
    <input type="search" name="q" value="{{ $q }}" placeholder="Tìm bài viết..." maxlength="100">

    <select name="category">
        <option value="">Tất cả chuyên mục</option>
        @foreach ($categories as $category)
        <option value="{{ $category->slug }}" @selected($categorySlug===$category->slug)>{{ $category->name }}</option>
        @endforeach
    </select>

    <select name="tag">
        <option value="">Tất cả tag</option>
        @foreach ($tags as $tag)
        <option value="{{ $tag->slug }}" @selected($tagSlug===$tag->slug)>{{ $tag->name }}</option>
        @endforeach
    </select>

    <button type="submit" class="btn">Tìm</button>

    @if ($isFiltering)
    <a class="btn btn-clear" href="{{ route('home') }}">Xóa bộ lọc</a>
    @endif
</form>

@error('q')
<p class="search-error">{{ $message }}</p>
@enderror

@if ($posts->isEmpty())
<p class="empty-note">{{ $isFiltering ? 'Không tìm thấy bài viết phù hợp.' : 'Chưa có bài viết nào.' }}</p>
@endif

@if ($hero)
<div class="top-news">

    <section class="latest-news">
        <div class="headline" style="background-image: url('{{ asset($hero->image) }}')">
            <p>Mới nhất</p>
            <a href="{{ route('article.show', $hero) }}">
                <h1>{{ $hero->title }}</h1>
            </a>
        </div>
    </section>

    <section class="top-stories">
        <h2>Bài viết nổi bật</h2>
        @foreach ($featured as $post)
        <div class="headline headline-{{ $loop->iteration }}" style="background-image: url('{{ asset($post->image) }}')">
            <a href="{{ route('article.show', $post) }}">
                <h1>{{ $post->title }}</h1>
            </a>
        </div>
        @endforeach
    </section>

</div>
@endif

@if ($list->isNotEmpty())
<section class="more" id="more">
    <h2>
        @if ($isFiltering)
        Tìm thấy {{ $posts->total() }} bài viết
        @if ($q !== '') · từ khóa “{{ $q }}” @endif
        @if ($activeCategory) · chuyên mục {{ $activeCategory->name }} @endif
        @if ($activeTag) · tag #{{ $activeTag->name }} @endif
        @else
        Tất cả bài viết
        @endif
    </h2>

    @foreach ($list as $post)
    @include('partials.story', ['post' => $post])
    @endforeach
</section>
@endif

{{ $posts->links() }}

@endsection