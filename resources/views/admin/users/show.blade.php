@extends('layouts.admin')

@section('title', $user->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ $user->name }}</h1>
        <div>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Редактировать
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Назад
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table">
                <tr>
                    <th style="width: 200px;">ID</th>
                    <td>{{ $user->id }}</td>
                </tr>
                <tr>
                    <th>Имя</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <th>Роль</th>
                    <td>
                        @if($user->role === 'admin')
                            <span class="badge bg-danger">Администратор</span>
                        @elseif($user->role === 'storekeeper')
                            <span class="badge bg-warning">Кладовщик</span>
                        @else
                            <span class="badge bg-info">Менеджер</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Email подтверждён</th>
                    <td>{{ $user->email_verified_at ? $user->email_verified_at->format('d.m.Y H:i:s') : 'Не подтверждён' }}</td>
                </tr>
                <tr>
                    <th>Создан</th>
                    <td>{{ $user->created_at->format('d.m.Y H:i:s') }}</td>
                </tr>
                <tr>
                    <th>Обновлён</th>
                    <td>{{ $user->updated_at->format('d.m.Y H:i:s') }}</td>
                </tr>
            </table>

            @if($user->id !== auth()->id())
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Удалить пользователя?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Удалить пользователя
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
