<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\EmailStatsOverviewWidget;
use App\Filament\Widgets\TopCountriesWidget;
use App\Filament\Widgets\TopRegionsWidget;
use BackedEnum;
use Filament\Pages\Page;

class EmailStatistics extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Email statistics';

    protected static ?string $title = 'Email statistics';

    protected static ?string $slug = 'email-statistics';

    protected static ?int $navigationSort = 5;

    public function getHeaderWidgets(): array
    {
        return [
            EmailStatsOverviewWidget::class,
        ];
    }

    public function getFooterWidgets(): array
    {
        return [
            TopCountriesWidget::class,
            TopRegionsWidget::class,
        ];
    }

    public function getFooterWidgetsColumns(): int|array
    {
        return [
            'default' => 1,
            'lg'      => 2,
        ];
    }
}
