@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Расходная накладная №{{ $invoice->invoice_number }}</h4>
            <a href="{{ route('outgoing.invoices.index') }}" class="btn btn-outline-secondary">
                ← К списку
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Статус:</strong>
                        <x-invoice-status :status="$invoice->status" />
                    </div>
                    <div class="col-md-3">
                        <strong>Дата:</strong> {{ $invoice->invoice_date->format('d.m.Y') }}
                    </div>
                    <div class="col-md-3">
                        <strong>Получатель:</strong> {{ $invoice->recipient }}
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

        <div class="mb-4 d-flex gap-2">
            @can('update', $invoice)
                <a href="{{ route('outgoing.invoices.edit', $invoice) }}"
                   class="btn btn-warning">
                      Редактировать
                </a>
            @endcan

            @if($invoice->isDraft())
                @can('post', $invoice)
                    <form action="{{ route('outgoing.invoices.post', $invoice) }}"
                          method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-success"
                                onclick="return confirm('Провести накладную? Остатки будут уменьшены.')">
                              Провести
                        </button>
                    </form>
                @endcan
            @endif

            @if($invoice->isPosted())
                @can('cancel', $invoice)
                    <form action="{{ route('outgoing.invoices.cancel', $invoice) }}"
                          method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-danger"
                                onclick="return confirm('Отменить накладную? Товары вернутся на склад.')">
                              Отменить
                        </button>
                    </form>
                @endcan
            @endif

            @can('delete', $invoice)
                @if($invoice->isDraft())
                    <form action="{{ route('outgoing.invoices.destroy', $invoice) }}"
                          method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger"
                                onclick="return confirm('Удалить накладную?')">
                              Удалить
                        </button>
                    </form>
                @endif
            @endcan
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Позиции накладной</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Артикул</th>
                            <th>Товар</th>
                            <th class="text-center">Количество</th>
                            <th>Ед. изм.</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($invoice->items as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->product->sku }}</td>
                                <td>{{ $item->product->name }}</td>
                                <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                <td>{{ $item->product->unit->abbreviation }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
