@extends('layouts.master')

@section('title', 'Đăng nhập | Góc Nhỏ')

@section('main-class', 'main-page')

@section('content')

<h2>Đăng nhập</h2>

<div class="form-container">
    <form action="{{ route('login') }}" method="POST">
        @csrf

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Email..">
        @error('email')
        <p class="error">{{ $message }}</p>
        @enderror

        <label for="password">Mật khẩu</label>
        <input type="password" id="password" name="password" placeholder="Mật khẩu..">
        @error('password')
        <p class="error">{{ $message }}</p>
        @enderror

        <label class="remember">
            <input type="checkbox" name="remember"> Ghi nhớ đăng nhập
        </label>

        <input type="submit" value="Đăng nhập">
    </form>

    <p><a href="{{ route('password.request') }}">Quên mật khẩu?</a></p>
    <p>Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký</a></p>
</div>

@endsection