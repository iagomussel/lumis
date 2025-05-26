@extends('layouts.admin')

@section('title', 'PDV - Ponto de Venda')

@section('content')

<div class="h-screen overflow-hidden bg-gray-50">
    <div class="flex h-full">
        <!-- Área Principal - Busca de Produtos -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <div class="bg-white border-b border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-gray-900">
                        <i class="ti ti-cash-register mr-2"></i>
                        PDV - Ponto de Venda
                    </h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">Usuário: {{ Auth::user()->name }}</span>
                        <span class="text-sm text-gray-500">{{ now()->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Busca de Produtos -->
            <div class="bg-white border-b border-gray-200 p-4">
                <div class="flex items-center space-x-4">
                    <div class="flex-1">
                        <label for="product-search" class="sr-only">Buscar produto</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ti ti-search text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                id="product-search" 
                                placeholder="Buscar produto por nome, SKU ou código de barras..."
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                autocomplete="off">
                        </div>
                    </div>
                    <button type="button" id="clear-search" class="px-4 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
            </div>

            <!-- Lista de Produtos -->
            <div class="flex-1 overflow-y-auto p-4">
                <div id="products-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 hidden">
                    <!-- Produtos serão carregados aqui via JavaScript -->
                </div>
                
                <!-- Estado vazio -->
                <div id="empty-state" class="text-center py-12">
                    <i class="ti ti-search text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-500 mb-2">Busque por produtos</h3>
                    <p class="text-gray-400">Digite o nome, SKU ou código do produto que deseja vender</p>
                </div>
            </div>
        </div>

        <!-- Sidebar - Carrinho -->
        <div class="w-96 bg-white border-l border-gray-200 flex flex-col">
            <!-- Header do Carrinho -->
            <div class="bg-blue-600 text-white p-4">
                <h2 class="text-lg font-semibold">
                    <i class="ti ti-shopping-cart mr-2"></i>
                    Carrinho de Vendas
                </h2>
                <p class="text-blue-100 text-sm">
                    <span id="cart-items-count">0</span> item(s)
                </p>
            </div>

            <!-- Seleção de Cliente -->
            <div class="border-b border-gray-200 p-4">
                <label for="customer-select" class="block text-sm font-medium text-gray-700 mb-2">Cliente</label>
                <select id="customer-select" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="">Venda sem cadastro</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" data-customer="{{ json_encode($customer) }}">
                            {{ $customer->name }} ({{ $customer->document }})
                        </option>
                    @endforeach
                </select>
                <button type="button" id="search-customer" class="mt-2 text-blue-600 hover:text-blue-800 text-sm">
                    <i class="ti ti-search mr-1"></i>
                    Buscar cliente
                </button>
            </div>

            <!-- Items do Carrinho -->
            <div class="flex-1 overflow-y-auto">
                <div id="cart-items" class="p-4 space-y-3">
                    <!-- Items serão adicionados aqui -->
                </div>
                
                <!-- Carrinho vazio -->
                <div id="empty-cart" class="p-4 text-center">
                    <i class="ti ti-shopping-cart-x text-gray-300 text-4xl mb-2"></i>
                    <p class="text-gray-500 text-sm">Carrinho vazio</p>
                    <p class="text-gray-400 text-xs">Adicione produtos para começar a venda</p>
                </div>
            </div>

            <!-- Totais e Pagamento -->
            <div class="border-t border-gray-200 p-4 space-y-4">
                <!-- Desconto -->
                <div>
                    <label for="discount" class="block text-sm font-medium text-gray-700 mb-1">Desconto (R$)</label>
                    <input 
                        type="number" 
                        id="discount" 
                        step="0.01" 
                        min="0"
                        placeholder="0,00"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <!-- Totais -->
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal:</span>
                        <span id="subtotal">R$ 0,00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Desconto:</span>
                        <span id="discount-amount">R$ 0,00</span>
                    </div>
                    <div class="flex justify-between text-lg font-semibold border-t pt-2">
                        <span>Total:</span>
                        <span id="total">R$ 0,00</span>
                    </div>
                </div>

                <!-- Método de Pagamento -->
                <div>
                    <label for="payment-method" class="block text-sm font-medium text-gray-700 mb-1">Forma de Pagamento</label>
                    <select id="payment-method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="money">Dinheiro</option>
                        <option value="credit_card">Cartão de Crédito</option>
                        <option value="debit_card">Cartão de Débito</option>
                        <option value="pix">PIX</option>
                        <option value="bank_transfer">Transferência</option>
                        <option value="bank_slip">Boleto</option>
                        <option value="check">Cheque</option>
                    </select>
                </div>

                <!-- Opção de Pagamento Parcial -->
                <div>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" id="partial-payment" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Pagamento Parcial (Sinal)</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1">Cliente paga apenas parte do valor agora</p>
                </div>

                <!-- Valor do Sinal (aparece quando checkbox marcado) -->
                <div id="partial-payment-section" class="hidden">
                    <label for="partial-amount" class="block text-sm font-medium text-gray-700 mb-1">Valor do Sinal (R$)</label>
                    <div class="flex space-x-2">
                        <input 
                            type="number" 
                            id="partial-amount" 
                            step="0.01" 
                            min="0"
                            placeholder="0,00"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <button type="button" id="set-50-percent" class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm">
                            50%
                        </button>
                    </div>
                    <div id="remaining-info" class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded text-sm text-yellow-800 hidden">
                        <div class="flex justify-between">
                            <span>Sinal:</span>
                            <span id="down-payment-display">R$ 0,00</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Restante para entrega:</span>
                            <span id="remaining-display">R$ 0,00</span>
                        </div>
                    </div>
                </div>

                <!-- Observações -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                    <textarea 
                        id="notes" 
                        rows="2" 
                        placeholder="Observações adicionais..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm resize-none"></textarea>
                </div>

                <!-- Botões de Ação -->
                <div class="flex space-x-2">
                    <button 
                        type="button" 
                        id="clear-cart" 
                        class="flex-1 px-4 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors text-sm font-medium">
                        <i class="ti ti-trash mr-1"></i>
                        Limpar
                    </button>
                    <button 
                        type="button" 
                        id="process-sale" 
                        class="flex-2 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium disabled:bg-gray-400"
                        disabled>
                        <i class="ti ti-check mr-1"></i>
                        Finalizar Venda
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Busca de Cliente -->
<div id="customer-search-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-96 max-h-96 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Buscar Cliente</h3>
        </div>
        <div class="p-6">
            <input 
                type="text" 
                id="customer-search-input" 
                placeholder="Nome, email ou documento do cliente..."
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <div id="customer-search-results" class="mt-4 max-h-48 overflow-y-auto">
                <!-- Resultados da busca -->
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
            <button type="button" id="close-customer-modal" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                Cancelar
            </button>
        </div>
    </div>
</div>

<!-- Modal de Sucesso -->
<div id="success-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-96">
        <div class="p-6 text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
                <i class="ti ti-check text-green-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Venda Realizada!</h3>
            <p class="text-gray-600 mb-4" id="success-message">Pedido finalizado com sucesso.</p>
            <div class="flex space-x-3">
                <button type="button" id="print-receipt" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="ti ti-printer mr-1"></i>
                    Imprimir
                </button>
                <button type="button" id="new-sale" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="ti ti-plus mr-1"></i>
                    Nova Venda
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Estado da aplicação
const appState = {
    cart: [],
    products: [],
    selectedCustomer: null,
    lastOrderId: null
};

// Elementos DOM - Inicialização segura
const elements = {};

function initializeElements() {
    elements.productSearch = document.getElementById('product-search');
    elements.productsGrid = document.getElementById('products-grid');
    elements.emptyState = document.getElementById('empty-state');
    elements.cartItems = document.getElementById('cart-items');
    elements.emptyCart = document.getElementById('empty-cart');
    elements.cartItemsCount = document.getElementById('cart-items-count');
    elements.subtotal = document.getElementById('subtotal');
    elements.discountAmount = document.getElementById('discount-amount');
    elements.total = document.getElementById('total');
    elements.discount = document.getElementById('discount');
    elements.paymentMethod = document.getElementById('payment-method');
    elements.notes = document.getElementById('notes');
    elements.customerSelect = document.getElementById('customer-select');
    elements.processSale = document.getElementById('process-sale');
    elements.clearCart = document.getElementById('clear-cart');
    elements.partialPayment = document.getElementById('partial-payment');
    elements.partialPaymentSection = document.getElementById('partial-payment-section');
    elements.partialAmount = document.getElementById('partial-amount');
    elements.set50Percent = document.getElementById('set-50-percent');
    elements.remainingInfo = document.getElementById('remaining-info');
    elements.downPaymentDisplay = document.getElementById('down-payment-display');
    elements.remainingDisplay = document.getElementById('remaining-display');
}

// Inicialização
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 DOM carregado - Inicializando PDV');
    
    try {
        // Inicializar elementos DOM
        initializeElements();
        
        // Verificar se estamos na página do PDV
        if (!elements.productSearch && !elements.cartItems) {
            console.log('ℹ️ Não é a página do PDV - pulando inicialização');
            return;
        }
        
        // Verificar se todos os elementos essenciais existem
        const requiredElements = ['product-search', 'products-grid', 'empty-state', 'cart-items', 'empty-cart'];
        const missingElements = [];
        
        for (const elementId of requiredElements) {
            const element = document.getElementById(elementId);
            if (!element) {
                console.error(`❌ Elemento obrigatório não encontrado: ${elementId}`);
                missingElements.push(elementId);
            }
        }
        
        if (missingElements.length > 0) {
            console.error('🚨 Elementos faltantes:', missingElements);
            console.log('ℹ️ Provavelmente não é a página do PDV');
            return;
        }
        
        initializeEventListeners();
        updateCartDisplay();
        
        // Foco inicial no campo de busca
        if (elements.productSearch) {
            elements.productSearch.focus();
        }
        
        console.log('✅ PDV inicializado com sucesso');
        
    } catch (error) {
        console.error('💥 Erro durante inicialização:', error);
        // Não mostrar alert se não for a página do PDV
        if (elements.productSearch || elements.cartItems) {
            alert('Erro durante inicialização do PDV: ' + error.message);
        }
    }
});

