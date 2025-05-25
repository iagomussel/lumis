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
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Estatísticas gerais
        $stats = [
            'total_customers' => Customer::count(),
            'active_customers' => Customer::active()->count(),
            'total_categories' => Category::count(),
            'ecommerce_categories' => Category::ecommerce()->count(),
            'internal_categories' => Category::internal()->count(),
            'active_categories' => Category::active()->count(),
            'total_products' => Product::count(),
            'ecommerce_products' => Product::whereHas('category', function($q) { $q->ecommerce(); })->count(),
            'internal_products' => Product::whereHas('category', function($q) { $q->internal(); })->count(),
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

        // Cálculos de ROI por categoria
        $roiData = $this->calculateROIData();

        // Inventário
        $inventoryData = $this->calculateInventoryData();

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

        // Top categorias por ROI
        $topROICategories = Category::active()
            ->with('products')
            ->get()
            ->map(function($category) {
                return [
                    'category' => $category,
                    'roi' => $category->getROI(),
                    'total_cost' => $category->getTotalCost(),
                    'total_value' => $category->getTotalValue(),
                    'usage' => $category->usage_display
                ];
            })
            ->sortByDesc('roi')
            ->take(10);

        return view('admin.dashboard', compact(
            'stats',
            'monthlyRevenue',
            'roiData',
            'inventoryData',
            'recentOrders',
            'recentProducts',
            'recentCategories',
            'lowStockProducts',
            'topROICategories'
        ));
    }

    private function calculateROIData()
    {
        // ROI por tipo de categoria
        $ecommerceROI = $this->getCategoryTypeROI(true); // E-commerce
        $internalROI = $this->getCategoryTypeROI(false); // Interno

        // ROI por tipo de produto (insumo vs ativo)
        $insumosROI = $this->getProductTypeROI('insumo');
        $ativosROI = $this->getProductTypeROI('ativo');

        return [
            'ecommerce' => $ecommerceROI,
            'internal' => $internalROI,
            'insumos' => $insumosROI,
            'ativos' => $ativosROI
        ];
    }

    private function getCategoryTypeROI($isEcommerce)
    {
        $query = Product::whereHas('category', function($q) use ($isEcommerce) {
            $q->where('show_in_ecommerce', $isEcommerce);
        });

        $totalValue = $query->sum(DB::raw('price * stock_quantity'));
        $totalCost = $query->sum(DB::raw('cost_price * stock_quantity'));
        $totalProducts = $query->count();

        $roi = $totalCost > 0 ? round((($totalValue - $totalCost) / $totalCost) * 100, 2) : 0;

        return [
            'total_value' => $totalValue,
            'total_cost' => $totalCost,
            'total_products' => $totalProducts,
            'roi' => $roi,
            'profit' => $totalValue - $totalCost
        ];
    }

    private function getProductTypeROI($type)
    {
        $query = Product::whereHas('category', function($q) use ($type) {
            $q->where('type', $type);
        });

        $totalValue = $query->sum(DB::raw('price * stock_quantity'));
        $totalCost = $query->sum(DB::raw('cost_price * stock_quantity'));
        $totalProducts = $query->count();

        $roi = $totalCost > 0 ? round((($totalValue - $totalCost) / $totalCost) * 100, 2) : 0;

        return [
            'total_value' => $totalValue,
            'total_cost' => $totalCost,
            'total_products' => $totalProducts,
            'roi' => $roi,
            'profit' => $totalValue - $totalCost
        ];
    }

    private function calculateInventoryData()
    {
        return [
            'total_inventory_value' => Product::sum(DB::raw('price * stock_quantity')),
            'total_inventory_cost' => Product::sum(DB::raw('cost_price * stock_quantity')),
            'ecommerce_inventory_value' => Product::whereHas('category', function($q) {
                $q->ecommerce();
            })->sum(DB::raw('price * stock_quantity')),
            'internal_inventory_value' => Product::whereHas('category', function($q) {
                $q->internal();
            })->sum(DB::raw('price * stock_quantity')),
            'low_stock_value' => Product::where('stock_quantity', '<=', 10)
                ->sum(DB::raw('price * stock_quantity')),
        ];
    }
}
