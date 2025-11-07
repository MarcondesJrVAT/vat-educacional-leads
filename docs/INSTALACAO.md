# 🚀 GUIA RÁPIDO DE INSTALAÇÃO

## Passo 1: Configure o Email

Abra o arquivo `config.php` e altere:

```php
define('FROM_EMAIL', 'seu-email@gmail.com');
define('ADMIN_EMAIL', 'seu-email@gmail.com');
```

## Passo 2: Crie o PDF

1. Abra `sample-course-template.html` no navegador
2. Pressione Ctrl+P (Imprimir)
3. Selecione "Salvar como PDF"
4. Salve como `sample-course.pdf` na raiz do projeto

OU adicione seu próprio PDF e renomeie para `sample-course.pdf`

## Passo 3: Execute o Servidor

```bash
php -S localhost:8000
```

## Passo 4: Teste

Acesse: http://localhost:8000

Preencha o formulário e teste!

## 📧 Configurar Email (Gmail)

Para enviar emails via Gmail:

1. Acesse: https://myaccount.google.com/apppasswords
2. Crie uma senha de aplicativo
3. Use essa senha no `config.php`

## ⚠️ Solução de Problemas

### Email não envia?

Verifique se a função mail() do PHP está habilitada:

```bash
php -r "if(function_exists('mail')) echo 'OK'; else echo 'Não disponível';"
```

### Instalar servidor de email (Linux):

```bash
sudo apt-get install sendmail
```

## 🎯 Arquivos Importantes

- `index.html` - Formulário
- `process.php` - Processamento
- `config.php` - Configurações
- `success.html` - Página de sucesso
- `sample-course.pdf` - PDF para enviar
- `leads.csv` - Leads salvos

## 🔒 Segurança

Proteja seus arquivos em produção:
- Remova `display_errors` do PHP
- Use HTTPS
- Configure o `.htaccess`
- Proteja o arquivo `leads.csv`

---

**Pronto! Seu sistema está configurado.**
