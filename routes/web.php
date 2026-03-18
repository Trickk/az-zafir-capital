<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GangController;
use App\Http\Controllers\Admin\HoldingController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\CashRollDeliveryController;
use App\Http\Controllers\Admin\SettlementController;
use App\Models\CashRollDelivery;

Route::get('/', function () {
    return view('public.home');
})->name('home');

Route::get('/about', function () {
    return view('public.about');
})->name('about');

Route::get('/companies', function () {
    return view('public.companies');
})->name('companies');

Route::get('/investments', function () {
    return view('public.investments');
})->name('investments');

Route::get('/contact', function () {
    return view('public.contact');
})->name('contact');

//Route::view('/test', 'welcome')->name('home');

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::view('testdashboard', 'dashboard')->name('dashboard');
// });


Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('gangs', GangController::class)->except(['show']);
    Route::resource('holdings', HoldingController::class)->except(['show']);
    Route::resource('invoices', InvoiceController::class)->except(['show']);
    Route::resource('cash-rolls', CashRollDeliveryController::class)->except(['show']);
    Route::resource('settlements', SettlementController::class)->except(['show']);

    Route::view('/companies', 'admin.companies.index')->name('companies');
});
require __DIR__.'/settings.php';
