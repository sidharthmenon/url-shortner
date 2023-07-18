<?php

namespace App\Console\Commands;

use App\Jobs\createUrl;
use App\Models\Shorten;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class generateUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:urls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Urls';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Shorten::orderBy('id')->chunk(100, function (Collection $urls) {
            foreach ($urls as $url) {
                $this->info($url->code);
                dispatch(new createUrl($url));
            }
        });
    }
}
