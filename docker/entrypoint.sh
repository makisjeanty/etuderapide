#!/bin/sh
# Entrypoint script para o container Laravel
# Executado ANTES do php-fpm iniciar

set -e

echo "==> Garantindo link do storage..."
php artisan storage:link || true

echo "==> Rodando migrações..."
php artisan migrate --force

echo "==> Otimizando cache do Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Iniciando PHP-FPM..."
exec php-fpm
