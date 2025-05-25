@extends('layouts.admin')

@section('title', 'Detalhes do Fornecedor')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Fornecedor: {{ $supplier->name }}</h3>
                <div>
                    <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="btn btn-warning">Editar</a>
                    <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">Voltar</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Informações da Empresa</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Nome:</strong></td>
                            <td>{{ $supplier->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Pessoa de Contato:</strong></td>
                            <td>{{ $supplier->contact_person ?? 'Não informado' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ $supplier->email ?? 'Não informado' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Telefone:</strong></td>
                            <td>{{ $supplier->phone ?? 'Não informado' }}</td>
                        </tr>
                        <tr>
                            <td><strong>CNPJ:</strong></td>
                            <td>{{ $supplier->cnpj ?? 'Não informado' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="badge badge-{{ $supplier->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ $supplier->status == 'active' ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Endereço</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Endereço:</strong></td>
                            <td>{{ $supplier->address ?? 'Não informado' }}</td>
                        </tr>
                        <tr>
                            <td><strong>CEP:</strong></td>
                            <td>{{ $supplier->zip_code ?? 'Não informado' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Cidade:</strong></td>
                            <td>{{ $supplier->city ?? 'Não informado' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Estado:</strong></td>
                            <td>{{ $supplier->state ?? 'Não informado' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Data de Cadastro:</strong></td>
                            <td>{{ $supplier->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Última Atualização:</strong></td>
                            <td>{{ $supplier->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($supplier->notes)
                <div class="row mt-4">
                    <div class="col-12">
                        <h5>Observações</h5>
                        <div class="card bg-light">
                            <div class="card-body">
                                {{ $supplier->notes }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Future: Add purchase history, statistics, etc. --}}
            {{--
            <div class="row mt-4">
                <div class="col-12">
                    <h5>Histórico de Compras</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Referência</th>
                                    <th>Data</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($supplier->purchases as $purchase)
                                    <tr>
                                        <td>{{ $purchase->reference_no }}</td>
                                        <td>{{ $purchase->purchase_date->format('d/m/Y') }}</td>
                                        <td>{{ $purchase->status }}</td>
                                        <td>R$ {{ number_format($purchase->total_amount, 2, ',', '.') }}</td>
                                        <td>
                                            <a href="{{ route('admin.purchases.show', $purchase) }}" class="btn btn-sm btn-info">Ver</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Nenhuma compra encontrada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
        .badge-success { background-color: #28a745; color: white; }
        .badge-secondary { background-color: #6c757d; color: white; }
    </style>
@stop

@section('js')
@stop 