@extends('layouts.admin')

@section('title', 'Busca Global')

@section('content')
<div class="space-y-6">
    <!-- Search Header -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Resultados da Busca</h2>
                    <p class="text-gray-600">
                        @if($query)
                            Mostrando resultados para: <strong>"{{ $query }}"</strong>
                        @else
                            Digite algo no campo de busca para encontrar produtos, clientes, pedidos e mais.
                        @endif
                    </p>
                </div>
                <div class="text-right">
                    <span class="text-sm text-gray-500">Total de resultados:</span>
                    <span class="text-xl font-bold text-blue-600">{{ $total_results }}</span>
                </div>
            </div>

            <!-- Advanced Search Form -->
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
                            <option value="customers" {{ request('type') === 'customers' ? 'selected' : '' }}>Clientes</option>
                            <option value="products" {{ request('type') === 'products' ? 'selected' : '' }}>Produtos</option>
                            <option value="orders" {{ request('type') === 'orders' ? 'selected' : '' }}>Pedidos</option>
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

    @if($query && $total_results > 0)
        <!-- Results Sections -->
        
        <!-- Customers Results -->
        @if($customers && $customers->count() > 0)
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="ti ti-users mr-2 text-blue-600"></i>
                        Clientes ({{ $customers->total() }})
                    </h3>
                    <a href="{{ route('admin.search', ['q' => $query, 'type' => 'customers']) }}" 
                       class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        Ver todos <i class="ti ti-arrow-right ml-1"></i>
                    </a>
                </div>
                
                <div class="space-y-3">
                    @foreach($customers->take(3) as $customer)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="ti ti-user text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $customer->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $customer->email }} • {{ $customer->phone }}</p>
                            </div>
                            <a href="{{ route('admin.customers.show', $customer) }}" 
                               class="text-blue-600 hover:text-blue-700">
                                <i class="ti ti-external-link"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Products Results -->
        @if($products && $products->count() > 0)
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="ti ti-package mr-2 text-green-600"></i>
                        Produtos ({{ $products->total() }})
                    </h3>
                    <a href="{{ route('admin.search', ['q' => $query, 'type' => 'products']) }}" 
                       class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        Ver todos <i class="ti ti-arrow-right ml-1"></i>
                    </a>
                </div>
                
                <div class="space-y-3">
                    @foreach($products->take(3) as $product)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="ti ti-package text-green-600"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $product->name }}</h4>
                                <p class="text-sm text-gray-600">
                                    SKU: {{ $product->sku }} • {{ $product->category?->name }} • 
                                    R$ {{ number_format($product->price, 2, ',', '.') }}
                                </p>
                            </div>
                            <a href="{{ route('admin.products.show', $product) }}" 
                               class="text-blue-600 hover:text-blue-700">
                                <i class="ti ti-external-link"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Orders Results -->
        @if($orders && $orders->count() > 0)
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="ti ti-shopping-cart mr-2 text-purple-600"></i>
                        Pedidos ({{ $orders->total() }})
                    </h3>
                    <a href="{{ route('admin.search', ['q' => $query, 'type' => 'orders']) }}" 
                       class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        Ver todos <i class="ti ti-arrow-right ml-1"></i>
                    </a>
                </div>
                
                <div class="space-y-3">
                    @foreach($orders->take(3) as $order)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                <i class="ti ti-shopping-cart text-purple-600"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">Pedido #{{ $order->id }}</h4>
                                <p class="text-sm text-gray-600">
                                    {{ $order->customer->name }} • R$ {{ number_format($order->total, 2, ',', '.') }} • 
                                    {{ ucfirst($order->status) }}
                                </p>
                            </div>
                            <a href="{{ route('admin.orders.show', $order) }}" 
                               class="text-blue-600 hover:text-blue-700">
                                <i class="ti ti-external-link"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

    @elseif($query && $total_results === 0)
        <!-- No Results -->
        <div class="card">
            <div class="card-body text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ti ti-search text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum resultado encontrado</h3>
                <p class="text-gray-600 mb-6">
                    Não encontramos nada para "<strong>{{ $query }}</strong>". 
                    Tente usar termos diferentes ou menos específicos.
                </p>
                <div class="space-y-2 text-sm text-gray-500">
                    <p><strong>Dicas:</strong></p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Verifique a ortografia</li>
                        <li>Use termos mais gerais</li>
                        <li>Tente buscar por SKU, nome ou email</li>
                        <li>Use apenas parte do nome</li>
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if(!$query)
        <!-- Search Tips -->
        <div class="card">
            <div class="card-body">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Como usar a busca</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <i class="ti ti-users text-blue-600 text-xl"></i>
                        </div>
                        <h4 class="font-medium text-gray-900 mb-2">Clientes</h4>
                        <p class="text-sm text-gray-600">
                            Busque por nome, email, telefone ou documento
                        </p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <i class="ti ti-package text-green-600 text-xl"></i>
                        </div>
                        <h4 class="font-medium text-gray-900 mb-2">Produtos</h4>
                        <p class="text-sm text-gray-600">
                            Busque por nome, SKU ou descrição
                        </p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <i class="ti ti-shopping-cart text-purple-600 text-xl"></i>
                        </div>
                        <h4 class="font-medium text-gray-900 mb-2">Pedidos</h4>
                        <p class="text-sm text-gray-600">
                            Busque por ID do pedido ou nome do cliente
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection 