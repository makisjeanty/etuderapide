# 🚀 Deploy Simplificado - Etuderapide

## 📋 Passo a Passo (Apenas 3 passos!)

### 1️⃣ Execute o script de configuração
```bash
chmod +x scripts/deploy-simples.sh
./scripts/deploy-simples.sh
```

### 2️⃣ Digite suas credenciais quando solicitado
O script vai pedir apenas 3 informações:
- 🔑 **Senha do banco de dados** PostgreSQL
- 📧 **Email e senha** para notificações
- 🌐 **URL do seu domínio**

### 3️⃣ Aguarde o deploy automaticamente
O script vai:
- ✅ Configurar todas as variáveis de ambiente
- ✅ Gerar chaves de segurança automaticamente
- ✅ Subir todos os containers Docker
- ✅ Executar migrações e otimizações

---

## 🔧 Alternativa Manual (se preferir)

Se quiser configurar manualmente, edite o arquivo `.env` com suas credenciais:

```bash
# Copie o template
cp .env.simples .env

# Edite as credenciais
nano .env  # ou use seu editor preferido

# Execute o deploy
docker-compose up -d
```

---

## 📊 Status do Deploy

Após a instalação, você pode verificar:

```bash
# Verificar se todos os containers estão rodando
docker-compose ps

# Ver logs se houver problemas
docker-compose logs

# Acessar a aplicação
open https://seu-dominio.com
```

---

## 🆘 Problemas Comuns

### Docker não está instalado?
Instale seguindo: https://docs.docker.com/get-docker/

### Porta 80 já está em uso?
Edite `docker-compose.yml` e mude a porta:
```yaml
ports:
  - "8080:80"  # Use 8080 ou outra porta disponível
```

### Erro de permissão?
Execute: `sudo chmod +x scripts/deploy-simples.sh`

---

## 🎯 Pronto! 

**Seu Etuderapide está no ar!** 🎉

- **Painel Admin**: `https://seu-dominio.com/gestao-makis`
- **API**: `https://seu-dominio.com/api/`
- **Blog**: `https://seu-dominio.com/blog`

Precisa de ajuda? O sistema está configurado para ser super simples!