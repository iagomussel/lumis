@extends('layouts.admin')

@section('title', 'Detalhes do Lead')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Lead: {{ $lead->name }}</h3>
                <div>
                    <a href="{{ route('admin.leads.edit', $lead) }}" class="btn btn-warning">Editar</a>
                    <a href="{{ route('admin.leads.index') }}" class="btn btn-secondary">Voltar</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Informações Pessoais</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Nome:</strong></td>
                            <td>{{ $lead->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ $lead->email ?? 'Não informado' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Telefone:</strong></td>
                            <td>{{ $lead->phone ?? 'Não informado' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Empresa:</strong></td>
                            <td>{{ $lead->company_name ?? 'Não informado' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Informações do Lead</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="badge badge-{{ $lead->status == 'won' ? 'success' : ($lead->status == 'lost' ? 'danger' : 'primary') }}">
                                    {{ ucfirst($lead->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Origem:</strong></td>
                            <td>{{ $lead->source ?? 'Não informado' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Data de Criação:</strong></td>
                            <td>{{ $lead->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Última Atualização:</strong></td>
                            <td>{{ $lead->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($lead->notes)
                <div class="row mt-4">
                    <div class="col-12">
                        <h5>Observações</h5>
                        <div class="card bg-light">
                            <div class="card-body">
                                {{ $lead->notes }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Future: Add lead activity history, interactions, etc. --}}
            {{--
            <div class="row mt-4">
                <div class="col-12">
                    <h5>Histórico de Atividades</h5>
                    <div class="timeline">
                        <!-- Lead activity timeline would go here -->
                    </div>
                </div>
            </div>
            --}}
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