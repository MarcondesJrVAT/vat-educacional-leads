# 📧 Sistema de Captação de Leads - Arquitetura MVC

Sistema profissional de captação de leads com arquitetura MVC, formulário responsivo, processamento PHP e envio automático de email com PDF.

## 🌓 Modo Escuro (Dark Mode)

Este projeto inclui um botão flutuante (canto inferior direito) para alternar entre tema claro e escuro.

### Como funciona

- Usa Tailwind com `darkMode: 'class'`.
- O script `public/assets/js/darkmode.js` aplica a classe `dark` em `<html>`.
- A preferência do usuário é persistida em `localStorage` (`theme = 'dark' | 'light'`).
- Respeita preferência inicial do sistema operacional se não houver valor salvo.

### Remover o botão (opcional)

1. Apague o bloco do botão em `app/views/leads/create.php` e `app/views/leads/success.php` (elemento com id `darkmode-toggle`).
2. Remova a tag `<script src="/assets/js/darkmode.js"></script>` dessas views.
3. (Opcional) Exclua o arquivo `public/assets/js/darkmode.js`.

### Personalizar

- Troque o ícone (Font Awesome) dentro do botão (`<i id="darkmode-icon" ...>`).
- Ajuste cores substituindo classes Tailwind dentro das views para variantes `dark:`.
- Para forçar um único tema, remova todas as referências ao script e à classe `dark`.

Trecho do botão:

```html
<button id="darkmode-toggle" aria-label="Alternar modo escuro" class="fixed bottom-4 right-4 z-50 p-3 rounded-full bg-indigo-600 text-white shadow-lg hover:bg-indigo-700 transition focus:outline-none">
    <i id="darkmode-icon" class="fas fa-sun"></i>
</button>
```

Script (já incluído em `public/assets/js/darkmode.js`):

```javascript
(function(){ /* ver arquivo para versão completa */ })();
```

Se aparecer flash de tema incorreto (FOUC), garanta que o snippet de pré-carregamento do tema esteja antes do CSS principal nas views.

## �🏗️ Estrutura do Projeto (MVC)

```
projeto/
├── app/                          # Camada da aplicação
│   ├── controllers/              # Controllers (Lógica de controle)
│   │   └── LeadController.php   # Controller de leads
│   ├── models/                   # Models (Lógica de negócio)
│   │   ├── Lead.php             # Model de Lead
│   │   └── EmailService.php     # Service de email
│   └── views/                    # Views (Interface)
│       └── leads/
│           ├── create.php       # Formulário de cadastro
│           └── success.php      # Página de sucesso
│
├── config/                       # Configurações
│   └── config.php               # Configurações gerais
│
├── public/                       # Pasta pública (DocumentRoot)
│   ├── index.php                # Front Controller
│   ├── assets/
│   │   ├── css/                 # Arquivos CSS
│   │   ├── js/
│   │   │   └── form.js         # JavaScript do formulário
│   │   └── pdf/
│   │       └── sample-course.pdf  # PDF para envio
│   └── .htaccess                # Configuração Apache
│
├── storage/                      # Armazenamento
│   ├── logs/                    # Logs da aplicação
│   │   └── app.log
│   └── data/                    # Dados (CSV, etc)
│       └── leads.csv            # Leads em CSV
│
├── docs/                         # Documentação
│   ├── README.md                # Documentação completa
│   ├── INSTALACAO.md            # Guia de instalação
│   ├── database.sql             # Script do banco (opcional)
│   └── install.sh               # Script de instalação
│
├── .htaccess                     # Rewrite para /public
├── .gitignore                    # Arquivos ignorados
└── README.md                     # Este arquivo
```

## 🎯 Padrão MVC

### Model (Modelo)
- **`Lead.php`**: Representa um lead, valida, sanitiza e persiste dados
- **`EmailService.php`**: Gerencia o envio de emails

### View (Visão)
- **`create.php`**: Interface do formulário de cadastro
- **`success.php`**: Página de confirmação

### Controller (Controlador)
- **`LeadController.php`**: Gerencia requisições e coordena Model e View

## 🚀 Instalação

### 1. Execute o Projeto Localmente (Desenvolvimento)

Requer PHP 7.4+ instalado. Recomenda-se usar um ambiente isolado (Docker, DDEV, Laragon, XAMPP, etc) para desenvolvimento.

```bash
php -S localhost:8085 -t public
```

Acesse: http://localhost:8085

### 2. (Opcional) Configuração em Produção (Apache)
Configure o DocumentRoot para apontar para a pasta `public/`:

```apache
<VirtualHost *:80>
    ServerName seusite.com
    DocumentRoot /caminho/para/projeto/public
    <Directory /caminho/para/projeto/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```


### 3. Configure o Email

Edite `config/config.php`:
```php
define('FROM_EMAIL', 'seu-email@gmail.com');
define('ADMIN_EMAIL', 'seu-email@gmail.com');
```
**Dica:** Para maior segurança, utilize variáveis de ambiente para armazenar senhas e dados sensíveis. Veja exemplos em `.env.example` (crie um se desejar usar [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv)).


