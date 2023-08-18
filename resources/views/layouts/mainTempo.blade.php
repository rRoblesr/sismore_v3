<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>SISMORE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @yield('css')
    <meta content="Responsive bootstrap 4 admin template" name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('/') }}public/assets/images/favicon.ico">

    <!-- Plugins css-->
    <link href="{{ asset('/') }}public/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet"
        type="text/css" />

    <!-- App css -->
    <link href="{{ asset('/') }}public/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"
        id="bootstrap-stylesheet" />
    <link href="{{ asset('/') }}public/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    
    <link href="{{ asset('/') }}public/assets/css/app.min.css" rel="stylesheet" type="text/css"
        id="app-stylesheet" />

    {{-- {{assets('/')}} --}}
    <!-- estilos personalizados XD-->
    <link rel="stylesheet" href="{{ asset('/') }}public/assets/css/otros/personalizado.css" type='text/css'>
</head>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        @auth()
            @include('layouts.navbars.navs.auth')
            @include('layouts.navbars.sidebar')
            {{-- @include('layouts.navbars.sidebarRight') --}}
            <!-- start page title -->

            <div class="content-page">
                <div class="content">
                    <div class="container-fluid">
                        @if (session('sistema_id') != 5)
                            <div class="row">
                                <div class="col-12">
                                    @if ($titlePage != '')
                                        <div class="page-title-box">
                                            <h4 class="page-title">{{ $titlePage }}</h4>
                                            <div class="page-title-right">
                                                <ol class="breadcrumb p-0 m-0">
                                                    @isset($breadcrumb)
                                                        @foreach ($breadcrumb as $key => $item)
                                                            @if ($key == count($breadcrumb) - 1)
                                                                <li class="breadcrumb-item">{{ $item['titulo'] }}</li>
                                                            @else
                                                                <li class="breadcrumb-item"><a
                                                                        href="{{ $item['url'] }}">{{ $item['titulo'] }}</a>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    @endisset
                                                </ol>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    @else
                                        <br>
                                    @endif

                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-12">
                                    @if ($titlePage != '')
                                        <div class="page-title-box">
                                            <h4 class="page-title">Ejecución Presupuestal De La Región Ucayali</h4>
                                            <div class="page-title-right">
                                                <ol class="breadcrumb p-0 m-0">
                                                    @if (isset($impG))
                                                        @php
                                                            $mes = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
                                                        @endphp
                                                        Actualizado al
                                                        {{ date('d', strtotime($impG->fechaActualizacion)); }} de
                                                        {{ $mes[date('m', strtotime($impG->fechaActualizacion)) - 1]; }} del
                                                        {{ date('Y', strtotime($impG->fechaActualizacion));}}
                                                    @endif
                                                </ol>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    @else
                                        <br>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @yield('content')
                        
                    </div>
                </div>
            </div>

            @include('layouts.footers.auth')

        @endauth

        @guest()
            @yield('content')
        @endguest

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        {{-- <div class="content-page">
        </div> --}}
        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- Bootstrap modal -->
    {{-- <div id="modal_form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;"> --}}
    <div id="modal_perfil_usuario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
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
                    <form action="" id="form_perfil_usuario" class="form-horizontal" autocomplete="off">
                        @csrf
                        <input type="hidden" id="idp" name="idp">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card pb-0">
                                    {{-- <div class="card-header">
                                        <h3 class="card-title"></h3>
                                    </div> --}}
                                    <div class="card-body">
                                        <div class="form">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>DNI<span class="required">*</span></label>
                                                        <input id="dnip" name="dnip" class="form-control"
                                                            type="text" maxlength="8"
                                                            onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Nombres<span class="required">*</span></label>
                                                        <input id="nombrep" name="nombrep" class="form-control"
                                                            type="text"
                                                            onkeyup="this.value=this.value.toUpperCase()">
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <label>Apellidos<span class="required">*</span></label>
                                                        <input id="apellidosp" name="apellidosp" class="form-control"
                                                            type="text"
                                                            onkeyup="this.value=this.value.toUpperCase()">
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Sexo<span class="required">*</span></label>
                                                        <select id="sexop" name="sexop" class="form-control">
                                                            <option value="M">MASCULINO</option>
                                                            <option value="F">FEMENINO</option>
                                                        </select>
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>Correo Electronico<span
                                                                class="required">*</span></label>
                                                        <input id="emailp" name="emailp" class="form-control"
                                                            type="email" required>
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Celular
                                                            <!--span class="required">*</span-->
                                                        </label>
                                                        <input id="celularp" name="celularp" class="form-control"
                                                            type="text">
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>Gerencia<span class="required">*</span></label>
                                                        <select name="entidadgerenciap" id="entidadgerenciap"
                                                            class="form-control" onchange="cargar_oficina2('')"
                                                            disabled>
                                                            <option value="">SELECCIONAR</option>
                                                        </select>
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Oficinas<span class="required">*</span></label>
                                                        <select name="entidadoficinap" id="entidadoficinap"
                                                            class="form-control" disabled>
                                                            <option value="">SELECCIONAR</option>
                                                        </select>
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>Usuario<span class="required">*</span></label>
                                                        <input id="usuariop" name="usuariop" class="form-control"
                                                            type="text">
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Contraseña<span class="required"
                                                                id="password-required">*</span></label>
                                                        <input id="passwordp" name="passwordp" class="form-control"
                                                            type="password">
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- .form -->
                                    </div>
                                    <!-- card-body -->
                                </div>
                                <!-- card -->
                            </div>
                            <!-- col -->
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnSavePerfilUsuario" onclick="savePerfilUsuario()"
                        class="btn btn-primary">Guardar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- End Bootstrap modal -->



    {{-- <script src="{{ asset('/') }}public/assets/jquery-ui/external/jquery/jquery.js"></script> --}}
    <!-- Vendor js -->
    <script src="{{ asset('/') }}public/assets/js/vendor.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/moment/moment.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/jquery-scrollto/jquery.scrollTo.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/sweetalert2/sweetalert2.min.js"></script>

    <!-- Chat app -->
    {{-- <script src="{{ asset('/') }}public/assets/js/pages/jquery.chat.js"></script> --}}

    <!-- Todo app -->
    {{-- <script src="{{ asset('/') }}public/assets/js/pages/jquery.todo.js"></script> --}}

    <script src="{{ asset('/') }}public/assets/libs/toastr/toastr.min.js"></script>
    <script src="{{ asset('/') }}public/assets/js/bootbox.js"></script>


    <!-- flot chart -->
    {{-- <script src="assets/libs/flot-charts/jquery.flot.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.time.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.tooltip.min.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.resize.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.pie.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.selection.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.stack.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.crosshair.js"></script> --}}

    <!-- Dashboard init JS -->
    {{-- <script src="assets/js/pages/dashboard.init.js"></script> --}}

    <!-- App js -->
    <script src="{{ asset('/') }}public/assets/js/app.min.js"></script>
    <script>
        /* var paleta_colores = ['#058DC7', '#50B432', '#9D561B', '#DDDF00', '#24CBE5', '#64E572', '#9F9655', '#FFF263',
                        '#6AF9C4'
                    ]; */
        var paleta_colores = ['#317eeb', '#ef5350', '#33b86c', '#33b86c', '#33b86c', '#6c757d', '#ec407a', '#7e57c2',
            '#ffd740'
        ];
        var table_language = {
            "lengthMenu": "Mostrar " +
                `<select class="custom-select custom-select-sm form-control form-control-sm">
                        <option value = '10'> 10</option>
                        <option value = '25'> 25</option>
                        <option value = '50'> 50</option>
                        <option value = '100'>100</option>
                        <option value = '-1'>Todos</option>
                        </select>` + " registros por página",
            "info": "Mostrando la página _PAGE_ de _PAGES_",
            "infoEmpty": "No records available",
            "infoFiltered": "(Filtrado de _MAX_ registros totales)",
            "emptyTable": "No hay datos disponibles en la tabla.",
            "info": "Del _START_ al _END_ de _TOTAL_ registros ",
            "infoEmpty": "Mostrando 0 registros de un total de 0. registros",
            "infoFiltered": "(filtrados de un total de _MAX_ )",
            "infoPostFix": "",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "searchPlaceholder": "Dato para buscar",
            "zeroRecords": "No se han encontrado coincidencias.",
            "paginate": {
                "next": "siguiente",
                "previous": "anterior"
            }
        };
        $(document).ready(function() {
            /* $('#modal_password_usuario').modal('show'); */
            cargar_entidad(0);
        });
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "2000",
            "extendedTimeOut": "1000"
        }

        function savePerfilUsuario() {
            $('#btnSavePerfilUsuario').text('guardando...');
            $('#btnSavePerfilUsuario').attr('disabled', true);
            var url;
            url = "{{ url('/') }}/Usuario/ajax_updateaux";
            msgsuccess = "El registro fue actualizado exitosamente.";
            msgerror = "El registro no se pudo actualizar. Verifique la operación";
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form_perfil_usuario').serialize(),
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {
                        $('#modal_perfil_usuario').modal('hide');
                        toastr.success(msgsuccess, 'Mensaje');
                    } else {
                        for (var i = 0; i < data.inputerror.length; i++) {
                            $('[name="' + data.inputerror[i] + '"]').parent().addClass('has-error');
                            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                        }
                    }
                    $('#btnSavePerfilUsuario').text('Guardar');
                    $('#btnSavePerfilUsuario').attr('disabled', false);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#btnSavePerfilUsuario').text('Guardar');
                    $('#btnSavePerfilUsuario').attr('disabled', false);
                    toastr.error(msgerror, 'Mensaje');
                }
            });
        }

        function editPerfilUsuario(id) {
            $('#form_perfil_usuario')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.col-md-6').removeClass('has-error');
            $('.help-block').empty();
            $.ajax({
                url: "{{ url('/') }}/Usuario/ajax_edit/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('[name="idp"]').val(data.usuario.id);
                    $('[name="dnip"]').val(data.usuario.dni);
                    $('[name="nombrep"]').val(data.usuario.nombre);
                    $('[name="apellidosp"]').val(data.usuario.apellidos);
                    $('[name="sexop"]').val(data.usuario.sexo);
                    $('[name="emailp"]').val(data.usuario.email);
                    $('[name="celularp"]').val(data.usuario.celular);
                    $('[name="usuariop"]').val(data.usuario.usuario);
                    $('[name="tipop"]').val(data.usuario.tipo);
                    if (data.entidad) {
                        $('[name="unidadejecutorap"]').val(data.entidad.entidad_id);
                        $.ajax({
                            url: "{{ url('/') }}/Usuario/CargarGerencia/" + data.entidad
                                .entidad_id,
                            type: 'get',
                            success: function(data2) {
                                $("#entidadgerenciap option").remove();
                                var options = '<option value="">SELECCIONAR</option>';
                                $.each(data2.gerencias, function(index, value) {
                                    ss = (data.entidad.gerencia_id == value.id ?
                                        "selected" :
                                        "");
                                    options += "<option value='" + value.id + "' " + ss +
                                        ">" +
                                        value.entidad +
                                        "</option>"
                                });
                                $("#entidadgerenciap").append(options);

                                $.ajax({
                                    url: "{{ url('/') }}/Usuario/CargarOficina/" +
                                        data
                                        .entidad.gerencia_id,
                                    type: 'get',
                                    success: function(data3) {
                                        $("#entidadoficinap option").remove();
                                        var options =
                                            '<option value="">SELECCIONAR</option>';
                                        $.each(data3.oficinas, function(index, value) {
                                            ss = (data.entidad.oficina_id ==
                                                value
                                                .id ? "selected" : "");
                                            options += "<option value='" + value
                                                .id + "' " + ss + ">" + value
                                                .entidad + "</option>"
                                        });
                                        $("#entidadoficinap").append(options);
                                    },
                                    error: function(jqXHR, textStatus, errorThrown) {
                                        console.log(jqXHR);
                                    },
                                });
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log(jqXHR);
                            },
                        });
                    }
                    $('#modal_perfil_usuario').modal('show');
                    $('#modal_perfil_usuario .modal-title').text('Modificar Datos Personales');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error('Error get data from ajax', 'Mensaje');
                }
            });
        }

        function cargar_entidad(id) {
            $.ajax({
                url: "{{ url('/') }}/Usuario/CargarEntidad/3",
                type: 'get',
                success: function(data) {
                    $("#unidadejecutorap option").remove();
                    var options = '<option value="">SELECCIONAR</option>';
                    $.each(data.unidadejecutadora, function(index, value) {
                        ss = (id == "" ? "" : (id == value.id ? "selected" : ""));
                        options += "<option value='" + value.id + "' " + ss + ">" + value
                            .unidad_ejecutora +
                            "</option>"
                    });
                    $("#unidadejecutorap").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargar_gerencia2(id) {
            $("#entidadoficina option").remove();
            $.ajax({
                url: "{{ url('/') }}/Usuario/CargarGerencia/" + $('#unidadejecutorap').val(),
                type: 'get',
                success: function(data) {
                    $("#entidadgerenciap option").remove();
                    var options = '<option value="">SELECCIONAR</option>';
                    $.each(data.gerencias, function(index, value) {
                        ss = (id == "" ? "" : (id == value.id ? "selected" : ""));
                        options += "<option value='" + value.id + "' " + ss + ">" + value.entidad +
                            "</option>"
                    });
                    $("#entidadgerenciap").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargar_oficina2(id) {
            $.ajax({
                url: "{{ url('/') }}/Usuario/CargarOficina/" + $('#entidadgerenciap').val(),
                type: 'get',
                success: function(data) {
                    $("#entidadoficinap option").remove();
                    var options = '<option value="">SELECCIONAR</option>';
                    $.each(data.oficinas, function(index, value) {
                        ss = (id == "" ? "" : (id == value.id ? "selected" : ""));
                        options += "<option value='" + value.id + "' " + ss + ">" + value.entidad +
                            "</option>"
                    });
                    $("#entidadoficinap").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }
    </script>
    @yield('js')
</body>

</html>
