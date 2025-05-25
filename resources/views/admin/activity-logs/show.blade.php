@extends('layouts.admin')

@section('title', 'Detalhes do Log de Auditoria')

@section('header-actions')
    <div class="flex space-x-2">
        <a href="{{ route('admin.activity-logs.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center">
            <i class="ti ti-arrow-left mr-2"></i>
            Voltar
        </a>
    </div>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center mr-4
                        @if($activityLog->severity === 'critical') bg-red-100
                        @elseif($activityLog->severity === 'error') bg-red-100
                        @elseif($activityLog->severity === 'warning') bg-yellow-100
                        @else bg-blue-100
                        @endif">
                        <i class="{{ $activityLog->severity_icon }} text-xl
                            @if($activityLog->severity === 'critical') text-red-600
                            @elseif($activityLog->severity === 'error') text-red-600
                            @elseif($activityLog->severity === 'warning') text-yellow-600
                            @else text-blue-600
                            @endif"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $activityLog->description }}</h2>
                        <p class="text-gray-500">Log ID: #{{ $activityLog->id }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($activityLog->severity === 'critical') bg-red-100 text-red-800
                        @elseif($activityLog->severity === 'error') bg-red-100 text-red-800
                        @elseif($activityLog->severity === 'warning') bg-yellow-100 text-yellow-800
                        @else bg-blue-100 text-blue-800
                        @endif">
                        <i class="{{ $activityLog->severity_icon }} mr-1"></i>
                        {{ ucfirst($activityLog->severity) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Data e Hora</label>
                    <p class="text-lg font-medium text-gray-900">{{ $activityLog->formatted_performed_at }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Ação</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <i class="{{ $activityLog->action_icon }} mr-1"></i>
                        {{ ucfirst($activityLog->action) }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Categoria</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                        {{ ucfirst($activityLog->category) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- User Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações do Usuário</h3>
            
            @if($activityLog->user_name || $activityLog->user_email)
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                        <span class="text-lg font-medium text-blue-800">
                            {{ $activityLog->user_name ? substr($activityLog->user_name, 0, 2) : 'S' }}
                        </span>
                    </div>
                    <div>
                        <h4 class="text-lg font-medium text-gray-900">
                            {{ $activityLog->user_name ?? 'Sistema' }}
                        </h4>
                        @if($activityLog->user_email)
                            <p class="text-gray-500">{{ $activityLog->user_email }}</p>
                        @endif
                    </div>
                </div>
            @else
                <div class="text-center py-4 text-gray-500">
                    <i class="ti ti-robot text-3xl mb-2"></i>
                    <p>Ação realizada pelo sistema</p>
                </div>
            @endif

            @if($activityLog->user && $activityLog->user->id)
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Status do Usuário</label>
                            <p class="text-sm text-gray-900">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="ti ti-check mr-1"></i>
                                    Ativo
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">ID do Usuário</label>
                            <p class="text-sm text-gray-900">#{{ $activityLog->user_id }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Model Information -->
        @if($activityLog->model_type || $activityLog->model_name)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações do Modelo</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($activityLog->model_type)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tipo do Modelo</label>
                            <p class="text-sm font-mono text-gray-900 bg-gray-100 px-2 py-1 rounded">
                                {{ $activityLog->model_type }}
                            </p>
                        </div>
                    @endif

                    @if($activityLog->model_id)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">ID do Modelo</label>
                            <p class="text-sm text-gray-900">#{{ $activityLog->model_id }}</p>
                        </div>
                    @endif

                    @if($activityLog->model_name)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Nome do Modelo</label>
                            <p class="text-sm text-gray-900">{{ $activityLog->model_name }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Changes Information -->
        @if($activityLog->old_values || $activityLog->new_values)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Alterações Realizadas</h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @if($activityLog->old_values)
                        <div>
                            <h4 class="text-md font-medium text-gray-700 mb-3">Valores Antigos</h4>
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <pre class="text-sm text-gray-800 whitespace-pre-wrap">{{ json_encode($activityLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        </div>
                    @endif

                    @if($activityLog->new_values)
                        <div>
                            <h4 class="text-md font-medium text-gray-700 mb-3">Valores Novos</h4>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <pre class="text-sm text-gray-800 whitespace-pre-wrap">{{ json_encode($activityLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Request Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações da Requisição</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($activityLog->ip_address)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Endereço IP</label>
                        <p class="text-sm font-mono text-gray-900 bg-gray-100 px-2 py-1 rounded">
                            {{ $activityLog->ip_address }}
                        </p>
                    </div>
                @endif

                @if($activityLog->method)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Método HTTP</label>
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                            @if($activityLog->method === 'GET') bg-blue-100 text-blue-800
                            @elseif($activityLog->method === 'POST') bg-green-100 text-green-800
                            @elseif($activityLog->method === 'PUT' || $activityLog->method === 'PATCH') bg-yellow-100 text-yellow-800
                            @elseif($activityLog->method === 'DELETE') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $activityLog->method }}
                        </span>
                    </div>
                @endif

                @if($activityLog->session_id)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">ID da Sessão</label>
                        <p class="text-sm font-mono text-gray-900 bg-gray-100 px-2 py-1 rounded truncate">
                            {{ $activityLog->session_id }}
                        </p>
                    </div>
                @endif

                @if($activityLog->url)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500 mb-1">URL</label>
                        <p class="text-sm font-mono text-gray-900 bg-gray-100 px-2 py-1 rounded break-all">
                            {{ $activityLog->url }}
                        </p>
                    </div>
                @endif

                @if($activityLog->user_agent)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500 mb-1">User Agent</label>
                        <p class="text-sm text-gray-900 bg-gray-100 px-2 py-1 rounded break-all">
                            {{ $activityLog->user_agent }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Metadata -->
        @if($activityLog->metadata)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Metadados Adicionais</h3>
                
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <pre class="text-sm text-gray-800 whitespace-pre-wrap">{{ json_encode($activityLog->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>
        @endif

        <!-- Timeline Context -->
        @if($activityLog->model_type && $activityLog->model_id)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Contexto Histórico</h3>
                
                @php
                    $relatedLogs = \App\Models\ActivityLog::where('model_type', $activityLog->model_type)
                        ->where('model_id', $activityLog->model_id)
                        ->where('id', '!=', $activityLog->id)
                        ->orderBy('performed_at', 'desc')
                        ->limit(5)
                        ->get();
                @endphp

                @if($relatedLogs->count() > 0)
                    <div class="space-y-3">
                        @foreach($relatedLogs as $relatedLog)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3
                                        @if($relatedLog->severity === 'critical') bg-red-100
                                        @elseif($relatedLog->severity === 'error') bg-red-100
                                        @elseif($relatedLog->severity === 'warning') bg-yellow-100
                                        @else bg-blue-100
                                        @endif">
                                        <i class="{{ $relatedLog->action_icon }} text-sm
                                            @if($relatedLog->severity === 'critical') text-red-600
                                            @elseif($relatedLog->severity === 'error') text-red-600
                                            @elseif($relatedLog->severity === 'warning') text-yellow-600
                                            @else text-blue-600
                                            @endif"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $relatedLog->description }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $relatedLog->formatted_performed_at }} por {{ $relatedLog->user_name ?? 'Sistema' }}
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ route('admin.activity-logs.show', $relatedLog) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    <i class="ti ti-external-link text-sm"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">
                        Nenhum outro log encontrado para este modelo.
                    </p>
                @endif
            </div>
        @endif

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Ações</h3>
                <div class="flex space-x-3">
                    <form action="{{ route('admin.activity-logs.destroy', $activityLog) }}" 
                          method="POST" 
                          class="inline"
                          onsubmit="return confirm('Tem certeza que deseja excluir este log?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg inline-flex items-center">
                            <i class="ti ti-trash mr-2"></i>
                            Excluir Log
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection 