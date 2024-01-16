@extends('auth.home')
@section('title', 'Viatura')

@push('script')
<script>
//************************************************************************************
function apagaForm() {
	let inputs = document.querySelectorAll('.form-control');
     for (let i = 0; i < inputs.length; i++) {
       inputs[i].value = '';
     }
}
//************************************************************************************
</script>
@endpush

@section('conteudo')

    <div class="row container">
        <div id="formulario" class="col-md-6">
            <div class="form-group">
                <h3>CADASTRO DE VIATURAS</h3>
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
            <form id="viatura" method="POST" action="@if ($viatura -> id_viatura == 0) {{route($rota)}} @else {{route($rota, ['id' => $viatura -> id_viatura])}} @endif ">
            @csrf

                <div class="form-group row">
                    <div class="col-md-3">
                        <strong>Prefixo:</strong>
                        <input class="form-control" type="text" name="prefixo" id="prefixo" value="{{old('prefixo', $viatura -> prefixo)}}" maxlength="30" placeholder="ARDC-001" required>
                        @error('prefixo')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <strong>Tipo:</strong>
                        <input class="form-control" type="text" name="tipo" id="tipo" value="{{old('tipo', $viatura -> tipo)}}" maxlength="30" placeholder="L200, Logan, Celta, Siena" required>
                        @error('tipo')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <strong>Placa:</strong>
                        <input class="form-control" type="text" name="placa" id="placa" value="{{old('placa', $viatura -> placa)}}"  maxlength="8" required>
                        @error('placa')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <strong>Baixada:</strong>
                        <select class="form-control" id="baixada" name="baixada" required>
                            <option value=""></option>
                            <option {{ (old('baixada', $viatura->baixada) == 'Sim' ? 'selected' : '') }} value="Sim">Sim</option>
                            <option {{ (old('baixada', $viatura->baixada) == 'Não' ? 'selected' : '') }} value="Não">Não</option>
                        </select>
                        @error('baixada')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror
                    </div>

                </div>

                <div class="row form-group">
                    <div class="col-md-6">
                        <strong>Órgão Patrimoniado:</strong>
                        <select class="form-control" name="orgao_carga" id="orgao_carga">
                            <option value=""></option>
                            @foreach($orgaos as $item)
                                <option {{($viatura -> orgao_carga == $item -> sigla ? "selected" : "")}} value="{{$item -> sigla}}">{{$item -> sigla}}</option>
                            @endforeach
                        </select>
                        @error('orgao_carga')
                        <div class="text-danger">
                            {{ $message }}
                        </div>  
                    @enderror

                    </div>

                    <div class="col-md-6">
                        <strong>Órgão que utiliza a Viatura:</strong>
                        <select class="form-control" name="orgao_utilizado" id="orgao_utilizado">
                            <option value=""></option>
                            @foreach($orgaos as $item)
                                <option {{($viatura -> orgao_utilizado == $item -> sigla ? "selected" : "")}} value="{{$item -> sigla}}">{{$item -> sigla}}</option>
                            @endforeach
                        </select>
                        @error('orgao_utilizado')
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
                        <button id="limpar" type="button" onclick="apagaForm()" class="btn btn-primary">Limpar</button>
                    </div>
                </div>
    
            </form>
        </div>
        @if ($viaturas->count() > 0)
            
            <div style="max-height: 70vh; overflow:auto; " class="col-md-6">
                <table style="font-size: 12px" border="1" cellpadding="2px" cellspacing="0" width="100%">
                    <tr align="center" bgcolor="#CCCCCCC">
                        <td>N°</td>
                        <td>Prefixo</td>
                        <td>Tipo de Veículo</td>
                        <td>Placa</td>
                        <td>Baixada</td>
                        <td>Editar</td>
                    </tr>
                    @foreach ($viaturas as $item)
                        <tr align="center" @if($loop -> even) bgcolor="#CCCCCCD" @endif>
                            <td >{{ $loop -> index + 1 }}</td>
                            <td >{{ $item -> prefixo }}</td>
                            <td >{{ $item -> tipo }}</td>
                            <td >{{ $item -> placa }}</td>
                            <td @if($item -> baixada == 'Não')  style="color: green" @elseif($item -> baixada == 'Sim')  style="color: red" @endif >{{ $item -> baixada }}</td>
                            <td ><a href="{{route('viatura.edit', ['id' => $item-> id_viatura])}}"><img src="{{asset('storage/icones/editar.png')}}" width="20px"></a></td>
                        </tr>
                    @endforeach
                </table>    
            </div>
                   
        @endif
    </div>
@endsection