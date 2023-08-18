@extends('layouts.main_vacio')
@section('title','INICIAR SESION')
@section('content')


<div class="" style="background:linear-gradient(60deg, #165588, #011646)">
{{-- <div class="" style="background-image: url('{{ asset('img/fondo.PNG') }}'); background-size: cover; background-position: top center;align-items: center;"> --}}
    {{-- style="background-image: url('{{ asset('img/login.jpg') }}'); background-size: cover; background-position: top center;align-items: center;" --}}
     
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                
                <div>
                    <br><br>
                </div>

                <div class="card mt-4">
                    
                    <h4 class=" text-center mb-0 "  >
                        <br>
                        <p  class="font-weight-bold">Sistema de Monitoreo Regional</p>
                            
                        <img style="width:200px;text-align:center" src="{{ asset('img/LogoT02.jpg')}}">
                    </h4>

                    <div class="card-body p-4 mt-2">
                  
                        <form class="form" method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="exampleInputEmail1">Correo Electronico</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                name="email" value="{{ old('email') }}" required autocomplete="email"  placeholder="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">   
                                <label for="exampleInputPassword1">Password</label>                                
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                name="password" required autocomplete="current-password" placeholder="Password">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- <div class="form-group mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="checkbox-signin">
                                    <label class="custom-control-label" for="checkbox-signin">Recordarme</label>
                                </div>
                            </div> --}}

                            <div class="form-group text-center mt-5 mb-4">
                                <button class="btn btn-primary waves-effect width-md waves-light" type="submit"> Iniciar Sesión</button>
                            </div>

                            <br>                            
        
                        </form>

                    </div>                    
                    
                </div>

                <div>
                    <br><br><br><br><br><br><br> <br><br><br><br><br>
                </div>
                 
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>
</div>

@endsection
