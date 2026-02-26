<?php

namespace App\Filament\Widgets;

use App\Models\EmailEvent;
use Filament\Widgets\Widget;

class TopRegionsWidget extends Widget
{
    protected string $view = 'filament.widgets.top-regions-widget';

    protected static ?int $sort = 3;

    public function getViewData(): array
    {
        $top = EmailEvent::withGeo()
            ->whereNotNull('geo_region')
            ->where('geo_region', '!=', '')
            ->selectRaw('geo_region, geo_country, count(*) as total')
            ->groupBy('geo_region', 'geo_country')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return ['regions' => $top];
    }
}
