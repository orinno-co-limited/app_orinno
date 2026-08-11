# Agent Log

Running log of changes made to this repo by AI coding agents, and the testing
rules that go with them. **Read this before making changes. Update it every
time you commit.** The goal: no more CI failures that a 30-second local test
run would have caught.

## Before you commit — always run the test suite locally

CI (`.github/workflows/ci.yml`) runs `php artisan test` against a fresh
SQLite `:memory:` DB (see `phpunit.xml`) on **PHP 8.1**. Reproduce that
locally before pushing:

```
/c/php83/php artisan test
```

**Do not use the bare `php` command on this machine** — the `php` on PATH
resolves to PHP 8.0.30 (XAMPP's bundled version), which is too old for this
Laravel version (needs 8.1+) and fails immediately with a parse error in
`vendor/symfony/console`. The correct interpreter is `C:\php83\php.exe`.
Same applies to `composer` if it invokes PHP internally.

### Gotchas specific to this test suite

- Most feature tests use `RefreshDatabase` so each test gets a freshly
  migrated SQLite schema. **Any test class that talks to the DB (directly or
  indirectly, e.g. via global middleware) needs this trait**, or it'll hit
  "no such table" errors. `tests/Feature/ExampleTest.php` didn't need it
  historically because the request never reached the DB — see the
  2026-08-10 entry below for what happened when that assumption broke.
- `tests/TestCase.php` does not enable `RefreshDatabase` globally — it's
  opt-in per test class.
- GROUP_CONCAT-style raw SQL breaks on SQLite (CI) even though it works on
  MySQL (production) — write driver-agnostic queries or branch on
  `DB::connection()->getDriverName()`.

## Changelog

### 2026-08-10 — Fix CI regression from the installer-gate removal
- **Files:** `tests/Feature/ExampleTest.php`
- **What:** Enabled `RefreshDatabase` on `ExampleTest`.
- **Why:** Removing the `storage_path('installed')` gate in
  `CommonMiddleware` (see below) means *every* request now queries the
  `languages` table unconditionally. `ExampleTest` hits `/` without
  `RefreshDatabase`, so on CI's fresh SQLite `:memory:` DB the `languages`
  table doesn't exist yet → query blows up → test fails. Confirmed locally
  with `/c/php83/php artisan test` before pushing this fix.

### 2026-08-09/10 — Session-expiry (419) and dead installer-redirect (404) fixes
- **Files:** `app/Exceptions/Handler.php`, `app/Http/Middleware/CommonMiddleware.php`
- **What:**
  - `Handler::render()` now catches `TokenMismatchException` (expired
    CSRF/session) and redirects back with input preserved + a toast,
    instead of showing the bare "419 Page Expired" page.
  - `CommonMiddleware` no longer gates every request behind
    `file_exists(storage_path('installed'))` → `redirect('/install')`. That
    route was already removed during debranding (see memory:
    `app_orinno codebase origin`), so any environment missing the marker
    file (fresh local checkouts, future redeploys) hit a 404 dead end on
    every single page.
- **Why:** Non-technical users (Ugandan property owners/tenants, per user
  request) were hitting dead-end error pages with no way forward. Both were
  pure friction removal, not security changes — CSRF protection itself is
  still enforced, just recovered from gracefully.
- **Production side-effect (not a code change):** the same investigation
  found 6 pending migrations on the live DB (most importantly
  `create_amenities_table`), which were the actual cause of a 500 on the
  Properties page. Ran `php artisan migrate --force` directly on the host —
  this is a one-time operational fix, not reflected in any commit.
- **Deploy note:** `main` is branch-protected (PR + passing "test" check
  required, no direct push). Fixes were pushed to `testBrunch` and deployed
  to the live server directly from that branch's commit (host's git remote
  only auto-fetches `main`, so `git fetch origin testBrunch` was used
  explicitly). The `testBrunch` → `main` PR still needs to be opened/merged
  on GitHub: https://github.com/orinno-co-limited/app_orinno/compare/main...testBrunch
