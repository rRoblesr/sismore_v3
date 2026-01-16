<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>INICIAR SESION</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Responsive bootstrap 4 admin template" name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('/') }}public/assets/images/favicon.ico">

    <!-- App css -->
    <link href="{{ asset('/') }}public/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"
        id="bootstrap-stylesheet" />
    <link href="{{ asset('/') }}public/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/') }}public/assets/css/app.min.css" rel="stylesheet" type="text/css"
        id="app-stylesheet" />
    <link href="{{ asset('/') }}public/assets/css/otros/personalizado.css" rel="stylesheet" type="text/css" />

</head>

<body class=""
    style="background-image: url('{{ asset('/') }}/public/img/fondo1.jpg');background-size: 100% 100%;">
    <div class="container">

        <div class="row"><br> </div>
        {{-- <div class="row justify-content-center">
            <h4 class="text-white">GOBIERNO REGIONAL DE UCAYALI</h4>
        </div> --}}
        {{-- <div class="row justify-content-center">
            <h5 class="text-white text-center">INICIAR SESION</h5>
        </div> --}}
        {{-- <br><br> --}}
        <div class="row justify-content-center">
            <h2 class="text-white text-center">SISTEMA DE MONITOREO REGIONAL</h2>{{-- text-white --}}
        </div>
        <div class="row justify-content-center">
            <div class="">
                <img style="width:250px;text-align:center" src="{{ asset('public/img/logoblanco.png') }}">
            </div>
        </div>
        <br>
        <div class="account-pages">{{--  my-5 --}}
            <div class="container">



                <div class="row justify-content-center ">
                    <div class="col-md-4 {{-- col-lg-4 col-xl-5 --}}">

                        <div class="card border">
                            {{-- <div class="card-header bg-white   position-relative"> --}}
                            {{-- <div class="bg-overlay"></div> --}}
                            {{-- <h1 class="text-primary text-center mb-0">SISMORE</h1> --}}
                            {{-- <h1 class="text-black text-center">SISMORE</h1>    --}}
                            <div class="card" style="background:linear-gradient(60deg, #2053c0, #01143f)">
                                <h1 class="text-white text-center">SISMORE</h1>
                            </div>

                            <div class="card-body p-4 mt-0">
                                @if(session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif
                                @if($errors->has('usuario'))
                                    <div class="alert alert-danger">{{ $errors->first('usuario') }}</div>
                                @endif
                                @if($errors->has('password'))
                                    <div class="alert alert-danger">{{ $errors->first('password') }}</div>
                                @endif
                                @if(session('warning'))
                                    <div class="alert alert-warning">{{ session('warning') }}</div>
                                @endif
                                @if(session('params_missing'))
                                    <div class="alert alert-warning">{{ session('params_missing') }}</div>
                                @endif
                                <form class="form p-0" method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label for="exampleInputEmail1">Usuario</label>
                                        <input id="usuario" type="text"
                                            class="form-control @error('usuario') is-invalid @enderror" name="usuario"
                                            value="{{ old('usuario') }}" required autocomplete="usuario"
                                            placeholder="usuario" autofocus>
                                        @error('usuario')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="exampleInputPassword1">Contraseña</label>
                                        <input id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            required autocomplete="current-password" placeholder="Contraseña">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    {{-- <div class="form-group mb-3">
                                        <input class="form-control" type="email" required="" placeholder="Username">
                                    </div>

                                    <div class="form-group mb-3">
                                        <input class="form-control" type="password" required="" placeholder="Password">
                                    </div> --}}
                                    <div class="form-group row mb-0">
                                        <div class="col-sm-12">
                                            <a href="{{ route('password.request') }}"><i class="fa fa-lock mr-1"></i> ¿Olvidaste tu
                                                contraseña?</a>
                                        </div>
                                        {{-- <div class="col-sm-5 text-right">
                                            <a href="pages-register.html">Create an account</a>
                                        </div> --}}
                                    </div>
                                    {{-- <div class="form-group mb-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkbox-signin">
                                            <label class="custom-control-label" for="checkbox-signin">Remember me</label>
                                        </div>
                                    </div> --}}

                                    <div class="form-group text-center mt-2 mb-0">{{-- mb-4 --}}
                                        <button class="btn btn-primary waves-effect width-md waves-light"
                                            type="submit">Iniciar Sesión</button>
                                    </div>
                                    {{-- <div class="form-group row mb-0">
                                        <div class="col-sm-7">
                                            <a href="pages-recoverpw.html"><i class="fa fa-lock mr-1"></i> Forgot your
                                                password?</a>
                                        </div>
                                        <div class="col-sm-5 text-right">
                                            <a href="pages-register.html">Create an account</a>
                                        </div>
                                    </div> --}}
                                </form>
                            </div>
                            <!-- end card-body -->
                        </div>
                        <!-- end card -->

                        <!-- end row -->

                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

            </div>
        </div>


    </div>
</body>

</html>
