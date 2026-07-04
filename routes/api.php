<?php

use App\Http\Controllers\Api\UserApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth.api-client', 'throttle:60,1'])->group(function () {
    Route::get('users', [UserApiController::class, 'index']);
    Route::post('users', [UserApiController::class, 'store']);
    Route::put('users/{uuid}', [UserApiController::class, 'update']);
    Route::delete('users/{uuid}', [UserApiController::class, 'destroy']);
});
