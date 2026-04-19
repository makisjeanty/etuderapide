# Makis Digital - Plataforma de Negócios 🚀

Plataforma premium desenvolvida em Laravel **11/13**, focada em alta performance, conversão de leads e **resiliência industrial**.

## 🛡️ Arquitetura de Resiliência (Anticorpos do Sistema)

Para evitar que falhas técnicas (como queda de banco de dados ou erro de drivers) derrubem o site para o cliente final, o sistema utiliza as seguintes travas:

1.  **Independência do Banco de Dados**: 
    *   `SESSION_DRIVER=file` e `CACHE_STORE=file` garantem que o "coração" do site inicie mesmo se o MySQL falhar.
2.  **Blindagem de Controllers**:
    *   Consultas dinâmicas (projetos, depoimentos, blog) são protegidas por blocos `try-catch`. Caso o banco não responda, o site exibe o conteúdo estático e oculta graciosamente os dados dinâmicos, mantendo o site online.
3.  **Monitoramento Crítico**: 
    *   Falhas de banco de dados são logadas com prioridade `CRITICAL` em `storage/logs/laravel.log`.

## ⚙️ Operação e Inicialização (Windows)

Para evitar erros de carregamento de extensões (como `could not find driver`), utilize sempre o script de inicialização customizado:

- **Iniciar Servidor**: Execute o arquivo `start_server.bat` na raiz do projeto. 
  - *Este script configura automaticamente o `PHPRC` e o `PATH` para garantir que o driver `pdo_mysql` seja carregado corretamente.*
- **Auditoria IA**: Certifique-se de que o microserviço em `pipeline/` esteja ativo para o funcionamento do Auditor de Negócios.

## 🔒 Segurança Avançada

O sistema segue os padrões "Ouro" de segurança:
- **Senhas**: Utiliza **Argon2id** com **Pepper** centralizado no `config/app.php`.
- **Cabeçalhos HTTP**: Middleware `SecurityHeaders` ativo com **CSP (Content Security Policy)** robusta para proteção contra XSS e injeção de scripts.
- **Admin Boundary**: Acesso restrito via `/gestao-makis` apenas para usuários com flag `is_admin` e e-mail verificado.

## 🛠️ Setup Rápido

```bash
composer install
npm install
php artisan key:generate
php artisan migrate --seed
npm run build
start_server.bat
```

## 📂 Estrutura de Domínios
O projeto utiliza o padrão PSR-4 sob o namespace `Modules\` para lógica de negócio complexa, mantendo os controladores Laravel focados apenas no fluxo de requisição.

---
**Makis Digital** - *Excelência em cada detalhe técnico.*
