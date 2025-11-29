<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub | Login</title>
    <link rel="stylesheet" href="{{ asset('css/stylelogin.css') }}">
</head>
<body>

    <div class="login-container">
        <div class="login-card">
            <div class="logo-area">
                <div class="logo"><img src="css\imagens\mascote.png" alt=""></div>
                <h1>WELCOME!<br><span>GAME HUB</span></h1>
            </div>

            @if(session('success'))
                <p class="success-msg">{{ session('success') }}</p>
            @endif

            <form class="form" method="POST" action="/login">
                @csrf
                <label>Email</label>
                <input type="email" name="email" placeholder="Seu email" required>

                <label>Senha</label>
                <input type="password" name="password" placeholder="Sua senha" required>
                <!-- Game Hub Button -->
                <button class="btn-login" aria-label="Ready? / Fight!">
                <div class="original">Ready?</div>
                <div class="letters" aria-hidden="true">
                    <span>F</span>
                    <span>I</span>
                    <span>G</span>
                    <span>H</span>
                    <span>T</span>
                    <span>!</span>
                </div>
                </button>

            </form>

            <div class="links">
                <a href="{{ route('register') }}">Cadastrar</a>
                <a href="#">Esqueci a senha</a>
            </div>

            <div class="divider">
                <span>ou entre com:</span>
            </div>

            <div class="social-icons">
                <div class="icon"></div>
                <div class="icon"></div>
                <div class="icon"></div>
            </div>
        </div>
    </div>

</body>
</html>
