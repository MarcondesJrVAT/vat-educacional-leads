<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Realizado com Sucesso!</title>
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
<body class="bg-gradient-to-br from-green-50 to-emerald-100 dark:bg-gradient-to-br dark:from-gray-900 dark:to-gray-800 min-h-screen flex items-center justify-center p-4 text-gray-900 dark:text-gray-100">
    <div class="max-w-2xl w-full">
        <!-- Card de Sucesso -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 md:p-12 text-center">
            <!-- Ícone de Sucesso Animado -->
            <div class="mb-6">
                <div class="inline-block p-6 bg-green-100 dark:bg-green-900/30 rounded-full animate-bounce">
                    <i class="fas fa-check-circle text-green-500 text-6xl"></i>
                </div>
            </div>

            <!-- Título -->
            <h1 class="text-4xl font-bold text-gray-800 dark:text-gray-100 mb-4">
                🎉 Cadastro Realizado com Sucesso!
            </h1>

            <!-- Mensagem Principal -->
            <p class="text-xl text-gray-600 dark:text-gray-300 mb-6">
                Obrigado por se cadastrar, <strong><?php echo htmlspecialchars($nome ?? 'amigo(a)'); ?></strong>!
            </p>

            <!-- Card de Informação -->
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-lg p-6 mb-8 text-left">
                <h2 class="font-semibold text-indigo-900 dark:text-indigo-200 mb-4 flex items-center justify-center text-lg">
                    <i class="fas fa-envelope-open-text mr-2 text-2xl"></i>
                    Verifique seu email!
                </h2>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-paper-plane text-indigo-600 mt-1"></i>
                        </div>
                        <p id="email-status" class="ml-3 text-gray-700 dark:text-gray-300">
                            <?php if ($emailEnviado): ?>
                                Enviamos um email para <strong class="text-indigo-600"><?php echo htmlspecialchars($email ?? 'o endereço cadastrado'); ?></strong> com o material gratuito do curso em PDF.
                            <?php else: ?>
                                Estamos enviando o material para o seu e-mail. Isso pode levar alguns segundos. Se não receber em até alguns minutos, por favor entre em contato conosco em <strong><?php echo ADMIN_EMAIL; ?></strong>
                            <?php endif; ?>
                        </p>
                    </div>

                    <?php if ($emailEnviado): ?>
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-file-pdf text-red-500 mt-1"></i>
                        </div>
                        <p class="ml-3 text-gray-700 dark:text-gray-300">
                            O PDF contém uma <strong>amostra exclusiva</strong> do conteúdo que você vai aprender no curso completo.
                        </p>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-yellow-600 mt-1"></i>
                        </div>
                        <p class="ml-3 text-gray-700 dark:text-gray-300">
                            O email pode levar alguns minutos para chegar. Não esqueça de verificar a caixa de <strong>spam</strong> também!
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Próximos Passos -->
            <div class="bg-gray-50 dark:bg-gray-900/30 rounded-lg p-6 mb-8 text-left">
                <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-3 flex items-center">
                    <i class="fas fa-list-check text-indigo-600 mr-2"></i>
                    Próximos Passos:
                </h3>
                <ol class="space-y-3 text-gray-700 dark:text-gray-300">
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-600 text-white rounded-full flex items-center justify-center text-sm mr-3">1</span>
                        <span>Verifique seu email e abra o material em PDF</span>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-600 text-white rounded-full flex items-center justify-center text-sm mr-3">2</span>
                        <span>Leia a amostra do curso com atenção</span>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-600 text-white rounded-full flex items-center justify-center text-sm mr-3">3</span>
                        <span>Em breve entraremos em contato com mais informações</span>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-600 text-white rounded-full flex items-center justify-center text-sm mr-3">4</span>
                        <span>Fique atento às nossas novidades!</span>
                    </li>
                </ol>
            </div>

            <!-- Botões de Ação -->
            <div class="space-y-4">
                <a href="/" class="block w-full bg-indigo-600 text-white font-semibold py-4 px-6 rounded-lg hover:bg-indigo-700 transform hover:scale-105 transition duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-home mr-2"></i>
                    Voltar para a Página Inicial
                </a>
                
                <button onclick="window.print()" class="block w-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-100 font-semibold py-4 px-6 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition duration-200">
                    <i class="fas fa-print mr-2"></i>
                    Imprimir Comprovante
                </button>
            </div>

            <!-- Redes Sociais -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <p class="text-gray-600 dark:text-gray-300 mb-4">Siga-nos nas redes sociais:</p>
                <div class="flex justify-center space-x-4">
                    <a href="#" class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition duration-200">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-12 h-12 bg-blue-400 text-white rounded-full flex items-center justify-center hover:bg-blue-500 transition duration-200">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-12 h-12 bg-pink-600 text-white rounded-full flex items-center justify-center hover:bg-pink-700 transition duration-200">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-12 h-12 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition duration-200">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Rodapé -->
        <div class="text-center mt-8 text-gray-600 dark:text-gray-300">
            <p><i class="fas fa-heart text-red-500"></i> Obrigado por confiar em nós!</p>
            <p class="mt-2">&copy; 2025 Todos os direitos reservados</p>
        </div>
    </div>
    <!-- Botão Dark Mode -->
    <button id="darkmode-toggle" class="fixed bottom-6 left-6 z-50 w-12 h-12 rounded-full bg-gray-900 text-yellow-300 dark:bg-yellow-400 dark:text-gray-900 shadow-lg hover:scale-105 transition flex items-center justify-center" aria-label="Alternar modo escuro">
        <i class="fas fa-moon" id="darkmode-icon"></i>
    </button>
    <script src="/assets/js/darkmode.js"></script>
    <script>
            (function() {
            // Variáveis passadas do PHP
            var leadId = <?php echo json_encode($leadIdParam ?? null); ?>;
            var email = <?php echo json_encode($email ?? null); ?>;
            var emailEnviado = <?php echo ($emailEnviado ? 'true' : 'false'); ?>;

            // Se ainda não enviado, iniciar polling para verificar status (até 10s)
                if (!emailEnviado && (leadId || email)) {
                var attempts = 0;
                var maxAttempts = 20; // aumentar timeout para até 20s
                var interval = 1000; // ms
                var timer = setInterval(function() {
                    attempts++;
                    var url = '/leads/status';
                    if (leadId) url += '?lead_id=' + encodeURIComponent(leadId);
                    else url += '?email=' + encodeURIComponent(email);
                    fetch(url, { credentials: 'same-origin' })
                        .then(function(resp) { return resp.json(); })
                        .then(function(json) {
                            if (json && json.sent) {
                                clearInterval(timer);
                                // Recarregar a página para renderizar a versão com sucesso
                                window.location.reload();
                            } else if (attempts >= maxAttempts) {
                                clearInterval(timer);
                                // atualizar mensagem para indicar erro e sugerir contato
                                var statusEl = document.getElementById('email-status');
                                if (statusEl) {
                                    statusEl.innerHTML = 'Houve um problema ao enviar o email. Por favor, entre em contato conosco em <strong><?php echo ADMIN_EMAIL; ?></strong>';
                                }
                            }
                        }).catch(function(err) {
                            clearInterval(timer);
                        });
                }, interval);
            }
            // Se já está confirmado como enviado, limpar query string para deixar a URL limpa
            if (emailEnviado) {
                try {
                    history.replaceState(null, '', window.location.pathname);
                } catch (e) {}
            }
        })();
    </script>
</body>
</html>
