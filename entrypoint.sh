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

# Aguardar o banco de dados estar disponível
if [ ! -z "$DB_HOST" ]; then
  echo "Aguardando o banco de dados..."
  until nc -z -v -w30 $DB_HOST 3306; do
    sleep 1
  done
fi

# Executar migrations do Laravel (para Sanctum)
if [ -f "artisan" ]; then
  php artisan migrate --force
fi

# Publicar configurações do Sanctum
if [ -f "artisan" ]; then
  # php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider" --force
fi

exec "$@"