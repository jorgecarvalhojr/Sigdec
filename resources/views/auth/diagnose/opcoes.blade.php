@extends('auth.diagnose_home')
@section('title', 'Opções de Questões')

@push('script')
<script src="{{ asset ('js/jquery-3.2.1.js') }}"></script>

<script>
//************************************************************************************
function apagaForm() {
	let inputs = document.querySelectorAll('.form-control');
     for (let i = 0; i < inputs.length; i++) {
       inputs[i].value = '';
     }
}
//**************************************************************************************
jQuery(function($) {
  $(document).on('keypress', 'input.number', function(e) {
	var key = (window.event)?event.keyCode:e.which;
	if((key > 47 && key < 58)) {
		return true;
  	} else {
 		return (key == 8 || key == 0)?true:false;
 	}
  });
});
//**************************************************************************************
</script>
@endpush

@section('conteudo')

    <div class="row container">
        <div id="formulario" class="col-md-6">
            <div class="form-group">
                <h3>CADASTRO DE OPÇÕES DE QUESTÃO</h3>
            </div>

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
            <form id="opcoes" method="POST" action="@if ($opcao -> id == 0) {{route($rota,['id' => $opcao -> id_questao])}} @else {{route($rota, ['id' => $opcao -> id])}} @endif ">
            @csrf

                <div class="form-group row">
                    <div class="col-md-12">
                        <strong>{!!$opcao -> Questao['questao']!!}</strong>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <strong>Opção:</strong>
                        <input class="form-control" type="text" name="opcao" id="opcao" value="{{old('opcao', $opcao -> opcao)}}" required>
                        @error('opcao')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3">
                        <strong>Ordem:</strong>
                        <input class="number form-control" type="text" name="ordem" id="ordem" value="{{old('ordem', $opcao -> ordem)}}" maxlength="2" required>
                        @error('ordem')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <strong>Score:</strong>
                        <input class="number form-control" type="text" name="score" id="score" value="{{old('score', $opcao -> score)}}" maxlength="2" required>
                        @error('score')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <strong>Ativo?</strong>
                        <select class="form-control" id="ativo" name="ativo" required>
                            <option value=""></option>
                            <option {{ (old('ativo', $opcao->ativo) == '1' ? 'selected' : '') }} value="1">Sim</option>
                            <option {{ (old('ativo', $opcao->ativo) == '0' ? 'selected' : '') }} value="0">Não</option>
                        </select>
                        @error('ativo')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <strong>Requer Comentário?</strong>
                        <select class="form-control" id="comentar" name="comentar" required>
                            <option value=""></option>
                            <option {{ (old('comentar', $opcao->comentar) == '1' ? 'selected' : '') }} value="1">Sim</option>
                            <option {{ (old('comentar', $opcao->comentar) == '0' ? 'selected' : '') }} value="0">Não</option>
                        </select>
                        @error('comentar')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror
                    </div>
                </div>

                <div class="form-group row">        
                    <div class="col-md-6">
                        <button id="enviar" type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                    <div class="col-md-6">
                        <button id="voltar" type="button" onclick="javascript:window.location='{{route('d.questao.index')}}'" class="btn btn-primary">Voltar</button>
                    </div>
                </div>
    
            </form>
        </div>
        @if ($opcoes->count() > 0)
            
            <div style="max-height: 70vh; overflow:auto; " class="col-md-6">
                <table style="font-size: 12px" border="1" cellpadding="2px" cellspacing="0" width="100%">
                    <tr align="center" bgcolor="#CCCCCCC">
                        <td>N°</td>
                        <td>Opção</td>
                        <td>Score</td>
                        <td>Requer Comentário</td>
                        <td>Ativo</td>
                        <td>Editar</td>
                    </tr>
                    @foreach ($opcoes as $item)
                        <tr align="center" @if($loop -> even) bgcolor="#CCCCCCD" @endif>
                            <td >{{ $item-> ordem }}</td>
                            <td >{{ $item -> opcao }}</td>
                            <td >{{ $item -> score }}</td>
                            <td >{{ ($item -> comentar == '0' ? 'Não' : ($item -> comentar == '1' ? 'Sim' : 'Não especificado')) }}</td>
                            <td @if($item -> ativo == '0')  style="color: red" @elseif($item -> ativo == '1')  style="color: green" @endif >{{ ($item -> ativo == '0' ? 'Não' : ($item -> ativo == '1' ? 'Sim' : 'Não especificado')) }}</td>
                            <td ><a href="{{route('d.opcao.edit', ['id' => $item-> id])}}"><img src="{{asset('storage/icones/editar.png')}}" width="20px"></a></td>
                        </tr>
                    @endforeach
                </table>    
            </div>
                   
        @endif
    </div>
@endsection