@extends('layouts.P_pdf')
@section('title', 'Atividade Interna')

@push('css')
<style type="text/css"> 
    .folha { 
        page-break-before:always; 
        width:100%; 
        height:855px;
    }
    
    .inicio {
        width:100%; 
        height:855px;
    }

    table {
        width: 100%;
        font-size: 14px;
        line-height: 15px;
    }
    </style> 
@endpush
@section('conteudo')

    <div class="inicio">
        <table border="1" cellspacing="0" cellpadding="0" >
            <tr>
                <td colspan="2" bgcolor="#CCCCCC" align="center"><strong>{{ $atv -> TipoAtividade['atividade'] }} - {{ $atv -> titulo }} </strong></td>
            </tr>
        </table><br>

        <table style="line-height: 20px" border="1" cellspacing="0" cellpadding="0" >
            <tr><td><strong>* @if(($atv -> autoria == "PROMOTOR" ? "O órgão atuou como promotor da atividade." : "O órgão atuaou como participante da atividade promovida por {{$atv -> Promotor['sigla']}}."))@endif</strong></td></tr>	
            <tr><td>Data de início do relatório: {{date('d/m/Y', strtotime($atv -> data_inicio))}}</td></tr>
            <tr><td>Data de término do relatório: {{($atv -> data_fim != '' ? date('d/m/Y', strtotime($atv -> data_fim)) : 'Aguardando Atualização')}}</td></tr>
            <tr><td>Município(s): {{ $atv -> municipio}} </td></tr>
            <tr><td>Ciclo: {{ $atv -> ciclo }} </td></tr>
            @if($viaturas -> Count() > 0)
                @foreach($viaturas as $item)
                    <tr><td>Viatura Utilizada: {{$item -> Viatura['prefixo']}} - {{$item -> Viatura['tipo']}} - Placa: {{$item -> Viatura['placa']}} </td></tr>
                @endforeach
            @endif
            <tr><td>Situação de Emergência: {{($atv -> se == "Sim" ? "Sim - Número do Decreto: ".$atv -> num_se : "Não")}}</td></tr>
            <tr><td>Estado de Calamidade Pública: {{($atv -> ecp =="Sim" ? "Sim - Número do Decreto:".$atv -> num_ecp : "Não")}}</td></tr>
            <tr><td>FIDE: {{($atv -> fide == "Sim" ? "Sim - Número do FIDE: ".$atv -> num_fide." - Cobrade: ".$atv -> cobrade." - ".$atv -> Cobrade['final'] : "Não")}}</td></tr>
            <tr><td>Responsável pelo Registro: {{$atv -> User['nome']}}</td></tr>
        </table><br>

        <table border="0" cellspacing="0" cellpadding="0" width="100%" height="90px">
            <tr><td style="font-size: 16px;" align="center"><strong>Descrição da Atividade:</strong></td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td align="justify">{!!$atv -> relato!!}</td></tr>
        </table>

        @if($atv -> arquivos != "")
            @php 
                $cont=0;
                $fotos = explode("; ", $atv -> arquivos);
            @endphp

            <table border="0" cellspacing="0" cellpadding="3" width="100%" height="90px">
                <tr><td colspan="2" style="font-size: 16px;" align="center"><strong>Relatóio Fotográfico:</strong></td></tr>
                <tr><td>&nbsp;</td></tr>
                @foreach($fotos as $teste)
                    @php
                        $link='storage/relatorio_foto/'.$atv -> id_atv.'_'.substr($atv-> data_cadastro, 0, 4).'/'.$teste;
                        if($cont==0){ echo "<tr>";}
                        $cont++;
                    @endphp
                    <td align="center" width="48%"><img src="{{public_path($link)}}" style="max-width: 80%" width="auto" /></td>
                    @php 
                        if($cont==2){
                            echo "</tr><tr>";
                        }
                        if($cont==4){
                            $cont=0;
                            echo "</tr>";
                        }
                    @endphp
                @endforeach
            </table>
        @endif
    </div>
@endsection