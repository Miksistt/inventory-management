@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Расходные накладные</h3>
            @can('create', App\Models\OutgoingInvoice::class)
                <a href="{{ route('outgoing.invoices.create') }}" class="btn btn-warning">
                    + Новая накладная
                </a>
            @endcan
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

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                <tr>
                    <th>Номер</th>
                    <th>Дата</th>
                    <th>Получатель</th>
                    <th>Создал</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @forelse($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ $invoice->invoice_date->format('d.m.Y') }}</td>
                        <td>{{ $invoice->recipient }}</td>
                        <td>{{ $invoice->user->name }}</td>
                        <td>
                            @php
                                $badge = match($invoice->status) {
                                    'draft'     => 'bg-secondary',
                                    'posted'    => 'bg-success',
                                    'cancelled' => 'bg-danger',
                                    default     => 'bg-light text-dark',
                                };
                                $text = match($invoice->status) {
                                    'draft'     => 'Черновик',
                                    'posted'    => 'Проведена',
                                    'cancelled' => 'Отменена',
                                    default     => $invoice->status,
                                };
                            @endphp
                            <span class="badge {{ $badge }}">{{ $text }}</span>
                        </td>
                        <td>
                            <a href="{{ route('outgoing.invoices.show', $invoice) }}"
                               class="btn btn-sm btn-outline-primary">
                                Просмотр
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Нет расходных накладных</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $invoices->links() }}
    </div>
@endsection
