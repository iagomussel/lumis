@extends('layouts.admin')

@section('title', 'Controle de Estoque')

@section('content_header')
    <h1>Controle de Estoque</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Produtos</h3>
            {{-- Add link to create new stock item or adjust stock for new products --}}
            {{-- <a href="{{ route('admin.inventory.create') }}" class="btn btn-primary float-right">Adicionar Novo Item ao Estoque</a> --}}
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>SKU</th>
                        <th>Quantidade em Estoque</th>
                        <th>Preço de Venda</th>
                        <th>Preço de Custo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->stock_quantity }}</td>
                            <td>R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                            <td>R$ {{ number_format($product->cost_price, 2, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('admin.inventory.edit', $product->id) }}" class="btn btn-sm btn-info">Ajustar Estoque</a>
                                {{-- Add other actions like view stock history if implemented --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Nenhum produto encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $products->links() }} {{-- Pagination links --}}
            </div>
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