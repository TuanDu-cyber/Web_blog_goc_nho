@extends('layouts.master')

@section('title', 'Thêm danh mục | Quản trị')

@section('main-class', 'main-page')

@section('content')

<h2>Quản trị: Thêm danh mục</h2>
@include('admin._nav')

@include('admin.categories._form', ['action' => route('admin.categories.store'), 'button' => 'Thêm'])

@endsection