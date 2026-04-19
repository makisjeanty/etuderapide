#!/bin/bash

echo "🚀 Iniciando Deploy do Etuderapide..."

# 1. Limpeza
echo "🧹 Limpando ambiente..."
docker stop $(docker ps -aq) 2>/dev/null
docker rm $(docker ps -aq) 2>/dev/null
docker system prune -a --volumes -f

# 2. Clone/Update do Código
if [ -d "etuderapide" ]; then
    echo "📂 Atualizando código..."
    cd etuderapide
    git pull origin main
else
    echo "📥 Clonando repositório..."
    git clone https://github.com/makisjeanty/etuderapide.git
    cd etuderapide
fi

# 3. Configuração do .env (Se não existir)
if [ ! -f ".env" ]; then
    echo "⚙️ Configurando .env..."
    cp .env.example .env
    # Aqui você deve editar o .env com os dados de produção
fi

# 4. Build e Up
echo "🏗️ Subindo containers..."
docker-compose up -d --build

echo "✅ Deploy concluído!"
