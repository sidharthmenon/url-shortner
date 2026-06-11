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

    public function __construct(Shorten $model)
    {
        $this->model = $model;
    }

    public function handle()
    {
        $filename = 'urls/' . $this->model->code . '/index.html';
        $url = $this->model->url;

        $metaTags = HtmlMeta::forUrl($url)->getMeta();

        $title = array_key_exists('title', $metaTags) ? $metaTags['title'] : '';
        $description = array_key_exists('description', $metaTags) ? $metaTags['description'] : '';
        $image = array_key_exists('og:image', $metaTags) ? $metaTags['og:image'] : '';
        $canonical = $url;
        $code = $this->model->code;
        $trackingEndpoint = config('services.short_links.tracking_endpoint');

        $html = view('shorten', compact('url', 'title', 'description', 'image', 'canonical', 'code', 'trackingEndpoint'))->render();

        Storage::disk(config('app.url_disk'))->put($filename, $html, 'public');
    }
}
