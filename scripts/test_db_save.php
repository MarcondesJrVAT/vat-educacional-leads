<?php
require_once __DIR__ . '/../vendor/autoload.php';
// carregar .env
if (class_exists('Dotenv\\Dotenv')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/models/Lead.php';

$data = [
    'nome' => 'DB Test',
    'email' => 'dbtest@example.com',
    'telefone' => '0000000',
    'descricao' => 'Teste de salvamento no DB'
];

$lead = new Lead($data);
$lead->sanitize();
$errors = $lead->validate();
if (!empty($errors)) {
    echo "Validation errors: \n" . implode("\n", $errors) . PHP_EOL;
    exit(1);
}

$ok = $lead->saveToDatabase();
if ($ok) {
    echo "saveToDatabase: OK\n";
    exit(0);
} else {
    echo "saveToDatabase: FALHOU\n";
    // mostrar log se existir
    $log = dirname(__DIR__) . '/storage/logs/app.log';
    if (file_exists($log)) {
        echo "Últimas linhas do log: \n";
        echo shell_exec('tail -n 30 ' . escapeshellarg($log));
    }
    exit(1);
}
