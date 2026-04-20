# Documentacao da API REST - Game Hub

## 1. Versao documentada

- Projeto: Game Hub
- Versao: `1.3`
- Marco: `6o periodo - Fase 02`
- Escopo: integracao completa entre frontend React + Inertia.js e backend Laravel 12

## 2. Grupo

- Lucas Jose Ramos Alves - 2412899
- Murilo Cesar Ramos Melo - 2310194
- Marcelo Alencar Quessada - 2321520
- Mateus Pereira Teixeira - 2211825

## 3. Descricao geral

O Game Hub e uma plataforma voltada ao ecossistema de games independentes. Nesta fase, o projeto passou a operar com frontend SPA em React + Inertia.js consumindo o backend Laravel real, sem mocks, com autenticacao por sessao, perfis, portfolio, busca e interacoes sociais.

## 4. Base URL

```text
http://127.0.0.1:8000
```

## 5. Tecnologias

- PHP 8.2+
- Laravel 12
- React 19
- Inertia.js
- Vite
- SQLite em desenvolvimento local
- Autenticacao baseada em sessao

## 6. Padrao de resposta

### Sucesso

```json
{
  "message": "Operacao realizada com sucesso.",
  "data": {}
}
```

### Erro de validacao

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email field is required."
    ]
  }
}
```

### Falha de autenticacao

```json
{
  "message": "Credenciais invalidas.",
  "errors": {
    "email": [
      "Credenciais invalidas."
    ]
  }
}
```

## 7. Autenticacao

O sistema usa autenticacao por sessao. As requisicoes autenticadas dependem do cookie de sessao e do token CSRF emitidos pelo Laravel.

```http
Cookie: laravel-session=SEU_COOKIE
X-XSRF-TOKEN: SEU_TOKEN
```

## 8. Endpoints

### 8.1 Cadastro

- Metodo: `POST`
- URL: `/register`

Body:

```json
{
  "username": "lucasdev",
  "email": "lucas@email.com",
  "data_nascimento": "2003-05-10",
  "password": "123456",
  "password_confirmation": "123456"
}
```

Resposta:

- `201 Created` em JSON
- `302 Found` no fluxo web/Inertia com redirecionamento para `/dashboard`

### 8.2 Login

- Metodo: `POST`
- URL: `/login`

Body:

```json
{
  "email": "lucas@email.com",
  "password": "123456"
}
```

Status:

- `200 OK` sucesso em JSON
- `302 Found` no fluxo web/Inertia com redirecionamento para `/dashboard`
- `401 Unauthorized` para credenciais invalidas em JSON
- `422 Unprocessable Entity` para validacao

### 8.3 Logout

- Metodo: `POST`
- URL: `/logout`

Status:

- `200 OK` em JSON
- `302 Found` no fluxo web/Inertia com redirecionamento para `/login`

### 8.4 Dashboard autenticado

- Metodo: `GET`
- URL: `/dashboard`

Descricao: retorna a pagina Inertia autenticada com dados do usuario logado e sugestoes.

Status:

- `200 OK`
- `302 Found` para `/login` quando nao autenticado

### 8.5 Perfil do usuario autenticado

- Metodo: `GET`
- URL: `/profile`

Resposta JSON:

```json
{
  "message": "Perfil carregado com sucesso",
  "data": {
    "user": {
      "id": 1,
      "username": "lucasdev",
      "email": "lucas@email.com",
      "professional_title": "Game Programmer"
    },
    "portfolio_items": []
  }
}
```

### 8.6 Perfil publico

- Metodo: `GET`
- URL: `/users/{user}`

Regras:

- perfis bloqueados nao devem ser exibidos
- se houver bloqueio entre os usuarios, o backend responde `404 Not Found`

### 8.7 Atualizacao de perfil

- Metodo: `PUT`
- URL: `/profile`

Campos aceitos:

- `username`
- `email`
- `data_nascimento`
- `professional_title`
- `profile_photo` via `multipart/form-data`

Status:

- `200 OK` em JSON
- `302 Found` no fluxo web/Inertia com redirecionamento para `/profile`
- `422 Unprocessable Entity` em validacao

### 8.8 Adicionar item ao portfolio

- Metodo: `POST`
- URL: `/portfolio/upload`

Campos:

- `title`
- `description`
- `type` com valores `link` ou `image`
- `link_url` quando `type=link`
- `file` quando `type=image`

Status:

- `201 Created` em JSON
- `302 Found` no fluxo web/Inertia com redirecionamento para `/profile`

### 8.9 Remover item do portfolio

- Metodo: `DELETE`
- URL: `/portfolio/{portfolioItem}`

Status:

- `200 OK` em JSON
- `302 Found` no fluxo web/Inertia com redirecionamento para `/profile`
- `404 Not Found` se o item nao pertencer ao usuario autenticado

### 8.10 Buscar usuarios

- Metodo: `GET`
- URL: `/users`

Query params:

- `search` opcional

Regras:

- exclui o usuario autenticado
- exclui usuarios bloqueados
- permite filtro por `username` e `professional_title`

Resposta JSON:

```json
{
  "message": "Usuarios carregados com sucesso.",
  "data": [
    {
      "id": 2,
      "username": "murilodev",
      "professional_title": "Level Designer",
      "is_following": false,
      "has_blocked": false
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 9,
    "total": 1
  }
}
```

### 8.11 Seguir usuario

- Metodo: `POST`
- URL: `/users/{user}/follow`

Regras:

- nao permite seguir a si mesmo
- nao permite seguir usuario bloqueado

Status:

- `201 Created` em JSON
- `302 Found` no fluxo web/Inertia

### 8.12 Deixar de seguir usuario

- Metodo: `DELETE`
- URL: `/users/{user}/follow`

Status:

- `200 OK` em JSON
- `302 Found` no fluxo web/Inertia

### 8.13 Bloquear usuario

- Metodo: `POST`
- URL: `/users/{user}/block`

Regras:

- nao permite bloquear a si mesmo
- remove relacoes de follow entre os dois usuarios
- remove o perfil da busca e da visualizacao publica

Status:

- `201 Created` em JSON
- `302 Found` no fluxo web/Inertia

### 8.14 Desbloquear usuario

- Metodo: `DELETE`
- URL: `/users/{user}/block`

Status:

- `200 OK` em JSON
- `302 Found` no fluxo web/Inertia

## 9. Status codes utilizados

- `200 OK`
- `201 Created`
- `302 Found`
- `401 Unauthorized`
- `404 Not Found`
- `422 Unprocessable Entity`

## 10. Observacoes finais

Esta documentacao reflete o estado real da Fase 02 do projeto, em que a navegacao principal ocorre via Inertia.js e as mesmas regras tambem podem ser consumidas por requisicoes HTTP/JSON para testes, integracao e validacao dos endpoints.
