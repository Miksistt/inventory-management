@extends('layouts.admin')

@section('title', $product->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ $product->name }}</h1>
        <div>
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Редактировать
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Назад
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table">
                <tr>
                    <th style="width: 200px;">ID</th>
                    <td>{{ $product->id }}</td>
                </tr>
                <tr>
                    <th>Артикул</th>
                    <td>{{ $product->sku }}</td>
                </tr>
                <tr>
                    <th>Название</th>
                    <td>{{ $product->name }}</td>
                </tr>
                <tr>
                    <th>Описание</th>
                    <td>{{ $product->description ?: '-' }}</td>
                </tr>
                <tr>
                    <th>Категория</th>
                    <td>{{ $product->category->name }}</td>
                </tr>
                <tr>
                    <th>Единица измерения</th>
                    <td>{{ $product->unit->name }} ({{ $product->unit->abbreviation }})</td>
                </tr>
                <tr>
                    <th>Текущий остаток</th>
                    <td>
                        @if($product->stock_quantity == 0)
                            <span class="badge bg-danger">{{ $product->stock_quantity }}</span>
                        @elseif($product->min_stock && $product->stock_quantity <= $product->min_stock)
                            <span class="badge bg-warning">{{ $product->stock_quantity }}</span>
                        @else
                            {{ $product->stock_quantity }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Минимальный остаток</th>
                    <td>{{ $product->min_stock ?: 'Не задан' }}</td>
                </tr>
                <tr>
                    <th>Создан</th>
                    <td>{{ $product->created_at->format('d.m.Y H:i:s') }}</td>
                </tr>
                <tr>
                    <th>Обновлён</th>
                    <td>{{ $product->updated_at->format('d.m.Y H:i:s') }}</td>
                </tr>
            </table>

            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Удалить товар?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Удалить товар
                </button>
            </form>
        </div>
    </div>
@endsection
