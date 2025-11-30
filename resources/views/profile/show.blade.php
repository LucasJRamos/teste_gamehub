<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub | Perfil</title>
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
                <a href="{{ route('profile') }}"
                    class="nav-link {{ !isset($isOwnProfile) || $isOwnProfile ? 'active' : '' }}">Perfil</a>
                <a href="{{ route('explore') }}" class="nav-link">Explorar</a>
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

    <main class="profile-container">
        @if(session('success'))
            <div class="success-message">{{ session('success') }}</div>
        @endif

        <div class="profile-header">
            <div class="profile-banner"></div>
            <div class="profile-info">
                @if($user->profile_photo)
                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Avatar" class="profile-avatar">
                @else
                    <img src="/css/imagens/mascote.png" alt="Avatar" class="profile-avatar">
                @endif
                <div class="profile-details">
                    <div class="profile-name-title">
                        <h1>{{ $user->username }}</h1>
                        @if($user->professional_title)
                            <span class="professional-title">{{ $user->professional_title }}</span>
                        @endif
                    </div>
                    <p>{{ $user->email }}</p>
                    <p>Membro desde {{ $user->created_at->format('d/m/Y') }}</p>
                    <p>Nascimento: {{ $user->data_nascimento->format('d/m/Y') }}</p>

                    @if(!isset($isOwnProfile) || $isOwnProfile)
                        <a href="{{ route('profile.edit') }}" class="btn-edit-profile">✏️ Editar Perfil</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="profile-tabs">
            <button class="tab-btn active" onclick="showTab('portfolio')">Portfólio</button>
        </div>

        <section class="portfolio-section" id="portfolio">
            @if(!isset($isOwnProfile) || $isOwnProfile)
                <div class="portfolio-upload">
                    <h2>Adicionar ao Portfólio</h2>
                    <div class="upload-options">
                        <button class="btn-option" onclick="showUploadForm('image')">📸 Upload de Imagem</button>
                        <button class="btn-option" onclick="showUploadForm('link')">🔗 Link de Repositório</button>
                    </div>

                    <!-- Form de Upload de Imagem -->
                    <form id="form-image" class="upload-form" method="POST" action="{{ route('portfolio.upload') }}"
                        enctype="multipart/form-data" style="display: none;">
                        @csrf
                        <input type="hidden" name="type" value="image">
                        <input type="text" name="title" placeholder="Título (opcional)" maxlength="255">
                        <textarea name="description" placeholder="Descrição (opcional)" maxlength="1000"
                            rows="3"></textarea>
                        <input type="file" name="file" accept="image/*" required>
                        <div class="form-actions">
                            <button type="submit" class="btn-submit">Enviar</button>
                            <button type="button" class="btn-cancel" onclick="hideUploadForm()">Cancelar</button>
                        </div>
                    </form>

                    <!-- Form de Link -->
                    <form id="form-link" class="upload-form" method="POST" action="{{ route('portfolio.upload') }}"
                        style="display: none;">
                        @csrf
                        <input type="hidden" name="type" value="link">
                        <input type="text" name="title" placeholder="Título (opcional)" maxlength="255">
                        <textarea name="description" placeholder="Descrição (opcional)" maxlength="1000"
                            rows="3"></textarea>
                        <input type="url" name="link_url" placeholder="URL do repositório" required>
                        <div class="form-actions">
                            <button type="submit" class="btn-submit">Enviar</button>
                            <button type="button" class="btn-cancel" onclick="hideUploadForm()">Cancelar</button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="portfolio-grid">
                @forelse($portfolioItems as $item)
                    <div class="portfolio-item">
                        @if($item->type === 'image')
                            <div class="portfolio-image">
                                <img src="{{ asset('storage/' . $item->file_path) }}"
                                    alt="{{ $item->title ?? 'Portfolio item' }}">
                            </div>
                        @else
                            <div class="portfolio-link">
                                <div class="link-icon">🔗</div>
                                <a href="{{ $item->link_url }}" target="_blank">{{ $item->link_url }}</a>
                            </div>
                        @endif

                        @if($item->title || $item->description)
                            <div class="portfolio-info">
                                @if($item->title)
                                    <h3>{{ $item->title }}</h3>
                                @endif
                                @if($item->description)
                                    <p>{{ $item->description }}</p>
                                @endif
                            </div>
                        @endif

                        @if(!isset($isOwnProfile) || $isOwnProfile)
                            <form method="POST" action="{{ route('portfolio.delete', $item->id) }}" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete"
                                    onclick="return confirm('Tem certeza que deseja remover este item?')">🗑️</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="empty-portfolio">
                        <p>{{ (!isset($isOwnProfile) || $isOwnProfile) ? 'Você ainda não tem itens no seu portfólio. Adicione seu primeiro projeto!' : 'Este usuário ainda não tem itens no portfólio.' }}
                        </p>
                    </div>
                @endforelse
            </div>
        </section>
    </main>

    <script>
        function showTab(tabName) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
        }

        function showUploadForm(type) {
            hideUploadForm();
            document.getElementById('form-' + type).style.display = 'block';
        }

        function hideUploadForm() {
            document.getElementById('form-image').style.display = 'none';
            document.getElementById('form-link').style.display = 'none';
        }
    </script>
</body>

</html>