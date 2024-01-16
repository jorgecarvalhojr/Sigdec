@extends('auth.diagnose_home')
@section('title', 'Questões')

@push('script')
<script src="{{ asset ('js/jquery-3.2.1.js') }}"></script>

<script src="{{ asset ('js/tinymce/tinymce.min.js') }}"></script>
<script>
    
//***********************************************************************************************************
tinymce.init({
    selector: ".editavel",
	language : 'pt_BR',
    theme: "modern",
    width: '100%',
    height: 100,
    plugins: 
    [
      "advlist autolink link lists charmap print preview hr anchor pagebreak spellchecker",
      "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime nonbreaking",
      "save table contextmenu directionality emoticons template paste textcolor"
    ],
    content_css: "css/content.css",
    forced_root_block : false,
    toolbar: "undo redo | fontselect | fontsizeselect | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | print preview fullpage | forecolor backcolor emoticons | code", 
    tools:"inserttable",
    font_formats: "Andale Mono=andale mono,times;"+
      "Arial=arial,helvetica,sans-serif;"+
      "Arial Black=arial black,avant garde;"+
      "Book Antiqua=book antiqua,palatino;"+
      "Comic Sans MS=comic sans ms,sans-serif;"+
      "Courier New=courier new,courier;"+
      "Georgia=georgia,palatino;"+
      "Helvetica=helvetica;"+
      "Impact=impact,chicago;"+
      "Symbol=symbol;"+
      "Tahoma=tahoma,arial,helvetica,sans-serif;"+
      "Terminal=terminal,monaco;"+
      "Times New Roman=times new roman,times;"+
      "Trebuchet MS=trebuchet ms,geneva;"+
      "Verdana=verdana,geneva;"+
      "Webdings=webdings;"+
      "Wingdings=wingdings,zapf dingbats",
    fontsize_formats: "8pt 10pt 12pt 14pt 16pt 18pt 20pt 24pt 28pt 36pt",
    style_formats: [
      {title: "Headers", items: 
        [
          {title: "Header 1", format: "h1"},
          {title: "Header 2", format: "h2"},
          {title: "Header 3", format: "h3"},
          {title: "Header 4", format: "h4"},
          {title: "Header 5", format: "h5"},
          {title: "Header 6", format: "h6"}
        ]
      },
      {title: "Inline", items: 
        [
          {title: "Bold", icon: "bold", format: "bold"},
          {title: "Italic", icon: "italic", format: "italic"},
          {title: "Underline", icon: "underline", format: "underline"},
          {title: "Strikethrough", icon: "strikethrough", format: "strikethrough"},
          {title: "Superscript", icon: "superscript", format: "superscript"},
          {title: "Subscript", icon: "subscript", format: "subscript"},
          {title: "Code", icon: "code", format: "code"}
        ]
      },
	    {title: "Blocks", items: 
        [
          {title: "Paragraph", format: "p"},
          {title: "Blockquote", format: "blockquote"},
          {title: "Div", format: "div"},
          {title: "Pre", format: "pre"}
        ]
      },	
      {title: "Alignment", items: 
        [
          {title: "Left", icon: "alignleft", format: "alignleft"},
          {title: "Center", icon: "aligncenter", format: "aligncenter"},
          {title: "Right", icon: "alignright", format: "alignright"},
          {title: "Justify", icon: "alignjustify", format: "alignjustify"}
        ]
      }
    ]	
  }); 
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
                <h3>CADASTRO DE QUESTÕES</h3>
            </div>
            
            {{-- @foreach ($errors->all() as $error)
            {{$error}}<br>  <!-- imprimir os erros de validação caso haja algum, serão enviados pelo Validator -->
            @endforeach --}}

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
            <form id="questoes" method="POST" action="@if ($questao -> id == 0) {{route($rota)}} @else {{route($rota, ['id' => $questao -> id])}} @endif ">
            @csrf

                <div class="form-group row">
                    <div class="col-md-6">
                        <strong>Grupo da Questão:</strong>
                        <select class="form-control" name="id_grupo" id="id_grupo" required>
                            <option value=""></option>
                            @foreach($grupos as $item)
                                <option {{(old('id_grupo',$questao -> id_grupo) == $item -> id ? 'selected' : '')}} value="{{$item -> id}}">{{$item -> grupo}}</option>
                            @endforeach
                        </select>
                        @error('id_grupo')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <strong>Ordem:</strong>
                        <input class="number form-control" type="text" name="ordem" id="ordem" value="{{old('ordem', $questao -> ordem)}}" maxlength="3" required>
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
                            <option {{ (old('ativo', $questao->ativo) == '1' ? 'selected' : '') }} value="1">Sim</option>
                            <option {{ (old('ativo', $questao->ativo) == '0' ? 'selected' : '') }} value="0">Não</option>
                        </select>
                        @error('ativo')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <strong>Resposta:</strong>
                        <select class="form-control" id="tipo" name="tipo" required>
                            <option value=""></option>
                            <option {{ (old('tipo', $questao->tipo) == '1' ? 'selected' : '') }} value="1">Múltipla</option>
                            <option {{ (old('tipo', $questao->tipo) == '0' ? 'selected' : '') }} value="0">Única</option>
                        </select>
                        @error('tipo')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror
                    </div>

                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <strong>Questão:</strong>
                        <textarea class="form-control editavel" rows="5" wrap="off" name="questao" id="questao">{{old('questao', $questao -> questao)}}</textarea>
                    </div>
                </div>

                <div class="form-group row">        
                    <div class="col-md-12">
                        <button id="enviar" type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </div>
    
            </form>
        </div>
        @if ($questoes->count() > 0)
            
            <div style="max-height: 70vh; overflow:auto; " class="col-md-6">
                <table style="font-size: 12px" border="1" cellpadding="2px" cellspacing="0" width="100%">
                    <tr align="center" bgcolor="#CCCCCCC">
                        <td>N°</td>
                        <td>Grupo</td>
                        <td>Questão</td>
                        <td>Ativo</td>
                        <td>Opções</td>
                        <td>Editar</td>
                        <td>Cadastrar Opção</td>
                    </tr>
                    @foreach ($questoes as $item)
                        <tr align="center" @if($loop -> even) bgcolor="#CCCCCCD" @endif>
                            <td >{{ $item -> ordem }}</td>
                            <td >{{ $item -> Grupo['grupo'] }}</td>
                            <td >{!! $item -> questao !!}</td>
                            <td @if($item -> ativo == '0')  style="color: red" @elseif($item -> ativo == '1')  style="color: green" @endif >{{ ($item -> ativo == '0' ? 'Não' : ($item -> ativo == '1' ? 'Sim' : 'Não especificado')) }}</td>
                            <td >{{ $opcoes -> where('id_questao', $item -> id)-> count() }}</td>
                            <td ><a href="{{route('d.questao.edit', ['id' => $item-> id])}}"><img src="{{asset('storage/icones/editar.png')}}" width="20px"></a></td>
                            <td ><a href="{{route('d.opcao.index', ['idq' => $item-> id])}}"><img src="{{asset('storage/icones/vincular.png')}}" width="20px"></a></td>
                        </tr>
                    @endforeach
                </table>    
            </div>
                   
        @endif
    </div>
@endsection