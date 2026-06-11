<?php

namespace App\Models;

use App\Traits\AutoFill;
use App\Traits\HashId;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable('url', 'code', 'user_id')]
class Shorten extends Model
{
    use AutoFill, HashId;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trackingEvents(): HasMany
    {
        return $this->hasMany(ShortenTrackingEvent::class);
    }

    public function trackingSnapshots(): HasMany
    {
        return $this->hasMany(ShortenTrackingSnapshot::class);
    }

    public function getLinkAttribute(): string
    {
        return 'https://ksum.in/' . $this->code;
    }
}
