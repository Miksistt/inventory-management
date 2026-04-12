@extends('layouts.admin')

@section('title', $supplier->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ $supplier->name }}</h1>
        <div>
            <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Редактировать
            </a>
            <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Назад
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table">
                <tr>
                    <th>ID</th>
                    <td>{{ $supplier->id }}</td>
                </tr>
                <tr>
                    <th>Название</th>
                    <td>{{ $supplier->name }}</td>
                </tr>
                <tr>
                    <th>Контактное лицо</th>
                    <td>{{ $supplier->contact_person ?: '-' }}</td>
                </tr>
                <tr>
                    <th>Телефон</th>
                    <td>{{ $supplier->phone ?: '-' }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $supplier->email ?: '-' }}</td>
                </tr>
                <tr>
                    <th>Адрес</th>
                    <td>{{ $supplier->address ?: '-' }}</td>
                </tr>
                <tr>
                    <th>Создан</th>
                    <td>{{ $supplier->created_at->format('d.m.Y H:i:s') }}</td>
                </tr>
                <tr>
                    <th>Обновлён</th>
                    <td>{{ $supplier->updated_at->format('d.m.Y H:i:s') }}</td>
                </tr>
            </table>

            <form action="{{ route('admin.suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Удалить поставщика?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Удалить поставщика
                </button>
            </form>
        </div>
    </div>
@endsection
