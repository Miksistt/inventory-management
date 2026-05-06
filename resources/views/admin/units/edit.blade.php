@extends('layouts.admin')

@section('title', 'Редактирование единицы измерения')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Редактирование: {{ $unit->name }}</h1>
        <a href="{{ route('admin.units.index') }}" class="btn btn-secondary">
            Назад
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.units.update', $unit) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Название *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $unit->name) }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="abbreviation" class="form-label">Сокращение *</label>
                    <input type="text" class="form-control @error('abbreviation') is-invalid @enderror" id="abbreviation" name="abbreviation" value="{{ old('abbreviation', $unit->abbreviation) }}" required>
                    @error('abbreviation')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Обновить</button>
            </form>
        </div>
    </div>
@endsection
