@extends('auth.home')
@section('title', 'Configuração')

@push('script')
<script>

    //********************************************************************************		
    function previewL1(input) {
                    if (input.files && input.files[0]) {
                    $('.logo1').attr('src','');
                        var reader = new FileReader();
     
                        reader.onload = function (e) {
                                $('.logo1')
                    .attr('src', e.target.result)
                                        .height()
                        };
                        reader.readAsDataURL(input.files[0]);
                    }
                }
    //********************************************************************************
    function previewL2(input) {
                    if (input.files && input.files[0]) {
                    $('.logo2').attr('src','');
                        var reader = new FileReader();
     
                        reader.onload = function (e) {
                                $('.logo2')
                    .attr('src', e.target.result)
                                        .height()
                        };
                        reader.readAsDataURL(input.files[0]);
                    }
                }
    //********************************************************************************
    function getColor(sel) {
        var value = '#'+sel.value;
        $('.topo').css({"background-color":value});
    }
    function getColorF(sel) {
        var value = '#'+sel.value;
        $('.span').css({"color":value});
    }
    function getT1() {
        var texto = document.getElementById('titulo1').value;
        document.getElementById('t1').innerHTML = texto.toUpperCase();
    }
    function getT2() {
        var texto = document.getElementById('titulo2').value;
        document.getElementById('t2').innerHTML = texto.toUpperCase();
    }
        
    //**********************************************************************************
    function criaMascara(mascaraInput) {
        const maximoInput = document.getElementById(mascaraInput).maxLength;
        let valorInput = document.getElementById(mascaraInput).value;
        let valorSemPonto = document.getElementById(mascaraInput).value.replace(/([^0-9])+/g, "");
        
        const mascaras = {    
            
            celular: valorInput.replace(/[^\d]/g, "").replace(/^(\d{2})(\d{5})(\d{4})/, "($1) $2-$3"),
            
            telefone1: valorInput.replace(/[^\d]/g, "").replace(/^(\d{2})(\d{4})(\d{4})/, "($1) $2-$3"),

            telefone2: valorInput.replace(/[^\d]/g, "").replace(/^(\d{2})(\d{4})(\d{4})/, "($1) $2-$3"),

            cpf: valorInput.replace(/[^\d]/g, ""),
            
        }

        valorInput.length === maximoInput ? document.getElementById(mascaraInput).value = mascaras[mascaraInput] : document.getElementById(mascaraInput).value = valorSemPonto;
    }	
</script>
@endpush

