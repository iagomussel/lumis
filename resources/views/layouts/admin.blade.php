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

        <div id="main-wrapper" class="flex p-5 xl:pr-0">
            <aside id="application-sidebar-brand"
                class="hs-overlay hs-overlay-open:translate-x-0 -translate-x-full transform hidden xl:block xl:translate-x-0 xl:end-auto xl:bottom-0 fixed xl:top-[90px] xl:left-auto top-0 left-0 with-vertical h-screen z-[999] shrink-0 w-[270px] shadow-md xl:rounded-md rounded-none bg-white left-sidebar transition-all duration-300">
                
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center mr-3">
                            <i class="ti ti-building-store text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-gray-500 font-semibold text-sm">{{ config('app.name') }}</h3>
                            <p class="text-gray-400 text-xs">ERP System</p>
                        </div>
                    </div>
                </div>

                <div class="scroll-sidebar" data-simplebar="">
                    <nav class="w-full flex flex-col sidebar-nav px-4 mt-5">
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
                                <a class="sidebar-link gap-3 py-2.5 my-1 text-base flex items-center relative rounded-md text-gray-500 w-full {{ request()->routeIs('admin.product-options.*') ? 'active' : '' }}"
                                   href="{{ route('admin.product-options.index') }}">
                                    <i class="ti ti-settings ps-2 text-2xl"></i> 
                                    <span>Opções de Produtos</span>
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
                        </ul>
                    </nav>
                </div>
            </aside>

            <div class="w-full page-wrapper xl:px-6 px-0">
                <main class="h-full max-w-full">
                    <div class="container full-container p-0 flex flex-col gap-6">
                        <header class="bg-white shadow-md rounded-md w-full text-sm py-4 px-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h1 class="text-xl font-semibold text-gray-500">@yield('title', 'Dashboard')</h1>
                                </div>
                                <div class="flex items-center space-x-3">
                                    @yield('header-actions')
                                </div>
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
        
        .page-wrapper {
            margin-left: 270px;
        }
        
        @media (max-width: 1280px) {
            .page-wrapper {
                margin-left: 0;
            }
        }
    </style>
    
    @stack('scripts')
</body>
</html> 