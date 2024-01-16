@extends('layouts.home')
@section('title', 'Redefinição de Senha')

@section('miolo')

<div class="container" style="justify-content: center">
  <div class="row">
    <div id="formulario" class="col-md-6 mx-auto">
      <div class="form-group">
        <h3>REDEFINIÇÃO DE SENHA</h3>
      </div>

      @if ($message = Session::get('status'))
        <div class="alert alert-success alert-dismissible show" role="alert">
          <strong>{!!$message!!}</strong> 
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

      <form id="forgot-password" method="POST" action="{{route('password.update')}}">
          @csrf

          <input type="hidden" name="token" id="token" value="{{$token}}">

          <!-- Email input -->
          <div class="form-group">
            <label class="form-label" for="email">E-Mail</label>
            <input type="email" id="email" name="email" value="{{old('email')}}" class="form-control" required autofocus />
            @error('email')
            <div class="text-danger">
              {{ $message }}
            </div>  
            @enderror
          </div>
          <!-- password input -->
          <div class="form-group">
            <label class="form-label" for="password">Senha</label>
            <input type="password" id="password" name="password" class="form-control" required/>
            @error('password')
            <div class="text-danger">
              {{ $message }}
            </div>  
            @enderror
          </div>
          <!-- confirma password input -->
          <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirme a senha</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required />
            @error('password_confirmation')
            <div class="text-danger">
              {{ $message }}
            </div>  
            @enderror
          </div>
        
          <div class="form-group">
            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-block md-4">Enviar</button>
          </div>
        
        </form>
      </div>
    </div>
  @endsection