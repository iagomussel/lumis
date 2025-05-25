@extends('layouts.admin')

@section('title', 'Editar Compra')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Editar Compra: {{ $purchase->purchase_number }}</h3>
        </div>
        <div class="card-body">
            <div class="alert alert-warning">
                <h5><i class="icon fas fa-exclamation-triangle"></i> Funcionalidade em Desenvolvimento</h5>
                <p>A funcionalidade completa de edição de compras ainda está sendo implementada.</p>
                <p>Por enquanto, você pode visualizar os detalhes da compra na página de visualização.</p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h5>Informações da Compra</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Número:</strong></td>
                            <td>{{ $purchase->purchase_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>Fornecedor:</strong></td>
                            <td>{{ $purchase->supplier->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Data de Entrega:</strong></td>
                            <td>{{ $purchase->delivery_date ? \Carbon\Carbon::parse($purchase->delivery_date)->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="badge badge-{{ $purchase->status == 'received' ? 'success' : ($purchase->status == 'cancelled' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($purchase->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Total:</strong></td>
                            <td>R$ {{ number_format($purchase->total, 2, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    @if($purchase->notes)
                        <h5>Observações</h5>
                        <div class="card bg-light">
                            <div class="card-body">
                                {{ $purchase->notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('admin.purchases.show', $purchase) }}" class="btn btn-info">Ver Detalhes Completos</a>
                <a href="{{ route('admin.purchases.index') }}" class="btn btn-secondary">Voltar para Lista</a>
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