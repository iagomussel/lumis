<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\AccountReceivable;
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
            'payment_method' => 'required|in:money,credit_card,debit_card,pix,bank_transfer,bank_slip,check',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'partial_payment' => 'nullable|array',
            'partial_payment.isPartial' => 'boolean',
            'partial_payment.paidAmount' => 'nullable|numeric|min:0',
            'partial_payment.remainingAmount' => 'nullable|numeric|min:0',
            'partial_payment.totalAmount' => 'nullable|numeric|min:0',
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

            // Verificar dados do pagamento parcial
            $partialPayment = $request->partial_payment;
            $isPartialPayment = $partialPayment && $partialPayment['isPartial'] ?? false;
            
            // Validar pagamento parcial
            if ($isPartialPayment) {
                $paidAmount = $partialPayment['paidAmount'] ?? 0;
                if ($paidAmount <= 0 || $paidAmount > $total) {
                    throw new \Exception("Valor do sinal inválido. Deve ser entre R$ 0,01 e R$ " . number_format($total, 2, ',', '.'));
                }
            }

            // Definir status do pagamento
            $paymentStatus = $isPartialPayment ? 'partially_paid' : 'paid';

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
                'payment_status' => $paymentStatus,
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

            // Se é pagamento parcial, criar conta a receber
            if ($isPartialPayment && $request->customer_id) {
                $paidAmount = $partialPayment['paidAmount'];
                $remainingAmount = $total - $paidAmount;

                \App\Models\AccountReceivable::create([
                    'customer_id' => $request->customer_id,
                    'order_id' => $order->id,
                    'description' => "Saldo - Pedido {$order->order_number}",
                    'amount' => $remainingAmount,
                    'paid_amount' => 0,
                    'due_date' => now()->addDays(30), // 30 dias para pagamento
                    'status' => 'pending',
                ]);
            }

            DB::commit();

            $responseMessage = $isPartialPayment 
                ? "Venda realizada! Sinal recebido: R$ " . number_format($partialPayment['paidAmount'], 2, ',', '.') . ". Saldo para entrega: R$ " . number_format($total - $partialPayment['paidAmount'], 2, ',', '.')
                : 'Venda realizada com sucesso!';

            return response()->json([
                'success' => true,
                'order' => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'total' => $order->total,
                    'formatted_total' => $order->formatted_total,
                    'payment_status' => $paymentStatus,
                    'is_partial' => $isPartialPayment,
                    'paid_amount' => $isPartialPayment ? $partialPayment['paidAmount'] : $total,
                    'remaining_amount' => $isPartialPayment ? ($total - $partialPayment['paidAmount']) : 0,
                ],
                'message' => $responseMessage
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
