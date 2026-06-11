#!/bin/bash
# Setup inicial do etuderapide no VPS HestiaCP (rodar como root, uma única vez).
set -euo pipefail

APP_DIR="/home/damil/web/etuderapide.com/public_html"
DBPASS=$(cat /root/.etude_dbpass)
PEPPER=$(openssl rand -hex 32)

echo "==> Clonando repositório como damil"
sudo -u damil bash -c "cd '$APP_DIR' && find . -mindepth 1 -delete && git clone --branch main https://github.com/makisjeanty/etuderapide.git ."

echo "==> Gerando .env de produção"
sudo -u damil bash -c "cd '$APP_DIR' && cp .env.example .env"
cd "$APP_DIR"
sudo -u damil sed -i \
    -e 's|^APP_NAME=.*|APP_NAME="Makis Digital"|' \
    -e 's|^APP_ENV=.*|APP_ENV=production|' \
    -e 's|^APP_DEBUG=.*|APP_DEBUG=false|' \
    -e 's|^APP_URL=.*|APP_URL=https://etuderapide.com|' \
    -e 's|^APP_LOCALE=.*|APP_LOCALE=pt_BR|' \
    -e 's|^TRUSTED_PROXIES=.*|TRUSTED_PROXIES=127.0.0.1,::1,195.26.252.210|' \
    -e "s|^AUTH_PEPPER=.*|AUTH_PEPPER=$PEPPER|" \
    -e 's|^DB_CONNECTION=.*|DB_CONNECTION=mysql|' \
    -e 's|^DB_HOST=.*|DB_HOST=localhost|' \
    -e 's|^DB_PORT=.*|DB_PORT=3306|' \
    -e 's|^DB_DATABASE=.*|DB_DATABASE=damil_etude|' \
    -e 's|^DB_USERNAME=.*|DB_USERNAME=damil_etude|' \
    -e "s|^DB_PASSWORD=.*|DB_PASSWORD=$DBPASS|" \
    -e 's|^SESSION_DRIVER=.*|SESSION_DRIVER=file|' \
    -e 's|^CACHE_STORE=.*|CACHE_STORE=file|' \
    -e 's|^QUEUE_CONNECTION=.*|QUEUE_CONNECTION=sync|' \
    -e 's|^MAIL_MAILER=.*|MAIL_MAILER=sendmail|' \
    -e 's|^MAIL_FROM_ADDRESS=.*|MAIL_FROM_ADDRESS="no-reply@etuderapide.com"|' \
    -e 's|^LOG_LEVEL=.*|LOG_LEVEL=warning|' \
    .env
chmod 640 .env

echo "==> Dependências PHP (necessárias para o artisan)"
sudo -u damil php8.4 /usr/local/bin/composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-progress

echo "==> APP_KEY"
sudo -u damil php8.4 artisan key:generate --force

echo "==> Setup inicial concluído. Rode agora: sudo -u damil /home/damil/deploy-etude.sh"
