<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController; // Adjust namespace if I didn't change folder
// Actually I put it in App\Http\Controllers\Auth\AuthController
use App\Http\Controllers\Buyer\HomeController;
use App\Http\Controllers\Buyer\CartController;
use App\Http\Controllers\Buyer\OrderController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;

// Auth Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{product}', [HomeController::class, 'show'])->name('product.show');
Route::get('/category/{category}', [HomeController::class, 'category'])->name('category.show');

// Buyer Routes (Accessible by any authenticated user)
Route::middleware(['auth', 'role:buyer'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cart}', [CartController::class, 'remove'])->name('cart.remove');
    
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'process'])->name('checkout.process');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    
    // Resource Routes
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'update']);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
});

// Secure Admin Setup Route
Route::middleware(['auth'])->get('/setup-admin', function () {
    $user = Illuminate\Support\Facades\Auth::user();
    
    // Check if ANY admin already exists
    if (\App\Models\User::where('role', 'admin')->exists()) {
        return redirect()->route('home')->with('error', 'Admin account already exists. Setup is disabled.');
    }

    // If no admin exists, promote this user
    $user->role = 'admin';
    $user->save();
    
    return redirect()->route('admin.dashboard')->with('success', 'You are now the System Admin!');
})->name('setup.admin');