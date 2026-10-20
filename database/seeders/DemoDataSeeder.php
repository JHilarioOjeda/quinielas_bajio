<?php

namespace Database\Seeders;

use App\Models\EventMatch;
use App\Models\MatchGame;
use App\Models\Prediction;
use App\Models\QuinielaEvent;
use App\Models\Team;
use App\Models\Ticket;
use App\Models\TicketMatch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Llena la base con datos de ejemplo realistas para probar el flujo completo
 * en local: equipos, una jornada abierta para apostar y una jornada ya
 * finalizada con resultados (para ver el historial). Se puede correr varias
 * veces: limpia las jornadas "Demo" anteriores antes de recrearlas.
 */
class DemoDataSeeder extends Seeder
{
    private const TITLE_PREFIX = 'Demo:';

    private array $teamNames = [
        ['Club León', 'LEO'],
        ['Querétaro', 'QRO'],
        ['Atlas', 'ATL'],
        ['Chivas', 'CHI'],
        ['América', 'AME'],
        ['Cruz Azul', 'CAZ'],
        ['Pumas UNAM', 'PUM'],
        ['Tigres UANL', 'TIG'],
    ];

    public function run(): void
    {
        $this->command?->info('Limpiando jornadas demo anteriores...');
        $this->cleanPreviousDemoData();

        $teams = collect($this->teamNames)->map(
            fn ($t) => Team::firstOrCreate(['name' => $t[0]], ['short_name' => $t[1]])
        );

        $this->command?->info('Creando jornada histórica (finalizada)...');
        $this->createFinishedJornada($teams);

        $this->command?->info('Creando jornada activa (abierta para apostar)...');
        $this->createOpenJornada($teams);

        $this->command?->info('Listo.');
    }

