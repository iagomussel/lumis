@extends('layouts.admin')

@section('title', 'Ajustar Estoque do Produto')

@section('content_header')
    <h1>Ajustar Estoque: {{ $product->name }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Ajustar Quantidade em Estoque</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.inventory.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="product_name">Produto</label>
                    <input type="text" id="product_name" class="form-control" value="{{ $product->name }} (SKU: {{ $product->sku ?? 'N/A' }})" readonly>
                </div>

                <div class="form-group">
                    <label for="current_stock_quantity">Quantidade Atual em Estoque</label>
                    <input type="number" id="current_stock_quantity" class="form-control" value="{{ $product->stock_quantity }}" readonly>
                </div>

                <div class="form-group">
                    <label for="stock_quantity">Nova Quantidade em Estoque</label>
                    <input type="number" name="stock_quantity" id="stock_quantity" class="form-control @error('stock_quantity') is-invalid @enderror" value="{{ old('stock_quantity', $product->stock_quantity) }}" required min="0">
                    @error('stock_quantity')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <small class="form-text text-muted">Insira a quantidade total correta para este produto.</small>
                </div>
                
                {{-- Optional: Add a field for reason for adjustment --}}
                {{--
                <div class="form-group">
                    <label for="adjustment_reason">Motivo do Ajuste (Opcional)</label>
                    <textarea name="adjustment_reason" id="adjustment_reason" class="form-control">{{ old('adjustment_reason') }}</textarea>
                </div>
                --}}

                <button type="submit" class="btn btn-primary">Salvar Ajuste</button>
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop

@section('css')
    {{-- Add any specific CSS if needed --}}
@stop

@section('js')
    <script>
        // Add any specific JS if needed
    </script>
@stop 