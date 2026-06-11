# Contributing to Etuderapide

Thank you for your interest in contributing to Etuderapide! This document outlines our standards and processes.

## Code Standards

### PHP Code Style

- **Standard**: Laravel Pint (PSR-12 based)
- **Run locally**: `./vendor/bin/pint`
- **Check only**: `./vendor/bin/pint --test`

### Type Hints

- Use strict type declarations in all new files
- Declare return types for all methods
- Use nullable types where appropriate
- Example:
    ```php
    public function getUserPosts(?int $userId): Collection
    ```

### Documentation

- Add PHPDoc blocks for complex methods
- Keep it simple; let type hints do the work
- Example:
    ```php
    /** Check if user can manage posts with audit logging */
    public function canManagePosts(): bool
    ```

## Testing Requirements

### Running Tests

```bash
# Local testing (SQLite)
composer run test

# Docker testing (PostgreSQL)
docker-compose -f docker-compose.test.yml up --build

# With coverage report
vendor/bin/phpunit --coverage-text
```

### Coverage Standards

- Maintain **90%+ code coverage** on all new code
- Critical paths must have feature tests
- API endpoints require request/response tests

### Test Organization

- Feature tests in `tests/Feature/`
- Unit tests in `tests/Unit/`
- Use descriptive test names: `it_validates_user_email()` not `test_email()`

## API Development Guidelines

### Response Format

All API responses follow this structure:

```json
{
    "data": {},
    "meta": {
        "pagination": {},
        "sort_by": "created_at",
        "sort_direction": "desc"
    }
}
```

### Error Handling

```json
{
    "message": "Validation failed",
    "errors": {
        "email": ["The email field is required."]
    }
}
```

### Versioning

- Current API version: v1
- Prefix routes: `/api/v1/posts`
- Maintain backward compatibility

## Database Migrations

### Standards

- Use descriptive names: `create_posts_table`, `add_featured_image_to_posts`
- Always write down migrations
- Test migrations fresh: `php artisan migrate:fresh`

### Naming

```php
// Good
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
});

// Avoid
Schema::create('t1', function (Blueprint $table) {
    // ...
});
```

## Git Workflow

### Branch Naming

- Feature: `feature/add-blog-tags`
- Bugfix: `bugfix/email-validation`
- Hotfix: `hotfix/security-issue`
- Docs: `docs/update-readme`

### Commit Messages

```
feat: add blog tag system
^    ^
|    └─ Lowercase subject
└─ Type: feat, fix, docs, style, refactor, test, chore

fix: handle null category in post controller

Fixes #123. Adds null checking in PostIndexController
to prevent 500 errors when category is deleted.
```

### Pull Requests

- **Title**: `feat: add two-factor authentication`
- **Description**: Include:
    - What changed and why
    - How to test
    - Related issues (#123)
    - Screenshots if UI changes
- **Reviews**: Wait for 1+ approvals
- **Tests**: All CI checks must pass

## CI/CD Pipeline

### Automated Checks

1. **PHPUnit** - 91+ tests must pass
2. **Laravel Pint** - Code style validation
3. **PHPStan** - Static analysis (level 8)
4. **Codecov** - Code coverage tracking (90%+ required)
5. **Composer Audit** - Dependency security scan

### Local Validation

Before pushing, run locally:

```bash
./vendor/bin/pint --test        # Check style
./vendor/bin/phpstan analyse    # Static analysis
composer run test               # Run tests
```

## Security

### Sensitive Data

- Never commit `.env` files
- Never commit API keys or passwords
- Use environment variables for secrets
- Reference: `.env.example`

### Security Headers

- All responses include CSP headers
- HSTS enabled on HTTPS
- Security headers middleware active in production

### Password Hashing

- Use `hash_password()` helper (Argon2id + Pepper)
- Never store plain text passwords
- Use `check_password()` for verification

## Performance Considerations

### Query Optimization

- Use `with()` for eager loading
- Avoid N+1 queries
- Use query scopes for common filters
- Profile with `Debugbar` in local

### Caching

- Cache frequently accessed data
- Invalidate cache on updates
- Use appropriate TTLs

### API Response Times

- Target: < 200ms for list endpoints
- Target: < 100ms for show endpoints
- Monitor with GitHub Actions logs

## Documentation

### Code Comments

- Avoid obvious comments
- Explain the "why", not the "what"
- Document business logic clearly
- Example:

    ```php
    // Good: Explains why
    // Prevent duplicate audit entries by grouping within 5 minutes
    if ($lastAudit?->diffInMinutes(now()) < 5) {
        return;
    }

    // Avoid: Explains what (obvious from code)
    // Get the user's email
    $email = $user->email;
    ```

### README Sections

- Add new features to README
- Keep AGENTS.md updated
- Document breaking changes

## Questions or Issues?

- **Bugs**: Open an issue with reproduction steps
- **Features**: Discuss in issues before coding
- **Questions**: Check AGENTS.md first

---

**Happy coding!** 🚀 Your contributions make Etuderapide better.
