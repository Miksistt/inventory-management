@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>История операций</h3>
            <span class="text-muted">Всего: {{ $operations->total() }}</span>
        </div>

        <form method="GET" action="{{ route('history.index') }}" class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Тип накладной</label>
                        <select name="type" class="form-select">
                            <option value="all" {{ $filters['type'] === 'all' ? 'selected' : '' }}>Все</option>
                            <option value="incoming" {{ $filters['type'] === 'incoming' ? 'selected' : '' }}>Приходные</option>
                            <option value="outgoing" {{ $filters['type'] === 'outgoing' ? 'selected' : '' }}>Расходные</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Статус</label>
                        <select name="status" class="form-select">
                            <option value="" {{ !$filters['status'] ? 'selected' : '' }}>Все</option>
                            <option value="draft" {{ $filters['status'] === 'draft' ? 'selected' : '' }}>Черновик</option>
                            <option value="posted" {{ $filters['status'] === 'posted' ? 'selected' : '' }}>Проведена</option>
                            <option value="cancelled" {{ $filters['status'] === 'cancelled' ? 'selected' : '' }}>Отменена</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Дата с</label>
                        <input type="date" name="from" class="form-control"
                               value="{{ $filters['from'] }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Дата по</label>
                        <input type="date" name="to" class="form-control"
                               value="{{ $filters['to'] }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Поиск по номеру</label>
                        <input type="text" name="search" class="form-control"
                               placeholder="IN-202604-000001"
                               value="{{ $filters['search'] }}">
                    </div>
                </div>
                <div class="mt-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Применить</button>
                    <a href="{{ route('history.index') }}" class="btn btn-outline-secondary">Сбросить</a>
                </div>
            </div>
        </form>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                <tr>
                    <th>Тип</th>
                    <th>Номер</th>
                    <th>Дата</th>
                    <th>Контрагент</th>
                    <th>Сотрудник</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($operations as $op)
                    <tr>
                        <td>
                        <span class="badge {{ $op['type_badge'] }}">
                            {{ $op['type_label'] }}
                        </span>
                        </td>
                        <td>{{ $op['invoice_number'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($op['invoice_date'])->format('d.m.Y') }}</td>
                        <td>{{ $op['counterparty'] }}</td>
                        <td>{{ $op['user'] }}</td>
                        <td>
                            @if($op['amount'] !== null)
                                {{ number_format($op['amount'], 2, '.', ' ') }} ₽
                            @else
                                —
                            @endif
                        </td>
                        <td><x-invoice-status :status="$op['status']" /></td>
                        <td>
                            <a href="{{ $op['route'] }}" class="btn btn-sm btn-outline-primary">
                                Просмотр
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            Нет операций по заданным фильтрам
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $operations->links() }}
    </div>
@endsection
