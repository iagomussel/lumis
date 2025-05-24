@extends('layouts.public')

@section('title', 'Página Inicial')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-4xl lg:text-6xl font-bold mb-6">
                    Sistema ERP
                    <span class="text-blue-200">Completo</span>
                </h1>
                <p class="text-xl mb-8 text-blue-100">
                    Gerencie seu negócio de forma eficiente com nossa plataforma completa de gestão empresarial. 
                    Controle vendas, estoque, clientes e muito mais.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    @guest('customer')
                        <a href="{{ route('customer.register') }}" 
                           class="bg-white text-blue-600 px-8 py-4 rounded-full font-semibold hover:bg-blue-50 transition-colors inline-flex items-center justify-center">
                            <i class="ti ti-user-plus mr-2"></i>
                            Cadastre-se Grátis
                        </a>
                        <a href="{{ route('customer.login') }}" 
                           class="border-2 border-white text-white px-8 py-4 rounded-full font-semibold hover:bg-white hover:text-blue-600 transition-colors inline-flex items-center justify-center">
                            <i class="ti ti-login mr-2"></i>
                            Fazer Login
                        </a>
                    @else
                        <a href="{{ route('customer.dashboard') }}" 
                           class="bg-white text-blue-600 px-8 py-4 rounded-full font-semibold hover:bg-blue-50 transition-colors inline-flex items-center justify-center">
                            <i class="ti ti-dashboard mr-2"></i>
                            Acessar Dashboard
                        </a>
                    @endguest
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-gray-500 text-lg font-semibold mb-4">Funcionalidades Principais</h3>
                        <div class="space-y-3">
                            <div class="flex items-center text-gray-500">
                                <i class="ti ti-check text-green-500 mr-3"></i>
                                Gestão de Clientes
                            </div>
                            <div class="flex items-center text-gray-500">
                                <i class="ti ti-check text-green-500 mr-3"></i>
                                Controle de Estoque
                            </div>
                            <div class="flex items-center text-gray-500">
                                <i class="ti ti-check text-green-500 mr-3"></i>
                                Pedidos e Vendas
                            </div>
                            <div class="flex items-center text-gray-500">
                                <i class="ti ti-check text-green-500 mr-3"></i>
                                Relatórios Completos
                            </div>
                            <div class="flex items-center text-gray-500">
                                <i class="ti ti-check text-green-500 mr-3"></i>
                                Interface Moderna
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
@if($categories->count() > 0)
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-500 mb-4">Categorias de Produtos</h2>
            <p class="text-lg text-gray-400 max-w-2xl mx-auto">
                Explore nossa ampla variedade de categorias e encontre exatamente o que você precisa para seu negócio.
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categories as $category)
                <div class="card hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="card-body text-center">
                        <div class="w-16 h-16 rounded-full bg-blue-500 flex items-center justify-center mx-auto mb-4">
                            <i class="ti ti-category text-white text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-500 mb-2">{{ $category->name }}</h3>
                        <p class="text-gray-400 text-sm mb-4">{{ $category->description ?? 'Categoria de produtos' }}</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $category->products_count }} {{ $category->products_count === 1 ? 'produto' : 'produtos' }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Featured Products Section -->
@if($featuredProducts->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-500 mb-4">Produtos em Destaque</h2>
            <p class="text-lg text-gray-400 max-w-2xl mx-auto">
                Confira alguns dos nossos produtos mais populares e bem avaliados pelos nossos clientes.
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
                <div class="card hover:shadow-lg transition-shadow">
                    <div class="card-body">
                        <div class="aspect-w-1 aspect-h-1 mb-4">
                            <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="ti ti-package text-gray-400 text-4xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-500 mb-2">{{ $product->name }}</h3>
                        <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ $product->description ?? 'Produto de qualidade' }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-green-600">
                                R$ {{ number_format($product->price, 2, ',', '.') }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Em estoque
                            </span>
                        </div>
                        @if($product->category)
                            <div class="mt-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $product->category->name }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Features Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-500 mb-4">Por que escolher nosso ERP?</h2>
            <p class="text-lg text-gray-400 max-w-2xl mx-auto">
                Nossa plataforma oferece todas as ferramentas necessárias para impulsionar seu negócio.
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-blue-500 flex items-center justify-center mx-auto mb-4">
                    <i class="ti ti-users text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-500 mb-3">Gestão de Clientes</h3>
                <p class="text-gray-400">
                    Cadastre e gerencie seus clientes com informações completas, histórico de compras e muito mais.
                </p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-green-500 flex items-center justify-center mx-auto mb-4">
                    <i class="ti ti-package text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-500 mb-3">Controle de Estoque</h3>
                <p class="text-gray-400">
                    Monitore seu estoque em tempo real, receba alertas de produtos em baixa e organize por categorias.
                </p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-purple-500 flex items-center justify-center mx-auto mb-4">
                    <i class="ti ti-shopping-cart text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-500 mb-3">Vendas e Pedidos</h3>
                <p class="text-gray-400">
                    Processe pedidos rapidamente, acompanhe vendas e gere relatórios detalhados de performance.
                </p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-yellow-500 flex items-center justify-center mx-auto mb-4">
                    <i class="ti ti-truck text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-500 mb-3">Fornecedores</h3>
                <p class="text-gray-400">
                    Gerencie seus fornecedores, controle compras e mantenha um relacionamento profissional.
                </p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-red-500 flex items-center justify-center mx-auto mb-4">
                    <i class="ti ti-chart-line text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-500 mb-3">Relatórios</h3>
                <p class="text-gray-400">
                    Acesse relatórios detalhados sobre vendas, estoque, clientes e performance do seu negócio.
                </p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-indigo-500 flex items-center justify-center mx-auto mb-4">
                    <i class="ti ti-devices text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-500 mb-3">Interface Moderna</h3>
                <p class="text-gray-400">
                    Interface intuitiva e responsiva que funciona perfeitamente em qualquer dispositivo.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
@guest('customer')
<section class="bg-gray-900 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-4">Pronto para começar?</h2>
        <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
            Junte-se a milhares de empresas que já usam nosso ERP para gerenciar seus negócios com eficiência.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('customer.register') }}" 
               class="bg-blue-600 text-white px-8 py-4 rounded-full font-semibold hover:bg-blue-700 transition-colors inline-flex items-center justify-center">
                <i class="ti ti-user-plus mr-2"></i>
                Criar Conta Grátis
            </a>
            <a href="{{ route('customer.login') }}" 
               class="border-2 border-white text-white px-8 py-4 rounded-full font-semibold hover:bg-white hover:text-gray-900 transition-colors inline-flex items-center justify-center">
                <i class="ti ti-login mr-2"></i>
                Já tenho conta
            </a>
        </div>
    </div>
</section>
@endguest
@endsection 