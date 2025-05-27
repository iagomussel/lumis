@extends('layouts.ecommerce')

@section('title', 'Loja Online')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
    <div class="container mx-auto px-4 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-4xl lg:text-6xl font-bold mb-6">
                    Bem-vindo à nossa 
                    <span class="text-yellow-300">Loja Online</span>
                </h1>
                <p class="text-xl mb-8 text-blue-100">
                    Descubra os melhores produtos com qualidade garantida e entrega rápida.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('ecommerce.products') }}" 
                       class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors text-center">
                        <i class="ti ti-shopping-cart mr-2"></i>
                        Ver Produtos
                    </a>
                    <a href="{{ route('ecommerce.products', ['on_promotion' => 1]) }}" 
                       class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors text-center">
                        <i class="ti ti-discount mr-2"></i>
                        Promoções
                    </a>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="relative">
                    <div class="w-full aspect-square bg-white bg-opacity-20 rounded-2xl backdrop-blur-sm overflow-hidden">
                        <img src="/images/branding/hero.png" alt="Hero" class="w-full h-full object-cover" />
                    </div>
                    <!-- Floating cards -->
                    <div class="absolute -top-4 -left-4 bg-yellow-400 text-yellow-900 p-4 rounded-lg shadow-lg">
                        <i class="ti ti-truck text-2xl"></i>
                        <p class="text-sm font-semibold mt-1">Entrega Rápida</p>
                    </div>
                    <div class="absolute -bottom-4 -right-4 bg-green-400 text-green-900 p-4 rounded-lg shadow-lg">
                        <i class="ti ti-shield-check text-2xl"></i>
                        <p class="text-sm font-semibold mt-1">Compra Segura</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ti ti-truck text-blue-600 text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Entrega Rápida</h3>
                <p class="text-gray-600 text-sm">Receba seus produtos com agilidade e segurança</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ti ti-shield-check text-green-600 text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Compra Segura</h3>
                <p class="text-gray-600 text-sm">Suas informações protegidas e pagamento seguro</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ti ti-refresh text-purple-600 text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Troca Fácil</h3>
                <p class="text-gray-600 text-sm">Política de trocas flexível e sem complicações</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ti ti-headset text-orange-600 text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Suporte 24h</h3>
                <p class="text-gray-600 text-sm">Atendimento especializado quando precisar</p>
            </div>
        </div>
    </div>
</section>

<!-- Categories -->
@if($categories->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Nossas Categorias</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Explore nossa ampla variedade de produtos organizados por categoria
            </p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($categories as $category)
                <a href="{{ route('ecommerce.products', ['category' => $category->id]) }}" 
                   class="group bg-white rounded-xl p-6 text-center hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                        <i class="ti ti-category text-white text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">{{ $category->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $category->products_count }} produtos</p>
                </a>
            @endforeach
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('ecommerce.products') }}" 
               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Ver Todos os Produtos
                <i class="ti ti-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Featured Products -->
@if($featuredProducts->count() > 0)
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Produtos em Destaque</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Os produtos mais populares e recomendados da nossa loja
            </p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
                @include('ecommerce.partials.product-card', ['product' => $product])
            @endforeach
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('ecommerce.products') }}" 
               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Ver Mais Produtos
                <i class="ti ti-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Promotional Products -->