// Event Listeners
function initializeEventListeners() {
    // Verificar se todos os elementos existem
    if (!elements.productSearch) {
        console.error('❌ Elemento product-search não encontrado');
        return;
    }

    // Busca de produtos
    elements.productSearch.addEventListener('input', debounce(searchProducts, 300));
    
    // Limpar busca
    const clearSearchBtn = document.getElementById('clear-search');
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', () => {
            elements.productSearch.value = '';
            showEmptyState();
        });
    }
    
    // Carrinho
    if (elements.clearCart) elements.clearCart.addEventListener('click', clearCart);
    if (elements.processSale) elements.processSale.addEventListener('click', processSale);
    if (elements.discount) elements.discount.addEventListener('input', updateTotals);
    
    // Pagamento parcial
    if (elements.partialPayment) elements.partialPayment.addEventListener('change', togglePartialPayment);
    if (elements.partialAmount) elements.partialAmount.addEventListener('input', updatePartialPaymentInfo);
    if (elements.set50Percent) elements.set50Percent.addEventListener('click', set50PercentPayment);
    
    // Cliente
    if (elements.customerSelect) elements.customerSelect.addEventListener('change', selectCustomer);
    
    const searchCustomerBtn = document.getElementById('search-customer');
    if (searchCustomerBtn) searchCustomerBtn.addEventListener('click', openCustomerSearchModal);
    
    // Modals
    const closeCustomerModalBtn = document.getElementById('close-customer-modal');
    if (closeCustomerModalBtn) closeCustomerModalBtn.addEventListener('click', closeCustomerSearchModal);
    
    const customerSearchInput = document.getElementById('customer-search-input');
    if (customerSearchInput) customerSearchInput.addEventListener('input', debounce(searchCustomers, 300));
    
    const newSaleBtn = document.getElementById('new-sale');
    if (newSaleBtn) newSaleBtn.addEventListener('click', startNewSale);
    
    const printReceiptBtn = document.getElementById('print-receipt');
    if (printReceiptBtn) printReceiptBtn.addEventListener('click', printReceipt);
}

