@extends('layouts.admin')

@section('title', 'Detalhes do Lead')

@section('header-actions')
    <div class="flex space-x-2">
        <a href="{{ route('admin.leads.edit', $lead) }}" 
           class="btn text-white font-medium hover:bg-blue-700 inline-flex items-center">
            <i class="ti ti-edit mr-2"></i>
            Editar
        </a>
        <a href="{{ route('admin.leads.index') }}" 
           class="btn-outline-primary font-medium hover:bg-blue-600 hover:text-white inline-flex items-center">
            <i class="ti ti-arrow-left mr-2"></i>
            Voltar
        </a>
    </div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Informações Básicas -->
    <div class="card">
        <div class="card-body">
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center">
                    <div class="w-16 h-16 rounded-full bg-purple-500 flex items-center justify-center mr-4">
                        <i class="ti ti-target text-white text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="text-gray-500 text-xl font-semibold">{{ $lead->name }}</h4>
                        @if($lead->company)
                            <p class="text-gray-400">{{ $lead->company }}</p>
                        @endif
                        @if($lead->position)
                            <p class="text-sm text-gray-400">{{ $lead->position }}</p>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($lead->status === 'new') bg-blue-500 text-white
                        @elseif($lead->status === 'contacted') bg-yellow-500 text-white
                        @elseif($lead->status === 'qualified') bg-purple-500 text-white
                        @elseif($lead->status === 'proposal') bg-orange-500 text-white
                        @elseif($lead->status === 'negotiation') bg-indigo-500 text-white
                        @elseif($lead->status === 'won') bg-green-500 text-white
                        @else bg-red-500 text-white
                        @endif">
                        @if($lead->status === 'new') 
                            <i class="ti ti-star mr-1"></i> Novo
                        @elseif($lead->status === 'contacted') 
                            <i class="ti ti-phone mr-1"></i> Contatado
                        @elseif($lead->status === 'qualified') 
                            <i class="ti ti-check mr-1"></i> Qualificado
                        @elseif($lead->status === 'proposal') 
                            <i class="ti ti-file-text mr-1"></i> Proposta
                        @elseif($lead->status === 'negotiation') 
                            <i class="ti ti-handshake mr-1"></i> Negociação
                        @elseif($lead->status === 'won') 
                            <i class="ti ti-trophy mr-1"></i> Ganho
                        @else 
                            <i class="ti ti-x mr-1"></i> Perdido
                        @endif
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Contato -->
                <div class="space-y-4">
                    <h6 class="text-lg text-gray-500 font-semibold">Contato</h6>
                    @if($lead->email)
                        <div class="flex items-center">
                            <i class="ti ti-mail mr-3 text-gray-400"></i>
                            <div>
                                <p class="text-sm text-gray-400">Email</p>
                                <p class="text-gray-500">{{ $lead->email }}</p>
                            </div>
                        </div>
                    @endif
                    @if($lead->phone)
                        <div class="flex items-center">
                            <i class="ti ti-phone mr-3 text-gray-400"></i>
                            <div>
                                <p class="text-sm text-gray-400">Telefone</p>
                                <p class="text-gray-500">{{ $lead->phone }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Origem -->
                <div class="space-y-4">
                    <h6 class="text-lg text-gray-500 font-semibold">Origem</h6>
                    @if($lead->source)
                        <div class="flex items-center">
                            @switch($lead->source)
                                @case('website')
                                    <i class="ti ti-world mr-3 text-gray-400"></i>
                                    @break
                                @case('social_media')
                                    <i class="ti ti-brand-facebook mr-3 text-gray-400"></i>
                                    @break
                                @case('referral')
                                    <i class="ti ti-users mr-3 text-gray-400"></i>
                                    @break
                                @case('advertising')
                                    <i class="ti ti-ad mr-3 text-gray-400"></i>
                                    @break
                                @case('cold_call')
                                    <i class="ti ti-phone-call mr-3 text-gray-400"></i>
                                    @break
                                @case('event')
                                    <i class="ti ti-calendar-event mr-3 text-gray-400"></i>
                                    @break
                                @default
                                    <i class="ti ti-dots mr-3 text-gray-400"></i>
                            @endswitch
                            <div>
                                <p class="text-sm text-gray-400">Fonte</p>
                                <p class="text-gray-500">
                                    @switch($lead->source)
                                        @case('website') Website @break
                                        @case('social_media') Redes Sociais @break
                                        @case('referral') Indicação @break
                                        @case('advertising') Publicidade @break
                                        @case('cold_call') Cold Call @break
                                        @case('event') Evento @break
                                        @default Outro
                                    @endswitch
                                </p>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-400">Não informado</p>
                    @endif
                </div>

                <!-- Responsável -->
                <div class="space-y-4">
                    <h6 class="text-lg text-gray-500 font-semibold">Responsável</h6>
                    @if($lead->user)
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-medium">
                                    {{ substr($lead->user->name, 0, 2) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Vendedor</p>
                                <p class="text-gray-500">{{ $lead->user->name }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-400">Não atribuído</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Qualificação e Valores -->
    <div class="card">
        <div class="card-body">
            <h6 class="text-lg text-gray-500 font-semibold mb-6">Qualificação e Valores</h6>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-3">
                        <i class="ti ti-star text-blue-500 text-xl"></i>
                    </div>
                    <p class="text-sm text-gray-400 mb-1">Score</p>
                    <p class="text-2xl font-bold text-gray-500">{{ $lead->score ?? '-' }}</p>
                    @if($lead->score)
                        <p class="text-xs text-gray-400">de 100</p>
                    @endif
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-3">
                        <i class="ti ti-currency-real text-green-500 text-xl"></i>
                    </div>
                    <p class="text-sm text-gray-400 mb-1">Valor Estimado</p>
                    <p class="text-2xl font-bold text-gray-500">{{ $lead->formatted_estimated_value ?? '-' }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 rounded-full bg-purple-100 flex items-center justify-center mx-auto mb-3">
                        <i class="ti ti-percentage text-purple-500 text-xl"></i>
                    </div>
                    <p class="text-sm text-gray-400 mb-1">Probabilidade</p>
                    <p class="text-2xl font-bold text-gray-500">{{ $lead->probability ?? '-' }}</p>
                    @if($lead->probability)
                        <p class="text-xs text-gray-400">%</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Datas Importantes -->
    <div class="card">
        <div class="card-body">
            <h6 class="text-lg text-gray-500 font-semibold mb-6">Datas Importantes</h6>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-center">
                    <i class="ti ti-calendar mr-3 text-gray-400"></i>
                    <div>
                        <p class="text-sm text-gray-400">Data de Criação</p>
                        <p class="text-gray-500">{{ $lead->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                @if($lead->expected_close_date)
                    <div class="flex items-center">
                        <i class="ti ti-calendar-event mr-3 text-gray-400"></i>
                        <div>
                            <p class="text-sm text-gray-400">Fechamento Previsto</p>
                            <p class="text-gray-500">{{ $lead->expected_close_date->format('d/m/Y') }}</p>
                        </div>
                    </div>
                @endif

                @if($lead->next_follow_up_at)
                    <div class="flex items-center">
                        <i class="ti ti-clock mr-3 text-gray-400"></i>
                        <div>
                            <p class="text-sm text-gray-400">Próximo Follow-up</p>
                            <p class="text-gray-500">{{ $lead->next_follow_up_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                @endif

                <div class="flex items-center">
                    <i class="ti ti-edit mr-3 text-gray-400"></i>
                    <div>
                        <p class="text-sm text-gray-400">Última Atualização</p>
                        <p class="text-gray-500">{{ $lead->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Observações -->
    @if($lead->notes)
        <div class="card">
            <div class="card-body">
                <h6 class="text-lg text-gray-500 font-semibold mb-4">Observações</h6>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-500 whitespace-pre-wrap">{{ $lead->notes }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Ações -->
    <div class="card">
        <div class="card-body">
            <h6 class="text-lg text-gray-500 font-semibold mb-4">Ações</h6>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.leads.edit', $lead) }}" 
                   class="btn text-white font-medium hover:bg-blue-700 inline-flex items-center">
                    <i class="ti ti-edit mr-2"></i>
                    Editar Lead
                </a>
                
                @if($lead->status !== 'won' && $lead->status !== 'lost')
                    <button type="button" 
                            class="btn-outline-success font-medium hover:bg-green-600 hover:text-white inline-flex items-center"
                            onclick="updateStatus('won')">
                        <i class="ti ti-trophy mr-2"></i>
                        Marcar como Ganho
                    </button>
                    
                    <button type="button" 
                            class="btn-outline-danger font-medium hover:bg-red-600 hover:text-white inline-flex items-center"
                            onclick="updateStatus('lost')">
                        <i class="ti ti-x mr-2"></i>
                        Marcar como Perdido
                    </button>
                @endif

                <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}" 
                      class="inline" 
                      onsubmit="return confirm('Tem certeza que deseja excluir este lead?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="btn-outline-danger font-medium hover:bg-red-600 hover:text-white inline-flex items-center">
                        <i class="ti ti-trash mr-2"></i>
                        Excluir Lead
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para atualizar status -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Atualizar Status do Lead</h3>
        <form id="statusForm" method="POST" action="{{ route('admin.leads.update', $lead) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" id="newStatus">
            <input type="hidden" name="name" value="{{ $lead->name }}">
            <input type="hidden" name="user_id" value="{{ $lead->user_id }}">
            
            <p class="text-gray-600 mb-4">Tem certeza que deseja alterar o status deste lead?</p>
            
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        onclick="closeStatusModal()"
                        class="btn-outline-primary font-medium hover:bg-blue-600 hover:text-white">
                    Cancelar
                </button>
                <button type="submit" 
                        class="btn text-white font-medium hover:bg-blue-700">
                    Confirmar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function updateStatus(status) {
    document.getElementById('newStatus').value = status;
    document.getElementById('statusModal').classList.remove('hidden');
    document.getElementById('statusModal').classList.add('flex');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
    document.getElementById('statusModal').classList.remove('flex');
}
</script>
@endsection 