@section('conteudo')
  <div class="container row">
        <div id="formulario" class="col-md-6">
            <div class="form-group">
                <h4>CONFIGURAÇÃO DO SIGDEC</h4>
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
    
            <form id="config" method="post" action="@if ($config->id_config == 0) {{route($rota)}} @else {{route($rota, ['id' => $config-> id_config])}} @endif" enctype="multipart/form-data">
                @csrf
		
                <div class="form-group">
                    <b style="background:#CCCCCC">CABEÇALHO E MENU DA PÁGINA</b>
                </div>

                <div class="form-group row">
                    <div class="col-md-4">
                        @if(Auth::user()->configuracao()->count() > 0)
                            <img id="logo1" class="logo1" src="{{ url('storage/'.Auth::user()-> configuracao -> logo1) }}" width="30%" />
                        @else
                            <img id="logo1" class="logo1" src="{{ url('storage/logos/RJ/logo1.png') }}" width="30%" />
                        @endif
                    </div>

                    <div class="col-md-8 control-label">
                        Selecione o Logo do Governo (Federal, Estadual ou Municipal), que irá substituir o Logo do Estado do Rio de Janeiro.
                        <input class="form-control" type="file" name="logo1" id="logo1" onChange="previewL1(this);" />
                        @error('logo1')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror       
                    </div>
                </div>

                <div class="form-group row">

                    <div class="col-md-4">
                        @if(Auth::user()->configuracao()->count() > 0)
                            <img id="logo2" class="logo2" src="{{ url('storage/'.Auth::user()-> configuracao -> logo2) }}" width="30%" />
                        @else
                            <img id="logo2" class="logo2" src="{{ url('storage/logos/RJ/logo2.png') }}" width="30%" />
                        @endif
                    </div>

                    <div class="col-md-8 control-label">
                        Selecione o Logo do órgão de Defesa Civil, que irá substituir a imagem do Logo da SEDEC.
                        <input class="form-control" type="file" name="logo2" id="logo2" onChange="previewL2(this);" />
                        @error('logo2')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror          
                    </div>
                </div>

                <div class="form-group row">

                    <div class="col-md-4 control-label">
                        Cor de Fundo:
                    </div>

                    <div class="col-md-8">
                        <select class="form-control" name="fundo" id="fundo" onchange="getColor(this)" required>
                            <option value=""></option>
                            <option style="background-color:#5990F1" {{ (old('fundo', $config->fundo) == '5990F1' ? 'selected' : '') }} value="5990F1">Light Blue
                            </option>
                            <option style="background-color:#F5703C" {{ (old('fundo', $config->fundo) == 'F5703C' ? 'selected' : '') }} value="F5703C">Coral
                            </option>
                            <option style="background-color:#00FFFF" {{ (old('fundo', $config->fundo) == '00FFFF' ? 'selected' : '') }} value="00FFFF">Aqua
                            </option>
                            <option style="background-color:#0000FF" {{ (old('fundo', $config->fundo) == '0000FF' ? 'selected' : '') }} value="0000FF">Blue
                            </option>
                            <option style="background-color:#FF00FF" {{ (old('fundo', $config->fundo) == 'FF00FF' ? 'selected' : '') }} value="FF00FF">Fuchsia
                            </option>
                            <option style="background-color:#FFFFFF" {{ (old('fundo', $config->fundo) == 'FFFFFF' ? 'selected' : '') }} value="FFFFFF">white
                            </option>
                            <option style="background-color:#008000" {{ (old('fundo', $config->fundo) == '008000' ? 'selected' : '') }} value="008000">Green
                            </option>
                            <option style="background-color:#808080" {{ (old('fundo', $config->fundo) == '808080' ? 'selected' : '') }} value="808080">Gray
                            </option>
                            <option style="background-color:#00FF00" {{ (old('fundo', $config->fundo) == '00FF00' ? 'selected' : '') }} value="00FF00">Lime
                            </option>
                            <option style="background-color:#800000" {{ (old('fundo', $config->fundo) == '800000' ? 'selected' : '') }} value="800000">Maroon
                            </option>
                            <option style="background-color:#000080" {{ (old('fundo', $config->fundo) == '000080' ? 'selected' : '') }} value="000080">Navy
                            </option>
                            <option style="background-color:#808000" {{ (old('fundo', $config->fundo) == '808000' ? 'selected' : '') }} value="808000">Olive
                            </option>
                            <option style="background-color:#800080" {{ (old('fundo', $config->fundo) == '800080' ? 'selected' : '') }} value="800080">Purple
                            </option>
                            <option style="background-color:#FF0000" {{ (old('fundo', $config->fundo) == 'FF0000' ? 'selected' : '') }} value="FF0000">Red
                            </option>
                            <option style="background-color:#C0C0C0" {{ (old('fundo', $config->fundo) == 'C0C0C0' ? 'selected' : '') }} value="C0C0C0">Silver
                            </option>
                            <option style="background-color:#008080" {{ (old('fundo', $config->fundo) == '008080' ? 'selected' : '') }} value="008080">Teal
                            </option>
                            <option style="background-color:#FFFF00" {{ (old('fundo', $config->fundo) == 'FFFF00' ? 'selected' : '') }} value="FFFF00">Yellow
                            </option>
                        </select>
                        @error('fundo')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror          
                    </div>
                </div>

                <div class="form-group row">

                    <div class="col-md-4 control-label">
                        Cor de fonte:
                    </div>

                    <div class="col-md-8">
                        <select class="form-control" name="fonte" id="fonte" onchange="getColorF(this)" required>
                            <option value=""></option>
                            <option style="background-color:#5990F1" {{ (old('fonte', $config->fonte) == '5990F1' ? 'selected' : '') }} value="5990F1">Light Blue
                            </option>
                            <option style="background-color:#F5703C" {{ (old('fonte', $config->fonte) == 'F5703C' ? 'selected' : '') }} value="F5703C">Coral
                            </option>
                            <option style="background-color:#00FFFF" {{ (old('fonte', $config->fonte) == '00FFFF' ? 'selected' : '') }} value="00FFFF">Aqua
                            </option>
                            <option style="background-color:#0000FF" {{ (old('fonte', $config->fonte) == '0000FF' ? 'selected' : '') }} value="0000FF">Blue
                            </option>
                            <option style="background-color:#FF00FF" {{ (old('fonte', $config->fonte) == 'FF00FF' ? 'selected' : '') }} value="FF00FF">Fuchsia
                            </option>
                            <option style="background-color:#FFFFFF" {{ (old('fonte', $config->fonte) == 'FFFFFF' ? 'selected' : '') }} value="FFFFFF">white
                            </option>
                            <option style="background-color:#008000" {{ (old('fonte', $config->fonte) == '008000' ? 'selected' : '') }} value="008000">Green
                            </option>
                            <option style="background-color:#808080" {{ (old('fonte', $config->fonte) == '808080' ? 'selected' : '') }} value="808080">Gray
                            </option>
                            <option style="background-color:#00FF00" {{ (old('fonte', $config->fonte) == '00FF00' ? 'selected' : '') }} value="00FF00">Lime
                            </option>
                            <option style="background-color:#800000" {{ (old('fonte', $config->fonte) == '800000' ? 'selected' : '') }} value="800000">Maroon
                            </option>
                            <option style="background-color:#000080" {{ (old('fonte', $config->fonte) == '000080' ? 'selected' : '') }} value="000080">Navy
                            </option>
                            <option style="background-color:#808000" {{ (old('fonte', $config->fonte) == '808000' ? 'selected' : '') }} value="808000">Olive
                            </option>
                            <option style="background-color:#800080" {{ (old('fonte', $config->fonte) == '800080' ? 'selected' : '') }} value="800080">Purple
                            </option>
                            <option style="background-color:#FF0000" {{ (old('fonte', $config->fonte) == 'FF0000' ? 'selected' : '') }} value="FF0000">Red
                            </option>
                            <option style="background-color:#C0C0C0" {{ (old('fonte', $config->fonte) == 'C0C0C0' ? 'selected' : '') }} value="C0C0C0">Silver
                            </option>
                            <option style="background-color:#008080" {{ (old('fonte', $config->fonte) == '008080' ? 'selected' : '') }} value="008080">Teal
                            </option>
                            <option style="background-color:#FFFF00" {{ (old('fonte', $config->fonte) == 'FFFF00' ? 'selected' : '') }} value="FFFF00">Yellow
                            </option>
                        </select>
                        @error('fonte')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror          
                    </div>
                </div>

                <div class="form-group row">

                    <div class="col-md-4 control-label">
                        Título da Primeira Linha
                    </div>

                    <div class="col-md-8">
                        <input class="form-control" style="text-transform:uppercase" type="text" name="titulo1" id="titulo1" value="{{old('titulo1', $config -> titulo1)}}" maxlength="100" onBlur="getT1()" required>
                        @error('titulo1')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror          
                    </div>
                </div>

                <div class="form-group row">

                    <div class="col-md-4 control-label">
                        Título da Segunda Linha
                    </div>

                    <div class="col-md-8">
                        <input class="form-control" style="text-transform:uppercase" type="text" name="titulo2" id="titulo2" value="{{old('titulo2', $config -> titulo2)}}" maxlength="100" onBlur="getT2()" required>
                        @error('titulo2')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror          
                    </div>
                </div>
                <div class="form-group row alert-danger">
                    <b style="text-align: center; font-size:11px"> Obs: Na reconfiguração do Sistema, se após submeter o formulário, não houver alteração para a nova configuração, limpe o histórico do seu navegador.</b>
                </div>

                <div class="form-group">
                    <b style="background:#CCCCCC">INFORMAÇÕES DO ÓRGAO ({{$config -> Orgao['sigla']}})</b>
                </div>

                <div class="form-group row">

                    <div class="col-md-4 control-label">
                        Titular do Órgão de Defesa Civil
                    </div>

                    <div class="col-md-8">
                        <input class="form-control" style="text-transform:uppercase" type="text" name="titular" id="titular" value="{{old('titular', $config -> titular)}}" maxlength="100" required>
                        @error('titular')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror          
                    </div>
                </div>

                <div class="form-group row">

                    <div class="col-md-4 control-label">
                        Função exercida pelo Titular
                    </div>

                    <div class="col-md-8">
                        <input class="form-control" style="text-transform:uppercase" type="text" name="funcao_titular" id="funcao_titular" value="{{old('funcao_titular', $config -> funcao_titular)}}" maxlength="100" required>
                        @error('funcao_titular')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror          
                    </div>
                </div>

                <div class="form-group row">

                    <div class="col-md-4 control-label">
                        Matrícula do Titular
                    </div>

                    <div class="col-md-8">
                        <input class="form-control" style="text-transform:uppercase" type="text" name="mat_titular" id="mat_titular" value="{{old('mat_titular', $config -> mat_titular)}}" maxlength="15" required>
                        @error('mat_titular')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror          
                    </div>
                </div>

                <div class="form-group row">

                    <div class="col-md-4 control-label">
                        Endereço do Órgão de Defesa Civil
                    </div>

                    <div class="col-md-8">
                        <input class="form-control" style="text-transform:uppercase" type="text" name="endereco" id="endereco" value="{{old('endereco', $config -> endereco)}}" maxlength="150" required>
                        @error('endereco')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror          
                    </div>
                </div>

                <div class="form-group row">

                    <div class="col-md-4 control-label">
                        Telefone de Contato
                    </div>

                    <div class="col-md-8">
                        <input class="form-control" type="text" name="telefone1" id="telefone1" oninput="criaMascara('telefone1')" value="{{old('telefone1', $config -> telefone1)}}" maxlength="10" required>
                        @error('telefone1')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror          
                    </div>
                </div>

                <div class="form-group row">

                    <div class="col-md-4 control-label">
                        Telefone de Contato 2
                    </div>

                    <div class="col-md-8">
                        <input class="form-control" type="text" name="telefone2" id="telefone2" oninput="criaMascara('telefone2')" value="{{old('telefone2', $config -> telefone2)}}" maxlength="10">
                        @error('telefone2')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror          
                    </div>
                </div>

                <div class="form-group row">

                    <div class="col-md-4 control-label">
                        Site Oficial
                    </div>

                    <div class="col-md-8">
                        <input class="form-control" type="text" name="site" id="site" value="{{old('site', $config -> site)}}" required>
                        @error('site')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror          
                    </div>
                </div>

                <div class="form-group row">

                    <div class="col-md-4 control-label">
                        E-mail
                    </div>

                    <div class="col-md-8">
                        <input class="form-control" type="text" name="email" id="email" value="{{old('email', $config -> email)}}" maxlength="100" required>
                        @error('email')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror          
                    </div>
                </div>

                <div class="form-group row">        
                    <div class="col-md-8 offset-2">
                    <button id="enviar" type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection