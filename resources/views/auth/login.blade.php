@extends('layouts.clear')
@section('content')
    <section class="padding-y bg-login-img vh-100">
        <div class="container">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="card shadow mx-auto" style="max-width:600px; margin-top:150px;">
                <div class="card-body">
                    <h2 class="text-center text-primary text-uppercase mb-4">
                        {{ __('b2b-sklad.com') }}
                    </h2>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-4">
                            <input id="email" class="form-control @error('email') is-invalid @enderror"
                                placeholder="{{ __('l.email') }}" type="email" name="email" value="{{ old('email') }}"
                                required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <input id="password" class="form-control @error('password') is-invalid @enderror"
                                placeholder="{{ __('l.password') }}" type="password" name="password"
                                autocomplete="current-password" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-center">
                            <div>
                                <input type="checkbox" class="custom-control-input" id="remember_me" name="remember_me">
                                <label class="custom-control-label pr-2" for="remember_me">{{ __('l.remember_me') }}</label>
                                <button type="submit" class="ms-3 btn  btn-primary">{{ __('l.login') }}</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    @if (Route::has('password.request'))
                        <p class="text-center mb-2"><a
                                href="{{ route('password.request') }}">{{ __('l.repair_password') }}</a></p>
                                <hr>
                    @endif
                    
			<span class="fw-bold text-danger b">1. Якщо не вдалося увійти в систему, оберіть відновлення пароля.<br>
2. Якщо після відновлення пароля виникли проблеми зверніться до служби підтримки.<br>
Tel, Viber, Telegram +38 (067) 322-32-25<br>
Пн-Пт з 9:00 до 18:00</span>
                </div>
            
            </div>
        </div>
    </section>
@endsection
