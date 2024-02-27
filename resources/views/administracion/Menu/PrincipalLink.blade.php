@extends('layouts.main', ['activePage' => 'usuarios', 'titlePage' => 'GESTION DE MENU LINK'])

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <style>
        .tablex thead th {
            padding: 2px;
            text-align: center;
        }

        .tablex thead td {
            padding: 2px;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
        }

        .tablex tbody td,
        .tablex tbody th,
        .tablex tfoot td,
        .tablex tfoot th {
            padding: 2px;
        }

        .fuentex {
            font-size: 10px;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <div class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="card card-border">
                    <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                        {{-- <div class="card-widgets">
                            <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()"><i
                                    class="fa fa-redo"></i> Actualizar</button>
                            <button type="button" class="btn btn-primary btn-xs" onclick="add()"><i class="fa fa-plus"></i>
                                Nuevo</button>
                        </div> --}}
                        <h4 class="card-title"></h4>
                    </div>

                    <div class="card-body">
                        <div class="row justify-content-between ">
                            <div class="col-md-4">
                                <div class="row form-group">
                                    <label class="col-md-4 col-form-label">SISTEMAS</label>
                                    <div class="col-md-8">
                                        <select class="form-control btn-xs" name="sistema" id="sistema"
                                            onchange="listarDT();">
                                            @foreach ($sistemas as $item)
                                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row justify-content-end">
                                    <button type="button" class="btn btn-primary btn-xs" onclick="add()"><i
                                            class="fa fa-plus"></i> Nuevo</button>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="dtPrincipal" class="table table-striped table-bordered" style="width:100%">
                                        <thead class="cabecera-dataTable table-success-0 text-white">
                                            <!--th>Nº</th-->
                                            <th>Menu</th>
                                            {{-- <th>Link</th> --}}
                                            <th>Icono</th>
                                            {{-- <th>Para-metro</th> --}}
                                            <th>Posicion</th>
                                            <th>Grupo</th>
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

    <!-- Modal  Eliminar -->
    <div class="modal fade" id="confirmModalEliminar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
    </div> <!-- Fin Modal  Eliminar -->

    <!-- Bootstrap modal -->
    <div id="modal_form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="form" class="form-horizontal" autocomplete="off">
                        @csrf
                        <input type="hidden" class="form-control" id="id" name="id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Sistema<span class="required">*</span></label>
                                    <select class="form-control" name="sistema_id" id="sistema_id"
                                        onchange="cargarGrupo();">
                                        <option value="">Seleccionar</option>
                                        @foreach ($sistemas as $item)
                                            <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Grupo
                                        <!--span class="required">*</span-->
                                    </label>
                                    <select class="form-control" name="dependencia" id="dependencia" onchange="">
                                        <option value="0">Seleccionar</option>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Menu<span class="required">*</span></label>
                                    <input id="nombre" name="nombre" class="form-control" type="text"
                                        placeholder="Ingrese Menu">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Link<span class="required">*</span></label>
                                    <textarea id="link" name="link" class="form-control" cols="30" rows="5"></textarea>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label>Icono
                                    </label>
                                    <input id="icono" name="icono" class="form-control" type="text">
                                    <span class="help-block"></span>
                                </div>
                            </div> --}}

                            <div class="col-md-6">
                                <label>Icono<span class="required">*</span></label>
                                <div class="input-group">
                                    <input type="text" id="icono" name="icono" class="form-control"
                                        placeholder="Seleccione un Icono" maxlength="8">
                                    <span class="help-block"></span>
                                    <span class="input-group-append">
                                        <button type="button" class="btn waves-effect waves-light btn-primary"
                                            onclick="open_modal_icon();">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label>Para-metro
                                    </label>
                                    <input id="para-metro" name="para-metro" class="form-control" type="text">
                                    <span class="help-block"></span>
                                </div>
                            </div> --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Posicion<span class="required">*</span></label>
                                    <input id="posicion" name="posicion" class="form-control" type="number">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Guardar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Bootstrap modal -->

    <!-- Modal  Eliminar -->
    <div class="modal fade" id="modal_icon" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">

                        </div>
                        <div class="col-md-6">
                            <select class="form-control btn-xs" name="tipoicono" id="tipoicono"
                                onchange="cargar_tabla_icon()">
                                <option value="0">TIPO DE ICONOS</option>
                                <option value="1">Iconos de diseño de materiales</option>
                                <option value="2">Iconos de iones</option>
                                <option value="3">Fuente impresionante</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table id="tabla_icon" class="table table-striped table-bordered tablex" style="width:100%">
                                <thead class="cabecera-dataTable table-success-0 text-white">
                                    <th>Nº</th>
                                    <th>NOMBRE</th>
                                    <th>ICONO</th>
                                    <th>ACCIÓN</th>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    {{-- <button type="button" id="btnEliminar" name="btnEliminar" class="btn btn-danger">Guardar</button> --}}
                </div>
            </div>
        </div>
    </div> <!-- Fin Modal  Eliminar -->
@endsection

