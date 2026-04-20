# 1.3 6o periodo - Fase 02

Integracao completa entre frontend e backend, incluindo autenticacao, perfis, interacoes sociais e busca.

## Grupo

- Lucas Jose Ramos Alves - 2412899
- Murilo Cesar Ramos Melo - 2310194
- Marcelo Alencar Quessada - 2321520
- Mateus Pereira Teixeira - 2211825

## Escopo concluido

- Migracao da interface principal para React + Inertia.js
- Login e cadastro integrados ao backend Laravel com sessao
- Dashboard autenticado
- Visualizacao e edicao de perfil com upload
- Portfolio carregado e enviado ao backend real
- Sistema de seguir e deixar de seguir
- Sistema de bloquear e desbloquear
- Busca dinamica de usuarios
- Exclusao de usuarios bloqueados da busca, sugestoes e perfis publicos
- Padronizacao de respostas JSON com Resources
- Separacao entre Controllers, Services, Requests e Components por dominio

## Principais rotas da fase

- `GET /login`
- `POST /login`
- `GET /register`
- `POST /register`
- `GET /dashboard`
- `GET /profile`
- `PUT /profile`
- `GET /users`
- `GET /users/{user}`
- `POST /users/{user}/follow`
- `DELETE /users/{user}/follow`
- `POST /users/{user}/block`
- `DELETE /users/{user}/block`

## Organizacao tecnica

- `AuthController`, `ProfileController`, `UserDirectoryController`, `DashboardController` e controllers sociais fazem apenas orquestracao
- `AuthService`, `ProfileService`, `SocialGraphService` e `UserDirectoryService` concentram regras de negocio
- `UserResource`, `ProfileResource` e `PortfolioItemResource` padronizam a serializacao
- `resources/js/Pages` e `resources/js/Components` foram separados por dominio

## Evidencias recomendadas

- Executar `php artisan test`
- Executar `npm run build`
- Registrar prints do login, dashboard, perfil, busca, follow e bloqueio
