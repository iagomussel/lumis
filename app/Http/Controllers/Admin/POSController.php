<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class POSController extends Controller
{
    /**
     * Display the POS interface
     */
    public function index()
    {
        $customers = Customer::active()
            ->select('id', 'name', 'email', 'type', 'document')
            ->orderBy('name')
            ->limit(100)
            ->get();

        return view('admin.pos.index', compact('customers'));
    }

    /**
     * Search products for POS
     */
    public function searchProducts(Request $request)
    {
        $search = $request->get('q');
        
        $products = Product::active()
            ->with('category')
            ->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->where('stock_quantity', '>', 0)
            ->limit(20)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $product->price,
                    'formatted_price' => 'R$ ' . number_format($product->price, 2, ',', '.'),
                    'stock_quantity' => $product->stock_quantity,
                    'category' => $product->category ? $product->category->name : null,
                    'image' => null, // Pode ser implementado depois
                ];
            });

        return response()->json($products);
    }

    /**
     * Get product details
     */
    public function getProduct(Product $product)
    {
        if ($product->status !== 'active' || $product->stock_quantity <= 0) {
            return response()->json(['error' => 'Produto indisponível'], 400);
        }

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'price' => $product->price,
            'formatted_price' => 'R$ ' . number_format($product->price, 2, ',', '.'),
            'stock_quantity' => $product->stock_quantity,
            'category' => $product->category ? $product->category->name : null,
            'description' => $product->description,
        ]);
    }

    /**
     * Process sale
     */
    public function processSale(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,pix,bank_transfer',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            // Verificar disponibilidade de estoque
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Produto {$product->name} tem estoque insuficiente. Disponível: {$product->stock_quantity}");
                }
            }

            // Calcular totais
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }

            $discount = $request->discount ?? 0;
            $total = $subtotal - $discount;

            // Criar pedido
            $order = Order::create([
                'customer_id' => $request->customer_id,
                'user_id' => Auth::id(),
                'status' => 'confirmed',
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => 0,
                'shipping' => 0,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'paid',
                'notes' => $request->notes,
            ]);

            // Criar itens do pedido e atualizar estoque
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                ]);

                // Atualizar estoque
                $product->decrement('stock_quantity', $item['quantity']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'order' => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'total' => $order->total,
                    'formatted_total' => $order->formatted_total,
                ],
                'message' => 'Venda realizada com sucesso!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Print receipt
     */
    public function printReceipt(Order $order)
    {
        $order->load(['customer', 'items.product', 'user']);
        
        return view('admin.pos.receipt', compact('order'));
    }

    /**
     * Get customer by document/name
     */
    public function searchCustomers(Request $request)
    {
        $search = $request->get('q');
        
        $customers = Customer::active()
            ->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('document', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get()
            ->map(function($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'document' => $customer->document,
                    'type' => $customer->type,
                    'display_name' => $customer->name . ' (' . $customer->document . ')',
                ];
            });

        return response()->json($customers);
    }
}
