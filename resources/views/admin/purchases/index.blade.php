@extends('layouts.admin')

@section('title', 'Gestão de Compras')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Lista de Compras</h3>
                <a href="{{ route('admin.purchases.create') }}" class="btn btn-primary">Nova Compra</a>
            </div>
        </div>
        <div class="card-body">
            <!-- Filtros -->
            <form method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Buscar por referência ou fornecedor..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">Todos os Status</option>
                            @if(isset($statuses))
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="supplier_id" class="form-control">
                            <option value="">Todos os Fornecedores</option>
                            @foreach($suppliers as $id => $name)
                                <option value="{{ $id }}" {{ request('supplier_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-outline-primary">Filtrar</button>
                    </div>
                </div>
            </form>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Número</th>
                            <th>Fornecedor</th>
                            <th>Data de Entrega</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($purchases as $purchase)
                            <tr>
                                <td>{{ $purchase->id }}</td>
                                <td>{{ $purchase->purchase_number }}</td>
                                <td>{{ $purchase->supplier->name ?? 'N/A' }}</td>
                                <td>{{ $purchase->delivery_date ? \Carbon\Carbon::parse($purchase->delivery_date)->format('d/m/Y') : 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-{{ $purchase->status == 'received' ? 'success' : ($purchase->status == 'cancelled' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($purchase->status) }}
                                    </span>
                                </td>
                                <td>R$ {{ number_format($purchase->total, 2, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('admin.purchases.show', $purchase) }}" class="btn btn-sm btn-info">Ver</a>
                                    <a href="{{ route('admin.purchases.edit', $purchase) }}" class="btn btn-sm btn-warning">Editar</a>
                                    @if($purchase->status !== 'received')
                                        <form action="{{ route('admin.purchases.destroy', $purchase) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Tem certeza que deseja excluir esta compra?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Nenhuma compra encontrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $purchases->links() }}
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .badge {
            padding: 0.25em 0.6em;
            font-size: 75%;
            font-weight: 700;
            border-radius: 0.25rem;
        }
        .badge-success { background-color: #28a745; color: white; }
        .badge-warning { background-color: #ffc107; color: #212529; }
        .badge-danger { background-color: #dc3545; color: white; }
    </style>
@stop

@section('js')
@stop 