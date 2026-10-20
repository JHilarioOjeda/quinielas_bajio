<?php

use App\Models\Ticket;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cualquier valor fuera del catalogo (incluido null, por la columna
        // text() original) se normaliza antes de restringir la columna.
        DB::table('tickets')
            ->where(function ($query) {
                $query->whereNull('payment_status')
                    ->orWhereNotIn('payment_status', Ticket::PAYMENT_STATUSES);
            })
            ->update(['payment_status' => Ticket::PAYMENT_PENDING]);

        DB::statement(
            "ALTER TABLE tickets MODIFY payment_status ENUM('pendiente', 'pagado') NOT NULL DEFAULT 'pendiente'"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE tickets MODIFY payment_status TEXT NULL');
    }
};
