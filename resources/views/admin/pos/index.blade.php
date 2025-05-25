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
                <div id="products-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
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
                        <option value="cash">Dinheiro</option>
                        <option value="card">Cartão</option>
                        <option value="pix">PIX</option>
                        <option value="bank_transfer">Transferência</option>
                    </select>
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

// Elementos DOM
const elements = {
    productSearch: document.getElementById('product-search'),
    productsGrid: document.getElementById('products-grid'),
    emptyState: document.getElementById('empty-state'),
    cartItems: document.getElementById('cart-items'),
    emptyCart: document.getElementById('empty-cart'),
    cartItemsCount: document.getElementById('cart-items-count'),
    subtotal: document.getElementById('subtotal'),
    discountAmount: document.getElementById('discount-amount'),
    total: document.getElementById('total'),
    discount: document.getElementById('discount'),
    paymentMethod: document.getElementById('payment-method'),
    notes: document.getElementById('notes'),
    customerSelect: document.getElementById('customer-select'),
    processSale: document.getElementById('process-sale'),
    clearCart: document.getElementById('clear-cart')
};

// Inicialização
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
    updateCartDisplay();
});

// Event Listeners
function initializeEventListeners() {
    // Busca de produtos
    elements.productSearch.addEventListener('input', debounce(searchProducts, 300));
    
    // Carrinho
    elements.clearCart.addEventListener('click', clearCart);
    elements.processSale.addEventListener('click', processSale);
    elements.discount.addEventListener('input', updateTotals);
    
    // Cliente
    elements.customerSelect.addEventListener('change', selectCustomer);
    document.getElementById('search-customer').addEventListener('click', openCustomerSearchModal);
    
    // Modals
    document.getElementById('close-customer-modal').addEventListener('click', closeCustomerSearchModal);
    document.getElementById('customer-search-input').addEventListener('input', debounce(searchCustomers, 300));
    document.getElementById('new-sale').addEventListener('click', startNewSale);
    document.getElementById('print-receipt').addEventListener('click', printReceipt);
}

// Busca de produtos
async function searchProducts() {
    const query = elements.productSearch.value.trim();
    
    if (!query) {
        showEmptyState();
        return;
    }

    try {
        const response = await fetch(`{{ route('admin.pos.search-products') }}?q=${encodeURIComponent(query)}`);
        const products = await response.json();
        
        appState.products = products;
        displayProducts(products);
    } catch (error) {
        console.error('Erro ao buscar produtos:', error);
        showEmptyState();
    }
}

