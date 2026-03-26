SET UP

https://github.com/EmeterioF/select_2.git
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
composer run dev
