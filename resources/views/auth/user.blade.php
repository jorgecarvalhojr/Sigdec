@extends('auth.home')
@section('title', 'Usuário')

@push('script')
  <script>
/* XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX */
function criaMascara(mascaraInput) {
  const maximoInput = document.getElementById(mascaraInput).maxLength;
  let valorInput = document.getElementById(mascaraInput).value;
  let valorSemPonto = document.getElementById(mascaraInput).value.replace(/([^0-9])+/g, "");
  
  const mascaras = {    
	
	  celular: valorInput.replace(/[^\d]/g, "").replace(/^(\d{2})(\d{5})(\d{4})/, "($1) $2-$3"),
	  
	  fixo: valorInput.replace(/[^\d]/g, "").replace(/^(\d{2})(\d{4})(\d{4})/, "($1) $2-$3"),

    cpf: valorInput.replace(/[^\d]/g, ""),
	  
  }

  valorInput.length === maximoInput ? document.getElementById(mascaraInput).value = mascaras[mascaraInput] : document.getElementById(mascaraInput).value = valorSemPonto;
};	
/* XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX */
function ValidaEmail()
{
var valido;		
  var str = $('#email').val();
  if(str!=""){
    var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    if(filter.test(str))
      valido = true;
    else
    {
      alert("Este endereçoo de e-mail não é válido!");
      $('#email').val('');
      $('#email').focus();
      return false;
    }
  }
}
/* XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX */
</script>
@endpush

@section('conteudo')
  <div class="row container">
    <div id="formulario" class="col-md-6">
      <div class="form-group">
        <h3>CADASTRO DE USUÁRIO</h3>
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

      <form id="user" method="POST" action="@if ($user->indice_adm == 0) {{route($rota)}} @else {{route($rota, ['id' => $user-> indice_adm])}} @endif ">
        @csrf

        <div class="row">
            <div class="col-md-8 form-group">
              <strong>Nome:</strong>
              <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', $user -> nome) }}" required>
              @error('nome')
                <div class="text-danger">
                  {{ $message }}
                </div>  
              @enderror
            </div>

            <div class="col-md-4 form-group">
              <strong>RG:</strong>
              <input type="text" class="form-control" id="matricula" name="matricula" value="{{ old('matricula', $user -> matricula) }}" required>
              @error('matricula')
                <div class="text-danger">
                  {{ $message }}
                </div>  
              @enderror
            </div>
        </div>
        
        <input type="hidden" name="uf" id="uf" value="{{Auth::user() -> uf}}" />

        <div class="row">
            <div class="col-md-4 form-group">
              <strong>Nome de Guerra:</strong>
              <input type="text" class="form-control" id="nome_guerra" name="nome_guerra" value="{{ old('nome_guerra', $user -> nome_guerra) }}" required>
              @error('nome_guerra')
                <div class="text-danger">
                  {{ $message }}
                </div>  
              @enderror
            </div>

            <div class="col-md-3 form-group">
              <strong>Posto/Graduação:</strong>
              <select class="form-control" id="posto" name="posto" required>
                <option value=""></option>
                @foreach($postos as $item)
                  <option {{($user -> posto == $item -> posto ? "selected" : "")}} value="{{$item -> posto}}">{{$item -> posto}}</option>
                @endforeach
              </select>
              @error('posto')
              <div class="text-danger">
                {{ $message }}
              </div>  
              @enderror
            </div>

            <div class="col-md-5 form-group">
              <strong>Município:</strong>
              <select class="form-control" id="municipio" name="municipio" required>
                <option value=""></option>
                @foreach($municipios as $item)
                  <option {{($user -> municipio == $item -> municipio ? "selected" : "")}} value="{{$item -> municipio}}">{{$item -> municipio}}</option>
                @endforeach
              </select>
              @error('municipio')
              <div class="text-danger">
                {{ $message }}
              </div>  
              @enderror
            </div>
        </div>
        
        <div class="row">
          <div class="col-md-6 form-group">
            <strong>Email:</strong>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user -> email) }}" onblur="ValidaEmail();" required>
            @error('email')
              <div class="text-danger">
                {{ $message }}
              </div>  
            @enderror
          </div>

          <div class="col-md-6 form-group">
            <strong>Órgão:</strong>
            <select class="form-control" id="orgao" name="orgao" required>
              @if(Auth::user() -> acesso == "1")
                <option value="{{Auth::user() -> orgao}}">{{Auth::user() -> Orgao['sigla']}}</option>
              @endif

              @if(Auth::user() -> acesso == "2")
                <option value=""></option>
                @foreach($orgaos as $item)
                  <option title="{{$item -> descricao}}" {{($user -> orgao == $item -> id_orgao ? "selected" : "")}} value="{{$item -> id_orgao}}">{{$item -> sigla}}</option>
                @endforeach
              @endif
            </select>
            @error('orgao')
            <div class="text-danger">
              {{ $message }}
            </div>  
            @enderror
          </div>

        </div>

        <div class="row">
          <div class="col-md-3 form-group ">
            <strong>Permissão:</strong>
            <select class="form-control" id="permissao" name="permissao" required>
              @if(Auth::user() -> permissao == "1")
                <option value="1">Usuário</option>
              @endif
              
              @if(Auth::user() -> permissao == "2")
              <option {{($user -> permissao == 1 ? "selected" : "")}} value="1">Usuário</option>
              <option {{($user -> permissao == 2 ? "selected" : "")}} value="2">Administrador</option>
              @endif
            </select>
            @error('permissao')
              <div class="text-danger">
                {{ $message }}
              </div>  
            @enderror
          </div>

          <div class="col-md-3 form-group">
            <strong>Acesso:</strong>
            <select class="form-control" id="acesso" name="acesso" required>
              @if(Auth::user() -> acesso == "1")
                <option value="1">Básico</option>
              @endif
              
              @if(Auth::user() -> acesso == "2")
              <option {{($user -> acesso == 1 ? "selected" : "")}} value="1">Básico</option>
              <option {{($user -> acesso == 2 ? "selected" : "")}} value="2">Avançado</option>
              @endif
            </select>
            @error('acesso')
              <div class="text-danger">
                {{ $message }}
              </div>  
            @enderror
          </div>

          <div class="col-md-3 form-group ">
            <strong>Ativo:</strong>
            <select class="form-control" id="ativo" name="ativo" required>
              <option value=""></option>
              <option {{ (old('ativo', $user -> ativo) == 'Sim' ? 'selected' : '') }} value="Sim">Sim</option>
              <option {{ (old('ativo', $user -> ativo) == 'Não' ? 'selected' : '') }} value="Não">Não</option>
            </select>
            @error('ativo')
            <div class="text-danger">
              {{ $message }}
            </div>  
            @enderror
          </div>

          <div class="col-md-3 form-group ">
            <strong>Senha:</strong>
            <input type="password" placeholder="8 caracteres" class="form-control" value="" id="senha" name="senha" >
            @error('senha')
              <div class="text-danger">
                {{ $message }}
              </div>  
            @enderror
          </div>
        </div>

        <div class="row">        
          <div class="col-md-12 form-group ">
            <button id="enviar" type="submit" class="btn btn-primary">Enviar</button>
          </div>
        </div>

      </form>
    </div>
  </div>
@endsection