# Aster Atelier

Vietnamese home-goods storefront: product catalog, session cart, checkout for guests or registered customers, product admin.

Demo: https://shop.larping.dpdns.org — admin login `demo@example.com` / `password` (read-only: can browse the admin and check out, cannot modify data).

## Stack

Laravel 13, PHP 8.3+, Tailwind CSS 4, Vite, SQLite. Product images are served from jsDelivr, pinned to the `v1.0.0` tag.

## Run locally

```bash
composer install
cp .env.example .env && php artisan key:generate
php artisan migrate --force
php artisan db:seed   # 16 products + demo admin
npm install && npm run build
php artisan serve
```

## Tests

```bash
php artisan test   # 47 tests, 146 assertions
```

## Layout

Domain logic lives in small modules, not controllers:

- `app/Products/ProductDraft` — admin form parsing (gallery/highlights/specs), slug collisions
- `app/Orders/CartStore` — session cart, per-item cap of 8
- `app/Orders/CartSnapshot` — immutable lines/subtotal/shipping/total
- `app/Orders/OrderIntake` — snapshot + customer details into a persisted order
- `app/Orders/OrderAccess` — order visibility (owner, placing session, or order number + email)
- `app/Admin/StoreSummary` — dashboard metrics
- `app/Support/Money` — VND formatting

Cart mutations (add/update/remove) are AJAX with a plain-form fallback; the `admin` middleware is the single gate for the admin area and enforces the read-only demo rule.

## Deploy

PHP 8.3 + php-fpm behind Caddy. `git pull`, `composer install --no-dev`, `npm ci && npm run build`, `php artisan migrate --force`, cache config/routes/views. `Dockerfile`/`docker-compose.yml` are a containerized alternative.
