@extends('auth.home')
@section('title', 'Usuários')

@if(Auth::user() -> acesso == 1) @php $users = $users -> where('orgao', Auth::user() -> orgao) @endphp @endif

@section('conteudo')
    <div class="row">
        <div class="col-md-12">
            <table style="text-align: center" cellpadding="2" cellspacing="0">
                <tr>
                    <td colspan="9" align="center">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-dismissible show" role="alert">
                            <strong>{!!$message!!}</strong> 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                        @endif
            
                        @if ($message = Session::get('error'))
                            <div class="alert alert-danger alert-dismissible show" role="alert">
                            <strong>{!!$message!!}</strong> 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                        @endif
            
                        @if ($message = Session::get('warning'))
                            <div class="alert alert-warning alert-dismissible show" role="alert">
                            <strong>{!!$message!!}</strong> 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="9" align="center"><strong>USUÁRIOS DO SIGDEC</strong></td>
                </tr>

                <tr>
                    <td colspan="9">
                        Total de usuários: {{count($users)}}
                    </td>
                </tr>
                <tr bgcolor="#999999" align="center" border="2">
                    <td ><strong>N°</strong></td>
                    <td ><strong>Nome</strong></td>
                    <td ><strong>Órgão</strong></td>
                    <td ><strong>Permissão</strong></td>
                    <td ><strong>Acesso</strong></td>
                    <td ><strong>Ativo</strong></td>
                    <td align="center"><strong>Editar</strong></td>
                </tr>
                @foreach ($users as $item)
                <tr @if($loop -> even) bgcolor="#CCCCCCD" @endif>
                    <td>{{ $loop -> index + 1 }}</td>
                    <td>{{ mb_strtoupper($item -> nome) }}</td>
                    <td>{{ $item -> Orgao['sigla'] }}</td>
                    <td>{{ ($item -> permissao == '1' ? "Usuário" : "Administrador")}} </td>
                    <td>{{ ($item -> acesso == '1' ? "Básico" : "Gestor")}} </td>
                    <td @if($item -> ativo == 'Não') style="color: red" @endif > {{$item -> ativo}} </td>
                    <td> @if(Auth::user()-> permissao == '1') <img src="{{asset('storage/icones/parar.png')}}" width="20px"> @else <a href="{{route('user.edit', ['id' => $item-> indice_adm])}}"><img src="{{asset('storage/icones/editar.png')}}" width="20px"></a> @endif </td>
                @endforeach
            </table>
        </div>
    </div>
@endsection

