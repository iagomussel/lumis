<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the customer dashboard.
     */
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        
        // Estatísticas do cliente
        $totalOrders = $customer->orders()->count();
        $totalSpent = $customer->orders()->where('status', 'completed')->sum('total_amount');
        $pendingOrders = $customer->orders()->where('status', 'pending')->count();
        $recentOrders = $customer->orders()->with('items.product')->latest()->limit(5)->get();

        return view('customer.dashboard', compact(
            'customer',
            'totalOrders',
            'totalSpent',
            'pendingOrders',
            'recentOrders'
        ));
    }
} 