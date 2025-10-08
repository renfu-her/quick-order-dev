# Quick Order

Quick Order is a full-stack food ordering platform built with Laravel 11 and Filament v4. It provides a production-ready administration panel for managing inventory, coupons, ads, and orders, alongside a responsive customer storefront that supports carts, promotions, and a complete checkout flow.

## Features
- **Admin (Filament v4):** CRUD for products, ingredients, coupons, ads, and orders with scheduling, status workflows, bulk actions, and image galleries.
- **Customer storefront:** Product catalogue, detail pages with image galleries, configurable temperature options, cart management, coupon redemption, and checkout with order confirmation.
- **Seeded sample data:** Demo products, ads, coupons, and an administrator account ready to use after the first seed run.
- **Media handling:** Multiple product images, storage symlink support, and Intervention Image for server-side processing.
- **Extensible foundation:** Modular resource structure, queue-ready configuration, database-backed sessions and cache.

## Tech Stack
- Laravel 11 (PHP 8.2+)
- Filament 4 for the admin UI
- MySQL or SQLite (default) for persistence
- Blade views with Vite + Tailwind CSS 4
- Intervention Image for media processing

## Prerequisites
- PHP 8.2 or newer with `BCMath`, `Ctype`, `Fileinfo`, `JSON`, `Mbstring`, `OpenSSL`, `PDO`, and `Tokenizer` enabled
- Composer
- Node.js 18+ and npm
- MySQL 8 (recommended) or SQLite

## Installation

1. **Clone and enter the project**
   ```bash
   git clone <repository-url>
   cd quick-order-dev
   ```

2. **Copy the environment file**
   ```bash
   cp .env.example .env   # use copy .env.example .env on Windows PowerShell
   ```

3. **Configure the database**
   - For MySQL, update `DB_*` variables in `.env` and create the database (e.g. `CREATE DATABASE quick_order CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;`).
   - For a quick start, you can leave the default SQLite configuration; Laravel will create `database/database.sqlite` automatically.

4. **Install PHP dependencies**
   ```bash
   composer install
   ```

5. **Install frontend dependencies**
   ```bash
   npm install
   ```

6. **Generate the application key**
   ```bash
   php artisan key:generate
   ```

7. **Run migrations with seed data**
   ```bash
   php artisan migrate --seed
   ```

8. **Create the storage symlink**
   ```bash
   php artisan storage:link
   ```

9. **(Optional) Run the helper script**
   - macOS/Linux: `./setup.sh`
   - Windows: `setup.bat`
   These scripts automate the steps above for a default MySQL setup.

## Running the Application

- **Laravel development server**
  ```bash
  php artisan serve
  ```
  Available at `http://localhost:8000`.

- **Vite asset watcher**
  ```bash
  npm run dev
  ```

- **All-in-one development workflow**
  ```bash
  composer dev
  ```
  Spawns the Laravel server, queue listener, live logs, and Vite dev server via `concurrently`.

## Production Build
- Compile assets: `npm run build`
- Cache configuration (recommended): `php artisan config:cache && php artisan route:cache`
- Queue worker (if using queues): `php artisan queue:work`
- Remember to run `php artisan migrate --force` on deploy.

## Default Accounts & Sample Data
- Admin panel: `http://localhost:8000/admin`
  - Email: `admin@quickorder.com`
  - Password: `password`
- Coupon codes available after seeding: `WELCOME10`, `SAVE5`, `SUMMER20`, `FREESHIP`
- Eight demo products with images, categories, and ingredients are created by the seeders.

## Testing
```bash
php artisan test
```
`composer test` clears cached configuration before running the test suite.

## Troubleshooting
- **Images do not load:** Ensure `php artisan storage:link` has been executed and the `storage/` directory is writable.
- **Database connection errors:** Verify `.env` credentials and confirm the database server is running and accessible.
- **Admin login issues:** Regenerate an admin user with `php artisan make:filament-user`.
- **Stale configuration:** Clear caches via `php artisan cache:clear`, `php artisan config:clear`, `php artisan route:clear`, `php artisan view:clear`.

## Project Structure Highlights
```
app/
 └─ Filament/Resources/
     ├─ Products/    # Product CRUD, forms, tables, pages
     ├─ Ads/         # Advertisement management
     ├─ Orders/      # Order listing, infolists, status management
     └─ Coupons/     # Coupon CRUD with dynamic forms
database/
 ├─ migrations/      # Schema migrations
 └─ seeders/         # Sample data seeders
resources/
 ├─ views/           # Blade templates for frontend pages
 └─ css/js/          # Vite entry points and Tailwind setup
```

Happy hacking! 🍔
