<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste do Sistema MVC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold mb-6 text-indigo-600">
            <i class="fas fa-flask mr-2"></i>Teste do Sistema MVC - Captação de Leads
        </h1>
        
        <!-- Informações da Estrutura -->
        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
            <h2 class="font-bold text-lg mb-2">📁 Estrutura MVC</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div class="bg-white p-3 rounded">
                    <strong class="text-blue-600">Model</strong>
                    <ul class="mt-2 space-y-1 text-gray-600">
                        <li>✓ Lead.php</li>
                        <li>✓ EmailService.php</li>
                    </ul>
                </div>
                <div class="bg-white p-3 rounded">
                    <strong class="text-green-600">View</strong>
                    <ul class="mt-2 space-y-1 text-gray-600">
                        <li>✓ create.php</li>
                        <li>✓ success.php</li>
                    </ul>
                </div>
                <div class="bg-white p-3 rounded">
                    <strong class="text-purple-600">Controller</strong>
                    <ul class="mt-2 space-y-1 text-gray-600">
                        <li>✓ LeadController.php</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <!-- Testes PHP -->
            <div class="p-4 bg-green-50 rounded-lg">
                <h2 class="font-bold text-lg mb-2">🧪 Testes do Sistema</h2>
                
                <h3 class="font-semibold mt-4 mb-2">1. Versão do PHP:</h3>
                <div class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm">
                    <?php echo "PHP " . phpversion(); ?>
                </div>

                <h3 class="font-semibold mt-4 mb-2">2. Caminhos definidos:</h3>
                <div class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm overflow-auto">
                    <?php
                    $paths = [
                        'BASE_PATH' => dirname(__DIR__),
                        'APP_PATH' => dirname(__DIR__) . '/app',
                        'CONFIG_PATH' => dirname(__DIR__) . '/config',
                        'STORAGE_PATH' => dirname(__DIR__) . '/storage',
                        'PUBLIC_PATH' => dirname(__DIR__) . '/public',
                    ];
                    
                    foreach ($paths as $name => $path) {
                        $exists = is_dir($path) ? '✓' : '✗';
                        echo "$exists $name: $path\n";
                    }
                    ?>
                </div>

                <h3 class="font-semibold mt-4 mb-2">3. Arquivo de configuração:</h3>
                <div class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm">
                    <?php 
                    $configFile = dirname(__DIR__) . '/config/config.php';
                    if (file_exists($configFile)) {
                        echo "✅ config/config.php encontrado\n";
                        require_once $configFile;
                        echo "📧 FROM_EMAIL: " . FROM_EMAIL . "\n";
                        echo "📧 ADMIN_EMAIL: " . ADMIN_EMAIL . "\n";
                        echo "🌐 SITE_NAME: " . SITE_NAME;
                    } else {
                        echo "❌ config/config.php NÃO encontrado";
                    }
                    ?>
                </div>

                <h3 class="font-semibold mt-4 mb-2">4. Arquivo PDF:</h3>
                <div class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm">
                    <?php 
                    $pdfFile = dirname(__DIR__) . '/public/assets/pdf/sample-course.pdf';
                    if (file_exists($pdfFile)) {
                        $filesize = filesize($pdfFile);
                        echo "✅ PDF encontrado (" . round($filesize/1024, 2) . " KB)\n";
                        echo "   Caminho: public/assets/pdf/sample-course.pdf";
                    } else {
                        echo "⚠️ PDF NÃO encontrado\n";
                        echo "   Crie em: public/assets/pdf/sample-course.pdf";
                    }
                    ?>
                </div>

                <h3 class="font-semibold mt-4 mb-2">5. Diretórios de armazenamento:</h3>
                <div class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm">
                    <?php 
                    $storageDirs = [
                        'storage/data' => dirname(__DIR__) . '/storage/data',
                        'storage/logs' => dirname(__DIR__) . '/storage/logs',
                    ];
                    
                    foreach ($storageDirs as $name => $path) {
                        if (is_dir($path)) {
                            $writable = is_writable($path) ? '(gravável)' : '(não gravável)';
                            echo "✅ $name existe $writable\n";
                        } else {
                            echo "❌ $name NÃO existe\n";
                        }
                    }
                    ?>
                </div>

                <h3 class="font-semibold mt-4 mb-2">6. Classes MVC:</h3>
                <div class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm">
                    <?php 
                    $classes = [
                        'LeadController' => dirname(__DIR__) . '/app/controllers/LeadController.php',
                        'Lead' => dirname(__DIR__) . '/app/models/Lead.php',
                        'EmailService' => dirname(__DIR__) . '/app/models/EmailService.php',
                    ];
                    
                    foreach ($classes as $className => $path) {
                        if (file_exists($path)) {
                            echo "✅ $className encontrado\n";
                        } else {
                            echo "❌ $className NÃO encontrado\n";
                        }
                    }
                    ?>
                </div>

                <h3 class="font-semibold mt-4 mb-2">7. Views:</h3>
                <div class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm">
                    <?php 
                    $views = [
                        'create.php' => dirname(__DIR__) . '/app/views/leads/create.php',
                        'success.php' => dirname(__DIR__) . '/app/views/leads/success.php',
                    ];
                    
                    foreach ($views as $viewName => $path) {
                        if (file_exists($path)) {
                            echo "✅ $viewName encontrado\n";
                        } else {
                            echo "❌ $viewName NÃO encontrado\n";
                        }
                    }
                    ?>
                </div>

                <h3 class="font-semibold mt-4 mb-2">8. Função mail():</h3>
                <div class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm">
                    <?php 
                    if (function_exists('mail')) {
                        echo "✅ Função mail() disponível";
                    } else {
                        echo "❌ Função mail() NÃO disponível";
                    }
                    ?>
                </div>
            </div>

            <!-- Links Rápidos -->
            <div class="p-4 bg-yellow-50 rounded-lg">
                <h2 class="font-bold text-lg mb-2">🔗 Links Rápidos</h2>
                <div class="space-y-2">
                    <a href="/" class="block text-blue-600 hover:underline font-semibold text-lg">
                        → Formulário de Captação (/)
                    </a>
                    <p class="text-sm text-gray-600 ml-4">Rota principal do sistema MVC</p>
                </div>
            </div>

            <!-- Informações do Servidor -->
            <div class="p-4 bg-purple-50 rounded-lg">
                <h2 class="font-bold text-lg mb-2">⚙️ Informações do Servidor</h2>
                <div class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm">
                    <?php
                    echo "Servidor: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
                    echo "Método: " . $_SERVER['REQUEST_METHOD'] . "\n";
                    echo "URI: " . $_SERVER['REQUEST_URI'] . "\n";
                    echo "Script: " . $_SERVER['SCRIPT_NAME'];
                    ?>
                </div>
            </div>

            <!-- Status Geral -->
            <div class="mt-6 p-6 rounded-lg text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h2 class="font-bold text-2xl mb-2 text-white">
                    <?php
                    $allOk = file_exists($configFile) && 
                             is_dir(dirname(__DIR__) . '/app/controllers') &&
                             is_dir(dirname(__DIR__) . '/app/models') &&
                             is_dir(dirname(__DIR__) . '/app/views');
                    
                    if ($allOk) {
                        echo "✅ Sistema MVC Pronto!";
                    } else {
                        echo "⚠️ Verifique as configurações";
                    }
                    ?>
                </h2>
                <p class="mb-4 text-white">
                    <?php if ($allOk): ?>
                        Arquitetura MVC implementada com sucesso
                    <?php else: ?>
                        Alguns componentes precisam ser verificados
                    <?php endif; ?>
                </p>
                <a href="/" class="inline-block bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    Ir para o Formulário →
                </a>
            </div>
        </div>
    </div>
</body>
</html>
