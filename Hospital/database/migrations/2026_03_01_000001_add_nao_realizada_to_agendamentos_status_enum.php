<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();
        
        if ($driver === 'sqlite') {
            // SQLite não suporta MODIFY, então apenas adicionamos a coluna se não existir
            // ou atualizamos os valores existentes
            DB::statement("UPDATE agendamentos SET status = 'nao_realizada' WHERE status IN ('pendente', 'confirmada', 'cancelada', 'realizada', 'nao_realizada')");
        } else {
            DB::statement("ALTER TABLE agendamentos MODIFY status ENUM('pendente', 'confirmada', 'cancelada', 'realizada', 'nao_realizada') NOT NULL DEFAULT 'pendente'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();
        
        if ($driver === 'sqlite') {
            DB::statement("UPDATE agendamentos SET status = 'cancelada' WHERE status = 'nao_realizada'");
        } else {
            DB::statement("UPDATE agendamentos SET status = 'cancelada' WHERE status = 'nao_realizada'");
            DB::statement("ALTER TABLE agendamentos MODIFY status ENUM('pendente', 'confirmada', 'cancelada', 'realizada') NOT NULL DEFAULT 'pendente'");
        }
    }
};
