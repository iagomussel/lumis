@extends('layouts.admin')

@section('title', 'Detalhes da Compra')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Compra: {{ $purchase->purchase_number }}</h3>
                <div>
                    @if($purchase->status !== 'received' && $purchase->status !== 'cancelled')
                        <form action="{{ route('admin.purchases.mark-received', $purchase) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Tem certeza que deseja marcar esta compra como recebida? O estoque será atualizado automaticamente.')">
                                <i class="ti ti-check"></i> Marcar como Recebida
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('admin.purchases.edit', $purchase) }}" class="btn btn-warning btn-sm">
                        <i class="ti ti-edit"></i> Editar
                    </a>
                    <a href="{{ route('admin.purchases.index') }}" class="btn btn-secondary btn-sm">
                        <i class="ti ti-arrow-left"></i> Voltar
                    </a>
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
                            <td><strong>Usuário:</strong></td>
                            <td>{{ $purchase->user->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Data de Entrega:</strong></td>
                            <td>{{ $purchase->delivery_date ? $purchase->delivery_date->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="badge badge-{{ $purchase->status == 'received' ? 'success' : ($purchase->status == 'cancelled' ? 'danger' : 'warning') }}">
                                    @switch($purchase->status)
                                        @case('pending') Pendente @break
                                        @case('ordered') Pedido Feito @break
                                        @case('received') Recebido @break
                                        @case('cancelled') Cancelado @break
                                        @default {{ ucfirst($purchase->status) }}
                                    @endswitch
                                </span>
                            </td>
                        </tr>
                        @if($purchase->payment_method)
                        <tr>
                            <td><strong>Forma de Pagamento:</strong></td>
                            <td>
                                @switch($purchase->payment_method)
                                    @case('cash') Dinheiro @break
                                    @case('credit_card') Cartão de Crédito @break
                                    @case('debit_card') Cartão de Débito @break
                                    @case('bank_transfer') Transferência @break
                                    @case('check') Cheque @break
                                    @case('on_delivery') Na Entrega @break
                                    @default {{ $purchase->payment_method }}
                                @endswitch
                            </td>
                        </tr>
                        @endif
                        @if($purchase->payment_terms)
                        <tr>
                            <td><strong>Condições de Pagamento:</strong></td>
                            <td>{{ $purchase->payment_terms }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td><strong>Data de Criação:</strong></td>
                            <td>{{ $purchase->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @if($purchase->received_at)
                        <tr>
                            <td><strong>Data de Recebimento:</strong></td>
                            <td>{{ $purchase->received_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Resumo Financeiro</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Subtotal:</strong></td>
                            <td class="text-end">R$ {{ number_format($purchase->subtotal ?? 0, 2, ',', '.') }}</td>
                        </tr>
                        @if($purchase->discount > 0)
                        <tr>
                            <td><strong>Desconto:</strong></td>
                            <td class="text-end text-success">- R$ {{ number_format($purchase->discount, 2, ',', '.') }}</td>
                        </tr>
                        @endif
                        @if($purchase->tax > 0)
                        <tr>
                            <td><strong>Taxa/Impostos:</strong></td>
                            <td class="text-end">R$ {{ number_format($purchase->tax, 2, ',', '.') }}</td>
                        </tr>
                        @endif
                        @if($purchase->shipping > 0)
                        <tr>
                            <td><strong>Frete:</strong></td>
                            <td class="text-end">R$ {{ number_format($purchase->shipping, 2, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr class="table-primary">
                            <td><strong>Valor Total:</strong></td>
                            <td class="text-end"><strong>R$ {{ number_format($purchase->total, 2, ',', '.') }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Items section -->
            <div class="row mt-4">
                <div class="col-12">
                    <h5>Itens da Compra</h5>
                    @if($purchase->items && $purchase->items->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Produto</th>
                                        <th>SKU</th>
                                        <th>Qtd. Pedida</th>
                                        <th>Qtd. Recebida</th>
                                        <th>Preço Unitário</th>
                                        <th>Total</th>
                                        @if($purchase->status === 'received')
                                            <th>Status</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($purchase->items as $item)
                                        <tr>
                                            <td>
                                                <strong>{{ $item->product_name }}</strong>
                                                @if($item->product)
                                                    <br><small class="text-muted">{{ $item->product->name }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $item->product_sku }}</td>
                                            <td>{{ number_format($item->quantity_ordered, 0, ',', '.') }}</td>
                                            <td>
                                                {{ number_format($item->quantity_received, 0, ',', '.') }}
                                                @if($item->quantity_pending > 0)
                                                    <br><small class="text-warning">Pendente: {{ $item->quantity_pending }}</small>
                                                @endif
                                            </td>
                                            <td>R$ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                                            <td>R$ {{ number_format($item->total_price, 2, ',', '.') }}</td>
                                            @if($purchase->status === 'received')
                                                <td>
                                                    @if($item->quantity_received >= $item->quantity_ordered)
                                                        <span class="badge badge-success">Completo</span>
                                                    @else
                                                        <span class="badge badge-warning">Parcial</span>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                        @if($item->notes)
                                            <tr>
                                                <td colspan="{{ $purchase->status === 'received' ? 7 : 6 }}" class="bg-light">
                                                    <small><strong>Obs:</strong> {{ $item->notes }}</small>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="{{ $purchase->status === 'received' ? 5 : 4 }}">Total dos Itens:</th>
                                        <th>R$ {{ number_format($purchase->items->sum('total_price'), 2, ',', '.') }}</th>
                                        @if($purchase->status === 'received')
                                            <th></th>
                                        @endif
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle"></i>
                            <strong>Nenhum item encontrado</strong> para esta compra.
                        </div>
                    @endif
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

            <!-- Actions -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        <div>
                            @if($purchase->status !== 'cancelled')
                                <form action="{{ route('admin.purchases.destroy', $purchase) }}" method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir esta compra? Esta ação não pode ser desfeita.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="ti ti-trash"></i> Excluir Compra
                                    </button>
                                </form>
                            @endif
                        </div>
                        <div>
                            @if($purchase->status === 'received')
                                <button class="btn btn-success btn-sm" disabled>
                                    <i class="ti ti-check"></i> Compra Recebida
                                </button>
                            @endif
                        </div>
                    </div>
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
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
@stop 