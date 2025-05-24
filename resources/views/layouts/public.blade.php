<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} @hasSection('title') - @yield('title') @endif</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface font-['Plus_Jakarta_Sans']">
    <!-- Header -->
    <header class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center mr-3">
                            <i class="ti ti-building-store text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-gray-500 font-semibold text-lg">{{ config('app.name') }}</h3>
                        </div>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors">
                        Início
                    </a>
                    <a href="#" class="text-gray-500 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors">
                        Produtos
                    </a>
                    <a href="#" class="text-gray-500 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors">
                        Sobre
                    </a>
                    <a href="#" class="text-gray-500 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors">
                        Contato
                    </a>
                </nav>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    @auth('customer')
                        <div class="relative">
                            <button class="flex items-center text-sm text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-lg p-2">
                                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center mr-2">
                                    <i class="ti ti-user text-white"></i>
                                </div>
                                <span class="hidden md:block">{{ Auth::guard('customer')->user()->name }}</span>
                                <i class="ti ti-chevron-down ml-1"></i>
                            </button>
                            
                            <!-- Dropdown menu -->
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 hidden group-hover:block">
                                <div class="py-1" role="menu">
                                    <a href="{{ route('customer.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        <i class="ti ti-dashboard mr-2"></i>
                                        Dashboard
                                    </a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        <i class="ti ti-user mr-2"></i>
                                        Meu Perfil
                                    </a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        <i class="ti ti-shopping-cart mr-2"></i>
                                        Meus Pedidos
                                    </a>
                                    <div class="border-t border-gray-100"></div>
                                    <form method="POST" action="{{ route('customer.logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            <i class="ti ti-logout mr-2"></i>
                                            Sair
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('customer.login') }}" 
                           class="text-gray-500 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors">
                            Entrar
                        </a>
                        <a href="{{ route('customer.register') }}" 
                           class="btn text-white font-medium hover:bg-blue-700 inline-flex items-center">
                            Cadastrar-se
                        </a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-gray-500 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 p-2">
                        <i class="ti ti-menu-2 text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @if (session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                <div class="bg-teal-400 text-teal-500 px-4 py-3 rounded-md flex items-center">
                    <i class="ti ti-check-circle mr-3 text-lg"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                <div class="bg-red-400 text-red-500 px-4 py-3 rounded-md flex items-center">
                    <i class="ti ti-alert-circle mr-3 text-lg"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-1">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center mr-3">
                            <i class="ti ti-building-store text-white text-lg"></i>
                        </div>
                        <h3 class="text-gray-500 font-semibold text-lg">{{ config('app.name') }}</h3>
                    </div>
                    <p class="text-gray-400 text-sm">
                        Sistema ERP completo para gestão empresarial com funcionalidades modernas e interface intuitiva.
                    </p>
                </div>

                <div>
                    <h4 class="text-gray-500 font-semibold mb-4">Produtos</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-blue-600 text-sm transition-colors">Categorias</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-blue-600 text-sm transition-colors">Catálogo</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-blue-600 text-sm transition-colors">Promoções</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-gray-500 font-semibold mb-4">Suporte</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-blue-600 text-sm transition-colors">Central de Ajuda</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-blue-600 text-sm transition-colors">Contato</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-blue-600 text-sm transition-colors">FAQ</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-gray-500 font-semibold mb-4">Contato</h4>
                    <ul class="space-y-2">
                        <li class="flex items-center text-gray-400 text-sm">
                            <i class="ti ti-mail mr-2"></i>
                            contato@lumiserp.com
                        </li>
                        <li class="flex items-center text-gray-400 text-sm">
                            <i class="ti ti-phone mr-2"></i>
                            (11) 99999-9999
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-200 mt-8 pt-8">
                <p class="text-center text-gray-400 text-sm">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.
                </p>
            </div>
        </div>
    </footer>

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
    </style>

    <script>
        // Simple dropdown toggle for mobile menu and user menu
        document.addEventListener('DOMContentLoaded', function() {
            const userMenuButton = document.querySelector('button[class*="focus:ring-blue-500"]');
            const userDropdown = document.querySelector('.absolute.right-0.mt-2');
            
            if (userMenuButton && userDropdown) {
                userMenuButton.addEventListener('click', function() {
                    userDropdown.classList.toggle('hidden');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html> 