<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\Lead;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SearchController extends Controller
{
    public function global(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'results' => [],
                'message' => 'Digite pelo menos 2 caracteres para buscar'
            ]);
        }

        $results = collect();

        // Buscar em Clientes
        $customers = Customer::where('name', 'like', "%{$query}%")
                            ->orWhere('email', 'like', "%{$query}%")
                            ->orWhere('phone', 'like', "%{$query}%")
                            ->orWhere('document', 'like', "%{$query}%")
                            ->limit(5)
                            ->get()
                            ->map(function ($customer) {
                                return [
                                    'type' => 'customer',
                                    'type_label' => 'Cliente',
                                    'title' => $customer->name,
                                    'subtitle' => $customer->email,
                                    'description' => $customer->phone,
                                    'url' => route('admin.customers.show', $customer),
                                    'icon' => 'ti-user',
                                    'color' => 'primary'
                                ];
                            });

        // Buscar em Produtos
        $products = Product::where('name', 'like', "%{$query}%")
                          ->orWhere('sku', 'like', "%{$query}%")
                          ->orWhere('description', 'like', "%{$query}%")
                          ->with('category')
                          ->limit(5)
                          ->get()
                          ->map(function ($product) {
                              return [
                                  'type' => 'product',
                                  'type_label' => 'Produto',
                                  'title' => $product->name,
                                  'subtitle' => 'SKU: ' . $product->sku,
                                  'description' => $product->category?->name . ' • R$ ' . number_format($product->price, 2, ',', '.'),
                                  'url' => route('admin.products.show', $product),
                                  'icon' => 'ti-package',
                                  'color' => 'success'
                              ];
                          });

        // Buscar em Pedidos
        $orders = Order::where('id', 'like', "%{$query}%")
                      ->orWhereHas('customer', function ($q) use ($query) {
                          $q->where('name', 'like', "%{$query}%")
                            ->orWhere('email', 'like', "%{$query}%");
                      })
                      ->with('customer')
                      ->limit(5)
                      ->get()
                      ->map(function ($order) {
                          return [
                              'type' => 'order',
                              'type_label' => 'Pedido',
                              'title' => 'Pedido #' . $order->id,
                              'subtitle' => $order->customer->name,
                              'description' => 'R$ ' . number_format($order->total, 2, ',', '.') . ' • ' . ucfirst($order->status),
                              'url' => route('admin.orders.show', $order),
                              'icon' => 'ti-shopping-cart',
                              'color' => 'info'
                          ];
                      });

        // Buscar em Leads
        $leads = Lead::where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('phone', 'like', "%{$query}%")
                    ->orWhere('company', 'like', "%{$query}%")
                    ->limit(5)
                    ->get()
                    ->map(function ($lead) {
                        return [
                            'type' => 'lead',
                            'type_label' => 'Lead',
                            'title' => $lead->name,
                            'subtitle' => $lead->email,
                            'description' => ($lead->company ? $lead->company . ' • ' : '') . ucfirst($lead->status),
                            'url' => route('admin.leads.show', $lead),
                            'icon' => 'ti-target',
                            'color' => 'warning'
                        ];
                    });

        // Buscar em Fornecedores
        $suppliers = Supplier::where('name', 'like', "%{$query}%")
                            ->orWhere('email', 'like', "%{$query}%")
                            ->orWhere('phone', 'like', "%{$query}%")
                            ->orWhere('document', 'like', "%{$query}%")
                            ->limit(5)
                            ->get()
                            ->map(function ($supplier) {
                                return [
                                    'type' => 'supplier',
                                    'type_label' => 'Fornecedor',
                                    'title' => $supplier->name,
                                    'subtitle' => $supplier->email,
                                    'description' => $supplier->phone,
                                    'url' => route('admin.suppliers.show', $supplier),
                                    'icon' => 'ti-truck',
                                    'color' => 'secondary'
                                ];
                            });

        // Buscar em Compras
        $purchases = Purchase::where('id', 'like', "%{$query}%")
                            ->orWhereHas('supplier', function ($q) use ($query) {
                                $q->where('name', 'like', "%{$query}%");
                            })
                            ->with('supplier')
                            ->limit(5)
                            ->get()
                            ->map(function ($purchase) {
                                return [
                                    'type' => 'purchase',
                                    'type_label' => 'Compra',
                                    'title' => 'Compra #' . $purchase->id,
                                    'subtitle' => $purchase->supplier->name,
                                    'description' => 'R$ ' . number_format($purchase->total, 2, ',', '.') . ' • ' . ucfirst($purchase->status),
                                    'url' => route('admin.purchases.show', $purchase),
                                    'icon' => 'ti-shopping-bag',
                                    'color' => 'dark'
                                ];
                            });

        // Buscar em Categorias
        $categories = Category::where('name', 'like', "%{$query}%")
                             ->orWhere('description', 'like', "%{$query}%")
                             ->withCount('products')
                             ->limit(5)
                             ->get()
                             ->map(function ($category) {
                                 return [
                                     'type' => 'category',
                                     'type_label' => 'Categoria',
                                     'title' => $category->name,
                                     'subtitle' => $category->description,
                                     'description' => $category->products_count . ' produto(s)',
                                     'url' => route('admin.categories.show', $category),
                                     'icon' => 'ti-category',
                                     'color' => 'light'
                                 ];
                             });

        // Combinar todos os resultados
        $results = $results->concat($customers)
                          ->concat($products)
                          ->concat($orders)
                          ->concat($leads)
                          ->concat($suppliers)
                          ->concat($purchases)
                          ->concat($categories);

        // Agrupar por tipo
        $groupedResults = $results->groupBy('type')->map(function ($items, $type) {
            return [
                'type' => $type,
                'type_label' => $items->first()['type_label'],
                'count' => $items->count(),
                'items' => $items->values()
            ];
        })->values();

        return response()->json([
            'query' => $query,
            'total_results' => $results->count(),
            'results' => $groupedResults,
            'message' => $results->count() > 0 ? null : 'Nenhum resultado encontrado'
        ]);
    }

    public function page(Request $request)
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'all');
        
        if (strlen($query) < 2) {
            return redirect()->route('admin.dashboard')
                           ->with('error', 'Digite pelo menos 2 caracteres para buscar');
        }

        $results = collect();
        $totalResults = 0;

        // Buscar baseado no tipo
        if ($type === 'all' || $type === 'customers') {
            $customers = Customer::where('name', 'like', "%{$query}%")
                                ->orWhere('email', 'like', "%{$query}%")
                                ->orWhere('phone', 'like', "%{$query}%")
                                ->orWhere('document', 'like', "%{$query}%")
                                ->paginate(10, ['*'], 'customers_page');
            
            if ($type === 'customers') {
                return view('admin.search.results', [
                    'query' => $query,
                    'type' => $type,
                    'results' => $customers,
                    'title' => 'Clientes'
                ]);
            }
            
            $totalResults += $customers->total();
        }

        if ($type === 'all' || $type === 'products') {
            $products = Product::where('name', 'like', "%{$query}%")
                              ->orWhere('sku', 'like', "%{$query}%")
                              ->orWhere('description', 'like', "%{$query}%")
                              ->with('category')
                              ->paginate(10, ['*'], 'products_page');
            
            if ($type === 'products') {
                return view('admin.search.results', [
                    'query' => $query,
                    'type' => $type,
                    'results' => $products,
                    'title' => 'Produtos'
                ]);
            }
            
            $totalResults += $products->total();
        }

        if ($type === 'all' || $type === 'orders') {
            $orders = Order::where('id', 'like', "%{$query}%")
                          ->orWhereHas('customer', function ($q) use ($query) {
                              $q->where('name', 'like', "%{$query}%")
                                ->orWhere('email', 'like', "%{$query}%");
                          })
                          ->with('customer')
                          ->paginate(10, ['*'], 'orders_page');
            
            if ($type === 'orders') {
                return view('admin.search.results', [
                    'query' => $query,
                    'type' => $type,
                    'results' => $orders,
                    'title' => 'Pedidos'
                ]);
            }
            
            $totalResults += $orders->total();
        }

        // Para busca geral, mostrar resumo
        return view('admin.search.overview', [
            'query' => $query,
            'customers' => $customers ?? collect(),
            'products' => $products ?? collect(),
            'orders' => $orders ?? collect(),
            'total_results' => $totalResults
        ]);
    }
} 