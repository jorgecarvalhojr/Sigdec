@extends('auth.home')
@section('title', 'Análises B.I.')

@section('conteudo')
    <div class="row">
        <div style="text-align: center" class="col-md-12">
            <h1 style="color: blue">ESTATÍSTICAS E ANÁLISES</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2">
            <a href="{{route('analises.prodec')}}"><img src="{{asset('storage/analises_bi/prodec.png')}}" width="100%"></a>
        </div>
        <div class="col-md-2">
            <a href="{{route('analises.sigdec')}}"><img src="{{asset('storage/analises_bi/sigdec.png')}}" width="100%"></a>
        </div>
        <div class="col-md-2">
            <a href="{{route('analises.plancon')}}"><img src="{{asset('storage/analises_bi/plancon.png')}}" width="100%"></a>
        </div>
    </div>
@endsection