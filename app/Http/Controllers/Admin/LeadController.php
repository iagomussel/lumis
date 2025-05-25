<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead; // Assuming Lead model exists

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Lead::query();

        // Basic search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leads = $query->latest()->paginate(20)->withQueryString();
        
        // Assuming a list of possible statuses for the filter dropdown
        $statuses = Lead::select('status')->distinct()->pluck('status')->toArray(); 
        // Or define manually: $statuses = ['new', 'contacted', 'qualified', 'lost', 'converted'];
        if (empty($statuses)) {
            $statuses = ['new', 'contacted', 'qualified', 'negotiation', 'won', 'lost', 'unqualified']; // Default statuses
        }

        return view('admin.leads.index', compact('leads', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Define default statuses if not dynamically fetched or to ensure consistency
        $statuses = ['new', 'contacted', 'qualified', 'negotiation', 'won', 'lost', 'unqualified'];
        return view('admin.leads.create', compact('statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:leads,email',
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'status' => 'required|string|in:new,contacted,qualified,negotiation,won,lost,unqualified', // Adjust based on actual statuses
            'source' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            // 'assigned_to' => 'nullable|exists:users,id', // If leads can be assigned
        ]);

        $lead = Lead::create($request->all());

        return redirect()->route('admin.leads.index')
                         ->with('success', 'Lead criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lead $lead) // Route model binding
    {
        return view('admin.leads.show', compact('lead'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lead $lead) // Route model binding
    {
        $statuses = ['new', 'contacted', 'qualified', 'negotiation', 'won', 'lost', 'unqualified']; // Consistent statuses
        return view('admin.leads.edit', compact('lead', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lead $lead) // Route model binding
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:leads,email,' . $lead->id,
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'status' => 'required|string|in:new,contacted,qualified,negotiation,won,lost,unqualified', // Adjust based on actual statuses
            'source' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            // 'assigned_to' => 'nullable|exists:users,id',
        ]);

        $lead->update($request->all());

        return redirect()->route('admin.leads.index')
                         ->with('success', 'Lead atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead) // Route model binding
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')
                         ->with('success', 'Lead excluído com sucesso!');
    }
}
