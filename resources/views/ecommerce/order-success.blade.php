@extends('layouts.ecommerce')

@section('title', 'Pedido Confirmado')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Success Header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
            <i class="ti ti-check text-green-600 text-4xl"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Pedido Confirmado!</h1>
        <p class="text-gray-600">Seu pagamento foi processado com sucesso</p>
    </div>

    <!-- Progress Steps -->
    <div class="mb-8">
        <div class="flex items-center justify-center">
            <div class="flex items-center">
                <!-- Step 1 -->
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-semibold">
                        <i class="ti ti-check"></i>
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-900">Carrinho</span>
                </div>
                
                <!-- Connector -->
                <div class="w-16 h-1 bg-green-500 mx-4"></div>
                
                <!-- Step 2 -->
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-semibold">
                        <i class="ti ti-check"></i>
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-900">Checkout</span>
                </div>
                
                <!-- Connector -->
                <div class="w-16 h-1 bg-green-500 mx-4"></div>
                
                <!-- Step 3 -->
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-semibold">
                        <i class="ti ti-check"></i>
                    </div>
                    <span class="ml-2 text-sm font-medium text-green-600">Confirmação</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Order Info -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Detalhes do Pedido</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Número do Pedido</h4>
                            <p class="text-lg font-bold text-gray-900">#{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Data do Pedido</h4>
                            <p class="text-lg text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Status do Pedido</h4>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <i class="ti ti-clock mr-1"></i>
                                Em processamento
                            </span>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Status do Pagamento</h4>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="ti ti-check mr-1"></i>
                                Pago
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Itens do Pedido</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($order->items as $item)
                        <div class="p-6">
                            <div class="flex items-center space-x-4">
                                <!-- Product Image -->
                                <div class="flex-shrink-0">
                                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                        @if($item->product->main_image)
                                            <img src="{{ $item->product->main_image }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="w-full h-full object-cover rounded-lg">
                                        @else
                                            <i class="ti ti-package text-gray-400 text-xl"></i>
                                        @endif
                                    </div>
                                </div>

                                <!-- Product Details -->
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-lg font-medium text-gray-900">{{ $item->product->name }}</h4>
                                    @if($item->product->category)
                                        <p class="text-sm text-gray-500">{{ $item->product->category->name }}</p>
                                    @endif
                                    <p class="text-sm text-gray-600">SKU: {{ $item->product->sku }}</p>
                                </div>

                                <!-- Quantity and Price -->
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">Qtd: {{ $item->quantity }}</p>
                                    <p class="text-sm text-gray-600">Unit: R$ {{ number_format($item->price, 2, ',', '.') }}</p>
                                    <p class="text-lg font-bold text-gray-900">R$ {{ number_format($item->total, 2, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping Address -->
            @php
                $address = json_decode($order->shipping_address_json, true);
            @endphp
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="ti ti-map-pin mr-2"></i>
                        Endereço de Entrega
                    </h3>
                </div>
                <div class="p-6">
                    <div class="text-gray-700">
                        <p class="font-medium">{{ $order->customer->name }}</p>
                        <p>{{ $address['address'] }}, {{ $address['number'] }}</p>
                        @if($address['complement'])
                            <p>{{ $address['complement'] }}</p>
                        @endif
                        <p>{{ $address['neighborhood'] }}</p>
                        <p>{{ $address['city'] }} - {{ $address['state'] }}</p>
                        <p>CEP: {{ $address['zip_code'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Resumo do Pagamento</h3>
                </div>

                <div class="p-6">
                    <!-- Totals -->
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium">R$ {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Frete</span>
                            <span class="font-medium text-green-600">Grátis</span>
                        </div>
                        <hr class="border-gray-200">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total Pago</span>
                            <span class="text-green-600">R$ {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Método de Pagamento</h4>
                        <div class="flex items-center">
                            <i class="ti ti-credit-card text-gray-400 mr-2"></i>
                            <span class="text-gray-700">Cartão de Crédito/Débito</span>
                        </div>
                    </div>

                    <!-- Next Steps -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h4 class="text-sm font-semibold text-blue-900 mb-2">Próximos Passos:</h4>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• Você receberá um e-mail de confirmação</li>
                            <li>• Seu pedido será processado em até 1 dia útil</li>
                            <li>• Você receberá o código de rastreamento</li>
                        </ul>
                    </div>

                    <!-- Actions -->
                    <div class="space-y-3">
                        <a href="{{ route('ecommerce.home') }}" 
                           class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors text-center block">
                            <i class="ti ti-home mr-2"></i>
                            Voltar à Loja
                        </a>
                        
                        @auth('customer')
                            <a href="{{ route('customer.dashboard') }}" 
                               class="w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-lg font-semibold hover:bg-gray-200 transition-colors text-center block">
                                <i class="ti ti-user mr-2"></i>
                                Minha Conta
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Customer Service -->
            <div class="bg-gray-50 rounded-xl p-6 mt-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Precisa de Ajuda?</h4>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center text-gray-600">
                        <i class="ti ti-phone mr-3 text-blue-600"></i>
                        <span>(21) 99577-5689</span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <i class="ti ti-mail mr-3 text-blue-600"></i>
                        <span>contato@lumispresentes.com.br</span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <i class="ti ti-clock mr-3 text-blue-600"></i>
                        <span>Seg-Sex: 9h às 18h</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thank You Message -->
    <div class="mt-12 text-center">
        <div class="max-w-2xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Obrigado pela sua compra!</h2>
            <p class="text-gray-600 text-lg">
                Sua confiança é muito importante para nós. Estamos preparando seu pedido com todo carinho 
                e você receberá todas as atualizações por e-mail.
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-redirect to account or home after 30 seconds
setTimeout(function() {
    const accountLink = document.querySelector('a[href*="customer.dashboard"]');
    if (accountLink) {
        window.location.href = accountLink.href;
    } else {
        window.location.href = '{{ route('ecommerce.home') }}';
    }
}, 30000);

// Print functionality
function printOrder() {
    window.print();
}

// Copy order number
function copyOrderNumber() {
    const orderNumber = '{{ $order->order_number }}';
    navigator.clipboard.writeText(orderNumber).then(function() {
        alert('Número do pedido copiado!');
    });
}
</script>
@endpush 