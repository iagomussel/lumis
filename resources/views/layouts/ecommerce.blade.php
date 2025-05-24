<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'E-commerce') - {{ config('app.name', 'Lumis Presentes') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-gray-50 font-['Plus_Jakarta_Sans']">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <!-- Top Bar -->
        <div class="bg-blue-600 text-white py-2 text-sm">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <span><i class="ti ti-phone mr-1"></i> (21) 99577-5689</span>
                        <span><i class="ti ti-mail mr-1"></i> contato@lumispresentes.com.br</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        @auth('customer')
                            <a href="{{ route('customer.dashboard') }}" class="hover:text-blue-200">
                                <i class="ti ti-user mr-1"></i> {{ Auth::guard('customer')->user()->name }}
                            </a>
                            <form method="POST" action="{{ route('customer.logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="hover:text-blue-200">
                                    <i class="ti ti-logout mr-1"></i> Sair
                                </button>
                            </form>
                        @else
                            <a href="{{ route('customer.login') }}" class="hover:text-blue-200">
                                <i class="ti ti-login mr-1"></i> Entrar
                            </a>
                            <a href="{{ route('customer.register') }}" class="hover:text-blue-200">
                                <i class="ti ti-user-plus mr-1"></i> Cadastrar
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Header -->
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('ecommerce.home') }}" class="flex items-center">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="ti ti-shopping-bag text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ config('app.name') }}</h1>
                            <p class="text-xs text-gray-500">E-commerce</p>
                        </div>
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="hidden md:flex flex-1 max-w-lg mx-8">
                    <div class="relative w-full">
                        <input 
                            type="text" 
                            id="search-input"
                            placeholder="Buscar produtos..." 
                            class="w-full pl-4 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <button class="absolute right-0 top-0 h-full px-4 text-gray-400 hover:text-gray-600">
                            <i class="ti ti-search"></i>
                        </button>
                        
                        <!-- Search Results -->
                        <div id="search-results" class="absolute top-full left-0 w-full bg-white border border-gray-200 rounded-lg shadow-lg mt-1 hidden z-50 max-h-96 overflow-y-auto">
                            <!-- Results will be loaded here -->
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center space-x-4">
                    <!-- Mobile Search -->
                    <button class="md:hidden text-gray-600 hover:text-gray-900" id="mobile-search-btn">
                        <i class="ti ti-search text-xl"></i>
                    </button>

                    <!-- Cart -->
                    <a href="{{ route('ecommerce.cart') }}" class="relative text-gray-600 hover:text-gray-900">
                        <i class="ti ti-shopping-cart text-2xl"></i>
                        <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            0
                        </span>
                    </a>

                    <!-- Menu Mobile -->
                    <button class="md:hidden text-gray-600 hover:text-gray-900" id="mobile-menu-btn">
                        <i class="ti ti-menu-2 text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="bg-gray-100 border-t border-gray-200">
            <div class="container mx-auto px-4">
                <div class="flex items-center space-x-8 py-3 overflow-x-auto">
                    <a href="{{ route('ecommerce.home') }}" 
                       class="text-gray-700 hover:text-blue-600 font-medium whitespace-nowrap {{ request()->routeIs('ecommerce.home') ? 'text-blue-600' : '' }}">
                        <i class="ti ti-home mr-1"></i> Início
                    </a>
                    <a href="{{ route('ecommerce.products') }}" 
                       class="text-gray-700 hover:text-blue-600 font-medium whitespace-nowrap {{ request()->routeIs('ecommerce.products') ? 'text-blue-600' : '' }}">
                        <i class="ti ti-package mr-1"></i> Produtos
                    </a>
                    
                    @php
                        $categories = \App\Models\Category::active()
                            ->withCount(['products' => function($query) {
                                $query->availableOnline();
                            }])
                            ->having('products_count', '>', 0)
                            ->limit(6)
                            ->get();
                    @endphp
                    
                    @foreach($categories as $category)
                        <a href="{{ route('ecommerce.products', ['category' => $category->id]) }}" 
                           class="text-gray-700 hover:text-blue-600 font-medium whitespace-nowrap">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </nav>

        <!-- Mobile Search Bar -->
        <div id="mobile-search" class="md:hidden bg-white border-t border-gray-200 px-4 py-3 hidden">
            <div class="relative">
                <input 
                    type="text" 
                    id="mobile-search-input"
                    placeholder="Buscar produtos..." 
                    class="w-full pl-4 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <button class="absolute right-0 top-0 h-full px-4 text-gray-400 hover:text-gray-600">
                    <i class="ti ti-search"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 hidden md:hidden">
        <div class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg">
            <div class="flex items-center justify-between p-4 border-b">
                <h2 class="text-lg font-semibold">Menu</h2>
                <button id="close-mobile-menu" class="text-gray-500 hover:text-gray-700">
                    <i class="ti ti-x text-xl"></i>
                </button>
            </div>
            <nav class="p-4">
                <ul class="space-y-2">
                    <li><a href="{{ route('ecommerce.home') }}" class="block py-2 text-gray-700 hover:text-blue-600">Início</a></li>
                    <li><a href="{{ route('ecommerce.products') }}" class="block py-2 text-gray-700 hover:text-blue-600">Produtos</a></li>
                    @foreach($categories as $category)
                        <li>
                            <a href="{{ route('ecommerce.products', ['category' => $category->id]) }}" 
                               class="block py-2 text-gray-700 hover:text-blue-600">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-2">
                            <i class="ti ti-shopping-bag text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold">{{ config('app.name') }}</h3>
                    </div>
                    <p class="text-gray-300 mb-4">
                        Sua loja online completa com os melhores produtos e atendimento de qualidade.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white">
                            <i class="ti ti-brand-facebook text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white">
                            <i class="ti ti-brand-instagram text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white">
                            <i class="ti ti-brand-twitter text-xl"></i>
                        </a>
                    </div>
                </div>

                <!-- Categories -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Categorias</h4>
                    <ul class="space-y-2">
                        @foreach($categories->take(5) as $category)
                            <li>
                                <a href="{{ route('ecommerce.products', ['category' => $category->id]) }}" 
                                   class="text-gray-300 hover:text-white">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Customer Service -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Atendimento</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white">Central de Ajuda</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Como Comprar</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Política de Troca</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Fale Conosco</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contato</h4>
                    <ul class="space-y-2 text-gray-300">
                        <li class="flex items-center">
                            <i class="ti ti-phone mr-2"></i>
                            (11) 99999-9999
                        </li>
                        <li class="flex items-center">
                            <i class="ti ti-mail mr-2"></i>
                            contato@lumiserp.com
                        </li>
                        <li class="flex items-center">
                            <i class="ti ti-map-pin mr-2"></i>
                            São Paulo, SP
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 flex items-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mr-3"></div>
            <span>Carregando...</span>
        </div>
    </div>

    @stack('scripts')

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.remove('hidden');
        });

        document.getElementById('close-mobile-menu').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.add('hidden');
        });

        // Mobile search toggle
        document.getElementById('mobile-search-btn').addEventListener('click', function() {
            const mobileSearch = document.getElementById('mobile-search');
            mobileSearch.classList.toggle('hidden');
            if (!mobileSearch.classList.contains('hidden')) {
                document.getElementById('mobile-search-input').focus();
            }
        });

        // Search functionality
        function setupSearch(inputId, resultsId) {
            const input = document.getElementById(inputId);
            const results = document.getElementById(resultsId);
            let searchTimeout;

            if (!input) return;

            input.addEventListener('input', function() {
                const query = this.value.trim();
                
                clearTimeout(searchTimeout);
                
                if (query.length < 2) {
                    if (results) results.classList.add('hidden');
                    return;
                }

                searchTimeout = setTimeout(() => {
                    fetch(`{{ route('ecommerce.search') }}?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            if (results) {
                                if (data.length > 0) {
                                    results.innerHTML = data.map(product => `
                                        <a href="${product.url}" class="block p-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0">
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 bg-gray-200 rounded-lg mr-3 flex items-center justify-center">
                                                    <i class="ti ti-package text-gray-400"></i>
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="font-medium text-gray-900">${product.name}</h4>
                                                    <p class="text-sm text-green-600">${product.price}</p>
                                                </div>
                                            </div>
                                        </a>
                                    `).join('');
                                    results.classList.remove('hidden');
                                } else {
                                    results.innerHTML = '<div class="p-3 text-center text-gray-500">Nenhum produto encontrado</div>';
                                    results.classList.remove('hidden');
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Erro na busca:', error);
                        });
                }, 300);
            });

            // Hide results when clicking outside
            document.addEventListener('click', function(e) {
                if (results && !input.contains(e.target) && !results.contains(e.target)) {
                    results.classList.add('hidden');
                }
            });
        }

        // Setup search for desktop and mobile
        setupSearch('search-input', 'search-results');
        setupSearch('mobile-search-input', null);

        // Update cart count on page load
        function updateCartCount() {
            fetch('{{ route('ecommerce.cart.count') }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('cart-count').textContent = data.count || 0;
                })
                .catch(error => console.error('Erro ao atualizar carrinho:', error));
        }

        // Update cart count on page load
        document.addEventListener('DOMContentLoaded', updateCartCount);

        // Loading overlay functions
        window.showLoading = function() {
            document.getElementById('loading-overlay').classList.remove('hidden');
        };

        window.hideLoading = function() {
            document.getElementById('loading-overlay').classList.add('hidden');
        };
    </script>
</body>
</html> 