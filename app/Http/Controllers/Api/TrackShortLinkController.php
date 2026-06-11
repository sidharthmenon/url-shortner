<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessShortLinkTracking;
use App\Models\Shorten;
use Illuminate\Http\Request;

class TrackShortLinkController extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'page_url' => ['required', 'url', 'max:2048'],
            'destination_url' => ['required', 'url', 'max:2048'],
            'referrer' => ['nullable', 'string', 'max:2048'],
            'language' => ['nullable', 'string', 'max:32'],
            'timezone' => ['nullable', 'string', 'max:128'],
            'screen_width' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'screen_height' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'utm_source' => ['nullable', 'string', 'max:255'],
            'utm_medium' => ['nullable', 'string', 'max:255'],
            'utm_campaign' => ['nullable', 'string', 'max:255'],
            'utm_term' => ['nullable', 'string', 'max:255'],
            'utm_content' => ['nullable', 'string', 'max:255'],
        ]);

        $shorten = Shorten::query()->where('code', $validated['code'])->first();

        if (! $shorten) {
            return response()->noContent(404);
        }

        ProcessShortLinkTracking::dispatch($shorten->id, $validated, [
            'ip' => $request->ip(),
            'user_agent' => (string) ($request->userAgent() ?? ''),
            'country' => (string) ($request->header('CF-IPCountry') ?: $request->header('X-Country-Code') ?: 'Unknown'),
        ]);

        return response()->noContent();
    }
}
