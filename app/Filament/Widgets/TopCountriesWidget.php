<?php

namespace App\Filament\Widgets;

use App\Models\EmailEvent;
use Filament\Widgets\Widget;

class TopCountriesWidget extends Widget
{
    protected string $view = 'filament.widgets.top-countries-widget';

    protected static ?int $sort = 2;

    public function getViewData(): array
    {
        $top = EmailEvent::withGeo()
            ->selectRaw('geo_country, geo_country_code, count(*) as total')
            ->groupBy('geo_country', 'geo_country_code')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return ['countries' => $top];
    }
}
