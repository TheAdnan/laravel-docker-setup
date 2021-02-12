# Laravel docker setup
Basic Laravel docker setup with MySQL and Nginx

## Installation
Run the following command:

`docker-compose up -d --build`

After a successful build, run database migrations for Laravel using the following command:

`docker-compose exec php php /var/www/artisan migrate:fresh --seed`
