@extends('layouts.admin')

@section('title', 'Teste PDV - Busca de Clientes')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Teste de Busca de Clientes - PDV</h1>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-4">
            <label for="search-input" class="block text-sm font-medium text-gray-700 mb-2">Buscar Cliente:</label>
            <input 
                type="text" 
                id="search-input" 
                placeholder="Digite o nome, email ou documento do cliente..."
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        
        <button 
            id="search-btn" 
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Buscar
        </button>
        
        <div id="debug-info" class="mt-4 p-4 bg-gray-100 rounded text-sm font-mono"></div>
        
        <div id="results" class="mt-4"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchBtn = document.getElementById('search-btn');
    const debugInfo = document.getElementById('debug-info');
    const results = document.getElementById('results');
    
    function log(message) {
        console.log(message);
        debugInfo.innerHTML += message + '<br>';
    }
    
    async function testCustomerSearch() {
        const query = searchInput.value.trim();
        
        log('=== INICIANDO TESTE DE BUSCA DE CLIENTES ===');
        log('Query: ' + query);
        log('CSRF Token: {{ csrf_token() }}');
        log('URL: {{ route('admin.pos.search-customers') }}');
        
        if (!query) {
            log('ERROR: Query vazia');
            return;
        }
        
        try {
            const url = `{{ route('admin.pos.search-customers') }}?q=${encodeURIComponent(query)}`;
            log('URL completa: ' + url);
            
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            log('Status da resposta: ' + response.status);
            log('Status text: ' + response.statusText);
            log('Headers: ' + JSON.stringify([...response.headers.entries()]));
            
            if (!response.ok) {
                const errorText = await response.text();
                log('ERROR Response body: ' + errorText);
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const data = await response.json();
            log('SUCCESS: Dados recebidos');
            log('Clientes encontrados: ' + data.length);
            log('Dados: ' + JSON.stringify(data, null, 2));
            
            // Exibir resultados
            results.innerHTML = `
                <h3 class="text-lg font-semibold mb-3">Clientes Encontrados (${data.length}):</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    ${data.map(customer => `
                        <div class="border rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">${customer.name}</h4>
                                    <p class="text-xs text-gray-500">ID: ${customer.id}</p>
                                </div>
                            </div>
                            <div class="space-y-1 text-sm">
                                <p><span class="font-medium text-gray-600">Email:</span> ${customer.email || 'N/A'}</p>
                                <p><span class="font-medium text-gray-600">Documento:</span> ${customer.document || 'N/A'}</p>
                                <p><span class="font-medium text-gray-600">Tipo:</span> ${customer.type || 'N/A'}</p>
                                <p><span class="font-medium text-gray-600">Display:</span> ${customer.display_name || 'N/A'}</p>
                            </div>
                        </div>
                    `).join('')}
                </div>
            `;
            
        } catch (error) {
            log('ERROR: ' + error.message);
            log('ERROR Stack: ' + error.stack);
        }
    }
    
    searchBtn.addEventListener('click', testCustomerSearch);
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            testCustomerSearch();
        }
    });
    
    log('Script carregado com sucesso');
    log('User: {{ Auth::user()->name ?? 'Não autenticado' }}');
    log('Total de clientes no sistema: {{ App\\Models\\Customer::count() }}');
});
</script>
@endsection 