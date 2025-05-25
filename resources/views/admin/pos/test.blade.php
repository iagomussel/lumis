@extends('layouts.admin')

@section('title', 'Teste PDV - Busca de Produtos')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Teste de Busca de Produtos</h1>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-4">
            <label for="search-input" class="block text-sm font-medium text-gray-700 mb-2">Buscar Produto:</label>
            <input 
                type="text" 
                id="search-input" 
                placeholder="Digite o nome do produto..."
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
    
    async function testSearch() {
        const query = searchInput.value.trim();
        
        log('=== INICIANDO TESTE DE BUSCA ===');
        log('Query: ' + query);
        log('CSRF Token: {{ csrf_token() }}');
        log('URL: {{ route('admin.pos.search-products') }}');
        
        if (!query) {
            log('ERROR: Query vazia');
            return;
        }
        
        try {
            const url = `{{ route('admin.pos.search-products') }}?q=${encodeURIComponent(query)}`;
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
            log('Produtos encontrados: ' + data.length);
            log('Dados: ' + JSON.stringify(data, null, 2));
            
            // Exibir resultados
            results.innerHTML = `
                <h3 class="text-lg font-semibold mb-3">Produtos Encontrados (${data.length}):</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    ${data.map(product => `
                        <div class="border rounded-lg p-4">
                            <h4 class="font-medium">${product.name}</h4>
                            <p class="text-sm text-gray-600">SKU: ${product.sku}</p>
                            <p class="text-sm text-gray-600">Preço: ${product.formatted_price}</p>
                            <p class="text-sm text-gray-600">Estoque: ${product.stock_quantity}</p>
                            <p class="text-sm text-gray-600">Categoria: ${product.category || 'N/A'}</p>
                        </div>
                    `).join('')}
                </div>
            `;
            
        } catch (error) {
            log('ERROR: ' + error.message);
            log('ERROR Stack: ' + error.stack);
        }
    }
    
    searchBtn.addEventListener('click', testSearch);
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            testSearch();
        }
    });
    
    log('Script carregado com sucesso');
    log('User: {{ Auth::user()->name ?? 'Não autenticado' }}');
});
</script>
@endsection 