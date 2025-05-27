@extends('layouts.ecommerce')

@section('title', $product->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8 text-sm">
        <a href="{{ route('ecommerce.home') }}" class="text-gray-600 hover:text-blue-600">Início</a>
        <span class="mx-2 text-gray-400">/</span>
        <a href="{{ route('ecommerce.products') }}" class="text-gray-600 hover:text-blue-600">Produtos</a>
        @if($product->category)
        <span class="mx-2 text-gray-400">/</span>
        <a href="{{ route('ecommerce.products', ['category' => $product->category->id]) }}" class="text-gray-600 hover:text-blue-600">{{ $product->category->name }}</a>
        @endif
        <span class="mx-2 text-gray-400">/</span>
        <span class="text-gray-900">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-12">
        <!-- Product Images -->
        <div class="space-y-4">
            <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                @if($product->main_image)
                    <img src="{{ $product->main_image }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                @endif
            </div>
            
            <!-- Additional images would go here if you have them -->
        </div>

        <!-- Product Information -->
        <div class="space-y-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                @if($product->category)
                    <p class="text-gray-600">{{ $product->category->name }}</p>
                @endif
                <p class="text-sm text-gray-500">SKU: {{ $product->sku }}</p>
            </div>

            <!-- Price -->
            <div class="space-y-2">
                @if($product->promotional_price && $product->isOnPromotion())
                    <div class="flex items-center space-x-3">
                        <span class="text-3xl font-bold text-red-600">R$ {{ number_format($product->promotional_price, 2, ',', '.') }}</span>
                        <span class="text-lg text-gray-500 line-through">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm font-medium">
                            {{ round((($product->price - $product->promotional_price) / $product->price) * 100) }}% OFF
                        </span>
                    </div>
                @else
                    <span class="text-3xl font-bold text-gray-900">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                @endif
            </div>

            <!-- Stock Status -->
            <div class="flex items-center space-x-2">
                @if($product->stock_quantity > 0)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Em estoque ({{ $product->stock_quantity }} unidades)
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        Fora de estoque
                    </span>
                @endif
            </div>

            <!-- Short Description -->
            @if($product->short_description)
                <div class="text-gray-700">
                    <p>{{ $product->short_description }}</p>
                </div>
            @endif

            <!-- Product Variants -->
            @if($product->variants && $product->variants->count() > 0)
                <div class="space-y-4 border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900">Selecione as opções:</h3>
                    
                    @php
                        $optionGroups = [];
                        foreach($product->variants as $variant) {
                            foreach($variant->option_values as $optionName => $optionValue) {
                                if (!isset($optionGroups[$optionName])) {
                                    $optionGroups[$optionName] = [];
                                }
                                if (!in_array($optionValue, $optionGroups[$optionName])) {
                                    $optionGroups[$optionName][] = $optionValue;
                                }
                            }
                        }
                    @endphp
                    
                    @foreach($optionGroups as $optionName => $optionValues)
                        <div class="variant-option-group" data-option="{{ $optionName }}">
                            <label class="text-sm font-medium text-gray-700 block mb-2">{{ ucfirst($optionName) }}:</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($optionValues as $optionValue)
                                    <button type="button" 
                                            class="variant-option px-4 py-2 border border-gray-300 rounded-md hover:border-blue-500 hover:text-blue-600 transition-colors"
                                            data-option="{{ $optionName }}"
                                            data-value="{{ $optionValue }}">
                                        {{ $optionValue }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                    
                    <!-- Selected variant info -->
                    <div id="selectedVariantInfo" class="hidden bg-blue-50 border border-blue-200 rounded-md p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-900" id="selectedVariantName"></p>
                                <p class="text-lg font-bold text-blue-600" id="selectedVariantPrice"></p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-blue-700" id="selectedVariantStock"></p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Add to Cart Form -->
            @if($product->stock_quantity > 0 || ($product->variants && $product->variants->count() > 0))
                <form id="addToCartForm" class="space-y-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="variant_id" id="selectedVariantId" value="">
                    
                    <div class="flex items-center space-x-4">
                        <label for="quantity" class="text-sm font-medium text-gray-700">Quantidade:</label>
                        <div class="flex items-center border border-gray-300 rounded-md">
                            <button type="button" onclick="decreaseQuantity()" class="px-3 py-2 text-gray-600 hover:text-gray-800">−</button>
                            <input type="number" 
                                   id="quantity" 
                                   name="quantity" 
                                   value="1" 
                                   min="1" 
                                   max="{{ $product->stock_quantity }}" 
                                   class="w-16 px-3 py-2 text-center border-0 focus:ring-0">
                            <button type="button" onclick="increaseQuantity()" class="px-3 py-2 text-gray-600 hover:text-gray-800">+</button>
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <button type="submit" 
                                class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition-colors font-medium">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5-5m6 5a2 2 0 104 0 2 2 0 00-4 0zm-6 0a2 2 0 104 0 2 2 0 00-4 0z"/>
                                </svg>
                                Adicionar ao Carrinho
                            </span>
                        </button>
                        
                        <button type="button" 
                                onclick="addToWishlist({{ $product->id }})"
                                class="px-6 py-3 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </button>
                    </div>
                </form>
            @else
                <div class="bg-gray-100 border border-gray-300 rounded-md p-4 text-center">
                    <p class="text-gray-600">Este produto está temporariamente indisponível</p>
                    <button class="mt-2 text-blue-600 hover:text-blue-800 text-sm underline">
                        Avisar quando estiver disponível
                    </button>
                </div>
            @endif

            <!-- Product Features -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Características</h3>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Entrega rápida e segura
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Garantia do fabricante
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Suporte técnico especializado
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Product Description -->
    @if($product->description)
        <div class="bg-white rounded-lg border border-gray-200 p-6 mb-12">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Descrição do Produto</h2>
            <div class="prose max-w-none text-gray-700">
                {!! nl2br(e($product->description)) !!}
            </div>
        </div>
    @endif

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-semibold text-gray-900 mb-8">Produtos Relacionados</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="bg-white rounded-lg border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                        <a href="{{ route('ecommerce.product', $relatedProduct) }}" class="block">
                            <div class="aspect-square bg-gray-100 rounded-t-lg overflow-hidden">
                                @if($relatedProduct->main_image)
                                    <img src="{{ $relatedProduct->main_image }}" 
                                         alt="{{ $relatedProduct->name }}" 
                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-medium text-gray-900 mb-2 line-clamp-2">{{ $relatedProduct->name }}</h3>
                                <div class="flex items-center justify-between">
                                    @if($relatedProduct->promotional_price && $relatedProduct->isOnPromotion())
                                        <div class="flex flex-col">
                                            <span class="text-lg font-bold text-red-600">R$ {{ number_format($relatedProduct->promotional_price, 2, ',', '.') }}</span>
                                            <span class="text-sm text-gray-500 line-through">R$ {{ number_format($relatedProduct->price, 2, ',', '.') }}</span>
                                        </div>
                                    @else
                                        <span class="text-lg font-bold text-gray-900">R$ {{ number_format($relatedProduct->price, 2, ',', '.') }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-md shadow-lg transform translate-x-full transition-transform duration-300 z-50">
    <div class="flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <span id="toast-message">Produto adicionado ao carrinho!</span>
    </div>
</div>

<!-- Modal de Confirmação -->
<div id="cartModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="cartModalContent">
        <div class="p-6">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Produto adicionado!</h3>
                <p class="text-gray-600" id="productAddedMessage">{{ $product->name }} foi adicionado ao seu carrinho.</p>
            </div>

            <div class="flex space-x-3">
                <button onclick="continueShoppingModal()" 
                        class="flex-1 bg-gray-100 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Continuar Comprando
                </button>
                <button onclick="goToCheckout()" 
                        class="flex-1 bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    Finalizar Compra
                </button>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Itens no carrinho:</span>
                    <span class="font-medium" id="cartItemCount">0</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Total:</span>
                    <span class="font-bold text-lg" id="cartTotal">R$ 0,00</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Product variants data
const productVariants = @json($product->variants ?? []);
const selectedOptions = {};
let selectedVariant = null;

document.addEventListener('DOMContentLoaded', function() {
    const addToCartForm = document.getElementById('addToCartForm');
    const quantityInput = document.getElementById('quantity');
    let maxQuantity = {{ $product->stock_quantity }};

    // Initialize variant system
    initializeVariants();

    // Variant selection handlers
    document.querySelectorAll('.variant-option').forEach(button => {
        button.addEventListener('click', function() {
            const option = this.dataset.option;
            const value = this.dataset.value;
            
            selectVariantOption(option, value);
            updateSelectedVariant();
        });
    });

    // Add to cart functionality
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route("ecommerce.cart.add") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showCartModal(data);
                    updateCartCount(data.cart_count);
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Erro ao adicionar produto ao carrinho', 'error');
            });
        });
    }

    // Quantity controls
    window.increaseQuantity = function() {
        const currentValue = parseInt(quantityInput.value);
        if (currentValue < maxQuantity) {
            quantityInput.value = currentValue + 1;
        }
    };

    window.decreaseQuantity = function() {
        const currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
        }
    };

    // Wishlist functionality (placeholder)
    window.addToWishlist = function(productId) {
        showToast('Funcionalidade em desenvolvimento', 'info');
    };
});

