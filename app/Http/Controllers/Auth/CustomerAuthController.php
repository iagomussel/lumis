<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;

class CustomerAuthController extends Controller
{
    /**
     * Display the customer login view.
     */
    public function showLogin()
    {
        return view('auth.customer.login');
    }

    /**
     * Handle a customer login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::guard('customer')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('customer.dashboard'));
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ])->onlyInput('email');
    }

    /**
     * Display the customer registration view.
     */
    public function showRegister()
    {
        return view('auth.customer.register');
    }

    /**
     * Handle a customer registration request.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:customers'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'type' => ['required', 'in:individual,company'],
            'document' => ['nullable', 'string', 'max:20'],
            'document_type' => ['nullable', 'in:cpf,cnpj'],
            'phone' => ['nullable', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date'],
            'company_name' => ['nullable', 'string', 'max:255'],
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
            'document' => $request->document,
            'document_type' => $request->document_type,
            'phone' => $request->phone,
            'birth_date' => $request->birth_date,
            'company_name' => $request->company_name,
            'status' => 'active',
            'credit_limit' => 0,
            'current_balance' => 0,
        ]);

        event(new Registered($customer));

        Auth::guard('customer')->login($customer);

        return redirect(route('customer.dashboard'));
    }

    /**
     * Destroy a customer session (logout).
     */
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
