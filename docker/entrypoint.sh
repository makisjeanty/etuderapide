#!/bin/sh
# Entrypoint script para o container Laravel
# Executado ANTES do php-fpm iniciar

set -e

echo "==> Otimizando cache do Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Rodando migrações..."
php artisan migrate --force

echo "==> Iniciando PHP-FPM..."
exec php-fpm
