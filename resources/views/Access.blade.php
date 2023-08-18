<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>SISMORE</title>
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
    style="background-image: url('{{ asset('/') }}public/img/fondo1.jpg');background-size: 100% 100%;">
    <div class="container">

        <div class="row"><br> </div>
        {{-- <div class="row justify-content-center">
            <h4 class="text-white">GOBIERNO REGIONAL DE UCAYALI</h4> 
        </div> --}}
        <div class="row justify-content-center">
            <h5 class="text-white text-center">BIENVENIDO</h5>{{-- text-white --}}
        </div>
        <div class="row justify-content-center">
            <h2 class="text-white text-center">SISTEMA DE MONITOREO REGIONAL</h2>{{-- text-white --}}
        </div>
        <div class="row justify-content-center">
            <div class="">
                <img style="width:250px;text-align:center" src="{{ asset('public/img/logoblanco.png') }}">
            </div>
        </div>
        <br>
        <br>
        {{-- <div class="row justify-content-center">
            <div class="col-md-4">
                <img style="width:200px;text-align:center" src="{{ asset('img/LogoT02.jpg')}}">
            </div>
            <br>
        </div> --}}
        <div class="row justify-content-center">
            <h5 class="text-white text-center">SELECCIONAR MODULO</h5>{{-- text-white --}}
        </div>
        <div class="row justify-content-center">
            @foreach ($sistemas as $sistema)
                <div class="col-md-3 col-xl-3">
                    <div class="card-box">
                        <a href=" {{ route('sistema_acceder', $sistema->sistema_id) }}">
                            <div class="media">
                                <div class="avatar-md bg-info rounded-circle mr-2">
                                    <i class="{{ $sistema->icono }} avatar-title font-26 text-white"></i>
                                </div>
                                <div class="media-body align-self-center">
                                    <div class="text-right">
                                        {{-- <h4 class="font-20 my-0 font-weight-bold"><span
                                                data-plugin="counterup">15852</span></h4> --}}
                                        <p class="mb-0 mt-1 text-truncate">{{ $sistema->nombre }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                {{-- <h6 class="text-uppercase">Target <span class="float-right">60%</span></h6> --}}
                                <div class="progress progress-sm m-0">
                                    <div class="progress-bar bg-info" role="progressbar" aria-valuenow="60"
                                        aria-valuemin="0" aria-valuemax="100" style="width:  100%">
                                        <span class="sr-only">60% Complete</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- end card-box-->
                </div>
            @endforeach
        </div>

        <!-- Bootstrap modal -->
        <div id="modal_password_usuario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            style="overflow:auto">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="form_title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="form_password_usuario" class="form-horizontal" autocomplete="off">
                            @csrf
                            <input type="hidden" id="cid" name="cid">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">{{-- Datos Personales --}}</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="form">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>Usuario<span class="required">*</span></label>
                                                            <input id="cusuario" name="cusuario" class="form-control"
                                                                type="text" disabled>
                                                            <span class="help-block"></span>
                                                        </div>
                                                        {{-- <div class="col-md-6">
                                                            <label>Password Anterior<span class="required"
                                                                    id="password-required">*</span></label>
                                                            <input id="cpassword" name="cpassword"
                                                                class="form-control" type="password">
                                                            <span class="help-block"></span>
                                                        </div> --}}
                                                        <div class="col-md-6">
                                                            <label>Password Nuevo<span class="required"
                                                                    id="password-required">*</span></label>
                                                            <input id="cpassword2" name="cpassword2"
                                                                class="form-control" type="password">
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="row">
                                                        <div class="col-md-6">

                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Password Nuevo<span class="required"
                                                                    id="password-required">*</span></label>
                                                            <input id="cpassword2" name="cpassword2"
                                                                class="form-control" type="password">
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">En Otro Momento</button>
                        <button type="button" id="btnSaveCambio" onclick="saveCambioPassword()"
                            class="btn btn-primary">Guardar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <!-- End Bootstrap modal -->

    </div>
    <!-- Vendor js -->
    <script src="{{ asset('/') }}public/assets/js/vendor.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/moment/moment.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/jquery-scrollto/jquery.scrollTo.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/sweetalert2/sweetalert2.min.js"></script>

    <!-- Todo app -->
    <script src="{{ asset('/') }}public/assets/js/pages/jquery.todo.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/toastr/toastr.min.js"></script>
    <script src="{{ asset('/') }}public/assets/js/bootbox.js"></script>

    <script>
        $(document).ready(function() {

            $("input").change(function() {
                $(this).parent().removeClass('has-error');
                $(this).next().empty();
            });
            $("textarea").change(function() {
                $(this).parent().removeClass('has-error');
                $(this).next().empty();
            });
            $("select").change(function() {
                $(this).parent().removeClass('has-error');
                $(this).next().empty();
            });

            @if (Auth::user()->password == '$2y$10$HnvF5rPk9gvJz.4l8mzar.KTB6F5UKtcMT2qJrnH9D82D.3iGGHRi')
                editPasswordCambio({{ Auth::user()->id }}); //$('#modal_password_usuario').modal('show');
            @endif
        });

        function editPasswordCambio(id) {
            $('#form_password_usuario')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.col-md-6').removeClass('has-error');
            $('.help-block').empty();
            $.ajax({
                url: "{{ url('/') }}/Usuario/ajax_edit_basico/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('[name="cid"]').val(data.usuario.id);
                    $('[name="cusuario"]').val(data.usuario.usuario);

                    $('#modal_password_usuario').modal('show');
                    $('#modal_password_usuario .modal-title').text('Cambiar Contraseña Anterior');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error('Error get data from ajax', 'Mensaje');
                }
            });
        }

        function saveCambioPassword() {
            $('#btnSaveCambio').text('guardando...');
            $('#btnSaveCambio').attr('disabled', true);
            var url;
            url = "{{ url('/') }}/Usuario/ajax_update_password";
            msgsuccess = "El registro fue actualizado exitosamente.";
            msgerror = "El registro no se pudo actualizar. Verifique la operación";
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form_password_usuario').serialize(),
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {
                        $('#modal_password_usuario').modal('hide');
                        toastr.success(msgsuccess, 'Mensaje');
                    } else {
                        for (var i = 0; i < data.inputerror.length; i++) {
                            $('[name="' + data.inputerror[i] + '"]').parent().addClass('has-error');
                            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                        }
                    }
                    $('#btnSaveCambio').text('Guardar');
                    $('#btnSaveCambio').attr('disabled', false);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#btnSaveCambio').text('Guardar');
                    $('#btnSaveCambio').attr('disabled', false);
                    toastr.error(msgerror, 'Mensaje');
                }
            });
        }
    </script>

</body>

</html>
