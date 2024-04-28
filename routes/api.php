<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\CongeController;
use App\Http\Controllers\Api\V1\EmailController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' =>'v1','namespace'=>'App\Http\Controllers\Api\V1'], function(){
    Route::apiResource('users', UserController::class);
    Route::post('/login', 'App\Http\Controllers\Api\V1\UserController@login');
    Route::post('/check-email', 'App\Http\Controllers\Api\V1\UserController@checkEmail');
    Route::post('/verify-code', 'App\Http\Controllers\Api\V1\UserController@verifyCode');
    Route::post('/reset-password', 'App\Http\Controllers\Api\V1\UserController@resetPassword');
    Route::post('send-verification-email', [EmailController::class, 'sendVerificationEmail']);
    Route::post('send-reset-password-email', [EmailController::class, 'sendResetPasswordEmail']);
    Route::get('/conges', [CongeController::class, 'index']);
    Route::post('/conges', [CongeController::class, 'store']);

});