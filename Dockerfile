# Start from an official PHP image (we use 8.2-apache for compatibility)
FROM php:8.2-apache

# Copy your application code to the Apache document root
COPY . /var/www/html

# Enable necessary Apache rewrite rules for clean URLs (optional, but often needed)
RUN a2enmod rewrite

# Update package lists and install any required PHP extensions (e.g., for database connection)
# If you use a database, you might need: pdo, pdo_mysql, pdo_pgsql, etc.
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install dependencies (only if you have a composer.json)
# If you have a composer.json, uncomment the next two lines:
# WORKDIR /var/www/html
# RUN composer install --no-dev --optimize-autoloader

# Expose the port (Apache usually runs on 80)
EXPOSE 80