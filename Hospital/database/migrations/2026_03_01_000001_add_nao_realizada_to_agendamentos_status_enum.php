<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE agendamentos MODIFY status ENUM('pendente', 'confirmada', 'cancelada', 'realizada', 'nao_realizada') NOT NULL DEFAULT 'pendente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE agendamentos SET status = 'cancelada' WHERE status = 'nao_realizada'");
        DB::statement("ALTER TABLE agendamentos MODIFY status ENUM('pendente', 'confirmada', 'cancelada', 'realizada') NOT NULL DEFAULT 'pendente'");
    }
};