// Busca de produtos - Versão simplificada e robusta
async function searchProducts() {
    const query = elements.productSearch ? elements.productSearch.value.trim() : '';
    
    if (!query) {
        showEmptyState();
        return;
    }

    if (!elements.productsGrid) {
        console.error('❌ Elemento products-grid não encontrado');
        return;
    }

    // Mostrar loading
    elements.productsGrid.innerHTML = `
        <div class="col-span-full text-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-500">Buscando produtos...</p>
        </div>
    `;
    if (elements.emptyState) elements.emptyState.classList.add('hidden');
    elements.productsGrid.classList.remove('hidden');

    try {
        // Construir URL
        const baseUrl = '{{ route('admin.pos.search-products') }}';
        const searchUrl = `${baseUrl}?q=${encodeURIComponent(query)}`;
        
        // Fazer requisição
        const response = await fetch(searchUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
            },
            credentials: 'same-origin'
        });
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('❌ Erro na resposta:', errorText);
            throw new Error(`Erro ${response.status}: ${response.statusText}`);
        }
        
        const products = await response.json();
        
        if (!Array.isArray(products)) {
            throw new Error('Resposta inválida: esperado array de produtos');
        }
        
        appState.products = products;
        displayProducts(products);
        
    } catch (error) {
        console.error('❌ Erro na busca:', error);
        
        elements.productsGrid.innerHTML = `
            <div class="col-span-full text-center py-12">
                <div class="text-red-500 mb-4">
                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-red-600 mb-2">Erro na busca</h3>
                <p class="text-red-500 mb-4">${error.message}</p>
                <button onclick="searchProducts()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                    🔄 Tentar novamente
                </button>
            </div>
        `;
    }
}

