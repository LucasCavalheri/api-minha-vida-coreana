<main>
    <div>
        <form action="{{ route('reset.password.post') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value={{$token}}>
            <div>
                <label for="email">Digite seu Email</label>
                <input type="text" name="email">
            </div>
            <div>
                <label for="password">Nova Senha</label>
                <input type="text" name="password">
            </div>
            <div>
                <label for="password_confirmation">Confirme a Nova Senha</label>
                <input type="text" name="password_confirmation">
            </div>
            <button type="submit">Enviar</button>
        </form>
    </div>
</main>
