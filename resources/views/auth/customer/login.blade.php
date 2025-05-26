@extends('layouts.ecommerce')

@section('title', 'Login de Cliente')

@section('content')
<div class="min-h-screen flex items-center justify-center lumis-hero-gradient py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <div class="w-16 h-16 rounded-full brand-bg-accent flex items-center justify-center mx-auto mb-4">
                <i class="ti ti-user text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-white">
                Acesse sua conta
            </h2>
            <p class="mt-2 text-sm text-blue-100">
                Entre com suas credenciais para acessar {{ config('branding.store.name') }}
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-xl p-8">
            <div>
                <form method="POST" action="{{ route('customer.login') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            E-mail
                        </label>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               autocomplete="email" 
                               required 
                               value="{{ old('email') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 @enderror"
                               placeholder="Digite seu e-mail"
                               style="focus:border-color: var(--store-primary); focus:ring-color: var(--store-primary);"
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-500 mb-2">
                            Senha
                        </label>
                        <input id="password" 
                               name="password" 
                               type="password" 
                               autocomplete="current-password" 
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-500 @enderror"
                               placeholder="Digite sua senha">
                        @error('password')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" 
                                   name="remember" 
                                   type="checkbox" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="remember" class="ml-2 block text-sm text-gray-500">
                                Lembrar de mim
                            </label>
                        </div>

                        <div class="text-sm">
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                                Esqueceu a senha?
                            </a>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                                class="w-full brand-bg-primary text-white font-medium py-3 px-6 rounded-lg hover:opacity-90 transition-all duration-200 inline-flex items-center justify-center">
                            <i class="ti ti-login mr-2"></i>
                            Entrar
                        </button>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            Não tem uma conta?
                            <a href="{{ route('customer.register') }}" class="font-medium brand-primary hover:opacity-80 transition-colors">
                                Cadastre-se aqui
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 