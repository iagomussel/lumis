@extends('layouts.admin')

@section('title', 'Editar Pedido')

@section('header-actions')
    <div class="flex items-center space-x-2">
        <a href="{{ route('admin.orders.show', $order) }}" 
           class="btn btn-outline-primary">
            <i class="ti ti-eye mr-2"></i>
            Ver Pedido
        </a>
        <a href="{{ route('admin.orders.index') }}" 
           class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left mr-2"></i>
            Voltar
        </a>
    </div>
@endsection

@section('content')
<div class="max-w-6xl mx-auto">
    <form id="orderForm" method="POST" action="{{ route('admin.orders.update', $order) }}">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Info -->
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold">Pedido #{{ $order->id }}</h3>
                            <span class="text-sm text-gray-500">Criado em {{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">Cliente *</label>
                                <select name="customer_id" id="customer_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ $order->customer_id == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} - {{ $customer->email }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="order_date" class="block text-sm font-medium text-gray-700 mb-2">Data do Pedido</label>
                                <input type="date" name="order_date" id="order_date" value="{{ $order->created_at->format('Y-m-d') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products -->
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold">Produtos</h3>
                            <button type="button" onclick="addProductLine()" class="btn bg-green-600 text-white hover:bg-green-700">
                                <i class="ti ti-plus mr-2"></i>
                                Adicionar Produto
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="products-container">
                            @foreach($order->items as $index => $item)
                                <div class="product-line border border-gray-200 rounded-lg p-4 mb-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="font-medium">Produto #{{ $index + 1 }}</h4>
                                        <button type="button" onclick="removeProductLine(this)" class="text-red-600 hover:text-red-700">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Produto *</label>
                                            <select name="products[][product_id]" class="product-select w-full px-3 py-2 border border-gray-300 rounded-lg" required onchange="updateProductPrice(this)">
                                                <option value="">Selecione um produto...</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}" 
                                                            {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                        {{ $product->name }} - R$ {{ number_format($product->price, 2, ',', '.') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantidade *</label>
                                            <input type="number" name="products[][quantity]" class="quantity-input w-full px-3 py-2 border border-gray-300 rounded-lg" 
                                                   value="{{ $item->quantity }}" min="1" required onchange="calculateLineTotal(this)">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Preço Unit.</label>
                                            <input type="number" name="products[][price]" class="price-input w-full px-3 py-2 border border-gray-300 rounded-lg" 
                                                   value="{{ $item->price }}" step="0.01" min="0" onchange="calculateLineTotal(this)">
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3 text-right">
                                        <span class="text-sm text-gray-600">Subtotal: </span>
                                        <span class="line-total font-medium">R$ {{ number_format($item->quantity * $item->price, 2, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div id="no-products" class="text-center py-8 text-gray-500" style="display: {{ $order->items->count() > 0 ? 'none' : 'block' }}">
                            <i class="ti ti-package text-4xl mb-2"></i>
                            <p>Nenhum produto adicionado. Clique em "Adicionar Produto" para começar.</p>
                        </div>
                    </div>
                </div>

                <!-- Order Notes -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold">Observações</h3>
                    </div>
                    <div class="card-body">
                        <textarea name="notes" id="notes" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Observações adicionais sobre o pedido...">{{ $order->notes }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Order Summary -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold">Resumo do Pedido</h3>
                    </div>
                    <div class="card-body space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span id="subtotal">R$ {{ number_format($order->subtotal, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Desconto:</span>
                            <div class="flex items-center space-x-2">
                                <input type="number" name="discount" id="discount" value="{{ $order->discount ?? 0 }}" min="0" step="0.01" 
                                       class="w-20 px-2 py-1 border border-gray-300 rounded text-sm" onchange="calculateTotal()">
                                <span class="text-sm">%</span>
                            </div>
                        </div>
                        <div class="flex justify-between border-t pt-3">
                            <span class="font-semibold">Total:</span>
                            <span id="total" class="font-semibold text-lg">R$ {{ number_format($order->total, 2, ',', '.') }}</span>
                        </div>
                        
                        <input type="hidden" name="subtotal" id="subtotal_input" value="{{ $order->subtotal }}">
                        <input type="hidden" name="total" id="total_input" value="{{ $order->total }}">
                    </div>
                </div>

                <!-- Payment & Status -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold">Pagamento e Status</h3>
                    </div>
                    <div class="card-body space-y-4">
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Método de Pagamento</label>
                            <select name="payment_method" id="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="cash" {{ $order->payment_method === 'cash' ? 'selected' : '' }}>Dinheiro</option>
                                <option value="credit_card" {{ $order->payment_method === 'credit_card' ? 'selected' : '' }}>Cartão de Crédito</option>
                                <option value="debit_card" {{ $order->payment_method === 'debit_card' ? 'selected' : '' }}>Cartão de Débito</option>
                                <option value="bank_transfer" {{ $order->payment_method === 'bank_transfer' ? 'selected' : '' }}>Transferência</option>
                                <option value="pix" {{ $order->payment_method === 'pix' ? 'selected' : '' }}>PIX</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-2">Status do Pagamento</label>
                            <select name="payment_status" id="payment_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pendente</option>
                                <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Pago</option>
                                <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Falhou</option>
                                <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Reembolsado</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status do Pedido</label>
                            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pendente</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processando</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Enviado</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Entregue</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-body space-y-3">
                        <button type="submit" class="w-full btn bg-blue-600 text-white hover:bg-blue-700">
                            <i class="ti ti-check mr-2"></i>
                            Salvar Alterações
                        </button>
                        
                        @if($order->status !== 'cancelled')
                            <form method="POST" action="{{ route('admin.orders.cancel', $order) }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full btn bg-red-600 text-white hover:bg-red-700" 
                                        onclick="return confirm('Tem certeza que deseja cancelar este pedido?')">
                                    <i class="ti ti-x mr-2"></i>
                                    Cancelar Pedido
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Order History -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold">Histórico</h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span>Criado:</span>
                                <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Última atualização:</span>
                                <span>{{ $order->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Product Line Template -->
<template id="product-line-template">
    <div class="product-line border border-gray-200 rounded-lg p-4 mb-4">
        <div class="flex items-center justify-between mb-3">
            <h4 class="font-medium">Produto #<span class="line-number"></span></h4>
            <button type="button" onclick="removeProductLine(this)" class="text-red-600 hover:text-red-700">
                <i class="ti ti-trash"></i>
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Produto *</label>
                <select name="products[][product_id]" class="product-select w-full px-3 py-2 border border-gray-300 rounded-lg" required onchange="updateProductPrice(this)">
                    <option value="">Selecione um produto...</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                            {{ $product->name }} - R$ {{ number_format($product->price, 2, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Quantidade *</label>
                <input type="number" name="products[][quantity]" class="quantity-input w-full px-3 py-2 border border-gray-300 rounded-lg" 
                       value="1" min="1" required onchange="calculateLineTotal(this)">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Preço Unit.</label>
                <input type="number" name="products[][price]" class="price-input w-full px-3 py-2 border border-gray-300 rounded-lg" 
                       step="0.01" min="0" onchange="calculateLineTotal(this)">
            </div>
        </div>
        
        <div class="mt-3 text-right">
            <span class="text-sm text-gray-600">Subtotal: </span>
            <span class="line-total font-medium">R$ 0,00</span>
        </div>
    </div>
</template>
@endsection

@push('scripts')
<script>
let productLineCount = {{ $order->items->count() }};

function addProductLine() {
    productLineCount++;
    const template = document.getElementById('product-line-template');
    const clone = template.content.cloneNode(true);
    
    // Update line number
    clone.querySelector('.line-number').textContent = productLineCount;
    
    // Add to container
    const container = document.getElementById('products-container');
    container.appendChild(clone);
    
    // Hide no products message
    document.getElementById('no-products').style.display = 'none';
    
    calculateTotal();
}

function removeProductLine(button) {
    button.closest('.product-line').remove();
    
    // Show no products message if no lines
    const container = document.getElementById('products-container');
    if (container.children.length === 0) {
        document.getElementById('no-products').style.display = 'block';
    }
    
    calculateTotal();
}

function updateProductPrice(select) {
    const selectedOption = select.options[select.selectedIndex];
    const price = selectedOption.dataset.price || 0;
    const line = select.closest('.product-line');
    const priceInput = line.querySelector('.price-input');
    
    priceInput.value = price;
    calculateLineTotal(priceInput);
}

function calculateLineTotal(input) {
    const line = input.closest('.product-line');
    const quantity = parseFloat(line.querySelector('.quantity-input').value) || 0;
    const price = parseFloat(line.querySelector('.price-input').value) || 0;
    const total = quantity * price;
    
    line.querySelector('.line-total').textContent = `R$ ${total.toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
    
    calculateTotal();
}

function calculateTotal() {
    let subtotal = 0;
    
    // Sum all line totals
    document.querySelectorAll('.product-line').forEach(line => {
        const quantity = parseFloat(line.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(line.querySelector('.price-input').value) || 0;
        subtotal += quantity * price;
    });
    
    // Apply discount
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const discountAmount = subtotal * (discount / 100);
    const total = subtotal - discountAmount;
    
    // Update display
    document.getElementById('subtotal').textContent = `R$ ${subtotal.toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
    document.getElementById('total').textContent = `R$ ${total.toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
    
    // Update hidden inputs
    document.getElementById('subtotal_input').value = subtotal.toFixed(2);
    document.getElementById('total_input').value = total.toFixed(2);
}

// Initialize calculations
document.addEventListener('DOMContentLoaded', function() {
    calculateTotal();
});
</script>
@endpush 