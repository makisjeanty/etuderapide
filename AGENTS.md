# AGENTS.md — Etuderapide Workspace

## Stack

Laravel 13 + PHP 8.3, MySQL (MariaDB 10.4 local), Vite + Tailwind 3 + AlpineJS, Spatie Permission (RBAC), Sanctum (auth). Separate AI pipeline in `pipeline/` (Bun + Elysia + Python). Locale `pt_BR`, timezone `America/Sao_Paulo`.

## Critical Commands

```bash
# Windows — always use this, NOT php artisan serve
start_server.bat

# Full dev environment (server + queue + vite + AI pipeline + logs)
composer run dev

# Full install: composer install → .env → key:generate → migrate → npm install → build
composer run setup

# Tests: local with SQLite in-memory
composer run test

# Tests: full with PostgreSQL via Docker (two options)
docker compose -f docker-compose.test.yml up --abort-on-container-exit
# Alternative (standalone, adjust path to your workspace):
docker run --rm --network host -v "${PWD}:C:\app" -w C:\app php:8.3-cli php vendor/bin/phpunit

# Lint / format (Pint, PSR-12)
./vendor/bin/pint

# Static analysis (Larastan/PHPStan level 8 + baseline; instalado em require-dev)
composer run analyse   # = vendor/bin/phpstan analyse --memory-limit=2G

# Shortcuts available in repo root
pa.bat         # -> C:\php-8.4\php.exe -c php.ini artisan
php.bat        # -> C:\php-8.4\php.exe -c php.ini
composer.bat   # -> php -c php.ini C:\ProgramData\ComposerSetup\bin\composer.phar
```

## Database (MySQL Local)

MariaDB 10.4 (MySQL-compatible) on localhost:3306. DB: `etuderapide` / user: `root` / password: (empty).

## Seeds & Custom Commands

- **`RoleSeeder`** creates 3 roles (`admin`, `editor`, `user`) + 6 permissions. Required for CI and fresh DBs. Run via `php artisan db:seed --class=RoleSeeder`.
- **`ProductionSeeder`**, **`ProfessionalContentSeeder`**, **`DatabaseSeeder`** available for richer data.
- Custom Artisan: `php artisan makis:backup` (DB dump + uploads), `php artisan app:sync-admin-roles` (sync `is_admin` flag → Spatie `admin` role).

## Environment Quirks

- `.npmrc` sets `ignore-scripts=true` — npm lifecycle scripts never run. `npm install --ignore-scripts` is intentional.
- `AUTH_PEPPER` default is `bin2hex(random_bytes(32))` — regenerates every time env is unset. Every `.env` needs an explicit `AUTH_PEPPER`.
- `SESSION_DRIVER=file`, `CACHE_STORE=file`: site stays online even if DB is down. Controllers must catch `QueryException` and log with `CRITICAL`.
- Local `.env` uses `QUEUE_CONNECTION=sync` (jobs run synchronously, no worker needed). `.env.example` defaults to `database` — don't copy blindly.
- Repo root `php.ini` sets `date.timezone`, enables pdo_mysql/pdo_sqlite/sqlite3/mbstring/fileinfo/gd/openssl/curl. Required by `pa.bat`, `php.bat`, `composer.bat` and `start_server.bat` (pdo_sqlite é necessário para os testes).
- PHP 8.4 is at `C:\php-8.4\php.exe` with extensions in `C:\php-8.4\ext`. The `php.ini` references this path.

## Architecture

