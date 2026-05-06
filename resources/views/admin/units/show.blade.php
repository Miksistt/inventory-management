@extends('layouts.admin')

@section('title', $unit->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ $unit->name }}</h1>
        <div>
            <a href="{{ route('admin.units.edit', $unit) }}" class="btn btn-warning">
                Редактировать
            </a>
            <a href="{{ route('admin.units.index') }}" class="btn btn-secondary">
                Назад
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table">
                <tr>
                    <th>ID</th>
                    <td>{{ $unit->id }}</td>
                </tr>
                <tr>
                    <th>Название</th>
                    <td>{{ $unit->name }}</td>
                </tr>
                <tr>
                    <th>Сокращение</th>
                    <td>{{ $unit->abbreviation }}</td>
                </tr>
                <tr>
                    <th>Создана</th>
                    <td>{{ $unit->created_at->format('d.m.Y H:i:s') }}</td>
                </tr>
                <tr>
                    <th>Обновлена</th>
                    <td>{{ $unit->updated_at->format('d.m.Y H:i:s') }}</td>
                </tr>
            </table>

            <form action="{{ route('admin.units.destroy', $unit) }}" method="POST" onsubmit="return confirm('Удалить единицу измерения?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    Удалить
                </button>
            </form>
        </div>
    </div>
@endsection
