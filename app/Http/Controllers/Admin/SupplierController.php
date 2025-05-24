<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers.
     */
    public function index(Request $request)
    {
        $query = Supplier::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('cnpj', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort
        $sortField = $request->get('sort', 'name');
        $sortDirection = $request->get('direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        $suppliers = $query->paginate(15)->withQueryString();

        return view('admin.suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function create()
    {
        return view('admin.suppliers.create');
    }

    /**
     * Store a newly created supplier in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:suppliers,email',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'cnpj' => 'nullable|string|max:18|unique:suppliers,cnpj',
            'state_registration' => 'nullable|string|max:20',
            'municipal_registration' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'address_number' => 'nullable|string|max:10',
            'complement' => 'nullable|string|max:100',
            'neighborhood' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'zip_code' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive,blocked',
            'contact_person' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'notes' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'payment_terms' => 'nullable|string|max:255',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();
            
            $supplier = Supplier::create($validated);
            
            DB::commit();

            return redirect()->route('admin.suppliers.index')
                ->with('success', 'Fornecedor criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Erro ao criar fornecedor: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified supplier.
     */
    public function show(Supplier $supplier)
    {
        $supplier->load(['purchases' => function($query) {
            $query->latest()->limit(10);
        }]);

        // Statistics
        $stats = [
            'total_purchases' => $supplier->purchases()->count(),
            'total_amount' => $supplier->purchases()->sum('total'),
            'pending_purchases' => $supplier->purchases()->where('status', 'pending')->count(),
            'last_purchase' => $supplier->purchases()->latest()->first()?->created_at,
        ];

        return view('admin.suppliers.show', compact('supplier', 'stats'));
    }

    /**
     * Show the form for editing the specified supplier.
     */
    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:suppliers,email,' . $supplier->id,
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'cnpj' => 'nullable|string|max:18|unique:suppliers,cnpj,' . $supplier->id,
            'state_registration' => 'nullable|string|max:20',
            'municipal_registration' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'address_number' => 'nullable|string|max:10',
            'complement' => 'nullable|string|max:100',
            'neighborhood' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'zip_code' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive,blocked',
            'contact_person' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'notes' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'payment_terms' => 'nullable|string|max:255',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();
            
            $supplier->update($validated);
            
            DB::commit();

            return redirect()->route('admin.suppliers.index')
                ->with('success', 'Fornecedor atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Erro ao atualizar fornecedor: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified supplier from storage.
     */
    public function destroy(Supplier $supplier)
    {
        try {
            // Check if supplier has purchases
            if ($supplier->purchases()->count() > 0) {
                return back()->with('error', 'Não é possível excluir fornecedor com compras associadas.');
            }

            $supplier->delete();

            return redirect()->route('admin.suppliers.index')
                ->with('success', 'Fornecedor excluído com sucesso!');

        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir fornecedor: ' . $e->getMessage());
        }
    }

    /**
     * Toggle supplier status
     */
    public function toggleStatus(Supplier $supplier)
    {
        $newStatus = $supplier->status === 'active' ? 'inactive' : 'active';
        $supplier->update(['status' => $newStatus]);

        $message = $newStatus === 'active' ? 'Fornecedor ativado!' : 'Fornecedor desativado!';
        
        return back()->with('success', $message);
    }
}
