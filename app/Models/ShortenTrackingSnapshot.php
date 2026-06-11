<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'shorten_id',
    'code',
    'snapshot_date',
    'dimension_type',
    'dimension_value',
    'total_clicks',
    'unique_visitors',
])]
class ShortenTrackingSnapshot extends Model
{
    protected function casts(): array
    {
        return [
            'snapshot_date' => 'date',
        ];
    }

    public function shorten(): BelongsTo
    {
        return $this->belongsTo(Shorten::class);
    }
}
