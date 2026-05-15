# 1.5 6o periodo - versao final para banca

Documento de apoio da entrega final para banca. A versao final para envio esta em:

- `docs/DOCUMENTACAO-GAME-HUB-VERSAO-FINAL-BANCA.html`
- `../DOCUMENTO DE ESPECIFICACAO GAME HUB - VERSAO FINAL BANCA.pdf`

A revisao considera os feedbacks da Fase 02 e consolida requisitos, arquitetura, integracao, evidencias de teste e build.

## Grupo

- Murilo Cesar Ramos Melo - 2310194
- Marcelo Alencar Quessada - 2321520
- Otavio - 2320135

## Escopo concluido

- Migracao da interface principal para React + Inertia.js
- Login e cadastro integrados ao backend Laravel com sessao
- Dashboard autenticado
- Visualizacao e edicao de perfil com upload
- Portfolio carregado e enviado ao backend real
- Sistema de seguir e deixar de seguir
- Sistema de bloquear e desbloquear, incluindo lista de usuarios bloqueados na tela de busca
- Busca dinamica de usuarios
- Exclusao de usuarios bloqueados da busca, sugestoes e perfis publicos
- Respostas JSON de dashboard e perfis para evidencia tecnica da integracao
- Matriz de requisitos da Fase 01 atualizada com status real da Fase 02
- Padronizacao de respostas JSON com Resources
- Separacao entre Controllers, Services, Requests e Components por dominio

## Principais rotas da fase

- `GET /login`
- `POST /login`
- `GET /register`
- `POST /register`
- `GET /dashboard`
- `GET /profile`
- `GET /users/{user}`
- `PUT /profile`
- `GET /users`
- `POST /users/{user}/follow`
- `DELETE /users/{user}/follow`
- `POST /users/{user}/block`
- `DELETE /users/{user}/block`

## Organizacao tecnica

- `AuthController`, `ProfileController`, `UserDirectoryController`, `DashboardController` e controllers sociais fazem apenas orquestracao
- `AuthService`, `ProfileService`, `SocialGraphService` e `UserDirectoryService` concentram regras de negocio
- `UserResource`, `ProfileResource` e `PortfolioItemResource` padronizam a serializacao
- `resources/js/Pages` e `resources/js/Components` foram separados por dominio
- Banco local documentado como SQLite, conforme `.env` do projeto

## Evidencias executadas

- `php artisan test` passou com 9 testes e 71 assertions
- `npm run build` passou com Vite e 600 modulos transformados
- A documentacao revisada inclui payloads de login, dashboard, busca, bloqueio, perfil e erro de validacao
