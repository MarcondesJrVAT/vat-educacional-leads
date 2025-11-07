# 📧 Sistema de Captação de Leads

Sistema completo de captação de leads com formulário HTML, processamento PHP e envio automático de email com PDF.

## 🚀 Funcionalidades

- ✅ Formulário responsivo com Tailwind CSS
- ✅ Validação de dados no frontend e backend
- ✅ Máscara automática para telefone
- ✅ Salvamento dos leads em arquivo CSV
- ✅ Envio automático de email com PDF anexo
- ✅ Notificação para o administrador
- ✅ Página de confirmação elegante
- ✅ Suporte opcional para banco de dados MySQL

## 📋 Requisitos

- PHP 7.4 ou superior
- Servidor web (Apache, Nginx, ou PHP built-in server)
- Função `mail()` do PHP configurada OU servidor SMTP

## 🔧 Instalação

### 1. Copie os arquivos para seu servidor

```bash
# Estrutura de arquivos:
├── index.html          # Formulário de captação
├── process.php         # Processamento dos dados
├── success.html        # Página de sucesso
├── config.php          # Configurações
├── database.sql        # Script SQL (opcional)
├── sample-course.pdf   # PDF de amostra (crie seu PDF)
└── README.md          # Este arquivo
```

### 2. Configure o arquivo `config.php`

Abra o arquivo `config.php` e altere as seguintes configurações:

```php
// Email de envio
define('FROM_EMAIL', 'seu-email@gmail.com');
define('ADMIN_EMAIL', 'seu-email@gmail.com');
```

### 3. Adicione seu PDF

Crie ou adicione um arquivo PDF chamado `sample-course.pdf` na raiz do projeto. Este será o material enviado aos leads.

### 4. Configure o servidor de email

#### Opção A: Usando a função mail() do PHP (Linux)

```bash
# Instalar e configurar o Postfix ou Sendmail
sudo apt-get install postfix
```

#### Opção B: Usando SMTP (Recomendado para Gmail)

Se quiser usar Gmail ou outro provedor SMTP, você precisará usar uma biblioteca como PHPMailer. 

Para Gmail:
1. Ative a verificação em duas etapas
2. Gere uma senha de aplicativo
3. Use essa senha no `config.php`

### 5. Permissões de arquivo

```bash
# Garantir que o PHP possa criar o arquivo CSV
chmod 755 /caminho/para/projeto
```

## 🌐 Executar o Projeto

### Usando o servidor built-in do PHP:

```bash
cd /caminho/para/projeto
php -S localhost:8000
```

Acesse: `http://localhost:8000`

### Usando Apache/Nginx:

Configure o virtual host apontando para o diretório do projeto e acesse via navegador.

## 📊 Banco de Dados (Opcional)

Se quiser salvar os leads em banco de dados:

1. Crie o banco de dados executando o script `database.sql`
2. Configure as credenciais no `config.php`
3. Altere `SAVE_TO_DATABASE` para `true`

```sql
mysql -u root -p < database.sql
```

## 📁 Arquivo de Leads

Os leads são automaticamente salvos em `leads.csv` com as seguintes informações:
- Data e hora
- Nome
- Email
- Telefone
- Descrição

## 🎨 Personalização

### Cores e Estilo

O projeto usa Tailwind CSS. Você pode personalizar as cores editando os arquivos HTML:

- `indigo` - Cor principal
- `green` - Cor de sucesso
- Altere conforme necessário

### Conteúdo do Email

Edite a função `enviarEmailLead()` no arquivo `process.php` para personalizar o email.

## 🔒 Segurança

- Validação de dados no backend
- Sanitização de inputs
- Proteção contra XSS
- Validação de email
- Proteção de arquivos sensíveis

### Recomendações:

```php
// Adicione no topo dos arquivos PHP para produção:
ini_set('display_errors', 0);
error_reporting(0);
```

## 📧 Configuração de Email para Produção

### Para usar Gmail:

1. Acesse: https://myaccount.google.com/apppasswords
2. Gere uma senha de aplicativo
3. Use no `config.php`:

```php
define('SMTP_USERNAME', 'seu-email@gmail.com');
define('SMTP_PASSWORD', 'senha-de-aplicativo-aqui');
```

### Para outros provedores:

Configure os parâmetros SMTP no `config.php` conforme seu provedor.

## 🐛 Solução de Problemas

### Email não está sendo enviado:

1. Verifique se a função `mail()` está habilitada:
```php
<?php
if (function_exists('mail')) {
    echo "Mail function available";
} else {
    echo "Mail function not available";
}
?>
```

2. Verifique os logs de erro do PHP:
```bash
tail -f /var/log/apache2/error.log
```

3. Teste o envio de email:
```php
mail('seu-email@gmail.com', 'Teste', 'Mensagem de teste');
```

### PDF não está sendo anexado:

1. Verifique se o arquivo existe:
```php
if (file_exists('sample-course.pdf')) {
    echo "PDF encontrado";
}
```

2. Verifique as permissões do arquivo:
```bash
chmod 644 sample-course.pdf
```

### Formulário não redireciona:

1. Verifique se não há output antes do `header()` no PHP
2. Certifique-se de que o `process.php` está sendo executado

## 📱 Responsividade

O formulário é totalmente responsivo e funciona em:
- 📱 Smartphones
- 📱 Tablets
- 💻 Desktops

## 🎯 Melhorias Futuras

- [ ] Integração com CRM
- [ ] Dashboard de analytics
- [ ] A/B testing
- [ ] Automação de email marketing
- [ ] Integração com WhatsApp
- [ ] Google reCAPTCHA
- [ ] Double opt-in

## 📄 Licença

Este projeto é de código aberto. Use livremente!

## 🤝 Suporte

Para dúvidas ou problemas, entre em contato.

---

**Desenvolvido com ❤️ para captação eficiente de leads!**
