@extends('layouts.main', ['activePage' => 'usuarios', 'titlePage' => 'GESTION DE USUARIOS'])

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">

    <!-- Plugins css -->
    {{-- <link href="{{ asset('/') }}public/assets/libs/select2/select2.min.css" rel="stylesheet" type="text/css" /> --}}

    {{-- autocompletar --}}
    <link href="{{ asset('/') }}public/assets/jquery-ui/jquery-ui.css" rel="stylesheet" />

    <style>
        .centrarmodal {
            display: flex;
            justify-content: center;
            align-items: center;
            background: #000000c9 !important;
        }

        .ui-autocomplete {
            z-index: 215000000 !important;
        }
    </style>
@endsection

@section('content')
    <div class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-border">
                            <div class="card-header border-success-0 card-header-primary bg-transparent pb-0">
                                <div class="card-widgets">
                                    <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()"><i
                                            class="fa fa-redo"></i> Actualizar</button>
                                    <button type="button" class="btn btn-primary btn-xs" onclick="add()"><i
                                            class="fa fa-plus"></i> Nuevo</button>
                                </div>
                                <h3 class="card-title">Relacion de Usuarios </h3>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="dtPrincipal" class="table table-striped table-bordered"
                                                style="width:100%,font-size:12px">
                                                <thead class="cabecera-dataTable table-success-0 text-white">
                                                    <!--th>Nº</th-->
                                                    {{-- <th>DNI</th> --}}
                                                    <th>Entidad</th>
                                                    <th>Nombre</th>
                                                    <th>Usuario</th>
                                                    <th>Total Sistemas</th>
                                                    {{-- <th>Registro</th> --}}
                                                    <th>Estado</th>
                                                    <th>Aciones</th>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div> <!-- End row -->
            </div>
        </div> <!-- End row -->

    </div>

    <!-- Bootstrap modal -->
    {{-- <div id="modal_form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;"> --}}
    <div id="modal_form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
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
                    <form action="" id="form" class="form-horizontal" autocomplete="off">
                        @csrf
                        <input type="hidden" id="id" name="id">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card card-border">
                                    <div class="card-header  bg-transparent p-0">
                                        <h3 class="card-title"></h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>DNI<span class="required">*</span></label>
                                                        <div class="input-group">
                                                            <input type="number" id="dni" name="dni"
                                                                class="form-control" placeholder="Numero de Documento"
                                                                maxlength="8">
                                                            <span class="help-block"></span>
                                                            <span class="input-group-append">
                                                                <button type="button"
                                                                    class="btn waves-effect waves-light btn-primary"
                                                                    onclick="buscardni();" id="btnbuscardni">
                                                                    <i class="fas fa-search"></i>
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Nombre<span class="required">*</span></label>
                                                        <input id="nombre" name="nombre" class="form-control"
                                                            type="text" onkeyup="this.value=this.value.toUpperCase()">
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <label>Apellido Paterno<span class="required">*</span></label>
                                                        <input id="apellido1" name="apellido1" class="form-control"
                                                            type="text" onkeyup="this.value=this.value.toUpperCase()">
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Apellido Materno<span class="required">*</span></label>
                                                        <input id="apellido2" name="apellido2" class="form-control"
                                                            type="text" onkeyup="this.value=this.value.toUpperCase()">
                                                        <span class="help-block"></span>
                                                    </div>


                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>Sexo<span class="required">*</span></label>
                                                        <select id="sexo" name="sexo" class="form-control">
                                                            <option value="M">MASCULINO</option>
                                                            <option value="F">FEMENINO</option>
                                                        </select>
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Correo Electronico<span class="required">*</span></label>
                                                        <input id="email" name="email" class="form-control"
                                                            type="email" required>
                                                        <span class="help-block"></span>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>Celular<span class="required d-none">*</span></label>
                                                        <input id="celular" name="celular" class="form-control"
                                                            type="text">
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Entidad<span class="required">*</span></label>
                                                        <div class="input-group">
                                                            <input type="hidden" name="entidad" id="entidad">
                                                            <input type="text" name="entidadn" id="entidadn"
                                                                class="form-control" placeholder="Buscar">
                                                            <span class="help-block"></span>
                                                            <span class="input-group-append">
                                                                <button type="button"
                                                                    class="btn waves-effect waves-light btn-primary"
                                                                    onclick="add_entidad();">
                                                                    <i class="fas fa-plus"></i>
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>Oficina<span class="required">*</span></label>
                                                        <div class="input-group">
                                                            <input type="hidden" name="entidadgerencia"
                                                                id="entidadgerencia">
                                                            <input type="text" name="entidadgerencian"
                                                                id="entidadgerencian" class="form-control"
                                                                placeholder="Buscar">
                                                            <span class="help-block"></span>
                                                            <span class="input-group-append">
                                                                <button type="button"
                                                                    class="btn waves-effect waves-light btn-primary"
                                                                    onclick="add_gerencia();">
                                                                    <i class="fas fa-plus"></i>
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>Cargo<span class="required">*</span></label>
                                                        <input type="text" name="cargo" id="cargo"
                                                            class="form-control"
                                                            onkeyup="this.value=this.value.toUpperCase();"
                                                            placeholder="Ingrese Cargo">
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>Usuario<span class="required">*</span></label>
                                                        <input id="usuario" name="usuario" class="form-control"
                                                            type="text">
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Password<span class="required"
                                                                id="password-required">*</span></label>
                                                        <input id="password" name="password" class="form-control"
                                                            type="password">
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Bootstrap modal -->

    <!-- Bootstrap modal -->
    <div id="modal_perfil" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Seleccionar Perfil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="form_perfil" class="form-horizontal" autocomplete="off">
                        @csrf
                        <input type="hidden" class="form-control" id="usuario_id" name="usuario_id">
                        <div class="form-body">
                            <div class="form-group">
                                <label>Sistema<span class="required">*</span></label>
                                <select class="form-control" name="sistema_id" id="sistema_id"
                                    onchange="cargarPerfil();">
                                    <option value="">Seleccionar</option>
                                    @foreach ($sistemas as $item)
                                        <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label>Perfiles<span class="required">*</span></label>
                                <ul class="" id="perfiles"></ul>
                                <!--list-unstyled-->
                                <span class="help-block"></span>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnSavePerfil" onclick="savePerfil()"
                        class="btn btn-primary">Guardar</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="dtperfil" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <!--th>#</th-->
                                            <th>Sistema</th>
                                            <th>Perfil Asignado</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Bootstrap modal -->

    <!-- Modal  Eliminar -->
    {{-- <div class="modal fade" id="confirmModalEliminar" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Desea eliminar el registro seleccionado?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btnEliminar" name="btnEliminar" class="btn btn-danger">Confirmar</button>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- End Bootstrap modal -->

    <div id="modal_form_entidad" class="modal fade centrarmodal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="form_entidad" class="form-horizontal" autocomplete="off">
                        @csrf
                        <input type="hidden" id="dependencia" name="dependencia">
                        <div class="form-group">
                            <label>Entidad<span class="required">*</span></label>
                            <input id="entidad_nombre" name="entidad_nombre" class="form-control" type="text"
                                readonly>
                            <span class="help-block"></span>
                        </div>
                        {{-- <div class="form">
                            <div class="form-group">
                                <label>Codigo<span class="required">*</span></label>
                                <input id="codigo" name="codigo" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div> --}}
                        <div class="form">
                            <div class="form-group">
                                <label>Nombre<span class="required">*</span></label>
                                <input id="descripcion" name="descripcion" class="form-control" type="text"
                                    onkeyup="this.value=this.value.toUpperCase()" maxlength="200">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form">
                            <div class="form-group">
                                <label>Abreviado<span class="required">*</span></label>
                                <input id="apodo" name="apodo" class="form-control" type="text"
                                    onkeyup="this.value=this.value.toUpperCase()" maxlength="10">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnSaveEntidad" onclick="saveentidad()"
                        class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Bootstrap modal -->
@endsection

@section('js')
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
    {{-- <script src="{{ asset('/') }}public/assets/js/app.min.js"></script> --}}

    {{-- autocompletar --}}
    <script src="{{ asset('/') }}public/assets/jquery-ui/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            var save_method = '';
            var tabla_perfil;
            var tabla_principal;
            var form_entidad = 0;

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

            $('#entidadn').autocomplete({
                minLength: 2,
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('entidad.autocomplete') }}",
                        data: {
                            term: request.term,
                            dependencia: 0
                        },
                        dataType: "JSON",
                        success: function(data) {
                            response(data);
                        }
                    })
                },
                select: function(event, ui) {
                    $('#entidad').val(ui.item.id);
                }
            });
            $('#entidadgerencian').autocomplete({
                minLength: 2,
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('entidad.autocomplete') }}",
                        data: {
                            term: request.term,
                            dependencia: $('#entidad').val()
                        },
                        dataType: "JSON",
                        success: function(data) {
                            response(data);
                        }
                    })
                },
                select: function(event, ui) {
                    $('#entidadgerencia').val(ui.item.id);
                }
            });
            /* $('#entidadoficinan').autocomplete({
                minLength: 2,
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('entidad.autocomplete') }}",
                        data: {
                            term: request.term,
                            dependencia: $('#entidadgerencia').val()
                        },
                        dataType: "JSON",
                        success: function(data) {
                            response(data);
                        }
                    })
                },
                select: function(event, ui) {
                    $('#entidadoficina').val(ui.item.id);
                }
            }); */
            tablaPrincipal();
        });

        function buscardni() {
            if ($('#dni').val().length == 8) {
                $('#btnbuscardni').html("<i class='fa fa-spinner fa-spin'></i>");
                $.ajax({
                    url: "https://apiperu.dev/api/dni/" + $('#dni').val() +
                        "?api_token=06693f71e2099b8bfca1cefc7a36cdafef2b292f31ad4e5bcfb8535de0698c34",
                    type: 'GET',
                    beforeSend: function() {
                        $('[name="nombre"]').val("");
                        $('[name="apellido1"]').val("");
                        $('[name="apellido2"]').val("");
                    },
                    success: function(data) {
                        if (data.success) {
                            $("#nombre").val(data.data.nombres);
                            $("#apellido1").val(data.data.apellido_paterno);
                            $("#apellido2").val(data.data.apellido_materno);
                            //toastr.success("El registro fue creado exitosamente.", 'Mensaje');
                        } else {
                            alert('El DNI no existe');
                            toastr.error('El DNI no existe', 'Mensaje');
                        }
                        $('#btnbuscardni').html('<i class="fa fa-search"></i>');
                    },
                    error: function(data) {
                        $('#btnbuscardni').html('<i class="fa fa-search"></i>');
                        toastr.error('Error en la busqueda, Contacte al Administrador del Sistema', 'Mensaje');
                    }
                });
            } else {
                alert('INGRESE UN DNI DE 8 DIGITOS');
                toastr.error('INGRESE UN DNI DE 8 DIGITOS', 'Mensaje');
            }

        }

        function tablaPrincipal() {
            tabla_principal = $('#dtPrincipal').DataTable({
                "ajax": "{{ route('Usuario.Lista_DataTable') }}",
                "columns": [{
                    data: 'entidad'
                }, {
                    data: 'nombrecompleto'
                }, {
                    data: 'usuario'
                }, {
                    @if (auth()->user()->id == 49)
                        data: 'perfiles'
                    @else
                        data: 'cperfiles'
                    @endif

                }, {
                    data: 'estado'
                }, {
                    data: 'action',
                    orderable: false
                }],
                responsive: true,
                /* autoWidth: false, */
                //orderable: true,
                destroy: true,
                language: table_language
            });
        }

        function reload_table() {
            tabla_principal.ajax.reload(null, false);
        }

        function listarDTperfiles(usuario_id) {
            tabla_perfil = $('#dtperfil').DataTable({
                "ajax": "{{ url('/') }}/Usuario/DTSistemasAsignados/" + usuario_id,
                "columns": [{
                    data: 'sistema'
                }, {
                    data: 'perfil'
                }, {
                    data: 'accion'
                }, ],
                responsive: true,
                searching: false,
                paging: false,
                info: false,
                destroy: true,
                language: {
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
                }
            });
        }

        function reload_table_perfil() {
            tabla_perfil.ajax.reload(null, false);
        }

        function add() {
            save_method = 'add';
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.col-md-6').removeClass('has-error');
            $('.help-block').empty();
            $('#modal_form').modal('show');
            $('#modal_form .modal-title').text('Nuevo Usuario');
            $('#id').val('');
        }

        function save() {
            $('#btnSave').text('guardando...');
            $('#btnSave').attr('disabled', true);
            var url;
            if (save_method == 'add') {
                url = "{{ url('/') }}/Usuario/ajax_add";
                msgsuccess = "El registro fue creado exitosamente.";
                msgerror = "El registro no se pudo crear verifique las validaciones.";
            } else {
                url = "{{ url('/') }}/Usuario/ajax_update";
                msgsuccess = "El registro fue actualizado exitosamente.";
                msgerror = "El registro no se pudo actualizar. Verifique la operación";
            }
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {
                        $('#modal_form').modal('hide');
                        reload_table();
                        toastr.success(msgsuccess, 'Mensaje');
                    } else {
                        for (var i = 0; i < data.inputerror.length; i++) {
                            $('[name="' + data.inputerror[i] + '"]').parent().addClass('has-error');
                            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                        }
                    }
                    $('#btnSave').text('Guardar');
                    $('#btnSave').attr('disabled', false);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#btnSave').text('Guardar');
                    $('#btnSave').attr('disabled', false);
                    toastr.error(msgerror, 'Mensaje');
                }
            });
        }

        function edit(id) {
            save_method = 'update';
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.col-md-6').removeClass('has-error');
            $('.help-block').empty();
            $.ajax({
                url: "{{ url('/') }}/Usuario/ajax_edit/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('[name="id"]').val(data.usuario.id);
                    $('[name="dni"]').val(data.usuario.dni);
                    $('[name="nombre"]').val(data.usuario.nombre);
                    $('[name="apellido1"]').val(data.usuario.apellido1);
                    $('[name="apellido2"]').val(data.usuario.apellido2);
                    $('[name="sexo"]').val(data.usuario.sexo);
                    $('[name="email"]').val(data.usuario.email);
                    $('[name="celular"]').val(data.usuario.celular);
                    $('[name="usuario"]').val(data.usuario.usuario);
                    $('[name="cargo"]').val(data.usuario.cargo);

                    $('[name="entidad"]').val(data.entidad ? data.entidad.entidad : 0);
                    $('[name="entidadn"]').val(data.entidad ? data.entidad.entidadn : '');
                    $('[name="entidadgerencia"]').val(data.entidad ? data.entidad.oficina : 0);
                    $('[name="entidadgerencian"]').val(data.entidad ? data.entidad.oficinan : '');
                    //$('[name="entidadoficina"]').val(data.entidad ? data.entidad.oficina : 0);
                    //$('[name="entidadoficinan"]').val(data.entidad ? data.entidad.oficinan : '');

                    $('#modal_form').modal('show');
                    $('#modal_form .modal-title').text('Modificar Usuario');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error('Error get data from ajax', 'Mensaje');
                }
            });
        }

        function borrar(id) {
            bootbox.confirm("Seguro desea Eliminar este registro?", function(result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ url('/') }}/Usuario/ajax_delete/" + id,
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                            reload_table();
                            toastr.success('El registro fue eliminado exitosamente.', 'Mensaje');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            alert(
                                'No se puede eliminar este registro por seguridad de su base de datos, Contacte al Administrador del Usuario'
                            )
                            toastr.error(
                                'No se puede eliminar este registro por seguridad de su base de datos, Contacte al Administrador del Usuario',
                                'Mensaje');
                        }
                    });
                }
            });
        }

        function perfil(id) {
            $('#form_perfil')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#modal_perfil').modal('show');
            //$('#modal_perfil .modal-title').text('Seleccionar Perfil');
            $('#usuario_id').val(id);
            $("#perfiles li").remove();
            listarDTperfiles(id);
        }

        function cargarPerfil() {
            $.ajax({
                url: "{{ url('/') }}/Usuario/CargarPerfil/" + $('#sistema_id').val() + "/" + $('#usuario_id')
                    .val(),
                type: 'get',
                success: function(data) {
                    $("#perfiles li").remove();
                    var options =
                        "<li><div class='radio radio-primary'><input id='perfilx' name='perfil' type='radio' checked> <label for='perfilx'>NINGUNO</label></div></li>";
                    $.each(data.perfil, function(index, value) {
                        activo = '';
                        $.each(data.usuarioperfil, function(index2, value2) {
                            if (value2.perfil_id == value.id) activo = 'checked';
                        });
                        options += "<li><div class='radio radio-primary'><input id='perfil" + index +
                            "' name='perfil' type='radio' value='" + value.id + "' " + activo + ">" +
                            " <label for='perfil" + index + "'>" + value.nombre + "</label></div></li>";
                        //options += "<li><label><input id='perfil' name='perfil[]' type='checkbox' value='"+value.id+"' "+activo+"> "+value.nombre+"</label></li>";
                    });

                    $("#perfiles").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function savePerfil() {
            $('#btnSavePerfil').text('guardando...');
            $('#btnSavePerfil').attr('disabled', true);
            $.ajax({
                url: "{{ url('/') }}/Usuario/ajax_add_perfil",
                type: "POST",
                data: $('#form_perfil').serialize(),
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {
                        reload_table_perfil();
                        reload_table();
                        toastr.success('El registro fue creado exitosamente.', 'Mensaje');
                    } else {
                        for (var i = 0; i < data.inputerror.length; i++) {
                            $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error');
                            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                        }
                    }
                    $('#btnSavePerfil').text('Guardar');
                    $('#btnSavePerfil').attr('disabled', false);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error('El registro no se pudo crear verifique las validaciones.', 'Mensaje');
                    $('#btnSavePerfil').text('Guardar');
                    $('#btnSavePerfil').attr('disabled', false);
                }
            });
        }

        function borrarperfil(id1, id2) {
            bootbox.confirm("Seguro desea Eliminar este registro?", function(result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ url('/') }}/Usuario/ajax_delete_perfil/" + id1 + "/" + id2,
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                            reload_table_perfil();
                            reload_table();
                            $('#sistema_id').val('');
                            $("#perfiles").html('');
                            toastr.success('El registro fue eliminado exitosamente.', 'Mensaje');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            alert(
                                'No se puede eliminar este registro por seguridad de su base de datos, Contacte al Administrador del Usuario'
                            )
                            toastr.error(
                                'No se puede eliminar este registro por seguridad de su base de datos, Contacte al Administrador del Usuario',
                                'Mensaje');
                        }
                    });
                }
            });
        }

        function estadoUsuario(id, x) {
            bootbox.confirm("Seguro desea " + (x == 1 ? "desactivar" : "activar") + " este registro?", function(result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ url('/') }}/Usuario/ajax_estadousuario/" + id,
                        /* type: "POST", */
                        dataType: "JSON",
                        success: function(data) {
                            reload_table();
                            if (data.estado)
                                toastr.success('El registro fue Activo exitosamente.', 'Mensaje');
                            else
                                toastr.success('El registro fue Desactivado exitosamente.', 'Mensaje');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            toastr.error(
                                'No se puede cambiar estado por seguridad de su base de datos, Contacte al Administrador del Sistema.',
                                'Mensaje');
                        }
                    });
                }
            });
        };
        /* $('#btnEliminar').click(function() {
            $.ajax({
                // url:"Usuario/Eliminar/"+id,
                url: "{{ url('/') }}/Usuario/Eliminar/" + id,
                beforeSend: function() {
                    // $('#btnEliminar').text('Eliminando....');
                },
                success: function(data) {
                    setTimeout(function() {
                        $('#confirmModalEliminar').modal('hide');
                        toastr.success('El registro fue eliminado correctamente', 'Mensaje', {
                            timeOut: 3000
                        });
                        $('#dtPrincipal').DataTable().ajax.reload();
                    }, 100); //02 segundos
                }
            });
        }); */

        function cargar_entidad(id) {
            $("#entidad option").remove();
            $.ajax({
                url: "{{ route('entidad.ajax.cargar') }}",
                data: {
                    "dependencia": 0
                },
                type: 'get',
                success: function(data) {
                    $("#entidad option").remove();
                    var options = '<option value="">SELECCIONAR</option>';
                    $.each(data.entidades, function(index, value) {
                        ss = (id == "" ? "" : (id == value.id ? "selected" : ""));
                        options += "<option value='" + value.id + "' " + ss + ">" + value.nombre +
                            "</option>"
                    });
                    $("#entidad").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function add_entidad() {
            $('#form_entidad')[0].reset();
            $('#form_entidad .form-group').removeClass('has-error');
            $('#form_entidad .help-block').empty();
            $('#entidad_nombre').parent().hide();
            $('#modal_form_entidad .modal-title').text('Nueva Entidad');
            $('#modal_form_entidad').modal('show');
            form_entidad = 0;
        }

        function saveentidad() {
            $('#btnSaveEntidad').text('guardando...');
            $('#btnSaveEntidad').attr('disabled', true);
            $.ajax({
                url: "{{ route('entidad.ajax.addentidad') }}",
                type: "POST",
                data: $('#form_entidad').serialize(),
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {
                        $('#modal_form_entidad').modal('hide');
                        if (form_entidad == 0) {
                            $('#entidad').val(data.id);
                            $('#entidadn').val(data.nombre);
                        } else if (form_entidad == 1) {
                            $('#entidadgerencia').val(data.id);
                            $('#entidadgerencian').val(data.nombre);
                        }
                        toastr.success("El registro fue creado exitosamente.", 'Mensaje');
                    } else {
                        for (var i = 0; i < data.inputerror.length; i++) {
                            $('[name="' + data.inputerror[i] + '"]').parent().addClass('has-error');
                            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                        }
                    }
                    $('#btnSaveEntidad').text('Guardar');
                    $('#btnSaveEntidad').attr('disabled', false);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#btnSaveEntidad').text('Guardar');
                    $('#btnSaveEntidad').attr('disabled', false);
                    toastr.error("El registro no se pudo crear verifique las validaciones.", 'Mensaje');
                }
            });
        }

        function cargar_gerencia(id) {
            $("#entidadoficina option").remove();
            $.ajax({
                url: "{{ route('entidad.ajax.cargar') }}",
                data: {
                    "dependencia": $('#entidad').val()
                },
                type: 'get',
                success: function(data) {
                    $("#entidadgerencia option").remove();
                    var options = '<option value="">SELECCIONAR</option>';
                    $.each(data.entidades, function(index, value) {
                        ss = (id == "" ? "" : (id == value.id ? "selected" : ""));
                        options += "<option value='" + value.id + "' " + ss + ">" + value.nombre +
                            "</option>"
                    });
                    $("#entidadgerencia").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function add_gerencia() {
            entidadid = $('#entidad').val();
            if (entidadid == 0) {
                alert('ERROR: seleccionar una Entidad');
                return false;
            }
            entidadnombre = $('#entidadn').val();
            $('#form_entidad')[0].reset();
            $('#form_entidad .form-group').removeClass('has-error');
            $('#form_entidad .help-block').empty();
            $('#dependencia').val(entidadid);
            $('#entidad_nombre').val(entidadnombre.trim());
            $('#entidad_nombre').parent().show();
            $('#modal_form_entidad .modal-title').text('Nueva Oficina');
            $('#modal_form_entidad').modal('show');
            form_entidad = 1;
        }

        /* function savegerencia() {
            $('#btnSaveEntidad').text('guardando...');
            $('#btnSaveEntidad').attr('disabled', true);
            $.ajax({
                url: "{{ route('entidad.ajax.addentidad') }}",
                type: "POST",
                data: $('#form_entidad').serialize(),
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {
                        $('#modal_form_entidad').modal('hide');
                        cargar_gerencia(data.entidad);
                        toastr.success("El registro fue creado exitosamente.", 'Mensaje');
                    } else {
                        for (var i = 0; i < data.inputerror.length; i++) {
                            $('[name="' + data.inputerror[i] + '"]').parent().addClass('has-error');
                            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                        }
                    }
                    $('#btnSaveEntidad').text('Guardar');
                    $('#btnSaveEntidad').attr('disabled', false);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#btnSaveEntidad').text('Guardar');
                    $('#btnSaveEntidad').attr('disabled', false);
                    toastr.error("El registro no se pudo crear verifique las validaciones.", 'Mensaje');
                }
            });
        } */

        /* function cargar_oficina(id) {
            $.ajax({
                url: "{{ route('entidad.ajax.cargar') }}",
                data: {
                    "dependencia": $('#entidadgerencia').val()
                },
                type: 'get',
                success: function(data) {
                    $("#entidadoficina option").remove();
                    var options = '<option value="">SELECCIONAR</option>';
                    $.each(data.entidades, function(index, value) {
                        ss = (id == "" ? "" : (id == value.id ? "selected" : ""));
                        options += "<option value='" + value.id + "' " + ss + ">" + value.nombre +
                            "</option>"
                    });
                    $("#entidadoficina").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        } */

        /* function add_oficina() {
            entidadid = $('#entidadgerencia').val();
            if (entidadid == 0) {
                alert('ERROR: seleccionar una Gerencia');
                return false;
            }
            entidadnombre = $('#entidadgerencia option:selected').text();
            $('#form_entidad')[0].reset();
            $('#form_entidad .form-group').removeClass('has-error');
            $('#form_entidad .help-block').empty();
            $('#dependencia').val(entidadid);
            $('#entidad_nombre').val(entidadnombre.trim());
            $('#entidad_nombre').parent().show();
            $('#modal_form_entidad .modal-title').text('Nueva Oficina');
            $('#modal_form_entidad').modal('show');
            form_entidad = 2;
        } */

        /* function saveoficina() {
            $('#btnSaveOficina').text('guardando...');
            $('#btnSaveOficina').attr('disabled', true);
            $.ajax({
                url: "{{-- route('usuario.ajax.addoficina') --}}",
                type: "POST",
                data: $('#form_oficina').serialize(),
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {
                        $('#modal_form_oficina').modal('hide');
                        cargar_oficina(data.codigo);
                        toastr.success("El registro fue creado exitosamente.", 'Mensaje');
                    } else {
                        for (var i = 0; i < data.inputerror.length; i++) {
                            $('[name="' + data.inputerror[i] + '"]').parent().addClass('has-error');
                            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                        }
                    }
                    $('#btnSaveOficina').text('Guardar');
                    $('#btnSaveOficina').attr('disabled', false);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#btnSaveOficina').text('Guardar');
                    $('#btnSaveOficina').attr('disabled', false);
                    toastr.error("El registro no se pudo crear verifique las validaciones.", 'Mensaje');
                }
            });
        } */
    </script>
@endsection
