<?php
// SUGESTÃO: namespace App\Services;

/**
 * Class EmailService
 * Responsável por envio de emails e notificações.
 *
 * @package App\Services
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
        
        // Criar o conteúdo do email
        $subject = 'Obrigado por se cadastrar - Seu material gratuito';
        
        $htmlMessage = self::getHtmlEmailTemplate($primeiroNome);
        $textMessage = self::getTextEmailTemplate($primeiroNome);
        
        // Verificar se o arquivo PDF existe
        if (!file_exists(PDF_PATH)) {
            error_log("Arquivo PDF não encontrado: " . PDF_PATH);
            return self::sendSimpleEmail($email, $subject, $htmlMessage, $textMessage);
        }
        
        // Preparar anexo
        $pdfContent = file_get_contents(PDF_PATH);
        $pdfEncoded = chunk_split(base64_encode($pdfContent));
        
        // Boundary para separar partes do email
        $boundary = md5(time());
        
        // Headers
        $headers = "From: " . FROM_NAME . " <" . FROM_EMAIL . ">\r\n";
        $headers .= "Reply-To: " . FROM_EMAIL . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";
        
        // Corpo do email
        $body = "--{$boundary}\r\n";
        $body .= "Content-Type: multipart/alternative; boundary=\"alt-{$boundary}\"\r\n\r\n";
        
        // Versão texto
        $body .= "--alt-{$boundary}\r\n";
        $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $body .= $textMessage . "\r\n\r\n";
        
        // Versão HTML
        $body .= "--alt-{$boundary}\r\n";
        $body .= "Content-Type: text/html; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $body .= $htmlMessage . "\r\n\r\n";
        
        $body .= "--alt-{$boundary}--\r\n\r\n";
        
        // Anexo PDF
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Type: application/pdf; name=\"" . PDF_NAME . "\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n";
        $body .= "Content-Disposition: attachment; filename=\"" . PDF_NAME . "\"\r\n\r\n";
        $body .= $pdfEncoded . "\r\n";
        
        $body .= "--{$boundary}--";
        
        // Enviar email
        return mail($email, $subject, $body, $headers);
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
        $boundary = md5(time());
        
        $headers = "From: " . FROM_NAME . " <" . FROM_EMAIL . ">\r\n";
        $headers .= "Reply-To: " . FROM_EMAIL . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n";
        
        $body = "--{$boundary}\r\n";
        $body .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
        $body .= $textMessage . "\r\n\r\n";
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
        $body .= $htmlMessage . "\r\n\r\n";
        $body .= "--{$boundary}--";
        
        return mail($email, $subject, $body, $headers);
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
        
        $headers = "From: " . FROM_EMAIL . "\r\n";
        $headers .= "Reply-To: " . $lead->getEmail() . "\r\n";
        
        return mail(ADMIN_EMAIL, $subject, $message, $headers);
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
