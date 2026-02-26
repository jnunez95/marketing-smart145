<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Top 5 states/regions
        </x-slot>
        @php $data = $this->getViewData()['regions']; @endphp
        @if($data->isEmpty())
            <p class="text-gray-500 dark:text-gray-400">No region data yet.</p>
        @else
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($data as $row)
                    <li class="py-2 flex justify-between items-center">
                        <span class="font-medium text-gray-900 dark:text-white">{{ $row->geo_region }} ({{ $row->geo_country }})</span>
                        <span class="text-gray-500 dark:text-gray-400">{{ number_format($row->total) }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
