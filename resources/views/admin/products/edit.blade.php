@extends('layouts.admin')

@section('title', 'Editar Produto')

@section('header-actions')
    <a href="{{ route('admin.products.index') }}" 
       class="btn-outline-primary font-medium hover:bg-blue-600 hover:text-white inline-flex items-center">
        <i class="ti ti-arrow-left mr-2"></i>
        Voltar
    </a>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="card">
        <div class="card-body">
            <div class="flex justify-between items-center mb-6">
                <h4 class="text-gray-500 text-lg font-semibold">Editar Produto</h4>
            </div>

            <form method="POST" action="{{ route('admin.products.update', $product) }}">
                @csrf
                @method('PUT')

                <!-- Informações Básicas -->
                <div class="mb-8">
                    <h6 class="text-md font-semibold text-gray-500 mb-4 pb-2 border-b border-gray-200">
                        <i class="ti ti-info-circle mr-2"></i>
                        Informações Básicas
                    </h6>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-500 mb-2">
                                Nome do Produto <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $product->name) }}" 
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                   placeholder="Digite o nome do produto">
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sku" class="block text-sm font-medium text-gray-500 mb-2">
                                SKU <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="sku" 
                                   id="sku" 
                                   value="{{ old('sku', $product->sku) }}" 
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sku') border-red-500 @enderror"
                                   placeholder="Ex: PROD-001">
                            @error('sku')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="description" class="block text-sm font-medium text-gray-500 mb-2">
                            Descrição
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                  placeholder="Descrição detalhada do produto">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Categoria e Preços -->
                <div class="mb-8">
                    <h6 class="text-md font-semibold text-gray-500 mb-4 pb-2 border-b border-gray-200">
                        <i class="ti ti-tag mr-2"></i>
                        Categoria e Preços
                    </h6>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-500 mb-2">
                                Categoria <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id" 
                                    id="category_id" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category_id') border-red-500 @enderror">
                                <option value="">Selecione uma categoria</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-500 mb-2">
                                Preço de Venda <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="price" 
                                   id="price" 
                                   value="{{ old('price', $product->price) }}" 
                                   step="0.01"
                                   min="0"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror"
                                   placeholder="0,00">
                            @error('price')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="cost_price" class="block text-sm font-medium text-gray-500 mb-2">
                                Preço de Custo
                            </label>
                            <input type="number" 
                                   name="cost_price" 
                                   id="cost_price" 
                                   value="{{ old('cost_price', $product->cost_price) }}" 
                                   step="0.01"
                                   min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('cost_price') border-red-500 @enderror"
                                   placeholder="0,00">
                            @error('cost_price')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Estoque -->
                <div class="mb-8">
                    <h6 class="text-md font-semibold text-gray-500 mb-4 pb-2 border-b border-gray-200">
                        <i class="ti ti-package mr-2"></i>
                        Controle de Estoque
                    </h6>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="stock_quantity" class="block text-sm font-medium text-gray-500 mb-2">
                                Quantidade em Estoque <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="stock_quantity" 
                                   id="stock_quantity" 
                                   value="{{ old('stock_quantity', $product->stock_quantity) }}" 
                                   min="0"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('stock_quantity') border-red-500 @enderror"
                                   placeholder="0">
                            @error('stock_quantity')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="min_stock" class="block text-sm font-medium text-gray-500 mb-2">
                                Estoque Mínimo
                            </label>
                            <input type="number" 
                                   name="min_stock" 
                                   id="min_stock" 
                                   value="{{ old('min_stock', $product->min_stock) }}" 
                                   min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('min_stock') border-red-500 @enderror"
                                   placeholder="0">
                            @error('min_stock')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Detalhes Físicos -->
                <div class="mb-8">
                    <h6 class="text-md font-semibold text-gray-500 mb-4 pb-2 border-b border-gray-200">
                        <i class="ti ti-ruler mr-2"></i>
                        Detalhes Físicos
                    </h6>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-500 mb-2">
                                Peso (kg)
                            </label>
                            <input type="number" 
                                   name="weight" 
                                   id="weight" 
                                   value="{{ old('weight', $product->weight) }}" 
                                   step="0.01"
                                   min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('weight') border-red-500 @enderror"
                                   placeholder="0,00">
                            @error('weight')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="dimensions" class="block text-sm font-medium text-gray-500 mb-2">
                                Dimensões
                            </label>
                            <input type="text" 
                                   name="dimensions" 
                                   id="dimensions" 
                                   value="{{ old('dimensions', $product->dimensions) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('dimensions') border-red-500 @enderror"
                                   placeholder="Ex: 10x15x5 cm">
                            @error('dimensions')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-8">
                    <h6 class="text-md font-semibold text-gray-500 mb-4 pb-2 border-b border-gray-200">
                        <i class="ti ti-settings mr-2"></i>
                        Configurações
                    </h6>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-500 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" 
                                id="status" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                            <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Ativo</option>
                            <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inativo</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4">
                    <button type="submit" 
                            class="btn text-white font-medium hover:bg-blue-700 inline-flex items-center">
                        <i class="ti ti-check mr-2"></i>
                        Atualizar Produto
                    </button>
                    <a href="{{ route('admin.products.index') }}" 
                       class="btn-outline-primary font-medium hover:bg-blue-600 hover:text-white inline-flex items-center">
                        <i class="ti ti-x mr-2"></i>
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 