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

## Technology Used 

- PHP ~~7.1.3~~ =>  8.2.7
- Laravel Framework ~~5.8.*~~ =>  10.13.5
- MySQL 8
- Laravel Sail with Docker 

## Prerequisite
- Docker
- WSL2 (Window)
- PHP
- Composer

## Installation

1. Copy .env.example to .env <br>
2. Update DB_USERNAME and DB_PASSWORD as desired.
3. In command prompt, execute the following.

``` 
composer install
docker comopse up
```

4. In docker container,
```
npm install
php artisan migrate
```
5. Access [http://localhost](http://localhost) and click "GENERATE APP KEY" button if you got "Your app key is missing" error.
## Setup
You need to set up the following before creating invoice.
- Category
- District
- Product
- Customer
## Upcoming

- Separate web and api server
- Redesign user interface
- Build frontend with React

## Possible Enhancement

- Inventory Management
- Account Management
- Accounting System
- HR System
- Support Multi-languages


## Author

The system is developed by Amber Wing Yan Lau.
