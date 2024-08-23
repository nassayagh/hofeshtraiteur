<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('connect', [\App\Http\Controllers\Auth\LoginController::class, 'authenticate']);
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'authenticate']);
/*Route::middleware('auth:sanctum')->get('logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
Route::post('register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('forget/password', [\App\Http\Controllers\Api\AuthController::class, 'sendTokenToResetPassword']);
Route::post('password/reset', [\App\Http\Controllers\Api\AuthController::class, 'resetPassword']);
Route::post('/send_email_verification', [\App\Http\Controllers\Api\AuthController::class, 'sendEmailVerification']);
Route::post('/verify_code', [\App\Http\Controllers\Api\AuthController::class, 'verifyCode']);
Route::get('/get_terms', [\App\Http\Controllers\Api\TypeController::class, 'privacyPolicy']);
Route::get('/settings', [\App\Http\Controllers\Api\TypeController::class, 'settings']);*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//Route::post("/quotes", [\App\Http\Controllers\DemandController::class]);

Route::group(['middleware' => 'auth:sanctum'], function () {
    /*Route::post('fileupload', [\App\Http\Controllers\FileController::class, 'store'])->name('fileupload');*/
    Route::get('profile', [\App\Http\Controllers\UserController::class, 'profile'])->name('profile');

    Route::resource("users", \App\Http\Controllers\UserController::class);
    Route::resource("customers", \App\Http\Controllers\CustomerController::class);
    Route::resource("demands", \App\Http\Controllers\DemandController::class);
    Route::resource("prestations", \App\Http\Controllers\PrestationController::class);
    Route::resource("services", \App\Http\Controllers\ServiceController::class);
    Route::resource("halls", \App\Http\Controllers\HallController::class);
    Route::resource("event_types", \App\Http\Controllers\EventTypeController::class);
    Route::resource("payment_methods", \App\Http\Controllers\PaymentMethodController::class);
    Route::resource("payments", \App\Http\Controllers\PaymentController::class);
    Route::post("/demands/comment/{id}", [\App\Http\Controllers\DemandController::class , 'addComment']);
    Route::post("/demands/cancel/{id}", [\App\Http\Controllers\DemandController::class , 'cancelDemand']);
    Route::post("/demands/validate/{id}", [\App\Http\Controllers\DemandController::class , 'validateDemand']);
    Route::post("/prestations/cancel/{id}", [\App\Http\Controllers\PrestationController::class , 'cancelPrestation']);
    Route::post("/prestations/comment/{id}", [\App\Http\Controllers\PrestationController::class , 'addComment']);
    Route::post("/prestations/changehall/{id}", [\App\Http\Controllers\PrestationController::class , 'changeHall']);
    Route::post("/prestations/validate/{id}", [\App\Http\Controllers\PrestationController::class , 'validatePrestation']);
    Route::post("/prestations/start/{id}", [\App\Http\Controllers\PrestationController::class , 'startPrestation']);
    Route::post("/prestations/processed/{id}", [\App\Http\Controllers\PrestationController::class , 'startPrestation']);
    Route::post("/prestations/close/{id}", [\App\Http\Controllers\PrestationController::class , 'closePrestation']);
    Route::post("/prestations/service/{id}", [\App\Http\Controllers\PrestationController::class , 'addService']);
    Route::post("/prestations/payment/{id}", [\App\Http\Controllers\PrestationController::class , 'addPayment']);
    Route::delete("/prestations/payments/{id}/{paymentId}", [\App\Http\Controllers\PrestationController::class , 'destroyPayment']);
    Route::delete("/prestations/services/{id}/{serviceId}", [\App\Http\Controllers\PrestationController::class , 'destroyService']);
    Route::get("/statistics/demands", [\App\Http\Controllers\DemandController::class , 'statistics']);
    Route::get("/statistics/customers", [\App\Http\Controllers\CustomerController::class , 'statistics']);
    Route::get("/statistics/prestations", [\App\Http\Controllers\PrestationController::class , 'statistics']);
    Route::get("/statistics/prestations/stats", [\App\Http\Controllers\PrestationController::class , 'stats']);
    Route::get("/statistics/demands/stats", [\App\Http\Controllers\DemandController::class , 'stats']);
    Route::get("/statistics/payments", [\App\Http\Controllers\PaymentController::class , 'stats']);
    Route::get("/payments/stats", [\App\Http\Controllers\PaymentController::class , 'stats']);
});
