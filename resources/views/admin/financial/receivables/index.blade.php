@extends('layouts.admin')

@section('title', 'Contas a Receber')

@section('content')
<div class="container mx-auto py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Contas a Receber</h1>
            <p class="text-gray-600 mt-1">Gestão de recebimentos e valores pendentes</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="exportData()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="ti ti-download mr-2"></i>Exportar
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total Geral</p>
                    <p class="text-2xl font-bold">R$ {{ number_format($summary['total'], 2, ',', '.') }}</p>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                    <i class="ti ti-wallet text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-amber-500 to-amber-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-amber-100 text-sm">Pendente</p>
                    <p class="text-2xl font-bold">R$ {{ number_format($summary['pending'], 2, ',', '.') }}</p>
                </div>
                <div class="bg-amber-400 bg-opacity-30 rounded-full p-3">
                    <i class="ti ti-clock text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm">Em Atraso</p>
                    <p class="text-2xl font-bold">R$ {{ number_format($summary['overdue'], 2, ',', '.') }}</p>
                </div>
                <div class="bg-red-400 bg-opacity-30 rounded-full p-3">
                    <i class="ti ti-alert-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Recebido</p>
                    <p class="text-2xl font-bold">R$ {{ number_format($summary['paid'], 2, ',', '.') }}</p>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                    <i class="ti ti-check-circle text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input 
                    type="text" 
                    id="search" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Cliente, descrição..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendente</option>
                    <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Parcial</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Pago</option>
                    <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Em Atraso</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>

            <div>
                <label for="due_date_from" class="block text-sm font-medium text-gray-700 mb-2">Venc. de</label>
                <input 
                    type="date" 
                    id="due_date_from" 
                    name="due_date_from" 
                    value="{{ request('due_date_from') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="due_date_to" class="block text-sm font-medium text-gray-700 mb-2">Venc. até</label>
                <input 
                    type="date" 
                    id="due_date_to" 
                    name="due_date_to" 
                    value="{{ request('due_date_to') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="ti ti-search mr-2"></i>Filtrar
                </button>
                <a href="{{ route('admin.financial.receivables') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="ti ti-x mr-2"></i>Limpar
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Lista de Recebimentos</h3>
            <p class="text-sm text-gray-500 mt-1">{{ $receivables->total() }} registro(s) encontrado(s)</p>
        </div>

        @if($receivables->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vencimento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pago</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($receivables as $receivable)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center mr-3">
                                            <i class="ti ti-user text-white"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $receivable->customer->name }}</div>
                                            @if($receivable->customer->document)
                                                <div class="text-sm text-gray-500">{{ $receivable->customer->document }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $receivable->description }}</div>
                                    @if($receivable->order)
                                        <div class="text-sm text-gray-500">Pedido: {{ $receivable->order->order_number }}</div>
                                    @endif
                                    @if($receivable->invoice_number)
                                        <div class="text-sm text-gray-500">NF: {{ $receivable->invoice_number }}</div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $receivable->due_date->format('d/m/Y') }}</div>
                                    @if($receivable->days_overdue > 0)
                                        <div class="text-sm text-red-500">{{ $receivable->days_overdue }} dias em atraso</div>
                                    @elseif($receivable->due_date->isToday())
                                        <div class="text-sm text-amber-500">Vence hoje</div>
                                    @elseif($receivable->due_date->isTomorrow())
                                        <div class="text-sm text-amber-500">Vence amanhã</div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $receivable->formatted_amount }}</div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">R$ {{ number_format($receivable->paid_amount, 2, ',', '.') }}</div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $receivable->formatted_remaining_amount }}</div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($receivable->status)
                                        @case('pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                <i class="ti ti-clock mr-1"></i>Pendente
                                            </span>
                                            @break
                                        @case('partial')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="ti ti-progress mr-1"></i>Parcial
                                            </span>
                                            @break
                                        @case('paid')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="ti ti-check mr-1"></i>Pago
                                            </span>
                                            @break
                                        @case('overdue')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="ti ti-alert-circle mr-1"></i>Atrasado
                                            </span>
                                            @break
                                        @case('cancelled')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="ti ti-x mr-1"></i>Cancelado
                                            </span>
                                            @break
                                    @endswitch
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        @if($receivable->status !== 'paid' && $receivable->status !== 'cancelled')
                                            <button 
                                                onclick="openPaymentModal({{ $receivable->id }}, '{{ $receivable->customer->name }}', {{ $receivable->remaining_amount }}, '{{ $receivable->description }}')"
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition-colors">
                                                <i class="ti ti-cash mr-1"></i>Receber
                                            </button>
                                        @endif
                                        
                                        <button 
                                            onclick="viewDetails({{ $receivable->id }})"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition-colors">
                                            <i class="ti ti-eye mr-1"></i>Ver
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $receivables->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="ti ti-wallet text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum recebimento encontrado</h3>
                <p class="text-gray-500">Não há contas a receber que correspondam aos filtros aplicados.</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal de Recebimento -->
<div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Registrar Recebimento</h3>
                <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="ti ti-x text-xl"></i>
                </button>
            </div>
            
            <form id="paymentForm" method="POST">
                @csrf
                <input type="hidden" id="receivableId" name="receivable_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cliente</label>
                    <p id="customerName" class="text-gray-900 font-medium"></p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                    <p id="receivableDescription" class="text-gray-600 text-sm"></p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Saldo Restante</label>
                    <p id="remainingAmount" class="text-lg font-bold text-blue-600"></p>
                </div>
                
                <div class="mb-4">
                    <label for="account_id" class="block text-sm font-medium text-gray-700 mb-2">Conta de Destino *</label>
                    <select id="account_id" name="account_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Selecione a conta</option>
                        @foreach(\App\Models\Account::active()->get() as $account)
                            <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->formatted_current_balance }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Valor Recebido *</label>
                    <input 
                        type="number" 
                        id="amount" 
                        name="amount" 
                        step="0.01" 
                        min="0.01" 
                        required
                        placeholder="0,00"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Forma de Pagamento *</label>
                    <select id="payment_method" name="payment_method" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Selecione</option>
                        <option value="money">Dinheiro</option>
                        <option value="credit_card">Cartão de Crédito</option>
                        <option value="debit_card">Cartão de Débito</option>
                        <option value="pix">PIX</option>
                        <option value="bank_transfer">Transferência Bancária</option>
                        <option value="bank_slip">Boleto</option>
                        <option value="check">Cheque</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Observações</label>
                    <textarea 
                        id="notes" 
                        name="notes" 
                        rows="3"
                        placeholder="Observações sobre o recebimento..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closePaymentModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="ti ti-check mr-2"></i>Confirmar Recebimento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openPaymentModal(receivableId, customerName, remainingAmount, description) {
    document.getElementById('receivableId').value = receivableId;
    document.getElementById('customerName').textContent = customerName;
    document.getElementById('receivableDescription').textContent = description;
    document.getElementById('remainingAmount').textContent = 'R$ ' + Number(remainingAmount).toFixed(2).replace('.', ',');
    document.getElementById('amount').setAttribute('max', remainingAmount);
    
    // Atualizar action do form
    document.getElementById('paymentForm').action = `/admin/financial/receivables/${receivableId}/pay`;
    
    document.getElementById('paymentModal').classList.remove('hidden');
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.add('hidden');
    // Limpar form
    document.getElementById('paymentForm').reset();
}

function viewDetails(receivableId) {
    // Implementar visualização de detalhes
    alert('Funcionalidade de detalhes será implementada');
}

function exportData() {
    // Implementar exportação
    alert('Funcionalidade de exportação será implementada');
}

// Fechar modal ao clicar fora
document.getElementById('paymentModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePaymentModal();
    }
});
</script>
@endpush

@endsection 