### 4. Adicione o PDF

Crie ou adicione um arquivo PDF em:
```
public/assets/pdf/sample-course.pdf
```

Ou use o template:
```bash
# Abra public/assets/pdf/sample-course-template.html no navegador
# Imprima como PDF (Ctrl+P)
# Salve como sample-course.pdf na mesma pasta
```


### 5. Ajuste Permissões

Garanta que as pastas `storage/` e `public/` sejam graváveis pelo servidor web:
```bash
chmod -R 755 storage/
chmod -R 755 public/
# Se necessário para uploads/logs:
chmod -R 777 storage/
```
**Importante:** Em produção, evite permissões 777. Prefira permissões restritas e usuário/grupo corretos.

## 📋 Rotas

| Rota | Método | Descrição |
|------|--------|-----------|
| `/` ou `/leads` | GET | Exibe formulário |
| `/leads/create` | GET | Exibe formulário |
| `/leads/store` | POST | Processa cadastro |
| `/leads/success` | GET | Página de sucesso |

## ✨ Funcionalidades

✅ **Arquitetura MVC** organizada e profissional  
✅ **Front Controller** para roteamento centralizado  
✅ **Separação de responsabilidades** (Model, View, Controller)  
✅ **Validação** no frontend e backend  
✅ **Sanitização** de dados  
✅ **Persistência** em CSV e MySQL (opcional)  
✅ **Email automático** com PDF anexo  
✅ **Design responsivo** com Tailwind CSS  
✅ **Segurança** com .htaccess  
✅ **PSR friendly** (preparado para autoload e namespaces)

## 🔒 Segurança

- Validação e sanitização de inputs
- Proteção contra XSS
- Proteção de diretórios sensíveis via .htaccess
- Configurações isoladas
- Headers de segurança configurados

## 📊 Banco de Dados (Opcional)

Se quiser usar banco de dados:

1. Execute o script SQL:

```bash
mysql -u root -p < docs/database.sql
```

2. Configure as credenciais em `config/config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'leads_db');
define('DB_USER', 'seu_usuario');
define('DB_PASS', 'sua_senha');
define('SAVE_TO_DATABASE', true);
```

## 📧 Configuração de Email

### Gmail

1. Ative a verificação em duas etapas
2. Gere uma senha de aplicativo: https://myaccount.google.com/apppasswords
3. Use no `config/config.php`

### Servidor Linux

```bash
sudo apt-get install sendmail
```

## 🎨 Personalização

### Alterar cores
Edite as classes Tailwind nos arquivos de view (`app/views/`)

### Adicionar campos
1. Adicione no formulário (`app/views/leads/create.php`)
2. Adicione propriedades no Model (`app/models/Lead.php`)
3. Atualize validação

### Personalizar email
Edite `app/models/EmailService.php`

## 🔄 Fluxo de Requisição

```
1. Usuário acessa /
2. .htaccess redireciona para public/index.php
3. index.php carrega configurações e autoloader
4. Roteador identifica a rota e instancia o Controller
5. Controller chama a View adequada
6. Formulário é exibido

Ao submeter:
1. POST para /leads/store
2. LeadController::store() processa
3. Cria instância do Model Lead
4. Valida e sanitiza dados
5. Salva em CSV/Database
6. EmailService envia emails
7. Redireciona para /leads/success
```

## 🛠️ Desenvolvimento

### Adicionar nova rota

Edite `public/index.php`:
```php
case 'nova-rota':
    $controller = new SeuController();
    $controller->metodo();
    break;
```

### Criar novo Model

```php
// app/models/SeuModel.php
<?php
class SeuModel {
    // Sua lógica aqui
}
?>
```

### Criar novo Controller

```php
// app/controllers/SeuController.php
<?php
class SeuController {
    public function metodo() {
        // Sua lógica aqui
        require_once APP_PATH . '/views/sua-view.php';
    }
}
?>
```

## 📝 Logs

Os logs são salvos em:
```
storage/logs/app.log
```

## 💾 Dados dos Leads

CSV gerado automaticamente em:
```
storage/data/leads.csv
```

## 🚨 Modo Debug

Em `config/config.php`:
```php
define('DEBUG_MODE', true);  // Desenvolvimento
define('DEBUG_MODE', false); // Produção
```

## 📚 Documentação Adicional

- **Documentação completa**: `docs/README.md`
- **Guia de instalação**: `docs/INSTALACAO.md`
- **Script do banco**: `docs/database.sql`

## 🤝 Contribuindo

1. Fork o projeto
2. Crie uma branch para sua feature
3. Commit suas mudanças
4. Push para a branch
5. Abra um Pull Request

## 📄 Licença

Este projeto é de código aberto. Use livremente!

## 🎓 Próximos Passos

- [ ] Implementar namespaces (PSR-4)
- [ ] Adicionar Composer
- [ ] Implementar template engine (Twig, Blade)
- [ ] Adicionar testes unitários
- [ ] Criar dashboard administrativo
- [ ] Integração com CRM
- [ ] API REST
- [ ] Autenticação de usuários

---

**Desenvolvido com ❤️ usando arquitetura MVC**
