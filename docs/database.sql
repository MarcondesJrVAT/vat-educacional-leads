-- Banco de Dados para Sistema de Captação de Leads
-- Execute este script se quiser salvar os leads em banco de dados

CREATE DATABASE IF NOT EXISTS leads_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE leads_db;

-- Tabela de Leads
CREATE TABLE IF NOT EXISTS leads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    descricao TEXT NOT NULL,
    data_cadastro DATETIME NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    status ENUM('novo', 'contatado', 'convertido', 'descartado') DEFAULT 'novo',
    INDEX idx_email (email),
    INDEX idx_data_cadastro (data_cadastro),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Log de Emails
CREATE TABLE IF NOT EXISTS email_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lead_id INT,
    email_destinatario VARCHAR(255) NOT NULL,
    assunto VARCHAR(255) NOT NULL,
    status ENUM('enviado', 'falhou') NOT NULL,
    data_envio DATETIME NOT NULL,
    erro TEXT,
    FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE,
    INDEX idx_lead_id (lead_id),
    INDEX idx_data_envio (data_envio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- View para estatísticas
CREATE OR REPLACE VIEW estatisticas_leads AS
SELECT 
    COUNT(*) as total_leads,
    COUNT(CASE WHEN DATE(data_cadastro) = CURDATE() THEN 1 END) as leads_hoje,
    COUNT(CASE WHEN YEARWEEK(data_cadastro) = YEARWEEK(NOW()) THEN 1 END) as leads_semana,
    COUNT(CASE WHEN MONTH(data_cadastro) = MONTH(NOW()) AND YEAR(data_cadastro) = YEAR(NOW()) THEN 1 END) as leads_mes,
    COUNT(CASE WHEN status = 'novo' THEN 1 END) as leads_novos,
    COUNT(CASE WHEN status = 'contatado' THEN 1 END) as leads_contatados,
    COUNT(CASE WHEN status = 'convertido' THEN 1 END) as leads_convertidos
FROM leads;
