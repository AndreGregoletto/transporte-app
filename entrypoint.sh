#!/bin/bash

cd /var/www

# Copia .env.example caso .env não exista
if [ ! -f .env ] && [ -f .env.example ]; then
  cp .env.example .env
fi

# Instala dependências se a pasta vendor não existir
if [ ! -d vendor ]; then
  echo "🔧 Instalando dependências do projeto..."
  composer install --optimize-autoloader --no-dev

  # Instala pacotes adicionais necessários
  composer require maatwebsite/excel:^3.1
  composer require laravel/sanctum
fi

# Gera a chave da aplicação
if [ -f ".env" ] && ! grep -q "APP_KEY=" .env; then
  php artisan key:generate
fi

exec "$@"
