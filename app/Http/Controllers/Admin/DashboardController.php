<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Lead;
use App\Models\Purchase;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Estatísticas gerais
        $stats = [
            'total_customers' => Customer::count(),
            'active_customers' => Customer::active()->count(),
            'total_categories' => Category::count(),
            'active_categories' => Category::active()->count(),
            'total_products' => Product::count(),
            'active_products' => Product::active()->count(),
            'low_stock_products' => Product::where('stock_quantity', '<=', 10)->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::pending()->count(),
            'total_leads' => 0, // Lead::count(),
            'new_leads' => 0, // Lead::new()->count(),
            'total_purchases' => 0, // Purchase::count(),
            'draft_purchases' => 0, // Purchase::where('status', 'draft')->count(),
        ];

        // Vendas do mês
        $monthlyRevenue = Order::whereMonth('created_at', now()->month)
            ->where('payment_status', 'paid')
            ->sum('total');

        // Pedidos recentes
        $recentOrders = Order::with(['customer'])->latest()->take(5)->get();

        // Produtos recentes
        $recentProducts = Product::with('category')
            ->latest()
            ->take(5)
            ->get();

        // Categorias recentes
        $recentCategories = Category::withCount('products')
            ->latest()
            ->take(5)
            ->get();

        // Produtos com baixo estoque
        $lowStockProducts = Product::where('stock_quantity', '<=', 10)
            ->with('category')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'monthlyRevenue',
            'recentOrders',
            'recentProducts',
            'recentCategories',
            'lowStockProducts'
        ));
    }
}