// Exibir produtos
function displayProducts(products) {
    const grid = elements.productsGrid;
    const emptyState = elements.emptyState;
    
    if (!grid) {
        console.error('❌ Elemento products-grid não encontrado!');
        return;
    }
    
    if (!emptyState) {
        console.error('❌ Elemento empty-state não encontrado!');
        return;
    }
    
    if (products.length === 0) {
        grid.classList.add('hidden');
        emptyState.classList.remove('hidden');
        emptyState.innerHTML = `
            <i class="ti ti-package-off text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-500 mb-2">Nenhum produto encontrado</h3>
            <p class="text-gray-400">Tente buscar com outros termos</p>
        `;
        return;
    }

    emptyState.classList.add('hidden');
    grid.classList.remove('hidden');
    
    const productsHTML = products.map(product => `
        <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow cursor-pointer product-card" 
             data-product-id="${product.id}">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-3 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="ti ti-package text-blue-600 text-2xl"></i>
                </div>
                <h3 class="font-medium text-gray-900 text-sm mb-1 line-clamp-2">${product.name}</h3>
                <p class="text-xs text-gray-500 mb-2">${product.sku}</p>
                <p class="font-semibold text-green-600 mb-2">${product.formatted_price || 'R$ ' + product.price}</p>
                <p class="text-xs text-gray-400">Estoque: ${product.stock_quantity}</p>
                ${product.category ? `<span class="inline-block mt-2 px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">${product.category}</span>` : ''}
            </div>
        </div>
    `).join('');
    
    grid.innerHTML = productsHTML;

    // Adicionar event listeners aos produtos
    const productCards = grid.querySelectorAll('.product-card');
    
    productCards.forEach(card => {
        card.addEventListener('click', () => {
            const productId = parseInt(card.dataset.productId);
            const product = products.find(p => p.id === productId);
            if (product) {
                addToCart(product);
            }
        });
    });
}

