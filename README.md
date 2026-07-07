# Economiza Aí — Controle Financeiro (SaaS em Laravel)

Aplicação web em **Laravel 12** para controle financeiro pessoal, em transformação para **SaaS por assinatura**. Cada usuário tem seus próprios dados (transações e categorias) totalmente isolados, com autenticação de produção via **Laravel Fortify**.

> Nome do produto: **Economiza Aí** (configurável em `APP_NAME`).

## 🎯 Funcionalidades

- **Autenticação de produção (Laravel Fortify)**:
  - Login e registro com hash de senha seguro (Bcrypt)
  - Recuperação de senha ("esqueci minha senha") por e-mail
  - **Verificação de e-mail obrigatória** antes de acessar a aplicação
  - Rate limit de login (5 tentativas/minuto por e-mail + IP)
  - E-mails de verificação e redefinição em **PT-BR**, enviados via fila
- **Isolamento por usuário (multi-tenancy)**: cada usuário só enxerga e manipula
  as próprias transações e categorias (proteção contra IDOR via Policies)
- **Período de teste (trial)**: cada novo usuário recebe uma data `trial_ends_at`
  (base para a futura assinatura)
- **Dashboard**:
  - Tabela de transações paginada (20 por página)
  - Filtros por Período (mês/ano), Entrada (Receitas/Despesas), Categoria e Busca por descrição
  - Saldos calculados no banco sobre o conjunto filtrado: Total, Receitas e Despesas
- **Gerenciamento de Transações**: CRUD completo (Criar, Ler, Atualizar, Deletar)
  - Campos: Tipo (Receitas/Despesas), Descrição, Valor e Categoria
- **Categorias**: CRUD de categorias por usuário, com tipo (Receitas/Despesas)
- **Cálculos dinâmicos**: Total, soma de Receitas, soma de Despesas e Saldo (Receitas − Despesas)

## 🛠️ Requisitos

- PHP 8.2+
- Composer
- MySQL 8+ (ou MariaDB)
- Node.js (para assets, opcional)

## 📦 Instalação

### 1. Clonar o repositório
```bash
git clone <seu-repositorio>
cd DESAFIO
```

### 2. Instalar dependências
```bash
composer install
```

### 3. Configurar ambiente
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurar o banco de dados
Crie o banco e ajuste as credenciais no `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=desafio_controle_financeiro
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

### 5. Executar migrations
```bash
php artisan migrate
```

### 6. Configurar o envio de e-mail
A verificação de e-mail é obrigatória, então a aplicação precisa conseguir enviar e-mail.

- **Sem credenciais (dev)**: com `MAIL_MAILER=log`, o link de verificação é gravado
  em `storage/logs/laravel.log` em vez de enviado.
- **Testando envio real (Mailtrap)**: no `.env`, use
  ```env
  MAIL_MAILER=smtp
  MAIL_HOST=sandbox.smtp.mailtrap.io
  MAIL_PORT=2525
  MAIL_USERNAME=<username da sua Inbox no Mailtrap>
  MAIL_PASSWORD=<password da sua Inbox no Mailtrap>
  ```

### 7. Iniciar servidor e worker da fila
Os e-mails são **enfileirados** (`ShouldQueue`) para não bloquear o cadastro. Com a
fila `database`, é preciso um worker ativo, senão os e-mails ficam parados na tabela `jobs`:

```bash
php artisan serve
php artisan queue:work   # em outro terminal
```

> Alternativa em dev: definir `QUEUE_CONNECTION=sync` no `.env` para enviar de forma
> síncrona, sem precisar de um worker.

A aplicação estará disponível em: `http://127.0.0.1:8000`

## 🚀 Como Usar

### Registro
1. Acesse `/register`
2. Preencha nome, e-mail e senha
3. Um e-mail de verificação (PT-BR) é enviado; confirme o link antes de usar a aplicação

### Login
1. Acesse `/login` (a raiz `/` redireciona para o dashboard, que exige login)
2. Insira e-mail e senha
3. Após login, é redirecionado ao `/dashboard`

### Recuperação de senha
1. Em `/login`, use "Esqueci minha senha" (`/forgot-password`)
2. Um e-mail com link de redefinição é enviado
3. O link abre `/reset-password/{token}` para definir a nova senha

### Dashboard
- **Saldos**: TOTAL, RECEITAS e DESPESAS, calculados sobre o conjunto filtrado
- **Filtros**:
  - **Período**: mês/ano (aplicado sobre a data de criação da transação)
  - **Entrada**: "Receitas" ou "Despesas"
  - **Categoria**: uma das categorias do próprio usuário
  - **Busca**: parte da descrição
- **Tabela**: transações do usuário, paginadas (20 por página)

### Gerenciar Transações
- **Nova Transação**: informe Tipo (Receitas/Despesas), Descrição, Valor (decimal) e Categoria.
  O dono da transação é sempre o usuário autenticado (não há seleção manual de usuário).
- **Editar / Visualizar / Deletar**: disponíveis apenas para as transações do próprio usuário.

### Categorias
- Gerencie suas categorias em `/categorias` (cada categoria pertence ao usuário logado).

## 📁 Estrutura do Projeto

