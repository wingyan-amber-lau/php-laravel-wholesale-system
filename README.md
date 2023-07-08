# php-laravel-wholesale-system
### An Upgraded version from [https://github.com/wingyan-amber-lau/Wholesale_System](https://github.com/wingyan-amber-lau/Wholesale_System)

Wholesale System is a web application that provide various management functions for wholesale operations as follows:  

- Product Management
- Order Management
- Invoice Management
- Customer Management
- Supplier Management
- Delivery Management
- District Management
- Monthly Settlement 

The system provided with database migration script. The database schema is reusable.

This is the backend server built using PHP, Laravel providing REST API service and the APIs are secured by OAuth2.0 using Laravel Passport.

The frontend built with Blade is obsoleted. You may download the release v1.0.0 for blade frontend.

Please look forward to the new web user interface.

## Technology Used 

- PHP ~~7.1.3~~ =>  8.2.7
- Laravel Framework ~~5.8.*~~ =>  10.13.5
- MySQL 8
- Laravel Sail with Docker 
- Laravel Passport

## Prerequisite
- Docker
- WSL2 (Window)
- PHP
- Composer

## Installation

1. Copy <code>.env.example</code> to <code>.env</code>
2. Update DB_USERNAME and DB_PASSWORD as desired.
3. In command prompt, execute the following.

``` 
composer install
docker compose up
```

4. In docker container,
```
npm install
php artisan migrate
php artisan key:generate
```

5. Generate passport keys
```
php artisan passport:keys
```

6. Paste the key in <code>storage/oauth-public.key</code> to <b>PASSPORT_PUBLIC_KEY</b> in <code>.env</code> file; and the key in <code>storage/oauth-private.key</code> to <b>PASSPORT_PRIVATE_KEY</b> in <code>.env</code> file

## Setup
You need to set up the following before creating invoice.
- Category
- District
- Product
- Customer
## Upcoming

- [x] API server
- [ ] Redesign user interface
- [ ] Build frontend with React

## Possible Enhancement

- Inventory Management
- Account Management
- Accounting System
- HR System
- Support Multi-languages


## Author

The system is developed by Amber Wing Yan Lau.