@if($promotionalProducts->count() > 0)
<section class="py-16 bg-gradient-to-r from-red-500 to-pink-500 text-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold mb-4">
                <i class="ti ti-flame mr-2"></i>
                Ofertas Especiais
            </h2>
            <p class="text-red-100 max-w-2xl mx-auto">
                Aproveite os produtos com os melhores preços por tempo limitado
            </p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($promotionalProducts as $product)
                <div class="bg-white rounded-xl p-6 text-gray-900 hover:shadow-xl transition-shadow">
                    <div class="relative">
                        @if($product->is_on_promotion)
                            <div class="absolute top-0 right-0 bg-red-500 text-white px-2 py-1 rounded-bl-lg text-sm font-semibold">
                                -{{ $product->discount_percentage }}%
                            </div>
                        @endif
                        
                        <a href="{{ route('ecommerce.product', $product->id) }}" class="block w-full h-48 bg-gray-100 rounded-lg mb-4 flex items-center justify-center group">
                            @if($product->main_image)
                                <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-lg group-hover:scale-105 transition-transform duration-300">
                            @else
                                <i class="ti ti-package text-gray-400 text-4xl"></i>
                            @endif
                        </a>
                        
                        <h3 class="font-semibold text-lg mb-2">
                            <a href="{{ route('ecommerce.product', $product->id) }}" class="hover:text-blue-600 transition-colors">
                                {{ $product->name }}
                            </a>
                        </h3>
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($product->short_description ?: $product->description, 80) }}</p>
                        
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                @if($product->is_on_promotion)
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-500 line-through">{{ $product->formatted_price }}</span>
                                        <span class="text-xl font-bold text-green-600">{{ $product->formatted_promotional_price }}</span>
                                    </div>
                                @else
                                    <span class="text-xl font-bold text-green-600">{{ $product->formatted_price }}</span>
                                @endif
                            </div>
                            
                            @if($product->stock_quantity > 0)
                                <span class="text-sm text-green-600 bg-green-100 px-2 py-1 rounded">Em estoque</span>
                            @else
                                <span class="text-sm text-red-600 bg-red-100 px-2 py-1 rounded">Esgotado</span>
                            @endif
                        </div>
                        
                        <div class="flex space-x-2">
                            <a href="{{ route('ecommerce.product', $product->id) }}" 
                               class="flex-1 bg-gray-100 text-gray-700 py-2 px-4 rounded-lg text-center hover:bg-gray-200 transition-colors">
                                Ver Detalhes
                            </a>
                            @if($product->stock_quantity > 0)
                                <a href="{{ route('ecommerce.product', $product->id) }}" 
                                   class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors text-center">
                                    <i class="ti ti-shopping-cart mr-1"></i>
                                    Comprar
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('ecommerce.products', ['on_promotion' => 1]) }}" 
               class="inline-flex items-center px-6 py-3 bg-white text-red-500 rounded-lg hover:bg-gray-100 transition-colors font-semibold">
                Ver Todas as Ofertas
                <i class="ti ti-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Latest Products -->
@if($latestProducts->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Novidades</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Confira os produtos mais recentes da nossa loja
            </p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($latestProducts as $product)
                @include('ecommerce.partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Newsletter -->
<section class="py-16 bg-blue-600 text-white">
    <div class="container mx-auto px-4 text-center">
        <div class="max-w-2xl mx-auto">
            <h2 class="text-3xl font-bold mb-4">Fique por dentro das novidades</h2>
            <p class="text-blue-100 mb-8">
                Receba ofertas exclusivas e seja o primeiro a saber sobre nossos lançamentos
            </p>
            <form class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                <input 
                    type="email" 
                    placeholder="Seu melhor e-mail" 
                    class="flex-1 px-4 py-3 rounded-lg text-gray-900 focus:ring-2 focus:ring-blue-300 focus:outline-none">
                <button type="submit" 
                        class="bg-yellow-400 text-yellow-900 px-6 py-3 rounded-lg font-semibold hover:bg-yellow-300 transition-colors">
                    Inscrever-se
                </button>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Add to cart function
function addToCart(productId, quantity = 1) {
    showLoading();
    
    fetch('{{ route('ecommerce.cart.add') }}', {
        method: 'POST',
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
            // Update cart count
            document.getElementById('cart-count').textContent = data.cart_count;
            
            // Show success message
            showNotification('Produto adicionado ao carrinho!', 'success');
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Erro:', error);
        showNotification('Erro ao adicionar produto ao carrinho', 'error');
    });
}

// Notification function
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
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}
</script>
@endpush 