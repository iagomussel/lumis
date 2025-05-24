@extends('layouts.admin')

@section('title', 'Clientes')

@section('header-actions')
    <a href="{{ route('admin.customers.create') }}" 
       class="btn text-white font-medium hover:bg-blue-700 inline-flex items-center">
        <i class="ti ti-plus mr-2"></i>
        Novo Cliente
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filters Card -->
    <div class="card">
        <div class="card-body">
            <h6 class="text-lg text-gray-500 font-semibold mb-4">Filtros de Busca</h6>
            <form method="GET" action="{{ route('admin.customers.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-400 mb-2">Buscar</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                               placeholder="Nome, email, documento..."
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
                        <label for="type" class="block text-sm font-medium text-gray-400 mb-2">Tipo</label>
                        <select name="type" id="type" 
                                class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Todos</option>
                            <option value="individual" {{ request('type') === 'individual' ? 'selected' : '' }}>Pessoa Física</option>
                            <option value="company" {{ request('type') === 'company' ? 'selected' : '' }}>Pessoa Jurídica</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end space-x-2">
                        <button type="submit" 
                                class="btn text-white font-medium hover:bg-blue-700">
                            <i class="ti ti-search mr-2"></i>
                            Filtrar
                        </button>
                        <a href="{{ route('admin.customers.index') }}" 
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
                <h4 class="text-gray-500 text-lg font-semibold">Lista de Clientes</h4>
                <div class="text-sm text-gray-400">
                    {{ $customers->total() }} cliente(s) encontrado(s)
                </div>
            </div>
            
            @if($customers->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cliente
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Contato
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tipo
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Limite de Crédito
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($customers as $customer)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center mr-3">
                                                <i class="ti ti-user text-white text-lg"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-500">{{ $customer->name }}</div>
                                                @if($customer->company_name)
                                                    <div class="text-sm text-gray-400">{{ $customer->company_name }}</div>
                                                @endif
                                                @if($customer->document)
                                                    <div class="text-xs text-gray-400">{{ strtoupper($customer->document_type) }}: {{ $customer->document }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            @if($customer->email)
                                                <div class="flex items-center mb-1">
                                                    <i class="ti ti-mail mr-2 text-gray-400"></i>
                                                    {{ $customer->email }}
                                                </div>
                                            @endif
                                            @if($customer->phone)
                                                <div class="flex items-center text-gray-400">
                                                    <i class="ti ti-phone mr-2"></i>
                                                    {{ $customer->phone }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $customer->type === 'individual' ? 'bg-blue-500 text-white' : 'bg-blue-300 text-white' }}">
                                            {{ $customer->type === 'individual' ? 'Pessoa Física' : 'Pessoa Jurídica' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($customer->status === 'active') bg-teal-500 text-white
                                            @elseif($customer->status === 'inactive') bg-yellow-500 text-white
                                            @else bg-red-500 text-white
                                            @endif">
                                            @if($customer->status === 'active') 
                                                <i class="ti ti-check mr-1"></i> Ativo
                                            @elseif($customer->status === 'inactive') 
                                                <i class="ti ti-clock mr-1"></i> Inativo
                                            @else 
                                                <i class="ti ti-x mr-1"></i> Bloqueado
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-500">
                                            {{ $customer->formatted_credit_limit }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.customers.show', $customer) }}" 
                                               class="text-blue-600 hover:text-blue-700 inline-flex items-center">
                                                <i class="ti ti-eye mr-1"></i> Ver
                                            </a>
                                            <a href="{{ route('admin.customers.edit', $customer) }}" 
                                               class="text-yellow-500 hover:text-yellow-600 inline-flex items-center">
                                                <i class="ti ti-edit mr-1"></i> Editar
                                            </a>
                                            <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}" 
                                                  class="inline" 
                                                  onsubmit="return confirm('Tem certeza que deseja excluir este cliente?')">
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

                @if($customers->hasPages())
                    <div class="mt-6">
                        {{ $customers->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="ti ti-users text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-500 mb-2">Nenhum cliente encontrado</h3>
                    <p class="text-sm text-gray-400 mb-6">
                        @if(request()->hasAny(['search', 'status', 'type']))
                            Nenhum cliente corresponde aos filtros aplicados.
                        @else
                            Comece criando seu primeiro cliente.
                        @endif
                    </p>
                    <a href="{{ route('admin.customers.create') }}" 
                       class="btn text-white font-medium hover:bg-blue-700 inline-flex items-center">
                        <i class="ti ti-plus mr-2"></i>
                        Novo Cliente
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 