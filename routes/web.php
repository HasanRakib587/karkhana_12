<?php

use App\Helpers\CartManagement;
use App\Http\Controllers\PageController;
use App\Livewire\CartPage;
use App\Livewire\HomePage;
use App\Livewire\CancelPage;
use App\Livewire\SuccessPage;
use App\Livewire\CheckoutPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\ProductsPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\MyOrderDetailPage;
use App\Livewire\ProductDetailPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\Auth\ForgotPasswordPage;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\SslCommerzController;
use App\Http\Middleware\VerifySslcommerzIp;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

Route::get('/', HomePage::class)->name('home');
Route::get('/products', ProductsPage::class)->name('all.products');
Route::get('/products/{slug}', ProductDetailPage::class)->name('product.detail');
Route::get('/cart', CartPage::class)->name('cartPage');
Route::get('/auth/redirection/{provider}', [SocialiteController::class, 'authProviderRedirect'])
        ->name('auth.redirection');
Route::get('auth/{provider}/callback', [SocialiteController::class, 'socialAuthentication'])
        ->name('auth.callback');
Route::get('/login', LoginPage::class)->name('login');
Route::get('/contact', [PageController::class, 'contactUs'])->name('contact.page');
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy.page');
Route::get('/terms-conditions', [PageController::class, 'termsConditions'])->name('terms.page');

// Route::middleware('guest')->group(function(){
//     Route::get('/login', LoginPage::class)->name('login');
//     Route::get('/register', RegisterPage::class)->name('user.register');
//     Route::get('/forgot', ForgotPasswordPage::class)->name('password.request');
//     Route::get('/reset/{token}', ResetPasswordPage::class)->name('password.reset');
// });

Route::middleware('auth:customer')->group(function(){

    Route::get('/logout', function(){
        Auth::guard('customer')->logout();        
        // Regenerate CSRF token only
        request()->session()->regenerateToken();
        
        return redirect('/');
    })->name('customer.logout');
    
    Route::get('/checkout', CheckoutPage::class)->name('checkout');
    Route::get('/my-orders', MyOrdersPage::class)->name('my.orders');
    Route::get('/my-orders/{order_id}', MyOrderDetailPage::class)->name('order.details');

    Route::get('/success', SuccessPage::class)->name('success');
    Route::get('/cancelled', CancelPage::class)->name('cancelled');       
});

// Route::prefix('sslcommerz')->group(function (){
//     Route::get('/init/{order}', [SslCommerzController::class, 'init'])
//             ->name('sslcommerz.init');
    
//     Route::any('/success', [SslCommerzController::class, 'success'])
//             ->name('sslcommerz.success');
    
//     Route::any('/fail', [SslCommerzController::class, 'fail'])
//             ->name('sslcommerz.fail');
    
//     Route::any('/cancel', [SslCommerzController::class, 'cancel'])
//             ->name('sslcommerz.cancel');
    
//     Route::any('/ipn', [SslCommerzController::class, 'ipn'])
//             ->name('sslcommerz.ipn')
//             ->withoutMiddleware(VerifyCsrfToken::class)
//             ->middleware(VerifySslcommerzIp::class);
// });

// Optional: catch all GET attempts and block them
// Route::get('/sslcommerz/{any}', function () {
//     abort(403, 'Unauthorized access.');
// })->where('any', '.*');



