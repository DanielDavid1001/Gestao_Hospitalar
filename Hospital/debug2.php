<?php
require 'vendor/autoload.php';
$app = require_once('bootstrap/app.php');
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$result = DB::table('medico_availabilities')
    ->where('medico_id', 1)
    ->select('data')
    ->distinct()
    ->orderBy('data')
    ->limit(10)
    ->get();

echo "Datas com disponibilidades para médico 1:\n";
foreach ($result as $row) {
    echo "- {$row->data}\n";
}

echo "\nData de hoje: " . date('Y-m-d') . "\n";
echo "Amanhã: " . date('Y-m-d', strtotime('+1 day')) . "\n";
