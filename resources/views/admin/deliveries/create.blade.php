@extends('layouts.admin')

@section('title', 'Nova Entrega')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Nova Entrega</h1>
        <a href="{{ route('admin.deliveries.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
            <i class="ti ti-arrow-left mr-1"></i>
            Voltar
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.deliveries.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Seleção do Pedido -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="order_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pedido <span class="text-red-500">*</span>
                    </label>
                    <select name="order_id" id="order_id" required 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Selecione um pedido</option>
                        @foreach($orders as $order)
                            <option value="{{ $order->id }}" data-customer="{{ $order->customer->name ?? 'Cliente não informado' }}" 
                                    data-total="{{ $order->total_amount }}" data-address="{{ $order->customer->address ?? '' }}">
                                Pedido #{{ $order->order_number }} - {{ $order->customer->name ?? 'Cliente não informado' }} - R$ {{ number_format($order->total_amount, 2, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @error('order_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Cliente
                    </label>
                    <select name="customer_id" id="customer_id" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Selecione um cliente</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Data e Horário -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="scheduled_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Data da Entrega <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="scheduled_date" id="scheduled_date" required
                           min="{{ date('Y-m-d') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('scheduled_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="scheduled_time_start" class="block text-sm font-medium text-gray-700 mb-2">
                        Horário Início
                    </label>
                    <input type="time" name="scheduled_time_start" id="scheduled_time_start"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="scheduled_time_end" class="block text-sm font-medium text-gray-700 mb-2">
                        Horário Fim
                    </label>
                    <input type="time" name="scheduled_time_end" id="scheduled_time_end"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Endereço de Entrega -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-900">Endereço de Entrega</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="delivery_address" class="block text-sm font-medium text-gray-700 mb-2">
                            Endereço <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="delivery_address" id="delivery_address" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Rua, número, complemento">
                        @error('delivery_address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="delivery_city" class="block text-sm font-medium text-gray-700 mb-2">
                            Cidade <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="delivery_city" id="delivery_city" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('delivery_city')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="delivery_state" class="block text-sm font-medium text-gray-700 mb-2">
                            Estado <span class="text-red-500">*</span>
                        </label>
                        <select name="delivery_state" id="delivery_state" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecione</option>
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
                        @error('delivery_state')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="delivery_zip_code" class="block text-sm font-medium text-gray-700 mb-2">
                            CEP
                        </label>
                        <input type="text" name="delivery_zip_code" id="delivery_zip_code"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="00000-000">
                    </div>
                </div>
            </div>

            <!-- Informações Financeiras -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-900">Informações Financeiras</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Valor Total <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="total_amount" id="total_amount" step="0.01" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('total_amount')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="paid_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Valor Pago
                        </label>
                        <input type="number" name="paid_amount" id="paid_amount" step="0.01" min="0" value="0"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="remaining_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Valor Restante
                        </label>
                        <input type="number" name="remaining_amount" id="remaining_amount" step="0.01" min="0" readonly
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100">
                    </div>
                </div>
            </div>

            <!-- Informações do Motorista -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-900">Informações do Motorista (Opcional)</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="driver_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome do Motorista
                        </label>
                        <input type="text" name="driver_name" id="driver_name"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="driver_phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Telefone do Motorista
                        </label>
                        <input type="text" name="driver_phone" id="driver_phone"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="(00) 00000-0000">
                    </div>

                    <div>
                        <label for="vehicle_info" class="block text-sm font-medium text-gray-700 mb-2">
                            Informações do Veículo
                        </label>
                        <input type="text" name="vehicle_info" id="vehicle_info"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Modelo, cor, placa">
                    </div>
                </div>
            </div>

            <!-- Observações -->
            <div>
                <label for="delivery_notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Observações da Entrega
                </label>
                <textarea name="delivery_notes" id="delivery_notes" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Instruções especiais, ponto de referência, etc."></textarea>
            </div>

            <!-- Botões -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('admin.deliveries.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="ti ti-check mr-1"></i>
                    Criar Entrega
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const orderSelect = document.getElementById('order_id');
    const customerSelect = document.getElementById('customer_id');
    const totalAmountInput = document.getElementById('total_amount');
    const paidAmountInput = document.getElementById('paid_amount');
    const remainingAmountInput = document.getElementById('remaining_amount');
    const deliveryAddressInput = document.getElementById('delivery_address');

    // Atualizar informações quando pedido for selecionado
    orderSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            // Preencher valor total
            const total = selectedOption.dataset.total;
            if (total) {
                totalAmountInput.value = parseFloat(total).toFixed(2);
                calculateRemaining();
            }

            // Preencher endereço se disponível
            const address = selectedOption.dataset.address;
            if (address && address.trim()) {
                deliveryAddressInput.value = address;
            }
        } else {
            totalAmountInput.value = '';
            deliveryAddressInput.value = '';
            remainingAmountInput.value = '';
        }
    });

    // Calcular valor restante
    function calculateRemaining() {
        const total = parseFloat(totalAmountInput.value) || 0;
        const paid = parseFloat(paidAmountInput.value) || 0;
        const remaining = Math.max(0, total - paid);
        remainingAmountInput.value = remaining.toFixed(2);
    }

    // Atualizar valor restante quando valores mudarem
    totalAmountInput.addEventListener('input', calculateRemaining);
    paidAmountInput.addEventListener('input', calculateRemaining);

    // Máscara para CEP
    const zipCodeInput = document.getElementById('delivery_zip_code');
    zipCodeInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        if (value.length > 5) {
            value = value.substring(0, 5) + '-' + value.substring(5, 8);
        }
        this.value = value;
    });

    // Máscara para telefone
    const phoneInput = document.getElementById('driver_phone');
    phoneInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        if (value.length > 10) {
            value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        } else if (value.length > 6) {
            value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
        } else if (value.length > 2) {
            value = value.replace(/(\d{2})(\d{0,5})/, '($1) $2');
        }
        this.value = value;
    });
});
</script>
@endsection 