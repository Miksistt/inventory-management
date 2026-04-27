@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Отчёт: Остатки товаров</h3>
            <div class="text-muted">
                Всего товаров: <strong>{{ $totalItems }}</strong> |
                Нулевой остаток: <strong class="text-danger">{{ $zeroStock }}</strong>
            </div>
        </div>

        <form method="GET" action="{{ route('reports.stock') }}" class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Категория</label>
                        <select name="category" class="form-select">
                            <option value="">Все категории</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Статус остатка</label>
                        <select name="stock_filter" class="form-select">
                            <option value="">Все</option>
                            <option value="zero" {{ request('stock_filter') === 'zero' ? 'selected' : '' }}>
                                Нулевой остаток
                            </option>
                            <option value="low" {{ request('stock_filter') === 'low' ? 'selected' : '' }}>
                                Ниже минимума
                            </option>
                            <option value="normal" {{ request('stock_filter') === 'normal' ? 'selected' : '' }}>
                                В норме
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">Применить</button>
                        <a href="{{ route('reports.stock') }}" class="btn btn-outline-secondary">Сбросить</a>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                <tr>
                    <th>Артикул</th>
                    <th>Товар</th>
                    <th>Категория</th>
                    <th class="text-center">Остаток</th>
                    <th class="text-center">Мин. остаток</th>
                    <th>Ед.</th>
                    <th>Статус</th>
                </tr>
                </thead>
                <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td class="text-center fw-bold
                        {{ $product->stock_quantity == 0 ? 'text-danger' :
                           ($product->isLowStock() ? 'text-warning' : 'text-success') }}">
                            {{ $product->stock_quantity }}
                        </td>
                        <td class="text-center">{{ $product->min_stock ?? '—' }}</td>
                        <td>{{ $product->unit->abbreviation }}</td>
                        <td>
                            @if($product->stock_quantity == 0)
                                <span class="badge bg-danger">Нет</span>
                            @elseif($product->isLowStock())
                                <span class="badge bg-warning text-dark">Мало</span>
                            @else
                                <span class="badge bg-success">Норма</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Нет товаров</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
