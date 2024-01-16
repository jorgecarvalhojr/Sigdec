@extends('layouts.home')
@section('title', 'Login')

@section('miolo')

  <div class="row">
    <div id="formulario" class="col-md-4 offset-4">
      <div class="form-group">
        <h3>ACESSO AO SISTEMA</h3>
      </div>

      @if ($message = Session::get('status'))
        <div class="alert alert-success alert-dismissible show" role="alert">
          <strong>{!!$message!!}</strong> 
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

      @isset ($success)
        <div class="alert alert-success alert-dismissible show" role="alert">
          <strong>{!!$success!!}</strong> 
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endisset

      @isset ($error)
        <div class="alert alert-danger alert-dismissible show" role="alert">
          <strong>{!!$error!!}</strong> 
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endisset

      @if ($message = Session::get('warning'))
        <div class="alert alert-warning alert-dismissible show" role="alert">
          <strong>{!!$message!!}</strong> 
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif
      <form id="login" method="POST" action="{{route('login.auth')}}">
          @csrf
          <!-- Email input -->
          <div class="form-outline md-4">
            <label class="form-label" for="email">E-Mail</label>
            <input type="email" id="email" name="email" class="form-control" required autofocus />
            @error('email')
            <div class="text-danger">
              {{ $message }}
            </div>  
            @enderror
          </div>
        
          <!-- Password input -->
          <div class="form-outline mb-4">
            <label class="form-label" for="password">Senha</label>
            <input type="password" id="password" name="password" class="form-control" required />
            @error('password')
            <div class="text-danger">
              {{ $message }}
            </div>  
            @enderror
          </div>
        
          <!-- 2 column grid layout for inline styling -->
          <div class="row mb-4">
            <div class="col d-flex justify-content-center">
              <!-- Checkbox -->
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember" checked />
                <label class="form-check-label" for="remember"> Continuar Conectado  </label>
              </div>
            </div>
        
            <div class="col">
              <!-- Simple link -->
              <a href="{{route('password.request')}}">Esqueci a senha!</a>
            </div>
          </div>
        
          <!-- Submit button -->
          <button type="submit" class="btn btn-primary btn-block mb-4">Entrar</button>
        
        </form>
      </div>
    </div>
  @endsection