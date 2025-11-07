# 🚀 Guia Rápido - Sistema MVC de Captação de Leads

## ⚡ Início Rápido

### 1. Configure o Email (OBRIGATÓRIO)
```bash
nano config/config.php
```
Altere:
- `FROM_EMAIL` 
- `ADMIN_EMAIL`

### 2. Adicione o PDF
Coloque seu PDF em:
```
public/assets/pdf/sample-course.pdf
```

### 3. Inicie o Servidor
```bash
cd public
php -S localhost:8000
```

### 4. Acesse
- Formulário: http://localhost:8000
- Testes: http://localhost:8000/test

## 📁 Estrutura MVC Implementada

```
app/
├── controllers/     ← LeadController.php (Coordena)
├── models/         ← Lead.php + EmailService.php (Lógica)
└── views/          ← create.php + success.php (Interface)

config/             ← Configurações centralizadas
public/             ← DocumentRoot + Assets
storage/            ← Dados (CSV) + Logs
docs/               ← Documentação
```

## 🎯 Rotas Disponíveis

| URL | Ação |
|-----|------|
| `/` | Formulário |
| `/leads/store` | Processa (POST) |
| `/leads/success` | Confirmação |
| `/test` | Diagnóstico |

## 🔧 Personalizações Comuns

### Adicionar campo no formulário

1. **View** (`app/views/leads/create.php`):
```php
<input type="text" name="novo_campo" />
```

2. **Model** (`app/models/Lead.php`):
```php
private $novoCampo;
// Adicionar getter/setter
```

3. **Controller** - já processa automaticamente via `$_POST`

### Alterar cores

Edite as classes Tailwind nas views:
- `indigo-600` → Sua cor preferida
- `blue-50` → Background

### Personalizar email

Edite `app/models/EmailService.php`:
- Método `getHtmlEmailTemplate()`
- Método `getTextEmailTemplate()`

## 📊 Dados dos Leads

Os leads são salvos automaticamente em:
```
storage/data/leads.csv
```

Formato:
```csv
Data,Nome,Email,Telefone,Descrição
2025-11-06 10:30:00,João Silva,joao@email.com,(11) 98765-4321,Interessado
```

## 🔍 Diagnóstico

### Servidor não inicia?
```bash
php -v  # Verifica se PHP está instalado
lsof -i :8000  # Verifica se porta está ocupada
```

### Email não envia?
```bash
# Instalar sendmail (Linux)
sudo apt-get install sendmail

# Testar função mail
php -r "echo function_exists('mail') ? 'OK' : 'Não disponível';"
```

### Permissões?
```bash
chmod -R 755 storage/
chmod -R 755 public/
```

## 📚 Documentação Completa

- **README.md** - Visão geral do projeto
- **docs/ARQUITETURA.md** - Diagramas e fluxos MVC
- **docs/README.md** - Documentação original completa
- **docs/INSTALACAO.md** - Guia de instalação detalhado

## 🎨 Tecnologias Utilizadas

- **PHP 7.4+** - Backend
- **Arquitetura MVC** - Padrão de projeto
- **Tailwind CSS** - Estilização
- **Font Awesome** - Ícones
- **CSV** - Armazenamento de dados
- **PHPMailer nativo** - Envio de emails

## ✅ Checklist de Deploy

- [ ] Configurar emails em `config/config.php`
- [ ] Adicionar PDF em `public/assets/pdf/`
- [ ] Configurar permissões (755 para storage/)
- [ ] Desativar DEBUG_MODE (`config/config.php`)
- [ ] Configurar HTTPS (SSL)
- [ ] Apontar DocumentRoot para `/public`
- [ ] Testar envio de email
- [ ] Configurar backup do CSV
- [ ] Configurar banco de dados (opcional)

## 🚀 Deploy em Produção

### Apache
```apache
<VirtualHost *:80>
    ServerName seudominio.com
    DocumentRoot /var/www/projeto/public
    
    <Directory /var/www/projeto/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Nginx
```nginx
server {
    listen 80;
    server_name seudominio.com;
    root /var/www/projeto/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
    }
}
```

## 💡 Dicas

1. **Teste localmente primeiro** com `php -S localhost:8000`
2. **Configure emails** antes de testar o envio
3. **Verifique logs** em `storage/logs/app.log`
4. **Backup do CSV** regularmente
5. **Use HTTPS** em produção

## 🆘 Suporte

Problemas comuns resolvidos em:
- `docs/README.md` - Seção "Solução de Problemas"
- Logs: `storage/logs/app.log`
- Teste: http://localhost:8000/test

---

**Sistema pronto para uso! 🎉**

Acesse: http://localhost:8000
