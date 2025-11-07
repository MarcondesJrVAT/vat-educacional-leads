# 📊 Arquitetura MVC - Diagrama Visual

## Fluxo de Requisição

```
┌─────────────────────────────────────────────────────────────────┐
│                          USUÁRIO                                 │
│                     (Navegador Web)                              │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             │ HTTP Request
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                       .htaccess                                  │
│                  (Rewrite Rules)                                 │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             │ Redireciona para
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                   public/index.php                               │
│                  (Front Controller)                              │
│                                                                  │
│  • Inicia sessão                                                │
│  • Define constantes (BASE_PATH, APP_PATH, etc)                 │
│  • Carrega config/config.php                                    │
│  • Registra autoloader                                          │
│  • Analisa rota da requisição                                   │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             │ Instancia Controller
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│              app/controllers/LeadController.php                  │
│                      (CONTROLLER)                                │
│                                                                  │
│  Métodos:                                                        │
│  • create()  → Exibir formulário                                │
│  • store()   → Processar cadastro                               │
│  • success() → Página de sucesso                                │
└────────┬────────────────────────────────┬───────────────────────┘
         │                                │
         │ Usa                            │ Renderiza
         ▼                                ▼
┌─────────────────────────┐    ┌──────────────────────────────────┐
│  app/models/Lead.php    │    │   app/views/leads/create.php     │
│      (MODEL)            │    │   app/views/leads/success.php    │
│                         │    │        (VIEW)                     │
│  • validate()           │    │                                   │
│  • sanitize()           │    │  • Formulário HTML               │
│  • saveToCSV()          │    │  • Tailwind CSS                  │
│  • saveToDatabase()     │    │  • JavaScript                    │
└─────────────────────────┘    └──────────────────────────────────┘
         │
         │ Usa
         ▼
┌─────────────────────────────────────────────────────────────────┐
│           app/models/EmailService.php                            │
│                  (SERVICE)                                       │
│                                                                  │
│  • sendLeadEmail()         → Envia PDF ao lead                  │
│  • sendAdminNotification() → Notifica admin                     │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             │ Persiste em
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                     ARMAZENAMENTO                                │
│                                                                  │
│  • storage/data/leads.csv  → Backup em CSV                      │
│  • MySQL Database (opcional) → Banco de dados                   │
│  • storage/logs/app.log → Logs de erro                          │
└─────────────────────────────────────────────────────────────────┘
```

## Estrutura de Diretórios

```
📦 Sistema de Captação de Leads
│
├── 📂 app/                          # Lógica da aplicação
│   ├── 📂 controllers/              # Camada de controle
│   │   └── 📄 LeadController.php   # Gerencia requisições de leads
│   │
│   ├── 📂 models/                   # Camada de negócio
│   │   ├── 📄 Lead.php             # Entidade Lead
│   │   └── 📄 EmailService.php     # Serviço de email
│   │
│   └── 📂 views/                    # Camada de apresentação
│       └── 📂 leads/
│           ├── 📄 create.php       # Formulário
│           └── 📄 success.php      # Confirmação
│
├── 📂 config/                       # Configurações
│   └── 📄 config.php               # Config central
│
├── 📂 public/                       # DocumentRoot (pasta pública)
│   ├── 📄 index.php                # Front Controller
│   ├── 📄 .htaccess                # Regras Apache
│   ├── 📄 test.php                 # Testes do sistema
│   │
│   └── 📂 assets/                  # Assets públicos
│       ├── 📂 css/                 # Estilos
│       ├── 📂 js/
│       │   └── 📄 form.js         # JavaScript do form
│       └── 📂 pdf/
│           └── 📄 sample-course.pdf # PDF enviado
│
├── 📂 storage/                      # Armazenamento
│   ├── 📂 data/
│   │   └── 📄 leads.csv           # Leads salvos
│   └── 📂 logs/
│       └── 📄 app.log             # Logs
│
├── 📂 docs/                         # Documentação
│   ├── 📄 README.md               # Doc completa
│   ├── 📄 INSTALACAO.md           # Guia instalação
│   ├── 📄 database.sql            # Script BD
│   └── 📄 install.sh              # Script instalação
│
├── 📄 .htaccess                    # Rewrite root → public
├── 📄 .gitignore                   # Arquivos ignorados
└── 📄 README.md                    # Este arquivo
```

