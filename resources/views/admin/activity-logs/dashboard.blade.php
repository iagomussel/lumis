@extends('layouts.admin')

@section('title', 'Dashboard de Auditoria')

@section('header-actions')
    <div class="flex space-x-2">
        <a href="{{ route('admin.activity-logs.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center">
            <i class="ti ti-list mr-2"></i>
            Ver Todos os Logs
        </a>
        <a href="{{ route('admin.activity-logs.export') }}" 
           class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg inline-flex items-center">
            <i class="ti ti-download mr-2"></i>
            Exportar
        </a>
    </div>
@endsection

@section('content')
    <!-- Statistics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="ti ti-activity text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Atividades (30 dias)</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_activities']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="ti ti-users text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Usuários Ativos</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['unique_users']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="ti ti-alert-octagon text-red-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Alertas Críticos</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['critical_alerts']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="ti ti-x-circle text-yellow-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Erros</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['error_count']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Daily Activity Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Atividade Diária (7 dias)</h3>
            
            @if($dailyActivity->count() > 0)
                <div class="space-y-4">
                    @php
                        $maxCount = $dailyActivity->map(function($activities) {
                            return $activities->sum('count');
                        })->max();
                    @endphp
                    
                    @for($i = 6; $i >= 0; $i--)
                        @php
                            $date = now()->subDays($i)->format('Y-m-d');
                            $activities = $dailyActivity->get($date, collect());
                            $totalCount = $activities->sum('count');
                            $criticalCount = $activities->where('severity', 'critical')->sum('count');
                            $errorCount = $activities->where('severity', 'error')->sum('count');
                            $warningCount = $activities->where('severity', 'warning')->sum('count');
                            $infoCount = $activities->where('severity', 'info')->sum('count');
                            $percentage = $maxCount > 0 ? ($totalCount / $maxCount) * 100 : 0;
                        @endphp
                        
                        <div class="flex items-center justify-between">
                            <div class="w-20 text-sm text-gray-600">
                                {{ now()->subDays($i)->format('d/m') }}
                            </div>
                            <div class="flex-1 mx-4">
                                <div class="relative">
                                    <div class="w-full bg-gray-200 rounded-full h-6">
                                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-6 rounded-full transition-all duration-300" 
                                             style="width: {{ $percentage }}%"></div>
                                    </div>
                                    @if($totalCount > 0)
                                        <div class="absolute inset-0 flex items-center justify-center text-xs font-medium text-white">
                                            {{ $totalCount }}
                                        </div>
                                    @endif
                                </div>
                                @if($criticalCount > 0 || $errorCount > 0 || $warningCount > 0)
                                    <div class="flex space-x-1 mt-1">
                                        @if($criticalCount > 0)
                                            <span class="text-xs bg-red-100 text-red-800 px-1 rounded">{{ $criticalCount }} crítico(s)</span>
                                        @endif
                                        @if($errorCount > 0)
                                            <span class="text-xs bg-red-100 text-red-800 px-1 rounded">{{ $errorCount }} erro(s)</span>
                                        @endif
                                        @if($warningCount > 0)
                                            <span class="text-xs bg-yellow-100 text-yellow-800 px-1 rounded">{{ $warningCount }} aviso(s)</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="w-12 text-right text-sm font-medium text-gray-900">
                                {{ $totalCount }}
                            </div>
                        </div>
                    @endfor
                </div>
            @else
                <div class="text-center py-8">
                    <i class="ti ti-chart-bar text-4xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500">Nenhuma atividade nos últimos 7 dias</p>
                </div>
            @endif
        </div>

        <!-- Top Actions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ações Mais Frequentes</h3>
            
            @if($topActions->count() > 0)
                <div class="space-y-3">
                    @foreach($topActions as $action)
                        @php
                            $percentage = $topActions->max('count') > 0 ? ($action->count / $topActions->max('count')) * 100 : 0;
                            $icon = [
                                'create' => 'ti-plus',
                                'update' => 'ti-edit',
                                'delete' => 'ti-trash',
                                'login' => 'ti-login',
                                'logout' => 'ti-logout',
                                'view' => 'ti-eye',
                            ][$action->action] ?? 'ti-activity';
                        @endphp
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center flex-1">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="{{ $icon }} text-blue-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-sm font-medium text-gray-900">{{ ucfirst($action->action) }}</span>
                                        <span class="text-sm text-gray-600">{{ $action->count }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-500 h-2 rounded-full transition-all duration-300" 
                                             style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="ti ti-activity text-4xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500">Nenhuma ação registrada</p>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Most Active Users -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Usuários Mais Ativos</h3>
            
            @if($topUsers->count() > 0)
                <div class="space-y-4">
                    @foreach($topUsers as $user)
                        @php
                            $percentage = $topUsers->max('count') > 0 ? ($user->count / $topUsers->max('count')) * 100 : 0;
                        @endphp
                        
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                <span class="text-sm font-medium text-blue-800">
                                    {{ substr($user->user_name, 0, 2) }}
                                </span>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-center mb-1">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->user_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->user_email }}</p>
                                    </div>
                                    <span class="text-sm font-medium text-gray-600">{{ $user->count }} ações</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full transition-all duration-300" 
                                         style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="ti ti-users text-4xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500">Nenhum usuário ativo registrado</p>
                </div>
            @endif
        </div>

        <!-- Recent Critical/Error Logs -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Alertas Recentes</h3>
                <a href="{{ route('admin.activity-logs.index', ['severity' => 'critical']) }}" 
                   class="text-sm text-blue-600 hover:text-blue-800">
                    Ver todos
                </a>
            </div>
            
            @if($recentAlerts->count() > 0)
                <div class="space-y-3">
                    @foreach($recentAlerts as $alert)
                        <div class="flex items-start p-3 rounded-lg
                            @if($alert->severity === 'critical') bg-red-50 border border-red-200
                            @else bg-yellow-50 border border-yellow-200
                            @endif">
                            <div class="flex-shrink-0 mr-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center
                                    @if($alert->severity === 'critical') bg-red-100
                                    @else bg-yellow-100
                                    @endif">
                                    <i class="{{ $alert->severity_icon }} text-sm
                                        @if($alert->severity === 'critical') text-red-600
                                        @else text-yellow-600
                                        @endif"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $alert->description }}
                                </p>
                                <div class="flex items-center mt-1 text-xs text-gray-500">
                                    <span>{{ $alert->formatted_performed_at }}</span>
                                    <span class="mx-1">•</span>
                                    <span>{{ $alert->user_name ?? 'Sistema' }}</span>
                                    @if($alert->model_name)
                                        <span class="mx-1">•</span>
                                        <span>{{ $alert->model_name }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-shrink-0 ml-2">
                                <a href="{{ route('admin.activity-logs.show', $alert) }}" 
                                   class="text-gray-400 hover:text-gray-600">
                                    <i class="ti ti-external-link text-sm"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="ti ti-shield-check text-4xl text-green-400 mb-2"></i>
                    <p class="text-green-600 font-medium">Nenhum alerta crítico</p>
                    <p class="text-sm text-gray-500">Sistema funcionando normalmente</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Ações Rápidas</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.activity-logs.index', ['severity' => 'critical']) }}" 
               class="flex items-center p-4 border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-4">
                    <i class="ti ti-alert-octagon text-red-600"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Ver Alertas Críticos</p>
                    <p class="text-sm text-gray-500">{{ $stats['critical_alerts'] }} alertas encontrados</p>
                </div>
            </a>

            <a href="{{ route('admin.activity-logs.index', ['date_range' => 'today']) }}" 
               class="flex items-center p-4 border border-blue-200 rounded-lg hover:bg-blue-50 transition-colors">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                    <i class="ti ti-calendar-today text-blue-600"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Atividade de Hoje</p>
                    <p class="text-sm text-gray-500">Ver logs de hoje</p>
                </div>
            </a>

            <button onclick="openCleanupModal()" 
                    class="flex items-center p-4 border border-yellow-200 rounded-lg hover:bg-yellow-50 transition-colors">
                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-4">
                    <i class="ti ti-trash text-yellow-600"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Limpeza de Logs</p>
                    <p class="text-sm text-gray-500">Remover logs antigos</p>
                </div>
            </button>
        </div>
    </div>

    <!-- Cleanup Modal -->
    <div id="cleanupModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Limpeza de Logs Antigos</h3>
            <form action="{{ route('admin.activity-logs.cleanup') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="days" class="block text-sm font-medium text-gray-700 mb-2">
                        Remover logs mais antigos que (dias):
                    </label>
                    <input type="number" 
                           name="days" 
                           id="days" 
                           min="1" 
                           max="365" 
                           value="90"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <p class="text-sm text-gray-500 mt-1">
                        Logs mais antigos que este período serão permanentemente removidos.
                    </p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeCleanupModal()"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md">
                        Limpar Logs
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function openCleanupModal() {
    document.getElementById('cleanupModal').classList.remove('hidden');
    document.getElementById('cleanupModal').classList.add('flex');
}

function closeCleanupModal() {
    document.getElementById('cleanupModal').classList.add('hidden');
    document.getElementById('cleanupModal').classList.remove('flex');
}
</script>
@endpush 