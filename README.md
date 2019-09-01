# MicroBlogSymfony
A simple micro-blog.

Technical Kit:
+ Symfony;
+ Bootstrap;
+ Javascript;
+ Mysql;

Demo: http://136.243.142.144:8003

## Build Setup
For prod:<br/>
export APP_ENV=prod <- optional<br/>
composer install --no-dev --optimize-autoloader<br/>

Do not forget to change .env file.<br/>
If you want to load fixtures, you should execute the command:<br/>
php bin/console doctrine:fixtures:load