<?php

namespace App\Filament\Widgets;

use App\Models\Station;
use App\Models\Campaign;
use App\Models\Group;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $campaignsThisMonth = Campaign::where('status', Campaign::STATUS_SENT)
            ->whereMonth('sent_at', now()->month)
            ->whereYear('sent_at', now()->year)
            ->count();

        $totalSent = Campaign::where('status', Campaign::STATUS_SENT)->sum('total_sent');
        $totalOpened = Campaign::where('status', Campaign::STATUS_SENT)->sum('total_opened');
        $openRate = $totalSent > 0 ? round(($totalOpened / $totalSent) * 100, 1) : 0;

        return [
            Stat::make('Stations', Station::count())
                ->description('Total stations')
                ->icon('heroicon-o-building-office-2'),
            Stat::make('Groups', Group::count())
                ->description('Active groups')
                ->icon('heroicon-o-user-group'),
            Stat::make('Campaigns this month', $campaignsThisMonth)
                ->description(now()->format('F Y'))
                ->icon('heroicon-o-paper-airplane'),
            Stat::make('Open rate', $openRate.'%')
                ->description('Overall average')
                ->icon('heroicon-o-envelope-open'),
        ];
    }
}
