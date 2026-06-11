# Security Best Practices Report

## Executive Summary

Static review of the Laravel application and the TypeScript AI pipeline found four concrete security issues worth prioritizing. The most important problems are on the API side: privileged API access can be obtained without the web admin 2FA step, and Sanctum token "abilities" are issued but not actually enforced on admin endpoints. I also found a publicly served upload path that preserves the client-supplied file extension, and personal access tokens appear to be non-expiring by default.

This review was code-based only. I did not perform live exploitation, infrastructure validation, or runtime header inspection.

## Critical / High

### [ER-SEC-001] High: Privileged API login bypasses the admin 2FA requirement

**Impact:** Anyone who obtains a privileged user's password can authenticate through the API and perform admin actions without completing the 2FA step enforced on the web admin panel.

**Evidence**

- `bootstrap/app.php:25-29` applies the `two_factor` middleware only to the web admin route group:

```php
Route::middleware(['web', 'auth', 'verified', 'admin', 'two_factor'])
    ->prefix(config('app.admin_prefix', 'gestao-makis'))
    ->name('admin.')
    ->group(base_path('routes/admin.php'));
```

- `app/Http/Controllers/Api/Auth/TokenController.php:14-65` issues bearer tokens after only email/password validation:

```php
public function store(Request $request): JsonResponse
{
    $validated = $request->validate([
        'email' => ['required', 'string', 'email'],
        'password' => ['required', 'string'],
```

```php
    $token = $user->createToken(
        $validated['device_name'] ?: 'api-token',
        $abilities
    );
```

- `routes/api.php:67-100` places admin API routes behind `auth:sanctum`, but not behind any second-factor requirement.
- Representative destructive admin endpoints only check verified email + role/permission, for example `app/Http/Controllers/Api/Admin/LeadDestroyController.php:12-21` and `app/Http/Controllers/Api/Admin/PostDestroyController.php:13-28`.

**Why this matters**

The project documentation explicitly treats 2FA as part of the admin protection chain, but the API creates an alternate single-factor authentication path for the same privileged operations.

**Recommended Fix**

- Do not allow `/api/login` to mint privileged tokens from password-only authentication.
- For users with admin or management permissions, require a completed second factor before token issuance.
- If machine/API access is required, create a separate explicitly managed PAT flow for admins, with out-of-band approval and narrower scopes.
- Add tests proving that privileged users cannot obtain admin-capable API access before satisfying the second factor.

### [ER-SEC-002] High: Sanctum token abilities are issued and documented, but not enforced

**Impact:** A token intentionally created with reduced abilities such as `["profile:read"]` can still call admin endpoints as long as the underlying user account has the matching role/permission.

**Evidence**

- The application computes scoped abilities in `app/Models/User.php:70-98`.
- Scoped abilities are accepted and attached to tokens in `app/Http/Controllers/Api/Auth/TokenController.php:47-57` and `app/Http/Controllers/Api/TokenManagementController.php:25-31`.

```php
$abilities = $this->resolveAbilities($user, $validated['abilities'] ?? null);
$token = $user->createToken(
    $validated['device_name'] ?: 'api-token',
    $abilities
);
```

- The docs promise scoped token behavior in `docs/api.md:67-72` and `docs/api.md:209-211`.
- Admin endpoints do not check `tokenCan(...)` or use Sanctum `abilities` middleware. Representative examples:
  - `app/Http/Controllers/Api/Admin/LeadIndexController.php:12-17`
  - `app/Http/Controllers/Api/Admin/ServiceStoreController.php:13-23`
  - `app/Http/Controllers/Api/Admin/PostDestroyController.php:13-18`

```php
abort_unless(
    $request->user()?->canManageLeads() && $request->user()?->hasVerifiedEmail(),
    403
);
```

**Why this matters**

Right now, token abilities are effectively metadata. They do not reduce the authority of the token, which defeats least privilege and makes automation tokens much riskier than the API contract suggests.

**Recommended Fix**

- Enforce token abilities on every authenticated API route, preferably at the route layer with Sanctum `abilities:` / `ability:` middleware.
- Keep the existing user/role checks, but add token checks as a second gate.
- Add tests that prove a token with only `profile:read` cannot reach `admin/*` endpoints and that write routes require the matching `*:manage` ability.

### [ER-SEC-003] High: Media upload stores files under a public web path using the client-supplied extension

**Impact:** A user who can reach the admin upload endpoint can publish files under `/storage/uploads/...` with an attacker-controlled extension, creating a content-type/extension confusion risk and increasing the chance of serving active content from your own origin.

**Evidence**

- `app/Http/Controllers/Admin/MediaController.php:27-33` uses `getClientOriginalExtension()` and stores directly on the `public` disk:

```php
$extension = $file->getClientOriginalExtension();
$filename = (string) Str::uuid().'.'.$extension;
$path = $file->storeAs('uploads', $filename, 'public');
$url = Storage::disk('public')->url($path);
```

- `config/filesystems.php:41-45` maps the `public` disk to `storage/app/public` and exposes it under `/storage`.
- `config/filesystems.php:76-77` defines the public symlink:

```php
'links' => [
    public_path('storage') => storage_path('app/public'),
],
```

**Why this matters**

Laravel's validation here checks that the uploaded content looks like an image, but the saved extension still comes from the client. That is not a safe invariant. Extension trust is especially risky when the file is then published from the same origin and later referenced back into pages or rich content.

**Recommended Fix**

- Derive the saved extension from trusted server-side detection (`$file->extension()` / MIME-based mapping), not `getClientOriginalExtension()`.
- Consider re-encoding uploaded images before storage so the saved bytes are guaranteed to match the declared type.
- Prefer storing uploads outside the public web root and serving them through a controller if they are not meant to be executable web content.
- Add tests covering mismatched original extensions.

## Medium

### [ER-SEC-004] Medium: Personal access tokens appear to be non-expiring

**Impact:** A stolen bearer token can remain valid indefinitely unless someone notices and revokes it manually.

**Evidence**

- `app/Http/Controllers/Api/Auth/TokenController.php:49-52` and `app/Http/Controllers/Api/TokenManagementController.php:25-26` call `createToken(...)` without an expiry argument.
- The project does not contain a `config/sanctum.php` override.
- The installed package default in `vendor/laravel/sanctum/config/sanctum.php:41-50` is:

```php
'expiration' => null,
```

**Why this matters**

Long-lived bearer tokens materially increase blast radius for phishing, laptop compromise, CI leakage, or accidental token exposure. In this codebase that risk is compounded by the privileged API findings above.

**Recommended Fix**

- Publish Sanctum config and set a bounded expiration appropriate to the use case.
- Consider passing an explicit `expiresAt` when creating tokens for interactive logins.
- Add token creation notifications and a simple review/revocation workflow for old tokens.

## Notes / Assumptions

- I did not verify runtime edge configuration such as reverse-proxy headers, TLS termination, or whether additional protections are enforced outside the repo.
- I did not treat missing TLS/HSTS deployment settings as findings.
- The AI pipeline appears to be internal in `docker-compose.yml`; I did not elevate it to a finding because the current repo does not show it being published directly to the internet.
