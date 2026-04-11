@extends('layouts.admin')

@section('title', $category->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ $category->name }}</h1>
        <div>
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Редактировать
            </a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Назад
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table">
                <tr>
                    <th>ID</th>
                    <td>{{ $category->id }}</td>
                </tr>
                <tr>
                    <th>Название</th>
                    <td>{{ $category->name }}</td>
                </tr>
                <tr>
                    <th>Описание</th>
                    <td>{{ $category->description ?: 'Нет описания' }}</td>
                </tr>
                <tr>
                    <th>Создана</th>
                    <td>{{ $category->created_at->format('d.m.Y H:i:s') }}</td>
                </tr>
                <tr>
                    <th>Обновлена</th>
                    <td>{{ $category->updated_at->format('d.m.Y H:i:s') }}</td>
                </tr>
            </table>

            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Удалить категорию?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Удалить категорию
                </button>
            </form>
        </div>
    </div>
@endsection
