<?php

namespace App\Services;

use App\Models\Shorten;
use App\Models\ShortenTrackingEvent;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ShortLinkAnalyticsService
{
    public function trackBrowserHit(Shorten $shorten, array $payload, array $requestMeta): void
    {
        $userAgent = trim((string) ($requestMeta['user_agent'] ?? ''));
        $normalizedUserAgent = Str::lower($userAgent);
        $ipAddress = $requestMeta['ip'] ?? null;

        ShortenTrackingEvent::query()->create([
            'shorten_id' => $shorten->id,
            'code' => $shorten->code,
            'clicked_at' => now(),
            'page_url' => $payload['page_url'],
            'destination_url' => $shorten->url,
            'referrer' => $payload['referrer'] ?: null,
            'referrer_host' => $this->extractReferrerHost($payload['referrer'] ?? null),
            'country' => $this->normalizeCountry($requestMeta['country'] ?? null),
            'language' => $payload['language'] ?: null,
            'timezone' => $payload['timezone'] ?: null,
            'screen_width' => $payload['screen_width'] ?? null,
            'screen_height' => $payload['screen_height'] ?? null,
            'browser' => $this->detectBrowser($userAgent),
            'os' => $this->detectOs($userAgent),
            'device_type' => $this->detectDeviceType($userAgent),
            'ip_hash' => $ipAddress ? hash('sha256', $ipAddress) : null,
            'user_agent_hash' => $normalizedUserAgent !== '' ? hash('sha256', $normalizedUserAgent) : null,
            'utm_source' => $payload['utm_source'] ?: null,
            'utm_medium' => $payload['utm_medium'] ?: null,
            'utm_campaign' => $payload['utm_campaign'] ?: null,
            'utm_term' => $payload['utm_term'] ?: null,
            'utm_content' => $payload['utm_content'] ?: null,
        ]);
    }

    public function buildReport(Shorten $shorten): array
    {
        $days = 14;
        $since = now()->subDays($days - 1)->startOfDay();

        $events = $shorten->trackingEvents()
            ->where('clicked_at', '>=', $since)
            ->orderBy('clicked_at')
            ->get();

        $trend = collect(range(0, $days - 1))
            ->map(function (int $offset) use ($events, $since): array {
                $date = $since->copy()->addDays($offset);
                $dateKey = $date->toDateString();
                $dayEvents = $events->filter(fn (ShortenTrackingEvent $event) => $event->clicked_at->toDateString() === $dateKey);

                return [
                    'date' => $dateKey,
                    'label' => $date->format('M j'),
                    'total' => $dayEvents->count(),
                    'unique' => $this->countUniqueVisitors($dayEvents),
                ];
            })
            ->values();

        return [
            'summary' => [
                'total_clicks' => $events->count(),
                'unique_visitors' => $this->countUniqueVisitors($events),
                'period_label' => 'Last 14 days',
            ],
            'trend' => $trend,
            'trend_max' => max(1, (int) $trend->max('total')),
            'top_referrers' => $this->topBreakdown($events, 'referrer_host'),
            'top_countries' => $this->topBreakdown($events, 'country'),
            'top_browsers' => $this->topBreakdown($events, 'browser'),
            'top_devices' => $this->topBreakdown($events, 'device_type'),
            'top_campaigns' => $this->topBreakdown($events, 'utm_campaign'),
        ];
    }

    protected function countUniqueVisitors(Collection $events): int
    {
        return $events
            ->map(function (ShortenTrackingEvent $event): string {
                $date = $event->clicked_at?->toDateString() ?? 'unknown-date';

                return implode('|', [
                    $date,
                    $event->ip_hash ?? 'unknown-ip',
                    $event->user_agent_hash ?? 'unknown-ua',
                ]);
            })
            ->unique()
            ->count();
    }

    protected function topBreakdown(Collection $events, string $field, int $limit = 5): Collection
    {
        return $events
            ->groupBy(fn (ShortenTrackingEvent $event) => $event->{$field} ?: 'Unknown')
            ->map(fn (Collection $group, string $label) => [
                'label' => $label,
                'total' => $group->count(),
            ])
            ->sortByDesc('total')
            ->take($limit)
            ->values();
    }

    protected function extractReferrerHost(?string $referrer): string
    {
        if (! $referrer) {
            return 'Direct';
        }

        $host = parse_url($referrer, PHP_URL_HOST);

        return $host ? Str::lower($host) : 'Direct';
    }

    protected function normalizeCountry(?string $country): string
    {
        return strtoupper(substr(trim((string) ($country ?: 'Unknown')), 0, 16)) ?: 'Unknown';
    }

    protected function detectDeviceType(string $userAgent): string
    {
        if ($userAgent === '') {
            return 'Unknown';
        }

        if (preg_match('/ipad|tablet|kindle|playbook|silk/i', $userAgent)) {
            return 'Tablet';
        }

        if (preg_match('/mobile|iphone|ipod|android/i', $userAgent)) {
            return 'Mobile';
        }

        return 'Desktop';
    }

    protected function detectBrowser(string $userAgent): string
    {
        if ($userAgent === '') {
            return 'Unknown';
        }

        return match (true) {
            preg_match('/edg\//i', $userAgent) === 1 => 'Edge',
            preg_match('/opr\//i', $userAgent) === 1 => 'Opera',
            preg_match('/chrome\//i', $userAgent) === 1 => 'Chrome',
            preg_match('/firefox\//i', $userAgent) === 1 => 'Firefox',
            preg_match('/safari\//i', $userAgent) === 1 && preg_match('/chrome\//i', $userAgent) !== 1 => 'Safari',
            preg_match('/msie|trident/i', $userAgent) === 1 => 'Internet Explorer',
            default => 'Other',
        };
    }

    protected function detectOs(string $userAgent): string
    {
        if ($userAgent === '') {
            return 'Unknown';
        }

        return match (true) {
            preg_match('/windows/i', $userAgent) === 1 => 'Windows',
            preg_match('/iphone|ipad|ipod/i', $userAgent) === 1 => 'iOS',
            preg_match('/android/i', $userAgent) === 1 => 'Android',
            preg_match('/mac os x|macintosh/i', $userAgent) === 1 => 'macOS',
            preg_match('/linux/i', $userAgent) === 1 => 'Linux',
            default => 'Other',
        };
    }
}
