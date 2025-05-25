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

            <form action="{{ route('admin.purchases.store') }}" method="POST">
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
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="delivery_date" class="form-label">Data de Entrega</label>
                            <input type="date" name="delivery_date" id="delivery_date" class="form-control @error('delivery_date') is-invalid @enderror" value="{{ old('delivery_date') }}">
                            @error('delivery_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="">Selecione um status</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pendente</option>
                                <option value="ordered" {{ old('status') == 'ordered' ? 'selected' : '' }}>Pedido Feito</option>
                                <option value="received" {{ old('status') == 'received' ? 'selected' : '' }}>Recebido</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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

                <!-- Placeholder for items section -->
                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <h5>Itens da Compra</h5>
                        <p class="text-muted">A funcionalidade de adição de itens será implementada em breve.</p>
                        <p class="text-muted">Por enquanto, defina um valor total temporário:</p>
                        
                        <div class="form-group">
                            <label for="total" class="form-label">Valor Total *</label>
                            <input type="number" name="total" id="total" class="form-control @error('total') is-invalid @enderror" value="{{ old('total') }}" step="0.01" min="0" required>
                            @error('total')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Hidden field to satisfy validation -->
                        <input type="hidden" name="items[0][product_id]" value="1">
                        <input type="hidden" name="items[0][quantity]" value="1">
                        <input type="hidden" name="items[0][unit_cost]" value="0">
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary">Salvar Compra (Temporário)</button>
                    <a href="{{ route('admin.purchases.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop 