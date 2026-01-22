<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiniela_event_id')->constrained('quiniela_events')->cascadeOnDelete();
            $table->text('folio')->nullable();
            $table->string('player_name', 100);
            $table->decimal('amount_paid', 10, 2)->nullable();
            $table->text('payment_status')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
