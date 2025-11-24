<!-- Copilot instructions tailored for the Kemetic Laravel app -->
# Quick Orientation

- **Project type:** Laravel 9 PHP app (requires PHP >= 8.1). See `composer.json` for packages.
- **Frontend:** Vite + Tailwind; see `package.json` scripts (`dev` / `build`).
- **Asset builder:** `npm run dev` (vite) and `npm run build` for production.
- **Helpful composer scripts:** `composer run setup` (installs deps, prepares .env, runs migrations and build) and `composer run dev` (starts server + queue + pail + vite via `concurrently`).

# Architecture & Key Areas

- **HTTP entrypoints:** `routes/web.php` (web UI) and `routes/api.php` (+ many include files under `routes/api/*`). Many API route groups include files using `base_path('routes/api/*.php')` — search the `routes/` folder to find route groupings.
- **Controllers:** `app/Http/Controllers/` organized by `Admin/`, `Api/`, `Auth/`, `Panel/`, `Web/`. Controller strings are frequently used in routes (e.g. `'Web\\HomeController@index'`) rather than `::class`; prefer following the existing style when adding routes.
- **Views:** `resources/views/web/default/` holds the main frontend blade templates. Follow existing blade partials and `share` middleware conventions.
- **Config & providers:** `config/app.php` loads numerous third-party providers (payments, Zoom, Firebase, Minio storage). Expect many external integrations; check `config/` files for provider-specific env variables.
- **Autoloaded helpers:** `composer.json` autoloads files: `app/Helpers/helper.php`, `app/Mixins/Geo/Geo.php`, `app/Helpers/ApiHelper.php`. Use these helpers rather than duplicating logic.

# Conventions & Patterns (project-specific)

- Routes often use grouped middleware like: `['middleware' => ['check_mobile_app','share','check_maintenance','check_restriction']]`. Preserve these middleware orders when adding routes.
- Authentication middleware names: `web.auth`, `api.auth` and custom level access `api.level-access:teacher` appear in `routes/api.php`.
- Payment and gateway patterns: many gateways are wired via dedicated controllers and package service providers (see `config/app.php` and `composer.json`). When adding payment flows, follow existing controllers under `App\\Http\\Controllers\\Api\\Panel` or `PaymentsController` patterns.
- Files and downloads are often served from controller methods like `WebinarController@downloadFile` or `ProductOrderController@downloadPdf`. Reuse existing response helpers and policies.

# Build / Test / Debug workflows

- Local dev (recommended):
  - Install deps: `composer install` and `npm install`
  - Copy `.env.example` -> `.env` and generate key: `php artisan key:generate`
  - Run migrations (if needed): `php artisan migrate`
  - Start frontend + backend (same as `composer run dev`): `npx concurrently "php artisan serve" "php artisan queue:listen --tries=1" "php artisan pail --timeout=0" "npm run dev"`
- Tests: `composer test` or `php artisan test` (see `composer.json` script `test`).
- Asset build: `npm run build` (Vite production build).

# Dangerous/Important Quick Notes

- There is an exposed emergency route: `GET /emergencyDatabaseUpdate` in `routes/web.php` that runs `artisan migrate --force` and `db:seed`. Avoid calling it on production and remove or protect it if deploying.
- The `dev` composer script runs `php artisan pail` — this appears to be a custom command; search `app/Console/Commands` if you need to debug it.

# Search tips & examples

- Find where API route groups are defined: search for `base_path('routes/api'` to find included route files.
- To locate a UI view for a controller action, search for the controller name in `resources/views` and `routes/web.php`. Example: `HomeController@index` -> `resources/views/web/default/home.blade.php` (follow existing structure under `resources/views/web/default`).

# Environment & Integration keys

- Expect many env variables for payment gateways, OpenAI, Firebase, Zoom, MinIO, and mail. Check `config/` for exact variable names (e.g., `config/openai.php`, `config/filesystems.php`).
- OpenAI: the project includes `openai-php/laravel` in `composer.json` — set `OPENAI_API_KEY` in `.env` and check `config/openai.php`.

# When submitting changes (code style)

- Follow existing Laravel structure; avoid changing route style (string vs ::class) inconsistently.
- Keep modifications small and reference the related controller and view files in the PR description.

If anything here is unclear or you'd like me to include additional file examples (specific controllers, route include files, or provider env keys), tell me which area and I will refine the instructions.
