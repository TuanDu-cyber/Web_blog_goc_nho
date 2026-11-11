@extends('layouts.master')

@section('title', 'Sửa danh mục | Quản trị')

@section('main-class', 'main-page')

@section('content')

<h2>Quản trị: Sửa danh mục</h2>
@include('admin._nav')

@include('admin.categories._form', ['action' => route('admin.categories.update', $category), 'button' => 'Lưu'])

@endsection