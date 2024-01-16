@extends('auth.diagnose_home')
@section('title', 'Municípios')

@section('conteudo')
    @if ($municipios->count() > 0)
        
        <div class="col-md-6">
            <table style="font-size: 12px" border="1" cellpadding="2px" cellspacing="0" width="100%">
                <tr><td align="center" colspan="5"><strong>RELATÓRIO DIAGNOSE - EDIÇÃO {{$edicao -> ano}}</strong> <button id="voltar" type="button" onclick="javascript:window.location='{{route('diagnose.edicaoShow')}}'" class="btn btn-primary">Voltar</button></td></tr>
                <tr align="center" bgcolor="#CCCCCCC">
                    <td>N°</td>
                    <td>Município</td>
                    <td>REDEC</td>
                    <td>Questões Respondidas</td>
                    <td>Entrar</td>
                </tr>
                @foreach ($municipios as $item)
                    <tr align="center" @if($loop -> even) bgcolor="#CCCCCCD" @endif>
                        <td >{{ $loop -> index + 1 }}</td>
                        <td >{{ $item -> municipio }}</td>
                        <td >{{ $item -> sigla }}</td>
                        <td >{{ $respostas -> where('municipio', $item -> id_redec) ->count() }} de {{$questoes -> count()}}</td>
                        <td ><a href="{{route('diagnose.municipio', ['idm' => $item -> id_redec, 'ide' => $edicao -> id])}}"><img src="{{asset('storage/icones/pasta.png')}}" width="20px"></a></td>
                    </tr>
                @endforeach
            </table>    
        </div>
    @endif
@endsection