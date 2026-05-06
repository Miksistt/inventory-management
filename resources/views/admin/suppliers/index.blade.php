@extends('layouts.admin')

@section('title', 'Поставщики')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Поставщики</h1>
        <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">
            Добавить поставщика
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
                        <th>Контактное лицо</th>
                        <th>Телефон</th>
                        <th>Email</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->id }}</td>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->contact_person ?: '-' }}</td>
                            <td>{{ $supplier->phone ?: '-' }}</td>
                            <td>{{ $supplier->email ?: '-' }}</td>
                            <td>
                                <a href="{{ route('admin.suppliers.show', $supplier) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.suppliers.destroy', $supplier) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить поставщика?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Поставщики не найдены</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $suppliers->links() }}
            </div>
        </div>
    </div>
@endsection
