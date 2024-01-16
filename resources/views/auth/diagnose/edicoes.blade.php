@extends('auth.diagnose_home')
@section('title', 'Edições Diagnose')

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
//**************************************************************************************
function criaMascara(mascaraInput) {
  const maximoInput = document.getElementById(mascaraInput).maxLength;
  let valorInput = document.getElementById(mascaraInput).value;
  let valorSemPonto = document.getElementById(mascaraInput).value.replace(/([^0-9])+/g, "");
  const mascaras = {    
	  CPF: valorInput.replace(/[^\d]/g, "").replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4"),
	  
	  telefone_sol: valorInput.replace(/[^\d]/g, "").replace(/^(\d{2})(\d{5})(\d{4})/, "($1) $2-$3"),
	  
	  Comercial: valorInput.replace(/[^\d]/g, "").replace(/^(\d{2})(\d{5})(\d{4})/, "($1) $2-$3"),
	  
	  Fixo: valorInput.replace(/[^\d]/g, "").replace(/^(\d{2})(\d{4})(\d{4})/, "($1) $2-$3"),
	  
	  CEP: valorInput.replace(/[^\d]/g, "").replace(/(\d{5})(\d{3})/, "$1-$2"),
	  
	  data_inicio: valorInput.replace(/[^\d]/g, "").replace(/(\d{2})(\d{2})(\d{4})/, "$1/$2/$3"),
	  
	  data_fim: valorInput.replace(/[^\d]/g, "").replace(/(\d{2})(\d{2})(\d{4})/, "$1/$2/$3"),
	  
	  horario: valorInput.replace(/[^\d]/g, "").replace(/(\d{2})(\d{2})/, "$1:$2"),
  }


  valorInput.length === maximoInput ? document.getElementById(mascaraInput).value = mascaras[mascaraInput]
 : document.getElementById(mascaraInput).value = valorSemPonto;
};	
//**************************************************************************************

</script>
@endpush

@section('conteudo')

    <div class="row container">
        <div id="formulario" class="col-md-6">
            <div class="form-group">
                <h3>CADASTRO DE EDIÇÕES DO R.D.</h3>
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
            <form id="edicoes" method="POST" action="@if ($edicao -> id == 0) {{route($rota)}} @else {{route($rota, ['id' => $edicao -> id])}} @endif ">
            @csrf

                <div class="form-group row">
                    <div class="col-md-3">
                        <strong>Ano:</strong>
                        <input class="form-control number" type="text" maxlength="4" name="ano" id="ano" value="{{old('ano', $edicao -> ano)}}" required>
                        @error('ano')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <strong>Data Início:</strong>
                        <input class="form-control" type="text" name="data_inicio" id="data_inicio" maxlength="8" value="{{old('data_inicio', (!empty($edicao -> data_inicio) ? date('d/m/Y', strtotime($edicao -> data_inicio)) : ''))}}" onblur="validaDat(this,this.value)" oninput="criaMascara('data_inicio')" required >
                        @error('data_inicio')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <strong>Data Término:</strong>
                        <input class="form-control" type="text" name="data_fim" id="data_fim" maxlength="8" value="{{old('data_fim', (!empty($edicao -> data_fim) ? date('d/m/Y', strtotime($edicao -> data_fim)) : ''))}}" onblur="validaDat(this,this.value)" oninput="criaMascara('data_fim')" >
                        @error('data_fim')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror
                    </div>
    
                    <div class="col-md-3">
                        <strong>Ativo?</strong>
                        <select class="form-control" id="ativo" name="ativo" required>
                            <option value=""></option>
                            <option {{ (old('ativo', $edicao->ativo) == '1' ? 'selected' : '') }} value="1">Sim</option>
                            <option {{ (old('ativo', $edicao->ativo) == '0' ? 'selected' : '') }} value="0">Não</option>
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
        @if ($edicoes->count() > 0)
            
            <div style="max-height: 70vh; overflow:auto; " class="col-md-6">
                <table style="font-size: 12px" border="1" cellpadding="2px" cellspacing="0" width="100%">
                    <tr align="center" bgcolor="#CCCCCCC">
                        <td>Ano</td>
                        <td>Data Início</td>
                        <td>Data Término</td>
                        <td>Ativo</td>
                        <td>Baixar CSV</td>
                        <td>Editar</td>
                    </tr>
                    @foreach ($edicoes as $item)
                        <tr align="center" @if($loop -> even) bgcolor="#CCCCCCD" @endif>
                            <td >{{ $item -> ano }}</td>
                            <td >{{ date('d/m/Y', strtotime($item -> data_inicio)) }}</td>
                            <td >{{ ($item -> data_fim != '' ? date('d/m/Y', strtotime($item -> data_fim)) : '') }}</td>
                            <td @if($item -> ativo == '0')  style="color: red" @elseif($item -> ativo == '1')  style="color: green" @endif >{{ ($item -> ativo == '0' ? 'Não' : ($item -> ativo == '1' ? 'Sim' : 'Não especificado')) }}</td>
                            @if(Storage::disk('public')->exists('relatorios/'.$item -> ano.'/RD'.$item -> ano.'.csv'))
                                <td ><a href="{{asset('storage/relatorios/'.$item -> ano.'/RD'.$item -> ano.'.csv')}}"><img src="{{asset('storage/icones/download.png')}}" width="20px"></a></td>
                            @else
                                <td ><a href="#"><img src="{{asset('storage/icones/parar.png')}}" width="20px"></a></td>
                            @endif
                            <td ><a href="{{route('d.edicao.edit', ['id' => $item-> id])}}"><img src="{{asset('storage/icones/editar.png')}}" width="20px"></a></td>
                        </tr>
                    @endforeach
                </table>    
            </div>
        @endif
    </div>
@endsection