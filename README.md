# Symfony_Ecommerce

- After cloning the repo, run :
composer install

- Set Up the Data Base: DATABASE_URL="mysql://[username]:[password]@127.0.0.1:8889/[table_name]?serverVersion=8.0.32&charset=utf8mb4"

- After that you have to do the migrations 
php bin/console doctrine:migrations:migrate

-php bin/console d:f:l --no-interaction
