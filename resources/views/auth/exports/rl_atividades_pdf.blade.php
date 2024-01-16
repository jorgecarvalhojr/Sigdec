@extends('layouts.P_pdf')
@section('title', 'Atividades')

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

    div {
        width: 100%;
        font-size: 14px;
        line-height: 15px;
    }

    </style> 
@endpush
@section('conteudo')

    <div class="inicio">
        <table width="100%" style="text-align: center; font-size:10px" border="0" cellpadding="2" cellspacing="0">
            <tr>
            <td colspan="9"><strong>ATIVIDADES REFERENTES AO PERÍODO ENTRE {{$data_inicial}} e {{$data_final}} - ÓRGÃO: {{$orgao}}</strong> </td>
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
            @php $dados = ""; @endphp
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

                @if($item -> op_viatura == "Sim")
                    @php
                        $viatura = "";
                        foreach($viaturas->where('id_atv', $item -> id_atv) as $linha){
                            $viatura .= "<span>Viatura Utilizada:  ".$linha -> Viatura['prefixo']." / Distância Percorrida:  ".$linha -> kilometragem." Km</span><br>";
                        }
                    @endphp
                @endif
                @php
                $dados .= "<div>
                <span style=\"text-align: center\" ><strong>".$item -> Orgao['sigla']."</strong></span><br>
                <span style=\"text-align: center\" ><strong>".$item -> TipoAtividade['atividade']."</strong></span><br>
                <span style=\"text-align: center\" ><strong>".$item -> titulo."</strong></span><br>
                </div><br>
                <div>
                <span><strong>*O órgão atuou como ".$item -> autoria." da atividade</strong></span><br>	
                <span>Data de início da atividade: ".date('d/m/Y H:i:s', strtotime($item -> data_inicio))."</span><br>
                <span>Data de término da atividade: ".($item -> data_fim != '' ? date('d/m/Y H:i:s', strtotime($item -> data_fim)) : 'Não registrada')."</span><br>
                <span>Ciclo de Atuação: ".$item -> ciclo."</span><br>
                <span>Município(s) abrangido(s): ".$item -> municipio."</span><br>";
                if($item ->op_viatura == "Sim") {$dados.= $viatura;}
                if($item -> se=="Sim"){$dados .= "<span>Situação de Emergência: ".$item -> se ."- Número do Decreto: ".$item -> num_se."</span><br>";}
                if($item -> ecp=="Sim"){$dados .= "<span>Estado de Calamidade Pública: ".$item -> ecp." - Número do Decreto: ".$item -> num_ecp."</span><br>";}
                if($item -> fide=="Sim"){$dados .= "<span>FIDE: ".$item -> fide ."- Número do FIDE: ".$item -> num_fide." - Cobrade: ".$item -> Cobrade['final']."</span><br>";}
                $dados .= "
                </div><br>
                <div>
                <span style=\"text-align: center\"><strong>Descrição da Atividade:</strong></span><br>
                <span>&nbsp;</span><br>
                <span>&nbsp;</span><br>
                <span style=\"text-align: justify\"> ".$item -> relato."</span><br>
                </div><br>";
                if($item -> arquivos != ""){ 
                    $cont2=0;
                    $path = 'storage/relatorio_foto/'.$item -> id_atv.'_'.substr($item -> data_cadastro, 0, 4).'/';
                    $fotos = explode("; ", $item -> arquivos);
                    $dados .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\">
                    <tr><td colspan=\"2\" align=\"center\"><strong>Relatório Fotográfico:</strong></td></tr>
                    <tr><td colspan=\"2\" >&nbsp;</td></tr>";
                    foreach($fotos as $teste){
                        $link= $path.$teste;
                        if($cont2==0){ $dados.= "<tr>";}
                        $cont2++;
                        $dados .= "<td align=\"center\" width=\"300px\"><img src=\"".public_path($link)."\" style=\"max-width: 280px\" width=\"auto\" />
                            </td>";
                        if($cont2==2){
                            $dados .= "</tr><tr>";
                        }
                        if($cont2==4){
                            $cont2=0;
                            $dados .= "</tr>";
                        }
                    }
                    $dados .= "</table>";
                }
                @endphp
            @endforeach
        </table>
    </div>
    <div class="inicio">{!!$dados!!}</div>
@endsection