@section('js')
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> --}}
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>


    {{-- DATA TABLE --}}
    <script>
        $(document).ready(function() {
            var save_method = '';
            var table_principal;
            var table_icon;


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
            listarDT();
        });

        function add() {
            save_method = 'add';
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#modal_form').modal('show');
            $('.modal-title').text('Crear Nuevo Menu');
        };

        function cargarGrupo() {
            $.ajax({
                url: "{{ url('/') }}/Menu/cargarGrupo/" + $('#sistema_id').val(),
                type: 'get',
                success: function(data) {
                    console.log(data)
                    $("#dependencia option").remove();
                    var options = '<option value="0">SELECCIONAR</option>';
                    $.each(data.grupo, function(index, value) {
                        options += "<option value='" + value.id + "'>" + value.nombre + "</option>"
                    });
                    $("#dependencia").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function save() {
            $('#btnSave').text('guardando...');
            $('#btnSave').attr('disabled', true);
            var url;
            if (save_method == 'add') {
                url = "{{ url('/') }}/Menu/ajax_add_link";
                msgsuccess = "El registro fue creado exitosamente.";
                msgerror = "El registro no se pudo crear verifique las validaciones.";
            } else {
                url = "{{ url('/') }}/Menu/ajax_update_link";
                msgsuccess = "El registro fue actualizado exitosamente.";
                msgerror = "El registro no se pudo actualizar. Verifique la operación";
            }
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function(data) {
                    console.log(data)
                    if (data.status) {
                        $('#modal_form').modal('hide');
                        reload_table_principal(); //listarDT();
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
                    toastr.error(msgerror, 'Mensaje');
                    $('#btnSave').text('Guardar');
                    $('#btnSave').attr('disabled', false);
                }
            });
        };

        function edit(id) {
            save_method = 'update';
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $.ajax({
                url: "{{ url('/') }}/Menu/ajax_edit/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('[name="id"]').val(data.menu.id);
                    $('[name="sistema_id"]').val(data.menu.sistema_id);
                    $('[name="nombre"]').val(data.menu.nombre);
                    $('[name="link"]').val(data.menu.link);
                    $('[name="icono"]').val(data.menu.icono);
                    // $('[name="para-metro"]').val(data.menu.para-metro);
                    $('[name="posicion"]').val(data.menu.posicion);
                    $.ajax({
                        url: "{{ url('/') }}/Menu/cargarGrupo/" + data.menu.sistema_id,
                        type: 'get',
                        success: function(data2) {
                            $("#dependencia option").remove();
                            var options = '<option value="0">SELECCIONAR</option>';
                            $.each(data2.grupo, function(index, value) {
                                options += "<option value='" + value.id + "'>" + value
                                    .nombre + "</option>"
                            });
                            $("#dependencia").append(options);
                            $('[name="dependencia"]').val(data.menu.dependencia > 0 ? data.menu
                                .dependencia : 0);

                            $('#modal_form').modal('show');
                            $('.modal-title').text('Modificar Menu');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR);
                        },
                    });

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        };

        function borrar(id) {
            bootbox.confirm("Seguro desea Eliminar este registro?", function(result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ url('/') }}/Menu/ajax_delete/" + id,
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                            $('#modal_form').modal('hide');
                            reload_table_principal(); //listarDT();
                            toastr.success('El registro fue eliminado exitosamente.', 'Mensaje');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            toastr.error(
                                'No se puede eliminar este registro por seguridad de su base de datos, Contacte al Administrador del Sistema',
                                'Mensaje');
                        }
                    });
                }
            });
        };


        function listarDT() {
            table_principal = $('#dtPrincipal').DataTable({
                "ajax": "{{ route('menu.listar.link', '') }}/" + $('#sistema').val(),
                "columns": [{
                        data: 'nombre'
                    },
                    // {
                    //     data: 'link'
                    // },
                    {
                        data: 'icono'
                    },
                    /* {
                        data: 'para-metro'
                    }, */
                    {
                        data: 'posicion'
                    },
                    {
                        data: 'grupo'
                    },
                    {
                        data: 'estado'
                    },
                    {
                        data: 'action',
                        /* orderable: false */
                    }
                ],
                responsive: true,
                autoWidth: false,
                orderable: true,
                destroy: true,
                language: table_language
            });
        }

        function reload_table_principal() {
            table_principal.ajax.reload(null, false);
        }

        function estado(id, x) {
            bootbox.confirm("Seguro desea " + (x == 1 ? "desactivar" : "activar") + " este registro?", function(result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ url('/') }}/Menu/ajax_estado/" + id,
                        /* type: "POST", */
                        dataType: "JSON",
                        success: function(data) {
                            console.log(data);
                            reload_table_principal(); //listarDT();
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

        function open_modal_icon() {
            $('#modal_icon').modal('show');
            cargar_tabla_icon();
        }

        function cargar_tabla_icon() {
            table_icon = $('#tabla_icon').DataTable({
                // responsive: true,
                // autoWidth: false,
                // ordered: false,
                destroy: true,
                language: table_language,
                ajax: {
                    "url": "{{ route('icono.listar') }}",
                    "type": "GET",
                    "data": {
                        'tipo': $('#tipoicono').val(),
                    },
                },

            });
        }

        function seleccionar_icon(icon) {
            $('#icono').val(icon);
            $('#modal_icon').modal('hide');
        }
    </script>
@endsection
