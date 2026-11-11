@extends('layouts.master')

@section('title', 'Đặt lại mật khẩu | Góc Nhỏ')

@section('main-class', 'main-page')

@section('content')

<h2>Đặt lại mật khẩu</h2>

<div class="form-container">
    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email', $email) }}" placeholder="Email..">
        @error('email')
        <p class="error">{{ $message }}</p>
        @enderror

        <label for="password">Mật khẩu mới</label>
        <input type="password" id="password" name="password" placeholder="Tối thiểu 8 ký tự">
        @error('password')
        <p class="error">{{ $message }}</p>
        @enderror

        <label for="password_confirmation">Nhập lại mật khẩu mới</label>
        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Nhập lại mật khẩu">

        <input type="submit" value="Đổi mật khẩu">
    </form>
</div>

@endsection