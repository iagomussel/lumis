@extends('layouts.ecommerce')

@section('title', 'Finalizar Compra')

@push('styles')
<script src="https://js.stripe.com/v3/"></script>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('ecommerce.home') }}" class="text-gray-700 hover:text-blue-600">
                    <i class="ti ti-home mr-2"></i>Início
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="ti ti-chevron-right text-gray-400 mx-2"></i>
                    <a href="{{ route('ecommerce.cart') }}" class="text-gray-700 hover:text-blue-600">Carrinho</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="ti ti-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-gray-500">Checkout</span>
                </div>
            </li>
        </ol>
    </nav>

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
                <div class="w-16 h-1 bg-blue-200 mx-4"></div>
                
                <!-- Step 2 -->
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold">
                        2
                    </div>
                    <span class="ml-2 text-sm font-medium text-blue-600">Checkout</span>
                </div>
                
                <!-- Connector -->
                <div class="w-16 h-1 bg-gray-200 mx-4"></div>
                
                <!-- Step 3 -->
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center text-sm font-semibold">
                        3
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-400">Confirmação</span>
                </div>
            </div>
        </div>
    </div>

    <form id="checkout-form" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf
        
        <!-- Left Column - Forms -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Customer Information -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="ti ti-user mr-2"></i>
                        Informações Pessoais
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nome *</label>
                            <input type="text" name="first_name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Seu nome">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sobrenome *</label>
                            <input type="text" name="last_name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Seu sobrenome">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">E-mail *</label>
                            <input type="email" name="email" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="seu@email.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Telefone *</label>
                            <input type="tel" name="phone" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="(11) 99999-9999">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">CPF *</label>
                            <input type="text" name="cpf" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="000.000.000-00">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="ti ti-map-pin mr-2"></i>
                        Endereço de Entrega
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">CEP *</label>
                            <input type="text" name="zip_code" required id="zip-code"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="00000-000">
                            <div id="cep-loading" class="hidden mt-1 text-sm text-gray-500">
                                <i class="ti ti-loader-2 animate-spin mr-1"></i>
                                Calculando frete...
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Endereço *</label>
                            <input type="text" name="address" required id="address"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Rua, Avenida...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Número *</label>
                            <input type="text" name="number" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="123">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Complemento</label>
                            <input type="text" name="complement"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Apartamento, bloco...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bairro *</label>
                            <input type="text" name="neighborhood" required id="neighborhood"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Bairro">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cidade *</label>
                            <input type="text" name="city" required id="city"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Cidade">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estado *</label>
                            <select name="state" required id="state"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Selecione...</option>
                                <option value="AC">Acre</option>
                                <option value="AL">Alagoas</option>
                                <option value="AP">Amapá</option>
                                <option value="AM">Amazonas</option>
                                <option value="BA">Bahia</option>
                                <option value="CE">Ceará</option>
                                <option value="DF">Distrito Federal</option>
                                <option value="ES">Espírito Santo</option>
                                <option value="GO">Goiás</option>
                                <option value="MA">Maranhão</option>
                                <option value="MT">Mato Grosso</option>
                                <option value="MS">Mato Grosso do Sul</option>
                                <option value="MG">Minas Gerais</option>
                                <option value="PA">Pará</option>
                                <option value="PB">Paraíba</option>
                                <option value="PR">Paraná</option>
                                <option value="PE">Pernambuco</option>
                                <option value="PI">Piauí</option>
                                <option value="RJ">Rio de Janeiro</option>
                                <option value="RN">Rio Grande do Norte</option>
                                <option value="RS">Rio Grande do Sul</option>
                                <option value="RO">Rondônia</option>
                                <option value="RR">Roraima</option>
                                <option value="SC">Santa Catarina</option>
                                <option value="SP">São Paulo</option>
                                <option value="SE">Sergipe</option>
                                <option value="TO">Tocantins</option>
                            </select>
                        </div>
                    </div>

                    <!-- Shipping Options -->
                    <div id="shipping-options" class="mt-6 hidden">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Opções de Entrega</h4>
                        <div id="shipping-options-list" class="space-y-3">
                            <!-- Shipping options will be populated here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="ti ti-credit-card mr-2"></i>
                        Método de Pagamento
                    </h3>
                </div>
                <div class="p-6">
                    <div id="card-element" class="p-3 border border-gray-300 rounded-lg bg-white">
                        <!-- Stripe card element will be mounted here -->
                    </div>
                    <div id="card-errors" role="alert" class="mt-2 text-sm text-red-600"></div>
                </div>
            </div>

            <!-- Additional Notes -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="ti ti-notes mr-2"></i>
                        Observações
                    </h3>
                </div>
                <div class="p-6">
                    <textarea name="notes" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Observações especiais sobre seu pedido (opcional)"></textarea>
                </div>
            </div>
        </div>

        <!-- Right Column - Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm sticky top-24">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Resumo do Pedido</h3>
                </div>

                <div class="p-6">
                    <!-- Items -->
                    <div class="space-y-4 mb-6">
                        @foreach($cartItems as $item)
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    @if($item['product']->main_image)
                                        <img src="{{ $item['product']->main_image }}" 
                                             alt="{{ $item['product']->name }}" 
                                             class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <i class="ti ti-package text-gray-400"></i>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-medium text-gray-900 truncate">{{ $item['product']->name }}</h4>
                                    <p class="text-sm text-gray-500">Qtd: {{ $item['quantity'] }}</p>
                                </div>
                                <div class="text-sm font-medium text-gray-900">
                                    R$ {{ number_format($item['total'], 2, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Totals -->
                    <div class="space-y-3 border-t border-gray-200 pt-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium" id="subtotal-amount">R$ {{ number_format($total, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between" id="shipping-cost-row">
                            <span class="text-gray-600">Frete</span>
                            @if($qualifiesForFreeShipping)
                                <span class="font-medium text-green-600" id="shipping-amount">Grátis</span>
                            @else
                                <span class="font-medium text-gray-500" id="shipping-amount">A calcular</span>
                            @endif
                        </div>
                        @if($qualifiesForFreeShipping)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                <div class="flex items-center">
                                    <i class="ti ti-truck text-green-600 mr-2"></i>
                                    <span class="text-sm font-medium text-green-800">Você ganhou frete grátis!</span>
                                </div>
                                <p class="text-xs text-green-600 mt-1">Compras acima de R$ {{ number_format($freeShippingMinimum, 2, ',', '.') }} têm frete grátis</p>
                            </div>
                        @endif
                        <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-3">
                            <span>Total</span>
                            <span id="final-total">R$ {{ number_format($total, 2, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submit-payment" 
                            class="w-full mt-6 bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="button-text">
                            <i class="ti ti-lock mr-2"></i>
                            Finalizar Pedido - <span id="button-total">R$ {{ number_format($total, 2, ',', '.') }}</span>
                        </span>
                        <span id="spinner" class="hidden">
                            <i class="ti ti-loader-2 animate-spin mr-2"></i>
                            Processando...
                        </span>
                    </button>

                    <!-- Security Badge -->
                    <div class="mt-4 text-center">
                        <div class="flex items-center justify-center text-sm text-gray-500">
                            <i class="ti ti-shield-check text-green-500 mr-2"></i>
                            Pagamento seguro com Stripe
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Initialize Stripe
const stripe = Stripe('{{ config('services.stripe.key', 'pk_test_YOUR_STRIPE_KEY') }}');
const elements = stripe.elements();

// Global variables
let selectedShipping = {
    cost: 0,
    service: 'STANDARD',
    deliveryTime: 7
};
const subtotal = {{ $total }};
const qualifiesForFreeShipping = {{ $qualifiesForFreeShipping ? 'true' : 'false' }};

// Card Element
const cardElement = elements.create('card', {
    style: {
        base: {
            fontSize: '16px',
            color: '#424770',
            '::placeholder': {
                color: '#aab7c4',
            },
        },
    },
});

cardElement.mount('#card-element');

// Handle real-time validation errors from the card Element
cardElement.on('change', ({error}) => {
    const displayError = document.getElementById('card-errors');
    if (error) {
        displayError.textContent = error.message;
    } else {
        displayError.textContent = '';
    }
});

// CEP Lookup and Shipping Calculation
document.getElementById('zip-code').addEventListener('blur', async function() {
    const cep = this.value.replace(/\D/g, '');
    if (cep.length === 8) {
        // First, populate address data
        try {
            const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
            const data = await response.json();
            
            if (!data.erro) {
                document.getElementById('address').value = data.logradouro;
                document.getElementById('neighborhood').value = data.bairro;
                document.getElementById('city').value = data.localidade;
                document.getElementById('state').value = data.uf;
            }
        } catch (error) {
            console.error('Erro ao buscar CEP:', error);
        }

        // Then calculate shipping if not free shipping qualified
        if (!qualifiesForFreeShipping) {
            await calculateShipping(this.value);
        }
    }
});

async function calculateShipping(cep) {
    const loadingElement = document.getElementById('cep-loading');
    const shippingOptions = document.getElementById('shipping-options');
    
    loadingElement.classList.remove('hidden');
    
    try {
        const response = await fetch('{{ route('ecommerce.shipping.calculate') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ cep: cep })
        });
        
        const result = await response.json();
        
        if (result.success) {
            displayShippingOptions(result.options, result.free_shipping);
            shippingOptions.classList.remove('hidden');
        } else {
            showNotification('Erro ao calcular frete: ' + result.message, 'error');
        }
        
    } catch (error) {
        console.error('Erro:', error);
        showNotification('Erro ao calcular frete', 'error');
    } finally {
        loadingElement.classList.add('hidden');
    }
}

function displayShippingOptions(options, isFreeShipping) {
    const container = document.getElementById('shipping-options-list');
    container.innerHTML = '';
    
    options.forEach((option, index) => {
        const optionElement = document.createElement('label');
        optionElement.className = 'flex items-center justify-between p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50';
        optionElement.innerHTML = `
            <div class="flex items-center">
                <input type="radio" name="shipping_option" value="${option.service_code}" 
                       class="text-blue-600 focus:ring-blue-500" 
                       data-cost="${option.price}" 
                       data-service="${option.service_code}"
                       data-delivery-time="${option.delivery_time}"
                       ${index === 0 ? 'checked' : ''}>
                <div class="ml-3">
                    <div class="text-sm font-medium text-gray-900">${option.service_name}</div>
                    <div class="text-xs text-gray-500">Entrega em ${option.formatted_delivery_time || option.delivery_time + ' dias úteis'}</div>
                </div>
            </div>
            <div class="text-sm font-medium text-gray-900">${option.formatted_price}</div>
        `;
        container.appendChild(optionElement);
        
        // Set default selection
        if (index === 0) {
            selectedShipping = {
                cost: option.price,
                service: option.service_code,
                deliveryTime: option.delivery_time
            };
            updateTotals();
        }
    });
    
    // Add event listeners to shipping options
    container.querySelectorAll('input[name="shipping_option"]').forEach(radio => {
        radio.addEventListener('change', function() {
            selectedShipping = {
                cost: parseFloat(this.dataset.cost),
                service: this.dataset.service,
                deliveryTime: parseInt(this.dataset.deliveryTime)
            };
            updateTotals();
        });
    });
}

function updateTotals() {
    const shippingAmount = document.getElementById('shipping-amount');
    const finalTotal = document.getElementById('final-total');
    const buttonTotal = document.getElementById('button-total');
    
    const total = subtotal + selectedShipping.cost;
    
    if (selectedShipping.cost === 0) {
        shippingAmount.textContent = 'Grátis';
        shippingAmount.className = 'font-medium text-green-600';
    } else {
        shippingAmount.textContent = 'R$ ' + selectedShipping.cost.toFixed(2).replace('.', ',');
        shippingAmount.className = 'font-medium text-gray-900';
    }
    
    const formattedTotal = 'R$ ' + total.toFixed(2).replace('.', ',');
    finalTotal.textContent = formattedTotal;
    buttonTotal.textContent = formattedTotal;
}

// Form Submission
document.getElementById('checkout-form').addEventListener('submit', async (event) => {
    event.preventDefault();
    
    const submitButton = document.getElementById('submit-payment');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');
    
    // Disable submit button
    submitButton.disabled = true;
    buttonText.classList.add('hidden');
    spinner.classList.remove('hidden');
    
    // Get form data
    const formData = new FormData(event.target);
    const customerData = Object.fromEntries(formData);
    
    try {
        const total = subtotal + selectedShipping.cost;
        
        // Create payment intent
        const response = await fetch('{{ route('ecommerce.payment.intent') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                customer_data: customerData,
                shipping_cost: selectedShipping.cost,
                total: total
            })
        });
        
        const { client_secret, error } = await response.json();
        
        if (error) {
            throw new Error(error);
        }
        
        // Confirm payment with Stripe
        const { error: stripeError, paymentIntent } = await stripe.confirmCardPayment(client_secret, {
            payment_method: {
                card: cardElement,
                billing_details: {
                    name: `${customerData.first_name} ${customerData.last_name}`,
                    email: customerData.email,
                    phone: customerData.phone,
                    address: {
                        line1: `${customerData.address}, ${customerData.number}`,
                        line2: customerData.complement,
                        city: customerData.city,
                        state: customerData.state,
                        postal_code: customerData.zip_code,
                        country: 'BR',
                    },
                },
            }
        });
        
        if (stripeError) {
            throw new Error(stripeError.message);
        }
        
        // Payment successful - create order
        const orderResponse = await fetch('{{ route('ecommerce.order.create') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                payment_intent_id: paymentIntent.id,
                customer_data: customerData,
                shipping_cost: selectedShipping.cost,
                shipping_service: selectedShipping.service,
                shipping_delivery_time: selectedShipping.deliveryTime
            })
        });
        
        const orderResult = await orderResponse.json();
        
        if (orderResult.success) {
            // Redirect to success page
            window.location.href = `{{ route('ecommerce.order.success') }}?order=${orderResult.order_id}`;
        } else {
            throw new Error(orderResult.error || 'Erro ao criar pedido');
        }
        
    } catch (error) {
        console.error('Erro:', error);
        showNotification(error.message || 'Erro no processamento do pagamento', 'error');
        
        // Re-enable submit button
        submitButton.disabled = false;
        buttonText.classList.remove('hidden');
        spinner.classList.add('hidden');
    }
});

function showNotification(message, type = 'info') {
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
    };
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 5000);
}
</script>
@endpush 