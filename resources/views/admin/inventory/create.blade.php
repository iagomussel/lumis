@extends('layouts.admin')

@section('title', 'Adicionar Item ao Estoque')

@section('content_header')
    <h1>Adicionar Item ao Estoque</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Novo Item de Estoque</h3>
        </div>
        <div class="card-body">
            <p>Funcionalidade para adicionar novo item ao estoque ou registrar estoque inicial de um produto ainda não implementada.</p>
            <p>Normalmente, o estoque é ajustado para produtos existentes ou o estoque inicial é definido ao criar um novo produto.</p>
            <a href="{{ route('admin.inventory.index') }}" class="btn btn-primary">Voltar para Lista de Estoque</a>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop 