@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Редактирование накладной №{{ $invoice->invoice_number }}</h4>
            <a href="{{ route('outgoing.invoices.show', $invoice) }}" class="btn btn-outline-secondary">
                ← Назад
            </a>
        </div>

        <form action="{{ route('outgoing.invoices.update', $invoice) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Номер накладной</label>
                            <input type="text" class="form-control"
                                   value="{{ $invoice->invoice_number }}" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Дата <span class="text-danger">*</span></label>
                            <input type="date" name="invoice_date"
                                   class="form-control @error('invoice_date') is-invalid @enderror"
                                   value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}"
                                   required>
                            @error('invoice_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Получатель <span class="text-danger">*</span></label>
                            <input type="text" name="recipient"
                                   class="form-control @error('recipient') is-invalid @enderror"
                                   value="{{ old('recipient', $invoice->recipient) }}" required>
                            @error('recipient')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Комментарий</label>
                        <textarea name="comment" class="form-control"
                                  rows="2">{{ old('comment', $invoice->comment) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Позиции накладной</h5>
                </div>
                <div class="card-body">
                    @error('items')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div id="items-container">
                        @foreach($invoice->items as $index => $item)
                            <div class="row mb-2 align-items-center item-row">
                                <div class="col-md-8">
                                    <select name="items[{{ $index }}][product_id]"
                                            class="form-select" required>
                                        <option value="">— Выберите товар —</option>
                                        @foreach($products as $p)
                                            <option value="{{ $p->id }}"
                                                {{ $item->product_id == $p->id ? 'selected' : '' }}>
                                                {{ $p->sku }} — {{ $p->name }}
                                                (остаток: {{ $p->stock_quantity }} {{ $p->unit->abbreviation }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="items[{{ $index }}][quantity]"
                                           class="form-control" placeholder="Количество"
                                           value="{{ $item->quantity }}"
                                           min="0.01" step="0.01" required>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm"
                                            onclick="this.closest('.item-row').remove()">✕</button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-outline-secondary mt-2" id="add-item">
                        + Добавить позицию
                    </button>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Сохранить</button>
                <a href="{{ route('outgoing.invoices.show', $invoice) }}"
                   class="btn btn-outline-secondary">Отмена</a>
            </div>
        </form>
    </div>

    <script>
        const products = @json($products);
        let idx = {{ $invoice->items->count() }};

        document.getElementById('add-item').addEventListener('click', addRow);

        function addRow() {
            const container = document.getElementById('items-container');
            const row = document.createElement('div');
            row.className = 'row mb-2 align-items-center item-row';
            row.innerHTML = `
        <div class="col-md-8">
            <select name="items[${idx}][product_id]" class="form-select" required>
                <option value="">— Выберите товар —</option>
                ${products.map(p =>
                `<option value="${p.id}">
                        ${p.sku} — ${p.name}
                        (остаток: ${p.stock_quantity} ${p.unit.abbreviation})
                    </option>`
            ).join('')}
            </select>
        </div>
        <div class="col-md-3">
            <input type="number" name="items[${idx}][quantity]"
                   class="form-control" placeholder="Количество"
                   min="0.01" step="0.01" required>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger btn-sm"
                    onclick="this.closest('.item-row').remove()">✕</button>
        </div>`;
            container.appendChild(row);
            idx++;
        }
    </script>
@endsection
