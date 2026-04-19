# API

API HTTP baseada em `Sanctum` para integrações internas, painéis headless e automações.

Especificação OpenAPI básica: [openapi.yaml](./openapi.yaml)
Collection Postman: [postman_collection.json](./postman_collection.json)
SDK TypeScript simples: [sdk/etuderapide-api-client.ts](./sdk/etuderapide-api-client.ts)

## Versionamento

`/api/v1` é a superfície principal a partir de agora.

Os endpoints legados sem versão continuam disponíveis por compatibilidade, mas a documentação e os novos consumidores devem usar `v1`.

## Autenticação

`POST /api/v1/login`

Payload:

```json
{
  "email": "admin@example.com",
  "password": "password",
  "device_name": "backoffice-script",
  "abilities": ["profile:read", "dashboard:read"]
}
```

Resposta:

```json
{
  "token": "1|plain-text-token",
  "token_type": "Bearer",
  "abilities": ["profile:read", "dashboard:read", "leads:manage"],
  "user": {
    "id": 1,
    "name": "Admin",
    "email": "admin@example.com",
    "is_admin": true,
    "email_verified": true
  }
}
```

Use o token retornado no header:

```http
Authorization: Bearer 1|plain-text-token
```

## Endpoints autenticados

`GET /api/v1/me`

- Retorna o usuário autenticado, roles e abilities do token atual.

`POST /api/v1/logout`

- Revoga o token atual.

`GET /api/v1/tokens`

- Lista os tokens do usuário autenticado.

`POST /api/v1/tokens`

- Cria um novo token nomeado para o usuário autenticado.
- Aceita `name` e `abilities[]`.
- As abilities solicitadas precisam ser subconjunto das abilities que o usuário realmente possui.

`DELETE /api/v1/tokens/{id}`

- Revoga um token do próprio usuário.

## Endpoints admin

Todos os endpoints abaixo exigem `auth:sanctum`, usuário verificado e a permissão correspondente.

`GET /api/v1/admin/summary`

- Requer `view-dashboard` ou acesso admin.
- Retorna contadores resumidos de conteúdo e leads.

`GET /api/v1/admin/leads?per_page=10&status=read&service_interest=SEO&search=cliente&created_from=2026-04-01&created_to=2026-04-30&sort_by=quoted_value&sort_direction=desc`

- Requer `manage-leads`.
- Retorna leads paginados com filtros por `status`, `service_interest`, `search`, intervalo de datas e ordenação.

`GET /api/v1/admin/leads/{lead}`

- Requer `manage-leads`.
- Retorna o lead completo.

`PATCH /api/v1/admin/leads/{lead}`

- Requer `manage-leads`.
- Atualiza status e campos comerciais do lead.

`DELETE /api/v1/admin/leads/{lead}`

- Requer `manage-leads`.
- Remove o lead.

`GET /api/v1/admin/posts?per_page=10&search=seo&category_id=3&is_published=1&created_from=2026-04-01&published_from=2026-04-10&sort_by=published_at&sort_direction=desc`

- Requer `manage-posts`.
- Retorna posts paginados com filtros por `search`, `category_id`, `is_published`, intervalo de datas e ordenação.

`POST /api/v1/admin/posts`

- Requer `manage-posts`.
- Cria um post.

`GET /api/v1/admin/posts/{post}`

- Requer `manage-posts`.
- Retorna o post completo, incluindo `body`.

`PATCH /api/v1/admin/posts/{post}`

- Requer `manage-posts`.
- Atualiza um post.

`DELETE /api/v1/admin/posts/{post}`

- Requer `manage-posts`.
- Remove um post.

`GET /api/v1/admin/projects?per_page=10&search=crm&category_id=2&status=published&is_featured=1&created_from=2026-04-01&finished_to=2026-04-30&sort_by=updated_at&sort_direction=desc`

- Requer `manage-projects`.
- Retorna projetos paginados com filtros por `search`, `category_id`, `status`, `is_featured`, intervalo de datas e ordenação.

`POST /api/v1/admin/projects`

- Requer `manage-projects`.
- Cria um projeto.

`GET /api/v1/admin/projects/{project}`

- Requer `manage-projects`.
- Retorna o projeto completo, incluindo `description`.

`PATCH /api/v1/admin/projects/{project}`

- Requer `manage-projects`.
- Atualiza um projeto.

`DELETE /api/v1/admin/projects/{project}`

- Requer `manage-projects`.
- Remove um projeto.

`GET /api/v1/admin/services?per_page=10&search=landing&category_id=4&is_active=1&created_from=2026-04-01&sort_by=name&sort_direction=asc`

- Requer `manage-services`.
- Retorna serviços paginados com filtros por `search`, `category_id`, `is_active`, intervalo de datas e ordenação.

`POST /api/v1/admin/services`

- Requer `manage-services`.
- Cria um serviço.

`GET /api/v1/admin/services/{service}`

- Requer `manage-services`.
- Retorna o serviço completo, incluindo `full_description`.

`PATCH /api/v1/admin/services/{service}`

- Requer `manage-services`.
- Atualiza um serviço.

`DELETE /api/v1/admin/services/{service}`

- Requer `manage-services`.
- Remove um serviço.

## Endpoints públicos

`GET /api/v1/public/posts?per_page=12&search=laravel&tag=laravel&category_id=3`

- Retorna apenas posts publicados.

`GET /api/v1/public/posts/{slug}`

- Retorna um post publicado por slug.

`GET /api/v1/public/projects?per_page=12&search=crm&category_id=2&is_featured=1`

- Retorna apenas projetos publicados.

`GET /api/v1/public/projects/{slug}`

- Retorna um projeto publicado por slug.

`GET /api/v1/public/services?per_page=12&search=landing&category_id=4`

- Retorna apenas serviços ativos.

`GET /api/v1/public/services/{slug}`

- Retorna um serviço ativo por slug.

## Observações

- `per_page` é limitado entre `1` e `50`. `limit` continua aceito como alias para compatibilidade.
- Os tokens emitidos herdam abilities calculadas a partir das permissões atuais do usuário no momento da emissão.
- Quando `abilities` é informado no login ou na criação de tokens, ele precisa ser subconjunto das abilities disponíveis para aquele usuário.
- Conteúdo admin agora também suporta escrita pela API `v1` para posts, projetos e serviços.
