@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Каталог</a></li>
                <li class="breadcrumb-item active">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h1>{{ $product->name }}</h1>
                        <h6 class="text-muted mb-3">Артикул: {{ $product->sku }}</h6>

                        <table class="table">
                            <tr>
                                <th style="width: 200px;">Категория</th>
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
                                        <span class="badge bg-danger fs-6">{{ $product->stock_quantity }} {{ $product->unit->abbreviation }}</span>
                                    @elseif($product->min_stock && $product->stock_quantity <= $product->min_stock)
                                        <span class="badge bg-warning fs-6">{{ $product->stock_quantity }} {{ $product->unit->abbreviation }}</span>
                                    @else
                                        <span class="badge bg-success fs-6">{{ $product->stock_quantity }} {{ $product->unit->abbreviation }}</span>
                                    @endif
                                </td>
                            </tr>
                            @if($product->min_stock)
                                <tr>
                                    <th>Минимальный остаток</th>
                                    <td>{{ $product->min_stock }} {{ $product->unit->abbreviation }}</td>
                                </tr>
                            @endif
                        </table>

                        @if($product->description)
                            <h5 class="mt-4">Описание</h5>
                            <p>{{ $product->description }}</p>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <i class="bi bi-info-circle"></i> Информация
                            </div>
                            <div class="card-body">
                                <p><strong>ID товара:</strong> {{ $product->id }}</p>
                                <p><strong>Добавлен:</strong> {{ $product->created_at->format('d.m.Y') }}</p>
                                <p><strong>Обновлён:</strong> {{ $product->updated_at->format('d.m.Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Назад к каталогу
                </a>
            </div>
        </div>
    </div>
@endsection
