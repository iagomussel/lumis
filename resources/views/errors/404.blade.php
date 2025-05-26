@extends('layouts.ecommerce')

@section('title', 'Página não encontrada - 404')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <!-- Logo -->
        <div>
            <img class="mx-auto h-20 w-auto" src="{{ asset('images/branding/logo.png') }}" alt="{{ config('app.name') }}">
        </div>

        <!-- Error Content -->
        <div class="space-y-6">
            <!-- 404 Number -->
            <div class="text-9xl font-bold text-gray-300">
                404
            </div>

            <!-- Error Message -->
            <div class="space-y-2">
                <h1 class="text-3xl font-bold text-gray-900">
                    Página não encontrada
                </h1>
                <p class="text-lg text-gray-600">
                    Desculpe, não conseguimos encontrar a página que você está procurando.
                </p>
            </div>

            <!-- Suggestions -->
            <div class="bg-white rounded-lg shadow-sm border p-6 text-left">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">
                    O que você pode fazer:
                </h3>
                <ul class="space-y-2 text-gray-600">
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Verificar se o endereço foi digitado corretamente
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Voltar à página anterior
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Ir para a página inicial
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Entrar em contato conosco se o problema persistir
                    </li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button onclick="history.back()" 
                        class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar
                </button>
                
                <a href="{{ route('ecommerce.home') }}" 
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-home mr-2"></i>
                    Página Inicial
                </a>
            </div>

            <!-- Contact Information -->
            <div class="bg-blue-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-blue-900 mb-2">
                    Precisa de ajuda?
                </h4>
                <div class="text-sm text-blue-700 space-y-1">
                    @if(config('app.contact_email'))
                        <div class="flex items-center justify-center">
                            <i class="fas fa-envelope mr-2"></i>
                            <a href="mailto:{{ config('app.contact_email') }}" class="hover:underline">
                                {{ config('app.contact_email') }}
                            </a>
                        </div>
                    @endif
                    
                    @if(config('app.contact_phone'))
                        <div class="flex items-center justify-center">
                            <i class="fas fa-phone mr-2"></i>
                            <a href="tel:{{ config('app.contact_phone') }}" class="hover:underline">
                                {{ config('app.contact_phone') }}
                            </a>
                        </div>
                    @endif
                    
                    @if(config('app.contact_whatsapp'))
                        <div class="flex items-center justify-center">
                            <i class="fab fa-whatsapp mr-2"></i>
                            <a href="https://wa.me/{{ config('app.contact_whatsapp') }}" 
                               target="_blank" class="hover:underline">
                                WhatsApp
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Search Box -->
            <div class="bg-white rounded-lg shadow-sm border p-4">
                <h4 class="text-sm font-semibold text-gray-900 mb-3">
                    Ou procure por produtos:
                </h4>
                <form action="{{ route('ecommerce.products.search') }}" method="GET" class="flex">
                    <input type="text" 
                           name="q" 
                           placeholder="Digite o que você procura..." 
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-sm text-gray-500">
            © {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
</style>
@endpush 