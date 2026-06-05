<?php

namespace App\Jobs;

use App\Models\Shorten;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class deleteUrl implements ShouldQueue
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
        $folder = 'urls/'. $this->model->code;

        Storage::disk(config('app.url_disk'))->deleteDirectory($folder);

        $this->model->delete();
    }

}
