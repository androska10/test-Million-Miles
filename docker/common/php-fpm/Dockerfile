# docker/common/php-fpm/Dockerfile
# Это базовый образ, который можно использовать и в dev, и в prod
FROM php:8.4-fpm

# Установка системных зависимостей
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    curl \
    git \
    unzip \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql mysqli zip opcache bcmath

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www