<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
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
        Model::unguard();

        $offsetWeeks = config('app.dev.offset_weeks');

        if ($offsetWeeks) {
            $now = now()->addWeeks($offsetWeeks);

            // Set this as the current time in the application
            // This will affect all date/time operations
            // throughout the application
            Carbon::setTestNow($now);
        }
    }
}
