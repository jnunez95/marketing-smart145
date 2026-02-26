<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'Home';

    protected static ?int $navigationSort = 0;

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
        ];
    }
}
