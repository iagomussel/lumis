@extends('layouts.admin')

@section('title', 'Gestão de Fornecedores')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Lista de Fornecedores</h3>
                <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">Novo Fornecedor</a>
            </div>
        </div>
        <div class="card-body">
            <!-- Filtros -->
            <form method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Buscar por nome, email ou telefone..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">Todos os Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativo</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>
                    <div class="col-md-2">
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
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Cidade</th>
                            <th>Status</th>
                            <th>Data de Cadastro</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suppliers as $supplier)
                            <tr>
                                <td>{{ $supplier->id }}</td>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->email ?? 'N/A' }}</td>
                                <td>{{ $supplier->phone ?? 'N/A' }}</td>
                                <td>{{ $supplier->city ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-{{ $supplier->status == 'active' ? 'success' : 'secondary' }}">
                                        {{ $supplier->status == 'active' ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                                <td>{{ $supplier->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.suppliers.show', $supplier) }}" class="btn btn-sm btn-info">Ver</a>
                                    <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="btn btn-sm btn-warning">Editar</a>
                                    <form action="{{ route('admin.suppliers.toggle-status', $supplier) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-{{ $supplier->status == 'active' ? 'secondary' : 'success' }}">
                                            {{ $supplier->status == 'active' ? 'Desativar' : 'Ativar' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.suppliers.destroy', $supplier) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Tem certeza que deseja excluir este fornecedor?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Nenhum fornecedor encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{-- {{ $suppliers->links() }} --}}
                {{-- Pagination will be added when controller is properly implemented --}}
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
        .badge-secondary { background-color: #6c757d; color: white; }
    </style>
@stop

@section('js')
@stop 