@extends('layouts.admin')

@section('title', 'Nova Compra')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Criar Nova Compra</h3>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <h5><i class="icon fas fa-info"></i> Funcionalidade em Desenvolvimento</h5>
                <p>A funcionalidade completa de criação de compras ainda está sendo implementada.</p>
                <p>Esta funcionalidade incluirá:</p>
                <ul>
                    <li>Seleção de fornecedor</li>
                    <li>Adição de múltiplos produtos</li>
                    <li>Cálculo automático de totais</li>
                    <li>Controle de status da compra</li>
                    <li>Integração com estoque</li>
                </ul>
            </div>

            <form action="{{ route('admin.purchases.store') }}" method="POST" id="purchaseForm">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="supplier_id" class="form-label">Fornecedor *</label>
                            <select name="supplier_id" id="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror" required>
                                <option value="">Selecione um fornecedor</option>
                                @foreach($suppliers as $id => $name)
                                    <option value="{{ $id }}" {{ old('supplier_id') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="purchase_number" class="form-label">Número da Compra *</label>
                            <input type="text" name="purchase_number" id="purchase_number" class="form-control @error('purchase_number') is-invalid @enderror" value="{{ old('purchase_number', $purchase_number) }}" required>
                            @error('purchase_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="delivery_date" class="form-label">Data de Entrega</label>
                            <input type="date" name="delivery_date" id="delivery_date" class="form-control @error('delivery_date') is-invalid @enderror" value="{{ old('delivery_date') }}">
                            @error('delivery_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="">Selecione um status</option>
                                <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pendente</option>
                                <option value="ordered" {{ old('status') == 'ordered' ? 'selected' : '' }}>Pedido Feito</option>
                                <option value="received" {{ old('status') == 'received' ? 'selected' : '' }}>Recebido</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="payment_method" class="form-label">Forma de Pagamento</label>
                            <select name="payment_method" id="payment_method" class="form-control @error('payment_method') is-invalid @enderror">
                                <option value="">Selecione</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Dinheiro</option>
                                <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Cartão de Crédito</option>
                                <option value="debit_card" {{ old('payment_method') == 'debit_card' ? 'selected' : '' }}>Cartão de Débito</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Transferência</option>
                                <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Cheque</option>
                                <option value="on_delivery" {{ old('payment_method') == 'on_delivery' ? 'selected' : '' }}>Na Entrega</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="payment_terms" class="form-label">Condições de Pagamento</label>
                    <input type="text" name="payment_terms" id="payment_terms" class="form-control @error('payment_terms') is-invalid @enderror" value="{{ old('payment_terms') }}" placeholder="Ex: 30 dias, À vista, etc.">
                    @error('payment_terms')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Items Section -->
                <div class="card bg-light mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Itens da Compra</h5>
                        <button type="button" class="btn btn-primary btn-sm" onclick="addItem()">
                            <i class="ti ti-plus"></i> Adicionar Item
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="items-container">
                            <!-- Items will be added here -->
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-6 offset-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Subtotal:</strong></td>
                                        <td class="text-end"><span id="subtotal-display">R$ 0,00</span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="discount">Desconto:</label>
                                            <input type="number" name="discount" id="discount" class="form-control form-control-sm" value="{{ old('discount', 0) }}" step="0.01" min="0" onchange="calculateTotals()">
                                        </td>
                                        <td class="text-end">R$ <span id="discount-display">0,00</span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="tax">Taxa/Impostos:</label>
                                            <input type="number" name="tax" id="tax" class="form-control form-control-sm" value="{{ old('tax', 0) }}" step="0.01" min="0" onchange="calculateTotals()">
                                        </td>
                                        <td class="text-end">R$ <span id="tax-display">0,00</span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="shipping">Frete:</label>
                                            <input type="number" name="shipping" id="shipping" class="form-control form-control-sm" value="{{ old('shipping', 0) }}" step="0.01" min="0" onchange="calculateTotals()">
                                        </td>
                                        <td class="text-end">R$ <span id="shipping-display">0,00</span></td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td><strong>Total:</strong></td>
                                        <td class="text-end"><strong><span id="total-display">R$ 0,00</span></strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="notes" class="form-label">Observações</label>
                    <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Observações sobre a compra...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.purchases.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy"></i> Salvar Compra
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Item Template (hidden) -->
    <template id="item-template">
        <div class="row mb-2 item-row">
            <div class="col-md-4">
                <select name="items[INDEX][product_id]" class="form-control product-select" required onchange="updateProductInfo(this)">
                    <option value="">Selecione um produto</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-sku="{{ $product->sku }}" data-cost="{{ $product->cost_price }}">
                            {{ $product->name }} ({{ $product->sku }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="items[INDEX][quantity]" class="form-control quantity-input" placeholder="Qtd" min="1" required onchange="calculateItemTotal(this)">
            </div>
            <div class="col-md-2">
                <input type="number" name="items[INDEX][unit_cost]" class="form-control cost-input" placeholder="Custo Unit." step="0.01" min="0" required onchange="calculateItemTotal(this)">
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control item-total" placeholder="Total" readonly>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">
                    <i class="ti ti-trash"></i>
                </button>
            </div>
        </div>
    </template>
@stop

@section('css')
@stop

@push('scripts')
<script>
    let itemIndex = 0;

    function addItem() {
        const template = document.getElementById('item-template').content.cloneNode(true);
        const container = document.getElementById('items-container');
        
        // Replace INDEX placeholder with actual index
        template.querySelectorAll('[name*="INDEX"]').forEach(element => {
            element.name = element.name.replace('INDEX', itemIndex);
        });
        
        container.appendChild(template);
        itemIndex++;
    }

    function removeItem(button) {
        button.closest('.item-row').remove();
        calculateTotals();
    }

    function updateProductInfo(select) {
        const option = select.selectedOptions[0];
        const row = select.closest('.item-row');
        const costInput = row.querySelector('.cost-input');
        
        if (option.dataset.cost) {
            costInput.value = parseFloat(option.dataset.cost).toFixed(2);
            calculateItemTotal(costInput);
        }
    }

    function calculateItemTotal(input) {
        const row = input.closest('.item-row');
        const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const cost = parseFloat(row.querySelector('.cost-input').value) || 0;
        const total = quantity * cost;
        
        row.querySelector('.item-total').value = formatCurrency(total);
        calculateTotals();
    }

    function calculateTotals() {
        let subtotal = 0;
        
        // Calculate subtotal from all items
        document.querySelectorAll('.item-row').forEach(row => {
            const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const cost = parseFloat(row.querySelector('.cost-input').value) || 0;
            subtotal += quantity * cost;
        });
        
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const tax = parseFloat(document.getElementById('tax').value) || 0;
        const shipping = parseFloat(document.getElementById('shipping').value) || 0;
        
        const total = subtotal - discount + tax + shipping;
        
        // Update displays
        document.getElementById('subtotal-display').textContent = formatCurrency(subtotal);
        document.getElementById('discount-display').textContent = formatCurrency(discount);
        document.getElementById('tax-display').textContent = formatCurrency(tax);
        document.getElementById('shipping-display').textContent = formatCurrency(shipping);
        document.getElementById('total-display').textContent = formatCurrency(total);
    }

    function formatCurrency(value) {
        return new Intl.NumberFormat('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(value);
    }

    // Form validation
    document.getElementById('purchaseForm').addEventListener('submit', function(e) {
        const itemsContainer = document.getElementById('items-container');
        if (itemsContainer.children.length === 0) {
            e.preventDefault();
            alert('Adicione pelo menos um item à compra.');
            return false;
        }
        
        // Validate all items have required fields
        let valid = true;
        document.querySelectorAll('.item-row').forEach(row => {
            const productSelect = row.querySelector('.product-select');
            const quantityInput = row.querySelector('.quantity-input');
            const costInput = row.querySelector('.cost-input');
            
            if (!productSelect.value || !quantityInput.value || !costInput.value) {
                valid = false;
            }
        });
        
        if (!valid) {
            e.preventDefault();
            alert('Preencha todos os campos dos itens.');
            return false;
        }
    });

    // Add first item on page load
    document.addEventListener('DOMContentLoaded', function() {
        addItem();
    });
</script>
@endpush 