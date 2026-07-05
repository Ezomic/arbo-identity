<?php

namespace App\Providers;

use App\Models\User;
use App\Services\ActivityLogger;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ActivityLogger::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureActivityLogging();
    }

    /**
     * Register activity log listeners for auth events.
     */
    protected function configureActivityLogging(): void
    {
        Event::listen(Login::class, function (Login $event) {
            $user = $event->user;
            app(ActivityLogger::class)->log('login', $user instanceof User ? $user->uuid : null);
        });

        Event::listen(Logout::class, function (Logout $event) {
            $user = $event->user;
            app(ActivityLogger::class)->log('logout', $user instanceof User ? $user->uuid : null);
        });

        Event::listen(Failed::class, function (Failed $event) {
            app(ActivityLogger::class)->log('failed_login', null, [
                'username' => $event->credentials['username'] ?? null,
            ]);
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
