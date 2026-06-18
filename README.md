# Website Yayasan Pendidikan Daarul Hikmah Al Madani

Website publik portal yayasan dengan admin panel.

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan storage:link
php artisan migrate:fresh --seed
npm install && npm run build
php artisan serve
```

Login admin: `admin@example.com` / `password`

## Pengembangan

```bash
composer dev   # server + queue + logs + vite
```
