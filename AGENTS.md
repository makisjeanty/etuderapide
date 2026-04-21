# AGENTS.md - Etuderapide Workspace

## Stack

- Laravel 13 + PHP 8.3
- Vite + Tailwind CSS 3 + AlpineJS (not a full SPA)
- PostgreSQL (Docker)
- Spatie Laravel Permission for RBAC
- Laravel Sanctum for auth
- Separate AI pipeline in `pipeline/` (Bun + Elysia)
- Blog system: Posts belongTo Category, Posts BelongsToMany Tag

## Critical Commands

**Windows Setup:**
```bash
# Use start_server.bat, NOT php artisan serve directly
start_server.bat

# Alternative if needed (XAMPP PHP)
"C:\xampp\php\php.exe" artisan serve
```

**Full dev setup:**
```bash
composer run setup

# Database setup (PostgreSQL Docker)
docker start etuderapide-db

# Run migrations
"C:\xampp\php\php.exe" artisan migrate

# Tests (Docker with PHP 8.3 + PostgreSQL)
docker run --rm --network host -v "D:\etuderapide-workspace:C:\app" -w C:\app php:8.3-cli php vendor/bin/phpunit

# Linter (Pint)
./vendor/bin/pint

# AI Pipeline (separate from main app)
cd pipeline && bun run dev
```

## PostgreSQL (Docker)

Container: `etuderapide-db`
- Port: 5433 (mapped to 5432)
- Database: etuderapide, etuderapide_test
- User: etuderapide
- Password: etuderapide2026

## Architecture

- **Admin routes**: `/gestao-makis` (configurable via `ADMIN_PREFIX` env)
- **Admin middleware chain**: `web` → `auth` → `verified` → `admin` → `two_factor`
- **Custom helpers** in `app/helpers.php`: `hash_password()`, `check_password()` (uses `app.auth_pepper`)
- **Security**: Argon2id password hashing with pepper
- **Models use Spatie Permission**: `User` has `Role` and `Permission` via `HasRoles`
- **Blog relations**:
  - `Post` → `category_id` (FK to `categories`)
  - `Post` ⟷ `Tag` (many-to-many via `post_tag` pivot)
  - `Category` → `type` (e.g., 'post', 'project', 'service', 'general')
- **Business logic**: Uses `Modules\` namespace for complex business logic (PSR-4)
- **Resilience**: Database failures gracefully degrade with file-based sessions/cache

## Resilience Architecture

The system includes anti-failure measures:
- `SESSION_DRIVER=file` and `CACHE_STORE=file` ensure site stays online if database fails
- Controllers wrapped in try-catch blocks gracefully handle database failures
- Database failures logged with `CRITICAL` priority in `storage/logs/laravel.log`

## Testing Notes

- Tests use PostgreSQL (requires pgsql extension)
- Use Docker to run tests: `docker run --rm --network host -v "D:\etuderapide-workspace:C:\app" -w C:\app php:8.3-cli php vendor/bin/phpunit`
- APP_KEY is hardcoded in `phpunit.xml` for test stability
- Email/notification drivers set to `array` (log)

## Important Middleware

- `admin`: Ensures user has is_admin=1 or admin role
- `two_factor`: Enforces 2FA requirement
- `bot_protection`: Bot detection

## External Integrations (check .env)

- WhatsApp service (custom)
- AI Pipeline (Bun server on separate port)
- PDF generation (barryvdh/laravel-dompdf)

## Environment Variables

- `ADMIN_PREFIX`: Admin routes prefix (default: `gestao-makis`)
- `LOGIN_PREFIX`: Auth routes prefix (default: `acesso-secreto`)
- `AUTH_PEPPER`: Security pepper for password hashing
- `AI_PIPELINE_URL`: URL for AI microservice

## Blog-Specific Details

- Public routes: `/blog` (index), `/blog/{slug}` (show)
- Admin routes under `/gestao-makis`: `posts`, `categories`, `tags` (via resource controllers)
- Post validation:
  - Slug auto-generated from title if empty
  - Published posts require `published_at` (defaults to now)
  - SEO fields: `seo_title`, `seo_description` (optional)
- Admin PostController:
  - Lists all posts (latest first)
  - Create/Edit shows categories filtered by type 'post' or 'general'
  - Audit logging via `App\Services\AuditLogger`
- Migration notes:
  - `2026_04_18_134438_create_tags_table` adds `name`, `slug` (unique), `description`
  - `2026_04_18_134453_create_post_tag_table` pivot with foreign keys and unique composite index