# API Vehicle

[documentation](http://127.0.0.1:8000/api/documentation)

Build:
```
composer install
touch database/database.sqlite
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan test

php artisan serve
php artisan l5-swagger:generate
```
