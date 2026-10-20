<?php

namespace App\Domain\Quiniela;

use App\Models\Ticket;

class TicketScorer
{
    /**
     * @param  iterable<\App\Models\EventMatch>  $eventMatches
     * @return array<int, string|null> match_id => '1'|'X'|'2'|null
     */
    public static function resultsByMatchId(iterable $eventMatches): array
    {
        $results = [];

        foreach ($eventMatches as $eventMatch) {
            $results[$eventMatch->match_id] = MatchResultCalculator::forMatch($eventMatch->matchGame);
        }

        return $results;
    }

    /**
     * @param  int[]  $regularMatchIds
     * @param  array<int, string|null>  $resultsByMatchId
     */
    public static function hits(Ticket $ticket, array $regularMatchIds, array $resultsByMatchId): int
    {
        $hits = 0;

        foreach ($ticket->ticketMatches as $ticketMatch) {
            if (! in_array($ticketMatch->match_id, $regularMatchIds, true)) {
                continue;
            }

            $result = $resultsByMatchId[$ticketMatch->match_id] ?? null;
            if ($result === null) {
                continue;
            }

            $selections = $ticketMatch->predictions->pluck('selection')->all();
            if (in_array($result, $selections, true)) {
                $hits++;
            }
        }

        return $hits;
    }

    /**
     * @param  array<int, string|null>  $resultsByMatchId
     */
    public static function substituteHit(Ticket $ticket, ?int $substituteMatchId, array $resultsByMatchId): bool
    {
        if ($substituteMatchId === null) {
            return false;
        }

        $result = $resultsByMatchId[$substituteMatchId] ?? null;
        if ($result === null) {
            return false;
        }

        foreach ($ticket->ticketMatches as $ticketMatch) {
            if ($ticketMatch->match_id !== $substituteMatchId) {
                continue;
            }

            $selections = $ticketMatch->predictions->pluck('selection')->all();

            return in_array($result, $selections, true);
        }

        return false;
    }
}
