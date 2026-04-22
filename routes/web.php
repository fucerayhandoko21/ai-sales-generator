<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesPageController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;


Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout')
    ->middleware('auth');

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [SalesPageController::class, 'index'])
        ->name('dashboard');

    // Generate Sales Page
    Route::get('/generate', [ProductController::class, 'create'])
        ->name('product.create');
    Route::post('/generate', [ProductController::class, 'store'])
        ->name('product.store');

    // Sales Pages CRUD
    Route::get('/pages/{salesPage}', [SalesPageController::class, 'show'])
        ->name('pages.show');
    Route::get('/pages/{salesPage}/edit', [SalesPageController::class, 'edit'])
        ->name('pages.edit');
    Route::put('/pages/{salesPage}', [SalesPageController::class, 'update'])
        ->name('pages.update');
    Route::delete('/pages/{salesPage}', [SalesPageController::class, 'destroy'])
        ->name('pages.destroy');

    // Export Sales Page as HTML
    Route::get('/pages/{salesPage}/export', function (App\Models\SalesPage $salesPage) {
        abort_if($salesPage->user_id !== Auth::id(), 403);
        return response($salesPage->generated_html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="' . str($salesPage->product_name)->slug() . '.html"');
    })->name('pages.export');

});
require __DIR__.'/auth.php';
