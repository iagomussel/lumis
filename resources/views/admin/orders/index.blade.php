@extends('layouts.admin')

@section('title', 'Gestão de Pedidos')

@section('header-actions')
    <a href="{{ route('admin.pos.index') }}" class="btn">
        <i class="ti ti-cash-register mr-2"></i>
        Novo Pedido (PDV)
    </a>
@endsection

@section('content')
<!-- Estatísticas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="card">
        <div class="card-body">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ti ti-shopping-cart text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_orders'] }}</h3>
                    <p class="text-gray-500 text-sm">Total de Pedidos</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ti ti-currency-real text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">R$ {{ number_format($stats['total_revenue'], 2, ',', '.') }}</h3>
                    <p class="text-gray-500 text-sm">Receita Total</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ti ti-clock text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['pending_orders'] }}</h3>
                    <p class="text-gray-500 text-sm">Pedidos Pendentes</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ti ti-check text-purple-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['confirmed_orders'] }}</h3>
                    <p class="text-gray-500 text-sm">Pedidos Confirmados</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-6">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                    <input 
                        type="text" 
                        id="search" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Número do pedido ou cliente..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos os status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendente</option>
                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmado</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processando</option>
                        <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Enviado</option>
                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Entregue</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>

                <div>
                    <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">Pagamento</label>
                    <select id="payment_status" name="payment_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos</option>
                        <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pendente</option>
                        <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Pago</option>
                        <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Reembolsado</option>
                        <option value="cancelled" {{ request('payment_status') === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>

                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                    <select id="customer_id" name="customer_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos os clientes</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Data Inicial</label>
                    <input 
                        type="date" 
                        id="date_from" 
                        name="date_from" 
                        value="{{ request('date_from') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Data Final</label>
                    <input 
                        type="date" 
                        id="date_to" 
                        name="date_to" 
                        value="{{ request('date_to') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="flex items-end space-x-2">
                    <button type="submit" class="btn">
                        <i class="ti ti-search mr-2"></i>
                        Filtrar
                    </button>
                    <a href="{{ route('admin.orders.index') }}" class="btn-outline-primary">
                        <i class="ti ti-x mr-2"></i>
                        Limpar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Lista de Pedidos -->
<div class="card">
    <div class="card-body">
        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Pedido</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Cliente</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Data</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Status</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Pagamento</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Total</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    <div class="font-medium text-gray-900">{{ $order->order_number }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->items->count() }} item(s)</div>
                                </td>
                                <td class="py-3 px-4">
                                    @if($order->customer)
                                        <div class="font-medium text-gray-900">{{ $order->customer->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $order->customer->document }}</div>
                                    @else
                                        <span class="text-gray-400">Venda sem cadastro</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="text-gray-900">{{ $order->created_at->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    @switch($order->status)
                                        @case('pending')
                                            <span class="inline-flex px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                                Pendente
                                            </span>
                                            @break
                                        @case('confirmed')
                                            <span class="inline-flex px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                                Confirmado
                                            </span>
                                            @break
                                        @case('processing')
                                            <span class="inline-flex px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">
                                                Processando
                                            </span>
                                            @break
                                        @case('shipped')
                                            <span class="inline-flex px-2 py-1 text-xs font-medium bg-indigo-100 text-indigo-800 rounded-full">
                                                Enviado
                                            </span>
                                            @break
                                        @case('delivered')
                                            <span class="inline-flex px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                                Entregue
                                            </span>
                                            @break
                                        @case('cancelled')
                                            <span class="inline-flex px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                                Cancelado
                                            </span>
                                            @break
                                    @endswitch
                                </td>
                                <td class="py-3 px-4">
                                    @switch($order->payment_status)
                                        @case('pending')
                                            <span class="inline-flex px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                                Pendente
                                            </span>
                                            @break
                                        @case('paid')
                                            <span class="inline-flex px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                                Pago
                                            </span>
                                            @break
                                        @case('refunded')
                                            <span class="inline-flex px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full">
                                                Reembolsado
                                            </span>
                                            @break
                                        @case('cancelled')
                                            <span class="inline-flex px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                                Cancelado
                                            </span>
                                            @break
                                    @endswitch
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-gray-900">{{ $order->formatted_total }}</div>
                                    <div class="text-xs text-gray-500">
                                        @switch($order->payment_method)
                                            @case('cash') Dinheiro @break
                                            @case('card') Cartão @break
                                            @case('pix') PIX @break
                                            @case('bank_transfer') Transferência @break
                                            @default {{ $order->payment_method }}
                                        @endswitch
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.orders.show', $order) }}" 
                                           class="text-blue-600 hover:text-blue-800" 
                                           title="Ver detalhes">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        
                                        @if(!in_array($order->status, ['delivered', 'cancelled']))
                                            <a href="{{ route('admin.orders.edit', $order) }}" 
                                               class="text-green-600 hover:text-green-800" 
                                               title="Editar">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                        @endif

                                        @if($order->payment_status !== 'paid')
                                            <form method="POST" action="{{ route('admin.orders.mark-as-paid', $order) }}" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-green-600 hover:text-green-800" 
                                                        title="Marcar como pago"
                                                        onclick="return confirm('Marcar pedido como pago?')">
                                                    <i class="ti ti-credit-card"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if(!in_array($order->status, ['delivered', 'cancelled']))
                                            <form method="POST" action="{{ route('admin.orders.cancel', $order) }}" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-800" 
                                                        title="Cancelar pedido"
                                                        onclick="return confirm('Tem certeza que deseja cancelar este pedido?')">
                                                    <i class="ti ti-x"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('admin.orders.print', $order) }}" 
                                           class="text-gray-600 hover:text-gray-800" 
                                           title="Imprimir"
                                           target="_blank">
                                            <i class="ti ti-printer"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="ti ti-shopping-cart-off text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-500 mb-2">Nenhum pedido encontrado</h3>
                <p class="text-gray-400 mb-4">Não há pedidos que correspondam aos filtros aplicados.</p>
                <a href="{{ route('admin.pos.index') }}" class="btn">
                    <i class="ti ti-plus mr-2"></i>
                    Criar Primeiro Pedido
                </a>
            </div>
        @endif
    </div>
</div>
@endsection 