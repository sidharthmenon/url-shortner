<?php

namespace Tests\Feature;

use App\Jobs\SnapshotAndPruneShortLinkTracking;
use App\Models\Shorten;
use App\Models\ShortenTrackingEvent;
use App\Models\ShortenTrackingSnapshot;
use App\Models\User;
use App\Services\ShortLinkAnalyticsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SnapshotShortLinkTrackingJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_snapshots_old_tracking_events_by_month_and_prunes_them(): void
    {
        Carbon::setTestNow('2026-06-30 12:00:00');

        $user = User::factory()->create();
        $shorten = Shorten::query()->create([
            'url' => 'https://example.com/landing',
            'code' => 'snap123',
            'user_id' => $user->id,
        ]);

        ShortenTrackingEvent::query()->create([
            'shorten_id' => $shorten->id,
            'code' => $shorten->code,
            'clicked_at' => Carbon::parse('2026-05-05 10:00:00'),
            'page_url' => 'https://ksum.in/snap123',
            'destination_url' => $shorten->url,
            'referrer' => 'https://google.com',
            'referrer_host' => 'google.com',
            'country' => 'IN',
            'browser' => 'Chrome',
            'os' => 'macOS',
            'device_type' => 'Desktop',
            'ip_hash' => 'ip-a',
            'user_agent_hash' => 'ua-a',
            'utm_campaign' => 'launch',
        ]);

        ShortenTrackingEvent::query()->create([
            'shorten_id' => $shorten->id,
            'code' => $shorten->code,
            'clicked_at' => Carbon::parse('2026-05-20 11:00:00'),
            'page_url' => 'https://ksum.in/snap123',
            'destination_url' => $shorten->url,
            'referrer' => 'https://google.com',
            'referrer_host' => 'google.com',
            'country' => 'IN',
            'browser' => 'Chrome',
            'os' => 'macOS',
            'device_type' => 'Desktop',
            'ip_hash' => 'ip-a',
            'user_agent_hash' => 'ua-a',
            'utm_campaign' => 'launch',
        ]);

        ShortenTrackingEvent::query()->create([
            'shorten_id' => $shorten->id,
            'code' => $shorten->code,
            'clicked_at' => Carbon::parse('2026-06-20 09:00:00'),
            'page_url' => 'https://ksum.in/snap123',
            'destination_url' => $shorten->url,
            'referrer' => null,
            'referrer_host' => 'Direct',
            'country' => 'US',
            'browser' => 'Safari',
            'os' => 'iOS',
            'device_type' => 'Mobile',
            'ip_hash' => 'ip-b',
            'user_agent_hash' => 'ua-b',
            'utm_campaign' => null,
        ]);

        (new SnapshotAndPruneShortLinkTracking())->handle();

        $this->assertDatabaseMissing('shorten_tracking_events', [
            'shorten_id' => $shorten->id,
            'clicked_at' => '2026-05-05 10:00:00',
        ]);

        $this->assertDatabaseHas('shorten_tracking_events', [
            'shorten_id' => $shorten->id,
            'country' => 'US',
        ]);

        $this->assertDatabaseHas('shorten_tracking_snapshots', [
            'shorten_id' => $shorten->id,
            'snapshot_date' => '2026-05-01',
            'dimension_type' => 'summary',
            'dimension_value' => '',
            'total_clicks' => 2,
            'unique_visitors' => 1,
        ]);

        $this->assertDatabaseHas('shorten_tracking_snapshots', [
            'shorten_id' => $shorten->id,
            'snapshot_date' => '2026-05-01',
            'dimension_type' => 'referrer_host',
            'dimension_value' => 'google.com',
            'total_clicks' => 2,
        ]);
    }

    public function test_reports_merge_monthly_snapshots_with_recent_events(): void
    {
        Carbon::setTestNow('2026-07-15 12:00:00');

        $user = User::factory()->create();
        $shorten = Shorten::query()->create([
            'url' => 'https://example.com/landing',
            'code' => 'merge123',
            'user_id' => $user->id,
        ]);

        ShortenTrackingSnapshot::query()->create([
            'shorten_id' => $shorten->id,
            'code' => $shorten->code,
            'snapshot_date' => '2026-05-01',
            'dimension_type' => 'summary',
            'dimension_value' => '',
            'total_clicks' => 4,
            'unique_visitors' => 3,
        ]);

        ShortenTrackingSnapshot::query()->create([
            'shorten_id' => $shorten->id,
            'code' => $shorten->code,
            'snapshot_date' => '2026-05-01',
            'dimension_type' => 'country',
            'dimension_value' => 'IN',
            'total_clicks' => 4,
            'unique_visitors' => 0,
        ]);

        ShortenTrackingEvent::query()->create([
            'shorten_id' => $shorten->id,
            'code' => $shorten->code,
            'clicked_at' => Carbon::parse('2026-07-06 09:00:00'),
            'page_url' => 'https://ksum.in/merge123',
            'destination_url' => $shorten->url,
            'referrer' => null,
            'referrer_host' => 'Direct',
            'country' => 'US',
            'browser' => 'Safari',
            'os' => 'iOS',
            'device_type' => 'Mobile',
            'ip_hash' => 'ip-b',
            'user_agent_hash' => 'ua-b',
            'utm_campaign' => null,
        ]);

        $report = app(ShortLinkAnalyticsService::class)->buildReport($shorten, 90);

        $this->assertSame(5, $report['summary']['total_clicks']);
        $this->assertSame(4, $report['summary']['unique_visitors']);
        $this->assertSame('IN', $report['top_countries']->first()['label']);
        $this->assertSame(4, $report['top_countries']->first()['total']);
        $this->assertSame('May 2026', $report['trend']->first()['label']);
    }
}
