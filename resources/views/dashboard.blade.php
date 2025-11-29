<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub | Feed</title>
    <link rel="stylesheet" href="{{ asset('css/styledashboard.css') }}">
</head>

<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <img src="css/imagens/mascote.png" alt="GameHub">
                <span>GAME HUB</span>
            </div>
            <div class="nav-menu">
                <a href="{{ route('dashboard') }}" class="nav-link active">Feed</a>
                <a href="{{ route('profile') }}" class="nav-link">Perfil</a>
                <a href="#" class="nav-link">Explorar</a>
            </div>
            <div class="nav-user">
                <span>{{ auth()->user()->username }}</span>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-logout">Sair</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="feed-container">
        <aside class="sidebar-left">
            <div class="widget">
                <h3>Bem-vindo!</h3>
                <p>Olá, <strong>{{ auth()->user()->username }}</strong>! Explore projetos incríveis da comunidade indie.
                </p>
            </div>
            <div class="widget">
                <h3>Categorias</h3>
                <ul class="category-list">
                    <li>🎮 Ação</li>
                    <li>🧩 Puzzle</li>
                    <li>🌍 RPG</li>
                    <li>👾 Arcade</li>
                    <li>🎨 Visual Novel</li>
                </ul>
            </div>
        </aside>

        <section class="feed-main">
            <div class="create-post">
                <img src="css/imagens/mascote.png" alt="Avatar" class="avatar">
                <input type="text" placeholder="Compartilhe seu projeto...">
                <button class="btn-post">Publicar</button>
            </div>

            <!-- Post exemplo 1 -->
            <article class="post-card">
                <div class="post-header">
                    <img src="css/imagens/mascote.png" alt="Avatar" class="avatar">
                    <div class="post-info">
                        <h4>DevMaster2000</h4>
                        <span>Há 2 horas</span>
                    </div>
                </div>
                <div class="post-content">
                    <p>Acabei de lançar meu novo jogo de plataforma! Levou 6 meses de desenvolvimento 🎮</p>
                    <div class="post-image">
                        <div class="placeholder-image">🎮 PREVIEW DO JOGO</div>
                    </div>
                </div>
                <div class="post-actions">
                    <button>❤️ 42 Curtidas</button>
                    <button>💬 8 Comentários</button>
                    <button>🔗 Compartilhar</button>
                </div>
            </article>

            <!-- Post exemplo 2 -->
            <article class="post-card">
                <div class="post-header">
                    <img src="css/imagens/mascote.png" alt="Avatar" class="avatar">
                    <div class="post-info">
                        <h4>PixelArtist</h4>
                        <span>Há 5 horas</span>
                    </div>
                </div>
                <div class="post-content">
                    <p>Estou procurando feedback sobre essa mecânica de movimento. O que acham? 🤔</p>
                    <div class="post-image">
                        <div class="placeholder-image">🕹️ GIF DA MECÂNICA</div>
                    </div>
                </div>
                <div class="post-actions">
                    <button>❤️ 28 Curtidas</button>
                    <button>💬 15 Comentários</button>
                    <button>🔗 Compartilhar</button>
                </div>
            </article>

            <!-- Post exemplo 3 -->
            <article class="post-card">
                <div class="post-header">
                    <img src="css/imagens/mascote.png" alt="Avatar" class="avatar">
                    <div class="post-info">
                        <h4>IndieGameDev</h4>
                        <span>Há 1 dia</span>
                    </div>
                </div>
                <div class="post-content">
                    <p>Tutorial: Como criar iluminação 2D realista no Unity! Link nos comentários 💡</p>
                </div>
                <div class="post-actions">
                    <button>❤️ 156 Curtidas</button>
                    <button>💬 34 Comentários</button>
                    <button>🔗 Compartilhar</button>
                </div>
            </article>
        </section>

        <aside class="sidebar-right">
            <div class="widget">
                <h3>🔥 Trending</h3>
                <ul class="trending-list">
                    <li>#IndieGameDev</li>
                    <li>#PixelArt</li>
                    <li>#GameJam2024</li>
                    <li>#UnityTips</li>
                    <li>#GodotEngine</li>
                </ul>
            </div>
            <div class="widget">
                <h3>👥 Sugestões</h3>
                <div class="suggestion">
                    <img src="css/imagens/mascote.png" alt="Avatar" class="avatar-small">
                    <div>
                        <strong>GameStudio</strong>
                        <button class="btn-follow">Seguir</button>
                    </div>
                </div>
                <div class="suggestion">
                    <img src="css/imagens/mascote.png" alt="Avatar" class="avatar-small">
                    <div>
                        <strong>ArtMaster</strong>
                        <button class="btn-follow">Seguir</button>
                    </div>
                </div>
            </div>
        </aside>
    </main>
</body>

</html>