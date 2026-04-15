@extends('layouts.app')

@section('content')
    <div class="container">
        <h4 class="mb-4">Новая приходная накладная</h4>

        <form action="{{ route('incoming.invoices.store') }}" method="POST" id="invoiceForm">
            @csrf

            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Номер накладной</label>
                    <input type="text" class="form-control" value="{{ $number }}" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Дата <span class="text-danger">*</span></label>
                    <input type="date" name="invoice_date" class="form-control @error('invoice_date') is-invalid @enderror"
                           value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                    @error('invoice_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Поставщик <span class="text-danger">*</span></label>
                    <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                        <option value="">— Выберите поставщика —</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" @selected(old('supplier_id') == $s->id)>{{ $s->name }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Комментарий</label>
                <textarea name="comment" class="form-control" rows="2">{{ old('comment') }}</textarea>
            </div>

            <h5>Позиции накладной</h5>
            @error('items')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div id="items-container"></div>

            <button type="button" class="btn btn-outline-secondary mb-3" id="add-item">
                + Добавить позицию
            </button>

            <div class="text-end fs-5 mb-3">
                Итого: <strong><span id="total-sum">0.00</span> ₽</strong>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Сохранить черновик</button>
                <a href="{{ route('incoming.invoices.index') }}" class="btn btn-outline-secondary">Отмена</a>
            </div>
        </form>
    </div>

    <script>
        const products = @json($products);
        let itemIndex = 0;

        document.getElementById('add-item').addEventListener('click', addRow);

        function addRow() {
            const container = document.getElementById('items-container');
            const row = document.createElement('div');
            row.className = 'row mb-2 align-items-center item-row';
            row.innerHTML = `
        <div class="col-md-5">
            <select name="items[${itemIndex}][product_id]" class="form-select product-select" required>
                <option value="">— Выберите товар —</option>
                ${products.map(p =>
                `<option value="${p.id}">${p.sku} — ${p.name} (${p.unit?.abbreviation || ''})</option>`
            ).join('')}
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" name="items[${itemIndex}][quantity]"
                   class="form-control qty" placeholder="Кол-во" min="0.01" step="0.01" required>
        </div>
        <div class="col-md-2">
            <input type="number" name="items[${itemIndex}][unit_price]"
                   class="form-control price" placeholder="Цена ₽" min="0" step="0.01" required>
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control line-total bg-light" placeholder="Сумма" readonly>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger btn-sm remove-item">✕</button>
        </div>`;
            container.appendChild(row);
            bindRowEvents(row);
            itemIndex++;
        }

        function bindRowEvents(row) {
            const qty = row.querySelector('.qty');
            const price = row.querySelector('.price');
            const total = row.querySelector('.line-total');

            const calc = () => {
                const t = (parseFloat(qty.value) || 0) * (parseFloat(price.value) || 0);
                total.value = t.toFixed(2);
                updateGrandTotal();
            };

            qty.addEventListener('input', calc);
            price.addEventListener('input', calc);
            row.querySelector('.remove-item').addEventListener('click', () => {
                row.remove();
                updateGrandTotal();
            });
        }

        function updateGrandTotal() {
            let sum = 0;
            document.querySelectorAll('.line-total').forEach(el => sum += parseFloat(el.value) || 0);
            document.getElementById('total-sum').textContent = sum.toFixed(2);
        }


        addRow();
    </script>
@endsection
