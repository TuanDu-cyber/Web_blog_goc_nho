@extends('layouts.master')

@section('title', 'Quên mật khẩu | Góc Nhỏ')

@section('main-class', 'main-page')

@section('content')

<h2>Quên mật khẩu</h2>

<div class="form-container">
    <form action="{{ route('password.email') }}" method="POST">
        @csrf

        <label for="email">Email đã đăng ký</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Email..">
        @error('email')
        <p class="error">{{ $message }}</p>
        @enderror

        <input type="submit" value="Gửi liên kết cấp lại mật khẩu">
    </form>

    <p><a href="{{ route('login') }}">Quay lại đăng nhập</a></p>
</div>

@endsection