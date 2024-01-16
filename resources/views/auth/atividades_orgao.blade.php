@extends('auth.home')
@section('title', 'Atividades do órgão')

@section('conteudo')
    <div class="row">
        <div class="col-md-12">
            @if($atividades ->Count() > 0)
            <table style="text-align: center; font-size:12px" border="0" cellpadding="2" cellspacing="0">
                <tr>
                    <td colspan="9" align="center"><strong>ATIVIDADES - {{($orgao -> sigla)}}</td>
                </tr>

                <tr>
                    <td bgcolor="#999999" ><strong>N°</strong></td>
                    <td bgcolor="#999999" ><strong>Data Inicio</strong></td>
                    <td bgcolor="#999999" ><strong>Tipo de Atividade</strong></td>
                    <td bgcolor="#999999" ><strong>Título</strong></td>
                    <td bgcolor="#999999" ><strong>Órgão</strong></td>
                    <td bgcolor="#999999" ><strong>Município(s)</strong></td>
                    <td bgcolor="#999999" ><strong>Ciclo</strong></td>
                    <td bgcolor="#999999" ><strong>Autoria</strong></td>
                    <td bgcolor="#999999" ><strong>Resumo</strong></td>
                </tr>
                @foreach ($atividades as $item)
                <tr @if($loop -> even) bgcolor="#CCCCCCD" @endif>
                    <td>{{ $loop -> index + 1 }}</td>
                    <td>{{ date('d/m/Y H:i:s', strtotime($item -> data_inicio)) }}</td>
                    <td>{{ $item -> TipoAtividade['atividade'] }}</td>
                    <td>{{ $item -> titulo }}</td>
                    <td>{{ $item -> Orgao['sigla'] }}</td>
                    <td>{{ $item -> municipio }}</td>
                    <td>{{ $item -> ciclo }}</td>
                    <td>{{ $item -> autoria }}</td>
                    <td ><a href="#" onclick="javascript:window.open('{{route('atv_dc.download', ['id' => $item -> id_atv])}}','','scrollbars=yes,resizable=yes,width=700,height=500');return false;" ><img src="{{asset('storage/icones/download.png')}}" width="20px"></a></td>
                </tr> 
                @endforeach
            </table>
            @else
            <strong>Não há Atividades para {{$orgao -> sigla}}!</strong>
            @endif
        </div>
    </div>
@endsection