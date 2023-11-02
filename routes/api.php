<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SupplierController;

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

    //Product Routes
    Route::get('products' , [ProductController::class , 'index']);
    Route::post('products' , [ProductController::class , 'store']);
    Route::post('products/{id}' , [ProductController::class , 'update']);
    Route::delete('products/{id}' , [ProductController::class , 'destroy']);


    //suppliers Routes
    Route::get('suppliers' , [SupplierController::class , 'index']);
    Route::post('suppliers' , [SupplierController::class , 'store']);
    Route::post('suppliers/{id}' , [SupplierController::class , 'update']);
    Route::delete('suppliers/{id}' , [SupplierController::class , 'destroy']);


    //invoices Routes
    Route::get('invoices' , [InvoiceController::class , 'index']);
    Route::post('invoices' , [InvoiceController::class , 'store']);
    Route::post('invoices/{id}' , [InvoiceController::class , 'update']);
    Route::delete('invoices/{id}' , [InvoiceController::class , 'destroy']);
