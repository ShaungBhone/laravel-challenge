<?php

use App\Http\Controllers\InternetServiceProviderController;
use App\Http\Controllers\{JobController, LoginController, PostController, StaffController};
use Illuminate\Support\Facades\Route;

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

Route::post('login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(PostController::class)->group(function () {
        Route::get('posts', 'list');
        Route::post('posts/reaction', 'toggleReaction');
    });

    Route::controller(InternetServiceProviderController::class)->group(function () {
        Route::post('mpt/invoice-amount', 'getMptInvoiceAmount');
        Route::post('ooredoo/invoice-amount', 'getOoredooInvoiceAmount');
    });

    Route::post('job/apply', [JobController::class, 'apply']);

    Route::post('staff/salary', [StaffController::class, 'payroll']);
});
