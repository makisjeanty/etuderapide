---
name: Etuderapide Workspace Master Skill
description: Comprehensive skill for Laravel 13 + PHP 8.3 full-stack development with PostgreSQL, Vite, AI pipeline integration, and blog system management
applyTo:
    - "**/*.php"
    - "**/*.blade.php"
    - "**/*.js"
    - "**/*.ts"
    - "package.json"
    - "composer.json"
    - "docker-compose.yml"
    - ".env*"
---

# Etuderapide Workspace Master Skill

## Quick Reference

### Critical Commands (Windows)

```bash
# Start dev server (MUST use this, not php artisan serve)
start_server.bat

# Full setup (composer + migrations + build)
composer run setup

# Database (PostgreSQL Docker)
docker start etuderapide-db

# Run migrations
php artisan migrate

# Tests (Docker with PHP 8.3 + PostgreSQL)
docker run --rm --network host -v "D:\etuderapide-workspace:C:\app" -w C:\app php:8.3-cli php vendor/bin/phpunit

# Linting (Pint)
./vendor/bin/pint

# AI Pipeline (separate Bun server)
cd pipeline && bun run dev

# Frontend builds
npm run build      # Production
npm run dev        # Watch mode
```

## Stack Overview

- **Backend**: Laravel 13 + PHP 8.3
- **Frontend**: Vite + Tailwind CSS 3 + AlpineJS (progressive enhancement, not SPA)
- **Database**: PostgreSQL (Docker container)
- **Auth**: Laravel Sanctum + Spatie Permission (RBAC)
- **Blog**: Posts → Category (1-to-many), Posts ⟷ Tags (many-to-many)
- **AI**: Separate pipeline in `pipeline/` (Bun + Elysia microservice). Requer `bun` e `python` instalados, com fallback para `PYTHON_BIN`, `python`, ou `python3`.

## Architecture Patterns

### Admin Routes & Security

- **Route prefix**: Configurable via `ADMIN_PREFIX` (default: `gestao-makis`)
- **Middleware chain**: `web` → `auth` → `verified` → `admin` → `two_factor`
- **Admin check**: `Admin` middleware verifies `is_admin=1` OR admin role via Spatie
- **2FA enforcement**: All admin routes require two-factor authentication
- **Bot protection**: `BotProtection` middleware on public forms

### Authentication & Passwords

- **Pepper**: Custom security pepper in `app/helpers.php`
    - `hash_password($password)` → Argon2id + pepper
    - `check_password($password, $hash)` → Constant-time comparison
- **Config key**: `app.auth_pepper` in `.env` (critical for security)

### Database Resilience

- **Failure handling**: Site stays online if database is down
    - `SESSION_DRIVER=file` + `CACHE_STORE=file`
    - Controllers use try-catch blocks
    - Database failures logged as `CRITICAL` in `storage/logs/laravel.log`
- **Graceful degradation**: File-based sessions/cache fallback

### Blog Data Model

```php
// Category
- id, name, slug, description, type ('post', 'project', 'service', 'general')
- hasMany: Post

// Post
- id, title, slug, content, excerpt, featured_image, category_id, published_at
- seo_title, seo_description (optional)
- belongsTo: Category
- belongsToMany: Tag (via post_tag pivot)

// Tag
- id, name, slug, description
- belongsToMany: Post (via post_tag pivot)
```

### Routes Structure

- **Public**: `/blog` (index), `/blog/{slug}` (show)
- **Admin**: `/gestao-makis/posts`, `/gestao-makis/categories`, `/gestao-makis/tags`
- **Auth**: `/acesso-secreto/*` (configurable via `LOGIN_PREFIX`)

## Development Workflow

### Setting Up New Features

1. **Create migration** (database change):

    ```bash
    php artisan make:migration create_table_name
    php artisan migrate
    ```

2. **Create model + resource controller**:

    ```bash
    php artisan make:model ModelName -mcr
    ```

3. **Add routes** in `routes/web.php` or `routes/admin.php`

4. **Write business logic** in `app/Modules/` namespace

5. **Create/update views** in `resources/views/`

6. **Test locally**: `start_server.bat`, then browser

### Testing & Validation

- **Run tests**: `docker run --rm --network host -v "D:\etuderapide-workspace:C:\app" -w C:\app php:8.3-cli php vendor/bin/phpunit`
- **Tests use**: PostgreSQL (requires pgsql extension)
- **APP_KEY**: Hardcoded in `phpunit.xml` for test stability
- **Email driver**: Set to `array` (logs to storage)

### Blog Operations

#### Creating a Post

1. Go to `/gestao-makis/posts/create`
2. Fill title (slug auto-generated if empty)
3. Select category (filtered by type: 'post' or 'general')
4. Add tags (many-to-many via pivot)
5. Set published_at (required for "published" posts)
6. Optional: SEO title/description
7. Save (audit logged automatically)

#### Category Management

- List: `/gestao-makis/categories`
- Create: `/gestao-makis/categories/create`
- Category `type` field controls filtering in post form

#### Tag Management

- List: `/gestao-makis/tags`
- Create: `/gestao-makis/tags/create`
- Slug auto-generated from name

### External Integrations

- **WhatsApp service**: Custom integration (check .env for config)
- **PDF generation**: `barryvdh/laravel-dompdf`
- **AI Pipeline**: Bun server at `AI_PIPELINE_URL` (check .env)

## Database Setup

### PostgreSQL Container

