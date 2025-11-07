<?php
// Script para envio de email em background por lead_id
require_once __DIR__ . '/../vendor/autoload.php';
// carregar .env
if (class_exists('Dotenv\\Dotenv')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/models/Lead.php';
require_once __DIR__ . '/../app/models/EmailService.php';

$id = $argv[1] ?? null;
if (!$id) {
    exit(1);
}

$lead = Lead::findById($id);
if (!$lead) {
    exit(1);
}

// enviar email para o lead e notificar admin
EmailService::sendLeadEmail($lead);
EmailService::sendAdminNotification($lead);

exit(0);
