@extends('auth.home')
@section('title', 'Estatísticas')

@push('script')
<script src="{{ asset ('js/jquery-3.2.1.js') }}"></script>
<script>
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
function criaMascara(mascaraInput) {
  const maximoInput = document.getElementById(mascaraInput).maxLength;
  let valorInput = document.getElementById(mascaraInput).value;
  let valorSemPonto = document.getElementById(mascaraInput).value.replace(/([^0-9])+/g, "");
  const mascaras = {    
	  
	  data_final: valorInput.replace(/[^\d]/g, "").replace(/(\d{2})(\d{2})(\d{4})/, "$1/$2/$3"),
	  
	  data_inicial: valorInput.replace(/[^\d]/g, "").replace(/(\d{2})(\d{2})(\d{4})/, "$1/$2/$3"),

      telefone: valorInput.replace(/[^\d]/g, "").replace(/^(\d{2})(\d{5})(\d{4})/, "($1) $2-$3"),

      num_bo: valorInput.replace(/[^\d]/g, "").replace(/(\d{5})(\d{4})/, "$1/$2"),
	  
  }


  valorInput.length === maximoInput ? document.getElementById(mascaraInput).value = mascaras[mascaraInput]
 : document.getElementById(mascaraInput).value = valorSemPonto;
};	
	
//***************************************************************************************
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
//************************************************************************************

</script>
@endpush

@section('conteudo')

    <div class="row container">
        <div id="formulario" class="col-md-6">
            <div class="form-group">
                <h3>ESTATÍSTICAS</h3>
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

            <form id="estatisticas" method="POST" action="{{route('estatisticas.show')}}">
            @csrf
                @error('opcao')
                    <div class="text-danger col-md-12">
                        {{ $message }}
                    </div>  
                @enderror

                <div style="text-align: left" class="row">
                    <div class="col-md-6 form-group" >
                        <input type="radio" name="opcao" id="op1" value="1" onclick="Habilitar()" />
                        <strong>Quilômetros Rodados: </strong>
                    </div>
                </div>

                <div style="text-align: left" class="row">
                    <div class="col-md-6 form-group" >
                        <input type="radio" name="opcao" id="op2" value="2" onclick="Habilitar()" />
                        <strong>Tipo de Atividade: </strong>
                    </div>
                </div>

                <div style="text-align: left" class="row">
                    <div class="col-md-6 form-group" >
                        <input type="radio" name="opcao" id="op3" value="3" onclick="Habilitar()" />
                        <strong>Ciclo de Atuação: </strong>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-3">
                        <strong>DATA INICIAL: </strong>
                        <input class="form-control" type="text" id="data_inicial" name="data_inicial" maxlength="8" value="{{old('data_inicial')}}" onblur="validaDat(this,this.value)" oninput="criaMascara('data_inicial')"/>
                        @error('data_inicial')
                            <div class="text-danger col-md-12">
                                {{ $message }}
                            </div>   
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <strong>DATA FINAL: </strong>
                        <input class="form-control" type="text" id="data_final" name="data_final" maxlength="8" value="{{old('data_final')}}" onblur="validaDat(this,this.value)" oninput="criaMascara('data_final')"/>
                        @error('data_final')
                            <div class="text-danger col-md-12">
                                {{ $message }}
                            </div>   
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <strong>ÓRGÃO: </strong>
                        <select class="form-control" name="orgao" id="orgao">
                            <option value="todos">TODOS</option>
                            @foreach($orgaos as $item)
                                <option title="{{$item -> descricao}}" value="{{$item -> orgao}}">{{$item -> sigla}}</option>
                            @endforeach
                        </select>
                        @error('orgao')
                            <div class="text-danger col-md-12">
                                {{ $message }}
                            </div>   
                        @enderror
                    </div>
                </div>

                <div class="form-goup row">        
                    <div class="form-group col-md-12">
                        <button id="enviar" type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </div>
        
            </form>
        </div>
    </div>
@endsection