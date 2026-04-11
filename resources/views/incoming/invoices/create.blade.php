@extends('layouts.app')

@section('content')
    <div class="container">
        <h4 class="mb-4">Новая приходная накладная</h4>

        <form action="{{ route('incoming.invoices.store') }}" method="POST">
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


            <div class="row mb-2">
                <div class="col-md-5">
                    <select name="items[0][product_id]" class="form-select" required>
                        <option value="">— Выберите товар —</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->sku }} — {{ $p->name }} ({{ $p->unit->abbreviation }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="items[0][quantity]" class="form-control" placeholder="Кол-во" min="0.01" step="0.01" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="items[0][unit_price]" class="form-control" placeholder="Цена" min="0" step="0.01" required>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Сохранить черновик</button>
                <a href="{{ route('incoming.invoices.index') }}" class="btn btn-outline-secondary">Отмена</a>
            </div>
        </form>
    </div>
@endsection
