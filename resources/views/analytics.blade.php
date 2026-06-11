@php
    $summary = $analytics['summary'];
    $trend = collect($analytics['trend'])->take(-14)->values();
    $trendMax = max(1, (int) $trend->max('total'));
@endphp

<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        <x-filament::section>
            <div class="space-y-1">
                <p class="text-sm text-gray-500">Total Clicks</p>
                <p class="text-3xl font-semibold">{{ number_format($summary['total_clicks']) }}</p>
                <p class="text-xs text-gray-500">{{ $summary['period_label'] }}</p>
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="space-y-1">
                <p class="text-sm text-gray-500">Unique Visitors</p>
                <p class="text-3xl font-semibold">{{ number_format($summary['unique_visitors']) }}</p>
                <p class="text-xs text-gray-500">IP per user agent per day</p>
            </div>
        </x-filament::section>
    </div>

    <x-filament::section heading="Clicks Trend" description="hits over the last 14 days.">
        <div class="flex min-h-52 items-end gap-2 overflow-x-auto pb-2">
            @foreach ($trend as $point)
                @php
                    $height = max(10, (int) round(($point['total'] / $trendMax) * 180));
                @endphp
                <div class="flex min-w-10 flex-1 flex-col items-center gap-2">
                    <div class="w-full rounded-t-md bg-primary-600" style="height: {{ $height }}px"></div>
                    <div class="text-center">
                        <p class="text-xs font-medium text-gray-700">{{ $point['total'] }}</p>
                        <p class="text-[11px] text-gray-500">{{ $point['label'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::section>

    <div class="grid gap-4 lg:grid-cols-2 xl:grid-cols-3">
        <x-filament::section heading="Top Referrers">
            <div class="space-y-3">
                @forelse ($analytics['top_referrers'] as $item)
                    <div class="flex items-center justify-between gap-4 border-b border-gray-100 pb-2 last:border-b-0 last:pb-0">
                        <p class="truncate text-sm text-gray-700">{{ $item['label'] }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $item['total'] }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No referrer data yet.</p>
                @endforelse
            </div>
        </x-filament::section>

        <x-filament::section heading="Countries">
            <div class="space-y-3">
                @forelse ($analytics['top_countries'] as $item)
                    <div class="flex items-center justify-between gap-4 border-b border-gray-100 pb-2 last:border-b-0 last:pb-0">
                        <p class="truncate text-sm text-gray-700">{{ $item['label'] }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $item['total'] }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No country data yet.</p>
                @endforelse
            </div>
        </x-filament::section>

        <x-filament::section heading="Browsers">
            <div class="space-y-3">
                @forelse ($analytics['top_browsers'] as $item)
                    <div class="flex items-center justify-between gap-4 border-b border-gray-100 pb-2 last:border-b-0 last:pb-0">
                        <p class="truncate text-sm text-gray-700">{{ $item['label'] }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $item['total'] }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No browser data yet.</p>
                @endforelse
            </div>
        </x-filament::section>

        <x-filament::section heading="Devices">
            <div class="space-y-3">
                @forelse ($analytics['top_devices'] as $item)
                    <div class="flex items-center justify-between gap-4 border-b border-gray-100 pb-2 last:border-b-0 last:pb-0">
                        <p class="truncate text-sm text-gray-700">{{ $item['label'] }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $item['total'] }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No device data yet.</p>
                @endforelse
            </div>
        </x-filament::section>

        <x-filament::section heading="Top Campaigns">
            <div class="space-y-3">
                @forelse ($analytics['top_campaigns'] as $item)
                    <div class="flex items-center justify-between gap-4 border-b border-gray-100 pb-2 last:border-b-0 last:pb-0">
                        <p class="truncate text-sm text-gray-700">{{ $item['label'] }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $item['total'] }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No campaign data yet.</p>
                @endforelse
            </div>
        </x-filament::section>
    </div>
</div>
