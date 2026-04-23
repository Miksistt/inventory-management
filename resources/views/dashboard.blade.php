@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="mb-4">Панель управления</h3>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Товаров в системе</h5>
                        <p class="card-text fs-2 fw-bold">{{ $stats['products_count'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <h5 class="card-title">Критический остаток</h5>
                        <p class="card-text fs-2 fw-bold">{{ $stats['low_stock_count'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Приходов сегодня</h5>
                        <p class="card-text fs-2 fw-bold">{{ $stats['invoices_today'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        @can('manage-inventory')
            <div class="mb-4">
                <a href="{{ route('incoming.invoices.create') }}" class="btn btn-success me-2">
                    + Приходная накладная
                </a>
            </div>
        @endcan

        <div class="row">
            <div class="col-md-6">
                <h5>Последние приходные накладные</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                        <tr>
                            <th>Номер</th>
                            <th>Поставщик</th>
                            <th>Статус</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($recentIncoming as $inv)
                            <tr>
                                <td>
                                    <a href="{{ route('incoming.invoices.show', $inv) }}">
                                        {{ $inv->invoice_number }}
                                    </a>
                                </td>
                                <td>{{ $inv->supplier->name ?? '—' }}</td>
                                <td><x-invoice-status :status="$inv->status" /></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">
                                    Нет приходных накладных
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-6">
                <h5>Товары с критическим остатком</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                        <tr>
                            <th>Товар</th>
                            <th>Остаток</th>
                            <th>Ед.</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($criticalProducts as $p)
                            <tr class="{{ $p->stock_quantity == 0 ? 'table-danger' : 'table-warning' }}">
                                <td>
                                    <a href="{{ route('products.show', $p) }}" class="text-decoration-none">
                                        {{ $p->name }}
                                    </a>
                                </td>
                                <td class="fw-bold">{{ $p->stock_quantity }}</td>
                                <td>{{ $p->unit->abbreviation ?? '' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">
                                    Нет товаров с критическим остатком
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
