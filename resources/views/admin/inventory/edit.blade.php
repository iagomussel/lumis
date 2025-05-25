@extends('layouts.admin')

@section('title', 'Ajustar Estoque')

@section('header-actions')
    <a href="{{ route('admin.inventory.index') }}" 
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
                <h4 class="text-gray-500 text-lg font-semibold">Ajustar Estoque: {{ $product->name }}</h4>
            </div>

            <!-- Informações do Produto -->
            <div class="mb-8">
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="w-16 h-16 rounded-full bg-indigo-500 flex items-center justify-center mr-4">
                            <i class="ti ti-package text-white text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <h5 class="text-lg font-semibold text-gray-500">{{ $product->name }}</h5>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-3">
                                @if($product->sku)
                                    <div>
                                        <p class="text-sm text-gray-400">SKU</p>
                                        <p class="text-gray-500">{{ $product->sku }}</p>
                                    </div>
                                @endif
                                @if($product->category)
                                    <div>
                                        <p class="text-sm text-gray-400">Categoria</p>
                                        <p class="text-gray-500">{{ $product->category->name }}</p>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm text-gray-400">Preço de Venda</p>
                                    <p class="text-gray-500">R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-400">Estoque Atual</p>
                            <p class="text-3xl font-bold text-gray-500">{{ $product->stock_quantity }}</p>
                            @if($product->stock_quantity <= 0)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="ti ti-x-circle mr-1"></i> Sem estoque
                                </span>
                            @elseif($product->stock_quantity <= 10)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="ti ti-alert-triangle mr-1"></i> Estoque baixo
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="ti ti-check mr-1"></i> OK
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.inventory.update', $product) }}">
                @csrf
                @method('PUT')

                <!-- Tipo de Ajuste -->
                <div class="mb-8">
                    <h6 class="text-lg text-gray-500 font-semibold mb-4">Tipo de Ajuste</h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="relative">
                            <input type="radio" name="adjustment_type" id="set" value="set" 
                                   class="peer sr-only" {{ old('adjustment_type', 'set') === 'set' ? 'checked' : '' }}>
                            <label for="set" class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                    <i class="ti ti-edit text-blue-500"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-500">Definir Quantidade</p>
                                    <p class="text-sm text-gray-400">Definir o valor exato do estoque</p>
                                </div>
                            </label>
                        </div>

                        <div class="relative">
                            <input type="radio" name="adjustment_type" id="add" value="add" 
                                   class="peer sr-only" {{ old('adjustment_type') === 'add' ? 'checked' : '' }}>
                            <label for="add" class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 hover:bg-gray-50">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                    <i class="ti ti-plus text-green-500"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-500">Adicionar</p>
                                    <p class="text-sm text-gray-400">Somar ao estoque atual</p>
                                </div>
                            </label>
                        </div>

                        <div class="relative">
                            <input type="radio" name="adjustment_type" id="subtract" value="subtract" 
                                   class="peer sr-only" {{ old('adjustment_type') === 'subtract' ? 'checked' : '' }}>
                            <label for="subtract" class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-red-500 peer-checked:bg-red-50 hover:bg-gray-50">
                                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mr-3">
                                    <i class="ti ti-minus text-red-500"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-500">Subtrair</p>
                                    <p class="text-sm text-gray-400">Remover do estoque atual</p>
                                </div>
                            </label>
                        </div>
                    </div>
                    @error('adjustment_type')
                        <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quantidade e Preço -->
                <div class="mb-8">
                    <h6 class="text-lg text-gray-500 font-semibold mb-4">Valores</h6>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="stock_quantity" class="block text-sm mb-2 text-gray-400">
                                <span id="quantity-label">Quantidade *</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="stock_quantity" id="stock_quantity" 
                                       value="{{ old('stock_quantity', $product->stock_quantity) }}" 
                                       required min="0" step="1"
                                       class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('stock_quantity') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-400 text-sm">unidades</span>
                                </div>
                            </div>
                            <div id="quantity-help" class="text-sm text-gray-400 mt-2">
                                Insira a quantidade total para este produto
                            </div>
                            @error('stock_quantity')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="cost_price" class="block text-sm mb-2 text-gray-400">Preço de Custo (Opcional)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-400">R$</span>
                                </div>
                                <input type="number" name="cost_price" id="cost_price" 
                                       value="{{ old('cost_price', $product->cost_price) }}" 
                                       step="0.01" min="0"
                                       class="py-3 px-4 pl-10 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('cost_price') border-red-500 @enderror">
                            </div>
                            <div class="text-sm text-gray-400 mt-2">
                                Atualizar o preço de custo do produto
                            </div>
                            @error('cost_price')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Motivo do Ajuste -->
                <div class="mb-8">
                    <h6 class="text-lg text-gray-500 font-semibold mb-4">Motivo do Ajuste</h6>
                    <div>
                        <label for="adjustment_reason" class="block text-sm mb-2 text-gray-400">Observações (Opcional)</label>
                        <textarea name="adjustment_reason" id="adjustment_reason" rows="4"
                                  placeholder="Descreva o motivo do ajuste de estoque..."
                                  class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('adjustment_reason') border-red-500 @enderror">{{ old('adjustment_reason') }}</textarea>
                        @error('adjustment_reason')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Preview do Resultado -->
                <div class="mb-8">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h6 class="text-blue-800 font-semibold mb-3">Preview do Ajuste</h6>
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <p class="text-sm text-blue-600">Estoque Atual</p>
                                <p class="text-xl font-bold text-blue-800" id="current-stock">{{ $product->stock_quantity }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-blue-600">Operação</p>
                                <p class="text-xl font-bold text-blue-800" id="operation">→</p>
                            </div>
                            <div>
                                <p class="text-sm text-blue-600">Novo Estoque</p>
                                <p class="text-xl font-bold text-blue-800" id="new-stock">{{ $product->stock_quantity }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.inventory.index') }}" 
                       class="btn-outline-primary font-medium hover:bg-blue-600 hover:text-white inline-flex items-center">
                        <i class="ti ti-x mr-2"></i>
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="btn text-white font-medium hover:bg-blue-700 inline-flex items-center">
                        <i class="ti ti-check mr-2"></i>
                        Salvar Ajuste
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const currentStock = {{ $product->stock_quantity }};
    const quantityInput = document.getElementById('stock_quantity');
    const adjustmentTypeInputs = document.querySelectorAll('input[name="adjustment_type"]');
    const quantityLabel = document.getElementById('quantity-label');
    const quantityHelp = document.getElementById('quantity-help');
    const operationDisplay = document.getElementById('operation');
    const newStockDisplay = document.getElementById('new-stock');

    function updatePreview() {
        const adjustmentType = document.querySelector('input[name="adjustment_type"]:checked')?.value || 'set';
        const quantity = parseInt(quantityInput.value) || 0;
        let newStock = currentStock;
        let operation = '→';

        switch (adjustmentType) {
            case 'set':
                newStock = quantity;
                operation = '=';
                quantityLabel.textContent = 'Nova Quantidade *';
                quantityHelp.textContent = 'Defina a quantidade total que o produto deve ter';
                break;
            case 'add':
                newStock = currentStock + quantity;
                operation = '+' + quantity;
                quantityLabel.textContent = 'Quantidade a Adicionar *';
                quantityHelp.textContent = 'Quantidade que será somada ao estoque atual';
                break;
            case 'subtract':
                newStock = Math.max(0, currentStock - quantity);
                operation = '-' + quantity;
                quantityLabel.textContent = 'Quantidade a Subtrair *';
                quantityHelp.textContent = 'Quantidade que será removida do estoque atual';
                break;
        }

        operationDisplay.textContent = operation;
        newStockDisplay.textContent = newStock;

        // Atualizar cor baseado no resultado
        if (newStock <= 0) {
            newStockDisplay.className = 'text-xl font-bold text-red-600';
        } else if (newStock <= 10) {
            newStockDisplay.className = 'text-xl font-bold text-yellow-600';
        } else {
            newStockDisplay.className = 'text-xl font-bold text-green-600';
        }
    }

    // Event listeners
    quantityInput.addEventListener('input', updatePreview);
    adjustmentTypeInputs.forEach(input => {
        input.addEventListener('change', updatePreview);
    });

    // Initial update
    updatePreview();
});
</script>
@endsection 