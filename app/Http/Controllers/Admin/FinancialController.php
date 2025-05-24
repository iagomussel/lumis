<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountReceivable;
use App\Models\AccountPayable;
use App\Models\FinancialTransaction;
use App\Models\Order;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinancialController extends Controller
{
    /**
     * Financial Dashboard
     */
    public function dashboard()
    {
        $currentMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();

        // Cash Flow Summary
        $cashFlow = [
            'total_balance' => Account::active()->sum('current_balance'),
            'monthly_income' => FinancialTransaction::income()
                ->where('reference_date', '>=', $currentMonth)
                ->sum('amount'),
            'monthly_expenses' => FinancialTransaction::expense()
                ->where('reference_date', '>=', $currentMonth)
                ->sum('amount'),
            'pending_receivables' => AccountReceivable::pending()->sum('amount'),
            'pending_payables' => AccountPayable::pending()->sum('amount'),
        ];

        // Overdue items
        $overdue = [
            'receivables' => AccountReceivable::overdue()->count(),
            'payables' => AccountPayable::overdue()->count(),
        ];

        // Recent transactions
        $recentTransactions = FinancialTransaction::with(['account', 'user'])
            ->latest()
            ->limit(10)
            ->get();

        // Monthly comparison
        $monthlyComparison = [
            'current_income' => $cashFlow['monthly_income'],
            'last_income' => FinancialTransaction::income()
                ->whereBetween('reference_date', [$lastMonth, $currentMonth])
                ->sum('amount'),
            'current_expenses' => $cashFlow['monthly_expenses'],
            'last_expenses' => FinancialTransaction::expense()
                ->whereBetween('reference_date', [$lastMonth, $currentMonth])
                ->sum('amount'),
        ];

        return view('admin.financial.dashboard', compact(
            'cashFlow',
            'overdue',
            'recentTransactions',
            'monthlyComparison'
        ));
    }

    /**
     * Accounts Management
     */
    public function accounts(Request $request)
    {
        $query = Account::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('bank_name', 'like', "%{$search}%")
                  ->orWhere('account_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $accounts = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.financial.accounts.index', compact('accounts'));
    }

    /**
     * Store new account
     */
    public function storeAccount(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:checking,savings,cash,credit_card,other',
            'bank_name' => 'nullable|string|max:255',
            'agency' => 'nullable|string|max:20',
            'account_number' => 'nullable|string|max:50',
            'initial_balance' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $account = Account::create([
                ...$validated,
                'current_balance' => $validated['initial_balance'],
                'status' => 'active'
            ]);

            // Create initial transaction if balance > 0
            if ($validated['initial_balance'] != 0) {
                FinancialTransaction::create([
                    'account_id' => $account->id,
                    'type' => $validated['initial_balance'] > 0 ? 'income' : 'expense',
                    'category' => 'initial_balance',
                    'amount' => abs($validated['initial_balance']),
                    'description' => 'Saldo inicial da conta',
                    'reference_date' => now(),
                    'status' => 'processed',
                    'user_id' => auth()->id(),
                ]);
            }

            DB::commit();

            return back()->with('success', 'Conta criada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Erro ao criar conta: ' . $e->getMessage());
        }
    }

    /**
     * Accounts Receivable
     */
    public function receivables(Request $request)
    {
        $query = AccountReceivable::with(['customer', 'order', 'account']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('due_date_from')) {
            $query->where('due_date', '>=', $request->due_date_from);
        }

        if ($request->filled('due_date_to')) {
            $query->where('due_date', '<=', $request->due_date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $receivables = $query->orderBy('due_date')->paginate(15)->withQueryString();

        // Summary
        $summary = [
            'total' => AccountReceivable::sum('amount'),
            'pending' => AccountReceivable::pending()->sum('amount'),
            'overdue' => AccountReceivable::overdue()->sum('amount'),
            'paid' => AccountReceivable::paid()->sum('amount'),
        ];

        return view('admin.financial.receivables.index', compact('receivables', 'summary'));
    }

    /**
     * Accounts Payable
     */
    public function payables(Request $request)
    {
        $query = AccountPayable::with(['supplier', 'purchase', 'account']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('due_date_from')) {
            $query->where('due_date', '>=', $request->due_date_from);
        }

        if ($request->filled('due_date_to')) {
            $query->where('due_date', '<=', $request->due_date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function($supplierQuery) use ($search) {
                      $supplierQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $payables = $query->orderBy('due_date')->paginate(15)->withQueryString();

        // Summary
        $summary = [
            'total' => AccountPayable::sum('amount'),
            'pending' => AccountPayable::pending()->sum('amount'),
            'overdue' => AccountPayable::overdue()->sum('amount'),
            'paid' => AccountPayable::paid()->sum('amount'),
        ];

        return view('admin.financial.payables.index', compact('payables', 'summary'));
    }

    /**
     * Process payment for receivable
     */
    public function payReceivable(Request $request, AccountReceivable $receivable)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01|max:' . $receivable->remaining_amount,
            'payment_method' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Update receivable
            $receivable->paid_amount += $validated['amount'];
            $receivable->payment_date = now();
            $receivable->payment_method = $validated['payment_method'];
            $receivable->account_id = $validated['account_id'];
            
            if ($receivable->paid_amount >= $receivable->amount) {
                $receivable->status = 'paid';
            } else {
                $receivable->status = 'partial';
            }
            
            $receivable->save();

            // Create financial transaction
            FinancialTransaction::create([
                'account_id' => $validated['account_id'],
                'type' => 'income',
                'category' => 'receivable_payment',
                'amount' => $validated['amount'],
                'description' => "Recebimento: {$receivable->description}",
                'reference_date' => now(),
                'status' => 'processed',
                'payment_method' => $validated['payment_method'],
                'transactionable_id' => $receivable->id,
                'transactionable_type' => AccountReceivable::class,
                'user_id' => auth()->id(),
                'notes' => $validated['notes'],
            ]);

            DB::commit();

            return back()->with('success', 'Pagamento registrado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao processar pagamento: ' . $e->getMessage());
        }
    }

    /**
     * Process payment for payable
     */
    public function payPayable(Request $request, AccountPayable $payable)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01|max:' . $payable->remaining_amount,
            'payment_method' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Update payable
            $payable->paid_amount += $validated['amount'];
            $payable->payment_date = now();
            $payable->payment_method = $validated['payment_method'];
            $payable->account_id = $validated['account_id'];
            
            if ($payable->paid_amount >= $payable->amount) {
                $payable->status = 'paid';
            } else {
                $payable->status = 'partial';
            }
            
            $payable->save();

            // Create financial transaction
            FinancialTransaction::create([
                'account_id' => $validated['account_id'],
                'type' => 'expense',
                'category' => 'payable_payment',
                'amount' => $validated['amount'],
                'description' => "Pagamento: {$payable->description}",
                'reference_date' => now(),
                'status' => 'processed',
                'payment_method' => $validated['payment_method'],
                'transactionable_id' => $payable->id,
                'transactionable_type' => AccountPayable::class,
                'user_id' => auth()->id(),
                'notes' => $validated['notes'],
            ]);

            DB::commit();

            return back()->with('success', 'Pagamento registrado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao processar pagamento: ' . $e->getMessage());
        }
    }

    /**
     * Cash Flow Report
     */
    public function cashFlow(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        $transactions = FinancialTransaction::with(['account'])
            ->whereBetween('reference_date', [$startDate, $endDate])
            ->orderBy('reference_date')
            ->get();

        $dailyFlow = $transactions->groupBy(function($transaction) {
            return $transaction->reference_date->format('Y-m-d');
        })->map(function($dayTransactions) {
            return [
                'income' => $dayTransactions->where('type', 'income')->sum('amount'),
                'expense' => $dayTransactions->where('type', 'expense')->sum('amount'),
                'balance' => $dayTransactions->sum('signed_amount'),
            ];
        });

        return view('admin.financial.cash-flow', compact('transactions', 'dailyFlow', 'startDate', 'endDate'));
    }
}
