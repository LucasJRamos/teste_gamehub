<h2>Login</h2>
@if(session('success'))
<p>{{ session('success') }}</p> @endif
<form method="POST" action="/login">
    @csrf
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Senha" required><br>
    <button type="submit">Entrar</button>
</form>
<a href="{{ route('register') }}">Não tem conta? Cadastre-se</a>