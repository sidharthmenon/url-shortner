<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'shorten_id',
    'code',
    'clicked_at',
    'page_url',
    'destination_url',
    'referrer',
    'referrer_host',
    'country',
    'language',
    'timezone',
    'screen_width',
    'screen_height',
    'browser',
    'os',
    'device_type',
    'ip_hash',
    'user_agent_hash',
    'utm_source',
    'utm_medium',
    'utm_campaign',
    'utm_term',
    'utm_content',
])]
class ShortenTrackingEvent extends Model
{
    protected function casts(): array
    {
        return [
            'clicked_at' => 'datetime',
        ];
    }

    public function shorten(): BelongsTo
    {
        return $this->belongsTo(Shorten::class);
    }
}
