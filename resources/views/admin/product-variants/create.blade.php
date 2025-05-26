@extends('layouts.admin')

@section('title', 'Nova Variação de Produto')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Nova Variação de Produto</h1>
                    <p class="mt-2 text-gray-600">Crie uma nova variação para um produto existente</p>
                </div>
                <a href="{{ route('admin.product-variants.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Voltar
                </a>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.product-variants.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informações Básicas</h3>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Product Selection -->
                    <div>
                        <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Produto <span class="text-red-500">*</span>
                        </label>
                        <select name="product_id" id="product_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('product_id') border-red-500 @enderror">
                            <option value="">Selecione um produto</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id', $selectedProduct?->id) == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome da Variação <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               placeholder="Ex: Camiseta Azul Tamanho M"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- SKU and Barcode -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">
                                SKU
                            </label>
                            <input type="text" name="sku" id="sku" value="{{ old('sku') }}"
                                   placeholder="Deixe em branco para gerar automaticamente"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('sku') border-red-500 @enderror">
                            @error('sku')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="barcode" class="block text-sm font-medium text-gray-700 mb-2">
                                Código de Barras
                            </label>
                            <input type="text" name="barcode" id="barcode" value="{{ old('barcode') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('barcode') border-red-500 @enderror">
                            @error('barcode')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Option Values -->
                    <div>
                        <label for="option_values" class="block text-sm font-medium text-gray-700 mb-2">
                            Valores das Opções <span class="text-red-500">*</span>
                        </label>
                        <textarea name="option_values" id="option_values" rows="3" required
                                  placeholder='{"cor": "azul", "tamanho": "M"}'
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('option_values') border-red-500 @enderror">{{ old('option_values') }}</textarea>
                        @error('option_values')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Formato JSON com as opções da variação</p>
                    </div>
                </div>
            </div>

            <!-- Pricing -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Ajustes de Preço</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="price_adjustment" class="block text-sm font-medium text-gray-700 mb-2">
                                Ajuste de Preço
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">R$</span>
                                <input type="number" name="price_adjustment" id="price_adjustment" value="{{ old('price_adjustment', 0) }}" step="0.01"
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('price_adjustment') border-red-500 @enderror">
                            </div>
                            @error('price_adjustment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Valor a ser adicionado/subtraído do preço base do produto</p>
                        </div>
                        <div>
                            <label for="cost_adjustment" class="block text-sm font-medium text-gray-700 mb-2">
                                Ajuste de Custo
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">R$</span>
                                <input type="number" name="cost_adjustment" id="cost_adjustment" value="{{ old('cost_adjustment', 0) }}" step="0.01"
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('cost_adjustment') border-red-500 @enderror">
                            </div>
                            @error('cost_adjustment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Valor a ser adicionado/subtraído do custo base do produto</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Estoque</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Quantidade em Estoque <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', 0) }}" min="0" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('stock_quantity') border-red-500 @enderror">
                            @error('stock_quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="min_stock" class="block text-sm font-medium text-gray-700 mb-2">
                                Estoque Mínimo
                            </label>
                            <input type="number" name="min_stock" id="min_stock" value="{{ old('min_stock', 5) }}" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('min_stock') border-red-500 @enderror">
                            @error('min_stock')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Physical Properties -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Propriedades Físicas</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label for="weight_adjustment" class="block text-sm font-medium text-gray-700 mb-2">
                            Ajuste de Peso (kg)
                        </label>
                        <input type="number" name="weight_adjustment" id="weight_adjustment" value="{{ old('weight_adjustment', 0) }}" step="0.001"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('weight_adjustment') border-red-500 @enderror">
                        @error('weight_adjustment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Valor a ser adicionado/subtraído do peso base do produto</p>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Status</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="active" id="active" value="1" {{ old('active', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="active" class="ml-2 block text-sm text-gray-700">
                            Variação ativa
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.product-variants.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Criar Variação
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-format option_values as JSON
    const optionValuesField = document.getElementById('option_values');
    optionValuesField.addEventListener('blur', function() {
        try {
            const value = this.value.trim();
            if (value && !value.startsWith('{')) {
                // Try to convert simple format to JSON
                const formatted = '{"' + value.replace(/:/g, '":"').replace(/,/g, '","') + '"}';
                this.value = formatted;
            }
        } catch (e) {
            // Ignore formatting errors
        }
    });
});
</script>
@endsection 