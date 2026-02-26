# Guia de Uso - Sistema de Gestão Hospitalar

## 🚀 Primeiros Passos

### Pré-requisitos
- XAMPP instalado
- PHP 8.1+
- MySQL/MariaDB
- Composer

---

## 1️⃣ Iniciar os Serviços

### Passo 1: Abrir XAMPP Control Panel
1. Procure por "XAMPP Control Panel" e abra
2. Clique em **Start** para os serviços:
   - ✅ Apache
   - ✅ MySQL

### Passo 2: Criar o Banco de Dados
1. Abra o navegador e vá para: `http://localhost/phpmyadmin`
2. Clique em **Novo** (à esquerda)
3. Digite o nome do banco: `hospitaldb`
4. Clique em **Criar**

---

## 2️⃣ Configurar a Aplicação

### Passo 1: Verificar o .env
O arquivo `.env` já está configurado. Se precisar mudá-lo:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hospitaldb
DB_USERNAME=root
DB_PASSWORD=
```

### Passo 2: Installar composer e Executar as Migrations
Abra o **PowerShell/CMD** na pasta do projeto e execute:

```bash
composer install
```

---

```bash
php artisan migrate
```

---

## 3️⃣ Iniciar a Aplicação

### Iniciar o Servidor Laravel
```bash
php artisan serve
```

Você verá:
```
Starting Laravel development server: http://127.0.0.1:8000
```

Acesse no navegador: **http://localhost:8000**

---

## 📝 Dados de Teste

### Primeiro Admin (Criado Automaticamente)
Ao executar a migration e seeder, um admin é criado automaticamente:

```bash
php artisan migrate
php artisan db:seed --class=AdminSeeder
```

### Dados de Login - Admin Padrão
- **Email**: admin@hospital.com
- **Senha**: admin123
- **Cargo**: Administrador Geral

---

## 🔐 Sistemas de Roles Implementados

### Admin (Administrador)
- Acesso total ao sistema
- **Criar/Editar/Deletar** médicos, pacientes e outros admins
- Visualizar dashboard com estatísticas gerais
- Gerenciar perfis de usuários

### Médico
- Visualizar lista de pacientes
- Ver e editar dados pessoais
- Dashboard com informações profissionais
- **Gerenciar disponibilidades** para atendimentos (data, horário, período)
- **Não pode** cadastrar novos pacientes ou médicos

### Paciente
- Visualizar e editar seus próprios dados
- Dashboard com dados de saúde
- **Não pode** acessar dados de outros pacientes
- **Não pode** cadastrar outros perfis

---

## 📅 Sistema de Disponibilidades (Médicos)

### O que é?
Os médicos podem **selecionar seus períodos de disponibilidade**, indicando quando estarão disponíveis para atendimentos.

### Como funciona?
- Médicos definem: **data**, **hora de início/fim** e **período** (manhã, tarde, noite)
- **Períodos não marcados** = médico **indisponível**
- Sistema evita conflitos de horários

### Formato dos dados
- **Data**: dd/mm/yyyy (ex: 25/02/2026)
- **Horários**: HH:mm no formato 24 horas (ex: 09:00, 14:30)
- **Períodos**: manhã, tarde ou noite

### Rotas de Disponibilidade
| Rota | Descrição | Acesso |
|------|-----------|--------|
| `/disponibilidades` | Lista de disponibilidades do médico | Médico |
| `/disponibilidades/criar` | Adicionar nova disponibilidade | Médico |
| `/disponibilidades/calendario` | Visualização em calendário | Médico |
| `/disponibilidades/{id}/editar` | Editar disponibilidade | Médico |
| `DELETE /disponibilidades/{id}` | Remover disponibilidade | Médico |

### Como acessar?
1. Faça login como **Médico**
2. Vá para **Lista de Médicos** (`/medicos`)
3. Clique em **"Minhas Disponibilidades"**
4. Adicione seus períodos disponíveis

---

## 📍 Rotas Principais

| Rota | Descrição | Acesso |
|------|-----------|--------|
| `/` | Home page | Público |
| `/login` | Página de login | Público |
| `/register` | Página de registro | Público (Admin, Médico, Paciente) |
| `/home` | Dashboard personalizado | Autenticado |
| `/dashboard` | Dashboard (alias) | Autenticado |
| `/medicos` | Lista de médicos | Admin, Médico |
| `/pacientes` | Lista de pacientes | Admin, Paciente |
| `/admins` | Lista de administradores | Admin apenas |
| `/medicos/create` | Criar médico | Admin apenas |
| `/pacientes/create` | Criar paciente | Admin apenas |
| `/admins/create` | Criar admin | Admin apenas |

---

## 🐛 Troubleshooting

### Erro: "No application encryption key has been specified"
```bash
php artisan key:generate
```

### Erro: "Connection refused" no MySQL
1. Verifique se o MySQL está rodando no XAMPP Control Panel
2. Verifique as credenciais no `.env`

### Erro: "SQLSTATE[HY000]: General error"
```bash
php artisan migrate:fresh
```

### Erro ao acessar register
O erro foi corrigido substituindo `@vite` por CDN do Bootstrap. Se ainda houver problema:
1. Limpe o cache: `php artisan config:cache`
2. Reinicie o servidor Laravel

---

## 📚 Estrutura de Arquivos Importante

```
Hospital/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── DashboardController.php
│   │   │   ├── AdminController.php
│   │   │   ├── MedicoController.php
│   │   │   └── PacienteController.php
│   │   └── Middleware/
│   │       └── CheckRole.php
│   └── Models/
│       ├── User.php
│       ├── Admin.php
│       ├── Medico.php
│       └── Paciente.php
├── resources/views/
│   ├── dashboard/
│   ├── admins/
│   ├── medicos/
│   ├── pacientes/
│   └── layouts/
│       └── app.blade.php
└── routes/
    └── web.php
```

---

## ✅ Checklist de Setup

- [ ] MySQL rodando
- [ ] Banco de dados criado (`hospitaldb`)
- [ ] Migrations executadas (`php artisan migrate`)
- [ ] Servidor iniciado (`php artisan serve`)
- [ ] Acessado `http://localhost:8000` com sucesso
- [ ] Conseguiu fazer login ou registro

---

## 📞 Informações de Suporte

Para dúvidas ou problemas:
1. Verifique o log em `storage/logs/laravel.log`
2. Use `php artisan tinker` para debugar
3. Verifique a configuração do `.env`

---

**Última Atualização**: 24 de Fevereiro de 2026

