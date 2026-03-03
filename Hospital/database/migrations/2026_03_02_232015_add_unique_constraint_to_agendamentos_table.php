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
        Schema::table('agendamentos', function (Blueprint $table) {
            // Adiciona índice único composto para prevenir agendamentos duplicados
            // Mesmo paciente não pode ter múltiplos agendamentos com mesmo médico no mesmo horário
            $table->unique(['paciente_id', 'medico_id', 'data_hora'], 'unique_agendamento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->dropUnique('unique_agendamento');
        });
    }
};