function showEmptyState() {
    elements.productsGrid.classList.add('hidden');
    elements.emptyState.classList.remove('hidden');
    elements.emptyState.innerHTML = `
        <i class="ti ti-search text-gray-300 text-6xl mb-4"></i>
        <h3 class="text-lg font-medium text-gray-500 mb-2">Busque por produtos</h3>
        <p class="text-gray-400">Digite o nome, SKU ou código do produto que deseja vender</p>
    `;
}

// Adicionar ao carrinho
function addToCart(product) {
    try {
        const existingItem = appState.cart.find(item => item.product_id === product.id);
        
        if (existingItem) {
            if (existingItem.quantity < product.stock_quantity) {
                existingItem.quantity++;
            } else {
                alert('Quantidade máxima em estoque atingida!');
                return;
            }
        } else {
            const newItem = {
                product_id: product.id,
                name: product.name,
                sku: product.sku,
                unit_price: parseFloat(product.price),
                quantity: 1,
                stock_quantity: product.stock_quantity
            };
            appState.cart.push(newItem);
        }
        
        updateCartDisplay();
        
    } catch (error) {
        console.error('❌ Erro em addToCart:', error);
    }
}

// Remover do carrinho
function removeFromCart(productId) {
    appState.cart = appState.cart.filter(item => item.product_id !== productId);
    updateCartDisplay();
}

// Atualizar quantidade
function updateQuantity(productId, quantity) {
    const item = appState.cart.find(item => item.product_id === productId);
    if (!item) return;
    
    if (quantity <= 0) {
        removeFromCart(productId);
        return;
    }
    
    if (quantity > item.stock_quantity) {
        alert('Quantidade maior que o estoque disponível!');
        return;
    }
    
    item.quantity = quantity;
    updateCartDisplay();
}

// Atualizar exibição do carrinho
function updateCartDisplay() {
    try {
        const cartContainer = elements.cartItems;
        const emptyCart = elements.emptyCart;
        const itemsCount = elements.cartItemsCount;
        
        // Verificar se os elementos existem
        if (!cartContainer || !emptyCart || !itemsCount) {
            console.error('❌ Elementos do carrinho não encontrados:', {
                cartContainer: !!cartContainer,
                emptyCart: !!emptyCart,
                itemsCount: !!itemsCount
            });
            return;
        }
        
        if (appState.cart.length === 0) {
            cartContainer.classList.add('hidden');
            emptyCart.classList.remove('hidden');
            itemsCount.textContent = '0';
            if (elements.processSale) elements.processSale.disabled = true;
            updateTotals();
            return;
        }
        
        emptyCart.classList.add('hidden');
        cartContainer.classList.remove('hidden');
        if (elements.processSale) elements.processSale.disabled = false;
        
        const totalItems = appState.cart.reduce((sum, item) => sum + item.quantity, 0);
        itemsCount.textContent = totalItems;
        
        const cartHTML = appState.cart.map(item => `
            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900 text-sm">${item.name}</h4>
                        <p class="text-xs text-gray-500">${item.sku}</p>
                        <p class="text-sm text-green-600 font-medium">R$ ${parseFloat(item.unit_price).toFixed(2).replace('.', ',')}</p>
                    </div>
                    <button class="text-red-600 hover:text-red-800 text-sm" onclick="removeFromCart(${item.product_id})">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <button class="w-6 h-6 bg-gray-300 text-gray-700 rounded text-xs hover:bg-gray-400" 
                                onclick="updateQuantity(${item.product_id}, ${item.quantity - 1})">-</button>
                        <span class="text-sm font-medium w-8 text-center">${item.quantity}</span>
                        <button class="w-6 h-6 bg-gray-300 text-gray-700 rounded text-xs hover:bg-gray-400" 
                                onclick="updateQuantity(${item.product_id}, ${item.quantity + 1})">+</button>
                    </div>
                    <div class="text-sm font-semibold text-gray-900">
                        R$ ${(item.quantity * parseFloat(item.unit_price)).toFixed(2).replace('.', ',')}
                    </div>
                </div>
            </div>
        `).join('');
        
        cartContainer.innerHTML = cartHTML;
        
        updateTotals();
        
    } catch (error) {
        console.error('❌ Erro em updateCartDisplay:', error);
    }
}

