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

    // Chatbot Route
    Route::post('/chatbot/send', [App\Http\Controllers\ChatbotController::class, 'sendMessage'])->name('chatbot.send');

    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Inventory Routes (Web Blade CRUD)
    Route::resource('inventory', InventoryController::class);

    Route::get('/stock-in', [App\Http\Controllers\PembelianController::class, 'webIndex'])->name('inventory.stockin');
    Route::post('/stock-in', [App\Http\Controllers\PembelianController::class, 'webStore'])->name('inventory.stockin.store');

    Route::get('/stock-out', [App\Http\Controllers\PenjualanController::class, 'webIndex'])->name('inventory.stockout');
    Route::post('/stock-out', [App\Http\Controllers\PenjualanController::class, 'webStore'])->name('inventory.stockout.store');

    // Warehouse (static view)
    Route::get('/warehouse', function () {
        return view('catalog.warehouse');
    })->name('catalog.warehouse');

    // Intelligence Routes
    Route::get('/forecast', [App\Http\Controllers\PrediksiPenjualanController::class, 'webIndex'])->name('intelligence.forecast');
    Route::post('/forecast/generate', [App\Http\Controllers\PrediksiPenjualanController::class, 'generate'])->name('intelligence.forecast.generate');

    Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('intelligence.reports');

    
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
