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
use App\Http\Controllers\Admin\MatrixFundController;

use App\Http\Controllers\Api\FivemDashboardController;
use App\Http\Controllers\Api\FivemGangController;
use App\Http\Controllers\Api\FivemCompanyController;
use App\Http\Controllers\Api\FivemCashDeliveryController;
use App\Http\Controllers\Api\FivemMatrixFundController;
use App\Http\Controllers\Api\FivemInvoiceController;

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
 Route::get('/invoice-public/{token}', [InvoiceController::class, 'publicRender'])
    ->name('invoices.public-render');

Route::get('invoices/{invoice}/image/view', [InvoiceController::class, 'imageView'])
        ->name('invoices.image.view');

Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Companies
        Route::resource('companies', CompanyController::class);

        // Gangs
        Route::resource('gangs', GangController::class);

        // Cash Deliveries
        Route::resource('cash-deliveries', CashDeliveryController::class);

        // Invoices
        Route::resource('invoices', InvoiceController::class);

        // Extras de invoices
        Route::get('invoices/{invoice}/preview', [InvoiceController::class, 'preview'])
            ->name('invoices.preview');

        Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])
            ->name('invoices.pdf');

        Route::get('/invoices/{invoice}/image/generate', [InvoiceController::class, 'generateImage'])
            ->name('admin.invoices.image.generate');

        Route::get('invoices/{invoice}/image', [InvoiceController::class, 'showImage'])
            ->name('invoices.image.show');

        Route::post('invoices/{invoice}/copy-image-url', [InvoiceController::class, 'copyImageUrl'])
            ->name('invoices.image.copy-url');


        // Matrix Fund
        Route::get('matrix-funds', [MatrixFundController::class, 'index'])
            ->name('matrix-funds.index');

        Route::get('matrix-funds/{matrixFund}', [MatrixFundController::class, 'show'])
            ->name('matrix-funds.show');

        // Retirada
        Route::get('matrix-funds/{matrixFund}/withdraw', [MatrixFundController::class, 'withdrawForm'])
            ->name('matrix-funds.withdraw.form');

        Route::post('matrix-funds/{matrixFund}/withdraw', [MatrixFundController::class, 'withdraw'])
            ->name('matrix-funds.withdraw');

        Route::delete('matrix-funds/{matrixFund}', [MatrixFundController::class, 'withdraw'])
            ->name('matrix-funds.destroy');

    });

require __DIR__.'/settings.php';
