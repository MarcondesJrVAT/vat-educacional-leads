<?php
// Script de teste para envio de email usando EmailService
// Não imprime senhas nem credenciais.

require_once __DIR__ . '/../vendor/autoload.php';
// carregar .env (se disponível) — usar load() para garantir que variáveis são definidas
if (class_exists('Dotenv\\Dotenv')) {
    try {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();
    } catch (Exception $e) {
        // ignore
    }
}

// (sem prints sensíveis)
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/models/Lead.php';
require_once __DIR__ . '/../app/models/EmailService.php';

// Dados de teste - altere o email destino se quiser
// usar como destinatário: TEST_DESTINATION_EMAIL (se setado) -> FROM_EMAIL (constante) -> SMTP_USERNAME -> vazio
$dest = getenv('TEST_DESTINATION_EMAIL') ?: (defined('FROM_EMAIL') ? FROM_EMAIL : (getenv('SMTP_USERNAME') ?: ''));
$testData = [
    'nome' => 'Teste Envio',
    'email' => $dest,
    'telefone' => '000000000',
    'descricao' => 'Teste automático de envio via PHPMailer'
];

$lead = new Lead($testData);
$lead->sanitize();

echo "Tentando enviar e-mail para: " . $lead->getEmail() . PHP_EOL;


$sent = EmailService::sendLeadEmail($lead);
if ($sent) {
    echo "sendLeadEmail: OK" . PHP_EOL;
} else {
    echo "sendLeadEmail: FALHOU" . PHP_EOL;
}

$adminNotified = EmailService::sendAdminNotification($lead);
if ($adminNotified) {
    echo "sendAdminNotification: OK" . PHP_EOL;
} else {
    echo "sendAdminNotification: FALHOU" . PHP_EOL;
}

// Exit code 0 if both OK, else 1
if ($sent && $adminNotified) {
    exit(0);
}
exit(1);
