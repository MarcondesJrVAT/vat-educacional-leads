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
            const telefone = document.getElementById('telefone').value.replace(/\D/g, '');
            if (telefone.length < 10) {
                e.preventDefault();
                alert('Por favor, insira um telefone válido com DDD.');
                return false;
            }
        });
    }
});
