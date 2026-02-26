<?php

namespace App\Http\Controllers;

use App\Models\EmailEvent;
use Illuminate\View\View;

class PublicStatsController extends Controller
{
    public function index(): View
    {
        $deliveries = EmailEvent::deliveries()->count();
        $opens = EmailEvent::opens()->count();
        $clicks = EmailEvent::clicks()->count();
        $bounces = EmailEvent::bounces()->count();
        $openRate = $deliveries > 0 ? round(($opens / $deliveries) * 100, 1) : 0;
        $clickRate = $opens > 0 ? round(($clicks / $opens) * 100, 1) : 0;

        $topCountries = EmailEvent::withGeo()
            ->selectRaw('geo_country, geo_country_code, count(*) as total')
            ->groupBy('geo_country', 'geo_country_code')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topRegions = EmailEvent::withGeo()
            ->whereNotNull('geo_region')
            ->where('geo_region', '!=', '')
            ->selectRaw('geo_region, geo_country, count(*) as total')
            ->groupBy('geo_region', 'geo_country')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topLinks = EmailEvent::clicks()
            ->whereNotNull('original_link')
            ->selectRaw('original_link, count(*) as total')
            ->groupBy('original_link')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('stats.index', [
            'deliveries'    => $deliveries,
            'opens'         => $opens,
            'clicks'        => $clicks,
            'bounces'       => $bounces,
            'openRate'      => $openRate,
            'clickRate'     => $clickRate,
            'topCountries'  => $topCountries,
            'topRegions'    => $topRegions,
            'topLinks'      => $topLinks,
        ]);
    }
}
