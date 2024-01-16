@extends('auth.diagnose_home')
@section('title', 'Resposta de questão')

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
function Habilitar(comentar,ordem, tipo, total) {
    var box = "opcao"+ordem;
    var campo = "comentar"+ordem;
    if(tipo == 1){
        if($("#"+box).is(":checked")){
            if(comentar == 1){
                $("#"+campo).prop("disabled", false);
                $("#"+campo).focus();
            }
        }else{
            $("#"+campo).prop("disabled", true);
        }
    }
    if(tipo == 0){
        if($("#"+box).is(":checked")){
            if(comentar == 1){
                $("#"+campo).prop("disabled", false);
                $("#"+campo).focus();
            }
            for (var i=1; i <= total; i++){//Percorre os radios
                if(i != ordem){
                   $("#"+"comentar"+i).prop("disabled", true);
                }
            }
        }
    }
}
//**************************************************************************************
</script>
@endpush

@section('conteudo')

    <div class="row container">
        <div id="formulario" class="col-md-10">
            <div class="form-group">
                <h4>RELATÓRIO DIAGNOSE {{$edicao -> ano}} - ({{mb_strtoUpper($municipio -> municipio)}}) - GRUPO: {{$questao -> Grupo['grupo']}}</h4>
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
            <form id="resposta" method="POST" action="@if ($resposta -> count() == 0) {{route('diagnose.create')}} @else {{route('diagnose.edit', ['id' => $resposta[0] -> id])}} @endif ">
            @csrf

                <input type="hidden" name="id_questao" id="id_questao" value="{{$questao -> id}}">	
                <input type="hidden" name="id_edicao" id="id_edicao" value="{{$edicao -> id}}">	
                <input type="hidden" name="municipio" id="municipio" value="{{$municipio -> id_redec}}">	
                <input type="hidden" name="id_grupo" id="id_grupo" value="{{$questao -> id_grupo}}">	
    
                <div class="form-group row">
                    <div class="col-md-12">
                        <span style="text-align: justify; font-size:16px; font-weight:bold">{!! $questao -> questao !!}</span>
                    </div>
                </div>

                {{-- @foreach ($errors->all() as $error)
                    {{$error}}<br>  <!-- imprimir os erros de validação caso haja algum, serão enviados pelo Validator -->
                @endforeach --}}


                <div class="form-group row">
                    <div class="col-md-6">
                        @error('opcao')
                        <div class="text-danger">
                            {{ $message }}
                        </div>  
                        @enderror
                    </div>
                </div>
                @php
                    $checked = null;
                    $disable = "disabled";
                    $comentario = null;
                @endphp
                @foreach($opcoes as $item)
                    @if($resposta -> count() > 0 && $resposta[0] -> respostas != "")
                        @php
                            $resp = explode(',', $resposta[0] -> respostas);
                        @endphp
                        @foreach($resp as $teste)
                            @if($teste == $item -> id)
                                @php
                                    $checked = "checked";
                                @endphp
                                @if($item -> comentar == "1")
                                    @php
                                        $disable = null;
                                    @endphp
                                    @if($resposta[0] -> comentario != "")
                                        @php
                                            $coment = explode(';', $resposta[0] -> comentario);
                                        @endphp
                                        @foreach($coment as $test)
                                            @php
                                                $cod = explode(',', $test);
                                            @endphp
                                            @if($cod[0] == $item -> ordem)
                                                @php
                                                    $comentario = $cod[1];
                                                    break;
                                                @endphp
                                            @endif
                                        @endforeach
                                    @endif
                                @endif
                            @endif
                        @endforeach
                    @endif
                    <div class="form-group row ">
                        @if($questao -> tipo == 1) 
                            <div class="col-md-1">
                                <input {{(is_array(old('opcao')) && in_array($item -> id, old('opcao')) ? ' checked' : $checked)}} type="checkbox" name="opcao[]" onclick="Habilitar({{$item -> comentar}},{{$item -> ordem}},{{$questao -> tipo}},{{$opcoes -> count()}})" id="opcao{{$item -> ordem}}" value="{{$item -> id}}"> 
                            </div>
                        @else
                            <div class="col-md-1">
                                <input {{(is_array(old('opcao')) && in_array($item -> id, old('opcao')) ? ' checked' : $checked)}} type="radio" name="opcao[]" onclick="Habilitar({{$item -> comentar}},{{$item -> ordem}},{{$questao -> tipo}},{{$opcoes -> count()}})" id="opcao{{$item -> ordem}}" value="{{$item -> id}}"> 
                            </div>
                        @endif
                        <div style="text-align: justify" class="col-md-5">{{$item -> opcao}}</div>
                        <div class="col-md-6">
                            <input class="form-control" type="text" name="comentar{{$item -> ordem}}" id="comentar{{$item -> ordem}}" value="{{($item -> comentar == "1" ? old('comentar'.$item -> ordem, $comentario) : '')}}" {{$disable}}  onmouseover="Habilitar({{$item -> comentar}},{{$item -> ordem}},{{$questao -> tipo}},{{$opcoes -> count()}})">
                            @error('comentar'.$item ->ordem)
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                            @enderror
                        </div>
                    </div>
                    @php
                        $checked = null;
                        $disable = "disabled";
                        $comentario = null;
                    @endphp
                @endforeach

                <div class="form-group row">        
                    <div class="col-md-6">
                        <button id="enviar" type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                    <div class="col-md-6">
                        <button id="voltar" type="button" onclick="javascript:window.location='{{route('diagnose.questoes', ['ide' => $edicao -> id, 'idm' => $municipio -> id_redec, 'idg' => $questao -> id_grupo])}}'" class="btn btn-primary">Voltar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection