<?php
// SUGESTÃO: namespace App\Controllers;

/**
 * Class LeadController
 * Responsável por gerenciar requisições relacionadas aos leads.
 *
 * @package App\Controllers
 */
class LeadController {
    
    /**
     * Exibe o formulário de cadastro de lead.
     * @return void
     */
    public function create() {
        require_once APP_PATH . '/views/leads/create.php';
    }
    
    /**
     * Processa e salva o lead submetido pelo formulário.
     * @return void
     */
    public function store() {
        // Verificar se é POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
            return;
        }
        
        // Criar instância do Lead
        $lead = new Lead($_POST);
        
        // Sanitizar dados
        $lead->sanitize();
        
        // Validar dados
        $errors = $lead->validate();
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_data'] = $_POST;
            $this->redirect('/');
            return;
        }
        
        // Salvar em CSV
        $lead->saveToCSV();
        
        // Salvar no banco (se habilitado)
        if (SAVE_TO_DATABASE) {
            $lead->saveToDatabase();
        }
        
        // Enviar email para o lead
        $emailEnviado = EmailService::sendLeadEmail($lead);
        
        // Enviar notificação para o admin
        EmailService::sendAdminNotification($lead);
        
        // Armazenar dados na sessão para exibir na página de sucesso
        $_SESSION['lead_email'] = $lead->getEmail();
        $_SESSION['lead_nome'] = $lead->getNome();
        $_SESSION['email_enviado'] = $emailEnviado;
        
        // Redirecionar para página de sucesso
        $this->redirect('/leads/success');
    }
    
    /**
     * Exibe a página de sucesso após cadastro.
     * @return void
     */
    public function success() {
        $email = $_SESSION['lead_email'] ?? 'o endereço cadastrado';
        $nome = $_SESSION['lead_nome'] ?? 'amigo(a)';
        $emailEnviado = $_SESSION['email_enviado'] ?? false;
        
        // Limpar sessão
        unset($_SESSION['lead_email']);
        unset($_SESSION['lead_nome']);
        unset($_SESSION['email_enviado']);
        
        require_once APP_PATH . '/views/leads/success.php';
    }
    
    /**
     * Redireciona para uma rota específica.
     * @param string $path
     * @return void
     */
    private function redirect($path) {
        header('Location: ' . $path);
        exit;
    }
}
?>
