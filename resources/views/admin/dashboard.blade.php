@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Customers -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center mr-4">
                        <i class="ti ti-users text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Total de Clientes</p>
                        <p class="text-2xl font-bold text-gray-500">{{ $stats['total_customers'] }}</p>
                        <p class="text-xs text-green-600">{{ $stats['active_customers'] }} ativos</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Categories -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-purple-500 flex items-center justify-center mr-4">
                        <i class="ti ti-category text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Total de Categorias</p>
                        <p class="text-2xl font-bold text-gray-500">{{ $stats['total_categories'] }}</p>
                        <p class="text-xs text-green-600">{{ $stats['active_categories'] }} ativas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Products -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-green-500 flex items-center justify-center mr-4">
                        <i class="ti ti-package text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Total de Produtos</p>
                        <p class="text-2xl font-bold text-gray-500">{{ $stats['total_products'] }}</p>
                        <p class="text-xs text-red-600">{{ $stats['low_stock_products'] }} com estoque baixo</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-yellow-500 flex items-center justify-center mr-4">
                        <i class="ti ti-shopping-cart text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Total de Pedidos</p>
                        <p class="text-2xl font-bold text-gray-500">{{ $stats['total_orders'] }}</p>
                        <p class="text-xs text-orange-600">{{ $stats['pending_orders'] }} pendentes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Products -->
        <div class="card">
            <div class="card-body">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-500">Produtos Recentes</h3>
                    <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Ver todos →
                    </a>
                </div>

                @if($recentProducts->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentProducts as $product)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center mr-3">
                                        <i class="ti ti-package text-white"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-500">{{ $product->name }}</p>
                                        <p class="text-sm text-gray-400">{{ $product->sku }}</p>
                                        @if($product->category)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                                {{ $product->category->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-green-600">R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                                    <p class="text-sm text-gray-400">Estoque: {{ $product->stock_quantity }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="ti ti-package text-gray-300 text-4xl mb-2"></i>
                        <p class="text-gray-400">Nenhum produto encontrado</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Categories -->
        <div class="card">
            <div class="card-body">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-500">Categorias Recentes</h3>
                    <a href="{{ route('admin.categories.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Ver todas →
                    </a>
                </div>

                @if($recentCategories->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentCategories as $category)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-purple-500 flex items-center justify-center mr-3">
                                        <i class="ti ti-category text-white"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-500">{{ $category->name }}</p>
                                        @if($category->description)
                                            <p class="text-sm text-gray-400">{{ Str::limit($category->description, 30) }}</p>
                                        @endif
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $category->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} mt-1">
                                            {{ $category->status === 'active' ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $category->products_count }} produtos
                                    </span>
                                    <p class="text-xs text-gray-400 mt-1">{{ $category->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="ti ti-category text-gray-300 text-4xl mb-2"></i>
                        <p class="text-gray-400">Nenhuma categoria encontrada</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Low Stock Products -->
    @if($lowStockProducts->count() > 0)
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Produtos com Estoque Baixo</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($lowStockProducts as $product)
                        <div class="border border-red-200 rounded-lg p-4 bg-red-50">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $product->name }}</h4>
                                    <p class="text-sm text-gray-500">SKU: {{ $product->sku }}</p>
                                    <p class="text-sm text-gray-500">Categoria: {{ $product->category->name }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-red-600">{{ $product->stock_quantity }} unidades</p>
                                    <p class="text-xs text-gray-500">Mín: {{ $product->min_stock }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.products.index') }}?filter=low_stock" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                        Ver todos os produtos com estoque baixo →
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection 