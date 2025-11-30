<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub | Explorar</title>
    <link rel="stylesheet" href="{{ asset('css/styledashboard.css') }}">
</head>

<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <img src="/css/imagens/mascote.png" alt="GameHub">
                <span>GAME HUB</span>
            </div>
            <div class="nav-menu">
                <a href="{{ route('dashboard') }}" class="nav-link">Feed</a>
                <a href="{{ route('profile') }}" class="nav-link">Perfil</a>
                <a href="{{ route('explore') }}" class="nav-link active">Explorar</a>
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

    <main class="explore-container">
        <div class="explore-header">
            <h1>Explorar Desenvolvedores</h1>
            <form method="GET" action="{{ route('explore') }}" class="search-form">
                <input type="text" name="search" placeholder="Buscar por nome ou profissão..."
                    value="{{ $search ?? '' }}" class="search-input">
                <button type="submit" class="btn-search">🔍 Buscar</button>
            </form>
        </div>

        <div class="users-list">
            @forelse($users as $user)
                <div class="user-card">
                    <div class="user-info">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Avatar" class="user-avatar">
                        @else
                            <img src="/css/imagens/mascote.png" alt="Avatar" class="user-avatar">
                        @endif
                        <div class="user-details">
                            <h3>{{ $user->username }}</h3>
                            @if($user->professional_title)
                                <p class="user-title">{{ $user->professional_title }}</p>
                            @else
                                <p class="user-title">Desenvolvedor</p>
                            @endif
                        </div>
                    </div>
                    <div class="user-actions">
                        <a href="{{ route('profile.show', $user->id) }}" class="btn-view-profile">👤 Ver Perfil</a>
                        <button class="btn-message">💬 Mensagem</button>
                    </div>
                </div>
            @empty
                <div class="empty-results">
                    <p>{{ $search ? 'Nenhum usuário encontrado.' : 'Nenhum desenvolvedor disponível no momento.' }}</p>
                </div>
            @endforelse
        </div>

        <div class="pagination">
            {{ $users->links() }}
        </div>
    </main>
</body>

</html>