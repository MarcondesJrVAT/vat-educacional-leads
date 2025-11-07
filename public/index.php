<?php
/**
 * Front Controller - Ponto de entrada da aplicação
 */

// Configurações de segurança para sessão
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);
}
session_start();

// Headers de segurança
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer-when-downgrade');
header('X-XSS-Protection: 1; mode=block');

// Definir o caminho base
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');
define('STORAGE_PATH', BASE_PATH . '/storage');
define('PUBLIC_PATH', BASE_PATH . '/public');

// Carregar configurações
require_once CONFIG_PATH . '/config.php';

// Autoloader simples
// SUGESTÃO: Migrar para Composer/PSR-4 para projetos maiores
spl_autoload_register(function ($class) {
    $paths = [
        APP_PATH . '/controllers/' . $class . '.php',
        APP_PATH . '/models/' . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Roteamento simples
$request = $_SERVER['REQUEST_URI'];
$request = str_replace('/index.php', '', $request);
$request = parse_url($request, PHP_URL_PATH);
$request = trim($request, '/');

// Rotas
switch ($request) {
    case '':
    case 'leads':
    case 'leads/create':
        $controller = new LeadController();
        $controller->create();
        break;
    
    case 'leads/store':
        $controller = new LeadController();
        $controller->store();
        break;
    
    case 'leads/success':
        $controller = new LeadController();
        $controller->success();
        break;
    
    case 'test':
        require_once PUBLIC_PATH . '/test.php';
        break;
    
    default:
        http_response_code(404);
        echo '404 - Página não encontrada';
        break;
}
?>
