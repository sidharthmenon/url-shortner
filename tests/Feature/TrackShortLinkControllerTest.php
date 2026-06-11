<?php

namespace Tests\Feature;

use App\Jobs\ProcessShortLinkTracking;
use App\Jobs\createUrl;
use App\Models\Shorten;
use App\Models\User;
use App\Services\ShortLinkAnalyticsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Kovah\HtmlMeta\Facades\HtmlMeta;
use Tests\TestCase;

class TrackShortLinkControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_queues_a_browser_tracking_event_job(): void
    {
        Queue::fake();

        $user = User::factory()->create();
        $shorten = Shorten::query()->create([
            'url' => 'https://example.com/landing',
            'code' => 'track123',
            'user_id' => $user->id,
        ]);

        $response = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 Mobile/15E148 Safari/604.1',
            'CF-IPCountry' => 'IN',
            'X-Forwarded-For' => '203.0.113.10',
        ])->postJson('/api/short-links/track', [
            'code' => $shorten->code,
            'page_url' => 'https://ksum.in/track123?utm_source=newsletter&utm_campaign=launch',
            'destination_url' => $shorten->url,
            'referrer' => 'https://google.com/search?q=ksum',
            'language' => 'en-US',
            'timezone' => 'Asia/Kolkata',
            'screen_width' => 1170,
            'screen_height' => 2532,
            'utm_source' => 'newsletter',
            'utm_medium' => null,
            'utm_campaign' => 'launch',
            'utm_term' => null,
            'utm_content' => null,
        ]);

        $response->assertNoContent();

        Queue::assertPushed(ProcessShortLinkTracking::class, function (ProcessShortLinkTracking $job) use ($shorten) {
            return $job->shortenId === $shorten->id
                && $job->payload['code'] === 'track123'
                && $job->requestMeta['country'] === 'IN';
        });
    }

    public function test_processing_job_stores_a_browser_tracking_event(): void
    {
        $user = User::factory()->create();
        $shorten = Shorten::query()->create([
            'url' => 'https://example.com/landing',
            'code' => 'track123',
            'user_id' => $user->id,
        ]);

        $job = new ProcessShortLinkTracking($shorten->id, [
            'code' => $shorten->code,
            'page_url' => 'https://ksum.in/track123?utm_source=newsletter&utm_campaign=launch',
            'destination_url' => $shorten->url,
            'referrer' => 'https://google.com/search?q=ksum',
            'language' => 'en-US',
            'timezone' => 'Asia/Kolkata',
            'screen_width' => 1170,
            'screen_height' => 2532,
            'utm_source' => 'newsletter',
            'utm_medium' => null,
            'utm_campaign' => 'launch',
            'utm_term' => null,
            'utm_content' => null,
        ], [
            'ip' => '203.0.113.10',
            'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 Mobile/15E148 Safari/604.1',
            'country' => 'IN',
        ]);

        $job->handle(app(ShortLinkAnalyticsService::class));

        $this->assertDatabaseHas('shorten_tracking_events', [
            'shorten_id' => $shorten->id,
            'code' => 'track123',
            'referrer_host' => 'google.com',
            'country' => 'IN',
            'language' => 'en-US',
            'timezone' => 'Asia/Kolkata',
            'screen_width' => 1170,
            'screen_height' => 2532,
            'browser' => 'Safari',
            'device_type' => 'Mobile',
            'utm_source' => 'newsletter',
            'utm_campaign' => 'launch',
        ]);
    }

    public function test_it_returns_not_found_for_unknown_codes(): void
    {
        Queue::fake();

        $response = $this->postJson('/api/short-links/track', [
            'code' => 'missing',
            'page_url' => 'https://ksum.in/missing',
            'destination_url' => 'https://example.com/landing',
        ]);

        $response->assertNoContent(404);
        Queue::assertNothingPushed();
    }

    public function test_static_page_contains_tracking_script_and_endpoint(): void
    {
        Storage::fake(config('app.url_disk'));
        config(['services.short_links.tracking_endpoint' => 'https://app.test/api/short-links/track']);

        $user = User::factory()->create();
        $shorten = Shorten::query()->create([
            'url' => 'https://example.com/landing',
            'code' => 'beacon1',
            'user_id' => $user->id,
        ]);

        HtmlMeta::shouldReceive('forUrl')->once()->with('https://example.com/landing')->andReturnSelf();
        HtmlMeta::shouldReceive('getMeta')->once()->andReturn([
            'title' => 'Example',
            'description' => 'Description',
            'og:image' => 'https://example.com/image.png',
        ]);

        (new createUrl($shorten))->handle();

        $html = Storage::disk(config('app.url_disk'))->get('urls/beacon1/index.html');

        $this->assertStringContainsString('navigator.sendBeacon', $html);
        $this->assertStringContainsString('https://app.test/api/short-links/track', $html);
        $this->assertStringContainsString('window.location.replace', $html);
        $this->assertStringContainsString('utm_source', $html);
    }
}
