# Dockerfile
FROM php:8.2-cli

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Установка PHP-расширений
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Копируем проект
COPY . .

# Устанавливаем зависимости
RUN composer install --optimize-autoloader --no-dev

# Создаём .env из .env.example
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Генерируем ключ
RUN php artisan key:generate

# Даём права на storage
RUN mkdir -p storage/logs storage/framework/{cache,sessions,views}
RUN chmod -R 775 storage bootstrap/cache

# Экспонируем порт (для ясности, но Railway сам задаст $PORT)
EXPOSE 8000

# Запускаем Laravel Development Server
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
