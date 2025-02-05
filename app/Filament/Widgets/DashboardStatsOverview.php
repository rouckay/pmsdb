<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\Athlet;
use App\Models\Fee;

class DashboardStatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Athletes', Athlet::count())
                ->description('Total number of athletes')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success'),

            Card::make('Total Fees Collected', Fee::sum('fees'))
                ->description('Total fees collected')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('primary'),

            Card::make('Athletes Expiring Soon', Athlet::where('admission_expiry_date', '<=', now()->addDays(5))->count())
                ->description('Athletes whose fees are expiring soon')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),
        ];
    }
}
