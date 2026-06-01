<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PrediksiPenjualanController;
use App\Http\Controllers\RestockRekomendasiController;

// Authentication Routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (Require Authentication)
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Inventory Routes (Web Blade CRUD)
    Route::resource('inventory', InventoryController::class);

    Route::get('/stock-in', function () {
        return view('inventory.stockin');
    })->name('inventory.stockin');

    Route::get('/stock-out', function () {
        return view('inventory.stockout');
    })->name('inventory.stockout');

    // Warehouse (static view)
    Route::get('/warehouse', function () {
        return view('catalog.warehouse');
    })->name('catalog.warehouse');

    // Intelligence Routes
    Route::get('/forecast', function () {
        return view('intelligence.forecast');
    })->name('intelligence.forecast');

    Route::get('/reports', function () {
        return view('intelligence.reports');
    })->name('intelligence.reports');
    
    // Web Blade CRUD Resource Routes
    Route::resource('kategoris', KategoriController::class);
    Route::resource('users', UserController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('customers', CustomerController::class);

    // API JSON Resource Routes
    Route::resource('roles', RoleController::class);
    Route::resource('produks', ProdukController::class);
    Route::resource('pembelians', PembelianController::class)->except(['edit', 'create', 'update']);
    Route::resource('penjualans', PenjualanController::class)->except(['edit', 'create', 'update']);
    Route::resource('prediksi-penjualans', PrediksiPenjualanController::class)->except(['edit', 'create']);
    Route::resource('restock-rekomendasis', RestockRekomendasiController::class)->except(['edit', 'create']);

});
