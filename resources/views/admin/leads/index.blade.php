@extends('layouts.admin')

@section('title', 'Gestão de Leads')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Lista de Leads</h3>
                <a href="{{ route('admin.leads.create') }}" class="btn btn-primary">Novo Lead</a>
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
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
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

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Empresa</th>
                            <th>Status</th>
                            <th>Origem</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($leads as $lead)
                            <tr>
                                <td>{{ $lead->id }}</td>
                                <td>{{ $lead->name }}</td>
                                <td>{{ $lead->email ?? 'N/A' }}</td>
                                <td>{{ $lead->phone ?? 'N/A' }}</td>
                                <td>{{ $lead->company_name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-{{ $lead->status == 'won' ? 'success' : ($lead->status == 'lost' ? 'danger' : 'primary') }}">
                                        {{ ucfirst($lead->status) }}
                                    </span>
                                </td>
                                <td>{{ $lead->source ?? 'N/A' }}</td>
                                <td>{{ $lead->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.leads.show', $lead) }}" class="btn btn-sm btn-info">Ver</a>
                                    <a href="{{ route('admin.leads.edit', $lead) }}" class="btn btn-sm btn-warning">Editar</a>
                                    <form action="{{ route('admin.leads.destroy', $lead) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Tem certeza que deseja excluir este lead?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Nenhum lead encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $leads->links() }}
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
        .badge-primary { background-color: #007bff; color: white; }
        .badge-success { background-color: #28a745; color: white; }
        .badge-danger { background-color: #dc3545; color: white; }
    </style>
@stop

@section('js')
@stop 