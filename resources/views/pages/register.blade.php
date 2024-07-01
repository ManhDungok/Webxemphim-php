@extends('layouts.app_web')

@section('content')
<div class="login">

    <form method="POST" action="{{ route('web.post-register') }}" class="login__form">
        @csrf
        <h1 class="login__title">Register</h1>
        <div class="login__content">

            <div class="login__box">
                <input value="{{ old('name') }}" type="text" name="name" class="login__input" id="login-name" placeholder=" " required>
                <label for="login-name" class="login__label">Họ và tên</label>
                {!! $errors->first('name', '<span class="text-danger mt-3 d-block">:message</span>') !!}
            </div>

            <div class="login__box">
                <input value="{{ old('email') }}" type="text" name="email" class="login__input" id="login-email" placeholder=" " required>
                <label for="login-email" class="login__label">Email</label>
                {!! $errors->first('email', '<span class="text-danger mt-3 d-block">:message</span>') !!}
            </div>

            <div class="login__box">
                <input value="{{ old('username') }}" type="text" name="username" class="login__input" id="login-username" placeholder=" " required>
                <label for="login-username" class="login__label">Tên đăng nhập</label>
                {!! $errors->first('username', '<span class="text-danger mt-3 d-block">:message</span>') !!}
            </div>

            <div class="login__box">
                <input value="{{ old('password') }}" type="password" name="password" class="login__input" id="login-password" placeholder=" " required>
                <label for="login-password" class="login__label">Mật khẩu</label>
                {!! $errors->first('password', '<span class="text-danger mt-3 d-block">:message</span>') !!}
            </div>

            <div class="login__box">
                <input value="{{ old('password_confirmation') }}" type="password" name="password_confirmation" class="login__input" id="login-confirm-pass" placeholder=" "
                    required>
                <label for="login-confirm-pass" class="login__label">Nhập lại mật khẩu</label>
                {!! $errors->first('password_confirmation', '<span class="text-danger mt-3 d-block">:message</span>') !!}
            </div>
        </div>
        <button type="submit" class="login__button">Register</button>

        <div class="login__line"></div>
        <a href="#!" class="login__button login__google">
            <img src="{{ asset('static/assets/icons/google.svg') }}" class="google-link" alt="">
            Or sign in with Google</a>

        <!-- Liên kết Đăng nhập -->
        <p class="login__register">
            I have an account ? <a href="{{ route('web.login') }}">Login</a>
        </p>
    </form>
</div>

@endsection
