<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Inventory') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-dark min-vh-100 d-flex align-items-center">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="text-center mb-4">
                <i class="bi bi-box-seam text-white" style="font-size: 4rem;"></i>
                <h1 class="text-white mt-2 fw-bold">Inventory</h1>
                <p class="text-secondary">Система управления складом</p>
            </div>

            <div class="card shadow">
                <div class="card-body p-4">
                    @if(Auth::check())
                        <p class="text-center text-muted mb-4">
                            Вы вошли как <strong>{{ Auth::user()->name }}</strong>
                        </p>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary w-100 mb-2">
                            Перейти в систему
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary w-100">
                                Выйти
                            </button>
                        </form>
                    @else
                        <h5 class="card-title text-center mb-4">Вход в систему</h5>
                        <a href="{{ route('login') }}" class="btn btn-primary w-100 mb-2">
                            Войти
                        </a>
                    @endif
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
