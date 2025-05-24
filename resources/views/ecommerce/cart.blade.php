@extends('layouts.ecommerce')

@section('title', 'Carrinho de Compras')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('ecommerce.home') }}" class="text-gray-700 hover:text-blue-600">
                    <i class="ti ti-home mr-2"></i>Início
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="ti ti-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-gray-500">Carrinho</span>
                </div>
            </li>
        </ol>
    </nav>

    @if(count($cartItems) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">
                            <i class="ti ti-shopping-cart mr-2"></i>
                            Meu Carrinho ({{ count($cartItems) }} {{ count($cartItems) == 1 ? 'item' : 'itens' }})
                        </h2>
                    </div>

                    <div class="divide-y divide-gray-200">
                        @foreach($cartItems as $item)
                            <div class="p-6 cart-item" data-product-id="{{ $item['product']->id }}">
                                <div class="flex items-center space-x-4">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center">
                                            @if($item['product']->main_image)
                                                <img src="{{ $item['product']->main_image }}" 
                                                     alt="{{ $item['product']->name }}" 
                                                     class="w-full h-full object-cover rounded-lg">
                                            @else
                                                <i class="ti ti-package text-gray-400 text-2xl"></i>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-medium text-gray-900 mb-1">
                                            <a href="{{ route('ecommerce.product', $item['product']->id) }}" 
                                               class="hover:text-blue-600">
                                                {{ $item['product']->name }}
                                            </a>
                                        </h3>
                                        
                                        @if($item['product']->category)
                                            <p class="text-sm text-gray-500 mb-2">{{ $item['product']->category->name }}</p>
                                        @endif

                                        <p class="text-sm text-gray-600 mb-2">SKU: {{ $item['product']->sku }}</p>

                                        <!-- Price -->
                                        <div class="flex items-center space-x-2">
                                            @if($item['product']->is_on_promotion)
                                                <span class="text-sm text-gray-500 line-through">{{ $item['product']->formatted_price }}</span>
                                                <span class="text-lg font-bold text-green-600">{{ $item['product']->formatted_current_price }}</span>
                                                <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">
                                                    -{{ $item['product']->discount_percentage }}%
                                                </span>
                                            @else
                                                <span class="text-lg font-bold text-green-600">{{ $item['product']->formatted_current_price }}</span>
                                            @endif
                                        </div>

                                        <!-- Stock Info -->
                                        @if($item['product']->stock_quantity <= 5)
                                            <p class="text-sm text-orange-600 mt-1">
                                                <i class="ti ti-alert-triangle mr-1"></i>
                                                Apenas {{ $item['product']->stock_quantity }} em estoque
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Quantity Controls -->
                                    <div class="flex items-center space-x-3">
                                        <div class="flex items-center border border-gray-300 rounded-lg">
                                            <button class="px-3 py-2 hover:bg-gray-100 rounded-l-lg quantity-btn" 
                                                    data-action="decrease" data-product-id="{{ $item['product']->id }}">
                                                <i class="ti ti-minus text-sm"></i>
                                            </button>
                                            <input type="number" 
                                                   value="{{ $item['quantity'] }}" 
                                                   min="1" 
                                                   max="{{ $item['product']->stock_quantity }}"
                                                   class="w-16 px-2 py-2 text-center border-0 focus:ring-0 quantity-input"
                                                   data-product-id="{{ $item['product']->id }}">
                                            <button class="px-3 py-2 hover:bg-gray-100 rounded-r-lg quantity-btn" 
                                                    data-action="increase" data-product-id="{{ $item['product']->id }}">
                                                <i class="ti ti-plus text-sm"></i>
                                            </button>
                                        </div>

                                        <!-- Remove Button -->
                                        <button class="text-red-500 hover:text-red-700 p-2 remove-item" 
                                                data-product-id="{{ $item['product']->id }}"
                                                title="Remover item">
                                            <i class="ti ti-trash text-lg"></i>
                                        </button>
                                    </div>

                                    <!-- Item Total -->
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-gray-900 item-total">
                                            R$ {{ number_format($item['total'], 2, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Cart Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <button class="text-red-600 hover:text-red-800 font-medium" id="clear-cart">
                                <i class="ti ti-trash mr-2"></i>
                                Limpar Carrinho
                            </button>
                            
                            <a href="{{ route('ecommerce.products') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                <i class="ti ti-arrow-left mr-2"></i>
                                Continuar Comprando
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm sticky top-24">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Resumo do Pedido</h3>
                    </div>

                    <div class="p-6">
                        <!-- Coupon Input -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cupom de Desconto</label>
                            <div class="flex space-x-2">
                                <input type="text" 
                                       placeholder="Digite seu cupom" 
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       id="coupon-input">
                                <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors"
                                        id="apply-coupon">
                                    Aplicar
                                </button>
                            </div>
                        </div>

                        <!-- Price Breakdown -->
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium" id="subtotal">R$ {{ number_format($total, 2, ',', '.') }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Frete</span>
                                <span class="font-medium text-green-600">Grátis</span>
                            </div>
                            
                            <div class="flex justify-between text-sm" id="discount-row" style="display: none;">
                                <span class="text-gray-600">Desconto</span>
                                <span class="font-medium text-green-600" id="discount-amount">-R$ 0,00</span>
                            </div>
                            
                            <hr class="border-gray-200">
                            
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total</span>
                                <span id="final-total">R$ {{ number_format($total, 2, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        <button id="checkout-btn" 
                                class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                            <i class="ti ti-credit-card mr-2"></i>
                            Finalizar Compra
                        </button>

                        <!-- Security -->
                        <div class="mt-4 text-center">
                            <div class="flex items-center justify-center text-sm text-gray-500">
                                <i class="ti ti-shield-check text-green-500 mr-2"></i>
                                Compra 100% segura
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="text-center py-16">
            <div class="max-w-md mx-auto">
                <i class="ti ti-shopping-cart-off text-8xl text-gray-400 mb-6"></i>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Seu carrinho está vazio</h2>
                <p class="text-gray-600 mb-8">
                    Parece que você ainda não adicionou nenhum item ao seu carrinho. 
                    Que tal explorar nossos produtos?
                </p>
                <div class="space-y-4">
                    <a href="{{ route('ecommerce.products') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="ti ti-shopping-bag mr-2"></i>
                        Ver Produtos
                    </a>
                    <div>
                        <a href="{{ route('ecommerce.home') }}" 
                           class="text-gray-600 hover:text-gray-900">
                            ← Voltar ao início
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity controls
    document.querySelectorAll('.quantity-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const action = this.dataset.action;
            const productId = this.dataset.productId;
            const input = document.querySelector(`input[data-product-id="${productId}"]`);
            const currentValue = parseInt(input.value);
            
            let newValue = currentValue;
            if (action === 'increase') {
                newValue = Math.min(currentValue + 1, parseInt(input.max));
            } else if (action === 'decrease') {
                newValue = Math.max(currentValue - 1, 1);
            }
            
            if (newValue !== currentValue) {
                input.value = newValue;
                updateCartItem(productId, newValue);
            }
        });
    });

    // Quantity input change
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const productId = this.dataset.productId;
            const quantity = Math.max(1, Math.min(parseInt(this.value) || 1, parseInt(this.max)));
            this.value = quantity;
            updateCartItem(productId, quantity);
        });
    });

    // Remove item
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            if (confirm('Tem certeza que deseja remover este item do carrinho?')) {
                removeCartItem(productId);
            }
        });
    });

    // Clear cart
    document.getElementById('clear-cart')?.addEventListener('click', function() {
        if (confirm('Tem certeza que deseja limpar todo o carrinho?')) {
            clearCart();
        }
    });

    // Checkout button
    document.getElementById('checkout-btn')?.addEventListener('click', function() {
        window.location.href = '{{ route('ecommerce.checkout') }}';
    });

    // Apply coupon
    document.getElementById('apply-coupon')?.addEventListener('click', function() {
        const coupon = document.getElementById('coupon-input').value.trim();
        if (coupon) {
            applyCoupon(coupon);
        }
    });
});

function updateCartItem(productId, quantity) {
    showLoading();
    
    fetch('{{ route('ecommerce.cart.update') }}', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            location.reload(); // Reload to update totals
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Erro:', error);
        showNotification('Erro ao atualizar carrinho', 'error');
    });
}

function removeCartItem(productId) {
    showLoading();
    
    fetch('{{ route('ecommerce.cart.remove') }}', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            location.reload();
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Erro:', error);
        showNotification('Erro ao remover item', 'error');
    });
}

function clearCart() {
    showLoading();
    
    fetch('{{ route('ecommerce.cart.clear') }}', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            location.reload();
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Erro:', error);
        showNotification('Erro ao limpar carrinho', 'error');
    });
}

function applyCoupon(coupon) {
    showNotification('Funcionalidade de cupom será implementada em breve!', 'info');
}

function showNotification(message, type = 'info') {
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
    };
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}
</script>
@endpush 