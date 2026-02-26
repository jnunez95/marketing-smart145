<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Top 5 countries
        </x-slot>
        @php $data = $this->getViewData()['countries']; @endphp
        @if($data->isEmpty())
            <p class="text-gray-500 dark:text-gray-400">No geolocation data yet.</p>
        @else
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($data as $row)
                    <li class="py-2 flex justify-between items-center">
                        <span class="font-medium text-gray-900 dark:text-white">{{ $row->geo_country ?? 'N/A' }}</span>
                        <span class="text-gray-500 dark:text-gray-400">{{ number_format($row->total) }} interactions</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
