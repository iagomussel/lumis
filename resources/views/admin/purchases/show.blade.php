@extends('layouts.admin')

@section('title', 'Detalhes da Compra')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Compra: {{ $purchase->purchase_number }}</h3>
                <div>
                    <a href="{{ route('admin.purchases.edit', $purchase) }}" class="btn btn-warning">Editar</a>
                    <a href="{{ route('admin.purchases.index') }}" class="btn btn-secondary">Voltar</a>
                </div>
            </div>
        </div>
        <div class="card-body">
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
                            <td><strong>Data de Criação:</strong></td>
                            <td>{{ $purchase->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Última Atualização:</strong></td>
                            <td>{{ $purchase->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Resumo Financeiro</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Valor Total:</strong></td>
                            <td class="text-right"><strong>R$ {{ number_format($purchase->total, 2, ',', '.') }}</strong></td>
                        </tr>
                        {{-- Future fields when implemented:
                        <tr>
                            <td><strong>Subtotal:</strong></td>
                            <td class="text-right">R$ {{ number_format($purchase->subtotal ?? 0, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Impostos:</strong></td>
                            <td class="text-right">R$ {{ number_format($purchase->tax_amount ?? 0, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Frete:</strong></td>
                            <td class="text-right">R$ {{ number_format($purchase->shipping_cost ?? 0, 2, ',', '.') }}</td>
                        </tr>
                        --}}
                    </table>
                </div>
            </div>

            @if($purchase->notes)
                <div class="row mt-4">
                    <div class="col-12">
                        <h5>Observações</h5>
                        <div class="card bg-light">
                            <div class="card-body">
                                {{ $purchase->notes }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Items section - will be implemented when Purchase items are ready --}}
            <div class="row mt-4">
                <div class="col-12">
                    <h5>Itens da Compra</h5>
                    <div class="alert alert-info">
                        <p><strong>Funcionalidade em Desenvolvimento:</strong> A listagem detalhada de itens da compra será implementada quando o sistema de itens de compra estiver completo.</p>
                        <p>Esta seção incluirá:</p>
                        <ul>
                            <li>Lista de produtos comprados</li>
                            <li>Quantidades e preços unitários</li>
                            <li>Subtotais por item</li>
                            <li>Informações de recebimento</li>
                        </ul>
                    </div>
                    
                    {{-- Future implementation:
                    @if($purchase->items && $purchase->items->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>SKU</th>
                                        <th>Quantidade</th>
                                        <th>Preço Unitário</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($purchase->items as $item)
                                        <tr>
                                            <td>{{ $item->product->name ?? 'N/A' }}</td>
                                            <td>{{ $item->product->sku ?? 'N/A' }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>R$ {{ number_format($item->unit_cost, 2, ',', '.') }}</td>
                                            <td>R$ {{ number_format($item->sub_total, 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Nenhum item encontrado para esta compra.</p>
                    @endif
                    --}}
                </div>
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