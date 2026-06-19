<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\AssetHelper;
use Illuminate\Support\Facades\Blade;

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
        // Register Blade directives for AssetHelper
        Blade::directive('image', function ($image) {
            return "<?php echo asset('assets/images/' . {$image}); ?>";
        });

        Blade::directive('icon', function ($icon) {
            return "<?php echo asset('assets/images/icons/' . {$icon} . '.svg'); ?>";
        });

        Blade::directive('asset_js', function ($path) {
            return "<?php echo asset('assets/js/' . {$path}); ?>";
        });

        Blade::directive('asset_css', function ($path) {
            return "<?php echo asset('assets/css/' . {$path}); ?>";
        });
    }
}
