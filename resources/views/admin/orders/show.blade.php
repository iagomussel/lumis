@extends('layouts.admin')

@section('title', 'Detalhes do Pedido #' . $order->order_number)

@section('header-actions')
    <div class="flex space-x-2">
        <a href="{{ route('admin.orders.print', $order) }}" class="btn-outline-primary" target="_blank">
            <i class="ti ti-printer mr-2"></i>
            Imprimir
        </a>
        @if(!in_array($order->status, ['delivered', 'cancelled']))
            <a href="{{ route('admin.orders.edit', $order) }}" class="btn">
                <i class="ti ti-edit mr-2"></i>
                Editar
            </a>
        @endif
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Informações Principais -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Cabeçalho do Pedido -->
        <div class="card">
            <div class="card-body">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $order->order_number }}</h2>
                        <p class="text-gray-500">Criado em {{ $order->created_at->format('d/m/Y \à\s H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <div class="mb-2">
                            @switch($order->status)
                                @case('pending')
                                    <span class="inline-flex px-3 py-1 text-sm font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                        Pendente
                                    </span>
                                    @break
                                @case('confirmed')
                                    <span class="inline-flex px-3 py-1 text-sm font-medium bg-blue-100 text-blue-800 rounded-full">
                                        Confirmado
                                    </span>
                                    @break
                                @case('processing')
                                    <span class="inline-flex px-3 py-1 text-sm font-medium bg-purple-100 text-purple-800 rounded-full">
                                        Processando
                                    </span>
                                    @break
                                @case('shipped')
                                    <span class="inline-flex px-3 py-1 text-sm font-medium bg-indigo-100 text-indigo-800 rounded-full">
                                        Enviado
                                    </span>
                                    @break
                                @case('delivered')
                                    <span class="inline-flex px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full">
                                        Entregue
                                    </span>
                                    @break
                                @case('cancelled')
                                    <span class="inline-flex px-3 py-1 text-sm font-medium bg-red-100 text-red-800 rounded-full">
                                        Cancelado
                                    </span>
                                    @break
                            @endswitch
                        </div>
                        <div>
                            @switch($order->payment_status)
                                @case('pending')
                                    <span class="inline-flex px-3 py-1 text-sm font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                        Pagamento Pendente
                                    </span>
                                    @break
                                @case('paid')
                                    <span class="inline-flex px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full">
                                        Pago
                                    </span>
                                    @break
                                @case('refunded')
                                    <span class="inline-flex px-3 py-1 text-sm font-medium bg-orange-100 text-orange-800 rounded-full">
                                        Reembolsado
                                    </span>
                                    @break
                                @case('cancelled')
                                    <span class="inline-flex px-3 py-1 text-sm font-medium bg-red-100 text-red-800 rounded-full">
                                        Pagamento Cancelado
                                    </span>
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>

                <!-- Ações Rápidas -->
                @if(!in_array($order->status, ['delivered', 'cancelled']))
                    <div class="flex space-x-2 mb-6">
                        @if($order->payment_status !== 'paid')
                            <form method="POST" action="{{ route('admin.orders.mark-as-paid', $order) }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="btn bg-green-600 hover:bg-green-700"
                                        onclick="return confirm('Marcar pedido como pago?')">
                                    <i class="ti ti-credit-card mr-2"></i>
                                    Marcar como Pago
                                </button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('admin.orders.cancel', $order) }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="btn bg-red-600 hover:bg-red-700"
                                    onclick="return confirm('Tem certeza que deseja cancelar este pedido?')">
                                <i class="ti ti-x mr-2"></i>
                                Cancelar Pedido
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <!-- Itens do Pedido -->
        <div class="card">
            <div class="card-body">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Itens do Pedido</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 font-medium text-gray-700">Produto</th>
                                <th class="text-center py-3 px-4 font-medium text-gray-700">Qtd</th>
                                <th class="text-right py-3 px-4 font-medium text-gray-700">Preço Unit.</th>
                                <th class="text-right py-3 px-4 font-medium text-gray-700">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr class="border-b border-gray-100">
                                    <td class="py-3 px-4">
                                        <div class="font-medium text-gray-900">{{ $item->product_name }}</div>
                                        <div class="text-xs text-gray-500">SKU: {{ $item->product_sku }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-center">{{ $item->quantity }}</td>
                                    <td class="py-3 px-4 text-right">{{ $item->formatted_unit_price }}</td>
                                    <td class="py-3 px-4 text-right font-medium">{{ $item->formatted_total_price }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Totais -->
                <div class="mt-6 border-t border-gray-200 pt-4">
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span>{{ $order->formatted_subtotal }}</span>
                        </div>
                        
                        @if($order->discount > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Desconto:</span>
                                <span class="text-red-600">-R$ {{ number_format($order->discount, 2, ',', '.') }}</span>
                            </div>
                        @endif

                        @if($order->shipping > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Frete:</span>
                                <span>R$ {{ number_format($order->shipping, 2, ',', '.') }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-lg font-semibold border-t pt-2">
                            <span>Total:</span>
                            <span>{{ $order->formatted_total }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($order->notes)
            <!-- Observações -->
            <div class="card">
                <div class="card-body">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Observações</h3>
                    <p class="text-gray-700">{{ $order->notes }}</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Informações do Cliente -->
        <div class="card">
            <div class="card-body">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Cliente</h3>
                
                @if($order->customer)
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Nome</label>
                            <p class="text-gray-900">{{ $order->customer->name }}</p>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium text-gray-500">{{ $order->customer->type === 'PF' ? 'CPF' : 'CNPJ' }}</label>
                            <p class="text-gray-900">{{ $order->customer->document }}</p>
                        </div>
                        
                        @if($order->customer->email)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Email</label>
                                <p class="text-gray-900">{{ $order->customer->email }}</p>
                            </div>
                        @endif
                        
                        @if($order->customer->phone)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Telefone</label>
                                <p class="text-gray-900">{{ $order->customer->phone }}</p>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-gray-500">Venda sem cadastro de cliente</p>
                @endif
            </div>
        </div>

        <!-- Informações de Pagamento -->
        <div class="card">
            <div class="card-body">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pagamento</h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Forma de Pagamento</label>
                        <p class="text-gray-900">
                            @switch($order->payment_method)
                                @case('cash') Dinheiro @break
                                @case('card') Cartão @break
                                @case('pix') PIX @break
                                @case('bank_transfer') Transferência Bancária @break
                                @default {{ $order->payment_method }}
                            @endswitch
                        </p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Status do Pagamento</label>
                        <p class="text-gray-900">
                            @switch($order->payment_status)
                                @case('pending') Pendente @break
                                @case('paid') Pago @break
                                @case('refunded') Reembolsado @break
                                @case('cancelled') Cancelado @break
                                @default {{ $order->payment_status }}
                            @endswitch
                        </p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Total</label>
                        <p class="text-xl font-bold text-gray-900">{{ $order->formatted_total }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informações do Vendedor -->
        <div class="card">
            <div class="card-body">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Vendedor</h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Nome</label>
                        <p class="text-gray-900">{{ $order->user->name }}</p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Email</label>
                        <p class="text-gray-900">{{ $order->user->email }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Datas Importantes -->
        <div class="card">
            <div class="card-body">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Histórico</h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Criado em</label>
                        <p class="text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    @if($order->shipped_at)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Enviado em</label>
                            <p class="text-gray-900">{{ $order->shipped_at->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif
                    
                    @if($order->delivered_at)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Entregue em</label>
                            <p class="text-gray-900">{{ $order->delivered_at->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Última atualização</label>
                        <p class="text-gray-900">{{ $order->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 