@extends('layouts.admin')

@section('title', 'Fornecedores')

@section('header-actions')
    <a href="{{ route('admin.suppliers.create') }}" 
       class="btn text-white font-medium hover:bg-blue-700 inline-flex items-center">
        <i class="ti ti-plus mr-2"></i>
        Novo Fornecedor
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filters Card -->
    <div class="card">
        <div class="card-body">
            <h6 class="text-lg text-gray-500 font-semibold mb-4">Filtros de Busca</h6>
            <form method="GET" action="{{ route('admin.suppliers.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-400 mb-2">Buscar</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                               placeholder="Nome, empresa, email, CNPJ..."
                               class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0">
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-400 mb-2">Status</label>
                        <select name="status" id="status" 
                                class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Todos</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativo</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inativo</option>
                            <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Bloqueado</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-400 mb-2">Cidade</label>
                        <input type="text" name="city" id="city" value="{{ request('city') }}"
                               placeholder="Filtrar por cidade..."
                               class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0">
                    </div>
                    
                    <div class="flex items-end space-x-2">
                        <button type="submit" 
                                class="btn text-white font-medium hover:bg-blue-700">
                            <i class="ti ti-search mr-2"></i>
                            Filtrar
                        </button>
                        <a href="{{ route('admin.suppliers.index') }}" 
                           class="btn-outline-primary font-medium hover:bg-blue-600 hover:text-white inline-flex items-center">
                            <i class="ti ti-refresh mr-2"></i>
                            Limpar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Card -->
    <div class="card">
        <div class="card-body">
            <div class="flex justify-between items-center mb-5">
                <h4 class="text-gray-500 text-lg font-semibold">Lista de Fornecedores</h4>
                <div class="text-sm text-gray-400">
                    {{ $suppliers->total() }} fornecedor(es) encontrado(s)
                </div>
            </div>
            
            @if($suppliers->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fornecedor
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Contato
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Localização
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Avaliação
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($suppliers as $supplier)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center mr-3">
                                                <i class="ti ti-building text-white text-lg"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-500">{{ $supplier->name }}</div>
                                                @if($supplier->company_name)
                                                    <div class="text-sm text-gray-400">{{ $supplier->company_name }}</div>
                                                @endif
                                                @if($supplier->cnpj)
                                                    <div class="text-xs text-gray-400">CNPJ: {{ $supplier->cnpj }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            @if($supplier->email)
                                                <div class="flex items-center mb-1">
                                                    <i class="ti ti-mail mr-2 text-gray-400"></i>
                                                    {{ $supplier->email }}
                                                </div>
                                            @endif
                                            @if($supplier->phone)
                                                <div class="flex items-center text-gray-400">
                                                    <i class="ti ti-phone mr-2"></i>
                                                    {{ $supplier->phone }}
                                                </div>
                                            @endif
                                            @if($supplier->contact_person)
                                                <div class="flex items-center text-gray-400 text-xs mt-1">
                                                    <i class="ti ti-user mr-2"></i>
                                                    {{ $supplier->contact_person }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            @if($supplier->city || $supplier->state)
                                                <div class="flex items-center">
                                                    <i class="ti ti-map-pin mr-2 text-gray-400"></i>
                                                    {{ $supplier->city }}@if($supplier->city && $supplier->state), @endif{{ $supplier->state }}
                                                </div>
                                            @endif
                                            @if($supplier->zip_code)
                                                <div class="text-xs text-gray-400 mt-1">CEP: {{ $supplier->zip_code }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($supplier->status === 'active') bg-green-500 text-white
                                            @elseif($supplier->status === 'blocked') bg-red-500 text-white
                                            @else bg-gray-500 text-white
                                            @endif">
                                            @if($supplier->status === 'active') 
                                                <i class="ti ti-check mr-1"></i> Ativo
                                            @elseif($supplier->status === 'blocked') 
                                                <i class="ti ti-ban mr-1"></i> Bloqueado
                                            @else 
                                                <i class="ti ti-x mr-1"></i> Inativo
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($supplier->rating)
                                            <div class="flex items-center">
                                                <div class="flex text-yellow-400">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $supplier->rating)
                                                            <i class="ti ti-star-filled text-sm"></i>
                                                        @else
                                                            <i class="ti ti-star text-sm"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="ml-2 text-sm text-gray-500">{{ number_format($supplier->rating, 1) }}</span>
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.suppliers.show', $supplier) }}" 
                                               class="text-blue-600 hover:text-blue-700 inline-flex items-center">
                                                <i class="ti ti-eye mr-1"></i> Ver
                                            </a>
                                            <a href="{{ route('admin.suppliers.edit', $supplier) }}" 
                                               class="text-yellow-500 hover:text-yellow-600 inline-flex items-center">
                                                <i class="ti ti-edit mr-1"></i> Editar
                                            </a>
                                            <form method="POST" action="{{ route('admin.suppliers.destroy', $supplier) }}" 
                                                  class="inline" 
                                                  onsubmit="return confirm('Tem certeza que deseja excluir este fornecedor?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-600 inline-flex items-center">
                                                    <i class="ti ti-trash mr-1"></i> Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($suppliers->hasPages())
                    <div class="mt-6">
                        {{ $suppliers->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="ti ti-building text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-500 mb-2">Nenhum fornecedor encontrado</h3>
                    <p class="text-sm text-gray-400 mb-6">
                        @if(request()->hasAny(['search', 'status', 'city']))
                            Tente ajustar os filtros ou 
                            <a href="{{ route('admin.suppliers.index') }}" class="text-blue-600 hover:text-blue-700">limpar a busca</a>
                        @else
                            Comece criando seu primeiro fornecedor
                        @endif
                    </p>
                    <a href="{{ route('admin.suppliers.create') }}" 
                       class="btn text-white font-medium hover:bg-blue-700 inline-flex items-center">
                        <i class="ti ti-plus mr-2"></i>
                        Criar Primeiro Fornecedor
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 