// Exibir produtos
function displayProducts(products) {
    const grid = elements.productsGrid;
    const emptyState = elements.emptyState;
    
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
    
    grid.innerHTML = products.map(product => `
        <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow cursor-pointer product-card" 
             data-product-id="${product.id}">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-3 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="ti ti-package text-blue-600 text-2xl"></i>
                </div>
                <h3 class="font-medium text-gray-900 text-sm mb-1 line-clamp-2">${product.name}</h3>
                <p class="text-xs text-gray-500 mb-2">${product.sku}</p>
                <p class="font-semibold text-green-600 mb-2">${product.formatted_price}</p>
                <p class="text-xs text-gray-400">Estoque: ${product.stock_quantity}</p>
                ${product.category ? `<span class="inline-block mt-2 px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">${product.category}</span>` : ''}
            </div>
        </div>
    `).join('');

    // Adicionar event listeners aos produtos
    grid.querySelectorAll('.product-card').forEach(card => {
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
    const existingItem = appState.cart.find(item => item.product_id === product.id);
    
    if (existingItem) {
        if (existingItem.quantity < product.stock_quantity) {
            existingItem.quantity++;
        } else {
            alert('Quantidade máxima em estoque atingida!');
            return;
        }
    } else {
        appState.cart.push({
            product_id: product.id,
            name: product.name,
            sku: product.sku,
            unit_price: product.price,
            quantity: 1,
            stock_quantity: product.stock_quantity
        });
    }
    
    updateCartDisplay();
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
    const cartContainer = elements.cartItems;
    const emptyCart = elements.emptyCart;
    const itemsCount = elements.cartItemsCount;
    
    if (appState.cart.length === 0) {
        cartContainer.classList.add('hidden');
        emptyCart.classList.remove('hidden');
        itemsCount.textContent = '0';
        elements.processSale.disabled = true;
        updateTotals();
        return;
    }
    
    emptyCart.classList.add('hidden');
    cartContainer.classList.remove('hidden');
    elements.processSale.disabled = false;
    
    itemsCount.textContent = appState.cart.reduce((sum, item) => sum + item.quantity, 0);
    
    cartContainer.innerHTML = appState.cart.map(item => `
        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
            <div class="flex justify-between items-start mb-2">
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900 text-sm">${item.name}</h4>
                    <p class="text-xs text-gray-500">${item.sku}</p>
                    <p class="text-sm text-green-600 font-medium">R$ ${item.unit_price.toFixed(2).replace('.', ',')}</p>
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
                    R$ ${(item.quantity * item.unit_price).toFixed(2).replace('.', ',')}
                </div>
            </div>
        </div>
    `).join('');
    
    updateTotals();
}

// Atualizar totais
function updateTotals() {
    const subtotal = appState.cart.reduce((sum, item) => sum + (item.quantity * item.unit_price), 0);
    const discount = parseFloat(elements.discount.value) || 0;
    const total = Math.max(0, subtotal - discount);
    
    elements.subtotal.textContent = `R$ ${subtotal.toFixed(2).replace('.', ',')}`;
    elements.discountAmount.textContent = `R$ ${discount.toFixed(2).replace('.', ',')}`;
    elements.total.textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
}

// Processar venda
async function processSale() {
    if (appState.cart.length === 0) {
        alert('Adicione produtos ao carrinho!');
        return;
    }
    
    const data = {
        customer_id: appState.selectedCustomer?.id || null,
        items: appState.cart,
        payment_method: elements.paymentMethod.value,
        discount: parseFloat(elements.discount.value) || 0,
        notes: elements.notes.value.trim()
    };
    
    elements.processSale.disabled = true;
    elements.processSale.innerHTML = '<i class="ti ti-loader animate-spin mr-1"></i> Processando...';
    
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
        elements.processSale.disabled = false;
        elements.processSale.innerHTML = '<i class="ti ti-check mr-1"></i> Finalizar Venda';
    }
}

// Limpar carrinho
function clearCart() {
    appState.cart = [];
    appState.selectedCustomer = null;
    elements.customerSelect.value = '';
    elements.discount.value = '';
    elements.notes.value = '';
    updateCartDisplay();
}

// Modal de sucesso
function showSuccessModal(result) {
    document.getElementById('success-message').textContent = 
        `Pedido ${result.order.order_number} - Total: ${result.order.formatted_total}`;
    document.getElementById('success-modal').classList.remove('hidden');
    document.getElementById('success-modal').classList.add('flex');
}

// Nova venda
function startNewSale() {
    document.getElementById('success-modal').classList.add('hidden');
    document.getElementById('success-modal').classList.remove('flex');
    elements.productSearch.focus();
}

// Imprimir cupom
function printReceipt() {
    if (appState.lastOrderId) {
        window.open(`{{ url('admin/pos/receipt') }}/${appState.lastOrderId}`, '_blank');
    }
}

// Busca de clientes
async function searchCustomers() {
    const query = document.getElementById('customer-search-input').value.trim();
    const resultsContainer = document.getElementById('customer-search-results');
    
    if (!query) {
        resultsContainer.innerHTML = '';
        return;
    }
    
    try {
        const response = await fetch(`{{ route('admin.pos.search-customers') }}?q=${encodeURIComponent(query)}`);
        const customers = await response.json();
        
        resultsContainer.innerHTML = customers.map(customer => `
            <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 customer-result" 
                 data-customer='${JSON.stringify(customer)}'>
                <div class="font-medium text-gray-900">${customer.name}</div>
                <div class="text-sm text-gray-500">${customer.document}</div>
                <div class="text-xs text-gray-400">${customer.email}</div>
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
        console.error('Erro ao buscar clientes:', error);
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
    elements.customerSelect.value = customer.id;
}

// Modals de cliente
function openCustomerSearchModal() {
    document.getElementById('customer-search-modal').classList.remove('hidden');
    document.getElementById('customer-search-modal').classList.add('flex');
    document.getElementById('customer-search-input').focus();
}

function closeCustomerSearchModal() {
    document.getElementById('customer-search-modal').classList.add('hidden');
    document.getElementById('customer-search-modal').classList.remove('flex');
    document.getElementById('customer-search-input').value = '';
    document.getElementById('customer-search-results').innerHTML = '';
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

// Limpar busca
document.getElementById('clear-search').addEventListener('click', () => {
    elements.productSearch.value = '';
    showEmptyState();
});
</script>
@endpush
@endsection 