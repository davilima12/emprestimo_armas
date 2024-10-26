<div>
    <div>
        Olá, {{ $userName }} voce foi convidado a registrar-se no Westside Motorcycle ERP.
    </div>
    <a
        href="{{ config('app.frontend_url') . "/user/confirm-password?code=$token"}}"
        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
    >
        Clique aqui
    </a> para confirmar seu usuario e registrar sua senha
</div>
