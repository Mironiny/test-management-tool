#!/bin/bash

cd src
# Install dependencies only for Docker.
[[ ! -e /.dockerenv ]] && exit 0
set -xe

# Update packages and install composer and PHP dependencies.
apt-get update -yqq
apt-get install -yq git libcurl4-gnutls-dev libicu-dev libmcrypt-dev libvpx-dev libjpeg-dev libpng-dev libxpm-dev zlib1g-dev libfreetype6-dev libxml2-dev libexpat1-dev libbz2-dev libgmp3-dev libldap2-dev unixodbc-dev libpq-dev libsqlite3-dev libaspell-dev libsnmp-dev libpcre3-dev libtidy-dev -yqq

# Compile PHP, include these extensions.
docker-php-ext-install mbstring mcrypt pdo_mysql curl json intl gd xml zip bz2 opcache

# Install Composer and project dependencies.
curl -sS https://getcomposer.org/installer | php
php composer.phar install

# Copy over testing configuration.
cp .env.testing .env

chmod -R o+w storage/

# Generate an application key. Re-cache.
php artisan key:generate
php artisan config:cache

# Run database migrations.
#php artisan migrate

php artisan config:clear

php artisan cache:clear

php artisan view:clear

# Run database seeder
#php artisan db:seed --class=UserTableSeeder
