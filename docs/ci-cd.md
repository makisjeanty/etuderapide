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
│  2. DEPLOY                                               │
│     └─ Gatilho HTTP para o Coolify, que faz o build      │
│        (Dockerfile.prod) e o deploy no servidor          │
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
  Dispara o webhook de deploy do Coolify (`secrets.COOLIFY_TOKEN`).

## Deploy (Coolify)

O servidor de produção é gerenciado pelo Coolify, que:

1. Recebe o webhook do GitHub Actions.
2. Faz build da imagem com `Dockerfile.prod` (php-fpm 8.3 Alpine, nginx, supervisor).
3. O entrypoint (`docker/entrypoint.sh`) roda `migrate --force` e os caches
   (`config:cache`, `route:cache`, `view:cache`).

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

| Segredo         | Uso                          |
| --------------- | ---------------------------- |
| `COOLIFY_TOKEN` | Autenticação do webhook de deploy |

## Troubleshooting

- **CI falhou no Pint**: rode `composer run lint` localmente e commite o resultado.
- **CI falhou no PHPStan**: rode `composer run analyse`; corrija os erros novos.
- **Deploy não disparou**: verifique se o push foi na `main` e se o job `quality` passou.
- **Deploy disparou mas o site não atualizou**: confira os logs do Coolify no painel.
