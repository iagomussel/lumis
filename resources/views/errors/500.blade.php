@extends('layouts.ecommerce')

@section('title', 'Erro interno do servidor - 500')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg w-full space-y-8 text-center">
        <!-- Logo -->
        <div>
            <img class="mx-auto h-20 w-auto" src="{{ asset('images/branding/logo.png') }}" alt="{{ config('app.name') }}">
        </div>

        <!-- Error Content -->
        <div class="space-y-6">
            <!-- 500 Number -->
            <div class="text-9xl font-bold text-red-300">
                500
            </div>

            <!-- Error Message -->
            <div class="space-y-2">
                <h1 class="text-3xl font-bold text-gray-900">
                    Erro interno do servidor
                </h1>
                <p class="text-lg text-gray-600">
                    Ops! Algo deu errado em nossos servidores. Estamos trabalhando para resolver isso.
                </p>
            </div>

            <!-- Error Details (only in development) -->
            @if(config('app.debug') && isset($exception))
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-left">
                    <h3 class="text-lg font-semibold text-red-900 mb-2">
                        Detalhes do erro (modo desenvolvimento):
                    </h3>
                    <div class="text-sm text-red-700 space-y-2">
                        <div>
                            <strong>Erro:</strong> {{ $exception->getMessage() }}
                        </div>
                        <div>
                            <strong>Arquivo:</strong> {{ $exception->getFile() }}
                        </div>
                        <div>
                            <strong>Linha:</strong> {{ $exception->getLine() }}
                        </div>
                    </div>
                </div>
            @endif

            <!-- What happened -->
            <div class="bg-white rounded-lg shadow-sm border p-6 text-left">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">
                    O que aconteceu?
                </h3>
                <ul class="space-y-2 text-gray-600">
                    <li class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                        Ocorreu um erro interno em nossos servidores
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-tools text-blue-500 mr-2"></i>
                        Nossa equipe técnica foi notificada automaticamente
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-clock text-yellow-500 mr-2"></i>
                        Estamos trabalhando para resolver o problema
                    </li>
                </ul>
            </div>

            <!-- What to do -->
            <div class="bg-white rounded-lg shadow-sm border p-6 text-left">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">
                    O que você pode fazer:
                </h3>
                <ul class="space-y-2 text-gray-600">
                    <li class="flex items-center">
                        <i class="fas fa-redo text-green-500 mr-2"></i>
                        Tentar novamente em alguns minutos
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-refresh text-blue-500 mr-2"></i>
                        Recarregar a página
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-home text-purple-500 mr-2"></i>
                        Voltar à página inicial
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-envelope text-orange-500 mr-2"></i>
                        Entrar em contato se o problema persistir
                    </li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button onclick="location.reload()" 
                        class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-redo mr-2"></i>
                    Tentar Novamente
                </button>
                
                <a href="{{ route('ecommerce.home') }}" 
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-home mr-2"></i>
                    Página Inicial
                </a>
            </div>

            <!-- Contact Information -->
            <div class="bg-red-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-red-900 mb-2">
                    <i class="fas fa-headset mr-1"></i>
                    Precisa de suporte urgente?
                </h4>
                <div class="text-sm text-red-700 space-y-1">
                    @if(config('app.contact_email'))
                        <div class="flex items-center justify-center">
                            <i class="fas fa-envelope mr-2"></i>
                            <a href="mailto:{{ config('app.contact_email') }}?subject=Erro 500 - {{ request()->url() }}" 
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
                            <a href="https://wa.me/{{ config('app.contact_whatsapp') }}?text=Olá, estou enfrentando um erro 500 no site" 
                               target="_blank" class="hover:underline">
                                WhatsApp
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Error ID for support -->
            <div class="bg-gray-100 rounded-lg p-3">
                <div class="text-xs text-gray-500">
                    <strong>ID do erro:</strong> {{ uniqid('ERR500_') }}
                </div>
                <div class="text-xs text-gray-500">
                    <strong>Horário:</strong> {{ now()->format('d/m/Y H:i:s') }}
                </div>
                @if(request()->ip())
                    <div class="text-xs text-gray-500">
                        <strong>IP:</strong> {{ request()->ip() }}
                    </div>
                @endif
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
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-retry after 30 seconds
    setTimeout(function() {
        if (confirm('Gostaria de tentar recarregar a página automaticamente?')) {
            location.reload();
        }
    }, 30000);
</script>
@endpush 