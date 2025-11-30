<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub | Editar Perfil</title>
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

    <main class="edit-profile-container">
        <div class="edit-card">
            <h1>Editar Perfil</h1>

            @if ($errors->any())
                <div class="error-message">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="edit-form">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="profile_photo">Foto de Perfil</label>
                    <div class="photo-preview">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Foto atual" id="preview-image">
                        @else
                            <img src="/css/imagens/mascote.png" alt="Foto padrão" id="preview-image">
                        @endif
                    </div>
                    <input type="file" id="profile_photo" name="profile_photo" accept="image/*"
                        onchange="previewPhoto(event)">
                </div>

                <div class="form-group">
                    <label for="username">Nome de usuário</label>
                    <input type="text" id="username" name="username" value="{{ $user->username }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ $user->email }}" required>
                </div>

                <div class="form-group">
                    <label for="data_nascimento">Data de nascimento</label>
                    <input type="date" id="data_nascimento" name="data_nascimento"
                        value="{{ $user->data_nascimento->format('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label for="professional_title">Título Profissional</label>
                    <select id="professional_title" name="professional_title">
                        <option value="">Selecione uma opção</option>
                        <option value="Game Designer" {{ $user->professional_title == 'Game Designer' ? 'selected' : '' }}>Game Designer</option>
                        <option value="Art Director" {{ $user->professional_title == 'Art Director' ? 'selected' : '' }}>
                            Art Director</option>
                        <option value="Game Programmer" {{ $user->professional_title == 'Game Programmer' ? 'selected' : '' }}>Game Programmer</option>
                        <option value="Software Engineer" {{ $user->professional_title == 'Software Engineer' ? 'selected' : '' }}>Software Engineer</option>
                        <option value="2D Artist" {{ $user->professional_title == '2D Artist' ? 'selected' : '' }}>2D
                            Artist</option>
                        <option value="3D Artist" {{ $user->professional_title == '3D Artist' ? 'selected' : '' }}>3D
                            Artist</option>
                        <option value="2D Animator" {{ $user->professional_title == '2D Animator' ? 'selected' : '' }}>2D
                            Animator</option>
                        <option value="3D Animator" {{ $user->professional_title == '3D Animator' ? 'selected' : '' }}>3D
                            Animator</option>
                        <option value="Sound Designer" {{ $user->professional_title == 'Sound Designer' ? 'selected' : '' }}>Sound Designer</option>
                        <option value="Voice Actor" {{ $user->professional_title == 'Voice Actor' ? 'selected' : '' }}>
                            Voice Actor</option>
                        <option value="QA Tester" {{ $user->professional_title == 'QA Tester' ? 'selected' : '' }}>QA
                            Tester</option>
                        <option value="Producer" {{ $user->professional_title == 'Producer' ? 'selected' : '' }}>Producer
                        </option>
                        <option value="Studio Director" {{ $user->professional_title == 'Studio Director' ? 'selected' : '' }}>Studio Director</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">Salvar Alterações</button>
                    <a href="{{ route('profile') }}" class="btn-cancel">Cancelar</a>
                </div>
            </form>
        </div>
    </main>

    <script>
        function previewPhoto(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('preview-image').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>

</html>