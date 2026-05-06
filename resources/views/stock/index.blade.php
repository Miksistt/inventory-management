@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Остатки на складе</h1>

        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('stock.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Поиск по артикулу или названию" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="">Все категории</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="stock_filter" class="form-select">
                            <option value="">Все остатки</option>
                            <option value="critical" {{ request('stock_filter') == 'critical' ? 'selected' : '' }}>Критические (0)</option>
                            <option value="low" {{ request('stock_filter') == 'low' ? 'selected' : '' }}>Ниже минимума</option>
                            <option value="normal" {{ request('stock_filter') == 'normal' ? 'selected' : '' }}>В норме</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            Фильтр
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>
                                <a href="{{ route('stock.index', array_merge(request()->all(), ['sort' => 'sku', 'direction' => request('sort') == 'sku' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark">
                                    Артикул
                                    @if(request('sort') == 'sku')
                                        <i class="bi bi-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('stock.index', array_merge(request()->all(), ['sort' => 'name', 'direction' => request('sort') == 'name' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark">
                                    Название
                                    @if(request('sort') == 'name')
                                        <i class="bi bi-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Категория</th>
                            <th>
                                <a href="{{ route('stock.index', array_merge(request()->all(), ['sort' => 'stock_quantity', 'direction' => request('sort') == 'stock_quantity' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark">
                                    Остаток
                                    @if(request('sort') == 'stock_quantity')
                                        <i class="bi bi-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Ед. изм.</th>
                            <th>Мин. остаток</th>
                            <th>Статус</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($products as $product)
                            <tr class="{{ $product->getStockStatusClass() }}">
                                <td>{{ $product->sku }}</td>
                                <td>
                                    <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
                                        {{ $product->name }}
                                    </a>
                                </td>
                                <td>{{ $product->category->name }}</td>
                                <td><strong>{{ $product->stock_quantity }}</strong></td>
                                <td>{{ $product->unit->abbreviation }}</td>
                                <td>{{ $product->min_stock ?: '-' }}</td>
                                <td>
                                    @if($product->stock_quantity == 0)
                                        <span class="badge bg-danger">Критический</span>
                                    @elseif($product->min_stock && $product->stock_quantity <= $product->min_stock)
                                        <span class="badge bg-warning">Ниже минимума</span>
                                    @else
                                        <span class="badge bg-success">В норме</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Товары не найдены</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
