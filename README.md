# Makis Digital — Plataforma de Negócios 🚀

Plataforma premium em **Laravel 13** (PHP 8.4): site público com blog, serviços, projetos e captura de leads, painel administrativo com 2FA e API REST versionada — com foco em alta performance, conversão e **resiliência industrial**.

## 🧰 Stack

- **Backend**: Laravel 13, PHP 8.4 · Sanctum (API) · Spatie Permission (RBAC)
- **Frontend**: Vite + Tailwind CSS 3 + Alpine.js (Blade)
- **Banco**: MySQL/MariaDB local · PostgreSQL 15 em produção · SQLite nos testes
- **Infra**: Docker (php-fpm + nginx + supervisor), deploy via Coolify
- **IA**: microserviço de auditoria em `pipeline/` (Bun + Elysia + Python)

## 🛠️ Setup Rápido

```bash
composer run setup   # install + .env + key + migrate + npm install + build
start_server.bat     # Windows: inicia o servidor com o php.ini correto
```

Ambiente completo de desenvolvimento (server + queue + vite + pipeline IA + logs):

```bash
composer run dev
```

> **Windows**: use sempre `start_server.bat` / `php.bat` / `pa.bat` / `composer.bat` —
> eles carregam o `php.ini` do repositório com as extensões necessárias (pdo_mysql, pdo_sqlite, …).

## ✅ Qualidade

| Comando                | O que faz                                  |
| ---------------------- | ------------------------------------------ |
| `composer run test`    | PHPUnit (SQLite in-memory)                 |
| `composer run lint`    | Laravel Pint (PSR-12)                      |
| `composer run analyse` | PHPStan nível 8 + baseline (Larastan)      |
| `composer audit`       | Vulnerabilidades de dependências           |

O CI (`.github/workflows/ci.yml`) roda tudo isso em cada PR; deploy para produção (Coolify) só acontece com a `main` verde. Detalhes em [docs/ci-cd.md](docs/ci-cd.md).

## 🛡️ Arquitetura de Resiliência

1. **Independência do banco**: `SESSION_DRIVER=file` e `CACHE_STORE=file` — o site sobe mesmo com o MySQL fora.
2. **Blindagem de controllers**: consultas dinâmicas protegidas por `try-catch`; com o banco fora, o site exibe conteúdo estático e segue no ar.
3. **Monitoramento**: falhas de banco logadas como `CRITICAL` em `storage/logs/laravel.log`.

## 🔒 Segurança

- **Senhas**: Argon2id + pepper (`AUTH_PEPPER`) via `hash_password()`/`check_password()`.
- **Admin web**: `/gestao-makis` exige `is_admin`, e-mail verificado e **2FA**.
- **Admin API**: emissão de token para usuários privilegiados exige código 2FA; rotas admin exigem ability `2fa:verified` + ability por recurso (middleware `ApiAdminSecurity`).
- **Tokens**: expiram em 30 dias.
- **Headers**: middleware `SecurityHeaders` com CSP robusta.
- **Uploads**: nome UUID + extensão derivada do conteúdo real (nunca do cliente).

Relatório completo: [docs/security/security-best-practices-report.md](docs/security/security-best-practices-report.md).

## 📂 Organização

```
app/                 Controllers (single-action na API), Models, Policies, Middleware
modules/             Lógica de negócio (namespace Modules\, PSR-4)
routes/              web, admin (prefixo configurável), api (v1), auth
tests/               Feature (Admin, Api, Auth, Public) + Unit
docs/                CI/CD, deploy, segurança, histórico
scripts/             Scripts de deploy e otimização
pipeline/            Microserviço de IA (Bun)
```

Referências para desenvolvimento: [AGENTS.md](AGENTS.md) · [CONTRIBUTING.md](CONTRIBUTING.md) · [SKILL.md](SKILL.md) · [CLAUDE.md](CLAUDE.md)

---

**Makis Digital** — _Excelência em cada detalhe técnico._
