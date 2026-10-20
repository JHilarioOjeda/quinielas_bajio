<?php

namespace App\Domain\Quiniela;

use App\Models\MatchGame;

class MatchResultCalculator
{
    public static function fromScores(?int $homeScore, ?int $awayScore): ?string
    {
        if ($homeScore === null || $awayScore === null) {
            return null;
        }

        if ($homeScore > $awayScore) {
            return '1';
        }

        if ($homeScore < $awayScore) {
            return '2';
        }

        return 'X';
    }

    public static function forMatch(?MatchGame $match): ?string
    {
        return $match ? self::fromScores($match->home_score, $match->away_score) : null;
    }
}
