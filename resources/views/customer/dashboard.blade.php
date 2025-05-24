@extends('layouts.public')

@section('title', 'Dashboard')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-500">Dashboard</h1>
                    <p class="text-gray-400">Bem-vindo, {{ $customer->name }}!</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                        @if($customer->status === 'active') bg-green-100 text-green-800
                        @elseif($customer->status === 'inactive') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst($customer->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center mr-4">
                            <i class="ti ti-shopping-cart text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Total de Pedidos</p>
                            <p class="text-2xl font-bold text-gray-500">{{ $totalOrders }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-green-500 flex items-center justify-center mr-4">
                            <i class="ti ti-currency-real text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Total Gasto</p>
                            <p class="text-2xl font-bold text-gray-500">R$ {{ number_format($totalSpent, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-yellow-500 flex items-center justify-center mr-4">
                            <i class="ti ti-clock text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Pedidos Pendentes</p>
                            <p class="text-2xl font-bold text-gray-500">{{ $pendingOrders }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-purple-500 flex items-center justify-center mr-4">
                            <i class="ti ti-credit-card text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Limite de Crédito</p>
                            <p class="text-2xl font-bold text-gray-500">{{ $customer->formatted_credit_limit }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Orders -->
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-500">Pedidos Recentes</h3>
                            <a href="#" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                Ver todos
                            </a>
                        </div>

                        @if($recentOrders->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentOrders as $order)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <h4 class="font-medium text-gray-500">Pedido #{{ $order->id }}</h4>
                                                <p class="text-sm text-gray-400">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($order->status === 'completed') bg-green-100 text-green-800
                                                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <p class="text-sm text-gray-400">
                                                {{ $order->items->count() }} {{ $order->items->count() === 1 ? 'item' : 'itens' }}
                                            </p>
                                            <p class="font-semibold text-gray-500">
                                                R$ {{ number_format($order->total_amount, 2, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="ti ti-shopping-cart text-gray-300 text-4xl mb-4"></i>
                                <p class="text-gray-400">Nenhum pedido encontrado</p>
                                <p class="text-sm text-gray-400">Seus pedidos aparecerão aqui quando você fizer compras</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="lg:col-span-1">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-lg font-semibold text-gray-500 mb-6">Informações da Conta</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-400">Nome</p>
                                <p class="font-medium text-gray-500">{{ $customer->name }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-400">E-mail</p>
                                <p class="font-medium text-gray-500">{{ $customer->email }}</p>
                            </div>

                            @if($customer->phone)
                                <div>
                                    <p class="text-sm text-gray-400">Telefone</p>
                                    <p class="font-medium text-gray-500">{{ $customer->phone }}</p>
                                </div>
                            @endif

                            @if($customer->document)
                                <div>
                                    <p class="text-sm text-gray-400">{{ strtoupper($customer->document_type) }}</p>
                                    <p class="font-medium text-gray-500">{{ $customer->document }}</p>
                                </div>
                            @endif

                            <div>
                                <p class="text-sm text-gray-400">Tipo de Cliente</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($customer->type === 'individual') bg-blue-100 text-blue-800
                                    @else bg-purple-100 text-purple-800 @endif">
                                    {{ $customer->type === 'individual' ? 'Pessoa Física' : 'Pessoa Jurídica' }}
                                </span>
                            </div>

                            @if($customer->company_name)
                                <div>
                                    <p class="text-sm text-gray-400">Empresa</p>
                                    <p class="font-medium text-gray-500">{{ $customer->company_name }}</p>
                                </div>
                            @endif

                            <div>
                                <p class="text-sm text-gray-400">Membro desde</p>
                                <p class="font-medium text-gray-500">{{ $customer->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <a href="#" class="btn-outline-primary w-full text-center inline-flex items-center justify-center">
                                <i class="ti ti-edit mr-2"></i>
                                Editar Perfil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 