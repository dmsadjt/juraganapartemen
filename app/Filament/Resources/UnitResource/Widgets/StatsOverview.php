<?php

namespace App\Filament\Resources\UnitResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Unit;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Units', Unit::count()),
            Stat::make('Available Units', Unit::where('status', 'available')->count()),
            Stat::make('Rented Units', Unit::where('status', 'booked')->count()),
            Stat::make('Sold Units', Unit::where('status', 'sold')->count()),
        ];
    }
}
