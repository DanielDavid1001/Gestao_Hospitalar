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
        Schema::table('medicos', function (Blueprint $table) {
            $table->string('crm', 20)->nullable()->change();
            $table->string('especialidade')->nullable()->change();
        });

        Schema::table('pacientes', function (Blueprint $table) {
            $table->string('cpf', 14)->nullable()->change();
            $table->date('data_nascimento')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medicos', function (Blueprint $table) {
            $table->string('crm', 20)->nullable(false)->change();
            $table->string('especialidade')->nullable(false)->change();
        });

        Schema::table('pacientes', function (Blueprint $table) {
            $table->string('cpf', 14)->nullable(false)->change();
            $table->date('data_nascimento')->nullable(false)->change();
        });
    }
};
