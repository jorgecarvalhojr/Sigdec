@extends('auth.home')
@section('title', 'Orgão')

@push('script')
<script>
//************************************************************************************

//************************************************************************************
</script>
@endpush

@section('conteudo')

    <div class="row container">
        <div id="formulario" class="col-md-6">
            <div class="form-group">
                <h3>CADASTRO DE ÓRGÃO DA SEDEC</h3>
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
            <form id="orgao" method="POST" action="@if ($orgao->id_orgao == 0) {{route($rota)}} @else {{route($rota, ['id' => $orgao -> id_orgao])}} @endif ">
            @csrf

                <div class="form-group row">
                    <div class="col-md-4">
                        <strong>Sigla do Órgão:</strong>
                        <input type="text" class="form-control" id="sigla" name="sigla" value="{{ old('sigla', $orgao -> sigla) }}" required>
                        @error('sigla')
                            <div class="text-danger">
                                {{ $message }}
                            </div>  
                        @enderror
                    </div>

                    <div class="col-md-8">
                        <strong>Descrição do Órgão:</strong>
                        <input type="text" class="form-control" id="descricao" name="descricao" value="{{ old('descricao', $orgao -> descricao) }}" required>
                        @error('descricao')
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
        @if ($estrutura->count() > 0)
            
            <div style="max-height: 70vh; overflow:auto; " class="col-md-6">
                <table style="font-size: 12px" border="1" cellpadding="2px" cellspacing="0" width="100%">
                    <tr align="center" bgcolor="#CCCCCCC">
                        <td>N°</td>
                        <td>Sigla</td>
                        <td>Descrição</td>
                        <td>Editar</td>
                    </tr>
                    @foreach ($estrutura as $item)
                        <tr align="center" @if($loop -> even) bgcolor="#CCCCCCD" @endif>
                            <td >{{ $loop -> index + 1 }}</td>
                            <td >{{ $item -> sigla }}</td>
                            <td >{{ $item -> descricao }}</td>
                            <td ><a href="{{route('orgao.edit', ['id' => $item-> id_orgao])}}"><img src="{{asset('storage/icones/editar.png')}}" width="20px"></a></td>
                        </tr>
                    @endforeach
                </table>    
            </div>
                   
        @endif
    </div>
@endsection