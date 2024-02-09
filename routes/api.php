<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\SaleInvoiceController;
use App\Http\Controllers\Api\InvoicePartiallyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

    //Auth

        Route::post('/login',[AuthController::class , 'login']);
        Route::post('/logout', [AuthController::class , 'logout']);
        Route::post('/changePassword', [AuthController::class , 'changePassword']);



Route::group([ 'middleware' => 'auth:api'],
    function() {
    //Product Routes
    Route::get('products' , [ProductController::class , 'index']);
    Route::get('products/dropdownProduct',[ProductController::class , 'dropDownProduct']);
    Route::post('products' , [ProductController::class , 'store']);
    Route::post('products/{id}' , [ProductController::class , 'update']);
    Route::delete('products/{id}' , [ProductController::class , 'destroy']);
      //search
    Route::get('products/search/{term}', [ProductController::class, 'search']);


    //suppliers Routes
    Route::get('suppliers' , [SupplierController::class , 'index']);
    Route::get('suppliers/dropDownSupplier',[SupplierController::class , 'dropDownSupplier']);
    Route::post('suppliers' , [SupplierController::class , 'store']);
    Route::post('suppliers/{id}' , [SupplierController::class , 'update']);
    Route::delete('suppliers/{id}' , [SupplierController::class , 'destroy']);
     //search
     Route::get('suppliers/search/{term}', [SupplierController::class, 'search']);


    //invoices Routes
    Route::get('invoices' , [InvoiceController::class , 'index'])->middleware('changeStatus');
    Route::post('invoices' , [InvoiceController::class , 'store']);
    Route::put('invoices/{id}' , [InvoiceController::class , 'update']);
    Route::delete('invoices/{id}' , [InvoiceController::class , 'destroy']);
    //search
    Route::get('invoices/search/{term}', [InvoiceController::class, 'search']);
    //search in details
    Route::get('invoices/search/details/{term}', [InvoiceController::class, 'searchInDetails']);

    //invoices Partially
    Route::get('invoice_Partiallies/{id}' , [InvoicePartiallyController::class , 'index']);
    Route::post('invoice_Partiallies/create/{id}' , [InvoicePartiallyController::class , 'store']);
    Route::put('invoice_Partiallies/edit/{id}' , [InvoicePartiallyController::class , 'update']);
    Route::delete('invoice_Partiallies/delete/{id}' , [InvoicePartiallyController::class , 'destroy']);


    //Sale_invoices
    Route::get('sale_invoices' , [SaleInvoiceController::class , 'index']);
    Route::get('sale_invoices/show/{id}' , [SaleInvoiceController::class , 'show']);
    Route::post('sale_invoices/create' , [SaleInvoiceController::class , 'store']);
    Route::put('sale_invoices/edit/{id}' , [SaleInvoiceController::class , 'update']);
    Route::delete('sale_invoices/delete/{id}' , [SaleInvoiceController::class , 'destroy']);
});