- **Container**: `etuderapide-db`
- **Host port**: 5433 → Container port 5432
- **Databases**: `etuderapide`, `etuderapide_test`
- **User**: `etuderapide`
- **Password**: `etuderapide2026`

### Key Migrations

- `*_create_tags_table`: Adds `name`, `slug` (unique), `description`
- `*_create_post_tag_table`: Pivot with foreign keys and unique composite index

## Environment Variables

```bash
# Admin routes prefix
ADMIN_PREFIX=gestao-makis

# Auth routes prefix
LOGIN_PREFIX=acesso-secreto

# Password security
AUTH_PEPPER=your-pepper-here

# AI Pipeline
AI_PIPELINE_URL=http://localhost:3001
SANCTUM_EXPIRATION=43200
PYTHON_BIN=C:\Python\Python311\python.exe

# Database
DB_CONNECTION=pgsql
DB_PORT=5433
DB_DATABASE=etuderapide
DB_USERNAME=etuderapide
DB_PASSWORD=etuderapide2026

# Session/Cache (file-based for resilience)
SESSION_DRIVER=file
CACHE_STORE=file
```

## Common Tasks

### Add New Admin Feature

1. Create model, migration, controller (resource)
2. Add route under `/gestao-makis` in `routes/web.php`
3. Create views in `resources/views/admin/`
4. Protect with middleware chain: `web`, `auth`, `verified`, `admin`, `two_factor`
5. Test with `start_server.bat`

### Fix Database Connection Issue

1. Check PostgreSQL container: `docker ps | find etuderapide-db`
2. Start if stopped: `docker start etuderapide-db`
3. Verify credentials in `.env`
4. Run migrations: `php artisan migrate`

### Deploy Changes

- See `docs/deploy-simples.md`, `scripts/deploy-simples.sh`, or `scripts/deploy-optimized.sh`
- Deploy de produção é via Coolify, disparado pelo GitHub Actions (`.github/workflows/ci.yml`) após CI verde na `main`
- Docker builds use `Dockerfile.prod`

### Add New Permission/Role

1. Use Spatie Permission: `php artisan permission:create-role admin --guard=web`
2. Assign to user: `$user->assignRole('admin')`
3. Check in controller: `auth()->user()->hasRole('admin')`

### Debug Failed Request

1. Check `storage/logs/laravel.log` (includes database failures)
2. Check `storage/logs/` for other logs
3. Browser DevTools → Network tab → check requests
4. Check database resilience: if DB is down, check `SESSION_DRIVER` + `CACHE_STORE`

## Troubleshooting

### Server won't start

- Use `start_server.bat`, NOT `php artisan serve`
- Check if another process is using port 8000
- Verify `.env` and `APP_KEY` are set

### Tests fail

- Use Docker: `docker run --rm --network host -v "D:\etuderapide-workspace:C:\app" -w C:\app php:8.3-cli php vendor/bin/phpunit`
- Ensure PostgreSQL container is running: `docker start etuderapide-db`
- Check `phpunit.xml` for APP_KEY and driver configs

### Database connection fails

- Check PostgreSQL container: `docker start etuderapide-db`
- Verify `.env`: port 5433, user/pass match container
- Check logs: `storage/logs/laravel.log` for `CRITICAL` entries

### Post not showing on blog

- Verify `published_at` is set (required for published posts)
- Check category type filtering in post form
- Ensure post status is "published" in database

### 2FA not working

- Check `MAIL_DRIVER` config (set to `array` in test)
- Verify user has 2FA enabled: `user.two_factor_secret` populated
- Check `storage/logs/laravel.log` for mail errors

## Security Checklist

- [ ] `AUTH_PEPPER` set in `.env` (custom password hashing)
- [ ] `APP_KEY` set and strong (base64 string)
- [ ] Database credentials not in git (use `.env`)
- [ ] 2FA enforced for all admin users
- [ ] Argon2id hashing enabled (PHP 7.2+)
- [ ] CSRF protection enabled on all forms
- [ ] Bot protection middleware active
- [ ] File permissions: `storage/` and `bootstrap/cache/` writable

## File Structure Reference

```
d:\Projetos\PHP\etuderapide-workspace\
├── app/
│   ├── Http/Controllers/         # Route controllers
│   ├── Models/                   # Eloquent models
│   ├── Modules/                  # Business logic (PSR-4)
│   └── helpers.php               # hash_password(), check_password()
├── database/
│   ├── migrations/               # Database migrations
│   └── seeders/                  # Database seeders
├── resources/
│   ├── views/                    # Blade templates
│   └── css/tailwind.css          # Tailwind entry
├── routes/
│   ├── web.php                   # Web routes (including /gestao-makis)
│   └── api.php                   # API routes (if needed)
├── storage/logs/laravel.log      # Application logs
├── pipeline/                     # AI microservice (Bun + Elysia)
├── docker-compose.yml            # PostgreSQL + other services
├── phpunit.xml                   # Test configuration
├── AGENTS.md                     # Workspace agents
└── SKILL.md                      # This file
```

## AI Pipeline Integration

The `pipeline/` directory contains a separate Bun server:

- **Start**: `cd pipeline && bun run dev`
- **URL**: Configured via `AI_PIPELINE_URL` in `.env`
- **Purpose**: AI tasks separate from main app
- **Integration**: Laravel calls pipeline via HTTP (see integration points in controllers/services)
- **Runtime**: The Bun server executes `python/analyzer.py`; ensure Python is available in the environment.

---

**Last Updated**: 2026-05-17  
**Status**: Master skill for full-stack Laravel + PostgreSQL + AI development
