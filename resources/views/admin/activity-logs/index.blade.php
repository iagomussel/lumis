@extends('layouts.admin')

@section('title', 'Logs de Auditoria')

@section('header-actions')
    <div class="flex space-x-2">
        <a href="{{ route('admin.activity-logs.dashboard') }}" 
           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg inline-flex items-center">
            <i class="ti ti-chart-bar mr-2"></i>
            Dashboard
        </a>
        <form action="{{ route('admin.activity-logs.export') }}" method="GET" class="inline">
            @foreach(request()->except('_token') as $key => $value)
                @if(is_array($value))
                    @foreach($value as $v)
                        <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                    @endforeach
                @else
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach
            <button type="submit" 
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg inline-flex items-center">
                <i class="ti ti-download mr-2"></i>
                Exportar
            </button>
        </form>
    </div>
@endsection

@section('content')
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="ti ti-activity text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Total de Logs</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_logs']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="ti ti-calendar-today text-green-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Hoje</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['today_logs']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="ti ti-alert-octagon text-red-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Críticos</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['critical_logs']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="ti ti-x-circle text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Erros</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['error_logs']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Buscar logs..."
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- User Filter -->
                <div>
                    <select name="user_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todos os usuários</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Filter -->
                <div>
                    <select name="action" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todas as ações</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                {{ ucfirst($action) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Category Filter -->
                <div>
                    <select name="category" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todas as categorias</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ ucfirst($category) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Severity Filter -->
                <div>
                    <select name="severity" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todas as severidades</option>
                        @foreach($severities as $severity)
                            <option value="{{ $severity }}" {{ request('severity') == $severity ? 'selected' : '' }}>
                                {{ ucfirst($severity) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Quick Date Range -->
                <div>
                    <select name="date_range" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Período personalizado</option>
                        <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Hoje</option>
                        <option value="yesterday" {{ request('date_range') == 'yesterday' ? 'selected' : '' }}>Ontem</option>
                        <option value="this_week" {{ request('date_range') == 'this_week' ? 'selected' : '' }}>Esta semana</option>
                        <option value="last_week" {{ request('date_range') == 'last_week' ? 'selected' : '' }}>Semana passada</option>
                        <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>Este mês</option>
                        <option value="last_month" {{ request('date_range') == 'last_month' ? 'selected' : '' }}>Mês passado</option>
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <input type="date" 
                           name="date_from" 
                           value="{{ request('date_from') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Date To -->
                <div>
                    <input type="date" 
                           name="date_to" 
                           value="{{ request('date_to') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Buttons -->
                <div class="flex space-x-2">
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md flex-1">
                        Filtrar
                    </button>
                    <a href="{{ route('admin.activity-logs.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                        Limpar
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Activity Logs Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">
                    Logs de Atividade ({{ $logs->total() }})
                </h3>
                
                @if($logs->count() > 0)
                    <div class="flex space-x-2">
                        <button onclick="selectAll()" 
                                class="text-sm text-blue-600 hover:text-blue-800">
                            Selecionar Todos
                        </button>
                        <button onclick="deleteSelected()" 
                                class="text-sm text-red-600 hover:text-red-800"
                                id="deleteSelectedBtn" 
                                style="display: none;">
                            Excluir Selecionados
                        </button>
                    </div>
                @endif
            </div>
        </div>

        @if($logs->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll()">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Data/Hora
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Usuário
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ação
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Descrição
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Categoria
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Severidade
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($logs as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <input type="checkbox" 
                                           class="logCheckbox" 
                                           value="{{ $log->id }}" 
                                           onchange="updateDeleteButton()">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $log->formatted_performed_at }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-xs font-medium text-blue-800">
                                                {{ $log->user_name ? substr($log->user_name, 0, 2) : 'S' }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $log->user_name ?? 'Sistema' }}
                                            </div>
                                            @if($log->user_email)
                                                <div class="text-sm text-gray-500">
                                                    {{ $log->user_email }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="{{ $log->action_icon }} mr-1"></i>
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                                    {{ $log->description }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($log->category) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($log->severity === 'critical') bg-red-100 text-red-800
                                        @elseif($log->severity === 'error') bg-red-100 text-red-800
                                        @elseif($log->severity === 'warning') bg-yellow-100 text-yellow-800
                                        @else bg-blue-100 text-blue-800
                                        @endif">
                                        <i class="{{ $log->severity_icon }} mr-1"></i>
                                        {{ ucfirst($log->severity) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.activity-logs.show', $log) }}" 
                                       class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.activity-logs.destroy', $log) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('Tem certeza que deseja excluir este log?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $logs->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="ti ti-activity text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum log encontrado</h3>
                <p class="text-gray-500">
                    @if(request()->hasAny(['search', 'user_id', 'action', 'category', 'severity', 'date_from', 'date_to', 'date_range']))
                        Tente ajustar os filtros para encontrar logs.
                    @else
                        Os logs de atividade aparecerão aqui conforme as ações são realizadas no sistema.
                    @endif
                </p>
            </div>
        @endif
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
function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const logCheckboxes = document.querySelectorAll('.logCheckbox');
    
    logCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
    
    updateDeleteButton();
}

function selectAll() {
    document.getElementById('selectAllCheckbox').checked = true;
    toggleSelectAll();
}

function updateDeleteButton() {
    const selectedCheckboxes = document.querySelectorAll('.logCheckbox:checked');
    const deleteButton = document.getElementById('deleteSelectedBtn');
    
    if (selectedCheckboxes.length > 0) {
        deleteButton.style.display = 'inline';
    } else {
        deleteButton.style.display = 'none';
    }
}

function deleteSelected() {
    const selectedCheckboxes = document.querySelectorAll('.logCheckbox:checked');
    const ids = Array.from(selectedCheckboxes).map(cb => cb.value);
    
    if (ids.length === 0) {
        alert('Selecione pelo menos um log para excluir.');
        return;
    }
    
    if (!confirm(`Tem certeza que deseja excluir ${ids.length} log(s) selecionado(s)?`)) {
        return;
    }
    
    fetch('{{ route("admin.activity-logs.bulk-delete") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ ids: ids })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erro ao excluir logs.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erro ao excluir logs.');
    });
}

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