```
├── app/
│   ├── Actions/
│   │   └── Fortify/                 # CreateNewUser, ResetUserPassword, UpdateUserPassword
│   ├── Http/
│   │   └── Controllers/
│   │       ├── DashboardController.php
│   │       ├── TransacoesController.php
│   │       └── CategoriasController.php
│   ├── Notifications/               # e-mails PT-BR enfileirados
│   │   ├── VerificarEmailNotification.php
│   │   └── RedefinirSenhaNotification.php
│   ├── Policies/                    # isolamento por usuário (IDOR)
│   ├── Providers/
│   │   └── FortifyServiceProvider.php
│   └── Models/
│       ├── Usuario.php
│       ├── Transacao.php
│       └── Categoria.php
├── config/
│   ├── auth.php
│   ├── fortify.php
│   ├── mail.php
│   └── queue.php
├── database/
│   └── migrations/
├── resources/
│   └── views/
│       ├── dashboard/
│       ├── transacoes/
│       ├── categorias/
│       ├── auth/                    # verify-email, forgot-password, reset-password
│       ├── usuarios/                # view de registro
│       └── login/
└── routes/
    └── web.php
```

## 🔐 Segurança

- **Autenticação**: Laravel Fortify (backend-only, mantendo as views Blade PT-BR)
- **Verificação de e-mail**: rotas da aplicação protegidas por middleware `auth` + `verified`
- **Multi-tenancy**: queries sempre escopadas por `Auth::user()`; Policies bloqueiam
  acesso a registros de outros usuários (IDOR)
- **Rate limiting**: login limitado a 5 tentativas/minuto por e-mail + IP
- **Senhas**: hash Bcrypt (rounds configurável via `BCRYPT_ROUNDS`)
- **Sessões**: regeneração após login
- **CSRF Protection**: tokens em todos os formulários
- **Mass assignment**: campos sensíveis (ex.: `email_verified_at`) fora do `$fillable`

## 📊 Modelos de Dados

### Usuario  (tabela `usuarios`)
- id
- nome
- email (único)
- email_verified_at
- password
- trial_ends_at
- timestamps

### Categoria  (tabela `categorias`)
- id
- usuario_id (FK)
- tipo (Receitas/Despesas)
- nome
- timestamps

### Transacao  (tabela `transacoes`)
- id
- tipo (Receitas/Despesas)
- descricao
- valor (decimal 10,2)
- categoria_id (FK)
- usuario_id (FK)
- timestamps

## 📝 Rotas Disponíveis

### Autenticação (registradas automaticamente pelo Fortify)

| Método | Rota | Descrição |
|--------|------|-----------|
| GET/POST | `/login` | Tela e processamento de login |
| GET/POST | `/register` | Tela e criação de usuário |
| POST | `/logout` | Logout |
| GET/POST | `/forgot-password` | Solicitar link de redefinição de senha |
| GET/POST | `/reset-password` | Redefinir senha via token |
| GET | `/email/verify` | Aviso de verificação de e-mail |
| GET | `/email/verify/{id}/{hash}` | Confirmar e-mail |
| POST | `/email/verification-notification` | Reenviar e-mail de verificação |

### Aplicação (em `routes/web.php`, protegidas por `auth` + `verified`)

| Método | Rota | Controller | Descrição |
|--------|------|-----------|-----------|
| GET | `/dashboard` | DashboardController@index | Dashboard com saldos |
| GET | `/filtro` | DashboardController@filtroDashboard | Dashboard com filtros aplicados |
| resource | `/transacoes` | TransacoesController | CRUD de transações |
| resource | `/categorias` | CategoriasController | CRUD de categorias |

## 🎓 Tecnologias Utilizadas

- **Framework**: Laravel 12
- **Linguagem**: PHP 8.2+
- **Banco de Dados**: MySQL
- **Autenticação**: Laravel Fortify (session-based)
- **Fila**: driver `database` (para envio de e-mails)
- **Views**: Blade Template Engine
- **Testes**: PHPUnit (rodam em SQLite `:memory:`)

## 🧪 Testes

```bash
php artisan test
```

## 🔄 Evolução para SaaS (atualizações recentes)

- ✅ **Segurança**: correção de mass assignment no registro, locale pt_BR, padrão PRG nos controllers
- ✅ **Multi-tenancy**: transações e categorias escopadas por `usuario_id`, Policies contra IDOR,
  remoção do seletor de "Usuário" das transações, paginação
- ✅ **Autenticação (Fortify)**: registro, recuperação de senha, verificação de e-mail obrigatória,
  rate limit de login, período de trial
- ✅ **E-mail**: notificações de verificação e reset traduzidas para PT-BR e enfileiradas (`ShouldQueue`)
- ⏳ **Próximos passos**: provedor de e-mail de produção, site público + LGPD, e por último o billing/assinatura

## 🐛 Troubleshooting

### E-mail de verificação não chega
- Confirme `MAIL_*` no `.env` (Mailtrap ou provedor real)
- Como os e-mails são enfileirados, garanta um `php artisan queue:work` ativo
  (ou use `QUEUE_CONNECTION=sync` em dev)
- Verifique `storage/logs/laravel.log` e a tabela `failed_jobs`

### Erro: "SQLSTATE[HY000] [1049] Unknown database"
- Crie o banco definido em `DB_DATABASE` antes de rodar as migrations

### Erro: "SQLSTATE[HY000]" ao conectar
- Verifique credenciais do banco em `.env` e se o MySQL está rodando
- Execute `php artisan migrate`

### Login não funciona
- Realize o cadastro antes (não há dados iniciais)
- Confirme que `config/fortify.php` usa o e-mail como username

## 👨‍💻 Autor

**Lucas Pereira Rocha**

## 📄 Licença

Projeto licenciado sob a [MIT License](https://opensource.org/licenses/MIT).

---

**Desenvolvido como desafio educacional em Laravel.**
