<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Admin')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface font-['Plus_Jakarta_Sans']">
    <main>
        <div class="app-topstrip z-40 sticky top-0 py-[15px] px-6 bg-[linear-gradient(90deg,_#0f0533_0%,_#1b0a5c_100%)]">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-white text-lg font-semibold">{{ config('app.name') }}</h2>
                    <p class="text-gray-300 text-xs">Sistema ERP</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center text-white">
                        <span class="text-sm">{{ Auth::user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-white hover:text-gray-200 transition-colors">
                            <i class="ti ti-logout text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Button -->
        <button id="mobile-menu-toggle" class="xl:hidden fixed top-20 left-4 z-50 bg-white p-3 rounded-full shadow-lg border border-gray-200">
            <i class="ti ti-menu-2 text-xl text-gray-700"></i>
        </button>

        <!-- Mobile Overlay -->
        <div id="mobile-overlay" class="xl:hidden fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

        <div id="main-wrapper" class="flex xl:p-5 xl:pr-0">
            <aside id="application-sidebar-brand"
                class="hs-overlay hs-overlay-open:translate-x-0 -translate-x-full transform hidden xl:block xl:translate-x-0 xl:end-auto xl:bottom-0 fixed xl:top-[90px] xl:left-auto top-0 left-0 with-vertical h-screen z-45 shrink-0 w-[270px] shadow-md xl:rounded-md rounded-none bg-white left-sidebar transition-all duration-300 flex flex-col">
                
                <!-- Sidebar Header -->
                <div class="p-4 border-b border-gray-100 flex-shrink-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center mr-3">
                                <i class="ti ti-building-store text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-gray-500 font-semibold text-sm">{{ config('app.name') }}</h3>
                                <p class="text-gray-400 text-xs">ERP System</p>
                            </div>
                        </div>
                        <!-- Close button for mobile -->
                        <button id="mobile-menu-close" class="xl:hidden p-2 rounded-md hover:bg-gray-100 transition-colors">
                            <i class="ti ti-x text-lg text-gray-500"></i>
                        </button>
                    </div>
                </div>

                <!-- Scrollable Navigation -->
                <div class="flex-1 overflow-y-auto scrollbar-thin">
                    <nav class="w-full flex flex-col sidebar-nav p-4">
                        <ul id="sidebarnav" class="text-gray-600 text-sm">
                            <li class="text-xs font-bold pb-[5px]">
                                <span class="text-xs text-gray-400 font-semibold">DASHBOARD</span>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link gap-3 py-2.5 my-1 text-base flex items-center relative rounded-md text-gray-500 w-full {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                                   href="{{ route('admin.dashboard') }}">
                                    <i class="ti ti-layout-dashboard ps-2 text-2xl"></i> 
                                    <span>Dashboard</span>
                                </a>
                            </li>

                            <li class="text-xs font-bold mb-4 mt-6">
                                <span class="text-xs text-gray-400 font-semibold">PRODUTOS</span>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link gap-3 py-2.5 my-1 text-base flex items-center relative rounded-md text-gray-500 w-full {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                                   href="{{ route('admin.categories.index') }}">
                                    <i class="ti ti-category ps-2 text-2xl"></i> 
                                    <span>Categorias</span>
                                </a>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link gap-3 py-2.5 my-1 text-base flex items-center relative rounded-md text-gray-500 w-full {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
                                   href="{{ route('admin.products.index') }}">
                                    <i class="ti ti-package ps-2 text-2xl"></i> 
                                    <span>Produtos</span>
                                </a>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link gap-3 py-2.5 my-1 text-base flex items-center relative rounded-md text-gray-500 w-full {{ request()->routeIs('admin.product-variants.*') ? 'active' : '' }}"
                                   href="{{ route('admin.product-variants.index') }}">
                                    <i class="ti ti-versions ps-2 text-2xl"></i> 
                                    <span>Variações</span>
                                </a>
                            </li>





                            <li class="sidebar-item">
                                <a class="sidebar-link gap-3 py-2.5 my-1 text-base flex items-center relative rounded-md text-gray-500 w-full {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}"
                                   href="{{ route('admin.inventory.index') }}">
                                    <i class="ti ti-archive ps-2 text-2xl"></i> 
                                    <span>Estoque</span>
                                </a>
                            </li>

                            <li class="text-xs font-bold mb-4 mt-6">
                                <span class="text-xs text-gray-400 font-semibold">PDV</span>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link gap-3 py-2.5 my-1 text-base flex items-center relative rounded-md text-gray-500 w-full {{ request()->routeIs('admin.pos.*') ? 'active' : '' }}"
                                   href="{{ route('admin.pos.index') }}">
                                    <i class="ti ti-cash-register ps-2 text-2xl"></i> 
                                    <span>Ponto de Venda</span>
                                </a>
                            </li>

                            <li class="text-xs font-bold mb-4 mt-6">
                                <span class="text-xs text-gray-400 font-semibold">VENDAS</span>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link gap-3 py-2.5 my-1 text-base flex items-center relative rounded-md text-gray-500 w-full {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}"
                                   href="{{ route('admin.customers.index') }}">
                                    <i class="ti ti-users ps-2 text-2xl"></i> 
                                    <span>Clientes</span>
                                </a>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link gap-3 py-2.5 my-1 text-base flex items-center relative rounded-md text-gray-500 w-full {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
                                   href="{{ route('admin.orders.index') }}">
                                    <i class="ti ti-shopping-cart ps-2 text-2xl"></i> 
                                    <span>Pedidos</span>
                                </a>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link gap-3 py-2.5 my-1 text-base flex items-center relative rounded-md text-gray-500 w-full {{ request()->routeIs('admin.leads.*') ? 'active' : '' }}"
                                   href="{{ route('admin.leads.index') }}">
                                    <i class="ti ti-target ps-2 text-2xl"></i> 
                                    <span>Leads</span>
                                </a>
                            </li>

                            <li class="text-xs font-bold mb-4 mt-6">
                                <span class="text-xs text-gray-400 font-semibold">ENTREGAS</span>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link gap-3 py-2.5 my-1 text-base flex items-center relative rounded-md text-gray-500 w-full {{ request()->routeIs('admin.deliveries.*') ? 'active' : '' }}"
                                   href="{{ route('admin.deliveries.index') }}">
                                    <i class="ti ti-truck-delivery ps-2 text-2xl"></i> 
                                    <span>Agendamentos</span>
                                </a>
                            </li>

                            <li class="text-xs font-bold mb-4 mt-6">
                                <span class="text-xs text-gray-400 font-semibold">COMPRAS</span>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link gap-3 py-2.5 my-1 text-base flex items-center relative rounded-md text-gray-500 w-full {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}"
                                   href="{{ route('admin.suppliers.index') }}">
                                    <i class="ti ti-truck ps-2 text-2xl"></i> 
                                    <span>Fornecedores</span>
                                </a>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link gap-3 py-2.5 my-1 text-base flex items-center relative rounded-md text-gray-500 w-full {{ request()->routeIs('admin.purchases.*') ? 'active' : '' }}"
                                   href="{{ route('admin.purchases.index') }}">
                                    <i class="ti ti-shopping-bag ps-2 text-2xl"></i> 
                                    <span>Compras</span>
                                </a>
                            </li>

                            <li class="text-xs font-bold mb-4 mt-6">
                                <span class="text-xs text-gray-400 font-semibold">FINANCEIRO</span>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link gap-3 py-2.5 my-1 text-base flex items-center relative rounded-md text-gray-500 w-full {{ request()->routeIs('admin.financial.receivables') ? 'active' : '' }}"
                                   href="{{ route('admin.financial.receivables') }}">
                                    <i class="ti ti-cash ps-2 text-2xl"></i> 
                                    <span>Contas a Receber</span>
                                </a>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link gap-3 py-2.5 my-1 text-base flex items-center relative rounded-md text-gray-500 w-full {{ request()->routeIs('admin.financial.payables') ? 'active' : '' }}"
                                   href="{{ route('admin.financial.payables') }}">
                                    <i class="ti ti-credit-card ps-2 text-2xl"></i> 
                                    <span>Contas a Pagar</span>
                                </a>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link gap-3 py-2.5 my-1 text-base flex items-center relative rounded-md text-gray-500 w-full {{ request()->routeIs('admin.financial.dashboard') ? 'active' : '' }}"
                                   href="{{ route('admin.financial.dashboard') }}">
                                    <i class="ti ti-chart-line ps-2 text-2xl"></i> 
                                    <span>Dashboard Financeiro</span>
                                </a>
                            </li>

                            <li class="text-xs font-bold mb-4 mt-6">
                                <span class="text-xs text-gray-400 font-semibold">MARKETING</span>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link gap-3 py-2.5 my-1 text-base flex items-center relative rounded-md text-gray-500 w-full {{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}"
                                   href="{{ route('admin.promotions.index') }}">
                                    <i class="ti ti-discount ps-2 text-2xl"></i> 
                                    <span>Promoções</span>
                                </a>
                            </li>

                            <li class="text-xs font-bold mb-4 mt-6">
                                <span class="text-xs text-gray-400 font-semibold">SISTEMA</span>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link gap-3 py-2.5 my-1 text-base flex items-center relative rounded-md text-gray-500 w-full {{ request()->routeIs('admin.search') ? 'active' : '' }}"
                                   href="{{ route('admin.search') }}">
                                    <i class="ti ti-search ps-2 text-2xl"></i> 
                                    <span>Busca Global</span>
                                </a>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link gap-3 py-2.5 my-1 text-base flex items-center relative rounded-md text-gray-500 w-full {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}"
                                   href="{{ route('admin.activity-logs.index') }}">
                                    <i class="ti ti-activity ps-2 text-2xl"></i> 
                                    <span>Logs de Auditoria</span>
                                </a>
                            </li>
                        </ul>
                        <!-- Bottom padding to ensure last items are accessible -->
                        <div class="h-8"></div>
                    </nav>
                </div>
            </aside>

            <div class="w-full page-wrapper xl:px-6 px-4">
                <main class="h-full max-w-full">
                    <div class="container full-container p-0 flex flex-col gap-6">
                        <header class="bg-white shadow-md rounded-md w-full text-sm py-4 px-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h1 class="text-xl font-semibold text-gray-500">@yield('title', 'Dashboard')</h1>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <!-- Global Search Box -->
                                    <div class="relative hidden md:block">
                                        <form action="{{ route('admin.search') }}" method="GET" class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="ti ti-search text-gray-400"></i>
                                            </div>
                                            <input 
                                                type="text" 
                                                name="q" 
                                                placeholder="Buscar produtos, clientes, pedidos..."
                                                value="{{ request('q') }}"
                                                class="w-80 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                                autocomplete="off"
                                            >
                                        </form>
                                    </div>
                                    
                                    <!-- Mobile Search Button -->
                                    <button id="mobile-search-toggle" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                                        <i class="ti ti-search text-gray-600"></i>
                                    </button>
                                    
                                    @yield('header-actions')
                                </div>
                            </div>
                            
                            <!-- Mobile Search Bar (Hidden by default) -->
                            <div id="mobile-search-bar" class="md:hidden mt-4 hidden">
                                <form action="{{ route('admin.search') }}" method="GET" class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ti ti-search text-gray-400"></i>
                                    </div>
                                    <input 
                                        type="text" 
                                        name="q" 
                                        placeholder="Buscar produtos, clientes, pedidos..."
                                        value="{{ request('q') }}"
                                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                        autocomplete="off"
                                    >
                                </form>
                            </div>
                        </header>

                        @if (session('success'))
                            <div class="card">
                                <div class="card-body">
                                    <div class="bg-teal-400 text-teal-500 px-4 py-3 rounded-md flex items-center">
                                        <i class="ti ti-check-circle mr-3 text-lg"></i>
                                        {{ session('success') }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="card">
                                <div class="card-body">
                                    <div class="bg-red-400 text-red-500 px-4 py-3 rounded-md flex items-center">
                                        <i class="ti ti-alert-circle mr-3 text-lg"></i>
                                        {{ session('error') }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
    </main>

    <style>
        .bg-surface {
            background-color: #f0f5f9;
        }
        
        .card {
            position: relative;
            border-radius: 18px;
            background-color: white;
            box-shadow: 0px 2px 6px rgba(37, 83, 185, 0.1);
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .btn {
            border-radius: 30px;
            background-color: #0085db;
            padding: 0.75rem 1.75rem;
            text-align: center;
            font-size: 0.875rem;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            background-color: #0071ba;
        }
        
        .btn-outline-primary {
            border-radius: 30px;
            border: 1px solid #0085db;
            padding: 0.625rem 1.75rem;
            text-align: center;
            font-size: 0.875rem;
            color: #0085db;
            background-color: transparent;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background-color: #0085db;
            color: white;
        }
        
        #sidebarnav .sidebar-item .sidebar-link::before {
            position: absolute;
            top: 0;
            bottom: 0;
            left: -16px;
            content: "";
            width: 0;
            height: 100%;
            z-index: -1;
            border-radius: 0 24px 24px 0;
            transition: all 0.4s ease-in-out;
            background-color: #e5f3fb;
        }
        
        #sidebarnav .sidebar-item .sidebar-link:hover:before,
        #sidebarnav .sidebar-item .sidebar-link.active:before {
            width: calc(100% + 16px);
        }
        
        #sidebarnav .sidebar-item .sidebar-link.active,
        #sidebarnav .sidebar-item .sidebar-link:hover {
            color: #006aaf;
        }
        
        /* Desktop layout */
        .page-wrapper {
            margin-left: 270px;
        }
        
        /* Sidebar scrollbar styling */
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }
        
        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 3px;
        }
        
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        
        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Mobile sidebar animations */
        .sidebar-open {
            transform: translateX(0) !important;
            display: block !important;
        }
        
        /* Z-index hierarchy */
        .app-topstrip {
            z-index: 30;
        }
        
        #mobile-menu-toggle {
            z-index: 50;
        }
        
        #mobile-overlay {
            z-index: 40;
        }
        
        #application-sidebar-brand {
            z-index: 45;
        }
        
        /* Mobile responsive design */
        @media (max-width: 1280px) {
            .page-wrapper {
                margin-left: 0;
            }
            
            #main-wrapper {
                padding: 1rem;
            }
            
            #application-sidebar-brand {
                top: 0 !important;
                height: 100vh !important;
                display: none;
            }
            
            .mobile-menu-open #application-sidebar-brand {
                display: flex !important;
                transform: translateX(0);
            }
            
            .mobile-menu-open #mobile-overlay {
                display: block !important;
            }
            
            /* Improve mobile header */
            .app-topstrip {
                padding: 1rem 1.5rem;
            }
        }
        
        /* Improve touch targets on mobile */
        @media (max-width: 768px) {
            .sidebar-link {
                padding: 0.875rem 0.75rem !important;
                margin: 0.125rem 0 !important;
                font-size: 0.9rem !important;
            }
            
            .sidebar-link i {
                font-size: 1.375rem !important;
                margin-right: 0.75rem !important;
            }
            
            .sidebar-item .text-xs {
                font-size: 0.75rem !important;
                margin: 1rem 0 0.5rem 0 !important;
            }
            
            #main-wrapper {
                padding: 0.5rem;
            }
            
            .page-wrapper {
                padding: 0 !important;
            }
        }
        
        /* Tablet specific adjustments */
        @media (min-width: 769px) and (max-width: 1279px) {
            #main-wrapper {
                padding: 1.5rem;
            }
        }
    </style>
    
    @stack('scripts')
    
    <!-- Mobile Menu JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const mobileMenuClose = document.getElementById('mobile-menu-close');
            const mobileOverlay = document.getElementById('mobile-overlay');
            const sidebar = document.getElementById('application-sidebar-brand');
            const body = document.body;
            
            function openMobileMenu() {
                console.log('Opening mobile menu');
                body.classList.add('mobile-menu-open');
                sidebar.classList.add('sidebar-open');
                mobileOverlay.classList.remove('hidden');
                // Prevent body scroll when menu is open
                body.style.overflow = 'hidden';
                // Force sidebar to show
                sidebar.style.display = 'flex';
                sidebar.style.transform = 'translateX(0)';
            }
            
            function closeMobileMenu() {
                console.log('Closing mobile menu');
                body.classList.remove('mobile-menu-open');
                sidebar.classList.remove('sidebar-open');
                mobileOverlay.classList.add('hidden');
                // Restore body scroll
                body.style.overflow = '';
                // Hide sidebar on mobile
                if (window.innerWidth < 1280) {
                    sidebar.style.display = 'none';
                    sidebar.style.transform = 'translateX(-100%)';
                }
            }
            
            function handleResize() {
                if (window.innerWidth >= 1280) {
                    // Desktop mode
                    closeMobileMenu();
                    sidebar.style.display = 'flex';
                    sidebar.style.transform = 'translateX(0)';
                } else {
                    // Mobile mode
                    if (!body.classList.contains('mobile-menu-open')) {
                        sidebar.style.display = 'none';
                        sidebar.style.transform = 'translateX(-100%)';
                    }
                }
            }
            
            // Initial setup
            handleResize();
            
            // Toggle menu on button click
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    openMobileMenu();
                });
            }
            
            // Close menu on close button click
            if (mobileMenuClose) {
                mobileMenuClose.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    closeMobileMenu();
                });
            }
            
            // Close menu on overlay click
            if (mobileOverlay) {
                mobileOverlay.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    closeMobileMenu();
                });
            }
            
            // Close menu on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && body.classList.contains('mobile-menu-open')) {
                    closeMobileMenu();
                }
            });
            
            // Close menu when clicking on a link (for mobile navigation)
            if (sidebar) {
                const sidebarLinks = sidebar.querySelectorAll('.sidebar-link');
                sidebarLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        // Only close on mobile
                        if (window.innerWidth < 1280) {
                            setTimeout(closeMobileMenu, 150); // Small delay for better UX
                        }
                    });
                });
            }
            
            // Handle window resize
            window.addEventListener('resize', handleResize);
            
            // Mobile search toggle
            const mobileSearchToggle = document.getElementById('mobile-search-toggle');
            const mobileSearchBar = document.getElementById('mobile-search-bar');
            
            if (mobileSearchToggle && mobileSearchBar) {
                mobileSearchToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    mobileSearchBar.classList.toggle('hidden');
                    
                    // Focus on search input when opened
                    if (!mobileSearchBar.classList.contains('hidden')) {
                        const searchInput = mobileSearchBar.querySelector('input[type="text"]');
                        if (searchInput) {
                            setTimeout(() => searchInput.focus(), 100);
                        }
                    }
                });
            }
        });
    </script>
</body>
</html> 