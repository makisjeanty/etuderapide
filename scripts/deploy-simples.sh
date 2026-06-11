#!/bin/bash

# 🚀 Script de Deploy Simplificado - Etuderapide
# Apenas execute e coloque suas credenciais quando solicitado!

echo "🚀 Bem-vindo ao Deploy Simplificado do Etuderapide!"
echo "📋 Este script vai configurar tudo para você."
echo ""

# Função para ler input do usuário
read_input() {
    local prompt="$1"
    local default="$2"
    local input
    
    if [ -n "$default" ]; then
        echo -n "$prompt (padrão: $default): "
    else
        echo -n "$prompt: "
    fi
    
    read input
    
    if [ -z "$input" ] && [ -n "$default" ]; then
        echo "$default"
    else
        echo "$input"
    fi
}

# Verificar se Docker está instalado
if ! command -v docker &> /dev/null; then
    echo "❌ Docker não está instalado. Por favor, instale o Docker primeiro."
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose não está instalado. Por favor, instale o Docker Compose primeiro."
    exit 1
fi

echo "✅ Docker e Docker Compose detectados!"
echo ""

# Criar arquivo .env com base no template simples
echo "📄 Criando arquivo de configuração (.env)..."
cp .env.simples .env

# Perguntar credenciais ao usuário
echo ""
echo "🔧 Vamos configurar as credenciais necessárias:"
echo ""

# Senha do banco de dados
DB_PASSWORD=$(read_input "🔑 Digite a senha do banco de dados PostgreSQL" "etuderapide2026")
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env

# Email
MAIL_USERNAME=$(read_input "📧 Digite seu email para envio de notificações" "seu_email@gmail.com")
sed -i "s/MAIL_USERNAME=.*/MAIL_USERNAME=$MAIL_USERNAME/" .env

MAIL_PASSWORD=$(read_input "🔐 Digite a senha do email (use senha de app se for Gmail)" "sua_senha")
sed -i "s/MAIL_PASSWORD=.*/MAIL_PASSWORD=$MAIL_PASSWORD/" .env

# Domínio
APP_URL=$(read_input "🌐 Digite a URL do seu domínio" "https://seu_dominio.com")
sed -i "s|APP_URL=.*|APP_URL=$APP_URL|" .env

# Telegram (opcional)
echo ""
echo "📱 Configuração do Telegram (opcional - para notificações):"
TELEGRAM_BOT_TOKEN=$(read_input "Bot Token do Telegram (ou deixe vazio para pular)" "")
if [ -n "$TELEGRAM_BOT_TOKEN" ]; then
    sed -i "s/TELEGRAM_BOT_TOKEN=.*/TELEGRAM_BOT_TOKEN=$TELEGRAM_BOT_TOKEN/" .env
    
    TELEGRAM_CHAT_ID=$(read_input "Chat ID do Telegram" "")
    sed -i "s/TELEGRAM_CHAT_ID=.*/TELEGRAM_CHAT_ID=$TELEGRAM_CHAT_ID/" .env
fi

# Gerar AUTH_PEPPER automaticamente
echo ""
echo "🔐 Gerando chave de segurança (AUTH_PEPPER)..."
AUTH_PEPPER=$(openssl rand -hex 32)
sed -i "s/AUTH_PEPPER=.*/AUTH_PEPPER=$AUTH_PEPPER/" .env

# Gerar APP_KEY automaticamente
echo "🔑 Gerando chave da aplicação (APP_KEY)..."
APP_KEY=$("C:\xampp\php\php.exe" artisan key:generate --show 2>/dev/null || php artisan key:generate --show)
sed -i "s|APP_KEY=.*|APP_KEY=$APP_KEY|" .env

# Criar diretório de backups
mkdir -p backups

echo ""
echo "✅ Configurações salvas com sucesso!"
echo ""
echo "📋 Resumo da configuração:"
echo "  • Banco de Dados: Configurado"
echo "  • Email: $MAIL_USERNAME"
echo "  • Domínio: $APP_URL"
echo "  • Segurança: Chaves geradas automaticamente"
echo ""

# Perguntar se quer iniciar o deploy agora
echo -n "🚀 Deseja iniciar o deploy agora? (s/n): "
read resposta

if [[ "$resposta" =~ ^[Ss]$ ]]; then
    echo ""
    echo "🏗️  Iniciando deploy..."
    
    # Executar o deploy otimizado
    if [ -f "deploy-optimized.sh" ]; then
        chmod +x deploy-optimized.sh
        ./deploy-optimized.sh
    else
        echo "❌ Arquivo deploy-optimized.sh não encontrado."
        echo "Execute manualmente: docker-compose up -d"
    fi
else
    echo ""
    echo "✅ Configuração concluída!"
    echo "📌 Para fazer o deploy depois, execute:"
    echo "   chmod +x deploy-optimized.sh"
    echo "   ./deploy-optimized.sh"
    echo ""
    echo "📖 Ou simplesmente: docker-compose up -d"
fi

echo ""
echo "🎉 Tudo pronto! Seu Etuderapide está configurado para deploy!"