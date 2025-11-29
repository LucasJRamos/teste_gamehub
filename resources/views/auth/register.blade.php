<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>GameHub | Cadastro</title>
  <link rel="stylesheet" href="{{ asset('css/stylelogin.css') }}">
</head>
<body class="page--cadastro">
  <div class="login-container">
    <div class="login-card">
      <div class="logo-area">
        <div class="logo"><img src="css\imagens\mascote.png" alt="Mascote GameHub"></div>
        <h1>CADASTRO<br><span>GAME HUB</span></h1>
      </div>

      @if(session('success'))
        <p class="success-msg">{{ session('success') }}</p>
      @endif

      @if ($errors->any())
        <div style="background: rgba(255,0,80,.10); color:#ff7c9b; padding:6px 8px; border-radius:6px; margin-bottom:10px;">
          <ul style="list-style: inside;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form class="form" method="POST" action="{{ route('register') }}">
        @csrf
        <label for="username">Nome de usuário</label>
        <input id="username" type="text" name="username" placeholder="Seu nome de usuário" required>

        <label for="email">Email</label>
        <input id="email" type="email" name="email" placeholder="Seu email" required>

        <label for="data_nascimento">Data de nascimento</label>
        <input id="data_nascimento" type="date" name="data_nascimento" required data-placeholder="Data de nascimento">

        <label for="password">Senha</label>
        <input id="password" type="password" name="password" placeholder="Crie uma senha" required>

        <label for="password_confirmation">Confirmar senha</label>
        <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Repita a senha" required>

        <button type="submit" class="btn-login">Cadastrar</button>
      </form>

      <div class="links">
        <a href="{{ route('login') }}">Já tem conta? Faça login</a>
        <span></span>
      </div>

      <div class="divider"><span>ou entre com:</span></div>
      <div class="social-icons">
        <div class="icon"></div><div class="icon"></div><div class="icon"></div>
      </div>
    </div>
  </div>
</body>

</html>
