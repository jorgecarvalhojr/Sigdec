@extends('auth.home')
@section('title', 'Relatório de atividades')

@section('conteudo')
    <div class="row">
        <div class="col-md-12">
            <table width="100%" style="text-align: center; font-size:10px" border="0" cellpadding="2" cellspacing="0">
                <tr>
                <td colspan="9"><strong>ATIVIDADES REFERENTES AO PERÍODO ENTRE {{$data_inicial}} e {{$data_final}} - ÓRGÃO: {{$nome_orgao}}</strong> <a href="{{route('export_xls', ['data_inicial' => \str_replace('/', '-', $data_inicial), 'data_final' => \str_replace('/', '-', $data_final), 'orgao' => $orgao])}}"><img src="{{asset('storage/icones/excel.png')}}" width="30px"></a> <a href="#" onclick="javascript:window.open('{{route('export_pdf', ['data_inicial' => \str_replace('/', '-', $data_inicial), 'data_final' => \str_replace('/', '-', $data_final), 'orgao' => $orgao])}}','','scrollbars=yes,resizable=yes,width=700,height=500');return false;" ><img src="{{asset('storage/icones/pdf.png')}}" width="30px"></a></td>
                </tr>
                <tr>
                    <td bgcolor="#999999" ><strong>N°</strong></td>
                    <td bgcolor="#999999" ><strong>Data Inicio</strong></td>
                    <td bgcolor="#999999" ><strong>Data Fim</strong></td>
                    <td bgcolor="#999999" ><strong>Tipo de Atividade</strong></td>
                    <td bgcolor="#999999" ><strong>Órgão</strong></td>
                    <td bgcolor="#999999" ><strong>Município(s)</strong></td>
                    <td bgcolor="#999999" ><strong>Ciclo</strong></td>
                    <td bgcolor="#999999" ><strong>Autoria</strong></td>
                    <td bgcolor="#999999" ><strong>Relatório Fotográfico</strong></td>
                </tr>
                @foreach($atividades as $item)
                    <tr @if($loop -> even) bgcolor="#CCCCCCD" @endif>
                        <td> {{ $loop -> index + 1 }} </td>
                        <td> {{ date('d/m/Y H:i:s', strtotime($item -> data_inicio)) }} </td>
                        <td> {{ ($item -> data_fim != '' ? date('d/m/Y H:i:s', strtotime($item -> data_fim)) : '') }} </td>
                        <td> {{ $item -> TipoAtividade -> atividade }} </td>
                        <td> {{ $item -> Orgao['sigla'] }} </td>
                        <td> {{ $item -> municipio }} </td>
                        <td> {{ $item -> ciclo }} </td>
                        <td> {{ $item -> autoria }} </td>
                        <td> {{ $item -> relatorio_foto }} </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

@endsection

