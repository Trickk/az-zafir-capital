<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HoldingController;
use App\Http\Controllers\Admin\CashRollDeliveryController;
use App\Http\Controllers\Admin\SettlementController;
use App\Models\CashRollDelivery;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GangController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\CashDeliveryController;
use App\Http\Controllers\Admin\InvoiceController;

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
 Route::get('/invoice-public/{invoice}', [InvoiceController::class, 'publicRender'])
    ->name('invoices.public-render');

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('companies', CompanyController::class)->except(['show']);
    Route::resource('gangs', GangController::class)->except(['show']);
    Route::resource('cash-deliveries', CashDeliveryController::class)->except(['show']);
    Route::resource('invoices', InvoiceController::class)->except(['show']);

    Route::get('invoices/{invoice}/preview', [InvoiceController::class, 'preview'])->name('invoices.preview');
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
    Route::post('invoices/{invoice}/generate-pdf', [InvoiceController::class, 'generatePdf'])->name('invoices.generate-pdf');

    Route::post('invoices/{invoice}/generate-png', [InvoiceController::class, 'generatePng'])
    ->name('invoices.generate-png');
});
require __DIR__.'/settings.php';
