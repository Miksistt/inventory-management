@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Приходная накладная №{{ $invoice->invoice_number }}</h4>
            <div>
                <a href="{{ route('incoming.invoices.index') }}" class="btn btn-outline-secondary">← К списку</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Статус:</strong>
                        @php
                            $badge = match($invoice->status) {
                                'draft' => 'bg-secondary',
                                'posted' => 'bg-success',
                                'cancelled' => 'bg-danger',
                                default => 'bg-light text-dark'
                            };
                            $text = match($invoice->status) {
                                'draft' => 'Черновик',
                                'posted' => 'Проведена',
                                'cancelled' => 'Отменена',
                                default => $invoice->status
                            };
                        @endphp
                        <span class="badge {{ $badge }}">{{ $text }}</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Дата:</strong> {{ $invoice->invoice_date->format('d.m.Y') }}
                    </div>
                    <div class="col-md-3">
                        <strong>Поставщик:</strong> {{ $invoice->supplier->name }}
                    </div>
                    <div class="col-md-3">
                        <strong>Создал:</strong> {{ $invoice->user->name }}
                    </div>
                </div>
                @if($invoice->comment)
                    <div class="row mt-3">
                        <div class="col-12">
                            <strong>Комментарий:</strong> {{ $invoice->comment }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="mb-4">
            @can('update', $invoice)
                <a href="{{ route('incoming.invoices.edit', $invoice) }}" class="btn btn-warning">
                    ✏️ Редактировать
                </a>
            @endcan

            @if($invoice->isDraft())
                @can('post', $invoice)
                    <form action="{{ route('incoming.invoices.post', $invoice) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-success" onclick="return confirm('Провести накладную? Остатки будут изменены.')">
                            ✅ Провести
                        </button>
                    </form>
                @endcan
            @endif

            @if($invoice->isPosted())
                @can('cancel', $invoice)
                    <form action="{{ route('incoming.invoices.cancel', $invoice) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-danger" onclick="return confirm('Отменить накладную? Остатки будут уменьшены.')">
                            ❌ Отменить
                        </button>
                    </form>
                @endcan
            @endif

            @can('delete', $invoice)
                <form action="{{ route('incoming.invoices.destroy', $invoice) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger" onclick="return confirm('Удалить накладную?')">
                        🗑️ Удалить
                    </button>
                </form>
            @endcan
        </div>
        <h5>Позиции накладной</h5>
        <div class="table-responsive mb-4">
            <table class="table table-bordered">
                <thead class="table-light">
                <tr>
                    <th>Товар</th>
                    <th>Количество</th>
                    <th>Цена за ед.</th>
                    <th>Сумма</th>
                </tr>
                </thead>
                <tbody>
                @foreach($invoice->items as $item)
                    <tr>
                        <td>{{ $item->product->name }} ({{ $item->product->unit->abbreviation }})</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->unit_price, 2, ',', ' ') }} ₽</td>
                        <td>{{ number_format($item->total, 2, ',', ' ') }} ₽</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Итого:</th>
                    <th>{{ number_format($invoice->total_amount, 2, ',', ' ') }} ₽</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
