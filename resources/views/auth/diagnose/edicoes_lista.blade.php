@extends('auth.diagnose_home')
@section('title', 'Edições Diagnose')

@section('conteudo')
    @if ($edicoes->count() > 0)
        
        <div class="col-md-6">
            <table style="font-size: 12px" border="1" cellpadding="2px" cellspacing="0" width="100%">
                <tr align="center" bgcolor="#CCCCCCC">
                    <td>Ano</td>
                    <td>Data Início</td>
                    <td>Data Término</td>
                    <td>Aceitando Respostas</td>
                    <td>Entrar</td>
                </tr>
                @foreach ($edicoes as $item)
                    <tr align="center" @if($loop -> even) bgcolor="#CCCCCCD" @endif>
                        <td >{{ $item -> ano }}</td>
                        <td >{{ date('d/m/Y', strtotime($item -> data_inicio)) }}</td>
                        <td >{{ ($item -> data_fim != '' ? date('d/m/Y', strtotime($item -> data_fim)) : '') }}</td>
                        <td @if($item -> ativo == '0')  style="color: red" @elseif($item -> ativo == '1')  style="color: green" @endif >{{ ($item -> ativo == '0' ? 'Não' : ($item -> ativo == '1' ? 'Sim' : 'Não especificado')) }}</td>
                        <td ><a href="{{route('diagnose.municipios', ['id' => $item-> id])}}"><img src="{{asset('storage/icones/pasta.png')}}" width="20px"></a></td>
                    </tr>
                @endforeach
            </table>    
        </div>
    @endif
@endsection