<?php

namespace App\Services;

use App\Models\QuinielaEvent;

class MatchResultsVersion
{
    /** Genera una firma estable solo con los datos que afectan los marcadores. */
    public function forEvent(?QuinielaEvent $event): ?string
    {
        if (! $event) {
            return null;
        }

        $matches = $event->eventMatches->sortBy('id')->map(fn ($eventMatch) => [
            'id' => $eventMatch->id,
            'match_id' => $eventMatch->match_id,
            'substitute' => (bool) $eventMatch->is_substitute,
            'status' => $eventMatch->matchGame?->status,
            'home_score' => $eventMatch->matchGame?->home_score,
            'away_score' => $eventMatch->matchGame?->away_score,
        ])->values()->all();

        return hash('sha256', json_encode([$event->id, $matches], JSON_THROW_ON_ERROR));
    }
}
