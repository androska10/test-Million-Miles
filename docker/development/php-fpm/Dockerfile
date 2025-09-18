# docker/development/php-fpm/Dockerfile
# Прямо расширяет официальный PHP-FPM, добавляя Xdebug и настройки для разработки
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

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Временно отключаем Xdebug — проблема с PECL и PHP 8.4
# RUN pecl channel-update pecl.php.net && \
#     pecl install xdebug && \
#     docker-php-ext-enable xdebug

# Создаём заглушку, чтобы не ломать конфигурацию
RUN echo "Xdebug временно отключён" > /usr/local/etc/php/conf.d/xdebug-disabled.ini


# Настройка Xdebug через аргументы
ARG XDEBUG_ENABLED=true
ARG XDEBUG_MODE=develop,debug
ARG XDEBUG_HOST=host.docker.internal

RUN if [ "${XDEBUG_ENABLED}" = "true" ]; then \
    echo "xdebug.mode=${XDEBUG_MODE}" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_host=${XDEBUG_HOST}" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.idekey=DOCKER" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini ; \
    fi

# Аргументы для синхронизации пользователя
ARG UID=1000
ARG GID=1000

# Создание пользователя
RUN if getent group ${GID}; then \
      group_name=$(getent group ${GID} | cut -d: -f1); \
      useradd -m -u ${UID} -g ${GID} -s /bin/bash www; \
    else \
      groupadd -g ${GID} www && \
      useradd -m -u ${UID} -g www -s /bin/bash www; \
      group_name=www; \
    fi

# Настройка PHP-FPM под нового пользователя
RUN sed -i "s/user = www-data/user = www/g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i "s/group = www-data/group = $group_name/g" /usr/local/etc/php-fpm.d/www.conf

WORKDIR /var/www

USER www

EXPOSE 9000
CMD ["php-fpm"]