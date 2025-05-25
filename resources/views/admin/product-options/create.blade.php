@extends('layouts.admin')

@section('title', 'Nova Opção de Produto')

@section('content')
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Nova Opção de Produto</h2>
                <p class="text-gray-600">Crie uma nova opção para ser usada pelos produtos (cor, tamanho, etc.)</p>
            </div>
            <a href="{{ route('admin.product-options.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center">
                <i class="ti ti-arrow-left mr-2"></i>
                Voltar
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <form action="{{ route('admin.product-options.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nome -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome da Opção <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name"
                           value="{{ old('name') }}"
                           placeholder="Ex: Cor, Tamanho, Material..."
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-300 @enderror"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipo -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo <span class="text-red-500">*</span>
                    </label>
                    <select name="type" 
                            id="type" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('type') border-red-300 @enderror"
                            required>
                        <option value="">Selecione o tipo</option>
                        <option value="select" {{ old('type') === 'select' ? 'selected' : '' }}>Seleção (dropdown)</option>
                        <option value="color" {{ old('type') === 'color' ? 'selected' : '' }}>Cor (seletor de cor)</option>
                        <option value="text" {{ old('type') === 'text' ? 'selected' : '' }}>Texto livre</option>
                        <option value="number" {{ old('type') === 'number' ? 'selected' : '' }}>Número</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Descrição -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Descrição
                </label>
                <textarea name="description" 
                          id="description" 
                          rows="3"
                          placeholder="Descrição opcional da opção..."
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-300 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Valores -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Valores Disponíveis <span class="text-red-500">*</span>
                </label>
                <div id="values-container" class="space-y-2">
                    @if(old('values'))
                        @foreach(old('values') as $index => $value)
                            <div class="value-input flex items-center gap-2">
                                <input type="text" 
                                       name="values[]" 
                                       value="{{ $value }}"
                                       placeholder="Ex: Azul, M, Algodão..."
                                       class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <button type="button" 
                                        onclick="removeValue(this)"
                                        class="text-red-600 hover:text-red-800 p-1">
                                    <i class="ti ti-trash text-lg"></i>
                                </button>
                            </div>
                        @endforeach
                    @else
                        <div class="value-input flex items-center gap-2">
                            <input type="text" 
                                   name="values[]" 
                                   placeholder="Ex: Azul, M, Algodão..."
                                   class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <button type="button" 
                                    onclick="removeValue(this)"
                                    class="text-red-600 hover:text-red-800 p-1">
                                <i class="ti ti-trash text-lg"></i>
                            </button>
                        </div>
                    @endif
                </div>
                
                <button type="button" 
                        onclick="addValue()" 
                        class="mt-2 bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm inline-flex items-center">
                    <i class="ti ti-plus mr-1"></i>
                    Adicionar Valor
                </button>
                
                @error('values')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('values.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Ordem -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                        Ordem de Exibição
                    </label>
                    <input type="number" 
                           name="sort_order" 
                           id="sort_order"
                           value="{{ old('sort_order', 0) }}"
                           min="0"
                           placeholder="0"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('sort_order') border-red-300 @enderror">
                    <p class="mt-1 text-xs text-gray-500">0 = primeira posição</p>
                    @error('sort_order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Obrigatório -->
                <div class="flex items-center">
                    <div class="flex items-center h-5">
                        <input type="hidden" name="required" value="0">
                        <input type="checkbox" 
                               name="required" 
                               id="required"
                               value="1"
                               {{ old('required') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    </div>
                    <div class="ml-3">
                        <label for="required" class="text-sm font-medium text-gray-700">
                            Campo obrigatório
                        </label>
                        <p class="text-xs text-gray-500">Clientes devem selecionar esta opção</p>
                    </div>
                </div>

                <!-- Status -->
                <div class="flex items-center">
                    <div class="flex items-center h-5">
                        <input type="hidden" name="active" value="0">
                        <input type="checkbox" 
                               name="active" 
                               id="active"
                               value="1"
                               {{ old('active', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    </div>
                    <div class="ml-3">
                        <label for="active" class="text-sm font-medium text-gray-700">
                            Opção ativa
                        </label>
                        <p class="text-xs text-gray-500">Disponível para uso</p>
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.product-options.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                    Criar Opção
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
function addValue() {
    const container = document.getElementById('values-container');
    const div = document.createElement('div');
    div.className = 'value-input flex items-center gap-2';
    div.innerHTML = `
        <input type="text" 
               name="values[]" 
               placeholder="Ex: Azul, M, Algodão..."
               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        <button type="button" 
                onclick="removeValue(this)"
                class="text-red-600 hover:text-red-800 p-1">
            <i class="ti ti-trash text-lg"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeValue(button) {
    const container = document.getElementById('values-container');
    if (container.children.length > 1) {
        button.closest('.value-input').remove();
    } else {
        alert('Deve haver pelo menos um valor!');
    }
}
</script>
@endpush 