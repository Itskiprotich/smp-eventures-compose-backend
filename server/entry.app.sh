#!/bin/bash

set -e

env

if [[ -n "$1" ]]; then
    exec "$@"
else
    composer install
    wait-for-it db:3306 -t 45
    php artisan migrate:refresh 
    php artisan key:generate 
    chown -R www-data:www-data storage
    exec apache2-foreground
fi
