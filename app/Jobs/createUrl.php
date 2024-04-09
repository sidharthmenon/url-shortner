<?php

namespace App\Jobs;

use App\Models\Shorten;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class createUrl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $model;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Shorten $model)
    {
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $filename = 'urls/'. $this->model->code . "/index.html";
        $url = $this->model->url;

        $web = new \Spekulatius\PHPScraper\PHPScraper;

        $web->go($url);

        $title = $web->title;
        $description = $web->description;
        $image = $web->image;

        if(!$image){
            $image = $web->openGraph['og:image'] ?? "";
        }

        $canonical = $web->canonical ?? $url;

        $html = view('shortner', compact('url', 'title', 'description', 'image', 'canonical'))->render();

        Storage::disk(config('app.url_disk'))->put($filename, $html, 'public');

    }
}
