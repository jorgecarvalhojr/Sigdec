@extends('auth.home')
@section('title', 'Estatísticas de atividades')

@section('conteudo')
    <div class="row">
        <div class="col-md-4">
            <table style="text-align: center" border="0" cellpadding="2" cellspacing="0">
                <tr>
                <td colspan="2"><strong>CICLO DE ATIVIDADES REFERENTES AO PERÍODO ENTRE {{$data_inicial}} e {{$data_final}} - ÓRGÃO: {{$nome_orgao}}</strong></td>
                </tr>
                <tr>
                    <td bgcolor="#999999" style="border:solid; border-width:1px; border-color:#000; "><strong>Ciclo de Atividade</strong></td>
                    <td bgcolor="#999999" style="border:solid; border-width:1px; border-color:#000; "><strong>Quantidade</strong></td>
                </tr>
                @php 
                    $total = 0;
                    $ano = substr($data_inicial, 6, 4);
                @endphp
                @foreach($atividades as $item)
                    @php $total += $item -> quantidade; @endphp
                    <tr @if($loop -> even) bgcolor="#CCCCCCD" @endif>
                        <td><strong>{{$item -> ciclo}}</strong></td>
                        <td><strong>{{$item -> quantidade}}</strong></td>
                    </tr>
                @endforeach
                <tr>
                    <td><strong>TOTAL</strong></td>    
                    <td><strong>{{$total}}</strong></td> 
                </tr>
            </table>
        </div>
        <div class="col-md-8">
            <img src="{!!$grafico1!!}">
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table width="100%" style="text-align: center" border="0" cellpadding="2" cellspacing="0">
                <tr>
                    <td colspan="14" align="center"><strong>CICLO DE ATIVIDADES REFERENTES AO ANO DE {{$ano}} - ÓRGÃO: {{$nome_orgao}}</strong> <a href="{{route('export_ciclo', ['data_inicial' => \str_replace('/', '-', $data_inicial), 'data_final' => \str_replace('/', '-', $data_final), 'orgao' => $orgao])}}"><img src="{{asset('storage/icones/excel.png')}}" width="30px"></a></td>
                </tr>

                <tr>
                    <td bgcolor="#999999" style="border:solid; border-width:1px; border-color:#000; "><strong>Ciclo</strong></td>
                    <td bgcolor="#999999" style="border:solid; border-width:1px; border-color:#000; "><strong>Jan</strong></td>
                    <td bgcolor="#999999" style="border:solid; border-width:1px; border-color:#000; "><strong>Fev</strong></td>
                    <td bgcolor="#999999" style="border:solid; border-width:1px; border-color:#000; "><strong>Mar</strong></td>
                    <td bgcolor="#999999" style="border:solid; border-width:1px; border-color:#000; "><strong>Abr</strong></td>
                    <td bgcolor="#999999" style="border:solid; border-width:1px; border-color:#000; "><strong>Mai</strong></td>
                    <td bgcolor="#999999" style="border:solid; border-width:1px; border-color:#000; "><strong>Jun</strong></td>
                    <td bgcolor="#999999" style="border:solid; border-width:1px; border-color:#000; "><strong>Jul</strong></td>
                    <td bgcolor="#999999" style="border:solid; border-width:1px; border-color:#000; "><strong>Ago</strong></td>
                    <td bgcolor="#999999" style="border:solid; border-width:1px; border-color:#000; "><strong>Set</strong></td>
                    <td bgcolor="#999999" style="border:solid; border-width:1px; border-color:#000; "><strong>Out</strong></td>
                    <td bgcolor="#999999" style="border:solid; border-width:1px; border-color:#000; "><strong>Nov</strong></td>
                    <td bgcolor="#999999" style="border:solid; border-width:1px; border-color:#000; "><strong>Dez</strong></td>
                    <td bgcolor="#999999" style="border:solid; border-width:1px; border-color:#000; "><strong>Total</strong></td>
                </tr>

                @foreach ($anual as $item)
                    <tr @if($loop -> even) bgcolor="#CCCCCCD" @endif>
                        <td><strong>{{ $item['ciclo'] }}</strong></td>
                        <td>{{$item['janeiro']}}</td>
                        <td>{{$item['fevereiro']}}</td>
                        <td>{{$item['marco']}}</td>
                        <td>{{$item['abril']}}</td>
                        <td>{{$item['maio']}}</td>
                        <td>{{$item['junho']}}</td>
                        <td>{{$item['julho']}}</td>
                        <td>{{$item['agosto']}}</td>
                        <td>{{$item['setembro']}}</td>
                        <td>{{$item['outubro']}}</td>
                        <td>{{$item['novembro']}}</td>
                        <td>{{$item['dezembro']}}</td>
                        <td><strong>{{$item['total'] }}</strong></td>
                    </tr> 
                @endforeach
            </table>
        </div>
    </div>

@endsection

