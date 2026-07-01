# Invoice & Budget Management System

Production-focused Laravel application for managing customers, suppliers, products, purchase invoices, sales invoices, payments, budgets, expenses, reports, and live currency exchange rates.

## Tech Stack

- Laravel
- PHP 8.3+
- MySQL
- Bootstrap 5 CDN
- Blade Templates
- jQuery
- Chart.js
- Laravel HTTP Client
- DomPDF
- Laravel Excel

## Features

- Admin authentication with login, logout, forgot password, reset password, profile update, and password change
- Admin dashboard with financial cards, charts, and currency widget
- Customer, supplier, and product management
- Purchase invoice and sales invoice management with dynamic line items
- Payment management and payment history
- Budget and expense tracking with live consumption calculations
- Financial reports with PDF and Excel export
- Responsive admin layout with sidebar, header, breadcrumbs, toasts, and loading spinner

## Installation

1. Clone the project into your local web root.
2. Run composer install:

```bash
composer install
```

3. Copy the environment file:

```bash
copy .env.example .env
```

4. Generate the application key:

```bash
php artisan key:generate
```

5. Create the database `management_pro` in MySQL or phpMyAdmin.
6. Update `.env` with your database and mail settings.
7. Run migrations and seeders:

```bash
php artisan migrate --seed
```

## Environment Setup

Recommended `.env` values:

```env
APP_NAME="Invoice & Budget Management System"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost
APP_TIMEZONE=Asia/Kolkata

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=management_pro
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
MAIL_MAILER=log

CURRENCY_API_BASE_URL=https://api.frankfurter.app
CURRENCY_API_TARGET=INR
CURRENCY_API_CACHE_MINUTES=60
```

## Database Setup

Create a MySQL database named `management_pro`, then run:

```bash
php artisan migrate
php artisan db:seed
```

## Seeder Commands

Run the full seed set:

```bash
php artisan db:seed
```

Run a specific seeder:

```bash
php artisan db:seed --class=AdminUserSeeder
php artisan db:seed --class=CustomerSeeder
php artisan db:seed --class=SupplierSeeder
php artisan db:seed --class=ProductSeeder
```

## Run Commands

```bash
php artisan serve
```

Then open:

```text
http://localhost:8000
```

## Default Admin Login

- Email: `admin@managementpro.test`
- Password: `password`

## Notes

- Bootstrap 5 is loaded from CDN only.
- Sidebar markup lives in `resources/views/layouts/partials/leftmenu.blade.php`.
- Top header with logout lives in `resources/views/layouts/partials/header.blade.php`.
- Currency rates are fetched with Laravel HTTP Client from the configured API.

