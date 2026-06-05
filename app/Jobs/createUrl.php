<?php

namespace App\Jobs;

use App\Models\Shorten;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Kovah\HtmlMeta\Facades\HtmlMeta;

class createUrl implements ShouldQueue
{
    use Queueable;

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

        $metaTags = HtmlMeta::forUrl($url)->getMeta();

        $title = array_key_exists('title', $metaTags) ? $metaTags['title'] : "";
        $description = array_key_exists('description', $metaTags) ? $metaTags['description'] : "";
        $image = array_key_exists('og:image', $metaTags) ? $metaTags['og:image'] : "";

        $canonical = $url;

        $html = view('shorten', compact('url', 'title', 'description', 'image', 'canonical'))->render();

        Storage::disk(config('app.url_disk'))->put($filename, $html, 'public');

    }

}