    private function cleanPreviousDemoData(): void
    {
        $previousEvents = QuinielaEvent::where('title', 'like', self::TITLE_PREFIX.'%')->get();

        foreach ($previousEvents as $event) {
            $matchIds = $event->eventMatches()->pluck('match_id');
            $event->tickets()->each(function (Ticket $ticket) {
                $ticket->ticketMatches()->each(function (TicketMatch $tm) {
                    $tm->predictions()->delete();
                });
                $ticket->ticketMatches()->delete();
            });
            $event->tickets()->delete();
            $event->eventMatches()->delete();
            MatchGame::whereIn('id', $matchIds)->delete();
            $event->delete();
        }
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Team>  $teams
     */
    private function createFinishedJornada($teams): void
    {
        $event = QuinielaEvent::create([
            'title' => self::TITLE_PREFIX.' Jornada 14',
            'price' => 20,
            'sales_start' => now()->subWeeks(3),
            'sales_end' => now()->subWeeks(2)->subDay(),
            'play_start' => now()->subWeeks(2),
            'play_end' => now()->subWeeks(2)->addDays(2),
            'status' => 'finished',
            'apply_substitute' => true,
        ]);

        // [home, away, home_score, away_score]
        $fixtures = [
            [0, 1, 2, 1], // León 2-1 Querétaro
            [2, 3, 0, 0], // Atlas 0-0 Chivas
            [4, 5, 1, 1], // América 1-1 Cruz Azul
            [6, 7, 3, 2], // Pumas 3-2 Tigres
            [1, 4, 0, 2], // Querétaro 0-2 América
        ];

        $eventMatches = $this->createMatches($event, $teams, $fixtures, now()->subWeeks(2), finished: true);
        $substitute = $this->createSubstitute($event, $teams, 2, 6, now()->subWeeks(2)->addDay(), score: [1, 1]);

        // folio => [predicciones por partido regular en el mismo orden de $fixtures, prediccion suplente, pagado]
        $tickets = [
            ['Carlos Mendoza', '4611234567', ['1', 'X', 'X', '1', '2'], 'X', true],
            ['Ana Patricia Ruiz', '4427654321', ['1', 'X', 'X', '1', '2'], 'X', true],   // ganadora exacta, igual que la anterior
            ['Jorge Luis Hernández', '4612223344', ['1', '1', '1', '2', '1'], '1', true],
            ['María Fernanda López', '4425556677', ['2', 'X', '1', '1', '2'], 'X', true],
            ['Roberto Torres', '4618889900', ['1', 'X', 'X', '1', '1'], 'X', true],      // 4 aciertos
            ['Lucía Ramírez', '4421112233', ['1', '2', 'X', '2', '2'], '2', false],      // pendiente de pago
            ['Diego Alejandro Castro', '4614445566', ['2', 'X', '2', '1', '1'], 'X', true],
            ['Fernanda Ortiz', '4427778899', ['1', '1', 'X', '1', '2'], '1', true],
            ['Sergio Pérez', '4613336622', ['X', 'X', '1', '2', '2'], 'X', true],
            ['Paola Jiménez', '4429998811', ['1', 'X', 'X', '1', '2'], 'X', false],      // ganadora exacta, pero sin pagar (no cuenta)
            ['Hugo Martínez', '4615552211', ['2', '1', '2', '1', '1'], '2', true],
            ['Daniela Soto', '4428887766', ['1', 'X', '1', 'X', '2'], 'X', true],
        ];

        foreach ($tickets as [$name, $phone, $preds, $subPred, $paid]) {
            $this->createTicket($event, $eventMatches, $substitute, $name, $phone, 20, $preds, $subPred, $paid, active: false);
        }
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Team>  $teams
     */
    private function createOpenJornada($teams): void
    {
        $event = QuinielaEvent::create([
            'title' => self::TITLE_PREFIX.' Jornada 15',
            'price' => 20,
            'sales_start' => now()->subDays(2),
            'sales_end' => now()->addDays(4),
            'play_start' => now()->addDays(5),
            'play_end' => now()->addDays(7),
            'status' => 'open',
            'apply_substitute' => true,
        ]);

        // Mismos emparejamientos pero sin resultado todavia (jornada por jugarse)
        $fixtures = [
            [1, 0, null, null], // Querétaro vs León
            [3, 2, null, null], // Chivas vs Atlas
            [5, 4, null, null], // Cruz Azul vs América
            [7, 6, null, null], // Tigres vs Pumas
            [0, 3, null, null], // León vs Chivas
        ];

        $eventMatches = $this->createMatches($event, $teams, $fixtures, now()->addDays(6), finished: false);
        $substitute = $this->createSubstitute($event, $teams, 4, 7, now()->addDays(6)->addHours(2), score: [null, null]);

        $tickets = [
            ['Valeria Gómez', '4616667788', ['1', 'X', '2', '1', '2'], '1', true],
            ['Emilio Vargas', '4423334455', ['2', '1', 'X', '2', '1'], 'X', true],
            ['Monserrat Flores', '4612229988', ['1', '1', '2', '1', '1'], '2', false],
            ['Alejandro Ríos', '4427776655', ['X', 'X', '1', 'X', '2'], 'X', true],
            ['Karla Núñez', '4614443322', ['1', '2', '2', '1', '1'], '1', true],
            ['Iván Morales', '4618882211', ['2', 'X', '1', '2', 'X'], '2', false],
        ];

        foreach ($tickets as [$name, $phone, $preds, $subPred, $paid]) {
            $this->createTicket($event, $eventMatches, $substitute, $name, $phone, 20, $preds, $subPred, $paid, active: true);
        }
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Team>  $teams
     * @param  array<int, array{0:int,1:int,2:?int,3:?int}>  $fixtures
     * @return \Illuminate\Support\Collection<int, EventMatch>
     */
    private function createMatches(QuinielaEvent $event, $teams, array $fixtures, $matchDate, bool $finished)
    {
        return collect($fixtures)->map(function ($fixture, $i) use ($event, $teams, $matchDate, $finished) {
            [$homeIdx, $awayIdx, $homeScore, $awayScore] = $fixture;

            $match = MatchGame::create([
                'home_team_id' => $teams[$homeIdx]->id,
                'away_team_id' => $teams[$awayIdx]->id,
                'match_date' => $matchDate->copy()->addHours($i),
                'home_score' => $homeScore,
                'away_score' => $awayScore,
                'status' => $finished ? 'finished' : 'pending',
            ]);

            return EventMatch::create([
                'quiniela_event_id' => $event->id,
                'match_id' => $match->id,
                'is_substitute' => false,
            ]);
        });
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Team>  $teams
     * @param  array{0:?int,1:?int}  $score
     */
    private function createSubstitute(QuinielaEvent $event, $teams, int $homeIdx, int $awayIdx, $matchDate, array $score): EventMatch
    {
        $match = MatchGame::create([
            'home_team_id' => $teams[$homeIdx]->id,
            'away_team_id' => $teams[$awayIdx]->id,
            'match_date' => $matchDate,
            'home_score' => $score[0],
            'away_score' => $score[1],
            'status' => $score[0] !== null ? 'finished' : 'pending',
        ]);

        return EventMatch::create([
            'quiniela_event_id' => $event->id,
            'match_id' => $match->id,
            'is_substitute' => true,
        ]);
    }

    /**
     * @param  \Illuminate\Support\Collection<int, EventMatch>  $eventMatches
     * @param  array<int, string>  $predictions  '1'|'X'|'2' en el mismo orden que $eventMatches
     */
    private function createTicket(
        QuinielaEvent $event,
        $eventMatches,
        EventMatch $substitute,
        string $playerName,
        string $phone,
        float $amount,
        array $predictions,
        string $subPrediction,
        bool $paid,
        bool $active
    ): void {
        $ticket = Ticket::create([
            'quiniela_event_id' => $event->id,
            'folio' => 'Q'.$event->id.'-'.strtoupper(substr(md5($playerName.$event->id), 0, 6)),
            'player_name' => $playerName,
            'phone_number' => $phone,
            'amount_paid' => $amount,
            'payment_status' => $paid ? Ticket::PAYMENT_PAID : Ticket::PAYMENT_PENDING,
            'active' => $active,
        ]);

        foreach ($eventMatches as $i => $eventMatch) {
            $tm = TicketMatch::create([
                'ticket_id' => $ticket->id,
                'match_id' => $eventMatch->match_id,
            ]);

            Prediction::create([
                'ticket_match_id' => $tm->id,
                'selection' => $predictions[$i],
            ]);
        }

        $subTm = TicketMatch::create([
            'ticket_id' => $ticket->id,
            'match_id' => $substitute->match_id,
        ]);

        Prediction::create([
            'ticket_match_id' => $subTm->id,
            'selection' => $subPrediction,
        ]);
    }
}
