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
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')
                ->nullable()
                ->constrained('pacientes')
                ->onDelete('set null');
            $table->foreignId('medico_id')
                ->constrained('medicos')
                ->onDelete('cascade');
            $table->dateTime('data_hora');
            $table->string('nome_paciente');
            $table->date('data_nascimento');
            $table->string('nome_responsavel')->nullable();
            $table->enum('status', ['pendente', 'confirmada', 'cancelada', 'realizada'])->default('pendente');
            $table->text('observacoes')->nullable();
            $table->timestamps();
            
            $table->index(['medico_id', 'data_hora']);
            $table->index(['paciente_id', 'data_hora']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendamentos');
    }
};