// Variant management functions
function initializeVariants() {
    // If product has variants, require variant selection
    if (productVariants.length > 0) {
        const addToCartButton = document.querySelector('#addToCartForm button[type="submit"]');
        if (addToCartButton) {
            addToCartButton.disabled = true;
            addToCartButton.textContent = 'Selecione as opções';
            addToCartButton.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
}

function selectVariantOption(option, value) {
    selectedOptions[option] = value;
    
    // Update UI - remove active class from all options in this group
    document.querySelectorAll(`[data-option="${option}"]`).forEach(btn => {
        btn.classList.remove('border-blue-500', 'bg-blue-500', 'text-white');
        btn.classList.add('border-gray-300');
    });
    
    // Add active class to selected option
    const selectedButton = document.querySelector(`[data-option="${option}"][data-value="${value}"]`);
    if (selectedButton) {
        selectedButton.classList.remove('border-gray-300');
        selectedButton.classList.add('border-blue-500', 'bg-blue-500', 'text-white');
    }
}

function updateSelectedVariant() {
    // Find variant that matches all selected options
    selectedVariant = productVariants.find(variant => {
        return Object.keys(selectedOptions).every(option => {
            return variant.option_values[option] === selectedOptions[option];
        });
    });
    
    const addToCartButton = document.querySelector('#addToCartForm button[type="submit"]');
    const selectedVariantInfo = document.getElementById('selectedVariantInfo');
    const quantityInput = document.getElementById('quantity');
    const selectedVariantId = document.getElementById('selectedVariantId');
    
    if (selectedVariant) {
        // Update variant info display
        document.getElementById('selectedVariantName').textContent = selectedVariant.name;
        
        // Calculate final price (base price + adjustment)
        const basePrice = {{ $product->price }};
        const finalPrice = basePrice + (selectedVariant.price_adjustment || 0);
        document.getElementById('selectedVariantPrice').textContent = 'R$ ' + finalPrice.toFixed(2).replace('.', ',');
        
        // Update stock info
        const stockText = selectedVariant.stock_quantity > 0 ? 
            `${selectedVariant.stock_quantity} em estoque` : 
            'Fora de estoque';
        document.getElementById('selectedVariantStock').textContent = stockText;
        
        selectedVariantInfo.classList.remove('hidden');
        
        // Update form
        selectedVariantId.value = selectedVariant.id;
        quantityInput.max = selectedVariant.stock_quantity;
        
        // Update add to cart button
        if (selectedVariant.stock_quantity > 0) {
            addToCartButton.disabled = false;
            addToCartButton.textContent = 'Adicionar ao Carrinho';
            addToCartButton.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            addToCartButton.disabled = true;
            addToCartButton.textContent = 'Fora de estoque';
            addToCartButton.classList.add('opacity-50', 'cursor-not-allowed');
        }
    } else {
        // No variant selected or invalid combination
        selectedVariantInfo.classList.add('hidden');
        selectedVariantId.value = '';
        
        if (productVariants.length > 0) {
            addToCartButton.disabled = true;
            addToCartButton.textContent = 'Selecione as opções';
            addToCartButton.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
}

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toast-message');
    
    toastMessage.textContent = message;
    
    // Set color based on type
    toast.className = toast.className.replace(/bg-\w+-500/, '');
    switch(type) {
        case 'error':
            toast.classList.add('bg-red-500');
            break;
        case 'info':
            toast.classList.add('bg-blue-500');
            break;
        default:
            toast.classList.add('bg-green-500');
    }
    
    // Show toast
    toast.classList.remove('translate-x-full');
    
    // Hide after 3 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full');
    }, 3000);
}

function updateCartCount(count) {
    const cartCountElements = document.querySelectorAll('[data-cart-count]');
    cartCountElements.forEach(element => {
        element.textContent = count;
        if (count > 0) {
            element.classList.remove('hidden');
        }
    });
}

function showCartModal(data) {
    const modal = document.getElementById('cartModal');
    const modalContent = document.getElementById('cartModalContent');
    const cartItemCount = document.getElementById('cartItemCount');
    const cartTotal = document.getElementById('cartTotal');
    
    // Atualizar informações do modal
    cartItemCount.textContent = data.cart_count;
    cartTotal.textContent = data.cart_total || 'R$ 0,00';
    
    // Mostrar modal
    modal.classList.remove('hidden');
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function continueShoppingModal() {
    const modal = document.getElementById('cartModal');
    const modalContent = document.getElementById('cartModalContent');
    
    modalContent.classList.add('scale-95', 'opacity-0');
    modalContent.classList.remove('scale-100', 'opacity-100');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function goToCheckout() {
    window.location.href = '{{ route("ecommerce.cart") }}';
}

// Fechar modal ao clicar fora
document.addEventListener('click', function(e) {
    const modal = document.getElementById('cartModal');
    if (e.target === modal) {
        continueShoppingModal();
    }
});
</script>
@endpush 