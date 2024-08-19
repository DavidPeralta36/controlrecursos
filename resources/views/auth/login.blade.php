@extends('layouts.app')

@section('content')
<div class="container marTop">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card d-flex flex-row">
                <div class="card-body">
                    <div class="h3 text-center">Inicio de sesion</div>
                    <hr>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="d-flex flex-column">
                            <label for="email" class=" col-form-label text-md-left">Correo electronico</label>

                            <div >
                                <input id="email" type="email" class="form-control w-100 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group column">
                            <label for="password" class="col-form-label text-md-left">Contraseña</label> 

                            <div class="">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="loginButtonContainer">
                            <button type="submit" class="btn btn-primary">
                                Iniciar sesion
                            </button>

                            <div>
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    Recuerdame
                                </label>
                            </div>
                        </div>

                        @if (Route::has('password.request'))
                            <a class="btn btn-link p-0 mt-3" href="{{ route('password.request') }}">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </form>
                </div>

                <div class=" w-50 d-flex justify-content-center align-items-center">
                    <img src="{{ asset('assets/trama - copia.png') }}" alt="logo" class="w-100 h-100"> 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
