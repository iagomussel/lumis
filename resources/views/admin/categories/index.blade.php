@extends('layouts.admin')

@section('title', 'Categorias')

@section('header-actions')
    <a href="{{ route('admin.categories.create') }}" 
       class="btn text-white font-medium hover:bg-blue-700 inline-flex items-center">
        <i class="ti ti-plus mr-2"></i>
        Nova Categoria
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filters Card -->
    <div class="card">
        <div class="card-body">
            <h6 class="text-lg text-gray-500 font-semibold mb-4">Filtros de Busca</h6>
            <form method="GET" action="{{ route('admin.categories.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-500 mb-2">Buscar</label>
                        <input type="text" 
                               name="search" 
                               id="search"
                               value="{{ request('search') }}" 
                               placeholder="Nome ou descrição"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-500 mb-2">Status</label>
                        <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos os status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativo</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <div class="flex gap-2">
                            <button type="submit" class="btn text-white font-medium hover:bg-blue-700 inline-flex items-center">
                                <i class="ti ti-search mr-2"></i>
                                Filtrar
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn-outline-primary font-medium hover:bg-blue-600 hover:text-white inline-flex items-center">
                                <i class="ti ti-x mr-2"></i>
                                Limpar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="card">
        <div class="card-body">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h4 class="text-gray-500 text-lg font-semibold">Categorias</h4>
                    <p class="text-gray-400 text-sm">{{ $categories->total() }} categorias encontradas</p>
                </div>
            </div>

            @if($categories->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 font-medium text-gray-500">Nome</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-500">Descrição</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-500">Produtos</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-500">Status</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-500">Criado em</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-500">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-4 px-4">
                                        <div class="font-medium text-gray-500">{{ $category->name }}</div>
                                        <div class="text-sm text-gray-400">{{ $category->slug }}</div>
                                    </td>
                                    <td class="py-4 px-4">
                                        @if($category->description)
                                            <span class="text-gray-500">{{ Str::limit($category->description, 60) }}</span>
                                        @else
                                            <span class="text-gray-400">Sem descrição</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $category->products_count }} produtos
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $category->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $category->status === 'active' ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="text-gray-500">{{ $category->created_at->format('d/m/Y') }}</span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.categories.show', $category) }}" 
                                               class="text-blue-600 hover:text-blue-800 transition-colors">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.categories.edit', $category) }}" 
                                               class="text-green-600 hover:text-green-800 transition-colors">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-800 transition-colors"
                                                        onclick="return confirm('Tem certeza que deseja excluir esta categoria?')">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $categories->appends(request()->except('page'))->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="ti ti-category text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-500 mb-2">Nenhuma categoria encontrada</h3>
                    <p class="text-gray-400 mb-6">Não há categorias que correspondam aos critérios de busca.</p>
                    <a href="{{ route('admin.categories.create') }}" 
                       class="btn text-white font-medium hover:bg-blue-700 inline-flex items-center">
                        <i class="ti ti-plus mr-2"></i>
                        Criar Primeira Categoria
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 