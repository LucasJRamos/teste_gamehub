<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Cadastro - Game Hub</title>

  <!-- fontes e css -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="{{ asset('css/styleregister.css') }}">
</head>
<body>

  @if ($errors->any())
    <div class="error" role="alert">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @if (session('success'))
    <div class="success" role="status">{{ session('success') }}</div>
  @endif

  <h1>Cadastro</h1>

  <form method="POST" action="{{ route('register') }}">
    @csrf
    <input type="text"     name="username"            placeholder="Nome de usuário" required>
    <input type="email"    name="email"               placeholder="Email"           required>
    <input type="date"     name="data_nascimento"     required>
    <input type="password" name="password"            placeholder="Senha"           required>
    <input type="password" name="password_confirmation" placeholder="Confirmar senha" required>
    <button type="submit">Cadastrar</button>
  </form>

  <p><a href="{{ route('login') }}">Já tem conta? Faça login</a></p>

</body>
</html>
