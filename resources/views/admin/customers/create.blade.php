@extends('layouts.admin')

@section('title', 'Novo Cliente')

@section('header-actions')
    <a href="{{ route('admin.customers.index') }}" 
       class="btn-outline-primary font-medium hover:bg-blue-600 hover:text-white inline-flex items-center">
        <i class="ti ti-arrow-left mr-2"></i>
        Voltar
    </a>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="card">
        <div class="card-body">
            <div class="flex justify-between items-center mb-6">
                <h4 class="text-gray-500 text-lg font-semibold">Cadastrar Novo Cliente</h4>
            </div>

            <form method="POST" action="{{ route('admin.customers.store') }}">
                @csrf

                <!-- Informações Básicas -->
                <div class="mb-8">
                    <h6 class="text-lg text-gray-500 font-semibold mb-4">Informações Básicas</h6>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm mb-2 text-gray-400">Nome *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                   class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="type" class="block text-sm mb-2 text-gray-400">Tipo *</label>
                            <select name="type" id="type" required onchange="toggleCompanyFields()"
                                    class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-500 focus:ring-blue-500 @error('type') border-red-500 @enderror">
                                <option value="">Selecione...</option>
                                <option value="individual" {{ old('type') === 'individual' ? 'selected' : '' }}>Pessoa Física</option>
                                <option value="company" {{ old('type') === 'company' ? 'selected' : '' }}>Pessoa Jurídica</option>
                            </select>
                            @error('type')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="company_name_field" style="display: none;">
                            <label for="company_name" class="block text-sm mb-2 text-gray-400">Razão Social</label>
                            <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}"
                                   class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('company_name') border-red-500 @enderror">
                            @error('company_name')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm mb-2 text-gray-400">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                   class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm mb-2 text-gray-400">Telefone</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                   class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="mobile" class="block text-sm mb-2 text-gray-400">Celular</label>
                            <input type="text" name="mobile" id="mobile" value="{{ old('mobile') }}"
                                   class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('mobile') border-red-500 @enderror">
                            @error('mobile')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Documentos -->
                <div class="mb-8">
                    <h6 class="text-lg text-gray-500 font-semibold mb-4">Documentos</h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="document_type" class="block text-sm mb-2 text-gray-400">Tipo de Documento</label>
                            <select name="document_type" id="document_type"
                                    class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-500 focus:ring-blue-500 @error('document_type') border-red-500 @enderror">
                                <option value="">Selecione...</option>
                                <option value="cpf" {{ old('document_type') === 'cpf' ? 'selected' : '' }}>CPF</option>
                                <option value="cnpj" {{ old('document_type') === 'cnpj' ? 'selected' : '' }}>CNPJ</option>
                            </select>
                            @error('document_type')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="document" class="block text-sm mb-2 text-gray-400">Número do Documento</label>
                            <input type="text" name="document" id="document" value="{{ old('document') }}"
                                   class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('document') border-red-500 @enderror">
                            @error('document')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="state_registration_field" style="display: none;">
                            <label for="state_registration" class="block text-sm mb-2 text-gray-400">Inscrição Estadual</label>
                            <input type="text" name="state_registration" id="state_registration" value="{{ old('state_registration') }}"
                                   class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('state_registration') border-red-500 @enderror">
                            @error('state_registration')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Endereço -->
                <div class="mb-8">
                    <h6 class="text-lg text-gray-500 font-semibold mb-4">Endereço</h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm mb-2 text-gray-400">Logradouro</label>
                            <input type="text" name="address" id="address" value="{{ old('address') }}"
                                   class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('address') border-red-500 @enderror">
                            @error('address')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="address_number" class="block text-sm mb-2 text-gray-400">Número</label>
                            <input type="text" name="address_number" id="address_number" value="{{ old('address_number') }}"
                                   class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('address_number') border-red-500 @enderror">
                            @error('address_number')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="complement" class="block text-sm mb-2 text-gray-400">Complemento</label>
                            <input type="text" name="complement" id="complement" value="{{ old('complement') }}"
                                   class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('complement') border-red-500 @enderror">
                            @error('complement')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="neighborhood" class="block text-sm mb-2 text-gray-400">Bairro</label>
                            <input type="text" name="neighborhood" id="neighborhood" value="{{ old('neighborhood') }}"
                                   class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('neighborhood') border-red-500 @enderror">
                            @error('neighborhood')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="city" class="block text-sm mb-2 text-gray-400">Cidade</label>
                            <input type="text" name="city" id="city" value="{{ old('city') }}"
                                   class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('city') border-red-500 @enderror">
                            @error('city')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="state" class="block text-sm mb-2 text-gray-400">Estado</label>
                            <input type="text" name="state" id="state" value="{{ old('state') }}" maxlength="2"
                                   class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('state') border-red-500 @enderror">
                            @error('state')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="zip_code" class="block text-sm mb-2 text-gray-400">CEP</label>
                            <input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code') }}"
                                   class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('zip_code') border-red-500 @enderror">
                            @error('zip_code')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Configurações -->
                <div class="mb-8">
                    <h6 class="text-lg text-gray-500 font-semibold mb-4">Configurações</h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="status" class="block text-sm mb-2 text-gray-400">Status *</label>
                            <select name="status" id="status" required
                                    class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-500 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                                <option value="">Selecione...</option>
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Ativo</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inativo</option>
                                <option value="blocked" {{ old('status') === 'blocked' ? 'selected' : '' }}>Bloqueado</option>
                            </select>
                            @error('status')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="credit_limit" class="block text-sm mb-2 text-gray-400">Limite de Crédito</label>
                            <input type="number" name="credit_limit" id="credit_limit" value="{{ old('credit_limit') }}" step="0.01" min="0"
                                   class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('credit_limit') border-red-500 @enderror">
                            @error('credit_limit')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="birth_date_field" style="display: none;">
                            <label for="birth_date" class="block text-sm mb-2 text-gray-400">Data de Nascimento</label>
                            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}"
                                   class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('birth_date') border-red-500 @enderror">
                            @error('birth_date')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Observações -->
                <div class="mb-8">
                    <h6 class="text-lg text-gray-500 font-semibold mb-4">Observações</h6>
                    <textarea name="notes" id="notes" rows="4"
                              class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botões -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.customers.index') }}" 
                       class="btn-outline-primary font-medium hover:bg-blue-600 hover:text-white inline-flex items-center">
                        <i class="ti ti-x mr-2"></i>
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="btn text-white font-medium hover:bg-blue-700 inline-flex items-center">
                        <i class="ti ti-check mr-2"></i>
                        Salvar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleCompanyFields() {
    const typeSelect = document.getElementById('type');
    const companyField = document.getElementById('company_name_field');
    const stateRegistrationField = document.getElementById('state_registration_field');
    const birthDateField = document.getElementById('birth_date_field');
    
    if (typeSelect.value === 'company') {
        companyField.style.display = 'block';
        stateRegistrationField.style.display = 'block';
        birthDateField.style.display = 'none';
    } else if (typeSelect.value === 'individual') {
        companyField.style.display = 'none';
        stateRegistrationField.style.display = 'none';
        birthDateField.style.display = 'block';
    } else {
        companyField.style.display = 'none';
        stateRegistrationField.style.display = 'none';
        birthDateField.style.display = 'none';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleCompanyFields();
});
</script>
@endsection 