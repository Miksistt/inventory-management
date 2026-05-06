@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Отчёт: Расходные накладные</h3>
            <div class="text-muted">
                Накладных: <strong>{{ $totalCount }}</strong> |
                Позиций отгружено: <strong>{{ $totalItems }}</strong>
            </div>
        </div>

        <form method="GET" action="{{ route('reports.outgoing') }}" class="card mb-4">
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
                        <a href="{{ route('reports.outgoing') }}" class="btn btn-outline-secondary">Сбросить</a>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                <tr>
                    <th>Номер</th>
                    <th>Дата</th>
                    <th>Получатель</th>
                    <th>Сотрудник</th>
                    <th class="text-center">Кол-во позиций</th>
                </tr>
                </thead>
                <tbody>
                @forelse($invoices as $invoice)
                    <tr>
                        <td>
                            <a href="{{ route('outgoing.invoices.show', $invoice) }}">
                                {{ $invoice->invoice_number }}
                            </a>
                        </td>
                        <td>{{ $invoice->invoice_date->format('d.m.Y') }}</td>
                        <td>{{ $invoice->recipient }}</td>
                        <td>{{ $invoice->user->name ?? '—' }}</td>
                        <td class="text-center">{{ $invoice->items->count() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Нет проведённых расходных накладных за период
                        </td>
                    </tr>
                @endforelse
                </tbody>
                @if($totalCount > 0)
                    <tfoot class="table-light">
                    <tr>
                        <td colspan="4" class="fw-bold">Итого накладных</td>
                        <td class="text-center fw-bold">{{ $totalCount }}</td>
                    </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
@endsection
