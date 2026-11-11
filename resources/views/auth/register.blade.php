@extends('layouts.master')

@section('title', 'Đăng ký | Góc Nhỏ')

@section('main-class', 'main-page')

@section('content')

<h2>Đăng ký tài khoản</h2>

<div class="form-container">
    <form action="{{ route('register') }}" method="POST">
        @csrf

        <label for="name">Họ tên</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Họ tên..">
        @error('name')
        <p class="error">{{ $message }}</p>
        @enderror

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Email..">
        @error('email')
        <p class="error">{{ $message }}</p>
        @enderror

        <label for="password">Mật khẩu</label>
        <input type="password" id="password" name="password" placeholder="Tối thiểu 8 ký tự">
        @error('password')
        <p class="error">{{ $message }}</p>
        @enderror

        <label for="password_confirmation">Nhập lại mật khẩu</label>
        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Nhập lại mật khẩu">

        <input type="submit" value="Đăng ký">
    </form>

    <p>Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a></p>
</div>

@endsection