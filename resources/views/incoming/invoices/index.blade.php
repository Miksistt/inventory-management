@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Приходные накладные</h3>
            <a href="{{ route('incoming.invoices.create') }}" class="btn btn-success">
                + Новая накладная
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Номер</th>
                    <th>Дата</th>
                    <th>Поставщик</th>
                    <th>Создал</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @forelse($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ $invoice->invoice_date->format('d.m.Y') }}</td>
                        <td>{{ $invoice->supplier->name }}</td>
                        <td>{{ $invoice->user->name }}</td>
                        <td>{{ number_format($invoice->total_amount, 2, ',', ' ') }} ₽</td>
                        <td>
                            <x-invoice-status :status="$invoice->status" />
                        </td>
                        <td>
                            <a href="{{ route('incoming.invoices.show', $invoice) }}" class="btn btn-sm btn-outline-primary">
                                Просмотр
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Нет приходных накладных</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $invoices->links() }}
    </div>
@endsection
