# Project: Etuderapide (Makis Digital)

Plataforma de negócios em Laravel: site público (blog, serviços, projetos, captura de leads) + painel admin + API REST versionada.

## Tech Stack
- Laravel 13, PHP 8.4 (produção: php-fpm 8.3 Alpine via Docker)
- MySQL/MariaDB local · PostgreSQL 15 em produção · SQLite in-memory nos testes
- Vite + Tailwind 3 + AlpineJS · Sanctum (API auth) · Spatie Permission (RBAC)
- Pipeline de IA separado em `pipeline/` (Bun + Elysia + Python)

## Commands
- Dev (Windows): `start_server.bat` (NUNCA `php artisan serve` direto) · ambiente completo: `composer run dev`
- Build: `npm run build`
- Test: `composer run test` (ou `.\php.bat vendor/bin/phpunit`)
- Lint: `composer run lint` (Pint) · Análise estática: `composer run analyse` (PHPStan nível 8 + baseline)
- Atalhos no root: `php.bat`, `pa.bat` (artisan), `composer.bat` — usam o `php.ini` do repositório

## Architecture
- Controllers single-action de API em `app/Http/Controllers/Api/{Admin,Public,Auth}/`; web em `app/Http/Controllers/{Admin,Web}/`
- Rotas: `routes/api.php` (registro duplo: sem versão + `v1`), `routes/admin.php` (prefixo `ADMIN_PREFIX`, padrão `gestao-makis`), `routes/auth.php`
- Models em `app/Models/`; lógica de negócio complexa no namespace `Modules\` → `modules/` (maioria ainda stub)
- Testes em `tests/Feature/{Admin,Api,Auth,Public}/` e `tests/Unit/`
- Helpers de senha em `app/helpers.php`: `hash_password()`/`check_password()` (Argon2id + pepper `AUTH_PEPPER`)
- Referência completa: `AGENTS.md` e `SKILL.md`

## Code Style
- PSR-12 via Laravel Pint — rode `composer run lint` antes de commitar
- Controllers de API são single-action (um endpoint por classe) e estendem `BaseApiController`
- Strings de UI em pt_BR; timezone `America/Sao_Paulo`
- Controllers públicos devem capturar `QueryException` e degradar graciosamente (site fica no ar sem o banco)

## Rules
- ALWAYS rode os testes após mudanças (`composer run test`)
- ALWAYS rode Pint e PHPStan antes de commitar; não adicione erros novos ao baseline do PHPStan
- NEVER commite direto na main — crie branch e abra PR
- NEVER commite credenciais; `.env.example` usa apenas placeholders
- Admin API exige token com ability `2fa:verified` (middleware `ApiAdminSecurity`) — não enfraquecer
- Keep files under 300 lines — split if larger
