<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
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
        Carbon::setLocale('id');

        Blade::directive('tanggal', function ($expression) {
            return "<?php echo \\Carbon\\Carbon::parse($expression)->isoFormat('D MMM Y'); ?>";
        });
    }
}
