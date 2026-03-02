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
        Schema::create('medico_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medico_id')
                ->constrained('medicos')
                ->onDelete('cascade');
            $table->date('data'); // dd/mm/yyyy
            $table->time('hora_inicio'); // Hora de início
            $table->time('hora_fim'); // Hora de fim
            $table->enum('periodo', ['manhã', 'tarde', 'noite']); // Período do dia
            $table->timestamps();
            
            // Índice para buscar disponibilidades por médico e data
            $table->index(['medico_id', 'data']);
            
            // Evitar duplicatas (um médico não pode ter dois horários iguais na mesma data)
            $table->unique(['medico_id', 'data', 'hora_inicio', 'hora_fim', 'periodo'], 'medico_avail_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medico_availabilities');
    }
};
