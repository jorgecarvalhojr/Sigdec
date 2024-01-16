@extends('auth.home')
@section('title', 'Atividades')


@push('script')
<script src="{{ asset ('js/jquery-3.2.1.js') }}"></script>

<script src="{{ asset ('js/viatura.js') }}"></script>

<script src="{{ asset ('js/tinymce/tinymce.min.js') }}"></script>
<script>
    
//***********************************************************************************************************
tinymce.init({
    selector: ".editavel",
	language : 'pt_BR',
    theme: "modern",
    width: '100%',
    height: 200,
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
//***********************************************************************************************************
function criaMascara(mascaraInput) {
  const maximoInput = document.getElementById(mascaraInput).maxLength;
  let valorInput = document.getElementById(mascaraInput).value;
  let valorSemPonto = document.getElementById(mascaraInput).value.replace(/([^0-9])+/g, "");
  const mascaras = {    
    cpf: valorInput.replace(/[^\d]/g, "").replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4"),
	  
    cnpj: valorInput.replace(/[^\d]/g, "").replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, "$1.$2.$3/$4-$5"),
	  
	telefone_resp: valorInput.replace(/[^\d]/g, "").replace(/^(\d{2})(\d{5})(\d{4})/, "($1) $2-$3"),
	  
	telefone_empresa: valorInput.replace(/[^\d]/g, "").replace(/^(\d{2})(\d{4})(\d{4})/, "($1) $2-$3"),
	  
	pavimentos: valorInput.replace(/[^\d]/g, ""),

    data_inicio: valorInput.replace(/[^\d]/g, "").replace(/^(\d{2})(\d{2})(\d{4})(\d{2})(\d{2})(\d{2})/, "$1/$2/$3 $4:$5:$6"),
	  
    data_fim: valorInput.replace(/[^\d]/g, "").replace(/^(\d{2})(\d{2})(\d{4})(\d{2})(\d{2})(\d{2})/, "$1/$2/$3 $4:$5:$6"),
	  
  }


  valorInput.length === maximoInput ? document.getElementById(mascaraInput).value = mascaras[mascaraInput]
 : document.getElementById(mascaraInput).value = valorSemPonto;
};	
	
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
function validaDat(campo,valor) {
	var date=valor;
	var ardt=new Array;
	var ExpReg=new RegExp("(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/[12][0-9]{3}");
	ardt=date.split("/");
	erro=false;
	if(date!='' && date != "__/__/____"){
		if ( date.search(ExpReg)==-1){
			erro = true;
			}
		else if (((ardt[1]==4)||(ardt[1]==6)||(ardt[1]==9)||(ardt[1]==11))&&(ardt[0]>30))
			erro = true;
		else if ( ardt[1]==2) {
			if ((ardt[0]>28)&&((ardt[2]%4)!=0))
				erro = true;
			if ((ardt[0]>29)&&((ardt[2]%4)==0))
				erro = true;
		}
		if (erro) {
			alert(valor+" não é uma data válida!!!");
			campo.focus();
			campo.value = "";
			return false;
		}
		return true;
	}
}
//**********************************************************************************
function ValidaDataI() {
    var value = $("#data_inicio").val();
    // capture all the parts
    if(value != ''){
        var matches = value.match(/^(\d{2})\/(\d{2})\/(\d{4}) (\d{2}):(\d{2}):(\d{2})$/);
        //alt:
        // value.match(/^(\d{2}).(\d{2}).(\d{4}).(\d{2}).(\d{2}).(\d{2})$/);
        // also matches 22/05/2013 11:23:22 and 22a0592013,11@23a22
        if (matches === null) {
            alert('Data Inválida!');
            $("#data_inicio").focus();
            $("#data_inicio").val('');
            return false;
        } else{
            // now lets check the date sanity
            var year = parseInt(matches[3], 10);
            var month = parseInt(matches[2], 10) - 1; // months are 0-11
            var day = parseInt(matches[1], 10);
            var hour = parseInt(matches[4], 10);
            var minute = parseInt(matches[5], 10);
            var second = parseInt(matches[6], 10);
            var date = new Date(year, month, day, hour, minute, second);
            if (date.getFullYear() !== year
            || date.getMonth() != month
            || date.getDate() !== day
            || date.getHours() !== hour
            || date.getMinutes() !== minute
            || date.getSeconds() !== second
            ) {
                alert('Data Inválida!');
                $("#data_inicio").focus();
                $("#data_inicio").val('')
                return false;
            } else {
                return true;
            }
        }
    }
}
//**********************************************************************************
function ValidaDataF() {
    var value = $("#data_fim").val();
    // capture all the parts
    if(value != ''){
        var matches = value.match(/^(\d{2})\/(\d{2})\/(\d{4}) (\d{2}):(\d{2}):(\d{2})$/);
        //alt:
        // value.match(/^(\d{2}).(\d{2}).(\d{4}).(\d{2}).(\d{2}).(\d{2})$/);
        // also matches 22/05/2013 11:23:22 and 22a0592013,11@23a22
        if (matches === null) {
            alert('Data Inválida!');
            $("#data_fim").focus();
            $("#data_fim").val('');
            return false;
        } else{
            // now lets check the date sanity
            var year = parseInt(matches[3], 10);
            var month = parseInt(matches[2], 10) - 1; // months are 0-11
            var day = parseInt(matches[1], 10);
            var hour = parseInt(matches[4], 10);
            var minute = parseInt(matches[5], 10);
            var second = parseInt(matches[6], 10);
            var date = new Date(year, month, day, hour, minute, second);
            if (date.getFullYear() !== year
            || date.getMonth() != month
            || date.getDate() !== day
            || date.getHours() !== hour
            || date.getMinutes() !== minute
            || date.getSeconds() !== second
            ) {
                alert('Data Inválida!');
                $("#data_fim").focus();
                $("#data_fim").val('')
                return false;
            } else {
                return true;
            }
        }
    }
}
//****************************************************************************************************
function Ativa_Foto() {
	if(document.getElementById('oprf1').checked == true){
		document.getElementById('fotos').disabled=false;
	}else{
		document.getElementById('fotos').disabled=true;
	}	
}
//****************************************************************************************************
function Habilitar() {
	if(document.getElementById('bol1').checked == true){
		document.getElementById('num_bol').disabled=false;
	}else{
		document.getElementById('num_bol').disabled=true;
	}
	
	if(document.getElementById('opf1').checked == true){
		document.getElementById('num_fide').disabled=false;
        document.getElementById('cobrade').disabled=false;
	}else{
		document.getElementById('num_fide').disabled=true;
        document.getElementById('cobrade').disabled=true;
	}
	
	if(document.getElementById('ope1').checked == true){
		document.getElementById('num_ecp').disabled=false;
	}else{
		document.getElementById('num_ecp').disabled=true;
	}
	
	if(document.getElementById('opse1').checked == true){
		document.getElementById('num_se').disabled=false;
	}else{
		document.getElementById('num_se').disabled=true;
	}
	
	if(document.getElementById('opv1').checked == true){
		$('.hab').attr("disabled", false);
		$("#adicionar").show();
	}else{
		$('.hab').attr("disabled", true);
		$("#adicionar").hide();
	}
	
	if(document.getElementById('aut2').checked == true){
		$('#promotor').attr("disabled", false);
		$(".promotor").show();
	}else{
		$('#promotor').attr("disabled", true);
		$(".promotor").hide();
	}
}
//****************************************************************************************************

window.onload = function() {
    Ativa_Foto();
    Habilitar();  
};
//***********************************************************************************************************
   
</script>

@endpush

@section('conteudo')

<div class="row container">
    <div id="formulario" class="col-md-6">
        <div class="form-group">
            <h3>CADASTRO DE ATIVIDADES DO ÓRGÃO</h3>
            @foreach ($errors->all() as $error)
                {{$error}}<br>  <!-- imprimir os erros de validação caso haja algum, serão enviados pelo Validator -->
            @endforeach
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

        @php
        if(Auth::user() -> configuracao)
        {
            $fundo = Auth::user() -> configuracao -> fundo;
            $fonte = Auth::user() -> configuracao -> fonte;
        }
        else
        {
            $fundo = "F5703C";
            $fonte = "000080";
        }
        @endphp


        <form id="atv" method="POST" action="@if ($atv->id_atv == 0) {{route($rota)}} @else {{route($rota, ['id' => $atv -> id_atv])}} @endif " enctype="multipart/form-data">
        @csrf

            <div class="row">
                <div class="col-md-6 form-group" >
                    <input {{(old('autoria', $atv -> autoria) == 'promotor' ? 'checked' : '')}} type="radio" name="autoria" id="aut1" value="promotor" onclick="Habilitar()" />
                    <strong>{{Auth::user() -> Orgao['sigla']}} está promovendo a Atividade</strong>
                </div>
                <div class="col-md-6 form-group" >
                    <input {{(old('autoria', $atv -> autoria) == 'participante' ? 'checked' : '')}} type="radio" name="autoria" id="aut2" value="participante" onclick="Habilitar()" />
                    <strong>{{Auth::user() -> Orgao['sigla']}} está participando da Atividade</strong>
                    <div class="col-md-12 form-group promotor" >
                        <strong>Órgão Promotor</strong>
                        <select class="form-control" name="promotor" id="promotor" disabled>
                            <option value="">Selecione o órgão</option>
                            @foreach($orgaos as $item)
                                <option style="font-size: 12px" {{(old('promotor', $atv -> promotor) == $item -> id_orgao ? 'selected' : '')}}  value="{{$item -> id_orgao}}">{{mb_strtoupper($item -> descricao)}}</option> 
                            @endforeach
                        </select>    
                    </div>
                    @error('promotor')
                        <div class="text-danger col-md-12">
                            {{ $message }}
                        </div>  
                    @enderror
    
                </div>
            </div>
            <input type="hidden" name="orgao" id="orgao" value="{{Auth::user() -> orgao}}">
            <div class="row">
                <div class="col-md-6 form-group" >
                    <strong>Selecione a Atividade:</strong> 
                    <Select class="form-control" name="tipo_atividade" id="tipo_atividade">
                        <option value=""></option>
                        @foreach ($lista_atv as $item)
                            <option title="{{$item -> descricao}}" {{(old('tipo_atividade', $atv -> tipo_atividade) == $item -> id_atividade ? 'selected' : '')}}  value="{{$item -> id_atividade}}">{{$item -> atividade}}</option>                            
                        @endforeach
                    </Select>
                    @error('tipo_atividade')
                        <div class="text-danger col-md-12">
                            {{ $message }}
                        </div>  
                    @enderror
                </div>
                <div class="col-md-6 form-group" >
                    <strong>Ciclo de Atuação:</strong> 
                    <Select class="form-control" name="ciclo" id="ciclo">
                        <option value=""></option>
                        @foreach ($ciclos as $item)
                            <option title="{{$item -> descricao}}" {{(old('ciclo', $atv -> ciclo) == $item -> ciclo ? 'selected' : '')}}  value="{{$item -> ciclo}}">{{$item -> ciclo}}</option>                            
                        @endforeach
                    </Select>
                    @error('ciclo')
                        <div class="text-danger col-md-12">
                            {{ $message }}
                        </div>  
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 form-group" >
                    <strong>Título da Atividade:</strong> 
                    <input class="form-control" name="titulo" id="titulo" value="{{old('titulo', $atv -> titulo)}}">
                    @error('titulo')
                        <div class="text-danger col-md-12">
                            {{ $message }}
                        </div>  
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group" >
                    <strong>Data de Início: </strong> 
                    <input class="form-control" type="text" id="data_inicio" name="data_inicio" maxlength="14" value="{{old('data_inicio', (!empty($atv -> data_inicio) ? date('d/m/Y H:i:s', strtotime($atv -> data_inicio)) : ''))}}" onblur="ValidaDataI()" oninput="criaMascara('data_inicio')" placeholder="dd/mm/aaaa hh:mm:ss"/> 
                    @error('data_inicio')
                        <div class="text-danger col-md-12">
                            {{ $message }}
                        </div>  
                    @enderror
                </div>
                <div class="col-md-6 form-group" >
                    <strong>Data de Término: </strong> 
                    <input class="form-control" type="text" id="data_fim" name="data_fim" maxlength="14" value="{{old('data_fim', (!empty($atv -> data_fim) ? date('d/m/Y H:i:s', strtotime($atv -> data_fim)) : ''))}}" onblur="ValidaDataF()" oninput="criaMascara('data_fim')" placeholder="dd/mm/aaaa hh:mm:ss"/> 
                    @error('data_fim')
                        <div class="text-danger col-md-12">
                            {{ $message }}
                        </div>  
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 form-group" >
                    <strong>Município:</strong> 
                    <Select class="form-control" multiple name="municipio[]" id="municipio">
                        <option style="font-weight: bold; color: red;" value="">Aperte a tecla "CTRL" para seleção múltipla</option>
                        @php $selecao = explode(',', $atv -> municipio); @endphp
                        @if(in_array('Rio de Janeiro (SEDEC)', $selecao))
                            <option selected value="Rio de Janeiro (SEDEC)">Rio de Janeiro (SEDEC)</option> 
                        @else
                            <option value="Rio de Janeiro (SEDEC)">Rio de Janeiro (SEDEC)</option>
                        @endif
                        @foreach ($municipios as $item)
                            <option @foreach($selecao as $opcao) {{(old('municipio', $opcao) == $item -> municipio ? 'selected' : '')}} @endforeach value="{{$item -> municipio}}">{{$item -> municipio}}</option> 
                        @endforeach
                    </Select>
                    @error('municipio')
                        <div class="text-danger col-md-12">
                            {{ $message }}
                        </div>  
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group" >
                    <input type="radio" name="op_viatura" id="opv1" value="Sim" {{($atv -> op_viatura == "Sim" ? "checked" : "")}} onclick="Habilitar()"> 
                    <strong>Houve utilização de Viatura.</strong>
                </div>
                <div class="col-md-6 form-group" >
                    <input type="radio" name="op_viatura" id="opv2" value="Não" {{($atv -> op_viatura == "Não" || $atv -> op_viatura == "" ? "checked" : "")}} onclick="Habilitar()"> 
                    <strong>Não houve utilização de Viatura.</strong><br>

                    @error('op_viatura')
                        <div class="text-danger col-md-12">
                            {{ $message }}
                        </div>  
                    @enderror
                </div>
            </div>

            @if(Count($viaturas) > 0)

                @foreach ($viaturas as $opcao)

                    <div class="row vtr">
                        <div class="form-group col-md-5">
                            <strong>Viatura Utilizada:</strong>
                            <select class="hab form-control" name="viatura[]" id="viatura" required disabled>
                                <option value=""></option>
                                @foreach($lista_vtr as $item)
                                    <option {{($opcao -> id_viatura == $item -> id_viatura ? 'selected' : '')}} title="{{$item -> tipo}} - {{$item -> placa}}" value="{{$item -> id_viatura}}">{{$item -> prefixo}}</option>
                                @endforeach    
                            </select>
                            @error('viatura')
                            <div class="text-danger col-md-12">
                                {{ $message }}
                            </div>  
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <strong>Km Rodados:</strong>
                            <input type="text" class="hab number form-control" required name="kilometragem[]" id="kilometragem" disabled value="{{$opcao -> kilometragem}}" maxlength="4" >
                            @error('kilometragem')
                                <div class="text-danger col-md-12">
                                    {{ $message }}
                                </div>  
                            @enderror
                        </div>
                        <div id="adicionar" class="form-group col-md-2">
                            <strong>&nbsp;</strong>
                            <input type="button" class="mais form-control" style="text-align:center; background:rgb(45, 128, 0); color:white" value="+">
                        </div>

                    </div>
                    <div id="novos"></div>

                @endforeach
            @else

                <div class="row vtr">
                    <div class="form-group col-md-5">
                        <strong>Viatura Utilizada:</strong>
                        <select class="hab form-control" name="viatura[]" id="viatura" required disabled>
                            <option value=""></option>
                            @foreach($lista_vtr as $item)
                                <option title="{{$item -> tipo}} - {{$item -> placa}}" value="{{$item -> id_viatura}}">{{$item -> prefixo}}</option>
                            @endforeach
                        </select>
                        @error('viatura')
                            <div class="text-danger col-md-12">
                                {{ $message }}
                            </div>  
                        @enderror
                    </div>

                    <div class="form-group col-md-3">
                        <strong>Km Rodados:</strong>
                        <input type="text" class="hab number form-control" required name="kilometragem[]" id="kilometragem" disabled value="" maxlength="4" onKeyPress="return somenteNumeros()" >
                        @error('kilometragem')
                            <div class="text-danger col-md-12">
                                {{ $message }}
                            </div>  
                        @enderror
                    </div>
                    <div id="adicionar" class="form-group col-md-2">
                        <strong>&nbsp;</strong>
                        <input type="button" class="mais form-control" style="text-align:center; background:rgb(45, 128, 0); color:white" value="+">
                    </div>

                </div>
                <div id="novos"></div>

            @endif

            <div class="row form-group">
                <div class="col-md-3">
                    <strong>Boletim SEDEC?</strong>
                </div>
                <div class="col-md-1">
                    <input type="radio" name="bol" id="bol1" value="Sim" {{(old('bol', $atv -> bol) == 'Sim' ? 'checked' : '')}} onclick="Habilitar()"><strong>Sim</strong>
                </div>

                <div class="col-md-1">
                    <input type="radio" name="bol" id="bol2" value="Não" {{(old('bol', $atv -> bol) == 'Não' ? 'checked' : ($atv -> bol == '' ? 'checked' : ''))}} onclick="Habilitar()"><strong>Não</strong>
                </div>

                <div class="col-md-7">
                    <input class="form-control" name="num_bol" id="num_bol" value="{{old('num_bol', $atv-> boletim)}}" placeholder="Bol SEDEC/CBMERJ xxx de xx/xx/xxxx" {{($atv -> bol == 'Não' ? 'disabled' : ($atv -> bol == '' ? 'disabled' : ''))}}>
                    @error('num_bol')
                        <div class="text-danger col-md-12">
                            {{ $message }}
                        </div>  
                    @enderror
                </div>

            </div>

            <div class="row form-group">
                <div class="col-md-3">
                    <strong>ECP?</strong>
                </div>
                <div class="col-md-1">
                    <input type="radio" name="ecp" id="ope1" value="Sim" {{(old('ecp', $atv -> ecp) == 'Sim' ? 'checked' : '')}} onclick="Habilitar()"><strong>Sim</strong>
                </div>

                <div class="col-md-1">
                    <input type="radio" name="ecp" id="ope2" value="Não" {{(old('ecp', $atv -> ecp) == 'Não' ? 'checked' : ($atv -> ecp == '' ? 'checked' : ''))}} onclick="Habilitar()"><strong>Não</strong>
                </div>

                <div class="col-md-7">
                    <input class="form-control" name="num_ecp" id="num_ecp" value="{{old('num_ecp', $atv-> num_ecp)}}" {{($atv -> ecp == 'Não' ? 'disabled' : ($atv -> ecp == '' ? 'disabled' : ''))}}>
                    @error('num_ecp')
                        <div class="text-danger col-md-12">
                            {{ $message }}
                        </div>  
                    @enderror
                </div>

            </div>

            <div class="row form-group">
                <div class="col-md-3">
                    <strong>SE?</strong>
                </div>
                <div class="col-md-1">
                    <input type="radio" name="se" id="opse1" value="Sim" {{(old('se', $atv -> se) == 'Sim' ? 'checked' : '')}} onclick="Habilitar()"><strong>Sim</strong>
                </div>

                <div class="col-md-1">
                    <input type="radio" name="se" id="opse2" value="Não" {{(old('se', $atv -> se) == 'Não' ? 'checked' : ($atv -> se == '' ? 'checked' : ''))}} onclick="Habilitar()"><strong>Não</strong>
                </div>

                <div class="col-md-7">
                    <input class="form-control" name="num_se" id="num_se" value="{{old('num_se', $atv-> num_se)}}" {{($atv -> se == 'Não' ? 'disabled' : ($atv -> se == '' ? 'disabled' : ''))}}>
                    @error('num_se')
                        <div class="text-danger col-md-12">
                            {{ $message }}
                        </div>  
                    @enderror
                </div>

            </div>

            <div class="row form-group">
                <div class="col-md-3">
                    <strong>FIDE?</strong>
                </div>
                <div class="col-md-1">
                    <input type="radio" name="fide" id="opf1" value="Sim" {{(old('fide', $atv -> fide) == 'Sim' ? 'checked' : '')}} onclick="Habilitar()"><strong>Sim</strong>
                </div>

                <div class="col-md-1">
                    <input type="radio" name="fide" id="opf2" value="Não" {{(old('fide', $atv -> fide) == 'Não' ? 'checked' : ($atv -> fide == '' ? 'checked' : ''))}} onclick="Habilitar()"><strong>Não</strong>
                </div>

                <div class="col-md-7">
                    <input class="form-control" name="num_fide" id="num_fide" value="{{old('num_fide', $atv-> num_fide)}}" {{($atv -> fide == 'Não' ? 'disabled' : ($atv -> fide == '' ? 'disabled' : ''))}}>
                    @error('num_fide')
                        <div class="text-danger col-md-12">
                            {{ $message }}
                        </div>  
                    @enderror
                </div>

            </div>

            <div class="row form-group">
                <div class="col-md-3">
                    <strong>COBRADE</strong>
                </div>
                <div class="col-md-9">
                    <select class="form-control" name="cobrade" id="cobrade" {{($atv -> fide == 'Não' ? 'disabled' : ($atv -> fide == '' ? 'disabled' : ''))}}>
                        <option value=""></option>
                        @foreach ($cobrades as $item)
                            <option style="font-size: 12px" {{(old('cobrade', $atv -> cobrade) == $item -> cobrade ? 'selected' : '')}} title="{{$item -> definicao}}" value="{{$item -> cobrade}}">{{$item -> final}}</option>
                        @endforeach
                    </select>
                    @error('cobrade')
                        <div class="text-danger col-md-12">
                            {{ $message }}
                        </div>  
                    @enderror
                </div>
            </div>

            <div class="row form-group">
                <div class="col-md-3">
                    <strong>Relatório Fotográfico</strong>
                </div>
                <div class="col-md-1">
                    <input type="radio" name="relatorio_foto" id="oprf1" value="Sim" {{(old('relatorio_foto', $atv -> relatorio_foto) == "Sim" ? 'checked' : '' )}} onClick="Ativa_Foto()"/><strong>Sim</strong>
                </div>
                <div class="col-md-1">
                    <input type="radio" name="relatorio_foto" id="oprf2" value="Não" {{(old('relatorio_foto', $atv -> relatorio_foto == "Não" ? 'checked' : ($atv -> relatorio_foto == '' ? 'checked' : '')) )}} onClick="Ativa_Foto()"/><strong>Não</strong>
                </div>

                <div class="col-md-7">
                    <input class="form-control" name="fotos[]" type="file" multiple="multiple" id="fotos" disabled>
                    @if ($errors->has('fotos'))
                        <div class="text-danger col-md-12">
                            <strong>{{ $errors->first('fotos') }}</strong>
                        </div>
                    @endif

                    @if($errors->has('fotos.*'))
                        @foreach ($errors->get('fotos.*') as $message)
                            @foreach ( $message as $value)
                                <div class="text-danger col-md-12">{{ $value }}</div>
                            @endforeach
                        @endforeach
                    @endif
                </div>

            </div>

            <div class="row">
                <div class="col-md-12" style="text-align: center; background-color:#D3D3D3">
                    <strong>DESCRIÇÃO DA ATIVIDADE</strong>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 form-group">
                    <textarea class="form-control editavel" wrap="off" name="relato" id="relato" rows="10" >{{ old('relato', $atv -> relato) }}</textarea> 
                    @error('relato')
                        <div class="text-danger col-md-12">
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
    @if ($atvs->count() > 0)

        <div style="max-height: 70vh; overflow:auto; " class="col-md-6">
            <table style="font-size: 12px" border="1" cellpadding="2px" cellspacing="0" width="100%">
                <tr align="center" bgcolor="#CCCCCCC">
                    <td>N°</td>
                    <td>Título</td>
                    <td>Atividade</td>
                    <td>Data Início</td>
                    <td>Relatório</td>
                    <td>Fotos</td>
                    <td>Editar</td>
                </tr>
                @foreach ($atvs as $item)
                    <tr align="center" @if($loop -> even) bgcolor="#CCCCCCD" @endif>
                        <td >{{ $loop -> index + 1 }}</td>
                        <td >{{ $item -> titulo }}</td>
                        <td >{{ $item -> TipoAtividade['atividade'] }}</td>
                        <td >{{ date('d/m/Y', strtotime($item -> data_inicio)) }}</td>
                        <td ><a href="#" onclick="javascript:window.open('{{route('atv_dc.download', ['id' => $item -> id_atv])}}','','scrollbars=yes,resizable=yes,width=700,height=500');return false;" ><img src="{{asset('storage/icones/download.png')}}" width="20px"></a></td>
                        <td><a href="#" onclick="javascript:window.open('{{route('atv_dc.fotos', ['id' => $item -> id_atv])}}','','scrollbars=yes,resizable=yes,width=700,height=500');return false;" ><img src="{{asset('storage/icones/visualizar.png')}}" width="20px"></a></td>
                        <td >@if($item -> usuario == Auth::user() -> indice_adm)<a href="{{route('atv_dc.edit', ['id' => $item-> id_atv])}}"><img src="{{asset('storage/icones/editar.png')}}" width="20px"></a> @else <img src="{{asset('storage/icones/parar.png')}}" width="20px"> @endif</td>
                    </tr>
                @endforeach
            </table>    
        </div> 
    @endif

</div>
@endsection