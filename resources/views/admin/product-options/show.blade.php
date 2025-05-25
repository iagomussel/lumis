@extends('layouts.admin')

@section('title', 'Opção de Produto: ' . $productOption->name)

@section('content')
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $productOption->name }}</h2>
                <p class="text-gray-600">Detalhes da opção de produto</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.product-options.edit', $productOption) }}" 
                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg inline-flex items-center">
                    <i class="ti ti-edit mr-2"></i>
                    Editar
                </a>
                <a href="{{ route('admin.product-options.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center">
                    <i class="ti ti-arrow-left mr-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações Principais -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informações da Opção</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Nome</label>
                        <p class="text-lg font-medium text-gray-900">{{ $productOption->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tipo</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $productOption->type === 'color' ? 'bg-purple-100 text-purple-800' : 
                               ($productOption->type === 'select' ? 'bg-blue-100 text-blue-800' : 
                               ($productOption->type === 'text' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800')) }}">
                            {{ $productOption->type_display }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $productOption->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            @if($productOption->active)
                                <i class="ti ti-check mr-1"></i>
                                Ativo
                            @else
                                <i class="ti ti-x mr-1"></i>
                                Inativo
                            @endif
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Ordem</label>
                        <p class="text-lg font-medium text-gray-900">{{ $productOption->sort_order }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Obrigatório</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $productOption->required ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                            @if($productOption->required)
                                <i class="ti ti-check mr-1"></i>
                                Sim
                            @else
                                <i class="ti ti-x mr-1"></i>
                                Não
                            @endif
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Criado em</label>
                        <p class="text-lg font-medium text-gray-900">{{ $productOption->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                @if($productOption->description)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-500 mb-2">Descrição</label>
                        <p class="text-gray-800">{{ $productOption->description }}</p>
                    </div>
                @endif

                <!-- Valores -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-500 mb-3">Valores Disponíveis</label>
                    
                    @if($productOption->values && count($productOption->values) > 0)
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                            @foreach($productOption->values as $value)
                                <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3 border">
                                    <span class="text-sm font-medium text-gray-900">{{ $value }}</span>
                                    @if($productOption->type === 'color')
                                        <div class="w-4 h-4 rounded-full border border-gray-300 ml-2" 
                                             style="background-color: {{ $value }}"></div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-3 text-sm text-gray-500">
                            Total: {{ count($productOption->values) }} valor(es)
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="ti ti-list text-3xl mb-2"></i>
                            <p>Nenhum valor configurado</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Ações Rápidas -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Ações</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.product-options.edit', $productOption) }}" 
                       class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg inline-flex items-center justify-center">
                        <i class="ti ti-edit mr-2"></i>
                        Editar Opção
                    </a>

                    <form action="{{ route('admin.product-options.toggle-status', $productOption) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full {{ $productOption->active ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} text-white px-4 py-2 rounded-lg inline-flex items-center justify-center">
                            @if($productOption->active)
                                <i class="ti ti-x mr-2"></i>
                                Desativar
                            @else
                                <i class="ti ti-check mr-2"></i>
                                Ativar
                            @endif
                        </button>
                    </form>

                    @if($productOption->products()->count() === 0)
                        <form action="{{ route('admin.product-options.destroy', $productOption) }}" 
                              method="POST" 
                              onsubmit="return confirm('Tem certeza que deseja excluir esta opção?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg inline-flex items-center justify-center">
                                <i class="ti ti-trash mr-2"></i>
                                Excluir Opção
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Estatísticas</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total de valores:</span>
                        <span class="font-semibold text-gray-900">{{ count($productOption->values ?? []) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Produtos usando:</span>
                        <span class="font-semibold text-gray-900">{{ $productOption->products()->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Última atualização:</span>
                        <span class="font-semibold text-gray-900">{{ $productOption->updated_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Produtos Associados -->
            @if($productOption->products()->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Produtos que Usam Esta Opção</h3>
                    <div class="space-y-3">
                        @foreach($productOption->products()->take(5)->get() as $product)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                    <p class="text-sm text-gray-500">SKU: {{ $product->sku }}</p>
                                </div>
                                <a href="{{ route('admin.products.show', $product) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    <i class="ti ti-external-link"></i>
                                </a>
                            </div>
                        @endforeach
                        
                        @if($productOption->products()->count() > 5)
                            <div class="text-center pt-2">
                                <p class="text-sm text-gray-500">
                                    e mais {{ $productOption->products()->count() - 5 }} produto(s)...
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection 