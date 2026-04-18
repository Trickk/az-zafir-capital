<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FivemDashboardController;
use App\Http\Controllers\Api\FivemGangController;
use App\Http\Controllers\Api\FivemCompanyController;
use App\Http\Controllers\Api\FivemCashDeliveryController;
use App\Http\Controllers\Api\FivemMatrixFundController;
use App\Http\Controllers\Api\FivemInvoiceController;

Route::middleware('fivem.token')->prefix('fivem')->group(function () {

    Route::get('dashboard', [FivemDashboardController::class, 'index']);

    Route::get   ('gangs',        [FivemGangController::class, 'index']);
    Route::post  ('gangs',        [FivemGangController::class, 'store']);
    Route::put   ('gangs/{gang}', [FivemGangController::class, 'update']);
    Route::delete('gangs/{gang}', [FivemGangController::class, 'destroy']);

    Route::get   ('companies',           [FivemCompanyController::class, 'index']);
    Route::post  ('companies',           [FivemCompanyController::class, 'store']);
    Route::put   ('companies/{company}', [FivemCompanyController::class, 'update']);
    Route::delete('companies/{company}', [FivemCompanyController::class, 'destroy']);

    Route::get   ('cash-deliveries',                 [FivemCashDeliveryController::class, 'index']);
    Route::post  ('cash-deliveries',                 [FivemCashDeliveryController::class, 'store']);
    Route::put   ('cash-deliveries/{cashDelivery}',  [FivemCashDeliveryController::class, 'update']);
    Route::delete('cash-deliveries/{cashDelivery}',  [FivemCashDeliveryController::class, 'destroy']);

    Route::get ('matrix-funds',                       [FivemMatrixFundController::class, 'index']);
    Route::get ('matrix-funds/{matrixFund}',          [FivemMatrixFundController::class, 'show']);
    Route::post('matrix-funds/{matrixFund}/withdraw', [FivemMatrixFundController::class, 'withdraw']);

    Route::get   ('invoices',            [FivemInvoiceController::class, 'index']);
    Route::post  ('invoices',            [FivemInvoiceController::class, 'store']);
    Route::put   ('invoices/{invoice}',  [FivemInvoiceController::class, 'update']);
    Route::delete('invoices/{invoice}',  [FivemInvoiceController::class, 'destroy']);
});
