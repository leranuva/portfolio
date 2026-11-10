<?php

namespace App\Providers;

use App\Services\PublicSubmission;
use App\View\Composers\PortfolioComposer;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer('layouts.portfolio', PortfolioComposer::class);

        RateLimiter::for('contact-submissions', function (Request $request): array {
            $limits = [
                Limit::perMinute(5)->by('ip:'.$request->ip()),
                Limit::perMinute(30)->by('global'),
            ];

            $email = $request->input('email');
            if (is_string($email) && strlen($email) <= 254 && str_contains($email, '@')) {
                $limits[] = Limit::perHour(3)->by('recipient:'.PublicSubmission::recipientKey($email));
            }

            return $limits;
        });

        RateLimiter::for('calendly-webhooks', fn (Request $request) => Limit::perMinute(60)->by($request->ip()));
    }
}
