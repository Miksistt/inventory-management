@extends('layouts.app')
@php use Illuminate\Support\Str; @endphp

@section('content')
    <div class="container">
        <h1 class="mb-4">Каталог товаров</h1>
        ...

        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('products.index') }}" class="row g-3">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control" placeholder="Поиск по артикулу или названию" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-5">
                        <select name="category" class="form-select">
                            <option value="">Все категории</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Найти
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            @forelse($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">{{ $product->sku }}</h6>
                            <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                            <ul class="list-unstyled">
                                <li><strong>Категория:</strong> {{ $product->category->name }}</li>
                                <li>
                                    <strong>Остаток:</strong>
                                    @if($product->stock_quantity == 0)
                                        <span class="badge bg-danger">{{ $product->stock_quantity }} {{ $product->unit->abbreviation }}</span>
                                    @elseif($product->min_stock && $product->stock_quantity <= $product->min_stock)
                                        <span class="badge bg-warning">{{ $product->stock_quantity }} {{ $product->unit->abbreviation }}</span>
                                    @else
                                        <span class="badge bg-success">{{ $product->stock_quantity }} {{ $product->unit->abbreviation }}</span>
                                    @endif
                                </li>
                            </ul>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary">
                                <i class="bi bi-info-circle"></i> Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">Товары не найдены</div>
                </div>
            @endforelse
        </div>

        <div class="mt-3">
            {{ $products->links() }}
        </div>
    </div>
@endsection
