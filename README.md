# Game Hub

Aplicacao web em Laravel 12 com frontend SPA em React + Inertia.js para perfis profissionais, descoberta de usuarios e interacoes sociais no ecossistema de games.

## Versao atual

`1.5` - `6o periodo - versao final para banca`

Integracao entre frontend e backend documentada com rotas, payloads, services, testes e build.

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
- Lista de usuarios bloqueados para permitir desbloqueio pelo frontend
- Filtro de bloqueio aplicado em busca, sugestoes e perfil publico
- Payloads JSON de dashboard, perfil e busca para evidencia tecnica
- Respostas JSON padronizadas para consumo HTTP real
- Controllers focados em orquestracao e regras de negocio movidas para `Services`

## Documento final para banca

- HTML fonte: `docs/DOCUMENTACAO-GAME-HUB-VERSAO-FINAL-BANCA.html`
- PDF para envio: `../DOCUMENTO DE ESPECIFICACAO GAME HUB - VERSAO FINAL BANCA.pdf`

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
