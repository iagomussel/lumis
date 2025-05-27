@extends('layouts.admin')

@section('title', 'Busca - ' . $title)

@section('content')
<div class="space-y-6">
    <!-- Search Header -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $title }}</h2>
                    <p class="text-gray-600">
                        Resultados para: <strong>"{{ $query }}"</strong>
                        ({{ $results->total() }} {{ $results->total() === 1 ? 'resultado' : 'resultados' }})
                    </p>
                </div>
                <a href="{{ route('admin.search', ['q' => $query]) }}" 
                   class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    <i class="ti ti-arrow-left mr-1"></i>
                    Voltar à busca geral
                </a>
            </div>

            <!-- Search Form -->
            <form action="{{ route('admin.search') }}" method="GET" class="bg-gray-50 p-4 rounded-lg">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input 
                            type="text" 
                            name="q" 
                            value="{{ $query }}"
                            placeholder="Digite sua busca aqui..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>
                    <div>
                        <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="all">Todos os tipos</option>
                            <option value="customers" {{ $type === 'customers' ? 'selected' : '' }}>Clientes</option>
                            <option value="products" {{ $type === 'products' ? 'selected' : '' }}>Produtos</option>
                            <option value="orders" {{ $type === 'orders' ? 'selected' : '' }}>Pedidos</option>
                        </select>
                    </div>
                    <button type="submit" class="btn bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        <i class="ti ti-search mr-2"></i>
                        Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Results -->
    @if($results->count() > 0)
        <div class="card">
            <div class="card-body">
                <div class="space-y-4">
                    @if($type === 'customers')
                        <!-- Customer Results -->
                        @foreach($results as $customer)
                            <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                    <i class="ti ti-user text-blue-600 text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">{{ $customer->name }}</h4>
                                    <div class="text-sm text-gray-600 space-y-1">
                                        <p><i class="ti ti-mail mr-1"></i> {{ $customer->email }}</p>
                                        @if($customer->phone)
                                            <p><i class="ti ti-phone mr-1"></i> {{ $customer->phone }}</p>
                                        @endif
                                        @if($customer->document)
                                            <p><i class="ti ti-id mr-1"></i> {{ $customer->document }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    <a href="{{ route('admin.customers.show', $customer) }}" 
                                       class="btn btn-outline-primary">
                                        Ver Detalhes
                                    </a>
                                </div>
                            </div>
                        @endforeach

                    @elseif($type === 'products')
                        <!-- Product Results -->
                        @foreach($results as $product)
                            <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                    <i class="ti ti-package text-green-600 text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">{{ $product->name }}</h4>
                                    <div class="text-sm text-gray-600 space-y-1">
                                        <p><i class="ti ti-tag mr-1"></i> SKU: {{ $product->sku }}</p>
                                        @if($product->category)
                                            <p><i class="ti ti-category mr-1"></i> {{ $product->category->name }}</p>
                                        @endif
                                        <p><i class="ti ti-currency-real mr-1"></i> R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="space-x-2">
                                        <a href="{{ route('admin.products.show', $product) }}" 
                                           class="btn btn-outline-primary">
                                            Ver Detalhes
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}" 
                                           class="btn bg-blue-600 text-white hover:bg-blue-700">
                                            Editar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    @elseif($type === 'orders')
                        <!-- Order Results -->
                        @foreach($results as $order)
                            <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                                    <i class="ti ti-shopping-cart text-purple-600 text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">Pedido #{{ $order->id }}</h4>
                                    <div class="text-sm text-gray-600 space-y-1">
                                        <p><i class="ti ti-user mr-1"></i> {{ $order->customer->name }}</p>
                                        <p><i class="ti ti-currency-real mr-1"></i> R$ {{ number_format($order->total, 2, ',', '.') }}</p>
                                        <p>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                                @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                                @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <a href="{{ route('admin.orders.show', $order) }}" 
                                       class="btn btn-outline-primary">
                                        Ver Pedido
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Pagination -->
                @if($results->hasPages())
                    <div class="mt-6 border-t pt-6">
                        {{ $results->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>

    @else
        <!-- No Results -->
        <div class="card">
            <div class="card-body text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ti ti-search text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum {{ strtolower($title) }} encontrado</h3>
                <p class="text-gray-600 mb-6">
                    Não encontramos nenhum {{ strtolower($title) }} para "<strong>{{ $query }}</strong>".
                </p>
                <div class="space-x-4">
                    <a href="{{ route('admin.search', ['q' => $query]) }}" 
                       class="btn btn-outline-primary">
                        Buscar em todas as categorias
                    </a>
                    <a href="{{ route('admin.search') }}" 
                       class="btn bg-blue-600 text-white hover:bg-blue-700">
                        Nova Busca
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection 