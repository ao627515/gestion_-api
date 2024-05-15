<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hall_ticket', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')
                ->constrained(table: 'tickets', column: 'tiket_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('hall_id')
                ->constrained(table: 'halls', column: 'id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hall_ticket');
    }
};
