# Stage 1: Build environment for PHP-FPM and dependencies
FROM php:8.2-fpm-alpine AS builder
RUN apk update && apk add --no-cache git unzip libpq postgresql-dev bash
RUN docker-php-ext-install pdo_pgsql
WORKDIR /var/www/html
COPY . /var/www/html
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoload --no-interaction

# Stage 2: Final image with Nginx and PHP-FPM files
FROM nginx:alpine
RUN apk update && apk add --no-cache bash
COPY ./nginx/default.conf /etc/nginx/conf.d/default.conf
COPY --from=builder /var/www/html /var/www/html
COPY --from=builder /usr/local/sbin/php-fpm /usr/local/sbin/php-fpm
WORKDIR /var/www/html
EXPOSE 80
CMD sh -c "php-fpm -D && nginx -g 'daemon off;'"