@extends('layouts.admin')

@section('title', 'Variações de Produtos')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Variações de Produtos</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.product-variants.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="ti ti-plus mr-1"></i>
                Nova Variação
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <img src="/images/branding/hero.png" alt="Hero" class="w-10 h-10 object-contain" />
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Variações</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_variants'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="ti ti-check text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Variações Ativas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['active_variants'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="ti ti-alert-triangle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Estoque Baixo</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['low_stock_variants'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="ti ti-x text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Sem Estoque</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['out_of_stock_variants'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.product-variants.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="SKU, título, código de barras..." 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="product_id" class="block text-sm font-medium text-gray-700 mb-1">Produto</label>
                <select name="product_id" id="product_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos os produtos</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativo</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="ti ti-search mr-1"></i>
                    Filtrar
                </button>
                <a href="{{ route('admin.product-variants.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="ti ti-refresh mr-1"></i>
                    Limpar
                </a>
            </div>
        </form>
    </div>

    <!-- Variants Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Variação
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Produto
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Preço
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estoque
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($variants as $variant)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $variant->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            SKU: {{ $variant->sku }}
                                            @if($variant->barcode)
                                                | Código: {{ $variant->barcode }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $variant->product->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    @if($variant->price_adjustment != 0)
                                        {{ $variant->price_adjustment > 0 ? '+' : '' }}R$ {{ number_format($variant->price_adjustment, 2, ',', '.') }}
                                    @else
                                        Preço base
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($variant->stock_quantity <= $variant->min_stock)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Estoque baixo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Em estoque
                                    </span>
                                @endif
                                <div class="text-xs text-gray-500 mt-1">
                                    Qtd: {{ $variant->stock_quantity ?? 0 }} (Min: {{ $variant->min_stock ?? 0 }})
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $variant->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $variant->active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.product-variants.show', $variant) }}" 
                                       class="text-blue-600 hover:text-blue-900" title="Visualizar">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.product-variants.edit', $variant) }}" 
                                       class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.product-variants.toggle', $variant) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="{{ $variant->active ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900' }}" 
                                                title="{{ $variant->active ? 'Desativar' : 'Ativar' }}">
                                            <i class="ti ti-{{ $variant->active ? 'eye-off' : 'eye' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.product-variants.destroy', $variant) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900" 
                                                title="Excluir"
                                                onclick="return confirm('Tem certeza que deseja excluir esta variação?')">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="ti ti-package text-4xl mb-2"></i>
                                    <p class="text-lg font-medium">Nenhuma variação encontrada</p>
                                    <p class="text-sm">Crie sua primeira variação de produto para começar.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($variants->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $variants->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection 