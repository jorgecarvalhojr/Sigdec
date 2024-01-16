@extends('auth.diagnose_home')
@section('title', $municipio-> municipio)

@section('conteudo')
       
    <div class="col-md-6">
        <table style="font-size: 12px" border="1" cellpadding="2px" cellspacing="0" width="100%">
            <tr><td align="center" colspan="5"><strong>RELATÓRIO DIAGNOSE - {{mb_strtoUpper($municipio -> municipio)}} - EDIÇÃO {{$edicao -> ano}}</strong> <button id="voltar" type="button" onclick="javascript:window.location='{{route('diagnose.municipios', ['id' => $edicao -> id])}}'" class="btn btn-primary">Voltar</button></td></tr>
            <tr align="center" bgcolor="#CCCCCCC">
                <td>N°</td>
                <td>Grupo de Questões</td>
                <td>Questões Respondidas</td>
                <td>Entrar</td>
            </tr>
            @foreach ($grupos as $item)
                <tr align="center" @if($loop -> even) bgcolor="#CCCCCCD" @endif>
                    <td >{{ $loop -> index + 1 }}</td>
                    <td >{{ $item -> grupo }}</td>
                    <td >{{ $respostas -> where('municipio', $municipio -> id_redec)->where('id_grupo', $item -> id) ->count() }} de {{$questoes ->where('id_grupo', $item -> id)-> count()}}</td>
                    <td ><a href="{{route('diagnose.questoes', ['idm' => $municipio -> id_redec, 'ide' => $edicao -> id, 'idg' => $item -> id])}}"><img src="{{asset('storage/icones/pasta.png')}}" width="20px"></a></td>
                </tr>
            @endforeach
        </table>    
    </div>
@endsection