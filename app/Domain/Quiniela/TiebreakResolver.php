<?php

namespace App\Domain\Quiniela;

use Illuminate\Support\Collection;

class TiebreakResolver
{
    /**
     * Si hay mas de este numero de empatados en 1er o 2do lugar, se usa el
     * partido suplente como diferenciador.
     */
    public const TIE_THRESHOLD = 20;

    /**
     * @param  Collection  $rows  filas con un campo de puntos y uno de acierto de suplente
     * @return array{firstPlace: Collection, firstTiebreakerApplied: bool, secondPlace: Collection, secondTiebreakerApplied: bool, maxPoints: int}
     */
    public static function resolve(Collection $rows, string $pointsField, string $subHitField, bool $subResultAvailable): array
    {
        $firstPlace = collect();
        $firstTiebreakerApplied = false;
        $secondPlace = collect();
        $secondTiebreakerApplied = false;

        $maxPoints = $rows->max($pointsField) ?? 0;

        if ($maxPoints > 0) {
            [$firstPlace, $firstTiebreakerApplied] = self::applyTiebreak(
                $rows->where($pointsField, $maxPoints), $subHitField, $subResultAvailable
            );

            $secondPoints = $rows->where($pointsField, '<', $maxPoints)->max($pointsField);

            if ($secondPoints !== null && $secondPoints > 0) {
                [$secondPlace, $secondTiebreakerApplied] = self::applyTiebreak(
                    $rows->where($pointsField, $secondPoints), $subHitField, $subResultAvailable
                );
            }
        }

        return compact('firstPlace', 'firstTiebreakerApplied', 'secondPlace', 'secondTiebreakerApplied', 'maxPoints');
    }

    /**
     * @return array{0: Collection, 1: bool}
     */
    protected static function applyTiebreak(Collection $tied, string $subHitField, bool $subResultAvailable): array
    {
        if ($subResultAvailable && $tied->count() > self::TIE_THRESHOLD) {
            $withSubHit = $tied->where($subHitField, true);
            if ($withSubHit->isNotEmpty()) {
                return [$withSubHit->values(), true];
            }
        }

        return [$tied->values(), false];
    }
}
