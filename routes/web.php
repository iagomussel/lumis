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
use App\Http\Controllers\Admin\FinancialController;
use App\Http\Controllers\Admin\ProductionController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\ProductVariantOptionController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\DeliveryController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EcommerceController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

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
    
    // Shipping routes
    Route::post('/shipping/calculate', [EcommerceController::class, 'calculateShipping'])->name('shipping.calculate');
    
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
    
    // Gestão de Variações de Produtos
    Route::resource('product-variants', ProductVariantController::class);
    Route::post('product-variants/{productVariant}/toggle-status', [ProductVariantController::class, 'toggleStatus'])->name('product-variants.toggle-status');
    Route::post('product-variants/bulk-update-inventory', [ProductVariantController::class, 'bulkUpdateInventory'])->name('product-variants.bulk-update-inventory');
    
    // Gestão de Clientes
    Route::resource('customers', CustomerController::class);
    
    // Gestão de Pedidos
    Route::resource('orders', OrderController::class);
    Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('orders/{order}/mark-as-paid', [OrderController::class, 'markAsPaid'])->name('orders.mark-as-paid');
    Route::get('orders/{order}/print', [OrderController::class, 'print'])->name('orders.print');
    
    // Gestão de Estoque (Inventory Management)
    Route::resource('inventory', \App\Http\Controllers\Admin\InventoryController::class);
    Route::post('inventory/bulk-update', [\App\Http\Controllers\Admin\InventoryController::class, 'bulkUpdate'])->name('inventory.bulk-update');
    Route::get('inventory/export', [\App\Http\Controllers\Admin\InventoryController::class, 'export'])->name('inventory.export');
    
    // PDV (Ponto de Venda)
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [POSController::class, 'index'])->name('index');
        Route::get('/test', function() { return view('admin.pos.test'); })->name('test');
        Route::get('/test-search', function() { return view('admin.pos.test-search'); })->name('test-search');
        Route::get('/test-customers', function() { return view('admin.pos.test-customers'); })->name('test-customers');
        Route::get('/debug', function() { 
            return response()->json([
                'authenticated' => Auth::check(),
                'user' => Auth::user() ? Auth::user()->only(['id', 'name', 'email']) : null,
                'csrf_token' => csrf_token(),
                'session_id' => session()->getId(),
                'timestamp' => now()->toISOString()
            ]); 
        })->name('debug');
        Route::get('/search-products', [POSController::class, 'searchProducts'])->name('search-products');
        Route::get('/search-customers', [POSController::class, 'searchCustomers'])->name('search-customers');
        Route::get('/product/{product}', [POSController::class, 'getProduct'])->name('get-product');
        Route::post('/process-sale', [POSController::class, 'processSale'])->name('process-sale');
        Route::get('/receipt/{order}', [POSController::class, 'printReceipt'])->name('receipt');
    });
    
    // Gestão de Fornecedores
    Route::resource('suppliers', SupplierController::class);
    Route::post('suppliers/{supplier}/toggle-status', [SupplierController::class, 'toggleStatus'])->name('suppliers.toggle-status');
    
    // Gestão de Compras
    Route::resource('purchases', PurchaseController::class);
    Route::patch('purchases/{purchase}/mark-received', [PurchaseController::class, 'markAsReceived'])->name('purchases.mark-received');
    
    // Gestão de Leads
    Route::resource('leads', LeadController::class);
    Route::post('leads/{lead}/convert-to-customer', [LeadController::class, 'convertToCustomer'])->name('leads.convert-to-customer');
    
    // Gestão de Promoções
    Route::resource('promotions', PromotionController::class);
    
    // Logs de Auditoria
    Route::prefix('activity-logs')->name('activity-logs.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
        Route::get('/dashboard', [ActivityLogController::class, 'dashboard'])->name('dashboard');
        Route::get('/export', [ActivityLogController::class, 'export'])->name('export');
        Route::get('/{activityLog}', [ActivityLogController::class, 'show'])->name('show');
        Route::delete('/{activityLog}', [ActivityLogController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [ActivityLogController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/cleanup', [ActivityLogController::class, 'cleanup'])->name('cleanup');
    });
    
    // Gestão de Entregas
    Route::resource('deliveries', DeliveryController::class);
    Route::post('deliveries/{delivery}/confirm', [DeliveryController::class, 'confirm'])->name('deliveries.confirm');
    Route::post('deliveries/{delivery}/dispatch', [DeliveryController::class, 'dispatch'])->name('deliveries.dispatch');
    Route::post('deliveries/{delivery}/mark-delivered', [DeliveryController::class, 'markAsDelivered'])->name('deliveries.mark-delivered');
    Route::post('deliveries/{delivery}/cancel', [DeliveryController::class, 'cancel'])->name('deliveries.cancel');
    Route::post('deliveries/{delivery}/update-payment', [DeliveryController::class, 'updatePayment'])->name('deliveries.update-payment');
    Route::get('deliveries/track/{trackingCode}', [DeliveryController::class, 'track'])->name('deliveries.track');
    Route::get('deliveries-dashboard', [DeliveryController::class, 'dashboard'])->name('deliveries.dashboard');
    
    // Busca Global
    Route::get('/search', [SearchController::class, 'page'])->name('search');
    Route::get('/search/global', [SearchController::class, 'global'])->name('search.global');
    
    // Gestão Financeira
    Route::prefix('financial')->name('financial.')->group(function () {
        Route::get('/dashboard', [FinancialController::class, 'dashboard'])->name('dashboard');
        
        // Contas
        Route::get('/accounts', [FinancialController::class, 'accounts'])->name('accounts');
        Route::post('/accounts', [FinancialController::class, 'storeAccount'])->name('accounts.store');
        
        // Contas a Receber
        Route::get('/receivables', [FinancialController::class, 'receivables'])->name('receivables');
        Route::post('/receivables/{receivable}/pay', [FinancialController::class, 'payReceivable'])->name('receivables.pay');
        
        // Contas a Pagar
        Route::get('/payables', [FinancialController::class, 'payables'])->name('payables');
        Route::get('/payables/create', [FinancialController::class, 'createPayable'])->name('payables.create');
        Route::post('/payables', [FinancialController::class, 'storePayable'])->name('payables.store');
        Route::get('/payables/{payable}', [FinancialController::class, 'showPayable'])->name('payables.show');
        Route::get('/payables/{payable}/edit', [FinancialController::class, 'editPayable'])->name('payables.edit');
        Route::patch('/payables/{payable}', [FinancialController::class, 'updatePayable'])->name('payables.update');
        Route::delete('/payables/{payable}', [FinancialController::class, 'destroyPayable'])->name('payables.destroy');
        Route::patch('/payables/{payable}/pay', [FinancialController::class, 'payPayable'])->name('payables.pay');
        
        // Fluxo de Caixa
        Route::get('/cash-flow', [FinancialController::class, 'cashFlow'])->name('cash-flow');
    });
    
    // Gestão de Produção
    Route::prefix('production')->name('production.')->group(function () {
        Route::get('/dashboard', [ProductionController::class, 'dashboard'])->name('dashboard');
        
        // Jobs de Produção
        Route::get('/jobs', [ProductionController::class, 'jobs'])->name('jobs');
        Route::post('/jobs/create-from-order/{order}', [ProductionController::class, 'createJobFromOrder'])->name('jobs.create-from-order');
        Route::post('/jobs/{job}/start', [ProductionController::class, 'startJob'])->name('jobs.start');
        Route::post('/jobs/{job}/complete', [ProductionController::class, 'completeJob'])->name('jobs.complete');
        Route::post('/jobs/{job}/quality-check', [ProductionController::class, 'qualityCheck'])->name('jobs.quality-check');
        
        // Designs
        Route::get('/designs', [ProductionController::class, 'designs'])->name('designs');
        Route::post('/designs/upload', [ProductionController::class, 'uploadDesign'])->name('designs.upload');
        
        // Equipamentos
        Route::get('/equipment', [ProductionController::class, 'equipment'])->name('equipment');
        Route::post('/equipment', [ProductionController::class, 'storeEquipment'])->name('equipment.store');
    });
});

require __DIR__.'/auth.php';
