# Built With
- PHP/Laravel
- RESTful API
- MySql
- jQuery
# Getting started
## Requirements
- PHP 8.1 or higher
- Laravel Framework 10.x
## Installation
1. Install Composer
```
composer install
```
2. Clone the repo and `cd` into it
```
git clone https://github.com/vuonghuynh202/weather_app_laravel.git
```
3. Import the database file from the folder `SQL File`
4. Rename or copy `.env.example` file to `.env`
5. Set your database credentials in your `.env` file
```
DB_DATABASE= your db name here
DB_USERNAME= your db username
DB_PASSWORD= your password
```
6. Generate application key
```
php artisan key:generate
```
7. Start running
```
php artisan serve
```


