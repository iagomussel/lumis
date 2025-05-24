@extends('layouts.ecommerce')

@section('title', 'Produtos')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Nossos Produtos</h1>
        <p class="text-gray-600">Encontre exatamente o que você está procurando</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Filters -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6 sticky top-24">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtros</h3>
                
                <form method="GET" action="{{ route('ecommerce.products') }}" id="filter-form">
                    <!-- Search -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Nome do produto..." 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Categories -->
                    @if($categories->count() > 0)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
                        <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todas as categorias</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ $category->products_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <!-- Price Range -->
                    @if($priceRange && $priceRange->min_price && $priceRange->max_price)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Faixa de Preço</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" 
                                   placeholder="Min: R$ {{ number_format($priceRange->min_price, 0) }}" 
                                   class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" 
                                   placeholder="Max: R$ {{ number_format($priceRange->max_price, 0) }}" 
                                   class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    @endif

                    <!-- Promotion Filter -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="on_promotion" value="1" {{ request('on_promotion') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Apenas promoções</span>
                        </label>
                    </div>

                    <!-- Sort -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ordenar por</label>
                        <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nome A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nome Z-A</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Menor preço</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Maior preço</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mais recentes</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Melhor avaliados</option>
                        </select>
                    </div>

                    <div class="flex space-x-2">
                        <button type="submit" class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                            Filtrar
                        </button>
                        <a href="{{ route('ecommerce.products') }}" class="flex-1 bg-gray-100 text-gray-700 py-2 px-4 rounded-lg text-center hover:bg-gray-200 transition-colors">
                            Limpar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="lg:col-span-3">
            <!-- Results Info -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <p class="text-gray-600">
                        Mostrando {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} 
                        de {{ $products->total() }} produtos
                    </p>
                </div>
                
                <!-- Mobile Filter Toggle -->
                <button class="lg:hidden bg-blue-600 text-white px-4 py-2 rounded-lg" onclick="toggleMobileFilters()">
                    <i class="ti ti-filter mr-2"></i>
                    Filtros
                </button>
            </div>

            @if($products->count() > 0)
                <!-- Products Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                    @foreach($products as $product)
                        @include('ecommerce.partials.product-card', ['product' => $product])
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $products->links() }}
                </div>
            @else
                <!-- No Products -->
                <div class="text-center py-12">
                    <i class="ti ti-package-off text-6xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Nenhum produto encontrado</h3>
                    <p class="text-gray-600 mb-6">Tente ajustar os filtros ou buscar por outros termos</p>
                    <a href="{{ route('ecommerce.products') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Ver todos os produtos
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Mobile Filters Modal -->
<div id="mobile-filters" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 hidden lg:hidden">
    <div class="fixed inset-y-0 left-0 w-80 bg-white shadow-lg overflow-y-auto">
        <div class="flex items-center justify-between p-4 border-b">
            <h2 class="text-lg font-semibold">Filtros</h2>
            <button onclick="toggleMobileFilters()" class="text-gray-500 hover:text-gray-700">
                <i class="ti ti-x text-xl"></i>
            </button>
        </div>
        <div class="p-4">
            <!-- Same filter form content as sidebar -->
            <form method="GET" action="{{ route('ecommerce.products') }}">
                <!-- Repeat the same filter inputs here -->
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleMobileFilters() {
    const modal = document.getElementById('mobile-filters');
    modal.classList.toggle('hidden');
}

// Auto-submit form on filter change
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filter-form');
    const selects = form.querySelectorAll('select');
    const checkboxes = form.querySelectorAll('input[type="checkbox"]');
    
    selects.forEach(select => {
        select.addEventListener('change', function() {
            form.submit();
        });
    });
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            form.submit();
        });
    });
});

// Add to cart function (same as homepage)
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
            document.getElementById('cart-count').textContent = data.cart_count;
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