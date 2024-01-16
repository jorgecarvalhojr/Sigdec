@extends('auth.diagnose_home')
@section('title', 'Grupos de questões')

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
                <h3>CADASTRO DE GRUPOS DE QUESTÕES</h3>
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
            <form id="grupos" method="POST" action="@if ($grupo -> id == 0) {{route($rota)}} @else {{route($rota, ['id' => $grupo -> id])}} @endif ">
            @csrf

                <div class="form-group row">
                    <div class="col-md-8">
                        <strong>Nome do grupo:</strong>
                        <input class="form-control" type="text" name="grupo" id="grupo" value="{{old('grupo', $grupo -> grupo)}}" required>
                        @error('grupo')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <strong>Ordem:</strong>
                        <input class="number form-control" type="text" name="ordem" id="ordem" value="{{old('ordem', $grupo -> ordem)}}" maxlength="2" required>
                        @error('ordem')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <strong>Ativo?</strong>
                        <select class="form-control" id="ativo" name="ativo" required>
                            <option value=""></option>
                            <option {{ (old('ativo', $grupo->ativo) == '1' ? 'selected' : '') }} value="1">Sim</option>
                            <option {{ (old('ativo', $grupo->ativo) == '0' ? 'selected' : '') }} value="0">Não</option>
                        </select>
                        @error('ativo')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror
                    </div>

                </div>

                <div class="form-group row">        
                    <div class="col-md-12">
                        <button id="enviar" type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </div>
    
            </form>
        </div>
        @if ($grupos->count() > 0)
            
            <div style="max-height: 70vh; overflow:auto; " class="col-md-6">
                <table style="font-size: 12px" border="1" cellpadding="2px" cellspacing="0" width="100%">
                    <tr align="center" bgcolor="#CCCCCCC">
                        <td>N°</td>
                        <td>Capítulo/Grupo</td>
                        <td>Ativo</td>
                        <td>Editar</td>
                    </tr>
                    @foreach ($grupos as $item)
                        <tr align="center" @if($loop -> even) bgcolor="#CCCCCCD" @endif>
                            <td >{{ $loop -> index + 1 }}</td>
                            <td >{{ $item -> grupo }}</td>
                            <td @if($item -> ativo == '0')  style="color: red" @elseif($item -> ativo == '1')  style="color: green" @endif >{{ ($item -> ativo == '0' ? 'Não' : ($item -> ativo == '1' ? 'Sim' : 'Não especificado')) }}</td>
                            <td ><a href="{{route('d.grupo.edit', ['id' => $item-> id])}}"><img src="{{asset('storage/icones/editar.png')}}" width="20px"></a></td>
                        </tr>
                    @endforeach
                </table>    
            </div>
                   
        @endif
    </div>
@endsection