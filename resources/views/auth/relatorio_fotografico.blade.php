<div class="row">
    <div class="col-md-12">
        @if($atv -> arquivos != '')
            @php
                $arquivos = explode('; ', $atv -> arquivos);
                $path = 'storage/relatorio_foto/'.$atv -> id_atv.'_'.substr($atv -> data_cadastro, 0, 4).'/';
            @endphp
            <table style="text-align: center; font-size: 12px" width="100%" cellpadding="2" cellSpacing="0" border="0">
                <tr>
                    <td colspan="7" align="center"><strong>Relatório Fotográfico - {{ $atv -> TipoAtividade['atividade'] }} - {{ $atv -> titulo }}</td>
                </tr>

                <tr bgcolor="#CCCCCC">
                    <td>N°</td>
                    <td>Foto</td>
                    <td>Excluir</td>
                </tr>
                @foreach ($arquivos as $item)
                    <tr @if($loop -> even) bgcolor="#CCCCCCD" @endif>
                        <td>{{ $loop -> index + 1}}</td>
                        <td><img src="{{asset($path.$item)}}" height="80px" /></td>
                        <td><a title="Excluir" href="{{route('atv_dc.delete', ['id' => $atv -> id_atv, 'img' => $item])}}" ><img src="{{asset('storage/icones/lixeira.png')}}" width="20px"> </a></td>
                    </tr>
                @endforeach
            </table>
        @else
            {{'Não há imagens cadastradas!'}}
        @endif
    </div>
</div>

