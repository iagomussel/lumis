@extends('layouts.admin')

@section('title', 'Teste de Busca de Produtos')

@section('content')
<div class="container">
    <h1>Teste de Busca de Produtos</h1>
    
    <div class="card">
        <div class="card-body">
            <h5>Informações de Debug</h5>
            <ul>
                <li><strong>Usuário logado:</strong> {{ Auth::user()->name ?? 'Não logado' }}</li>
                <li><strong>CSRF Token:</strong> {{ csrf_token() }}</li>
                <li><strong>URL da busca:</strong> {{ route('admin.pos.search-products') }}</li>
            </ul>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <h5>Teste Manual</h5>
            <div class="form-group">
                <label>Buscar produtos:</label>
                <input type="text" id="search-input" class="form-control" placeholder="Digite para buscar...">
            </div>
            <button id="test-btn" class="btn btn-primary">Testar Busca</button>
            <div id="results" class="mt-3"></div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <h5>Produtos no Banco (Teste Direto)</h5>
            <div class="row">
                @php
                    $products = \App\Models\Product::active()->with('category')->limit(10)->get();
                @endphp
                
                <div class="col-12">
                    <p><strong>Total de produtos ativos:</strong> {{ $products->count() }}</p>
                    
                    @if($products->count() > 0)
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>SKU</th>
                                    <th>Preço</th>
                                    <th>Estoque</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->sku }}</td>
                                    <td>R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                                    <td>{{ $product->stock_quantity }}</td>
                                    <td>{{ $product->status }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-warning">Nenhum produto ativo encontrado!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('test-btn').addEventListener('click', async function() {
    const query = document.getElementById('search-input').value;
    const resultsDiv = document.getElementById('results');
    
    resultsDiv.innerHTML = '<div class="alert alert-info">Buscando...</div>';
    
    try {
        const response = await fetch('{{ route('admin.pos.search-products') }}?q=' + encodeURIComponent(query), {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            credentials: 'same-origin'
        });
        
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`HTTP ${response.status}: ${errorText}`);
        }
        
        const data = await response.json();
        console.log('Response data:', data);
        
        if (Array.isArray(data) && data.length > 0) {
            let html = '<div class="alert alert-success">Encontrados ' + data.length + ' produtos:</div>';
            html += '<div class="row">';
            
            data.forEach(product => {
                html += `
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h6>${product.name}</h6>
                                <p class="text-muted small">SKU: ${product.sku}</p>
                                <p class="text-success">${product.formatted_price}</p>
                                <p class="text-info">Estoque: ${product.stock_quantity}</p>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            resultsDiv.innerHTML = html;
        } else {
            resultsDiv.innerHTML = '<div class="alert alert-warning">Nenhum produto encontrado para: "' + query + '"</div>';
        }
        
    } catch (error) {
        console.error('Erro:', error);
        resultsDiv.innerHTML = '<div class="alert alert-danger">Erro: ' + error.message + '</div>';
    }
});

// Teste automático ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('search-input').value = 'test';
    document.getElementById('test-btn').click();
});
</script>
@endsection 