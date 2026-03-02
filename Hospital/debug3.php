<?php
require 'vendor/autoload.php';
$app = require_once('bootstrap/app.php');
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

// Limpar a tabela
DB::table('medico_availabilities')->truncate();
echo "Tabela limpa!\n";

// Recriar
\Artisan::call('db:seed', ['--class' => 'MedicoAvailabilitySeeder']);
echo "Seed executado!\n";

// Verificar novamente
$result = DB::table('medico_availabilities')
    ->where('medico_id', 1)
    ->select('data', 'hora_inicio')
    ->distinct()
    ->orderBy('data')
    ->limit(5)
    ->get();

echo "\nDatas após recreação:\n";
foreach ($result as $row) {
    echo "Data: {$row->data}, Hora: {$row->hora_inicio}\n";
}
