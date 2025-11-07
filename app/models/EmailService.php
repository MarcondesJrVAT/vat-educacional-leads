<?php
// SUGESTÃO: namespace App.Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Class EmailService
 * Responsável por envio de emails e notificações.
 *
 * @package App.Services
 */
class EmailService {
    
    /**
     * Envia email para o lead com PDF em anexo.
     * @param Lead $lead
     * @return bool
     */
    public static function sendLeadEmail($lead) {
        $nome = $lead->getNome();
        $email = $lead->getEmail();
        $primeiroNome = explode(' ', $nome)[0];

        $subject = 'Obrigado por se cadastrar - Seu material gratuito';
        $htmlMessage = self::getHtmlEmailTemplate($primeiroNome);
        $textMessage = self::getTextEmailTemplate($primeiroNome);

        $mail = new PHPMailer(true);
        try {
            // Configurar SMTP
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->Port = SMTP_PORT;

            // Remetente e destinatário
            $mail->setFrom(FROM_EMAIL, FROM_NAME);
            $mail->addAddress($email, $nome);
            $mail->addReplyTo(FROM_EMAIL, FROM_NAME);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $htmlMessage;
            $mail->AltBody = $textMessage;

            if (file_exists(PDF_PATH)) {
                $mail->addAttachment(PDF_PATH, PDF_NAME);
            }

            $mail->send();

            // Log no banco (se possível)
            try {
                $leadId = method_exists($lead, 'getId') ? $lead->getId() : null;
                self::logEmailToDb($leadId, $email, $subject, 'enviado', null);
            } catch (Exception $e) {
                error_log('EmailService logEmailToDb error: ' . $e->getMessage());
            }

            return true;
        } catch (Exception $e) {
            error_log('PHPMailer error (sendLeadEmail): ' . $e->getMessage());
            try {
                $leadId = method_exists($lead, 'getId') ? $lead->getId() : null;
                self::logEmailToDb($leadId, $email, $subject, 'falhou', $e->getMessage());
            } catch (Exception $ex) {
                error_log('EmailService logEmailToDb error: ' . $ex->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Envia email simples (sem anexo).
     * @param string $email
     * @param string $subject
     * @param string $htmlMessage
     * @param string $textMessage
     * @return bool
     */
    private static function sendSimpleEmail($email, $subject, $htmlMessage, $textMessage) {
        // Fallback usando PHPMailer em modo SMTP
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->Port = SMTP_PORT;

            $mail->setFrom(FROM_EMAIL, FROM_NAME);
            $mail->addAddress($email);
            $mail->addReplyTo(FROM_EMAIL, FROM_NAME);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $htmlMessage;
            $mail->AltBody = $textMessage;

            $mail->send();

            try {
                self::logEmailToDb(null, $email, $subject, 'enviado', null);
            } catch (Exception $e) {
                error_log('EmailService logEmailToDb error: ' . $e->getMessage());
            }

            return true;
        } catch (Exception $e) {
            error_log('PHPMailer error (sendSimpleEmail): ' . $e->getMessage());
            try {
                self::logEmailToDb(null, $email, $subject, 'falhou', $e->getMessage());
            } catch (Exception $ex) {
                error_log('EmailService logEmailToDb error: ' . $ex->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Envia notificação para o administrador.
     * @param Lead $lead
     * @return bool
     */
    public static function sendAdminNotification($lead) {
        $subject = 'Novo Lead Cadastrado - ' . SITE_NAME;

        $message = "Um novo lead foi cadastrado!\n\n";
        $message .= "Nome: " . $lead->getNome() . "\n";
        $message .= "Email: " . $lead->getEmail() . "\n";
        $message .= "Telefone: " . $lead->getTelefone() . "\n";
        $message .= "Descrição: " . $lead->getDescricao() . "\n\n";
        $message .= "Data: " . date('d/m/Y H:i:s') . "\n";

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->Port = SMTP_PORT;

            $mail->setFrom(FROM_EMAIL, FROM_NAME);
            $mail->addAddress(ADMIN_EMAIL);
            $mail->addReplyTo($lead->getEmail(), $lead->getNome());

            $mail->isHTML(false);
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();
                try {
                    $leadId = method_exists($lead, 'getId') ? $lead->getId() : null;
                    self::logEmailToDb($leadId, ADMIN_EMAIL, $subject, 'enviado', null);
                } catch (Exception $e) {
                    error_log('EmailService logEmailToDb error: ' . $e->getMessage());
                }
                return true;
        } catch (Exception $e) {
            error_log('PHPMailer error (sendAdminNotification): ' . $e->getMessage());
                try {
                    $leadId = method_exists($lead, 'getId') ? $lead->getId() : null;
                    self::logEmailToDb($leadId, ADMIN_EMAIL, $subject, 'falhou', $e->getMessage());
                } catch (Exception $ex) {
                    error_log('EmailService logEmailToDb error: ' . $ex->getMessage());
                }
            return false;
        }
    }

        /**
         * Registra um log de email na tabela `email_logs` quando possível.
         * @param int|null $leadId
         * @param string $email_destinatario
         * @param string $assunto
         * @param string $status 'enviado'|'falhou'
         * @param string|null $erro
         */
        private static function logEmailToDb($leadId, $email_destinatario, $assunto, $status, $erro = null) {
            if (!defined('SAVE_TO_DATABASE') || !SAVE_TO_DATABASE) return;
            try {
                $pdo = new PDO(
                    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                    DB_USER,
                    DB_PASS
                );
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = "INSERT INTO email_logs (lead_id, email_destinatario, assunto, status, data_envio, erro) VALUES (:lead_id, :email_destinatario, :assunto, :status, :data_envio, :erro)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':lead_id' => $leadId,
                    ':email_destinatario' => $email_destinatario,
                    ':assunto' => substr($assunto, 0, 255),
                    ':status' => $status,
                    ':data_envio' => date('Y-m-d H:i:s'),
                    ':erro' => $erro
                ]);
                // Se o email foi enviado com sucesso, marcar lead.email_sent = 1 quando leadId estiver presente
                if ($status === 'enviado' && $leadId) {
                    try {
                        $sql2 = "UPDATE leads SET email_sent = 1 WHERE id = :lead_id";
                        $stmt2 = $pdo->prepare($sql2);
                        $stmt2->execute([':lead_id' => $leadId]);
                    } catch (PDOException $ex) {
                        error_log('logEmailToDb update lead email_sent error: ' . $ex->getMessage());
                    }
                }
            } catch (PDOException $e) {
                error_log('logEmailToDb PDO error: ' . $e->getMessage());
            }
        }
    
    /**
     * Retorna template HTML do email.
     * @param string $primeiroNome
     * @return string
     */
    private static function getHtmlEmailTemplate($primeiroNome) {
        return '
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                .button { display: inline-block; padding: 15px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>🎉 Bem-vindo(a), ' . htmlspecialchars($primeiroNome) . '!</h1>
                </div>
                <div class="content">
                    <h2>Obrigado por se cadastrar!</h2>
                    <p>Ficamos muito felizes com seu interesse no nosso curso.</p>
                    
                    <p><strong>Conforme prometido, segue em anexo o material gratuito com uma amostra do nosso curso.</strong></p>
                    
                    <p>📚 No PDF você encontrará:</p>
                    <ul>
                        <li>Introdução ao conteúdo do curso</li>
                        <li>Tópicos que serão abordados</li>
                        <li>Metodologia de ensino</li>
                        <li>Depoimentos de alunos</li>
                    </ul>
                    
                    <p>Em breve entraremos em contato com mais informações sobre o curso completo.</p>
                    
                    <p>Qualquer dúvida, é só responder este email!</p>
                    
                    <p>Abraços,<br><strong>Equipe ' . SITE_NAME . '</strong></p>
                </div>
                <div class="footer">
                    <p>&copy; 2025 ' . SITE_NAME . '. Todos os direitos reservados.</p>
                    <p>Você está recebendo este email porque se cadastrou em nosso site.</p>
                </div>
            </div>
        </body>
        </html>
        ';
    }
    
    /**
     * Retorna template texto do email.
     * @param string $primeiroNome
     * @return string
     */
    private static function getTextEmailTemplate($primeiroNome) {
        $message = "Olá, $primeiroNome!\n\n";
        $message .= "Obrigado por se cadastrar!\n\n";
        $message .= "Conforme prometido, segue em anexo o material gratuito com uma amostra do nosso curso.\n\n";
        $message .= "Em breve entraremos em contato com mais informações.\n\n";
        $message .= "Abraços,\nEquipe " . SITE_NAME;
        
        return $message;
    }
}
?>
