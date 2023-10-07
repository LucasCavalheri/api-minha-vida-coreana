<!-- Depois isso vai ser uma rota no front -->


<main>
    <div>
        <p>Te enviaremos um link para o email fornecido. Use este link para redefinir sua senha.</p>
        <form action="{{ route('forgot.password.post') }}" method="POST">
            @csrf
            <div>
                <label for="email">Digite seu Email</label>
                <input type="text" name="email">
            </div>
            <button type="submit">Enviar</button>
        </form>
    </div>
</main>
