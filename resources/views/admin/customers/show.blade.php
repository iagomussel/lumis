@extends('layouts.admin')

@section('title', 'Detalhes do Cliente')

@section('header-actions')
    <div class="flex items-center space-x-2">
        <a href="{{ route('admin.customers.edit', $customer) }}" 
           class="btn bg-blue-600 text-white hover:bg-blue-700">
            <i class="ti ti-edit mr-2"></i>
            Editar
        </a>
        <a href="{{ route('admin.customers.index') }}" 
           class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left mr-2"></i>
            Voltar
        </a>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Customer Header -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-start justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="ti ti-user text-blue-600 text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $customer->name }}</h1>
                        <p class="text-gray-600">{{ $customer->email }}</p>
                        <div class="flex items-center space-x-4 mt-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($customer->status === 'active') bg-green-100 text-green-800
                                @elseif($customer->status === 'inactive') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($customer->status) }}
                            </span>
                            <span class="text-sm text-gray-500">
                                {{ $customer->type === 'individual' ? 'Pessoa Física' : 'Pessoa Jurídica' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Cliente desde</p>
                    <p class="font-semibold">{{ $customer->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Customer Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold">Informações Pessoais</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Nome Completo</label>
                            <p class="text-gray-900">{{ $customer->name }}</p>
                        </div>
                        
                        @if($customer->type === 'company' && $customer->company_name)
                        <div>
                            <label class="text-sm font-medium text-gray-700">Razão Social</label>
                            <p class="text-gray-900">{{ $customer->company_name }}</p>
                        </div>
                        @endif

                        <div>
                            <label class="text-sm font-medium text-gray-700">E-mail</label>
                            <p class="text-gray-900">{{ $customer->email ?: 'Não informado' }}</p>
                        </div>

                        @if($customer->phone)
                        <div>
                            <label class="text-sm font-medium text-gray-700">Telefone</label>
                            <p class="text-gray-900">{{ $customer->phone }}</p>
                        </div>
                        @endif

                        @if($customer->mobile)
                        <div>
                            <label class="text-sm font-medium text-gray-700">Celular</label>
                            <p class="text-gray-900">{{ $customer->mobile }}</p>
                        </div>
                        @endif

                        @if($customer->document)
                        <div>
                            <label class="text-sm font-medium text-gray-700">
                                {{ $customer->document_type === 'cnpj' ? 'CNPJ' : 'CPF' }}
                            </label>
                            <p class="text-gray-900">{{ $customer->document }}</p>
                        </div>
                        @endif

                        @if($customer->birth_date)
                        <div>
                            <label class="text-sm font-medium text-gray-700">Data de Nascimento</label>
                            <p class="text-gray-900">{{ \Carbon\Carbon::parse($customer->birth_date)->format('d/m/Y') }}</p>
                        </div>
                        @endif

                        @if($customer->gender)
                        <div>
                            <label class="text-sm font-medium text-gray-700">Gênero</label>
                            <p class="text-gray-900">
                                @if($customer->gender === 'M') Masculino
                                @elseif($customer->gender === 'F') Feminino
                                @else Outro
                                @endif
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            @if($customer->address)
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold">Endereço</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-700">Endereço</label>
                            <p class="text-gray-900">
                                {{ $customer->address }}
                                @if($customer->address_number), {{ $customer->address_number }}@endif
                                @if($customer->complement) - {{ $customer->complement }}@endif
                            </p>
                        </div>

                        @if($customer->neighborhood)
                        <div>
                            <label class="text-sm font-medium text-gray-700">Bairro</label>
                            <p class="text-gray-900">{{ $customer->neighborhood }}</p>
                        </div>
                        @endif

                        @if($customer->city)
                        <div>
                            <label class="text-sm font-medium text-gray-700">Cidade</label>
                            <p class="text-gray-900">{{ $customer->city }}</p>
                        </div>
                        @endif

                        @if($customer->state)
                        <div>
                            <label class="text-sm font-medium text-gray-700">Estado</label>
                            <p class="text-gray-900">{{ $customer->state }}</p>
                        </div>
                        @endif

                        @if($customer->zip_code)
                        <div>
                            <label class="text-sm font-medium text-gray-700">CEP</label>
                            <p class="text-gray-900">{{ $customer->zip_code }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Notes -->
            @if($customer->notes)
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold">Observações</h3>
                </div>
                <div class="card-body">
                    <p class="text-gray-900">{{ $customer->notes }}</p>
                </div>
            </div>
            @endif

            <!-- Orders History -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold">Histórico de Pedidos</h3>
                        <span class="text-sm text-gray-500">{{ $customer->orders->count() }} pedidos</span>
                    </div>
                </div>
                <div class="card-body">
                    @if($customer->orders->count() > 0)
                        <div class="space-y-4">
                            @foreach($customer->orders->take(5) as $order)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <h4 class="font-medium text-gray-900">Pedido #{{ $order->id }}</h4>
                                        <p class="text-sm text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-900">R$ {{ number_format($order->total, 2, ',', '.') }}</p>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                            @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                            @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.orders.show', $order) }}" 
                                           class="text-blue-600 hover:text-blue-700">
                                            <i class="ti ti-external-link"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($customer->orders->count() > 5)
                            <div class="mt-4 text-center">
                                <a href="{{ route('admin.orders.index', ['customer' => $customer->id]) }}" 
                                   class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                    Ver todos os pedidos <i class="ti ti-arrow-right ml-1"></i>
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="ti ti-shopping-cart text-gray-400 text-2xl"></i>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Nenhum pedido encontrado</h4>
                            <p class="text-gray-600">Este cliente ainda não fez nenhum pedido.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold">Estatísticas</h3>
                </div>
                <div class="card-body space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total de Pedidos</span>
                        <span class="font-semibold">{{ $customer->orders->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total Gasto</span>
                        <span class="font-semibold">R$ {{ number_format($customer->orders->sum('total'), 2, ',', '.') }}</span>
                    </div>
                    @if($customer->credit_limit)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Limite de Crédito</span>
                        <span class="font-semibold">R$ {{ number_format($customer->credit_limit, 2, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Cliente desde</span>
                        <span class="font-semibold">{{ $customer->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold">Ações Rápidas</h3>
                </div>
                <div class="card-body space-y-3">
                    <a href="{{ route('admin.customers.edit', $customer) }}" 
                       class="w-full btn bg-blue-600 text-white hover:bg-blue-700">
                        <i class="ti ti-edit mr-2"></i>
                        Editar Cliente
                    </a>
                    <a href="{{ route('admin.orders.create', ['customer' => $customer->id]) }}" 
                       class="w-full btn bg-green-600 text-white hover:bg-green-700">
                        <i class="ti ti-plus mr-2"></i>
                        Novo Pedido
                    </a>
                    @if($customer->email)
                    <a href="mailto:{{ $customer->email }}" 
                       class="w-full btn btn-outline-primary">
                        <i class="ti ti-mail mr-2"></i>
                        Enviar E-mail
                    </a>
                    @endif
                    @if($customer->phone)
                    <a href="tel:{{ $customer->phone }}" 
                       class="w-full btn btn-outline-primary">
                        <i class="ti ti-phone mr-2"></i>
                        Ligar
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 