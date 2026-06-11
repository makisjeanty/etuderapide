#!/bin/bash

# Script de Otimização para Produção - Etuderapide

echo "🚀 Otimizando Etuderapide para produção..."

# 1. Limpar caches antigos
echo "🧹 Limpando caches..."
php artisan config:clear
php artisan route:clear  
php artisan view:clear
php artisan cache:clear
php artisan event:clear

# 2. Cache de configurações
echo "⚙️  Criando cache de configurações..."
php artisan config:cache

# 3. Cache de rotas
echo "🛣️  Criando cache de rotas..."
php artisan route:cache

# 4. Cache de views
echo "👁️  Criando cache de views..."
php artisan view:cache

# 5. Cache de eventos
echo "📅 Criando cache de eventos..."
php artisan event:cache

# 6. Otimizar composer
echo "📦 Otimizando autoloader..."
composer dump-autoload --optimize --no-dev --classmap-authoritative

# 7. Configurar permissões
echo "🔐 Configurando permissões..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 8. Link simbólico storage
echo "🔗 Criando link simbólico do storage..."
php artisan storage:link

# 9. Limpar logs antigos
echo "🗑️  Limpando logs antigos..."
find storage/logs -name "*.log" -mtime +7 -delete 2>/dev/null || true

# 10. Verificar saúde
echo "🏥 Verificando saúde da aplicação..."
php artisan route:list | head -5

echo "✅ Otimização concluída!"
echo "📊 Aplicação pronta para produção!"