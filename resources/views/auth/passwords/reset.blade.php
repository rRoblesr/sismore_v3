<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Restablecer Contraseña</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="{{ asset('/') }}public/assets/images/favicon.ico">
    <link href="{{ asset('/') }}public/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
    <link href="{{ asset('/') }}public/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/') }}public/assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />
    <link href="{{ asset('/') }}public/assets/css/otros/personalizado.css" rel="stylesheet" type="text/css" />
</head>
<body style="background-image: url('{{ asset('/') }}/public/img/fondo1.jpg');background-size: 100% 100%;">
    <div class="container">
        <div class="row mt-4"></div>
        <div class="row justify-content-center">
            <h2 class="text-white text-center">SISTEMA DE MONITOREO REGIONAL</h2>
        </div>
        <div class="row justify-content-center">
            <div>
                <img style="width:250px;text-align:center" src="{{ asset('public/img/logoblanco.png') }}">
            </div>
        </div>
        <br>
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card border">
                    <div class="card" style="background:linear-gradient(60deg, #2053c0, #01143f)">
                        <h1 class="text-white text-center">SISMORE</h1>
                    </div>
                    <div class="card-body p-4">
                        <h4 class="text-center mb-3">Restablecer contraseña</h4>
                        <p class="text-muted text-center">Ingresa tu nueva contraseña para tu cuenta</p>
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="form-group mb-3">
                                <label>Correo electrónico</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label>Nueva contraseña</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label>Confirmar contraseña</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                            <div class="form-group text-center mt-3 mb-0">
                                <button type="submit" class="btn btn-primary waves-effect width-md waves-light">
                                    Restablecer contraseña
                                </button>
                            </div>
                            <div class="form-group text-center mt-3">
                                <a href="{{ route('login') }}">Volver al inicio de sesión</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
