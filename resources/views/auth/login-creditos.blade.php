<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Sistema | INICIAR SESION </title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- <link rel="icon" type="image/png" href="https://creditos.epicscode.com/public/src/img/favicon.png"> --}}
    <link rel="shortcut icon" href="{{ asset('/') }}public/assets/images/favicon.ico">

    {{-- <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,600,700,800" rel="stylesheet"> --}}

    <link rel="stylesheet" href="https://creditos.epicscode.com/public/plugins/bootstrap/dist/css/bootstrap.min.css">
    {{-- <link rel="stylesheet" href="https://creditos.epicscode.com/public/plugins/fontawesome-free/css/all.min.css"> --}}
    {{-- <link rel="stylesheet" href="https://creditos.epicscode.com/public/plugins/icon-kit/dist/css/iconkit.min.css"> --}}
    {{-- <link rel="stylesheet" href="https://creditos.epicscode.com/public/plugins/ionicons/dist/css/ionicons.min.css"> --}}
    {{-- <link rel="stylesheet"
        href="https://creditos.epicscode.com/public/plugins/perfect-scrollbar/css/perfect-scrollbar.css"> --}}

    {{-- <link rel="stylesheet"
        href="https://creditos.epicscode.com/public/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css"> --}}
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.20/dist/sweetalert2.min.css"> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.20/dist/sweetalert2.all.min.js"></script> --}}
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css"> -->
    {{-- <link id="bsdp-css" href="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.min.css"
        rel="stylesheet"> --}}

    <link rel="stylesheet" href="https://creditos.epicscode.com/public/dist/css/theme.min.css">

</head>

<body>
    <div class="wrapper">
        <div class="auth-wrapper">
            <div class="container-fluid h-100">
                <div class="row flex-row h-100 bg-white">
                    <div class="col-xl-8 col-lg-6 col-md-5 p-0 d-md-block d-lg-block d-sm-none d-none">
                        {{-- <div class="lavalite-bg"
                            style="background-image: url(https://creditos.epicscode.com/public/img/auth/login-bg.jpg)">
                            <div class="lavalite-overlay"></div>
                        </div> --}}
                        <div class="lavalite-bg"
                            style="background-image: url('{{ asset('/') }}/public/img/fondo1.jpg')">
                            {{-- <div class="lavalite-overlay"></div> --}}
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-7 my-auto p-0">
                        <div class="authentication-form mx-auto">
                            <div class="logo-centered" style="width: 260px">
                                {{-- <a href="#"><img class="img-fluid" src="https://creditos.epicscode.com/public/src/img/icon-p.svg" alt=""></a> --}}
                                <img class="img-fluid"
                                    src="{{ asset('public/img/logoblanco.png') }}">{{-- style="width:250px;text-align:center" --}}
                            </div>
                            <h3 class="text-center">Sistema de Monitoreo Regional</h3>
                            <p>Ingrese su Usuario y Contraseña.</p>
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    <input type="usuario" name="usuario" class="form-control"
                                        placeholder="Escriba su correo." required>
                                    {{-- <i class="ion ion-md-alert"></i> --}}
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control"
                                        placeholder="Escriba su contraseña." required>
                                    {{-- <i class="ion ion-md-albums"></i> --}}
                                </div>

                                <div class="sign-btn text-center">
                                    {{-- <button class="btn btn-theme btn-block"><i class="ik ik-arrow-right-circle"></i> Entrar</button> --}}
                                    <button class="btn btn-primary btn-block">
                                        {{-- <i class="ik ik-arrow-right-circle"></i> --}}
                                        Entrar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade apps-modal" id="appsModal" tabindex="-1" role="dialog" aria-labelledby="appsModalLabel"
        aria-hidden="true" data-backdrop="false">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i
                class="ik ik-x-circle"></i></button>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="quick-search">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4 ml-auto mr-auto">
                                <div class="input-wrap">
                                    <input type="text" id="quick-search" class="form-control"
                                        placeholder="Buscar..." />
                                    <i class="ik ik-search"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="modal-body d-flex align-items-center">
                    <div class="container">
                        <div class="apps-wrap">
                            <div class="app-item">
                                <a href="https://creditos.epicscode.com/"><i
                                        class="ik ik-home"></i><span>Inicio</span></a>
                            </div>
                            <div class="app-item">
                                <a href="https://creditos.epicscode.com/estacionar"><i
                                        class="fas fa-parking"></i><span>Estacionar</span></a>
                            </div>
                            <div class="app-item">
                                <a href="https://creditos.epicscode.com/clientes"><i
                                        class="ik ik-users"></i><span>Clientes</span></a>
                            </div>
                            <div class="app-item">
                                <a href="https://creditos.epicscode.com/matriculas"><i
                                        class="ik ik-shopping-cart"></i><span>Matrículas</span></a>
                            </div>
                            <div class="app-item">
                                <a href="https://creditos.epicscode.com/usuarios"><i
                                        class="ik ik-briefcase"></i><span>Usuarios</span></a>
                            </div>
                            <div class="app-item">
                                <a href="https://creditos.epicscode.com/sistema"><i
                                        class="ik ik-server"></i><span>Sistema</span></a>
                            </div>
                            <div class="app-item">
                                <a href="https://creditos.epicscode.com/formas"><i
                                        class="ik ik-clipboard"></i><span>Formas de Pago</span></a>
                            </div>
                            <div class="app-item">
                                <a href="https://creditos.epicscode.com/precios"><i
                                        class="ik ik-message-square"></i><span>Precios</span></a>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    {{-- <script type="text/javascript">
        base_url = 'https://creditos.epicscode.com/'
    </script> --}}
    {{-- <script src="https://creditos.epicscode.com/public/src/js/vendor/modernizr-2.8.3.min.js"></script> --}}
    <script src="https://creditos.epicscode.com/public/src/js/vendor/jquery-3.3.1.min.js"></script>{{-- 
    <script src="https://creditos.epicscode.com/public/plugins/popper.js/dist/umd/popper.min.js"></script> --}}
    <script src="https://creditos.epicscode.com/public/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
    {{-- <script src="https://creditos.epicscode.com/public/plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js"></script> --}}
    {{-- <script src="https://creditos.epicscode.com/public/plugins/screenfull/dist/screenfull.js"></script> --}}
{{--     <script src="https://creditos.epicscode.com/public/plugins/moment/moment.min.js"></script> --}}
    <script
        src="https://creditos.epicscode.com/public/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js">
    </script>

    {{-- <script src="https://creditos.epicscode.com/public/dist/js/theme.min.js"></script> --}}

    <!-- <link rel="stylesheet" href="https://creditos.epicscode.com/public/plugins/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css"> -->
    <!-- <script src="https://creditos.epicscode.com/public/plugins/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js">
    </script> -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-touchspin/4.2.0/jquery.bootstrap-touchspin.min.js"
        integrity="sha512-VzUh7hLMvCqgvfBmkd2OINf5/pHDbWGqxS+RFaL/fsgA+rT94LxTFnjlFkm0oKM5BXWbc9EjBQAuARqzGKLbcA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
    {{-- <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-touchspin/4.2.0/jquery.bootstrap-touchspin.css"
        integrity="sha512-M+RT/z+GO2INvbXyfkn7l5qN+g09mr0+JQ++nxLUfqAufrp/v5GIQ1k4IMn0BIHgxZK2Ss+YA+kHK4wJUKJK0A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
    <script>
       /*  $("#logotipo").change(function() {
            readImage(this);
        });

        function readImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imgPreview').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        } */
    </script>

</body>

</html>
