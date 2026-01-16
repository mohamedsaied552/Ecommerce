<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\PublicInvoiceController;
use App\Http\Controllers\WebhookController;

// Store controllers
use App\Http\Controllers\Store\StoreController;
use App\Http\Controllers\Store\ProductController;
use App\Http\Controllers\Store\CartController;
use App\Http\Controllers\Store\CheckoutController;
use App\Http\Controllers\Store\PaymobController;

Route::post('/paymob/pay', [PaymobController::class, 'pay'])
    ->name('paymob.pay');
// Public routes
Route::get('/', function () {
    return redirect()->route('store.home');
});

// Store front (rate limited)
Route::middleware(['throttle:120,1'])->group(function () {
    Route::get('/store', [StoreController::class, 'home'])->name('store.home');

    Route::get('/products', [ProductController::class, 'index'])->name('store.products.index');
    Route::get('/products/{slug}', [ProductController::class, 'show'])->name('store.products.show');

    Route::get('/cart', [CartController::class, 'index'])->name('store.cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('store.cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('store.cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('store.cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('store.cart.clear');

    Route::post('/checkout/place', [CheckoutController::class, 'place'])->name('checkout.place');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'submit'])->name('store.checkout.submit');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('store.checkout.success');
    Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('store.checkout.cancel');
});

// Public invoice routes (keep old module)
Route::middleware(['throttle:60,1'])->group(function () {
    Route::get('/i/{token}', [PublicInvoiceController::class, 'show'])->name('invoice.show');
Route::post('/i/{token}/pay', [PublicInvoiceController::class, 'pay'])->name('invoice.pay');
    Route::get('/success', [PublicInvoiceController::class, 'success'])->name('invoice.success');
    Route::get('/cancel', [PublicInvoiceController::class, 'cancel'])->name('invoice.cancel');
});

// Webhook route (no CSRF, rate limited)
Route::middleware(['throttle:100,1'])->group(function () {
    Route::post('/webhooks/paymob', [WebhookController::class, 'paymob'])
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
});


// Admin routes (authenticated)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', function () {
        $invoices = \App\Models\Invoice::with('latestPayment')->latest()->take(10)->get();
        $stats = [
            'total' => \App\Models\Invoice::count(),
            'paid' => \App\Models\Invoice::where('status', 'paid')->count(),
            'pending' => \App\Models\Invoice::where('status', 'pending')->count(),
            'total_amount' => \App\Models\Invoice::where('status', 'paid')->sum('amount_cents') / 100,
        ];
        return view('admin.dashboard', compact('invoices', 'stats'));
    })->name('dashboard');

    // Invoice module (existing)
    Route::resource('invoices', InvoiceController::class);
    Route::post('invoices/{invoice}/resend-email', [InvoiceController::class, 'resendEmail'])->name('invoices.resend-email');
    Route::post('invoices/{invoice}/mark-expired', [InvoiceController::class, 'markExpired'])->name('invoices.mark-expired');

    // Store admin
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::resource('orders', AdminOrderController::class)->only(['index','show']);
});

require __DIR__.'/auth.php';
