@extends('layouts.admin')

@section('title', 'Detalhes do Produto')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Detalhes do Produto</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Produtos</a></li>
                    <li class="breadcrumb-item active">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informações Básicas -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informações do Produto</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nome:</label>
                                <p class="mb-0">{{ $product->name }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">SKU:</label>
                                <p class="mb-0">{{ $product->sku }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Categoria:</label>
                                <p class="mb-0">{{ $product->category->name ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status:</label>
                                <span class="badge badge-{{ $product->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ $product->status === 'active' ? 'Ativo' : 'Inativo' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Preço de Venda:</label>
                                <p class="mb-0 text-success fw-bold">R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                            </div>
                            @if($product->promotional_price)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Preço Promocional:</label>
                                <p class="mb-0 text-danger fw-bold">R$ {{ number_format($product->promotional_price, 2, ',', '.') }}</p>
                            </div>
                            @endif
                            <div class="mb-3">
                                <label class="form-label fw-bold">Preço de Custo:</label>
                                <p class="mb-0">R$ {{ number_format($product->cost_price ?? 0, 2, ',', '.') }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Estoque:</label>
                                <p class="mb-0">
                                    <span class="badge badge-{{ $product->stock_quantity > 0 ? 'success' : 'danger' }}">
                                        {{ $product->stock_quantity }} unidades
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    @if($product->description)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Descrição:</label>
                        <div class="border p-3 rounded bg-light">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                    @endif

                    @if($product->short_description)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Descrição Curta:</label>
                        <p class="mb-0">{{ $product->short_description }}</p>
                    </div>
                    @endif

                    <div class="row">
                        @if($product->weight)
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Peso:</label>
                                <p class="mb-0">{{ $product->weight }}g</p>
                            </div>
                        </div>
                        @endif
                        @if($product->dimensions)
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Dimensões:</label>
                                <p class="mb-0">{{ $product->dimensions }}</p>
                            </div>
                        </div>
                        @endif
                        @if($product->min_stock)
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Estoque Mínimo:</label>
                                <p class="mb-0">{{ $product->min_stock }} unidades</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Variações do Produto -->
            @if($product->variants->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Variações do Produto</h6>
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addVariantModal">
                        <i class="fas fa-plus"></i> Nova Variação
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>SKU</th>
                                    <th>Opções</th>
                                    <th>Ajuste de Preço</th>
                                    <th>Estoque</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->variants as $variant)
                                <tr>
                                    <td>{{ $variant->name }}</td>
                                    <td>{{ $variant->sku }}</td>
                                    <td>
                                        @if($variant->option_values)
                                            @foreach($variant->option_values as $option => $value)
                                                <span class="badge badge-info">{{ ucfirst($option) }}: {{ $value }}</span>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if($variant->price_adjustment > 0)
                                            <span class="text-success">+R$ {{ number_format($variant->price_adjustment, 2, ',', '.') }}</span>
                                        @elseif($variant->price_adjustment < 0)
                                            <span class="text-danger">-R$ {{ number_format(abs($variant->price_adjustment), 2, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">R$ 0,00</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $variant->stock_quantity > 0 ? 'success' : 'danger' }}">
                                            {{ $variant->stock_quantity }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $variant->active ? 'success' : 'secondary' }}">
                                            {{ $variant->active ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                onclick="editVariant({{ $variant->id }})" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="deleteVariant({{ $variant->id }})" title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Variações do Produto</h6>
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addVariantModal">
                        <i class="fas fa-plus"></i> Adicionar Variação
                    </button>
                </div>
                <div class="card-body text-center">
                    <p class="text-muted">Este produto não possui variações.</p>
                    <p class="text-muted">Clique no botão acima para adicionar variações como tamanho, cor, etc.</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Estatísticas -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Estatísticas</h6>
                </div>
                <div class="card-body">
                    <div class="row no-gutters align-items-center mb-3">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Vendido
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSold }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Receita Total
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                R$ {{ number_format($totalRevenue, 2, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configurações E-commerce -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">E-commerce</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Venda Online:</label>
                        <span class="badge badge-{{ $product->online_sale ? 'success' : 'secondary' }}">
                            {{ $product->online_sale ? 'Habilitado' : 'Desabilitado' }}
                        </span>
                    </div>
                    @if($product->online_sale)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Link do Produto:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" 
                                   value="{{ route('ecommerce.product-detail', $product->slug) }}" 
                                   readonly>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" 
                                        onclick="copyToClipboard('{{ route('ecommerce.product-detail', $product->slug) }}')">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label fw-bold">Destaque:</label>
                        <span class="badge badge-{{ $product->featured ? 'warning' : 'secondary' }}">
                            {{ $product->featured ? 'Sim' : 'Não' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Datas -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informações de Data</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Criado em:</label>
                        <p class="mb-0">{{ $product->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Última atualização:</label>
                        <p class="mb-0">{{ $product->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Adicionar Variação -->
<div class="modal fade" id="addVariantModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar Nova Variação</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="addVariantForm">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="variant_name">Nome da Variação</label>
                                <input type="text" class="form-control" id="variant_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="variant_sku">SKU</label>
                                <input type="text" class="form-control" id="variant_sku" name="sku" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price_adjustment">Ajuste de Preço (R$)</label>
                                <input type="number" class="form-control" id="price_adjustment" 
                                       name="price_adjustment" step="0.01" value="0">
                                <small class="form-text text-muted">Valor positivo aumenta o preço, negativo diminui</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stock_quantity">Estoque</label>
                                <input type="number" class="form-control" id="stock_quantity" 
                                       name="stock_quantity" min="0" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Opções da Variação</label>
                        <div id="variantOptions">
                            <div class="row variant-option-row">
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="option_names[]" 
                                           placeholder="Nome da opção (ex: Cor, Tamanho)">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="option_values[]" 
                                           placeholder="Valor da opção (ex: Azul, M)">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-success btn-sm" onclick="addVariantOption()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="variant_active" 
                                   name="active" value="1" checked>
                            <label class="form-check-label" for="variant_active">
                                Variação ativa
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Variação</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Mostrar feedback visual
        const button = event.target.closest('button');
        const originalIcon = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check text-success"></i>';
        setTimeout(() => {
            button.innerHTML = originalIcon;
        }, 2000);
    });
}

function addVariantOption() {
    const container = document.getElementById('variantOptions');
    const newRow = document.createElement('div');
    newRow.className = 'row variant-option-row mt-2';
    newRow.innerHTML = `
        <div class="col-md-5">
            <input type="text" class="form-control" name="option_names[]" 
                   placeholder="Nome da opção (ex: Cor, Tamanho)">
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="option_values[]" 
                   placeholder="Valor da opção (ex: Azul, M)">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger btn-sm" onclick="removeVariantOption(this)">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    `;
    container.appendChild(newRow);
}

function removeVariantOption(button) {
    button.closest('.variant-option-row').remove();
}

// Submissão do formulário de variação
document.getElementById('addVariantForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("admin.product-variants.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erro ao criar variação: ' + (data.message || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erro ao criar variação');
    });
});

function editVariant(variantId) {
    // Implementar edição de variação
    alert('Funcionalidade de edição em desenvolvimento');
}

function deleteVariant(variantId) {
    if (confirm('Tem certeza que deseja excluir esta variação?')) {
        fetch(`/admin/product-variants/${variantId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao excluir variação: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao excluir variação');
        });
    }
}
</script>
@endpush 