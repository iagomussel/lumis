@extends('layouts.admin')

@section('title', 'Produtos')

@section('header-actions')
    <a href="{{ route('admin.products.create') }}" 
       class="btn text-white font-medium hover:bg-blue-700 inline-flex items-center">
        <i class="ti ti-plus mr-2"></i>
        Novo Produto
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filters Card -->
    <div class="card">
        <div class="card-body">
            <h6 class="text-lg text-gray-500 font-semibold mb-4">Filtros de Busca</h6>
            <form method="GET" action="{{ route('admin.products.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-500 mb-2">Buscar</label>
                        <input type="text" 
                               name="search" 
                               id="search"
                               value="{{ request('search') }}" 
                               placeholder="Nome, SKU ou descrição"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-500 mb-2">Categoria</label>
                        <select name="category_id" id="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todas as categorias</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-500 mb-2">Status</label>
                        <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos os status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativo</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>

                    <div>
                        <label for="price_min" class="block text-sm font-medium text-gray-500 mb-2">Preço Mín.</label>
                        <input type="number" 
                               name="price_min" 
                               id="price_min"
                               value="{{ request('price_min') }}" 
                               step="0.01"
                               placeholder="0,00"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="price_max" class="block text-sm font-medium text-gray-500 mb-2">Preço Máx.</label>
                        <input type="number" 
                               name="price_max" 
                               id="price_max"
                               value="{{ request('price_max') }}" 
                               step="0.01"
                               placeholder="999,99"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="flex gap-4 mt-4">
                    <button type="submit" class="btn text-white font-medium hover:bg-blue-700 inline-flex items-center">
                        <i class="ti ti-search mr-2"></i>
                        Filtrar
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn-outline-primary font-medium hover:bg-blue-600 hover:text-white inline-flex items-center">
                        <i class="ti ti-x mr-2"></i>
                        Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card">
        <div class="card-body">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h4 class="text-gray-500 text-lg font-semibold">Produtos</h4>
                    <p class="text-gray-400 text-sm">{{ $products->total() }} produtos encontrados</p>
                </div>
            </div>

            @if($products->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 font-medium text-gray-500">Produto</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-500">SKU</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-500">Categoria</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-500">Preço</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-500">Estoque</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-500">Status</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-500">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-4 px-4">
                                        <div>
                                            <div class="font-medium text-gray-500">{{ $product->name }}</div>
                                            @if($product->description)
                                                <div class="text-sm text-gray-400 mt-1">{{ Str::limit($product->description, 50) }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="font-mono text-sm text-gray-500">{{ $product->sku }}</span>
                                    </td>
                                    <td class="py-4 px-4">
                                        @if($product->category)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $product->category->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">Sem categoria</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="font-semibold text-green-600">
                                            R$ {{ number_format($product->price, 2, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center">
                                            <span class="font-medium text-gray-500">{{ $product->stock_quantity }}</span>
                                            @if($product->min_stock && $product->stock_quantity <= $product->min_stock)
                                                <i class="ti ti-alert-triangle text-yellow-500 ml-2" title="Estoque baixo"></i>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $product->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $product->status === 'active' ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.products.show', $product) }}" 
                                               class="text-blue-600 hover:text-blue-800 transition-colors">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product) }}" 
                                               class="text-green-600 hover:text-green-800 transition-colors">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-800 transition-colors"
                                                        onclick="return confirm('Tem certeza que deseja excluir este produto?')">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $products->appends(request()->except('page'))->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="ti ti-package text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-500 mb-2">Nenhum produto encontrado</h3>
                    <p class="text-gray-400 mb-6">Não há produtos que correspondam aos critérios de busca.</p>
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
@endsection 