## Padrão MVC Aplicado

### 🎯 Model (Modelo)
**Responsabilidade**: Lógica de negócio e acesso a dados

- **Lead.php**
  - Representa a entidade Lead
  - Valida dados (validate)
  - Sanitiza inputs (sanitize)
  - Persiste em CSV (saveToCSV)
  - Persiste em DB (saveToDatabase)
  
- **EmailService.php**
  - Envia email com PDF anexo
  - Notifica administrador
  - Templates de email

### 👁️ View (Visão)
**Responsabilidade**: Apresentação e interface

- **create.php**
  - Formulário HTML
  - Design com Tailwind CSS
  - Validação frontend
  - Exibe erros de validação
  
- **success.php**
  - Página de confirmação
  - Feedback visual
  - Instruções para o usuário

### 🎮 Controller (Controlador)
**Responsabilidade**: Coordenação entre Model e View

- **LeadController.php**
  - `create()`: Renderiza formulário
  - `store()`: Processa e salva lead
  - `success()`: Exibe confirmação
  - Gerencia fluxo da aplicação

## Ciclo de Vida de uma Requisição

### 1️⃣ Requisição Inicial (GET /)
```
Usuário → .htaccess → index.php → Router
    ↓
LeadController::create()
    ↓
app/views/leads/create.php (Formulário renderizado)
```

### 2️⃣ Submissão do Formulário (POST /leads/store)
```
Formulário → index.php → Router
    ↓
LeadController::store()
    ↓
    ├─→ new Lead($_POST)
    │   ├─→ sanitize()
    │   ├─→ validate()
    │   ├─→ saveToCSV()
    │   └─→ saveToDatabase() (opcional)
    │
    └─→ EmailService::sendLeadEmail()
    └─→ EmailService::sendAdminNotification()
    ↓
redirect('/leads/success')
```

### 3️⃣ Página de Sucesso (GET /leads/success)
```
Router → LeadController::success()
    ↓
app/views/leads/success.php (Confirmação renderizada)
```

## Princípios Seguidos

### ✅ Separation of Concerns
- Model: Dados e lógica de negócio
- View: Apresentação
- Controller: Coordenação

### ✅ Single Responsibility
- Cada classe tem uma responsabilidade específica
- EmailService separado do Model Lead

### ✅ DRY (Don't Repeat Yourself)
- Configurações centralizadas
- Funções reutilizáveis

### ✅ Segurança
- Validação e sanitização
- Proteção de diretórios
- Headers de segurança

## Rotas do Sistema

| Rota | Método | Controller | Action | Descrição |
|------|--------|-----------|---------|-----------|
| `/` | GET | LeadController | create() | Exibe formulário |
| `/leads` | GET | LeadController | create() | Exibe formulário |
| `/leads/create` | GET | LeadController | create() | Exibe formulário |
| `/leads/store` | POST | LeadController | store() | Processa cadastro |
| `/leads/success` | GET | LeadController | success() | Página de sucesso |
| `/test` | GET | - | - | Testes do sistema |

## Benefícios da Arquitetura MVC

### 🔧 Manutenibilidade
- Código organizado e estruturado
- Fácil localização de bugs
- Mudanças isoladas

### 📈 Escalabilidade
- Fácil adicionar novos recursos
- Preparado para crescimento
- Estrutura profissional

### 👥 Trabalho em Equipe
- Divisão clara de responsabilidades
- Frontend e Backend separados
- Múltiplos desenvolvedores

### 🧪 Testabilidade
- Models podem ser testados isoladamente
- Controllers testáveis
- Lógica separada da apresentação

---

**Arquitetura MVC implementada com sucesso! 🎉**
