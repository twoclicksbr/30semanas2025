@if (!session()->has('auth_adm-global'))
    @php
        session()->flash('error', 'Você precisa estar logado para acessar essa página.');
        session()->save(); // força salvar a sessão
        header('Location: ' . url('/'));
        exit;
    @endphp
@endif
