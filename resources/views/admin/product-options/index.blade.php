@extends('layouts.admin')

@section('title', 'Opções de Produtos')

@section('content')
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Opções de Produtos</h2>
                <p class="text-gray-600">Gerencie as opções disponíveis para seus produtos (cor, tamanho, etc.)</p>
            </div>
            <a href="{{ route('admin.product-options.create') }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg inline-flex items-center">
                <i class="ti ti-plus mr-2"></i>
                Nova Opção
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.product-options.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" 
                       name="search" 
                       id="search"
                       value="{{ request('search') }}"
                       placeholder="Nome da opção..."
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                <select name="type" id="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Todos os tipos</option>
                    <option value="select" {{ request('type') === 'select' ? 'selected' : '' }}>Seleção</option>
                    <option value="color" {{ request('type') === 'color' ? 'selected' : '' }}>Cor</option>
                    <option value="text" {{ request('type') === 'text' ? 'selected' : '' }}>Texto</option>
                    <option value="number" {{ request('type') === 'number' ? 'selected' : '' }}>Número</option>
                </select>
            </div>

            <div>
                <label for="active" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="active" id="active" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Ativo</option>
                    <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md mr-2">
                    <i class="ti ti-search mr-1"></i>
                    Filtrar
                </button>
                <a href="{{ route('admin.product-options.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                    Limpar
                </a>
            </div>
        </form>
    </div>

    <!-- Lista de Opções -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        @if($options->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Opção
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tipo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Valores
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Produtos
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($options as $option)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $option->name }}</div>
                                        @if($option->description)
                                            <div class="text-sm text-gray-500">{{ Str::limit($option->description, 50) }}</div>
                                        @endif
                                        @if($option->required)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 mt-1">
                                                Obrigatório
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $option->type === 'color' ? 'bg-purple-100 text-purple-800' : 
                                           ($option->type === 'select' ? 'bg-blue-100 text-blue-800' : 
                                           ($option->type === 'text' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800')) }}">
                                        {{ $option->type_display }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        @if($option->values && count($option->values) > 0)
                                            <div class="flex flex-wrap gap-1">
                                                @foreach(array_slice($option->values, 0, 3) as $value)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-800">
                                                        {{ $value }}
                                                    </span>
                                                @endforeach
                                                @if(count($option->values) > 3)
                                                    <span class="text-xs text-gray-500">+{{ count($option->values) - 3 }} mais</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-400">Nenhum valor</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('admin.product-options.toggle-status', $option) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $option->active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                            @if($option->active)
                                                <i class="ti ti-check mr-1"></i>
                                                Ativo
                                            @else
                                                <i class="ti ti-x mr-1"></i>
                                                Inativo
                                            @endif
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $option->products()->count() }} produto(s)
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('admin.product-options.show', $option) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.product-options.edit', $option) }}" 
                                           class="text-yellow-600 hover:text-yellow-900">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        @if($option->products()->count() === 0)
                                            <form action="{{ route('admin.product-options.destroy', $option) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('Tem certeza que deseja excluir esta opção?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            @if($options->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $options->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">
                    <i class="ti ti-settings"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma opção encontrada</h3>
                <p class="text-gray-500 mb-6">Comece criando sua primeira opção de produto.</p>
                <a href="{{ route('admin.product-options.create') }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg inline-flex items-center">
                    <i class="ti ti-plus mr-2"></i>
                    Criar Nova Opção
                </a>
            </div>
        @endif
    </div>
@endsection 