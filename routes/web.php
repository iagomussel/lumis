<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\POSController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EcommerceController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', [HomeController::class, 'index'])->name('home');

// E-commerce Routes
Route::prefix('loja')->name('ecommerce.')->group(function () {
    Route::get('/', [EcommerceController::class, 'index'])->name('home');
    Route::get('/produtos', [EcommerceController::class, 'products'])->name('products');
    Route::get('/produto/{product}', [EcommerceController::class, 'productDetail'])->name('product');
    Route::get('/buscar', [EcommerceController::class, 'searchProducts'])->name('search');
    
    // Cart routes
    Route::get('/carrinho', [EcommerceController::class, 'showCart'])->name('cart');
    Route::prefix('carrinho')->name('cart.')->group(function () {
        Route::post('/adicionar', [EcommerceController::class, 'addToCart'])->name('add');
        Route::patch('/atualizar', [EcommerceController::class, 'updateCart'])->name('update');
        Route::delete('/remover', [EcommerceController::class, 'removeFromCart'])->name('remove');
        Route::delete('/limpar', [EcommerceController::class, 'clearCart'])->name('clear');
        Route::get('/count', function() {
            $cart = Session::get('cart', []);
            return response()->json(['count' => array_sum(array_column($cart, 'quantity'))]);
        })->name('count');
    });
    
    Route::get('/checkout', [EcommerceController::class, 'checkout'])->name('checkout');
    
    // Payment routes
    Route::post('/payment/intent', [EcommerceController::class, 'createPaymentIntent'])->name('payment.intent');
    Route::post('/order/create', [EcommerceController::class, 'createOrder'])->name('order.create');
    Route::get('/order/success', [EcommerceController::class, 'orderSuccess'])->name('order.success');
});

// Customer Authentication Routes
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'login']);
    Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [CustomerAuthController::class, 'register']);
    Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');
    
    // Protected Customer Routes
    Route::middleware('customer.auth')->group(function () {
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Gestão de Categorias
    Route::resource('categories', CategoryController::class);
    
    // Gestão de Produtos
    Route::resource('products', ProductController::class);
    
    // Gestão de Clientes
    Route::resource('customers', CustomerController::class);
    
    // Gestão de Pedidos
    Route::resource('orders', OrderController::class);
    Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('orders/{order}/mark-as-paid', [OrderController::class, 'markAsPaid'])->name('orders.mark-as-paid');
    Route::get('orders/{order}/print', [OrderController::class, 'print'])->name('orders.print');
    
    // PDV (Ponto de Venda)
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [POSController::class, 'index'])->name('index');
        Route::get('/search-products', [POSController::class, 'searchProducts'])->name('search-products');
        Route::get('/search-customers', [POSController::class, 'searchCustomers'])->name('search-customers');
        Route::get('/product/{product}', [POSController::class, 'getProduct'])->name('get-product');
        Route::post('/process-sale', [POSController::class, 'processSale'])->name('process-sale');
        Route::get('/receipt/{order}', [POSController::class, 'printReceipt'])->name('receipt');
    });
    
    // Gestão de Fornecedores
    Route::resource('suppliers', SupplierController::class);
    
    // Gestão de Compras
    Route::resource('purchases', PurchaseController::class);
    
    // Gestão de Leads
    Route::resource('leads', LeadController::class);
    
    // Gestão de Promoções
    Route::resource('promotions', PromotionController::class);
});

require __DIR__.'/auth.php';
