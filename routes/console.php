<?php

use App\Jobs\createUrl;
use App\Jobs\SnapshotAndPruneShortLinkTracking;
use App\Models\Shorten;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('short-links:rebuild {code? : Optional short code to rebuild} {--sync : Run generation inline instead of queueing}', function (?string $code = null) {
    $query = Shorten::query()->orderBy('id');

    if ($code !== null) {
        $query->where('code', $code);
    }

    $shortLinks = $query->get();

    if ($shortLinks->isEmpty()) {
        $this->warn($code === null
            ? 'No short links found to rebuild.'
            : "No short link found for code [{$code}].");

        return self::FAILURE;
    }

    $sync = (bool) $this->option('sync');

    foreach ($shortLinks as $shortLink) {
        $job = new createUrl($shortLink);

        if ($sync) {
            $job->handle();
        } else {
            dispatch($job);
        }
    }

    $count = $shortLinks->count();

    $this->info($sync
        ? "Rebuilt {$count} short link HTML file(s)."
        : "Queued {$count} short link HTML rebuild job(s).");

    return self::SUCCESS;
})->purpose('Recreate static HTML files for one short link or all short links');

Schedule::job(new SnapshotAndPruneShortLinkTracking())->lastDayOfMonth('01:00');
