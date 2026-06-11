# CI/CD Pipeline — Etuderapide

Este documento descreve o pipeline real de testes, análise e deploy do projeto.

## Visão Geral

```
┌──────────────────────────────────────────────────────────┐
│  1. QUALITY (toda push em main/develop e PR para main)   │
│     ├─ PHP 8.4 (Ubuntu) — SQLite in-memory               │
│     ├─ PHPUnit (105+ testes)                             │
│     ├─ Laravel Pint (--test, PSR-12)                     │
│     ├─ PHPStan nível 8 + baseline (Larastan)             │
│     └─ composer audit (vulnerabilidades)                 │
└──────────────────────────────────────────────────────────┘
                       │
                       ├─ ✅ tudo verde? (somente push na main)
                       │
┌──────────────────────────────────────────────────────────┐
│  2. DEPLOY (VPS com HestiaCP)                            │
│     └─ SSH com chave restrita executa o script           │
│        /home/damil/deploy-etude.sh no servidor:          │
│        git pull → composer → vite build → migrate →      │
│        caches (config/route/view/event)                  │
└──────────────────────────────────────────────────────────┘
```

## Arquivo de Workflow

Tudo vive em **`.github/workflows/ci.yml`** (dois jobs: `quality` e `deploy`).

- **Triggers**: `push` em `main`/`develop`; `pull_request` para `main`.
- **Job `quality`**: roda os mesmos comandos disponíveis localmente:
  - `vendor/bin/phpunit --no-coverage` (SQLite `:memory:`, igual ao `phpunit.xml`)
  - `vendor/bin/pint --test`
  - `vendor/bin/phpstan analyse --memory-limit=2G`
  - `composer audit`
- **Job `deploy`**: só roda em push na `main` e depois do `quality` passar.
  Conecta via SSH no VPS e executa o script de deploy.

## Deploy (VPS HestiaCP)

Produção roda num VPS Contabo (195.26.252.210) com HestiaCP:

- **Domínio**: `etuderapide.com` (usuário Hestia `damil`), PHP-FPM 8.4,
  template `PHP-8_4-LARAVEL` (open_basedir liberado para a raiz do domínio).
- **App**: `/home/damil/web/etuderapide.com/public_html` (docroot → `public/`).
- **Banco**: MariaDB local (`damil_etude`).
- **TLS**: Let's Encrypt emitido pelo Hestia (renovação automática).
- **Script de deploy**: `/home/damil/deploy-etude.sh` (versionado em
  `scripts/deploy-hestia.sh`) — git pull, composer, build do Vite,
  migrações e caches, com modo manutenção durante a troca.
- **Segurança da chave**: o `DEPLOY_SSH_KEY` do GitHub corresponde a uma
  chave em `~damil/.ssh/authorized_keys` com `command=` forçado — ela só
  consegue executar o script de deploy, nada mais.
- Setup inicial documentado em `scripts/setup-inicial-hestia.sh`.

> Os arquivos Docker (`Dockerfile.prod`, `docker-compose.yml`) continuam no
> repositório para uso futuro/local, mas o deploy de produção atual é o
> fluxo HestiaCP acima.

## Rodando o pipeline localmente

```bash
composer run test       # PHPUnit
composer run lint       # Pint
composer run analyse    # PHPStan (nível 8 + baseline)
composer audit          # vulnerabilidades de dependências
```

## PHPStan e baseline

- Configuração em `phpstan.neon` (Larastan, nível 8).
- Erros pré-existentes estão registrados em `phpstan-baseline.neon` (233 itens)
  para correção gradual. **Não adicione erros novos ao baseline** — corrija-os.
- Para regenerar o baseline (apenas quando reduzir a dívida):
  `vendor/bin/phpstan analyse --generate-baseline phpstan-baseline.neon`

## Segredos necessários (GitHub)

| Segredo          | Uso                                                   |
| ---------------- | ----------------------------------------------------- |
| `DEPLOY_SSH_KEY` | Chave privada SSH (restrita) que executa o deploy no VPS |

## Troubleshooting

- **CI falhou no Pint**: rode `composer run lint` localmente e commite o resultado.
- **CI falhou no PHPStan**: rode `composer run analyse`; corrija os erros novos.
- **Deploy não disparou**: verifique se o push foi na `main` e se o job `quality` passou.
- **Deploy falhou**: veja o log do job no GitHub Actions; no servidor, os logs
  ficam em `storage/logs/laravel.log` e `/var/log/apache2/domains/etuderapide.com.error.log`.
- **Site fora do ar após deploy**: `php8.4 artisan up` no diretório do app
  desativa o modo manutenção manualmente.
