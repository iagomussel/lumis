@extends('layouts.admin')

@section('title', 'Nova Categoria')

@section('header-actions')
    <a href="{{ route('admin.categories.index') }}" 
       class="btn-outline-primary font-medium hover:bg-blue-600 hover:text-white inline-flex items-center">
        <i class="ti ti-arrow-left mr-2"></i>
        Voltar
    </a>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="card-body">
            <div class="flex justify-between items-center mb-6">
                <h4 class="text-gray-500 text-lg font-semibold">Criar Nova Categoria</h4>
            </div>

            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-500 mb-2">
                            Nome da Categoria <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}" 
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                               placeholder="Digite o nome da categoria">
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-500 mb-2">
                            Descrição
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                  placeholder="Descrição detalhada da categoria (opcional)">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-500 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" 
                                id="status" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Ativo</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inativo</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 mt-8">
                    <button type="submit" 
                            class="btn text-white font-medium hover:bg-blue-700 inline-flex items-center">
                        <i class="ti ti-check mr-2"></i>
                        Salvar Categoria
                    </button>
                    <a href="{{ route('admin.categories.index') }}" 
                       class="btn-outline-primary font-medium hover:bg-blue-600 hover:text-white inline-flex items-center">
                        <i class="ti ti-x mr-2"></i>
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 