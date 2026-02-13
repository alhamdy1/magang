<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\View\Composers\DashboardStatsComposer;

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
        // Force HTTPS in production or when behind proxy (like GitHub Codespaces)
        if ($this->app->environment('production') || request()->server('HTTP_X_FORWARDED_PROTO') === 'https') {
            URL::forceScheme('https');
        }
        
        // Register View Composers
        View::composer([
            'admin.dashboard',
            'operator.dashboard',
            'kasi.dashboard',
            'kabid.dashboard',
            'user.dashboard',
        ], DashboardStatsComposer::class);
        
        // Custom Blade Directives
        Blade::directive('rupiah', function ($amount) {
            return "<?php echo \\App\\Helpers\\FormatHelper::rupiah($amount); ?>";
        });
        
        Blade::directive('dateindo', function ($expression) {
            return "<?php echo \\App\\Helpers\\DateHelper::formatIndonesian($expression); ?>";
        });
        
        Blade::directive('nik', function ($nik) {
            return "<?php echo \\App\\Helpers\\FormatHelper::nik($nik); ?>";
        });
        
        Blade::directive('phone', function ($phone) {
            return "<?php echo \\App\\Helpers\\FormatHelper::phone($phone); ?>";
        });
        
        Blade::directive('truncate', function ($expression) {
            return "<?php echo \\App\\Helpers\\FormatHelper::truncate($expression); ?>";
        });
    }
}
