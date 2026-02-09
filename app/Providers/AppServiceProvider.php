<?php

namespace App\Providers;

use App\Models\SkripsiRequest;
use Carbon\Carbon;
use App\Models\BebasPustakaRequest;
use Illuminate\Support\Facades\Gate;
use App\Policies\SkripsiRequestPolicy;
use Illuminate\Support\ServiceProvider;
use App\Policies\BebasPustakaRequestPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $smtp = config('mail.mailers.smtp', []);
        $smtp['scheme'] = env('MAIL_SCHEME', 'smtps');
        $smtp['encryption'] = env('MAIL_ENCRYPTION', 'ssl');
        config(['mail.mailers.smtp' => $smtp]);

        Gate::policy(BebasPustakaRequest::class, BebasPustakaRequestPolicy::class);
        Gate::policy(SkripsiRequest::class, SkripsiRequestPolicy::class);

        Gate::define('manage-users', fn ($user) => $user?->hasRole('admin') ?? false);

        Carbon::setLocale(config('app.locale'));
    }
}