// Atualizar totais
function updateTotals() {
    const subtotal = appState.cart.reduce((sum, item) => sum + (item.quantity * parseFloat(item.unit_price)), 0);
    const discount = parseFloat(elements.discount?.value) || 0;
    const total = Math.max(0, subtotal - discount);
    
    if (elements.subtotal) elements.subtotal.textContent = `R$ ${subtotal.toFixed(2).replace('.', ',')}`;
    if (elements.discountAmount) elements.discountAmount.textContent = `R$ ${discount.toFixed(2).replace('.', ',')}`;
    if (elements.total) elements.total.textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
}

// Processar venda
async function processSale() {
    if (appState.cart.length === 0) {
        alert('Adicione produtos ao carrinho!');
        return;
    }
    
    // Validar pagamento parcial
    const partialPaymentData = getPartialPaymentData();
    
    if (partialPaymentData && partialPaymentData.paidAmount > partialPaymentData.totalAmount) {
        alert('O valor do sinal não pode ser maior que o total!');
        if (elements.partialAmount) elements.partialAmount.focus();
        return;
    }
    
    if (partialPaymentData && partialPaymentData.paidAmount <= 0) {
        alert('O valor do sinal deve ser maior que zero!');
        if (elements.partialAmount) elements.partialAmount.focus();
        return;
    }
    
    const data = {
        customer_id: appState.selectedCustomer?.id || null,
        items: appState.cart,
        payment_method: elements.paymentMethod?.value || 'money',
        discount: parseFloat(elements.discount?.value) || 0,
        notes: elements.notes?.value?.trim() || '',
        partial_payment: partialPaymentData
    };
    
    if (elements.processSale) {
        elements.processSale.disabled = true;
        elements.processSale.innerHTML = '<i class="ti ti-loader animate-spin mr-1"></i> Processando...';
    }
    
    try {
        const response = await fetch('{{ route('admin.pos.process-sale') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            appState.lastOrderId = result.order.id;
            showSuccessModal(result);
            clearCart();
            

        } else {
            alert(result.message || 'Erro ao processar venda');
        }
    } catch (error) {
        console.error('Erro ao processar venda:', error);
        alert('Erro ao processar venda. Tente novamente.');
    } finally {
        if (elements.processSale) {
            elements.processSale.disabled = false;
            elements.processSale.innerHTML = '<i class="ti ti-check mr-1"></i> Finalizar Venda';
        }
    }
}

// Limpar carrinho
function clearCart() {
    appState.cart = [];
    appState.selectedCustomer = null;
    if (elements.customerSelect) elements.customerSelect.value = '';
    if (elements.discount) elements.discount.value = '';
    if (elements.notes) elements.notes.value = '';
    
    // Limpar pagamento parcial
    if (elements.partialPayment) elements.partialPayment.checked = false;
    if (elements.partialAmount) {
        elements.partialAmount.value = '';
        elements.partialAmount.style.borderColor = '';
        elements.partialAmount.style.backgroundColor = '';
    }
    if (elements.partialPaymentSection) elements.partialPaymentSection.classList.add('hidden');
    if (elements.remainingInfo) elements.remainingInfo.classList.add('hidden');
    
    updateCartDisplay();
}

// Modal de sucesso
function showSuccessModal(result) {
    const successMessage = document.getElementById('success-message');
    const successModal = document.getElementById('success-modal');
    
    if (successMessage) {
        successMessage.textContent = 
            `Pedido ${result.order.order_number} - Total: ${result.order.formatted_total}`;
    }
    
    if (successModal) {
        successModal.classList.remove('hidden');
        successModal.classList.add('flex');
    }
}

