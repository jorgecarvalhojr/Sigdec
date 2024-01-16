<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    @stack('css')
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">


    <title>@yield('title', 'SIGDEC')</title>

    @livewireStyles
</head>

<body>
    
    <div id="head" style="@yield('fundo', 'background:#F5703C'); @yield('fonte', 'color:#000080');" class="row align-items-center topo">
        @section('header')
        <div id="logo1" class="col-md-2">
            @section('logo1')
            <img class="logo1" src="{{ url('storage/logos/logo1.png') }}" alt="logo1">
            @show
        </div>
        <div id="texto" class="col-md-8 align-self-center span">
            <h4 id="t1">@yield('t1', 'SECRETRIA DE ESTADO DE DEFESA CIVIL')</h4>
            <h4 id="t2">@yield('t2', 'SISTEMA DE GESTÃO EM DEFESA CIVIL')</h4>
            <h4 id="t3">RELATÓRIO DIAGNOSE</h4>
        </div>
        <div id="logo2" class="col-md-2">
            @section('logo2')
            <img class="logo2" src="{{ url('storage/logos/logo2.png') }}" alt="logo2">
            @show
        </div>
        @show
    </div>
    
    
    {{-- <div id="barra" class="row">
        @section('barra')
        <div class="col-md-3"><a id="home" href="{{route('manual')}}" class="barra">Manual</a></div>
        <div class="col-md-3"><a id="adesao" href="{{route('adesao.index')}}" class="barra">Solicitação de Cadastro</a></div>
        <div class="col-md-3"><a id="contato" href="{{route('contato')}}" class="barra">Fale Conosco</a></div>
        <div class="col-md-3"><a id="login" href="{{ route('acesso.index') }}" class="barra">Entrar</a></div>
        @show
    </div> --}}

    <div class="row">
        <div id="miolo" class="col-md-12">
            @section('miolo')
                <div class="row">
                    <div id="menu" class="col-md-2">
                        @yield('menu')  
                    </div>
                    <div id="conteudo" class="col-md-10">
                        @yield('conteudo')
                    </div>
                </div>
            @show
        </div>
    </div>
   
    <div class="row">
        <div id="rodape" class="col-md-12">
            @section('footer')
            <img src="{{asset('storage/logos/logo2.png')}}">
            <span>SIGDEC &copy; {{date('Y')}}</span>
            @show
        </div>
    </div>
    @stack('script')
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
    @livewireScripts
</body>

</html>