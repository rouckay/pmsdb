<?php
// ...existing code...
use App\Filament\Widgets\DashboardStatsOverview;
use Carbon\Laravel\ServiceProvider;
use Filament\Facades\Filament;
// ...existing code...

class FilamentServiceProvider extends ServiceProvider
{
    // ...existing code...

    public function boot()
    {
        Filament::registerWidgets([
            DashboardStatsOverview::class,
        ]);

        // ...existing code...
    }

    // ...existing code...
}
