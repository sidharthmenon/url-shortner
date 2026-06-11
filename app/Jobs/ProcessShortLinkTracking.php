<?php

namespace App\Jobs;

use App\Models\Shorten;
use App\Services\ShortLinkAnalyticsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessShortLinkTracking implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $shortenId,
        public array $payload,
        public array $requestMeta,
    ) {
    }

    public function handle(ShortLinkAnalyticsService $analytics): void
    {
        $shorten = Shorten::query()->find($this->shortenId);

        if (! $shorten) {
            return;
        }

        $analytics->trackBrowserHit($shorten, $this->payload, $this->requestMeta);
    }
}
