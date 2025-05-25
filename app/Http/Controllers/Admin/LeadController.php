<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Lead::with('user');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $leads = $query->latest()->paginate(15);
        $users = User::all();

        return view('admin.leads.index', compact('leads', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('admin.leads.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:100',
            'status' => 'required|in:new,contacted,qualified,proposal_sent,negotiation,won,lost,unqualified',
            'source' => 'nullable|in:website,social_media,email_campaign,referral,trade_show,cold_call,organic_search,paid_ads,other',
            'score' => 'nullable|integer|min:0|max:100',
            'estimated_value' => 'nullable|numeric|min:0',
            'probability' => 'nullable|integer|min:0|max:100',
            'expected_close_date' => 'nullable|date',
            'user_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
            'next_follow_up_at' => 'nullable|datetime',
        ]);

        // Definir valores padrão para campos que não podem ser null no banco
        $validated['score'] = $validated['score'] ?? 0;
        $validated['probability'] = $validated['probability'] ?? 0;
        $validated['last_contact_at'] = now();

        $lead = Lead::create($validated);

        return redirect()->route('admin.leads.index')
            ->with('success', 'Lead criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lead $lead)
    {
        $lead->load('user');
        return view('admin.leads.show', compact('lead'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lead $lead)
    {
        $users = User::all();
        return view('admin.leads.edit', compact('lead', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:100',
            'status' => 'required|in:new,contacted,qualified,proposal_sent,negotiation,won,lost,unqualified',
            'source' => 'nullable|in:website,social_media,email_campaign,referral,trade_show,cold_call,organic_search,paid_ads,other',
            'score' => 'nullable|integer|min:0|max:100',
            'estimated_value' => 'nullable|numeric|min:0',
            'probability' => 'nullable|integer|min:0|max:100',
            'expected_close_date' => 'nullable|date',
            'user_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
            'next_follow_up_at' => 'nullable|datetime',
        ]);

        // Definir valores padrão para campos que não podem ser null no banco
        $validated['score'] = $validated['score'] ?? 0;
        $validated['probability'] = $validated['probability'] ?? 0;

        $lead->update($validated);

        return redirect()->route('admin.leads.index')
            ->with('success', 'Lead atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')
            ->with('success', 'Lead excluído com sucesso!');
    }

    /**
     * Convert lead to customer
     */
    public function convertToCustomer(Lead $lead)
    {
        // Verificar se já existe cliente com o mesmo email
        if ($lead->email && \App\Models\Customer::where('email', $lead->email)->exists()) {
            return redirect()->back()
                           ->with('error', 'Já existe um cliente cadastrado com este email.');
        }

        try {
            \DB::transaction(function () use ($lead) {
                // Criar customer a partir dos dados do lead
                $customerData = [
                    'name' => $lead->name,
                    'email' => $lead->email,
                    'phone' => $lead->phone,
                    'type' => $lead->company ? 'company' : 'individual',
                    'company_name' => $lead->company,
                    'status' => 'active',
                    'notes' => "Cliente convertido do lead #{$lead->id}\n\n" . $lead->notes,
                ];

                $customer = \App\Models\Customer::create($customerData);

                // Atualizar status do lead para "won"
                $lead->update([
                    'status' => 'won',
                    'notes' => $lead->notes . "\n\n[CONVERTIDO] Cliente criado em " . now()->format('d/m/Y H:i')
                ]);

                session()->flash('customer_created_id', $customer->id);
            });

            return redirect()->route('admin.leads.index')
                           ->with('success', 'Lead convertido em cliente com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Erro ao converter lead: ' . $e->getMessage());
        }
    }
}
