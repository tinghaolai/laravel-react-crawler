## Installation (non-docker ver.)

### Built

Create `.env` file.

Run commands

```bash
npm update --legacy-peer-deps
composer install
php artisan optimize
php artisan storage:link
php artisan migrate:install
php artisan migrate
```

### Run

```shell
php artisan serve
npm run dev
```
