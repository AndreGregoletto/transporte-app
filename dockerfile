FROM php:8.3-fpm

# Instalar dependências do sistema
RUN apt-get update --fix-missing && apt-get install -y --no-install-recommends \
    zip unzip git curl libzip-dev libxml2-dev libgd-dev libonig-dev \
    pkg-config build-essential && \
    docker-php-ext-install pdo_mysql zip mbstring xml gd && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Definir diretório de trabalho
WORKDIR /var/www

# Copiar composer.json e composer.lock do subdiretório laravel
COPY laravel/composer.json laravel/composer.lock* ./

# Copiar o restante do projeto Laravel
COPY laravel/ .

# Ajustar permissões
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

CMD ["php-fpm"]