<?php

use App\Http\Controllers\Master\ImpersonateController;
use App\Http\Controllers\Master\TenantController;
use App\Http\Controllers\Master\TenantUserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'platform-admin'])->prefix('master')->name('master.')->group(function () {
    Route::redirect('/', '/master/tenants')->name('index');

    Route::resource('tenants', TenantController::class)->only(['index', 'create', 'store', 'show', 'update']);

    Route::post('tenants/{tenant}/users', [TenantUserController::class, 'store'])
        ->name('tenants.users.store');

    Route::delete('tenants/{tenant}/users/{uuid}', [TenantUserController::class, 'destroy'])
        ->name('tenants.users.destroy');

    Route::post('tenants/{tenant}/users/{uuid}/reissue-enrollment', [TenantUserController::class, 'reissueEnrollment'])
        ->name('tenants.users.reissue-enrollment');

    Route::post('impersonate/{uuid}', [ImpersonateController::class, 'store'])
        ->name('impersonate');
});
