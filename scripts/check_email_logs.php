<?php
require_once __DIR__ . '/../vendor/autoload.php';
if (class_exists('Dotenv\\Dotenv')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}
require_once __DIR__ . '/../config/config.php';

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $leadId = $argv[1] ?? null;
    $email = $argv[2] ?? null;

    if ($leadId) {
        $sql = "SELECT * FROM email_logs WHERE lead_id = :id ORDER BY data_envio DESC LIMIT 10";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $leadId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "email_logs for lead_id={$leadId}:\n";
        print_r($rows);
    }

    if ($email) {
        $sql = "SELECT * FROM email_logs WHERE email_destinatario = :email ORDER BY data_envio DESC LIMIT 10";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "email_logs for email={$email}:\n";
        print_r($rows);
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

return 0;
