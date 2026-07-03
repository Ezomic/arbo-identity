<?php

use App\Http\Controllers\Auth\DevLoginController;
use App\Http\Controllers\Sso\SsoAuthorizeController;
use App\Http\Controllers\Sso\SsoLogoutController;
use App\Http\Controllers\Tenants\TenantRegistrationController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

Route::post('/dev-login', [DevLoginController::class, 'store'])->name('dev-login.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::redirect('/', '/dashboard')->name('home');
    Route::get('dashboard', SsoAuthorizeController::class)->name('dashboard');
});

Route::get('/sso/authorize', SsoAuthorizeController::class)->name('sso.authorize');
Route::get('/sso/logout', SsoLogoutController::class)->name('sso.logout');

Route::get('tenants/create', [TenantRegistrationController::class, 'create'])->name('tenants.create');
Route::post('tenants', [TenantRegistrationController::class, 'store'])->name('tenants.store');

Route::get('/.well-known/identity-public-key', fn () => response(
    File::get(config('sso.public_key_path')),
    200,
    ['Content-Type' => 'text/plain'],
))->name('sso.public-key');

require __DIR__.'/settings.php';
