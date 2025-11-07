/**
 * JavaScript para o formulário de leads
 */

// Máscara para telefone
document.addEventListener('DOMContentLoaded', function() {
    const telefoneInput = document.getElementById('telefone');
    
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
                value = value.replace(/(\d)(\d{4})$/, '$1-$2');
            }
            e.target.value = value;
        });
    }

    // Validação do formulário
    const leadForm = document.getElementById('leadForm');
    
    if (leadForm) {
        leadForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const telefone = document.getElementById('telefone').value.replace(/\D/g, '');
            if (telefone.length < 10) {
                alert('Por favor, insira um telefone válido com DDD.');
                return false;
            }

            // Mostrar overlay de carregamento e evitar múltiplos envios
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.classList.remove('hidden');
            }

            // Desabilitar todos os botões submit dentro do formulário
            const submits = leadForm.querySelectorAll('[type="submit"]');
            submits.forEach(function(btn) {
                btn.disabled = true;
                btn.classList.add('opacity-60', 'cursor-not-allowed');
            });

            // Timeout de segurança: caso o envio não conclua em 30s, remover overlay e reabilitar
            const timeoutMs = 30000;
            const timeoutHandle = setTimeout(function() {
                if (overlay) overlay.classList.add('hidden');
                submits.forEach(function(btn) {
                    btn.disabled = false;
                    btn.classList.remove('opacity-60', 'cursor-not-allowed');
                });
                alert('O envio está demorando. Verifique sua conexão e tente novamente.');
            }, timeoutMs);

            // Enviar usando fetch (AJAX) para evitar reload completo da página e assets externos
            const formData = new FormData(leadForm);

            fetch(leadForm.action, {
                method: 'POST',
                // Enviar cookies de sessão e aceitar Set-Cookie (mesma origem)
                credentials: 'same-origin',
                redirect: 'follow',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            }).then(function(response) {
                clearTimeout(timeoutHandle);
                if (overlay) overlay.classList.add('hidden');
                if (response.ok) {
                    var contentType = response.headers.get('Content-Type') || '';
                    if (contentType.indexOf('application/json') !== -1) {
                        // Ler JSON com redirect
                        response.json().then(function(json) {
                            if (json && json.redirect) {
                                window.location.href = json.redirect;
                            } else {
                                window.location.href = '/leads/success';
                            }
                        }).catch(function() {
                            window.location.href = '/leads/success';
                        });
                    } else {
                        // fallback: usar response.url se disponível
                        try {
                            var destination = response.url || ('/leads/success');
                            window.location.href = destination;
                        } catch (e) {
                            window.location.href = '/leads/success';
                        }
                    }
                } else {
                    // Em caso de erro, reabilitar botões e mostrar mensagem
                    submits.forEach(function(btn) {
                        btn.disabled = false;
                        btn.classList.remove('opacity-60', 'cursor-not-allowed');
                    });
                    alert('Ocorreu um erro ao processar seu pedido. Tente novamente.');
                }
            }).catch(function(err) {
                clearTimeout(timeoutHandle);
                if (overlay) overlay.classList.add('hidden');
                submits.forEach(function(btn) {
                    btn.disabled = false;
                    btn.classList.remove('opacity-60', 'cursor-not-allowed');
                });
                alert('Falha na conexão: ' + err.message);
            });
        });
    }
});
