@extends('layouts.home')
@section('title', 'Home')

@if(Auth::user()-> configuracao()->count() > 0 and Auth::user()-> configuracao -> fundo != '')
    @section('fundo', 'background:#'.Auth::user()-> configuracao -> fundo)
@endif  

@if(Auth::user()-> configuracao()->count() > 0 and Auth::user()-> configuracao -> fonte != '')
    @section('fonte', 'color:#'.Auth::user()-> configuracao -> fonte)
@endif

@if(Auth::user()-> configuracao()->count() > 0 and Storage::disk('public')->exists(Auth::user()-> configuracao -> logo1))
    @section('logo1')
        <img class="logo1" src="{{ url('storage/'.Auth::user()-> configuracao -> logo1) }}" alt="logo1">
    @endsection
@endif

@if(Auth::user()-> configuracao()->count() > 0 and Storage::disk('public')->exists(Auth::user()-> configuracao -> logo2))
    @section('logo2')
        <img class="logo2" src="{{ url('storage/'.Auth::user()-> configuracao -> logo2) }}" alt="logo2">
    @endsection
@endif

@if(Auth::user()-> configuracao()->count() > 0 and Auth::user()-> configuracao -> titulo1 != '')
    @section('t1', Auth::user()-> configuracao -> titulo1)
@endif

@if(Auth::user()-> configuracao()->count() > 0 and Auth::user()-> configuracao -> titulo2 != '')
    @section('t2', Auth::user()-> configuracao -> titulo2)
@endif

@section('barra')

<div class="col-md-12">
    Bem Vindo! {{Auth::user()-> nome}}
</div>

@endsection

@if(Auth::user())
    @section('menu')
    <div class="row menu"><a id="analises" href="{{ route('analises.index') }}" class="barra">Análises B.I.</a></div>
    <div class="row menu"><a id="inicio" href="{{ route('inicio') }}" class="barra">Atividades de Hoje</a></div>
    <div class="row menu"><a id="atualizar" href="{{ route('user.edit', ['id' => Auth::user() -> indice_adm]) }}" class="barra">Atualizar Cadastro</a></div>
        <div class="row menu"><a id="cadastro" href="{{ route('user.index') }}" class="barra">Cadastro de Usuário</a></div>
        @if(Auth::user() -> permissao == '2')
            <div class="row menu"><a id="orgao" href="{{ route('orgao.index') }}" class="barra">Cadastro de Órgão</a></div>
            <div class="row menu"><a id="viatura" href="{{ route('viatura.index') }}" class="barra">Cadastro de Viaturas</a></div>
            <div class="row menu"><a id="config" href="{{ route('config.index') }}" class="barra">Configuração</a></div>
        @endif
        <div class="row menu"><a id="estatisticas" href="{{ route('estatisticas.index') }}" class="barra">Estatísticas</a></div>
        <div class="row menu"><a id="exibir" href="{{ route('users.show') }}" class="barra">Exibir Usuários</a></div>
        <div class="row menu"><a id="atv_dc" href="{{ route('atv_dc.index') }}" class="barra">Registro de Atividade</a></div>
        @if(Auth::user() -> acesso == '2')
            <div class="row menu"><a id="registros" href="{{ route('registros.index') }}" class="barra">Registros dos Órgãos</a></div>
        @endif
        <div class="row menu"><a id="diagnose" href="{{ route('diagnose.home') }}" class="barra">Relatório Diagnose</a></div>
        <div class="row menu"><a id="relatorios" href="{{ route('relatorios.index') }}" class="barra">Relatórios</a></div>
        <div class="row menu"><a id="sair" href="{{ route('sair') }}" class="barra">Sair</a></div>
    @endsection
@endif

