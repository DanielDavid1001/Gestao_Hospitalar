<?php

require 'vendor/autoload.php';
require 'bootstrap/app.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$admin = \App\Models\Admin::first();
$user = \App\Models\User::first();

echo "Total de usuários: " . \App\Models\User::count() . PHP_EOL;
echo "Total de admins: " . \App\Models\Admin::count() . PHP_EOL;

if ($admin) {
    echo "Admin encontrado: " . $admin->user->name . " (" . $admin->user->email . ")" . PHP_EOL;
} else {
    echo "Nenhum admin encontrado!" . PHP_EOL;
}

if ($user) {
    echo "Primeiro usuário: " . $user->name . " - Rol: " . $user->role . PHP_EOL;
}
