@extends('layouts.home2')
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
    <div class="row menu"><a id="sigdec" href="{{ route('home') }}" class="barra">Retornar ao SIGDEC</a></div>
    @if(Auth::user() -> acesso == 2)
    <div class="row menu"><a id="grupo" href="{{ route('d.grupo.index') }}" class="barra">Adicionar Grupo</a></div>
    <div class="row menu"><a id="questao" href="{{ route('d.questao.index') }}" class="barra">Adicionar Questão</a></div>
    <div class="row menu"><a id="edicao" href="{{ route('d.edicao.index') }}" class="barra">Criar Edição</a></div>
    @endif
    <div class="row menu"><a id="relatorios" href="{{ route('diagnose.edicaoShow') }}" class="barra">Relatórios Diagnose</a></div>
    <div class="row menu"><a id="bi" href="{{ route('analises.diagnose') }}" class="barra">B.I. Diagnose</a></div>
    <div class="row menu"><a id="sair" href="{{ route('sair') }}" class="barra">Sair</a></div>
    @endsection
@endif

