<?php

namespace App\Jobs;

use App\Models\ShortenTrackingEvent;
use App\Models\ShortenTrackingSnapshot;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SnapshotAndPruneShortLinkTracking implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        $this->onQueue((string) config('services.short_links.queue', 'short-link-analytics'));
    }

    public function handle(): void
    {
        $cutoffDate = now()->subDays(30);

        $eventsByMonth = ShortenTrackingEvent::query()
            ->where('clicked_at', '<=', $cutoffDate)
            ->orderBy('clicked_at')
            ->get()
            ->groupBy(fn (ShortenTrackingEvent $event) => $event->clicked_at->startOfMonth()->toDateString())
            ->filter(function (Collection $events, string $monthStart) use ($cutoffDate): bool {
                return Carbon::parse($monthStart)->endOfMonth()->lte($cutoffDate);
            });

        foreach ($eventsByMonth as $monthStart => $monthEvents) {
            DB::transaction(function () use ($monthStart, $monthEvents): void {
                foreach ($monthEvents->groupBy('shorten_id') as $shortenEvents) {
                    $this->snapshotShortenMonth($shortenEvents, $monthStart);
                }

                ShortenTrackingEvent::query()
                    ->whereBetween('clicked_at', [
                        Carbon::parse($monthStart)->startOfMonth(),
                        Carbon::parse($monthStart)->endOfMonth(),
                    ])
                    ->delete();
            });
        }
    }

    protected function snapshotShortenMonth(Collection $events, string $monthStart): void
    {
        $first = $events->first();

        if (! $first) {
            return;
        }

        $rows = [
            [
                'shorten_id' => $first->shorten_id,
                'code' => $first->code,
                'snapshot_date' => $monthStart,
                'dimension_type' => 'summary',
                'dimension_value' => '',
                'total_clicks' => $events->count(),
                'unique_visitors' => $this->countMonthlyUniqueVisitors($events),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach (['referrer_host', 'country', 'browser', 'device_type', 'utm_campaign'] as $field) {
            foreach ($events->groupBy(fn ($event) => $event->{$field} ?: 'Unknown') as $label => $group) {
                $rows[] = [
                    'shorten_id' => $first->shorten_id,
                    'code' => $first->code,
                    'snapshot_date' => $monthStart,
                    'dimension_type' => $field,
                    'dimension_value' => (string) $label,
                    'total_clicks' => $group->count(),
                    'unique_visitors' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        ShortenTrackingSnapshot::query()->upsert(
            $rows,
            ['shorten_id', 'snapshot_date', 'dimension_type', 'dimension_value'],
            ['code', 'total_clicks', 'unique_visitors', 'updated_at']
        );
    }

    protected function countMonthlyUniqueVisitors(Collection $events): int
    {
        return $events
            ->map(function ($event): string {
                return implode('|', [
                    $event->ip_hash ?? 'unknown-ip',
                    $event->user_agent_hash ?? 'unknown-ua',
                ]);
            })
            ->unique()
            ->count();
    }
}
