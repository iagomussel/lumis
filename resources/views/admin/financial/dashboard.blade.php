@extends('layouts.admin')

@section('title', 'Dashboard Financeiro')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Dashboard Financeiro</h1>
                    <p class="mt-2 text-gray-600">Visão geral da situação financeira da empresa</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.financial.receivables') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Contas a Receber
                    </a>
                    <a href="{{ route('admin.financial.payables') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                        Contas a Pagar
                    </a>
                </div>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Saldo Total -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Saldo Total</dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    R$ {{ number_format($totalBalance ?? 0, 2, ',', '.') }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Receitas do Mês -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Receitas do Mês</dt>
                                <dd class="text-lg font-medium text-green-600">
                                    R$ {{ number_format($monthlyIncome ?? 0, 2, ',', '.') }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Despesas do Mês -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Despesas do Mês</dt>
                                <dd class="text-lg font-medium text-red-600">
                                    R$ {{ number_format($monthlyExpenses ?? 0, 2, ',', '.') }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resultado do Mês -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 {{ ($monthlyIncome ?? 0) - ($monthlyExpenses ?? 0) >= 0 ? 'bg-green-500' : 'bg-red-500' }} rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Resultado do Mês</dt>
                                <dd class="text-lg font-medium {{ ($monthlyIncome ?? 0) - ($monthlyExpenses ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    R$ {{ number_format(($monthlyIncome ?? 0) - ($monthlyExpenses ?? 0), 2, ',', '.') }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contas a Receber e Pagar -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Contas a Receber -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Contas a Receber</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Pendente</span>
                            <span class="text-lg font-semibold text-green-600">
                                R$ {{ number_format($totalReceivables ?? 0, 2, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Vencidas</span>
                            <span class="text-lg font-semibold text-red-600">
                                {{ $overdueReceivables ?? 0 }} contas
                            </span>
                        </div>
                        <div class="pt-3">
                            <a href="{{ route('admin.financial.receivables') }}" 
                               class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                                Ver Todas
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contas a Pagar -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Contas a Pagar</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Pendente</span>
                            <span class="text-lg font-semibold text-red-600">
                                R$ {{ number_format($totalPayables ?? 0, 2, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Vencidas</span>
                            <span class="text-lg font-semibold text-red-600">
                                {{ $overduePayables ?? 0 }} contas
                            </span>
                        </div>
                        <div class="pt-3">
                            <a href="{{ route('admin.financial.payables') }}" 
                               class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                                Ver Todas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Últimas Transações -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Últimas Transações</h3>
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentTransactions ?? [] as $transaction)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction->reference_date ? \Carbon\Carbon::parse($transaction->reference_date)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction->description ?? 'Sem descrição' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $transaction->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $transaction->type === 'income' ? 'Receita' : 'Despesa' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium 
                                    {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->type === 'income' ? '+' : '-' }}R$ {{ number_format($transaction->amount ?? 0, 2, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    Nenhuma transação encontrada
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Comparativo Mensal -->
        <div class="mt-8 bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Comparativo Mensal</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Mês Atual</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Receitas:</span>
                                <span class="text-sm font-medium text-green-600">
                                    R$ {{ number_format($monthlyIncome ?? 0, 2, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Despesas:</span>
                                <span class="text-sm font-medium text-red-600">
                                    R$ {{ number_format($monthlyExpenses ?? 0, 2, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between border-t pt-2">
                                <span class="text-sm font-medium text-gray-700">Resultado:</span>
                                <span class="text-sm font-bold {{ ($monthlyIncome ?? 0) - ($monthlyExpenses ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    R$ {{ number_format(($monthlyIncome ?? 0) - ($monthlyExpenses ?? 0), 2, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Mês Anterior</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Receitas:</span>
                                <span class="text-sm font-medium text-green-600">
                                    R$ {{ number_format($previousMonthIncome ?? 0, 2, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Despesas:</span>
                                <span class="text-sm font-medium text-red-600">
                                    R$ {{ number_format($previousMonthExpenses ?? 0, 2, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between border-t pt-2">
                                <span class="text-sm font-medium text-gray-700">Resultado:</span>
                                <span class="text-sm font-bold {{ ($previousMonthIncome ?? 0) - ($previousMonthExpenses ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    R$ {{ number_format(($previousMonthIncome ?? 0) - ($previousMonthExpenses ?? 0), 2, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 