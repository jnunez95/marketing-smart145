<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email statistics – {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen p-6">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-2xl font-semibold mb-6">Email statistics</h1>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">Emails sent</p>
                <p class="text-2xl font-bold">{{ number_format($deliveries) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">Emails opened</p>
                <p class="text-2xl font-bold">{{ number_format($opens) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">Link clicks</p>
                <p class="text-2xl font-bold">{{ number_format($clicks) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">Open rate</p>
                <p class="text-2xl font-bold">{{ $openRate }}%</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">Click rate</p>
                <p class="text-2xl font-bold">{{ $clickRate }}%</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">Bounces</p>
                <p class="text-2xl font-bold">{{ number_format($bounces) }}</p>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h2 class="text-lg font-semibold mb-4">Top 5 countries</h2>
                @if($topCountries->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400">No data yet.</p>
                @else
                    <ul class="space-y-2">
                        @foreach($topCountries as $row)
                            <li class="flex justify-between"><span>{{ $row->geo_country ?? 'N/A' }}</span><span>{{ number_format($row->total) }}</span></li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h2 class="text-lg font-semibold mb-4">Top 5 states/regions</h2>
                @if($topRegions->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400">No data yet.</p>
                @else
                    <ul class="space-y-2">
                        @foreach($topRegions as $row)
                            <li class="flex justify-between"><span>{{ $row->geo_region }} ({{ $row->geo_country }})</span><span>{{ number_format($row->total) }}</span></li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        @if($topLinks->isNotEmpty())
            <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h2 class="text-lg font-semibold mb-4">Most clicked links</h2>
                <ul class="space-y-2 break-all">
                    @foreach($topLinks as $row)
                        <li class="flex justify-between gap-4"><span class="min-w-0">{{ $row->original_link }}</span><span class="shrink-0">{{ number_format($row->total) }}</span></li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</body>
</html>
