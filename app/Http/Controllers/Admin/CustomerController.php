<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('document', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $customers = $query->latest()->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'document' => 'nullable|string|max:20',
            'document_type' => 'nullable|in:cpf,cnpj',
            'type' => 'required|in:individual,company',
            'company_name' => 'nullable|string|max:255',
            'state_registration' => 'nullable|string|max:50',
            'municipal_registration' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'address_number' => 'nullable|string|max:20',
            'complement' => 'nullable|string|max:100',
            'neighborhood' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'zip_code' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive,blocked',
            'credit_limit' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:M,F,O',
        ]);

        $customer = Customer::create($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Cliente criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $customer->load('orders');
        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'document' => 'nullable|string|max:20',
            'document_type' => 'nullable|in:cpf,cnpj',
            'type' => 'required|in:individual,company',
            'company_name' => 'nullable|string|max:255',
            'state_registration' => 'nullable|string|max:50',
            'municipal_registration' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'address_number' => 'nullable|string|max:20',
            'complement' => 'nullable|string|max:100',
            'neighborhood' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'zip_code' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive,blocked',
            'credit_limit' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:M,F,O',
        ]);

        $customer->update($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Cliente atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        // Verificar se o cliente tem pedidos antes de excluir
        if ($customer->orders()->exists()) {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Não é possível excluir um cliente que possui pedidos.');
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Cliente excluído com sucesso!');
    }
}
