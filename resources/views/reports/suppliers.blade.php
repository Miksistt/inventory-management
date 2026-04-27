@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Отчёт: По поставщикам</h3>
        </div>

        <form method="GET" action="{{ route('reports.suppliers') }}" class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Дата с</label>
                        <input type="date" name="from" class="form-control" value="{{ $from }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Дата по</label>
                        <input type="date" name="to" class="form-control" value="{{ $to }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">Применить</button>
                        <a href="{{ route('reports.suppliers') }}" class="btn btn-outline-secondary">Сбросить</a>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                <tr>
                    <th>Поставщик</th>
                    <th>Контактное лицо</th>
                    <th class="text-center">Накладных</th>
                    <th class="text-end">Сумма поставок</th>
                </tr>
                </thead>
                <tbody>
                @forelse($suppliers as $supplier)
                    <tr>
                        <td>{{ $supplier->name }}</td>
                        <td>{{ $supplier->contact_person ?? '—' }}</td>
                        <td class="text-center">{{ $supplier->invoices_count }}</td>
                        <td class="text-end">
                            {{ number_format($supplier->total_amount ?? 0, 2, '.', ' ') }} ₽
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Нет данных</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
