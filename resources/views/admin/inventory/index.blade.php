@extends('layouts.admin')

@section('title', 'Controle de Estoque')

@section('header-actions')
    <div class="flex space-x-2">
        <a href="{{ route('admin.products.create') }}" 
           class="btn text-white font-medium hover:bg-blue-700 inline-flex items-center">
            <i class="ti ti-plus mr-2"></i>
            Novo Produto
        </a>
        <button type="button" 
                class="btn-outline-primary font-medium hover:bg-blue-600 hover:text-white inline-flex items-center"
                onclick="toggleBulkEdit()">
            <i class="ti ti-edit mr-2"></i>
            Edição em Lote
        </button>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                        <i class="ti ti-package text-blue-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Total de Produtos</p>
                        <p class="text-2xl font-bold text-gray-500">{{ number_format($stats['total_products']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center mr-4">
                        <i class="ti ti-alert-triangle text-yellow-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Estoque Baixo</p>
                        <p class="text-2xl font-bold text-gray-500">{{ number_format($stats['low_stock']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mr-4">
                        <i class="ti ti-x-circle text-red-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Sem Estoque</p>
                        <p class="text-2xl font-bold text-gray-500">{{ number_format($stats['out_of_stock']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mr-4">
                        <i class="ti ti-currency-real text-green-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Valor Total</p>
                        <p class="text-2xl font-bold text-gray-500">R$ {{ number_format($stats['total_value'], 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card">
        <div class="card-body">
            <h6 class="text-lg text-gray-500 font-semibold mb-4">Filtros de Busca</h6>
            <form method="GET" action="{{ route('admin.inventory.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-400 mb-2">Buscar</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                               placeholder="Nome, SKU, descrição..."
                               class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0">
                    </div>
                    
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-400 mb-2">Categoria</label>
                        <select name="category_id" id="category_id" 
                                class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Todas</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="stock_status" class="block text-sm font-medium text-gray-400 mb-2">Status do Estoque</label>
                        <select name="stock_status" id="stock_status" 
                                class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Todos</option>
                            <option value="available" {{ request('stock_status') === 'available' ? 'selected' : '' }}>Disponível</option>
                            <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Estoque Baixo</option>
                            <option value="out" {{ request('stock_status') === 'out' ? 'selected' : '' }}>Sem Estoque</option>
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-400 mb-2">Status do Produto</label>
                        <select name="status" id="status" 
                                class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Todos</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativo</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end space-x-2">
                        <button type="submit" 
                                class="btn text-white font-medium hover:bg-blue-700">
                            <i class="ti ti-search mr-2"></i>
                            Filtrar
                        </button>
                        <a href="{{ route('admin.inventory.index') }}" 
                           class="btn-outline-primary font-medium hover:bg-blue-600 hover:text-white inline-flex items-center">
                            <i class="ti ti-refresh mr-2"></i>
                            Limpar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Card -->
    <div class="card">
        <div class="card-body">
            <div class="flex justify-between items-center mb-5">
                <h4 class="text-gray-500 text-lg font-semibold">Controle de Estoque</h4>
                <div class="text-sm text-gray-400">
                    {{ $products->total() }} produto(s) encontrado(s)
                </div>
            </div>
            
            @if($products->count() > 0)
                <!-- Bulk Edit Form (Hidden by default) -->
                <form id="bulkEditForm" method="POST" action="{{ route('admin.inventory.bulk-update') }}" style="display: none;" class="mb-6">
                    @csrf
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h6 class="text-blue-800 font-semibold">Edição em Lote</h6>
                            <button type="button" onclick="toggleBulkEdit()" class="text-blue-600 hover:text-blue-800">
                                <i class="ti ti-x"></i>
                            </button>
                        </div>
                        <div class="flex space-x-3">
                            <button type="submit" class="btn text-white font-medium hover:bg-blue-700">
                                <i class="ti ti-check mr-2"></i>
                                Salvar Alterações
                            </button>
                            <button type="button" onclick="selectAllProducts()" class="btn-outline-primary font-medium hover:bg-blue-600 hover:text-white">
                                Selecionar Todos
                            </button>
                        </div>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" id="selectAll" class="bulk-edit-only" style="display: none;">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Produto
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Categoria
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estoque Atual
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Preços
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($products as $product)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="products[{{ $loop->index }}][id]" value="{{ $product->id }}" 
                                               class="bulk-edit-only product-checkbox" style="display: none;">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center mr-3">
                                                <i class="ti ti-package text-white text-lg"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-500">{{ $product->name }}</div>
                                                @if($product->sku)
                                                    <div class="text-sm text-gray-400">SKU: {{ $product->sku }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($product->category)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $product->category->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="text-lg font-bold text-gray-500 mr-2">{{ $product->stock_quantity }}</span>
                                            @if($product->stock_quantity <= 0)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="ti ti-x-circle mr-1"></i> Sem estoque
                                                </span>
                                            @elseif($product->stock_quantity <= 10)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="ti ti-alert-triangle mr-1"></i> Baixo
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="ti ti-check mr-1"></i> OK
                                                </span>
                                            @endif
                                        </div>
                                        <!-- Bulk edit input -->
                                        <input type="number" name="products[{{ $loop->index }}][stock_quantity]" 
                                               value="{{ $product->stock_quantity }}" min="0"
                                               class="bulk-edit-only mt-2 py-1 px-2 text-sm border border-gray-300 rounded w-20" 
                                               style="display: none;">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            <div class="font-medium">Venda: R$ {{ number_format($product->price, 2, ',', '.') }}</div>
                                            @if($product->cost_price)
                                                <div class="text-gray-400">Custo: R$ {{ number_format($product->cost_price, 2, ',', '.') }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($product->status === 'active') bg-green-500 text-white
                                            @else bg-gray-500 text-white
                                            @endif">
                                            @if($product->status === 'active') 
                                                <i class="ti ti-check mr-1"></i> Ativo
                                            @else 
                                                <i class="ti ti-x mr-1"></i> Inativo
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.inventory.show', $product) }}" 
                                               class="text-blue-600 hover:text-blue-700 inline-flex items-center">
                                                <i class="ti ti-eye mr-1"></i> Ver
                                            </a>
                                            <a href="{{ route('admin.inventory.edit', $product) }}" 
                                               class="text-yellow-500 hover:text-yellow-600 inline-flex items-center">
                                                <i class="ti ti-edit mr-1"></i> Ajustar
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product) }}" 
                                               class="text-green-600 hover:text-green-700 inline-flex items-center">
                                                <i class="ti ti-settings mr-1"></i> Produto
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($products->hasPages())
                    <div class="mt-6">
                        {{ $products->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="ti ti-package text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-500 mb-2">Nenhum produto encontrado</h3>
                    <p class="text-sm text-gray-400 mb-6">
                        @if(request()->hasAny(['search', 'category_id', 'stock_status', 'status']))
                            Tente ajustar os filtros ou 
                            <a href="{{ route('admin.inventory.index') }}" class="text-blue-600 hover:text-blue-700">limpar a busca</a>
                        @else
                            Comece criando seu primeiro produto
                        @endif
                    </p>
                    <a href="{{ route('admin.products.create') }}" 
                       class="btn text-white font-medium hover:bg-blue-700 inline-flex items-center">
                        <i class="ti ti-plus mr-2"></i>
                        Criar Primeiro Produto
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function toggleBulkEdit() {
    const form = document.getElementById('bulkEditForm');
    const elements = document.querySelectorAll('.bulk-edit-only');
    
    if (form.style.display === 'none') {
        form.style.display = 'block';
        elements.forEach(el => el.style.display = 'block');
    } else {
        form.style.display = 'none';
        elements.forEach(el => el.style.display = 'none');
    }
}

function selectAllProducts() {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const selectAll = document.getElementById('selectAll');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

document.getElementById('selectAll')?.addEventListener('change', selectAllProducts);
</script>
@endsection 