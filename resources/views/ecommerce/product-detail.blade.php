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

            <!-- Add to Cart Form -->
            @if($product->stock_quantity > 0)
                <form id="addToCartForm" class="space-y-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
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

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const addToCartForm = document.getElementById('addToCartForm');
    const quantityInput = document.getElementById('quantity');
    const maxQuantity = {{ $product->stock_quantity }};

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
                    showToast(data.message, 'success');
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
</script>
@endpush 