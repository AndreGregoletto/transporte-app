# Base image PHP com FPM
FROM php:8.2-fpm

# Instalar dependências do sistema
RUN apt-get update --fix-missing && apt-get install -y --no-install-recommends \
    zip unzip git curl libzip-dev libxml2-dev libgd-dev libonig-dev \
    pkg-config build-essential && \
    docker-php-ext-install pdo_mysql zip mbstring xml gd && \
    apt-get clean

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Adicionar script de entrada customizado
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /var/www

RUN if [ -f "artisan" ]; then php artisan key:generate; fi

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
