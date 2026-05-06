@extends('layouts.admin')

@section('title', 'Единицы измерения')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Единицы измерения</h1>
        <a href="{{ route('admin.units.create') }}" class="btn btn-primary">
            Добавить единицу
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Сокращение</th>
                        <th>Дата создания</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($units as $unit)
                        <tr>
                            <td>{{ $unit->id }}</td>
                            <td>{{ $unit->name }}</td>
                            <td>{{ $unit->abbreviation }}</td>
                            <td>{{ $unit->created_at->format('d.m.Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.units.show', $unit) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.units.edit', $unit) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.units.destroy', $unit) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить единицу измерения?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Единицы измерения не найдены</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $units->links() }}
            </div>
        </div>
    </div>
@endsection
