<?php

namespace App\Filament\Widgets;

use App\Models\EmailEvent;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EmailStatsOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $deliveries = EmailEvent::deliveries()->count();
        $opens = EmailEvent::opens()->count();
        $clicks = EmailEvent::clicks()->count();
        $bounces = EmailEvent::bounces()->count();

        $openRate = $deliveries > 0 ? round(($opens / $deliveries) * 100, 1) : 0;
        $clickRate = $opens > 0 ? round(($clicks / $opens) * 100, 1) : 0;

        return [
            Stat::make('Emails sent', number_format($deliveries))
                ->description('Delivery events')
                ->icon('heroicon-o-paper-airplane'),
            Stat::make('Emails opened', number_format($opens))
                ->description('Open events')
                ->icon('heroicon-o-envelope-open'),
            Stat::make('Link clicks', number_format($clicks))
                ->description('Click events')
                ->icon('heroicon-o-cursor-arrow-ripple'),
            Stat::make('Open rate', $openRate.'%')
                ->description('Opens / Deliveries')
                ->icon('heroicon-o-chart-bar'),
            Stat::make('Click rate', $clickRate.'%')
                ->description('Clicks / Opens')
                ->icon('heroicon-o-hand-raised'),
            Stat::make('Bounces', number_format($bounces))
                ->description('Bounce events')
                ->icon('heroicon-o-exclamation-triangle'),
        ];
    }
}
