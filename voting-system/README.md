# NACOS Vote

A full-stack award voting platform built with Laravel 12, MySQL, Blade, TailwindCSS, Alpine.js, and Paystack integration. It supports public voting, real-time leaderboards, and an admin console for managing categories, candidates, and transactions.

## Features

### Public
- Home page with countdown and featured categories
- Browse categories and candidates
- Vote with a dynamic price calculator
- Pay via Paystack or bank transfer
- Real-time leaderboard (auto-refresh)
- Dark mode, search, social sharing

### Admin Dashboard
- Stats overview with charts (Chart.js)
- Category and candidate management
- Transaction management with filters
- Approve/reject bank transfer payments
- Settings for vote price, voting toggle, event date, and bank details
- Export transactions to CSV

### Security
- Paystack webhook HMAC SHA-512 signature validation
- Backend-only payment verification
- Transaction idempotency and database locking
- CSRF, XSS, and SQL injection protection
- Admin middleware authorization

## Tech Stack

- Laravel 12
- MySQL
- Blade + TailwindCSS
- Alpine.js
- Vite
- Paystack (optional: Korapay support is available in config)

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8+

## Setup

1. Install dependencies:
	- `composer install`
	- `npm install`
2. Copy `.env.example` to `.env` and update database and payment keys.
3. Generate app key: `php artisan key:generate`
4. Create the database (default name: `nacovote`).
5. Run migrations and seeders: `php artisan migrate --seed`
6. Link storage: `php artisan storage:link`
7. Build assets: `npm run build`

## Environment Variables

Start from `.env.example` and set the required keys below.

### Application (required)
- `APP_NAME`
- `APP_ENV`
- `APP_KEY`
- `APP_URL`

### Database (required)
- `DB_CONNECTION` (set to `mysql` for MySQL)
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

### Payments (required for live voting)
- Paystack:
	- `PAYSTACK_PUBLIC_KEY`
	- `PAYSTACK_SECRET_KEY`
	- `PAYSTACK_MERCHANT_EMAIL`
	- `PAYSTACK_CALLBACK_URL`

### Payments (optional)
- `PAYSTACK_PAYMENT_URL`
- Korapay (if enabled):
	- `KORAPAY_PUBLIC_KEY`
	- `KORAPAY_SECRET_KEY`
	- `KORAPAY_PAYMENT_URL`
	- `KORAPAY_CALLBACK_URL`

### Local Development

- Start the app: `php artisan serve`
- Start the frontend dev server: `npm run dev`

## Default Admin Account

- Email: `admin@nacovote.com`
- Password: `password`

## Configuration Notes

- Vote price is configurable in the Admin Settings page. Default is ₦50 per vote.
- Payment keys and webhook secrets are read from `.env`.
- Bank transfer approvals are handled in the admin panel.

