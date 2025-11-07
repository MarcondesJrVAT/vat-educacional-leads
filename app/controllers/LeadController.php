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
    $logPrefix = '[LeadController::store] ';
    $t0 = microtime(true);
    // log start
    debug_log("{$logPrefix}start " . date('Y-m-d H:i:s'));

        $lead = new Lead($_POST);
        
        // Sanitizar dados
        $lead->sanitize();
        
        // Validar dados
        $errors = $lead->validate();
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_data'] = $_POST;
            $t1 = microtime(true);
            debug_log("{$logPrefix}validation_failed time=" . round(($t1-$t0), 4));
            $this->redirect('/');
            return;
        }

        $t_validation = microtime(true);
    debug_log("{$logPrefix}validation_ok time=" . round(($t_validation-$t0), 4));
        
        // Salvar no banco (se habilitado). Se estiver salvando no banco, não escrevemos no CSV.
        $spawnedJob = false;
        if (defined('SAVE_TO_DATABASE') && SAVE_TO_DATABASE) {
            $insertId = $lead->saveToDatabase();
            $t_after_db = microtime(true);
            debug_log("{$logPrefix}after_save time=" . round(($t_after_db-$t0), 4) . " insertId=" . var_export($insertId, true));
            if ($insertId) {
                // Antes de fechar a sessão, gravar os dados essenciais para a tela de sucesso
                if (session_status() !== PHP_SESSION_ACTIVE) {
                    @session_start();
                }
                $_SESSION['lead_email'] = $lead->getEmail();
                $_SESSION['lead_nome'] = $lead->getNome();
                // inicialmente marcamos como false; se o job for spawnado com sucesso, atualizamos para true
                $_SESSION['email_enviado'] = false;

                // Disparar envio de email em background para reduzir latência da requisição
                // Liberar lock de sessão imediatamente para não bloquear a requisição seguinte
                if (session_status() === PHP_SESSION_ACTIVE) {
                    session_write_close();
                    debug_log("{$logPrefix}session_closed time=" . round((microtime(true)-$t0), 4));
                }

                $php = PHP_BINARY;
                // Caminho absoluto seguro para o script de job
                $script = BASE_PATH . '/scripts/send_email_job.php';
                $cmd = escapeshellcmd($php) . ' ' . escapeshellarg($script) . ' ' . escapeshellarg($insertId);

                // Disparar de forma não bloqueante usando nohup + popen/pclose (mais robusto que exec + &)
                $fullCmd = 'nohup ' . $cmd . ' >/dev/null 2>&1 &';
                @pclose(@popen($fullCmd, 'r'));
                $spawnedJob = true;
                debug_log("{$logPrefix}job_spawned cmd=" . substr($fullCmd,0,200) . " time=" . round((microtime(true)-$t0), 4));

                // Reabrir sessão rapidamente para atualizar flag de email enviado
                if ($spawnedJob) {
                    if (session_status() !== PHP_SESSION_ACTIVE) {
                        @session_start();
                    }
                    $_SESSION['email_enviado'] = true;
                    session_write_close();
                }
            } else {
                // fallback: tentar salvar em CSV caso o banco falhe
                $lead->saveToCSV();
                // enviar sincronamente (tentativa)
                EmailService::sendLeadEmail($lead);
                EmailService::sendAdminNotification($lead);
            }
        } else {
            // Salvar em CSV quando o salvamento em banco estiver desabilitado
            $lead->saveToCSV();
        }

        $t_end_prepare = microtime(true);
    debug_log("{$logPrefix}ready_to_redirect time=" . round(($t_end_prepare-$t0), 4));
        
        // Se não houve job em background, enviar sincronamente (caso de CSV fallback ou SAVE_TO_DATABASE desabilitado)
        $emailEnviado = false;
        if (!$spawnedJob) {
            $emailEnviado = EmailService::sendLeadEmail($lead);
            // Enviar notificação para o admin
            EmailService::sendAdminNotification($lead);
        } else {
            // Job em background fará os envios; marcamos como enviado para UX (será atualizado pelo job/log se precisar)
            $emailEnviado = true;
        }
        
        // Armazenar dados na sessão para exibir na página de sucesso
        if (session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }
        $_SESSION['lead_email'] = $lead->getEmail();
        $_SESSION['lead_nome'] = $lead->getNome();
        $_SESSION['email_enviado'] = $emailEnviado;
        // Fechar sessão para liberar lock
        session_write_close();
        
        // Redirecionar para página de sucesso
        // Incluir lead_id ou email na query para evitar dependência exclusiva de sessão
        $redirectUrl = '/leads/success';
        if (!empty($insertId)) {
            $redirectUrl .= '?lead_id=' . urlencode($insertId);
        } else {
            $redirectUrl .= '?email=' . urlencode($lead->getEmail());
        }

        // Se for uma requisição AJAX (fetch/XHR), retornar JSON com a URL de redirect
        $isAjax = false;
        $xrw = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        if (strtolower($xrw) === 'xmlhttprequest' || stripos($accept, 'application/json') !== false) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['redirect' => $redirectUrl]);
            return;
        }

        $this->redirect($redirectUrl);
    }
    
    /**
     * Exibe a página de sucesso após cadastro.
     * @return void
     */
    public function success() {
        $email = $_SESSION['lead_email'] ?? null;
        $nome = $_SESSION['lead_nome'] ?? null;
        $emailEnviado = $_SESSION['email_enviado'] ?? false;

        // Se houver parâmetros na query, usá-los como fallback (lead_id ou email)
        $leadIdParam = $_GET['lead_id'] ?? null;
        $emailParam = $_GET['email'] ?? null;
        if (!$email && $emailParam) {
            $email = $emailParam;
        }
        // Tentar obter nome via DB se faltando e houver lead_id
        if (!$nome && $leadIdParam) {
            $leadObj = Lead::findById($leadIdParam);
            if ($leadObj) {
                $nome = $leadObj->getNome();
            }
        }
        
        // Se a sessão indicar que o email não foi enviado, tentamos um fallback consultando o banco
        if (!$emailEnviado && defined('SAVE_TO_DATABASE') && SAVE_TO_DATABASE && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
                $pdo = new PDO(
                    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                    DB_USER,
                    DB_PASS
                );
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Procurar por um registro de email_logs recente para esse destinatário
                if ($leadIdParam) {
                    $sql = "SELECT status, data_envio FROM email_logs WHERE lead_id = :lead_id ORDER BY data_envio DESC LIMIT 1";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':lead_id' => $leadIdParam]);
                } else {
                    $sql = "SELECT status, data_envio FROM email_logs WHERE email_destinatario = :email ORDER BY data_envio DESC LIMIT 1";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':email' => $email]);
                }
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row && $row['status'] === 'enviado') {
                    $emailEnviado = true;
                }
            } catch (PDOException $e) {
                file_put_contents(LOG_PATH, "[LeadController::success] email_logs query failed: " . $e->getMessage() . "\n", FILE_APPEND);
            }
        }

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

    /**
     * Endpoint AJAX para verificar status de envio do email (JSON).
     * Aceita GET com lead_id ou email.
     */
    public function status() {
        header('Content-Type: application/json; charset=utf-8');
        $leadId = $_GET['lead_id'] ?? null;
        $email = $_GET['email'] ?? null;

        $result = ['sent' => false, 'checked' => false, 'status' => null, 'data_envio' => null];

        if (!(defined('SAVE_TO_DATABASE') && SAVE_TO_DATABASE)) {
            echo json_encode($result);
            return;
        }

        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                DB_USER,
                DB_PASS
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if ($leadId) {
                $sql = "SELECT status, data_envio FROM email_logs WHERE lead_id = :lead_id ORDER BY data_envio DESC LIMIT 1";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':lead_id' => $leadId]);
            } elseif ($email) {
                $sql = "SELECT status, data_envio FROM email_logs WHERE email_destinatario = :email ORDER BY data_envio DESC LIMIT 1";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':email' => $email]);
            } else {
                echo json_encode($result);
                return;
            }

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $result['checked'] = true;
                $result['status'] = $row['status'];
                $result['data_envio'] = $row['data_envio'];
                $result['sent'] = ($row['status'] === 'enviado');
            }
        } catch (PDOException $e) {
            // registrar e retornar false
            file_put_contents(LOG_PATH, "[LeadController::status] error: " . $e->getMessage() . "\n", FILE_APPEND);
        }

        echo json_encode($result);
        return;
    }
}
?>
