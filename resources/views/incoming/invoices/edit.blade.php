@extends('layouts.app')

@section('content')
    <div class="container">
        <h4 class="mb-4">Редактирование накладной №{{ $invoice->invoice_number }}</h4>

        <form action="{{ route('incoming.invoices.update', $invoice) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Номер накладной</label>
                    <input type="text" class="form-control" value="{{ $invoice->invoice_number }}" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Дата <span class="text-danger">*</span></label>
                    <input type="date" name="invoice_date" class="form-control @error('invoice_date') is-invalid @enderror"
                           value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}" required>
                    @error('invoice_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Поставщик <span class="text-danger">*</span></label>
                    <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                        <option value="">— Выберите поставщика —</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" @selected(old('supplier_id', $invoice->supplier_id) == $s->id)>{{ $s->name }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Комментарий</label>
                <textarea name="comment" class="form-control" rows="2">{{ old('comment', $invoice->comment) }}</textarea>
            </div>

            <h5>Позиции накладной</h5>
            @error('items')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            @foreach($invoice->items as $index => $item)
                <div class="row mb-2">
                    <div class="col-md-5">
                        <select name="items[{{ $index }}][product_id]" class="form-select" required>
                            <option value="">— Выберите товар —</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" @selected($item->product_id == $p->id)>{{ $p->sku }} — {{ $p->name }} ({{ $p->unit->abbreviation }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="items[{{ $index }}][quantity]" class="form-control" placeholder="Кол-во" min="0.01" step="0.01" value="{{ $item->quantity }}" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="items[{{ $index }}][unit_price]" class="form-control" placeholder="Цена" min="0" step="0.01" value="{{ $item->unit_price }}" required>
                    </div>
                </div>
            @endforeach

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                <a href="{{ route('incoming.invoices.show', $invoice) }}" class="btn btn-outline-secondary">Отмена</a>
            </div>
        </form>
    </div>
@endsection
