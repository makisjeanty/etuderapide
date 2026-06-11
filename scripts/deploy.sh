#!/bin/bash

set -euo pipefail

echo "🚀 Iniciando Deploy do Etuderapide..."

# 1. Clone/Update do Código
if [ -d "etuderapide" ]; then
    echo "📂 Atualizando código..."
    cd etuderapide
    git pull origin main
else
    echo "📥 Clonando repositório..."
    git clone https://github.com/makisjeanty/etuderapide.git
    cd etuderapide
fi

# 2. Configuração do .env (Se não existir)
if [ ! -f ".env" ]; then
    echo "⚙️ Configurando .env..."
    cp .env.production.example .env
    # Aqui você deve editar o .env com os dados de produção
fi

if grep -q '^DB_PASSWORD=change-this-database-password$' .env; then
    echo "❌ Configure DB_PASSWORD no arquivo .env antes do deploy."
    exit 1
fi

if grep -q '^AUTH_PEPPER=change-this-long-random-pepper$' .env; then
    echo "🔐 Gerando AUTH_PEPPER..."
    pepper="$(openssl rand -hex 32)"
    sed -i "s/^AUTH_PEPPER=.*/AUTH_PEPPER=${pepper}/" .env
fi

# 3. Build
echo "🏗️ Buildando imagens..."
docker compose build

if grep -q '^APP_KEY=$' .env; then
    echo "🔑 Gerando APP_KEY..."
    docker compose run --rm --no-deps --entrypoint php app artisan key:generate --force
fi

# 4. Up
echo "🚀 Subindo containers..."
docker compose up -d

echo "🧪 Validando configuração do container app..."
docker compose exec -T app php -m | grep -E "pdo_pgsql|pgsql" >/dev/null

echo "✅ Deploy concluído!"
