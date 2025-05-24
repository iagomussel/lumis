@extends('layouts.public')

@section('title', 'Cadastro de Cliente')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full space-y-8">
        <div class="text-center">
            <div class="w-16 h-16 rounded-full bg-blue-600 flex items-center justify-center mx-auto mb-4">
                <i class="ti ti-user-plus text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-500">
                Criar nova conta
            </h2>
            <p class="mt-2 text-sm text-gray-400">
                Preencha os dados abaixo para criar sua conta
            </p>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('customer.register') }}" class="space-y-6">
                    @csrf

                    <!-- Customer Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-3">
                            Tipo de Cliente
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" 
                                       name="type" 
                                       value="individual" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                       {{ old('type', 'individual') === 'individual' ? 'checked' : '' }}
                                       onchange="toggleCustomerType()">
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-500">Pessoa Física</div>
                                    <div class="text-xs text-gray-400">CPF</div>
                                </div>
                            </label>
                            <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" 
                                       name="type" 
                                       value="company" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                       {{ old('type') === 'company' ? 'checked' : '' }}
                                       onchange="toggleCustomerType()">
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-500">Pessoa Jurídica</div>
                                    <div class="text-xs text-gray-400">CNPJ</div>
                                </div>
                            </label>
                        </div>
                        @error('type')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-500 mb-2">
                                Nome Completo
                            </label>
                            <input id="name" 
                                   name="name" 
                                   type="text" 
                                   required 
                                   value="{{ old('name') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('name') border-red-500 @enderror"
                                   placeholder="Digite seu nome completo">
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-500 mb-2">
                                E-mail
                            </label>
                            <input id="email" 
                                   name="email" 
                                   type="email" 
                                   required 
                                   value="{{ old('email') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 @enderror"
                                   placeholder="Digite seu e-mail">
                            @error('email')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-500 mb-2">
                                Senha
                            </label>
                            <input id="password" 
                                   name="password" 
                                   type="password" 
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-500 @enderror"
                                   placeholder="Digite sua senha">
                            @error('password')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-500 mb-2">
                                Confirmar Senha
                            </label>
                            <input id="password_confirmation" 
                                   name="password_confirmation" 
                                   type="password" 
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Confirme sua senha">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Document -->
                        <div>
                            <label for="document" class="block text-sm font-medium text-gray-500 mb-2">
                                <span id="document-label">CPF</span>
                            </label>
                            <input id="document" 
                                   name="document" 
                                   type="text" 
                                   value="{{ old('document') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('document') border-red-500 @enderror"
                                   placeholder="Digite o CPF">
                            <input type="hidden" name="document_type" id="document_type" value="cpf">
                            @error('document')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-500 mb-2">
                                Telefone
                            </label>
                            <input id="phone" 
                                   name="phone" 
                                   type="text" 
                                   value="{{ old('phone') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('phone') border-red-500 @enderror"
                                   placeholder="Digite seu telefone">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Individual Fields -->
                    <div id="individual-fields" class="space-y-6">
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-500 mb-2">
                                Data de Nascimento
                            </label>
                            <input id="birth_date" 
                                   name="birth_date" 
                                   type="date" 
                                   value="{{ old('birth_date') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('birth_date') border-red-500 @enderror">
                            @error('birth_date')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Company Fields -->
                    <div id="company-fields" class="space-y-6 hidden">
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-gray-500 mb-2">
                                Razão Social
                            </label>
                            <input id="company_name" 
                                   name="company_name" 
                                   type="text" 
                                   value="{{ old('company_name') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('company_name') border-red-500 @enderror"
                                   placeholder="Digite a razão social">
                            @error('company_name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                                class="w-full btn text-white font-medium hover:bg-blue-700 inline-flex items-center justify-center">
                            <i class="ti ti-user-plus mr-2"></i>
                            Criar Conta
                        </button>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="text-sm text-gray-400">
                            Já tem uma conta?
                            <a href="{{ route('customer.login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                                Faça login aqui
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleCustomerType() {
    const type = document.querySelector('input[name="type"]:checked').value;
    const individualFields = document.getElementById('individual-fields');
    const companyFields = document.getElementById('company-fields');
    const documentLabel = document.getElementById('document-label');
    const documentInput = document.getElementById('document');
    const documentType = document.getElementById('document_type');
    
    if (type === 'individual') {
        individualFields.classList.remove('hidden');
        companyFields.classList.add('hidden');
        documentLabel.textContent = 'CPF';
        documentInput.placeholder = 'Digite o CPF';
        documentType.value = 'cpf';
    } else {
        individualFields.classList.add('hidden');
        companyFields.classList.remove('hidden');
        documentLabel.textContent = 'CNPJ';
        documentInput.placeholder = 'Digite o CNPJ';
        documentType.value = 'cnpj';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleCustomerType();
});
</script>
@endsection 