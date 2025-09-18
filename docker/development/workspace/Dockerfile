# docker/development/workspace/Dockerfile
FROM php:8.4-cli

ARG UID=1000
ARG GID=1000
ARG NODE_VERSION=20

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    vim \
    libpng-dev \
    libonig-dev \
    libzip-dev \
    default-mysql-client \   
    postgresql-client \
    && docker-php-ext-install pdo_mysql zip \
    && apt-get autoremove -y \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установка Node.js v20 напрямую (без nvm)
ENV NODE_VERSION=20
RUN curl -fsSL https://deb.nodesource.com/setup_${NODE_VERSION}.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest \
    && rm -rf /var/lib/apt/lists/*

# Создание пользователя
RUN if getent group ${GID}; then \
      useradd -m -u ${UID} -g ${GID} -s /bin/bash www; \
    else \
      groupadd -g ${GID} www && \
      useradd -m -u ${UID} -g www -s /bin/bash www; \
    fi && \
    usermod -aG sudo www && \
    echo 'www ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers

USER www
WORKDIR /var/www

# Автозагрузка NVM
RUN echo 'export NVM_DIR="/home/www/.nvm"' >> /home/www/.bashrc && \
    echo '[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"' >> /home/www/.bashrc && \
    echo '[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"' >> /home/www/.bashrc

ENTRYPOINT []
CMD ["bash"]