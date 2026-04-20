# Game Hub

Aplicacao web em Laravel 12 com frontend SPA em React + Inertia.js para perfis profissionais, descoberta de usuarios e interacoes sociais no ecossistema de games.

## Versao atual

`1.3` - `6o periodo - Fase 02`

Integracao completa entre frontend e backend, incluindo autenticacao, perfis, interacoes sociais e busca.

## Stack

- Laravel 12
- React 19
- Inertia.js
- Vite
- Tailwind/Vite plugin disponivel
- Autenticacao por sessao com CSRF

## Fluxos entregues nesta fase

- Cadastro e login pelo frontend com redirecionamento para `/dashboard`
- Rotas protegidas com middleware `auth`
- Visualizacao e edicao de perfil via Inertia
- Busca dinamica de usuarios
- Seguir e deixar de seguir usuarios
- Bloquear e desbloquear usuarios
- Filtro de bloqueio aplicado em busca, sugestoes e perfil publico
- Respostas JSON padronizadas para consumo HTTP real
- Controllers focados em orquestracao e regras de negocio movidas para `Services`

## Estrutura principal

- `app/Http/Controllers`: entrada HTTP e navegacao Inertia
- `app/Services`: regras de negocio
- `app/Http/Requests`: validacao
- `app/Http/Resources`: serializacao JSON
- `resources/js/Pages`: paginas React por dominio
- `resources/js/Components`: componentes reutilizaveis

## Rodando o projeto

```bash
composer install
npm install
php artisan migrate
npm run dev
php artisan serve
```

## Testes

```bash
php artisan test
npm run build
```
