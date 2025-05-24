<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class EcommerceController extends Controller
{
    /**
     * Homepage do E-commerce
     */
    public function index()
    {
        // Produtos em destaque
        $featuredProducts = Product::availableOnline()
            ->featured()
            ->with('category')
            ->limit(8)
            ->get();

        // Produtos em promoção
        $promotionalProducts = Product::availableOnline()
            ->onPromotion()
            ->with('category')
            ->limit(6)
            ->get();

        // Produtos mais recentes
        $latestProducts = Product::availableOnline()
            ->with('category')
            ->latest()
            ->limit(8)
            ->get();

        // Categorias principais
        $categories = Category::active()
            ->whereHas('products', function($query) {
                $query->availableOnline();
            })
            ->limit(8)
            ->get();

        return view('ecommerce.home', compact(
            'featuredProducts',
            'promotionalProducts', 
            'latestProducts',
            'categories'
        ));
    }

    /**
     * Listagem de produtos
     */
    public function products(Request $request)
    {
        $query = Product::availableOnline()->with('category');

        // Filtro por categoria
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filtro por busca
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filtro por preço
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filtro por promoção
        if ($request->filled('on_promotion') && $request->on_promotion == 1) {
            $query->whereNotNull('promotional_price')
                  ->where('promotion_start', '<=', now())
                  ->where('promotion_end', '>=', now());
        }

        // Ordenação
        switch ($request->get('sort', 'name_asc')) {
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        $products = $query->paginate(12)->withQueryString();
        
        $categories = Category::active()
            ->whereHas('products', function($query) {
                $query->availableOnline();
            })
            ->get();

        // Faixa de preços para o filtro
        $priceRange = Product::availableOnline()
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        return view('ecommerce.products', compact(
            'products',
            'categories',
            'priceRange'
        ));
    }

    /**
     * Detalhes do produto
     */
    public function productDetail(Product $product)
    {
        // Verificar se o produto está disponível para venda online
        if (!$product->online_sale || $product->status !== 'active') {
            abort(404);
        }

        $product->load('category');

        // Produtos relacionados (mesma categoria)
        $relatedProducts = Product::availableOnline()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('ecommerce.product-detail', compact('product', 'relatedProducts'));
    }

    /**
     * Adicionar ao carrinho
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::find($request->product_id);

        // Verificar se o produto está disponível
        if (!$product->online_sale || $product->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Produto não disponível para compra'
            ], 400);
        }

        // Verificar estoque
        if ($product->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Quantidade não disponível em estoque'
            ], 400);
        }

        // Obter carrinho atual
        $cart = Session::get('cart', []);
        $productId = $product->id;

        if (isset($cart[$productId])) {
            // Verificar se a nova quantidade não excede o estoque
            $newQuantity = $cart[$productId]['quantity'] + $request->quantity;
            if ($newQuantity > $product->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quantidade total excede o estoque disponível'
                ], 400);
            }
            $cart[$productId]['quantity'] = $newQuantity;
        } else {
            $cart[$productId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->current_price,
                'quantity' => $request->quantity,
                'image' => $product->main_image,
                'sku' => $product->sku
            ];
        }

        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Produto adicionado ao carrinho',
            'cart_count' => $this->getCartCount()
        ]);
    }

    /**
     * Ver carrinho
     */
    public function showCart()
    {
        $cart = Session::get('cart', []);
        $cartItems = [];
        $total = 0;

        foreach ($cart as $item) {
            $product = Product::with('category')->find($item['product_id']);
            if ($product && $product->online_sale && $product->status === 'active') {
                $itemTotal = $product->current_price * $item['quantity'];
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'total' => $itemTotal
                ];
                $total += $itemTotal;
            }
        }

        return view('ecommerce.cart', compact('cartItems', 'total'));
    }

    /**
     * Atualizar carrinho
     */
    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0'
        ]);

        $cart = Session::get('cart', []);
        $productId = $request->product_id;

        if ($request->quantity == 0) {
            unset($cart[$productId]);
        } else {
            $product = Product::find($productId);
            if ($request->quantity > $product->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quantidade não disponível em estoque'
                ], 400);
            }

            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] = $request->quantity;
            }
        }

        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'cart_count' => $this->getCartCount()
        ]);
    }

    /**
     * Remover do carrinho
     */
    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $cart = Session::get('cart', []);
        unset($cart[$request->product_id]);
        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'cart_count' => $this->getCartCount()
        ]);
    }

    /**
     * Limpar carrinho
     */
    public function clearCart()
    {
        Session::forget('cart');

        return response()->json([
            'success' => true,
            'cart_count' => 0
        ]);
    }

    /**
     * Busca de produtos (AJAX)
     */
    public function searchProducts(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::availableOnline()
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%");
            })
            ->limit(8)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->formatted_current_price,
                    'image' => $product->main_image,
                    'url' => route('ecommerce.product', $product->id)
                ];
            });

        return response()->json($products);
    }

    /**
     * Contar itens no carrinho
     */
    private function getCartCount()
    {
        $cart = Session::get('cart', []);
        return array_sum(array_column($cart, 'quantity'));
    }

    /**
     * Checkout
     */
    public function checkout()
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('ecommerce.cart')
                ->with('error', 'Seu carrinho está vazio');
        }

        $cartItems = [];
        $total = 0;

        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            if ($product && $product->online_sale && $product->status === 'active') {
                $itemTotal = $product->current_price * $item['quantity'];
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'total' => $itemTotal
                ];
                $total += $itemTotal;
            }
        }

        return view('ecommerce.checkout', compact('cartItems', 'total'));
    }

    /**
     * Create payment intent
     */
    public function createPaymentIntent(Request $request)
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return response()->json(['error' => 'Carrinho vazio'], 400);
        }

        $total = 0;
        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            if ($product && $product->online_sale && $product->status === 'active') {
                $total += $product->current_price * $item['quantity'];
            }
        }

        if ($total <= 0) {
            return response()->json(['error' => 'Total inválido'], 400);
        }

        try {
            Stripe::setApiKey(env('STRIPE_SECRET', 'sk_test_YOUR_STRIPE_SECRET'));

            $intent = PaymentIntent::create([
                'amount' => round($total * 100), // Convert to cents
                'currency' => 'brl',
                'metadata' => [
                    'customer_email' => $request->input('customer_data.email'),
                    'customer_name' => $request->input('customer_data.first_name') . ' ' . $request->input('customer_data.last_name'),
                ]
            ]);

            return response()->json([
                'client_secret' => $intent->client_secret
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Create order after successful payment
     */
    public function createOrder(Request $request)
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return response()->json(['error' => 'Carrinho vazio'], 400);
        }

        try {
            DB::beginTransaction();

            // Calculate total
            $total = 0;
            $cartItems = [];
            
            foreach ($cart as $item) {
                $product = Product::find($item['product_id']);
                if ($product && $product->online_sale && $product->status === 'active') {
                    
                    // Check stock
                    if ($product->stock_quantity < $item['quantity']) {
                        throw new \Exception("Produto {$product->name} não possui estoque suficiente");
                    }
                    
                    $itemTotal = $product->current_price * $item['quantity'];
                    $cartItems[] = [
                        'product' => $product,
                        'quantity' => $item['quantity'],
                        'price' => $product->current_price,
                        'total' => $itemTotal
                    ];
                    $total += $itemTotal;
                }
            }

            // Create or find customer
            $customerData = $request->input('customer_data');
            $customer = Customer::where('email', $customerData['email'])->first();
            
            if (!$customer) {
                $customer = Customer::create([
                    'name' => $customerData['first_name'] . ' ' . $customerData['last_name'],
                    'email' => $customerData['email'],
                    'phone' => $customerData['phone'],
                    'cpf' => $customerData['cpf'],
                    'status' => 'active',
                    'password' => bcrypt('123456'), // Default password
                ]);
            }

            // Create order
            $order = Order::create([
                'customer_id' => $customer->id,
                'order_number' => 'WEB-' . strtoupper(uniqid()),
                'status' => 'pending',
                'payment_status' => 'paid',
                'payment_method' => 'card',
                'total_amount' => $total,
                'shipping_address_json' => json_encode([
                    'address' => $customerData['address'],
                    'number' => $customerData['number'],
                    'complement' => $customerData['complement'],
                    'neighborhood' => $customerData['neighborhood'],
                    'city' => $customerData['city'],
                    'state' => $customerData['state'],
                    'zip_code' => $customerData['zip_code'],
                ]),
                'notes' => $customerData['notes'] ?? null,
                'stripe_payment_intent_id' => $request->input('payment_intent_id'),
            ]);

            // Create order items and update stock
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['total'],
                ]);

                // Update stock
                $item['product']->decrement('stock_quantity', $item['quantity']);
            }

            // Clear cart
            Session::forget('cart');

            DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Order success page
     */
    public function orderSuccess(Request $request)
    {
        $orderId = $request->get('order');
        $order = Order::with(['customer', 'items.product'])->find($orderId);

        if (!$order) {
            return redirect()->route('ecommerce.home')->with('error', 'Pedido não encontrado');
        }

        return view('ecommerce.order-success', compact('order'));
    }
} 