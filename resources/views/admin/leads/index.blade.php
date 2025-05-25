@extends('layouts.admin')

@section('title', 'Leads')

@section('header-actions')
    <a href="{{ route('admin.leads.create') }}" class="btn text-white font-medium hover:bg-blue-700 inline-flex items-center">
        <i class="ti ti-plus mr-2"></i>
        Novo Lead
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Filters Card -->
        <div class="card">
            <div class="card-body">
                <h6 class="text-lg text-gray-500 font-semibold mb-4">Filtros de Busca</h6>
                <form method="GET" action="{{ route('admin.leads.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-400 mb-2">Buscar</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                placeholder="Nome, email, telefone, empresa..."
                                class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0">
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-400 mb-2">Status</label>
                            <select name="status" id="status"
                                class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Todos</option>
                                <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>Novo</option>
                                <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>
                                    Contatado</option>
                                <option value="qualified" {{ request('status') === 'qualified' ? 'selected' : '' }}>
                                    Qualificado</option>
                                <option value="proposal_sent" {{ request('status') === 'proposal_sent' ? 'selected' : '' }}>
                                    Proposta Enviada</option>
                                <option value="unqualified" {{ request('status') === 'unqualified' ? 'selected' : '' }}>Não
                                    Qualificado</option>
                                <option value="negotiation" {{ request('status') === 'negotiation' ? 'selected' : '' }}>
                                    Negociação</option>
                                <option value="won" {{ request('status') === 'won' ? 'selected' : '' }}>Ganho</option>
                                <option value="lost" {{ request('status') === 'lost' ? 'selected' : '' }}>Perdido
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="source" class="block text-sm font-medium text-gray-400 mb-2">Origem</label>
                            <select name="source" id="source"
                                class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Todas</option>
                                <option value="website" {{ request('source') === 'website' ? 'selected' : '' }}>Website
                                </option>
                                <option value="social_media" {{ request('source') === 'social_media' ? 'selected' : '' }}>
                                    Redes Sociais</option>
                                <option value="referral" {{ request('source') === 'referral' ? 'selected' : '' }}>Indicação
                                </option>
                                <option value="email_campaign" {{ request('source') === 'email_campaign' ? 'selected' : '' }}>
                                    Campanha de Email</option>
                                <option value="trade_show" {{ request('source') === 'trade_show' ? 'selected' : '' }}>
                                    Feira/Evento</option>
                                <option value="organic_search" {{ request('source') === 'organic_search' ? 'selected' : '' }}>
                                    Busca Orgânica</option>
                                <option value="paid_ads" {{ request('source') === 'paid_ads' ? 'selected' : '' }}>
                                    Anúncios Pagos</option>
                                <option value="cold_call" {{ request('source') === 'cold_call' ? 'selected' : '' }}>Cold
                                    Call</option>

                                <option value="other" {{ request('source') === 'other' ? 'selected' : '' }}>Outro</option>
                            </select>
                        </div>

                        <div class="flex items-end space-x-2">
                            <button type="submit" class="btn text-white font-medium hover:bg-blue-700">
                                <i class="ti ti-search mr-2"></i>
                                Filtrar
                            </button>
                            <a href="{{ route('admin.leads.index') }}"
                                class="btn-outline-primary font-medium hover:bg-blue-600 hover:text-white inline-flex items-center">
                                <i class="ti ti-refresh mr-2"></i>
                                Limpar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Card -->
        <div class="card">
            <div class="card-body">
                <div class="flex justify-between items-center mb-5">
                    <h4 class="text-gray-500 text-lg font-semibold">Lista de Leads</h4>
                    <div class="text-sm text-gray-400">
                        {{ $leads->total() }} lead(s) encontrado(s)
                    </div>
                </div>

                @if ($leads->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Lead
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Contato
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Origem
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Valor Estimado
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Responsável
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($leads as $lead)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-10 h-10 rounded-full bg-purple-500 flex items-center justify-center mr-3">
                                                    <i class="ti ti-target text-white text-lg"></i>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-500">{{ $lead->name }}
                                                    </div>
                                                    @if ($lead->company)
                                                        <div class="text-sm text-gray-400">{{ $lead->company }}</div>
                                                    @endif
                                                    @if ($lead->position)
                                                        <div class="text-xs text-gray-400">{{ $lead->position }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                @if ($lead->email)
                                                    <div class="flex items-center mb-1">
                                                        <i class="ti ti-mail mr-2 text-gray-400"></i>
                                                        {{ $lead->email }}
                                                    </div>
                                                @endif
                                                @if ($lead->phone)
                                                    <div class="flex items-center text-gray-400">
                                                        <i class="ti ti-phone mr-2"></i>
                                                        {{ $lead->phone }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if ($lead->status === 'new') bg-blue-500 text-white
                                            @elseif($lead->status === 'contacted') bg-yellow-500 text-white
                                            @elseif($lead->status === 'qualified') bg-purple-500 text-white
                                            @elseif($lead->status === 'proposal_sent') bg-orange-500 text-white
                                            @elseif($lead->status === 'unqualified') bg-gray-500 text-white
                                            @elseif($lead->status === 'negotiation') bg-indigo-500 text-white
                                            @elseif($lead->status === 'won') bg-green-500 text-white
                                            @else bg-red-500 text-white @endif">
                                                @if ($lead->status === 'new')
                                                    <i class="ti ti-star mr-1"></i> Novo
                                                @elseif($lead->status === 'contacted')
                                                    <i class="ti ti-phone mr-1"></i> Contatado
                                                @elseif($lead->status === 'qualified')
                                                    <i class="ti ti-check mr-1"></i> Qualificado
                                                @elseif($lead->status === 'proposal_sent')
                                                    <i class="ti ti-file-text mr-1"></i> Proposta Enviada
                                                @elseif($lead->status === 'unqualified')
                                                    <i class="ti ti-x mr-1"></i> Não Qualificado
                                                @elseif($lead->status === 'negotiation')
                                                    <i class="ti ti-handshake mr-1"></i> Negociação
                                                @elseif($lead->status === 'won')
                                                    <i class="ti ti-trophy mr-1"></i> Ganho
                                                @else
                                                    <i class="ti ti-x mr-1"></i> Perdido
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($lead->source)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    @switch($lead->source)
                                                        @case('website')
                                                            <i class="ti ti-world mr-1"></i> Website
                                                        @break

                                                        @case('social_media')
                                                            <i class="ti ti-brand-facebook mr-1"></i> Redes Sociais
                                                        @break

                                                        @case('referral')
                                                            <i class="ti ti-users mr-1"></i> Indicação
                                                        @break

                                                        @case('email_campaign')
                                                            <i class="ti ti-mail mr-1"></i> Campanha de Email
                                                        @break

                                                        @case('trade_show')
                                                            <i class="ti ti-calendar-event mr-1"></i> Feira/Evento
                                                        @break

                                                        @case('cold_call')
                                                            <i class="ti ti-phone-call mr-1"></i> Cold Call
                                                        @break

                                                        @case('organic_search')
                                                            <i class="ti ti-search mr-1"></i> Busca Orgânica
                                                        @break

                                                        @case('paid_ads')
                                                            <i class="ti ti-ad mr-1"></i> Anúncios Pagos
                                                        @break

                                                        @default
                                                            <i class="ti ti-dots mr-1"></i> Outro
                                                    @endswitch
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-500">
                                                {{ $lead->formatted_estimated_value ?? '-' }}
                                            </div>
                                            @if ($lead->probability)
                                                <div class="text-xs text-gray-400">{{ $lead->probability }}% probabilidade
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($lead->user)
                                                <div class="flex items-center">
                                                    <div
                                                        class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center mr-2">
                                                        <span class="text-white text-xs font-medium">
                                                            {{ substr($lead->user->name, 0, 2) }}
                                                        </span>
                                                    </div>
                                                    <div class="text-sm text-gray-500">{{ $lead->user->name }}</div>
                                                </div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.leads.show', $lead) }}"
                                                    class="text-blue-600 hover:text-blue-700 inline-flex items-center">
                                                    <i class="ti ti-eye mr-1"></i> Ver
                                                </a>
                                                <a href="{{ route('admin.leads.edit', $lead) }}"
                                                    class="text-yellow-500 hover:text-yellow-600 inline-flex items-center">
                                                    <i class="ti ti-edit mr-1"></i> Editar
                                                </a>
                                                <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}"
                                                    class="inline"
                                                    onsubmit="return confirm('Tem certeza que deseja excluir este lead?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-500 hover:text-red-600 inline-flex items-center">
                                                        <i class="ti ti-trash mr-1"></i> Excluir
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($leads->hasPages())
                        <div class="mt-6">
                            {{ $leads->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                            <i class="ti ti-target text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-500 mb-2">Nenhum lead encontrado</h3>
                        <p class="text-sm text-gray-400 mb-6">
                            @if (request()->hasAny(['search', 'status', 'source']))
                                Tente ajustar os filtros ou
                                <a href="{{ route('admin.leads.index') }}"
                                    class="text-blue-600 hover:text-blue-700">limpar a busca</a>
                            @else
                                Comece criando seu primeiro lead
                            @endif
                        </p>
                        <a href="{{ route('admin.leads.create') }}"
                            class="btn text-white font-medium hover:bg-blue-700 inline-flex items-center">
                            <i class="ti ti-plus mr-2"></i>
                            Criar Primeiro Lead
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
