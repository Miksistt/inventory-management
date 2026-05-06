<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Вход — {{ config('app.name', 'Inventory') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-dark min-vh-100 d-flex align-items-center">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="text-center mb-4">
                <i class="bi bi-box-seam text-white" style="font-size: 3rem;"></i>
                <h2 class="text-white mt-2 fw-bold">Inventory</h2>
                <p class="text-secondary">Система управления складом</p>
            </div>

            <div class="card shadow">
                <div class="card-body p-4">
                    <h5 class="card-title text-center mb-4">Вход в систему</h5>

                    @if(session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" required autofocus>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Пароль</label>
                            <input type="password" id="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   required>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input"
                                   id="remember_me" name="remember">
                            <label class="form-check-label" for="remember_me">
                                Запомнить меня
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Войти
                        </button>

                        @if(Route::has('password.request'))
                            <div class="text-center mt-3">
                                <a href="{{ route('password.request') }}" class="text-muted small">
                                    Забыли пароль?
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <p class="text-center text-secondary small mt-3">
                © {{ date('Y') }} Inventory Management System
            </p>
        </div>
    </div>
</div>

</body>
</html>
