@extends('auth.diagnose_home')
@section('title', $municipio-> municipio)

@section('conteudo')
       
    <div class="col-md-10">
        <table style="font-size: 12px" border="1" cellpadding="2px" cellspacing="0" width="100%">
            <tr><td align="center" colspan="5"><strong>RELATÓRIO DIAGNOSE - {{mb_strtoUpper($municipio -> municipio)}} - EDIÇÃO {{$edicao -> ano}} - GRUPO: {{$grupo -> grupo}}</strong>  <button id="voltar" type="button" onclick="javascript:window.location='{{route('diagnose.municipio', ['idm' => $municipio -> id_redec, 'ide' => $edicao -> id])}}'" class="btn btn-primary">Voltar</button></td></tr>
            <tr align="center" bgcolor="#CCCCCCC">
                <td>N°</td>
                <td>Questão</td>
                <td>Responder</td>
            </tr>
            @foreach ($questoes as $item)
                <tr align="center" bgcolor="{{($respostas -> where('municipio', $municipio -> id_redec) -> where('id_edicao', $edicao -> id)->where('id_questao', $item ->id)->count() > 0 ? 'lightgreen' : 'white')}}" >
                    <td >{{ $loop -> index + 1 }}</td>
                    <td >{!! $item -> questao !!}</td>
                    @if($edicao -> ativo == '1' || Auth::user() -> acesso == '2')
                        <td ><a href="{{route('diagnose.responder', ['idm' => $municipio -> id_redec, 'ide' => $edicao -> id, 'idq' => $item -> id])}}"><img src="{{asset('storage/icones/pasta.png')}}" width="20px"></a></td>
                    @else
                        <td ><a href="#"><img src="{{asset('storage/icones/parar.png')}}" width="20px"></a></td>
                    @endif
                </tr>
            @endforeach
        </table>    
    </div>
@endsection