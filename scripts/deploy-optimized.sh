#!/bin/bash

set -euo pipefail

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}🚀 Iniciando Deploy do Etuderapide...${NC}"

# Função para verificar comando
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Verificar dependências
if ! command_exists docker; then
    echo -e "${RED}❌ Docker não está instalado${NC}"
    exit 1
fi

if ! command_exists docker-compose; then
    echo -e "${RED}❌ Docker Compose não está instalado${NC}"
    exit 1
fi

# 1. Configurar ambiente de produção
if [ ! -f ".env.production" ]; then
    echo -e "${YELLOW}⚠️  Arquivo .env.production não encontrado. Criando...${NC}"
    cp .env.example .env.production
fi

# 2. Validar configurações críticas
echo -e "${YELLOW}🔍 Validando configurações de produção...${NC}"

if grep -q '^DB_PASSWORD=change-this-database-password$' .env.production; then
    echo -e "${RED}❌ Configure DB_PASSWORD no arquivo .env.production antes do deploy.${NC}"
    exit 1
fi

if grep -q '^AUTH_PEPPER=change-this-long-random-pepper$' .env.production; then
    echo -e "${GREEN}🔐 Gerando AUTH_PEPPER único...${NC}"
    pepper="$(openssl rand -hex 32)"
    sed -i "s/^AUTH_PEPPER=.*/AUTH_PEPPER=${pepper}/" .env.production
fi

# 3. Backup do banco de dados (se existir)
if docker-compose ps | grep -q "etuderapide-db.*Up"; then
    echo -e "${GREEN}💾 Criando backup do banco de dados...${NC}"
    backup_name="backup_$(date +%Y%m%d_%H%M%S).sql"
    docker-compose exec -T db pg_dump -U etuderapide etuderapide > "backups/${backup_name}" || true
fi

# 4. Build das imagens
echo -e "${GREEN}🏗️  Buildando imagens Docker...${NC}"
docker-compose -f docker-compose.yml build --no-cache

# 5. Gerar APP_KEY se necessário
if grep -q '^APP_KEY=$' .env.production; then
    echo -e "${GREEN}🔑 Gerando APP_KEY único...${NC}"
    app_key=$(docker-compose -f docker-compose.yml run --rm --no-deps app php artisan key:generate --show)
    sed -i "s|^APP_KEY=.*|APP_KEY=${app_key}|" .env.production
fi

# 6. Deploy com zero downtime
echo -e "${GREEN}🚀 Iniciando deploy com zero downtime...${NC}"

# Parar containers antigos com timeout
echo -e "${YELLOW}⏹️  Parando containers antigos...${NC}"
docker-compose -f docker-compose.yml down --timeout 30 || true

# Iniciar novos containers
echo -e "${GREEN}▶️  Iniciando novos containers...${NC}"
docker-compose -f docker-compose.yml up -d

# 7. Aguardar saúde dos serviços
echo -e "${YELLOW}⏳ Aguardando serviços ficarem saudáveis...${NC}"
sleep 10

# 8. Executar migrações
echo -e "${GREEN}🔄 Executando migrações de banco de dados...${NC}"
docker-compose -f docker-compose.yml exec -T app php artisan migrate --force

# 9. Limpar cache e otimizar
echo -e "${GREEN}🧹 Otimizando aplicação...${NC}"
docker-compose -f docker-compose.yml exec -T app php artisan config:cache
docker-compose -f docker-compose.yml exec -T app php artisan route:cache
docker-compose -f docker-compose.yml exec -T app php artisan view:cache

# 10. Verificar saúde final
echo -e "${YELLOW}🏥 Verificando saúde dos serviços...${NC}"
if docker-compose -f docker-compose.yml ps | grep -q "Up (healthy)"; then
    echo -e "${GREEN}✅ Todos os serviços estão saudáveis!${NC}"
else
    echo -e "${RED}⚠️  Alguns serviços não estão saudáveis. Verifique os logs.${NC}"
    docker-compose -f docker-compose.yml logs --tail=50
fi

# 11. Limpar imagens antigas
echo -e "${GREEN}🧹 Limpando imagens Docker antigas...${NC}"
docker image prune -f

echo -e "${GREEN}🎉 Deploy concluído com sucesso!${NC}"
echo -e "${GREEN}📊 Aplicação disponível em: https://etuderapide.com${NC}"
echo -e "${GREEN}🔧 Admin: /gestao-makis${NC}"

# Mostrar status final
docker-compose -f docker-compose.yml ps
