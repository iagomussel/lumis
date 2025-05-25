// Script de debug para PDV
console.log('🔍 INICIANDO DEBUG DO PDV');

// 1. Verificar se elementos existem
const elements = {
    productSearch: document.getElementById('product-search'),
    productsGrid: document.getElementById('products-grid'),
    emptyState: document.getElementById('empty-state')
};

console.log('📋 ELEMENTOS DOM:');
Object.entries(elements).forEach(([name, element]) => {
    if (element) {
        console.log(`✅ ${name}:`, element);
        console.log(`   - Classes:`, element.className);
        console.log(`   - Visível:`, !element.classList.contains('hidden'));
        console.log(`   - Display:`, window.getComputedStyle(element).display);
        console.log(`   - Conteúdo:`, element.innerHTML.substring(0, 100) + '...');
    } else {
        console.log(`❌ ${name}: NÃO ENCONTRADO`);
    }
});

// 2. Verificar valor do campo de busca
console.log('🔍 CAMPO DE BUSCA:');
console.log('   - Valor:', elements.productSearch?.value);

// 3. Testar busca manualmente
async function testSearch() {
    console.log('🧪 TESTANDO BUSCA MANUAL...');
    
    try {
        const response = await fetch('/admin/pos/search-products?q=caneca', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        });
        
        console.log('📡 Resposta da API:');
        console.log('   - Status:', response.status);
        console.log('   - OK:', response.ok);
        
        const data = await response.json();
        console.log('   - Dados:', data);
        console.log('   - Quantidade:', data.length);
        
        // Tentar renderizar manualmente
        if (elements.productsGrid && data.length > 0) {
            console.log('🎨 RENDERIZANDO PRODUTOS MANUALMENTE...');
            
            elements.emptyState.classList.add('hidden');
            elements.productsGrid.classList.remove('hidden');
            
            elements.productsGrid.innerHTML = data.map(product => `
                <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow cursor-pointer product-card" 
                     data-product-id="${product.id}">
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-3 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="ti ti-package text-blue-600 text-2xl"></i>
                        </div>
                        <h3 class="font-medium text-gray-900 text-sm mb-1">${product.name}</h3>
                        <p class="text-xs text-gray-500 mb-2">${product.sku}</p>
                        <p class="font-semibold text-green-600 mb-2">${product.formatted_price}</p>
                        <p class="text-xs text-gray-400">Estoque: ${product.stock_quantity}</p>
                    </div>
                </div>
            `).join('');
            
            console.log('✅ Produtos renderizados com sucesso!');
        }
        
    } catch (error) {
        console.error('❌ Erro na busca:', error);
    }
}

// 4. Executar teste
testSearch();

console.log('🔍 DEBUG CONCLUÍDO - Verifique os logs acima'); 