<?php

namespace App\Providers;

use App\Services\VoteCounter;
use Illuminate\Support\ServiceProvider;

class VotingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(VoteCounter::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