// Nova venda
function startNewSale() {
    const successModal = document.getElementById('success-modal');
    if (successModal) {
        successModal.classList.add('hidden');
        successModal.classList.remove('flex');
    }
    if (elements.productSearch) elements.productSearch.focus();
}

// Imprimir cupom
function printReceipt() {
    if (appState.lastOrderId) {
        window.open(`{{ url('admin/pos/receipt') }}/${appState.lastOrderId}`, '_blank');
    }
}

// Busca de clientes - Versão robusta com debug
async function searchCustomers() {
    const query = document.getElementById('customer-search-input').value.trim();
    const resultsContainer = document.getElementById('customer-search-results');
    
    if (!query) {
        resultsContainer.innerHTML = '';
        return;
    }
    
    // Mostrar loading
    resultsContainer.innerHTML = `
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-3"></div>
            <p class="text-gray-500 text-sm">Buscando clientes...</p>
        </div>
    `;
    
    try {
        const baseUrl = '{{ route('admin.pos.search-customers') }}';
        const searchUrl = `${baseUrl}?q=${encodeURIComponent(query)}`;
        
        const response = await fetch(searchUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
            },
            credentials: 'same-origin'
        });
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('❌ Erro na resposta de clientes:', errorText);
            throw new Error(`Erro ${response.status}: ${response.statusText}`);
        }
        
        const customers = await response.json();
        
        if (!Array.isArray(customers)) {
            throw new Error('Resposta inválida: esperado array de clientes');
        }
        
        if (customers.length === 0) {
            resultsContainer.innerHTML = `
                <div class="text-center py-8">
                    <div class="text-gray-400 mb-3">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Nenhum cliente encontrado</p>
                    <p class="text-gray-400 text-xs">Tente buscar com outros termos</p>
                </div>
            `;
            return;
        }
        
        resultsContainer.innerHTML = customers.map(customer => `
            <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 customer-result transition-colors" 
                 data-customer='${JSON.stringify(customer)}'>
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="font-medium text-gray-900">${customer.name}</div>
                        <div class="text-sm text-gray-500">${customer.document || 'Sem documento'}</div>
                        <div class="text-xs text-gray-400">${customer.email || 'Sem email'}</div>
                    </div>
                </div>
            </div>
        `).join('');
        
        // Event listeners para resultados
        resultsContainer.querySelectorAll('.customer-result').forEach(item => {
            item.addEventListener('click', () => {
                const customer = JSON.parse(item.dataset.customer);
                selectCustomerFromSearch(customer);
                closeCustomerSearchModal();
            });
        });
        
    } catch (error) {
        console.error('❌ Erro na busca de clientes:', error);
        
        resultsContainer.innerHTML = `
            <div class="text-center py-8">
                <div class="text-red-500 mb-3">
                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-sm font-medium text-red-600 mb-2">Erro na busca</h3>
                <p class="text-red-500 text-xs mb-3">${error.message}</p>
                <button onclick="searchCustomers()" class="px-3 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700 transition-colors">
                    🔄 Tentar novamente
                </button>
            </div>
        `;
    }
}

// Selecionar cliente
function selectCustomer() {
    const select = elements.customerSelect;
    const option = select.options[select.selectedIndex];
    
    if (option.value) {
        appState.selectedCustomer = JSON.parse(option.dataset.customer);
    } else {
        appState.selectedCustomer = null;
    }
}

function selectCustomerFromSearch(customer) {
    appState.selectedCustomer = customer;
    
    // Tentar encontrar o cliente no select principal
    const selectOption = Array.from(elements.customerSelect.options).find(option => 
        option.value == customer.id
    );
    
    if (selectOption) {
        elements.customerSelect.value = customer.id;
    } else {
        // Se não existe no select, criar uma nova opção
        const newOption = new Option(
            `${customer.name} (${customer.document || 'Sem documento'})`,
            customer.id
        );
        newOption.dataset.customer = JSON.stringify(customer);
        elements.customerSelect.appendChild(newOption);
        elements.customerSelect.value = customer.id;
    }
    
    // Feedback visual
    elements.customerSelect.style.borderColor = '#10b981';
    elements.customerSelect.style.backgroundColor = '#f0fdf4';
    
    setTimeout(() => {
        elements.customerSelect.style.borderColor = '';
        elements.customerSelect.style.backgroundColor = '';
    }, 2000);
}

