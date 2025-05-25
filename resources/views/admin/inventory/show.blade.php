@extends('layouts.admin')

@section('title', 'Detalhes do Estoque')

@section('header-actions')
    <div class="flex space-x-2">
        <a href="{{ route('admin.inventory.edit', $product) }}" 
           class="btn text-white font-medium hover:bg-blue-700 inline-flex items-center">
            <i class="ti ti-edit mr-2"></i>
            Ajustar Estoque
        </a>
        <a href="{{ route('admin.products.edit', $product) }}" 
           class="btn-outline-success font-medium hover:bg-green-600 hover:text-white inline-flex items-center">
            <i class="ti ti-settings mr-2"></i>
            Editar Produto
        </a>
        <a href="{{ route('admin.inventory.index') }}" 
           class="btn-outline-primary font-medium hover:bg-blue-600 hover:text-white inline-flex items-center">
            <i class="ti ti-arrow-left mr-2"></i>
            Voltar
        </a>
    </div>
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Informações Principais -->
    <div class="card">
        <div class="card-body">
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center">
                    <div class="w-20 h-20 rounded-full bg-indigo-500 flex items-center justify-center mr-6">
                        <i class="ti ti-package text-white text-3xl"></i>
                    </div>
                    <div>
                        <h4 class="text-gray-500 text-2xl font-semibold">{{ $product->name }}</h4>
                        @if($product->sku)
                            <p class="text-gray-400 text-lg">SKU: {{ $product->sku }}</p>
                        @endif
                        @if($product->category)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 mt-2">
                                {{ $product->category->name }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-400">Status do Produto</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($product->status === 'active') bg-green-500 text-white
                        @else bg-gray-500 text-white
                        @endif">
                        @if($product->status === 'active') 
                            <i class="ti ti-check mr-1"></i> Ativo
                        @else 
                            <i class="ti ti-x mr-1"></i> Inativo
                        @endif
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Estoque Atual -->
                <div class="text-center">
                    <div class="w-16 h-16 rounded-full 
                        @if($product->stock_quantity <= 0) bg-red-100
                        @elseif($product->stock_quantity <= 10) bg-yellow-100
                        @else bg-green-100
                        @endif 
                        flex items-center justify-center mx-auto mb-3">
                        <i class="ti ti-package 
                            @if($product->stock_quantity <= 0) text-red-500
                            @elseif($product->stock_quantity <= 10) text-yellow-500
                            @else text-green-500
                            @endif text-xl"></i>
                    </div>
                    <p class="text-sm text-gray-400 mb-1">Estoque Atual</p>
                    <p class="text-3xl font-bold text-gray-500">{{ $product->stock_quantity }}</p>
                    @if($product->stock_quantity <= 0)
                        <p class="text-xs text-red-500">Sem estoque</p>
                    @elseif($product->stock_quantity <= 10)
                        <p class="text-xs text-yellow-500">Estoque baixo</p>
                    @else
                        <p class="text-xs text-green-500">Disponível</p>
                    @endif
                </div>

                <!-- Preço de Venda -->
                <div class="text-center">
                    <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-3">
                        <i class="ti ti-currency-real text-blue-500 text-xl"></i>
                    </div>
                    <p class="text-sm text-gray-400 mb-1">Preço de Venda</p>
                    <p class="text-2xl font-bold text-gray-500">R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                </div>

                <!-- Preço de Custo -->
                <div class="text-center">
                    <div class="w-16 h-16 rounded-full bg-purple-100 flex items-center justify-center mx-auto mb-3">
                        <i class="ti ti-receipt text-purple-500 text-xl"></i>
                    </div>
                    <p class="text-sm text-gray-400 mb-1">Preço de Custo</p>
                    <p class="text-2xl font-bold text-gray-500">
                        @if($product->cost_price)
                            R$ {{ number_format($product->cost_price, 2, ',', '.') }}
                        @else
                            -
                        @endif
                    </p>
                </div>

                <!-- Valor Total em Estoque -->
                <div class="text-center">
                    <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-3">
                        <i class="ti ti-calculator text-green-500 text-xl"></i>
                    </div>
                    <p class="text-sm text-gray-400 mb-1">Valor Total</p>
                    <p class="text-2xl font-bold text-gray-500">
                        @if($product->cost_price)
                            R$ {{ number_format($product->stock_quantity * $product->cost_price, 2, ',', '.') }}
                        @else
                            -
                        @endif
                    </p>
                    @if($product->cost_price)
                        <p class="text-xs text-gray-400">{{ $product->stock_quantity }} × R$ {{ number_format($product->cost_price, 2, ',', '.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Informações Detalhadas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Detalhes do Produto -->
        <div class="card">
            <div class="card-body">
                <h6 class="text-lg text-gray-500 font-semibold mb-4">Detalhes do Produto</h6>
                <div class="space-y-4">
                    @if($product->description)
                        <div>
                            <p class="text-sm text-gray-400">Descrição</p>
                            <p class="text-gray-500">{{ $product->description }}</p>
                        </div>
                    @endif
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-400">Data de Criação</p>
                            <p class="text-gray-500">{{ $product->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Última Atualização</p>
                            <p class="text-gray-500">{{ $product->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    @if($product->weight || $product->dimensions)
                        <div class="grid grid-cols-2 gap-4">
                            @if($product->weight)
                                <div>
                                    <p class="text-sm text-gray-400">Peso</p>
                                    <p class="text-gray-500">{{ $product->weight }}g</p>
                                </div>
                            @endif
                            @if($product->dimensions)
                                <div>
                                    <p class="text-sm text-gray-400">Dimensões</p>
                                    <p class="text-gray-500">{{ $product->dimensions }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Alertas e Ações -->
        <div class="card">
            <div class="card-body">
                <h6 class="text-lg text-gray-500 font-semibold mb-4">Alertas e Ações</h6>
                
                <!-- Alertas de Estoque -->
                <div class="space-y-3 mb-6">
                    @if($product->stock_quantity <= 0)
                        <div class="flex items-center p-3 bg-red-50 border border-red-200 rounded-lg">
                            <i class="ti ti-alert-circle text-red-500 mr-3"></i>
                            <div>
                                <p class="text-red-800 font-medium">Produto sem estoque</p>
                                <p class="text-red-600 text-sm">Este produto não está disponível para venda</p>
                            </div>
                        </div>
                    @elseif($product->stock_quantity <= 10)
                        <div class="flex items-center p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <i class="ti ti-alert-triangle text-yellow-500 mr-3"></i>
                            <div>
                                <p class="text-yellow-800 font-medium">Estoque baixo</p>
                                <p class="text-yellow-600 text-sm">Considere reabastecer este produto</p>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                            <i class="ti ti-check-circle text-green-500 mr-3"></i>
                            <div>
                                <p class="text-green-800 font-medium">Estoque adequado</p>
                                <p class="text-green-600 text-sm">Produto disponível para venda</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Ações Rápidas -->
                <div class="space-y-3">
                    <a href="{{ route('admin.inventory.edit', $product) }}" 
                       class="w-full btn text-white font-medium hover:bg-blue-700 inline-flex items-center justify-center">
                        <i class="ti ti-edit mr-2"></i>
                        Ajustar Estoque
                    </a>
                    
                    <a href="{{ route('admin.products.edit', $product) }}" 
                       class="w-full btn-outline-primary font-medium hover:bg-blue-600 hover:text-white inline-flex items-center justify-center">
                        <i class="ti ti-settings mr-2"></i>
                        Editar Produto
                    </a>

                    @if($product->status === 'active')
                        <button type="button" 
                                class="w-full btn-outline-warning font-medium hover:bg-yellow-600 hover:text-white inline-flex items-center justify-center">
                            <i class="ti ti-eye-off mr-2"></i>
                            Desativar Produto
                        </button>
                    @else
                        <button type="button" 
                                class="w-full btn-outline-success font-medium hover:bg-green-600 hover:text-white inline-flex items-center justify-center">
                            <i class="ti ti-eye mr-2"></i>
                            Ativar Produto
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Histórico de Movimentações (Placeholder) -->
    <div class="card">
        <div class="card-body">
            <h6 class="text-lg text-gray-500 font-semibold mb-4">Histórico de Movimentações</h6>
            <div class="text-center py-8">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                    <i class="ti ti-history text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-500 mb-2">Histórico não disponível</h3>
                <p class="text-sm text-gray-400">
                    O sistema de histórico de movimentações será implementado em breve.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
@stop

@section('js')
@stop 