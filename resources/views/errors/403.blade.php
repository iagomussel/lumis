@extends('layouts.ecommerce')

@section('title', 'Acesso negado - 403')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <!-- Logo -->
        <div>
            <img class="mx-auto h-20 w-auto" src="{{ asset('images/branding/logo.png') }}" alt="{{ config('app.name') }}">
        </div>

        <!-- Error Content -->
        <div class="space-y-6">
            <!-- 403 Number -->
            <div class="text-9xl font-bold text-yellow-300">
                403
            </div>

            <!-- Error Message -->
            <div class="space-y-2">
                <h1 class="text-3xl font-bold text-gray-900">
                    Acesso negado
                </h1>
                <p class="text-lg text-gray-600">
                    Você não tem permissão para acessar esta página ou recurso.
                </p>
            </div>

            <!-- Why this happened -->
            <div class="bg-white rounded-lg shadow-sm border p-6 text-left">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">
                    Por que isso aconteceu?
                </h3>
                <ul class="space-y-2 text-gray-600">
                    <li class="flex items-center">
                        <i class="fas fa-lock text-red-500 mr-2"></i>
                        Esta página requer permissões especiais
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-user-times text-orange-500 mr-2"></i>
                        Você pode não estar logado
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-shield-alt text-blue-500 mr-2"></i>
                        Sua conta pode não ter as permissões necessárias
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-clock text-yellow-500 mr-2"></i>
                        Sua sessão pode ter expirado
                    </li>
                </ul>
            </div>

            <!-- What to do -->
            <div class="bg-white rounded-lg shadow-sm border p-6 text-left">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">
                    O que você pode fazer:
                </h3>
                <ul class="space-y-2 text-gray-600">
                    @guest
                        <li class="flex items-center">
                            <i class="fas fa-sign-in-alt text-green-500 mr-2"></i>
                            Fazer login em sua conta
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-user-plus text-blue-500 mr-2"></i>
                            Criar uma nova conta se não tiver uma
                        </li>
                    @else
                        <li class="flex items-center">
                            <i class="fas fa-redo text-green-500 mr-2"></i>
                            Tentar fazer login novamente
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-user-cog text-blue-500 mr-2"></i>
                            Verificar as permissões da sua conta
                        </li>
                    @endguest
                    <li class="flex items-center">
                        <i class="fas fa-home text-purple-500 mr-2"></i>
                        Voltar à página inicial
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-envelope text-orange-500 mr-2"></i>
                        Entrar em contato para solicitar acesso
                    </li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                @guest
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Fazer Login
                    </a>
                @else
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Sair e Tentar Novamente
                        </button>
                    </form>
                @endguest
                
                <a href="{{ route('ecommerce.home') }}" 
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-home mr-2"></i>
                    Página Inicial
                </a>
            </div>

            <!-- Quick Access -->
            @guest
                <div class="bg-blue-50 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-blue-900 mb-2">
                        Acesso rápido:
                    </h4>
                    <div class="flex flex-col sm:flex-row gap-2 justify-center">
                        <a href="{{ route('customer.login') }}" 
                           class="text-sm text-blue-700 hover:text-blue-900 hover:underline">
                            <i class="fas fa-user mr-1"></i>
                            Login de Cliente
                        </a>
                        <a href="{{ route('customer.register') }}" 
                           class="text-sm text-blue-700 hover:text-blue-900 hover:underline">
                            <i class="fas fa-user-plus mr-1"></i>
                            Criar Conta
                        </a>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" 
                               class="text-sm text-blue-700 hover:text-blue-900 hover:underline">
                                <i class="fas fa-key mr-1"></i>
                                Esqueci a Senha
                            </a>
                        @endif
                    </div>
                </div>
            @endguest

            <!-- Contact Information -->
            <div class="bg-yellow-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-yellow-900 mb-2">
                    <i class="fas fa-question-circle mr-1"></i>
                    Precisa de ajuda com permissões?
                </h4>
                <div class="text-sm text-yellow-700 space-y-1">
                    @if(config('app.contact_email'))
                        <div class="flex items-center justify-center">
                            <i class="fas fa-envelope mr-2"></i>
                            <a href="mailto:{{ config('app.contact_email') }}?subject=Solicitação de Acesso - {{ request()->url() }}" 
                               class="hover:underline">
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
                            <a href="https://wa.me/{{ config('app.contact_whatsapp') }}?text=Olá, preciso de ajuda com permissões de acesso" 
                               target="_blank" class="hover:underline">
                                WhatsApp
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- User Info (if logged in) -->
            @auth
                <div class="bg-gray-100 rounded-lg p-3">
                    <div class="text-xs text-gray-500">
                        <strong>Usuário logado:</strong> {{ auth()->user()->name ?? auth()->user()->email }}
                    </div>
                    <div class="text-xs text-gray-500">
                        <strong>Tipo de conta:</strong> {{ auth()->guard('customer')->check() ? 'Cliente' : 'Usuário' }}
                    </div>
                    <div class="text-xs text-gray-500">
                        <strong>Horário:</strong> {{ now()->format('d/m/Y H:i:s') }}
                    </div>
                </div>
            @endauth
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
        background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%);
    }
</style>
@endpush 