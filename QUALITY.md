# Manifesto de Qualidade - Game Hub

O **Game Hub** é uma aplicação Laravel voltada a centralizar perfis, portfólio e interações da comunidade gamer. Para transformar a qualidade em código, o repositório adota um Quality Gate automatizado no GitHub Actions para cada Pull Request aberto contra a branch `main`.

## Ferramentas adotadas

- **Linter / analisador estático:** Laravel Pint, executado com `composer lint`. Ele valida padronização PSR/Laravel nos arquivos PHP sem alterar código durante a CI.
- **Suíte de testes automatizados:** PHPUnit via `php artisan test`, executado pelo script `composer test`. A suíte cobre autenticação, cadastro, dashboard protegido, perfil, portfólio, exploração de usuários e relacionamentos dos models.
- **Cobertura mínima travada:** 80% de cobertura de código em `app/`, executada por `composer test:coverage` com `php artisan test --coverage --min=80`.

## Quality Gate

O workflow `.github/workflows/quality-gate.yml` executa checkout, setup do PHP 8.2, instalação das dependências, lint e testes com cobertura. O Pull Request só deve ser liberado para merge quando o job `quality` passar com sucesso. Caso o Pint encontre erro de estilo, algum teste falhe ou a cobertura fique abaixo de 80%, o GitHub Actions encerra o job com exit code diferente de zero e a Branch Protection Rule bloqueia o merge na `main`.
