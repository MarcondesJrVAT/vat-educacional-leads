<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Captação de Leads - Curso</title>
    <script>
        // Aplicar tema antes da pintura para evitar FOUC
        (function() {
            try {
                const stored = localStorage.getItem('theme');
                const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (stored === 'dark' || (!stored && prefersDark)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (e) {}
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Config do Tailwind via CDN: ativar dark mode por classe
        try {
            tailwind.config = Object.assign({}, tailwind.config || {}, { darkMode: 'class' });
        } catch (e) {
            window.tailwind = window.tailwind || {};
            window.tailwind.config = { darkMode: 'class' };
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:bg-gradient-to-br dark:from-gray-900 dark:to-gray-800 min-h-screen flex items-center justify-center p-4 text-gray-900 dark:text-gray-100">
    <div class="max-w-2xl w-full">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-block p-4 bg-indigo-600 rounded-full mb-4 shadow-lg">
                <i class="fas fa-graduation-cap text-white text-4xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 dark:text-gray-100 mb-2">Curso Gratuito</h1>
            <p class="text-gray-600 dark:text-gray-300 text-lg">Cadastre-se e receba uma amostra grátis do nosso curso!</p>
        </div>

        <!-- Card do Formulário -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 md:p-12">
            <!-- Erros de Validação -->
            <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
                <div class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-300">Erro ao processar o formulário:</h3>
                            <ul class="mt-2 text-sm text-red-700 dark:text-red-300 list-disc list-inside">
                                <?php foreach ($_SESSION['errors'] as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>

            <!-- Benefícios -->
            <div class="mb-8 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-6">
                <h3 class="font-semibold text-indigo-900 dark:text-indigo-200 mb-3 flex items-center">
                    <i class="fas fa-gift mr-2"></i> O que você vai receber:
                </h3>
                <ul class="space-y-2 text-gray-700 dark:text-gray-300">
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        PDF gratuito com conteúdo exclusivo
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Acesso imediato ao material
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Atualizações sobre novos cursos
                    </li>
                </ul>
            </div>

            <!-- Formulário -->
            <form action="/leads/store" method="POST" class="space-y-6" id="leadForm">
                <!-- Nome -->
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-user text-indigo-600 mr-2"></i>Nome Completo
                    </label>
                    <input 
                        type="text" 
                        id="nome" 
                        name="nome" 
                        required
                        value="<?php echo htmlspecialchars($_SESSION['old_data']['nome'] ?? ''); ?>"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 outline-none dark:bg-gray-700 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-300"
                        placeholder="Seu nome completo"
                    >
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-envelope text-indigo-600 mr-2"></i>Email
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        required
                        value="<?php echo htmlspecialchars($_SESSION['old_data']['email'] ?? ''); ?>"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 outline-none dark:bg-gray-700 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-300"
                        placeholder="seu@email.com"
                    >
                </div>

                <!-- Telefone -->
                <div>
                    <label for="telefone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-phone text-indigo-600 mr-2"></i>Telefone
                    </label>
                    <input 
                        type="tel" 
                        id="telefone" 
                        name="telefone" 
                        required
                        value="<?php echo htmlspecialchars($_SESSION['old_data']['telefone'] ?? ''); ?>"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 outline-none dark:bg-gray-700 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-300"
                        placeholder="(00) 00000-0000"
                    >
                </div>

                <!-- Descrição -->
                <div>
                    <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-comment text-indigo-600 mr-2"></i>Como podemos ajudar você?
                    </label>
                    <textarea 
                        id="descricao" 
                        name="descricao" 
                        rows="4"
                        required
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 outline-none resize-none dark:bg-gray-700 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-300"
                        placeholder="Conte-nos um pouco sobre seu interesse no curso..."
                    ><?php echo htmlspecialchars($_SESSION['old_data']['descricao'] ?? ''); ?></textarea>
                </div>

                <!-- Checkbox de Aceite -->
                <div class="flex items-start">
                    <input 
                        type="checkbox" 
                        id="aceite" 
                        name="aceite" 
                        required
                        class="mt-1 w-4 h-4 text-indigo-600 border-gray-300 dark:border-gray-600 rounded focus:ring-indigo-500"
                    >
                    <label for="aceite" class="ml-2 text-sm text-gray-600 dark:text-gray-300">
                        Concordo em receber comunicações por email sobre o curso e aceito os termos de privacidade.
                    </label>
                </div>

                <!-- Botão de Submit -->
                <button 
                    type="submit"
                    class="w-full bg-indigo-600 text-white font-semibold py-4 px-6 rounded-lg hover:bg-indigo-700 transform hover:scale-105 transition duration-200 shadow-lg hover:shadow-xl flex items-center justify-center"
                >
                    <i class="fas fa-paper-plane mr-2"></i>
                    Receber Material Gratuito
                </button>
            </form>

            <!-- Footer do Card -->
            <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
                <i class="fas fa-lock mr-1"></i>
                Seus dados estão seguros conosco
            </div>
        </div>

        <!-- Rodapé -->
        <div class="text-center mt-8 text-gray-600 dark:text-gray-300">
            <p>&copy; 2025 Todos os direitos reservados</p>
        </div>
    </div>

    <!-- Script para máscara de telefone -->
    <script src="/assets/js/form.js"></script>
    
    <!-- Overlay de carregamento -->
    <div id="loadingOverlay" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 flex items-center space-x-4 shadow-lg">
            <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            <div>
                <p class="font-semibold text-gray-900 dark:text-gray-100">Aguarde, estamos processando seu cadastro...</p>
                <p class="text-sm text-gray-600 dark:text-gray-300">Você será redirecionado em breve.</p>
            </div>
        </div>
    </div>
    <!-- Script e botão de Dark Mode -->
    <button id="darkmode-toggle" class="fixed bottom-6 left-6 z-50 w-12 h-12 rounded-full bg-gray-900 text-yellow-300 dark:bg-yellow-400 dark:text-gray-900 shadow-lg hover:scale-105 transition flex items-center justify-center" aria-label="Alternar modo escuro">
        <i class="fas fa-moon" id="darkmode-icon"></i>
    </button>
    <script src="/assets/js/darkmode.js"></script>
</body>
</html>
<?php unset($_SESSION['old_data']); ?>
