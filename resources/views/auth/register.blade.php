@if ($errors->any())
    <ul style="color:red;">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

@if (session('success'))
    <div style="color:green;">{{ session('success') }}</div>
@endif

<h2>Cadastro</h2>
<form method="POST" action="{{ route('register') }}">
    @csrf
    <input type="text" name="username" placeholder="Nome de usuário" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="date" name="data_nascimento" required><br>
    <input type="password" name="password" placeholder="Senha" required><br>
    <input type="password" name="password_confirmation" placeholder="Confirmar senha" required><br>
    <button type="submit">Cadastrar</button>
</form>
<a href="{{ route('login') }}">Já tem conta? Faça login</a>