- **Admin routes**: `/{ADMIN_PREFIX}` (default: `gestao-makis`). Middleware: `web → auth → verified → admin → two_factor`. Defined in `routes/admin.php`.
- **Auth routes**: `/` (default) for public login, `/{LOGIN_PREFIX}` (default: `acesso-secreto`) for admin login. Defined in `routes/auth.php`.
- **API routes** (`routes/api.php`): dual-registration — both unversioned (`api.*`) and versioned (`api.v1.*`, prefix `v1`). Public and Sanctum-authenticated endpoints. Admin API under `/api/{v1/}admin/*`.
- **Custom helpers** (`app/helpers.php`): `hash_password()`, `check_password()` — append pepper from `config('app.auth_pepper')`, then delegate to Laravel's `Hash::make()`/`Hash::check()` (Argon2id).
- **Business logic**: `Modules\` namespace → `modules/` dir (PSR-4). Most subdirectories are stub/`gitkeep` — check before assuming code exists.
- **Blog**: `Post` belongsTo `Category`, `Post` belongsToMany `Tag` (pivot `post_tag`). `Category.type` filters content type (`post`, `project`, `service`, `general`).
- **`app/Models/User.php`**: `isAdmin()` checks `is_admin=1` flag **OR** Spatie admin role.

## Security

- **Admin 2FA (web)**: `TwoFactorVerification` middleware enforces 2FA for the admin panel.
- **Admin 2FA (API)**: `TokenController` exige código 2FA por e-mail para usuários privilegiados antes de emitir token; o token recebe a ability `2fa:verified`.
- **Token ability enforcement**: middleware `ApiAdminSecurity` exige `2fa:verified` + ability por recurso (`leads:manage`, `posts:manage`, etc.) em todas as rotas admin da API.
- **Token expiry**: tokens emitidos com validade de 30 dias.
- **Password hashing**: Argon2id + pepper via `hash_password()`/`check_password()`.
- **Bot protection**: `PreventBotsMiddleware` uses honeypot field `website_url`.
- **Media upload**: nome de arquivo é UUID e a extensão é derivada do MIME real do conteúdo (`$file->extension()`), nunca do cliente.

## Middleware Aliases

- `admin` → `EnsureUserIsAdmin` (checks `canAccessAdminPanel()`, logs denial)
- `two_factor` → `TwoFactorVerification` (session `2fa_verified` flag)
- `bot_protection` → `PreventBotsMiddleware`
- Global: `SecurityHeaders` (CSP, HSTS, etc.) appended to every route

## CI/CD (GitHub Actions)

Workflow único em `.github/workflows/ci.yml`. Push em `main`/`develop` ou PR para `main` roda o job `quality`: build do Vite + PHPUnit (SQLite in-memory) + Pint + PHPStan (nível 8 + baseline) + Composer audit. Em push na `main`, após `quality` verde, o job `deploy` conecta via SSH (chave restrita `DEPLOY_SSH_KEY`) no VPS HestiaCP e executa `/home/damil/deploy-etude.sh`. Detalhes em `docs/ci-cd.md`.

## Production Docker Stack

Five services (`docker-compose.yml`): `app` (php-fpm 8.3) + `db` (PostgreSQL 15) + `pipeline` (Bun) + `redis` (7) + `nginx`. Entrypoint runs `migrate --force`, `config:cache`, `route:cache`, `view:cache`.

## Known Issues / Gaps

- **Modules dir** is mostly stubs — verify actual code before assuming module exists
- **PHPStan baseline** (`phpstan-baseline.neon`) registra 233 erros pré-existentes para correção gradual — não adicione novos

## AI Pipeline

Separate service: `pipeline/` (Elysia/Bun server on port 3001). POST `/analyze` validates input, spawns `python/analyzer.py`. Configured via `AI_PIPELINE_URL` env. If Python is not on the PATH, set `PYTHON_BIN` before starting the pipeline. Run locally with `cd pipeline && bun run dev`.

## Reference Docs (in repo)

- `SKILL.md` — comprehensive workspace reference
- `CONTRIBUTING.md` — coding standards, git workflow, API format
- `docs/ci-cd.md` — pipeline de CI/CD e deploy (Coolify)
- `docs/deploy-simples.md` — deploy manual via Docker Compose
- `docs/security/security-best-practices-report.md` — relatório de segurança (achados já corrigidos)
- `docs/history/` — relatórios históricos de análise e implementação
