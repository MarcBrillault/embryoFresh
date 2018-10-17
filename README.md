# embryoFresh
A fresh installation of Laravel+ Backpack with default capabilities.

## Installation

1. Clone or download the repo
2. Install dependencies : `composer install`
3. ENV management
    1. Copy the `.env.example` file to `.env`
    2. `php artisan key:generate`
    3. Adapt the database information in the file
3. Install backpack's dependencies, including AdminLTE public dir
    1. `php artisan backpack:base:install`
    2. `php artisan backpack:crud:install`
