@extends('layouts.admin')

@section('title', 'Editar Lead')

@section('header-actions')
    <a href="{{ route('admin.leads.index') }}" 
       class="btn-outline-primary font-medium hover:bg-blue-600 hover:text-white inline-flex items-center">
        <i class="ti ti-arrow-left mr-2"></i>
        Voltar
    </a>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="card">
        <div class="card-body">
            <div class="flex justify-between items-center mb-6">
                <h4 class="text-gray-500 text-lg font-semibold">Editar Lead: {{ $lead->name }}</h4>
            </div>

            <form method="POST" action="{{ route('admin.leads.update', $lead) }}">
                @csrf
                @method('PUT')

                <!-- Informações Básicas -->
                <div class="mb-8">
                    <h6 class="text-lg text-gray-500 font-semibold mb-4">Informações Básicas</h6>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm mb-2 text-gray-400">Nome *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $lead->name) }}" required
                                   class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm mb-2 text-gray-400">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $lead->email) }}"
                                   class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm mb-2 text-gray-400">Telefone</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $lead->phone) }}"
                                   class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="company" class="block text-sm mb-2 text-gray-400">Empresa</label>
                            <input type="text" name="company" id="company" value="{{ old('company', $lead->company) }}"
                                   class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('company') border-red-500 @enderror">
                            @error('company')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="position" class="block text-sm mb-2 text-gray-400">Cargo</label>
                            <input type="text" name="position" id="position" value="{{ old('position', $lead->position) }}"
                                   class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('position') border-red-500 @enderror">
                            @error('position')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="user_id" class="block text-sm mb-2 text-gray-400">Responsável *</label>
                            <select name="user_id" id="user_id" required
                                    class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-500 focus:ring-blue-500 @error('user_id') border-red-500 @enderror">
                                <option value="">Selecione...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $lead->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status e Origem -->
                <div class="mb-8">
                    <h6 class="text-lg text-gray-500 font-semibold mb-4">Status e Origem</h6>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="status" class="block text-sm mb-2 text-gray-400">Status *</label>
                            <select name="status" id="status" required
                                    class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-500 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                                <option value="">Selecione...</option>
                                <option value="new" {{ old('status', $lead->status) === 'new' ? 'selected' : '' }}>Novo</option>
                                <option value="contacted" {{ old('status', $lead->status) === 'contacted' ? 'selected' : '' }}>Contatado</option>
                                <option value="qualified" {{ old('status', $lead->status) === 'qualified' ? 'selected' : '' }}>Qualificado</option>
                                <option value="proposal" {{ old('status', $lead->status) === 'proposal' ? 'selected' : '' }}>Proposta</option>
                                <option value="negotiation" {{ old('status', $lead->status) === 'negotiation' ? 'selected' : '' }}>Negociação</option>
                                <option value="won" {{ old('status', $lead->status) === 'won' ? 'selected' : '' }}>Ganho</option>
                                <option value="lost" {{ old('status', $lead->status) === 'lost' ? 'selected' : '' }}>Perdido</option>
                            </select>
                            @error('status')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="source" class="block text-sm mb-2 text-gray-400">Origem</label>
                            <select name="source" id="source"
                                    class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-500 focus:ring-blue-500 @error('source') border-red-500 @enderror">
                                <option value="">Selecione...</option>
                                <option value="website" {{ old('source', $lead->source) === 'website' ? 'selected' : '' }}>Website</option>
                                <option value="social_media" {{ old('source', $lead->source) === 'social_media' ? 'selected' : '' }}>Redes Sociais</option>
                                <option value="referral" {{ old('source', $lead->source) === 'referral' ? 'selected' : '' }}>Indicação</option>
                                <option value="advertising" {{ old('source', $lead->source) === 'advertising' ? 'selected' : '' }}>Publicidade</option>
                                <option value="cold_call" {{ old('source', $lead->source) === 'cold_call' ? 'selected' : '' }}>Cold Call</option>
                                <option value="event" {{ old('source', $lead->source) === 'event' ? 'selected' : '' }}>Evento</option>
                                <option value="other" {{ old('source', $lead->source) === 'other' ? 'selected' : '' }}>Outro</option>
                            </select>
                            @error('source')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Qualificação -->
                <div class="mb-8">
                    <h6 class="text-lg text-gray-500 font-semibold mb-4">Qualificação</h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="score" class="block text-sm mb-2 text-gray-400">Score (0-100)</label>
                            <input type="number" name="score" id="score" value="{{ old('score', $lead->score) }}" min="0" max="100"
                                   class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('score') border-red-500 @enderror">
                            @error('score')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="estimated_value" class="block text-sm mb-2 text-gray-400">Valor Estimado (R$)</label>
                            <input type="number" name="estimated_value" id="estimated_value" value="{{ old('estimated_value', $lead->estimated_value) }}" step="0.01" min="0"
                                   class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('estimated_value') border-red-500 @enderror">
                            @error('estimated_value')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="probability" class="block text-sm mb-2 text-gray-400">Probabilidade (%)</label>
                            <input type="number" name="probability" id="probability" value="{{ old('probability', $lead->probability) }}" min="0" max="100"
                                   class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('probability') border-red-500 @enderror">
                            @error('probability')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Datas -->
                <div class="mb-8">
                    <h6 class="text-lg text-gray-500 font-semibold mb-4">Datas</h6>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="expected_close_date" class="block text-sm mb-2 text-gray-400">Data Prevista de Fechamento</label>
                            <input type="date" name="expected_close_date" id="expected_close_date" 
                                   value="{{ old('expected_close_date', $lead->expected_close_date ? $lead->expected_close_date->format('Y-m-d') : '') }}"
                                   class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('expected_close_date') border-red-500 @enderror">
                            @error('expected_close_date')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="next_follow_up_at" class="block text-sm mb-2 text-gray-400">Próximo Follow-up</label>
                            <input type="datetime-local" name="next_follow_up_at" id="next_follow_up_at" 
                                   value="{{ old('next_follow_up_at', $lead->next_follow_up_at ? $lead->next_follow_up_at->format('Y-m-d\TH:i') : '') }}"
                                   class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('next_follow_up_at') border-red-500 @enderror">
                            @error('next_follow_up_at')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Observações -->
                <div class="mb-8">
                    <h6 class="text-lg text-gray-500 font-semibold mb-4">Observações</h6>
                    <div>
                        <label for="notes" class="block text-sm mb-2 text-gray-400">Notas</label>
                        <textarea name="notes" id="notes" rows="4"
                                  class="py-3 px-4 text-gray-500 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0 @error('notes') border-red-500 @enderror">{{ old('notes', $lead->notes) }}</textarea>
                        @error('notes')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.leads.index') }}" 
                       class="btn-outline-primary font-medium hover:bg-blue-600 hover:text-white inline-flex items-center">
                        <i class="ti ti-x mr-2"></i>
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="btn text-white font-medium hover:bg-blue-700 inline-flex items-center">
                        <i class="ti ti-check mr-2"></i>
                        Atualizar Lead
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 