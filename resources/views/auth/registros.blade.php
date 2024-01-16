@extends('auth.home')
@section('title', 'Registros')

@section('conteudo')
    <div class="row">
        <div class="col-md-12">
            <table style="text-align: center; font-size:12px;" border="0" cellpadding="1" >
                <tr>
                    <td colspan="3" align="center"><strong>REGISTROS DE ATIVIDADES</td>
                </tr>
                <tr>
                    <td bgcolor="#999999" ><strong>N°</strong></td>
                    <td bgcolor="#999999" ><strong>Órgão</strong></td>
                    <td bgcolor="#999999" ><strong>Listar</strong></td>
                </tr>
                @foreach ($orgaos as $item)
                <tr @if($loop -> even) bgcolor="#CCCCCCD" @endif>
                    <td>{{ $loop -> index + 1 }}</td>
                    <td>{{ $item -> descricao }} / {{ $item -> sigla }}</td>
                    <td ><a href="{{route('registros.show', ['id' => $item -> id_orgao])}}" ><img src="{{asset('storage/icones/visualizar.png')}}" width="20px"></a></td>
                </tr> 
                @endforeach
            </table>
        </div>
    </div>
@endsection