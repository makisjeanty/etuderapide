#!/bin/bash
# Deploy do etuderapide no HestiaCP (roda como usuário damil no VPS).
# Primeiro deploy: configura .env, banco e build. Deploys seguintes: git pull + rebuild.
set -euo pipefail

APP_DIR="/home/damil/web/etuderapide.com/public_html"
PHP="php8.4"
REPO="https://github.com/makisjeanty/etuderapide.git"

echo "==> Deploy etuderapide ($(date))"

if [ ! -d "$APP_DIR/.git" ]; then
    echo "==> Primeiro deploy: clonando repositório"
    cd "$APP_DIR"
    find . -mindepth 1 -delete
    git clone --branch main "$REPO" .
else
    echo "==> Atualizando código"
    cd "$APP_DIR"
    git fetch origin main
    git reset --hard origin/main
fi

cd "$APP_DIR"

echo "==> Dependências PHP"
$PHP /usr/local/bin/composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-progress

if [ ! -f .env ]; then
    echo "ERRO: .env não existe. Configure-o antes do primeiro deploy." >&2
    exit 1
fi

echo "==> Build de assets"
npm ci --ignore-scripts --no-audit --no-fund
npm run build

echo "==> Manutenção ligada"
$PHP artisan down --retry=30 || true

echo "==> Migrações"
$PHP artisan migrate --force

echo "==> Caches"
$PHP artisan config:cache
$PHP artisan route:cache
$PHP artisan view:cache
$PHP artisan event:cache
$PHP artisan storage:link || true

echo "==> Manutenção desligada"
$PHP artisan up

echo "==> Deploy concluído com sucesso"
