@extends('layouts.master')

@section('title', 'Danh mục | Quản trị')

@section('main-class', 'main-page')

@section('content')

<h2>Quản trị: Danh mục</h2>
@include('admin._nav')

<div class="page-actions">
    <a class="btn" href="{{ route('admin.categories.create') }}">+ Thêm danh mục</a>
</div>

<div class="table-wrap">
    <table class="post-table">
        <thead>
            <tr>
                <th>Tên</th>
                <th>Slug</th>
                <th>Số bài</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>{{ $category->slug }}</td>
                <td>{{ $category->posts_count }}</td>
                <td class="actions">
                    <a class="btn" href="{{ route('admin.categories.edit', $category) }}">Sửa</a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-form"
                        onsubmit="return confirm('Xóa danh mục này?')">
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

@endsection