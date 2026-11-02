# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

A Laravel 10 (PHP ^8.1) school project: a session-authenticated admin panel for managing **infoproneurs** (profiles with photo), their **projets**, and **domaines** (categories), plus a dashboard and a user profile page. UI text, validation messages and flash messages are in French (`APP_LOCALE` is `fr`, messages in `lang/fr/validation.php`); some code comments are in Arabic. Amounts are shown in DH.

## Commands

```bash
composer install
cp .env.example .env && php artisan key:generate   # MySQL by default
php artisan migrate
php artisan db:seed            # optional demo data + admin@infopreneurs.test / password
php artisan serve              # http://127.0.0.1:8000

php artisan test                          # all tests (in-memory SQLite, never touches MySQL)
php artisan test --filter=InfoproneurTest # single test class / method
./vendor/bin/pint                         # code style
```

There is no registration page: create users via the seeder or `php artisan tinker` (`User::create([...])` — the `password` cast hashes plain text).

## Architecture

- **Routes** (`routes/web.php`): public landing page at `/` (`home` route, `LandingController` → `landing.blade.php`, standalone page with `lp-*` styles in `app.css`), `guest` group for login, everything else inside one `auth` group. `Route::resource` for `infoproneurs`, `projets`, `domaines` (domaines has no create/show page — the create form is on the index). Dashboard is `/index` (`dashboard` route, `RouteServiceProvider::HOME`).
- **Data model**: `Domaine` 1–n `Infoproneur` 1–n `Projet`; `Projet` also has an optional `domaine_id`. Deleting an infoproneur cascades to its projets (FK) and deletes its photo file; deleting a domaine nulls `domaine_id` on both via a `deleting` hook in `Domaine::booted()` (needed because SQLite ignores FKs added with `ALTER TABLE`). Project statuses live in `Projet::STATUTS`; `progression` is computed from status and dates.
- **Validation**: `app/Http/Requests/{Infoproneur,Projet,Domaine}Request.php`, shared by store/update.
- **Uploads**: stored on the `public` disk (`photos/`, `avatars/`) and served through `MediaController` (`route('media', $path)`), not via `storage:link` — the symlink doesn't work inside OneDrive. Use the `photo_url` / `avatar_url` accessors in views.
- **Views**: all pages extend `resources/views/layouts/app.blade.php` (sidebar with counts from a view composer in `AppServiceProvider`, toasts for `session('success'|'error')`, a shared delete-confirm modal). Reusable pieces are anonymous components in `resources/views/components` (`x-icon`, `x-avatar`, `x-domaine-badge`, `x-status-badge`, `x-empty-state`, `x-field-error`); create/edit share an `_form` partial per resource. Pagination uses `resources/views/pagination/default.blade.php`.
- **Front-end**: plain `public/css/app.css` and `public/js/app.js`, no build step (Vite is configured but unused; `node_modules` isn't installed). JS behaviours are driven by data attributes: `form[data-confirm]`, `[data-upload]`, `[data-color-field]`, `form[data-auto-submit]`, `[data-password-toggle]`, `[data-search]`.
- **Tests**: feature tests in `tests/Feature`. The GD extension isn't installed, so use `$this->fakeImage()` (in `tests/TestCase.php`) instead of `UploadedFile::fake()->image()`.
- Leftover, unused files: `resources/views/{content,content2,test,welcome}.blade.php`, `resources/css/styles.css`, `app/Http/Controllers/EFM.php`.

## Repository note

The git repository root is the user's home directory (`C:/Users/HP`), not this project folder. Do not run broad git commands (`git add -A`, `git status` without a path, etc.) — scope any git operation to this directory, and confirm with the user before committing.
