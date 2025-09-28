<h2>Bem-vindo, {{ auth()->user()->username }}!</h2>
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Sair</button>
</form>