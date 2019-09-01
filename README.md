# MicroBlogSymfony
A simple micro-blog.

Technical Kit:
+ Symfony;
+ Bootstrap;
+ Javascript;
+ Mysql;

Demo: http://136.243.142.144:8003

## Build Setup
For prod:
export APP_ENV=prod <- optional
composer install --no-dev --optimize-autoloader

Do not forget to change .env file.
If you want to load fixtures, you should execute the command:
php bin/console doctrine:fixtures:load