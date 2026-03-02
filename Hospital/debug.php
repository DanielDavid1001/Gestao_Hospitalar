<?php
require 'vendor/autoload.php';
$app = require_once('bootstrap/app.php');
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$result = DB::table('medico_availabilities')
    ->where('medico_id', 1)
    ->where('data', '2026-03-03')
    ->select('id', 'medico_id', 'data', 'hora_inicio', 'hora_fim', 'periodo')
    ->limit(5)
    ->get();

echo "Total encontrado: " . count($result) . "\n";
foreach ($result as $row) {
    echo "ID: {$row->id}, Médico: {$row->medico_id}, Data: {$row->data}, Hora Inicio: {$row->hora_inicio}, Hora Fim: {$row->hora_fim}, Período: {$row->periodo}\n";
}