// Modals de cliente
function openCustomerSearchModal() {
    const modal = document.getElementById('customer-search-modal');
    const input = document.getElementById('customer-search-input');
    
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    if (input) input.focus();
}

function closeCustomerSearchModal() {
    const modal = document.getElementById('customer-search-modal');
    const input = document.getElementById('customer-search-input');
    const results = document.getElementById('customer-search-results');
    
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    if (input) input.value = '';
    if (results) results.innerHTML = '';
}

// Funções de Pagamento Parcial
function togglePartialPayment() {
    if (!elements.partialPayment) return;
    
    const isPartial = elements.partialPayment.checked;
    
    if (isPartial) {
        if (elements.partialPaymentSection) elements.partialPaymentSection.classList.remove('hidden');
        if (elements.partialAmount) elements.partialAmount.focus();
        
        // Sugerir 50% como padrão
        const total = calculateFinalTotal();
        if (total > 0 && elements.partialAmount) {
            const halfAmount = total / 2;
            elements.partialAmount.value = halfAmount.toFixed(2);
            updatePartialPaymentInfo();
        }
    } else {
        if (elements.partialPaymentSection) elements.partialPaymentSection.classList.add('hidden');
        if (elements.remainingInfo) elements.remainingInfo.classList.add('hidden');
        if (elements.partialAmount) elements.partialAmount.value = '';
    }
}

function set50PercentPayment() {
    if (!elements.partialAmount) return;
    
    const total = calculateFinalTotal();
    if (total > 0) {
        const halfAmount = total / 2;
        elements.partialAmount.value = halfAmount.toFixed(2);
        updatePartialPaymentInfo();
    }
}

function updatePartialPaymentInfo() {
    if (!elements.partialAmount) return;
    
    const partialAmount = parseFloat(elements.partialAmount.value) || 0;
    const total = calculateFinalTotal();
    const remaining = Math.max(0, total - partialAmount);
    
    if (partialAmount > 0 && partialAmount <= total) {
        if (elements.remainingInfo) elements.remainingInfo.classList.remove('hidden');
        if (elements.downPaymentDisplay) elements.downPaymentDisplay.textContent = `R$ ${partialAmount.toFixed(2).replace('.', ',')}`;
        if (elements.remainingDisplay) elements.remainingDisplay.textContent = `R$ ${remaining.toFixed(2).replace('.', ',')}`;
        
        // Validação visual
        if (partialAmount > total) {
            elements.partialAmount.style.borderColor = '#ef4444';
            elements.partialAmount.style.backgroundColor = '#fef2f2';
        } else {
            elements.partialAmount.style.borderColor = '#10b981';
            elements.partialAmount.style.backgroundColor = '#f0fdf4';
        }
    } else {
        if (elements.remainingInfo) elements.remainingInfo.classList.add('hidden');
        elements.partialAmount.style.borderColor = '';
        elements.partialAmount.style.backgroundColor = '';
    }
}

function calculateFinalTotal() {
    const subtotal = appState.cart.reduce((sum, item) => sum + (item.quantity * parseFloat(item.unit_price)), 0);
    const discount = parseFloat(elements.discount.value) || 0;
    return Math.max(0, subtotal - discount);
}

function isPartialPayment() {
    return elements.partialPayment.checked && parseFloat(elements.partialAmount.value) > 0;
}

function getPartialPaymentData() {
    if (!isPartialPayment()) return null;
    
    const partialAmount = parseFloat(elements.partialAmount.value) || 0;
    const total = calculateFinalTotal();
    const remaining = Math.max(0, total - partialAmount);
    
    return {
        isPartial: true,
        paidAmount: partialAmount,
        remainingAmount: remaining,
        totalAmount: total
    };
}

// Utilitários
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}




</script>
@endpush
@endsection 