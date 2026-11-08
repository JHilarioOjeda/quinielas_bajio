<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class PopularTeamsSeeder extends Seeder
{
    /**
     * Seed 50 popular clubs from Mexico, Spain, England and other frequent
     * UEFA Champions League participants. Running this seeder more than once
     * updates matching team names instead of creating duplicates.
     */
    public function run(): void
    {
        foreach ($this->teams() as [$name, $shortName]) {
            Team::updateOrCreate(
                ['name' => $name],
                ['short_name' => $shortName],
            );
        }
    }

    /**
     * @return array<int, array{0: string, 1: string}>
     */
    private function teams(): array
    {
        return [
            // Mexico (18)
            ['América', 'AME'],
            ['Chivas', 'CHI'],
            ['Cruz Azul', 'CAZ'],
            ['Pumas UNAM', 'PUM'],
            ['Tigres UANL', 'TIG'],
            ['Monterrey', 'MTY'],
            ['Club León', 'LEO'],
            ['Pachuca', 'PAC'],
            ['Toluca', 'TOL'],
            ['Santos Laguna', 'SAN'],
            ['Atlas', 'ATL'],
            ['Necaxa', 'NEC'],
            ['Puebla', 'PUE'],
            ['Querétaro', 'QRO'],
            ['Tijuana', 'TIJ'],
            ['FC Juárez', 'JUA'],
            ['Atlético de San Luis', 'ASL'],
            ['Mazatlán FC', 'MAZ'],

            // Spain (11)
            ['Real Madrid', 'RMA'],
            ['Barcelona', 'BAR'],
            ['Atlético de Madrid', 'ATM'],
            ['Athletic Club', 'ATH'],
            ['Sevilla', 'SEV'],
            ['Valencia', 'VAL'],
            ['Villarreal', 'VIL'],
            ['Real Sociedad', 'RSO'],
            ['Real Betis', 'BET'],
            ['Girona', 'GIR'],
            ['Espanyol', 'ESP'],

            // England (11)
            ['Manchester United', 'MUN'],
            ['Manchester City', 'MCI'],
            ['Liverpool', 'LIV'],
            ['Arsenal', 'ARS'],
            ['Chelsea', 'CHE'],
            ['Tottenham Hotspur', 'TOT'],
            ['Newcastle United', 'NEW'],
            ['Aston Villa', 'AVL'],
            ['Everton', 'EVE'],
            ['West Ham United', 'WHU'],
            ['Nottingham Forest', 'NFO'],

            // Other popular Champions League clubs (10)
            ['Bayern Múnich', 'BAY'],
            ['Borussia Dortmund', 'BVB'],
            ['Bayer Leverkusen', 'B04'],
            ['Paris Saint-Germain', 'PSG'],
            ['Inter de Milán', 'INT'],
            ['AC Milan', 'MIL'],
            ['Juventus', 'JUV'],
            ['Napoli', 'NAP'],
            ['Ajax', 'AJA'],
            ['Benfica', 'BEN'],
        ];
    }
}
