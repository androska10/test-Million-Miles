# Dockerfile для Railway (Laravel + Nginx + PHP-FPM)

FROM php:8.2-fpm

# Установка системных зависимостей
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    unzip \
    nginx \
    supervisor \
    && rm -rf /var/lib/apt/lists/*

# Установка PHP-расширений
RUN docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql mysqli zip opcache bcmath

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Копируем проект
COPY . .

# Устанавливаем зависимости (без dev)
RUN composer install --optimize-autoloader --no-dev

# Генерируем ключ Laravel
RUN php artisan key:generate

# Подготавливаем папки
RUN mkdir -p storage/logs storage/framework/{cache,sessions,views}
RUN chmod -R 775 storage bootstrap/cache

# Копируем конфиги
COPY nginx.conf /etc/nginx/sites-available/default
COPY supervisor.conf /etc/supervisor/conf.d/laravel.conf

# Порт
EXPOSE 80

# Запуск
CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]