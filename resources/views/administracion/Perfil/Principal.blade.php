@extends('layouts.main', ['activePage' => 'usuarios', 'titlePage' => 'GESTION DE PERFIL'])

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endsection

@section('content')
    <div class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-border">
                            <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                                <h4 class="card-title"></h4>
                            </div>

                            <div class="card-body">
                                <div class="row justify-content-between ">
                                    <div class="col-md-4">
                                        <div class="row form-group">
                                            <label class="col-md-4 col-form-label">SISTEMAS</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="sistema" id="sistema"
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
                                            <br>
                                            <table id="dtPrincipal" class="table table-striped table-bordered"
                                                style="width:100%">
                                                <thead class="cabecera-dataTable table-success-0 text-white" id="xxx">
                                                    <!--th>Nº</th-->
                                                    <th>Nombre</th>
                                                    <th>Administrando</th>
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
                </div>  
            </div>
        </div>  

    </div>

    <!-- Bootstrap modal -->
    <div id="modal_form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog">
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
                        <div class="form-body">
                            <div class="form-group">
                                <label>Sistema<span class="required">*</span></label>
                                <select class="form-control" name="sistema_id" id="sistema_id">
                                    <option value="">Seleccionar</option>
                                    @foreach ($sistemas as $item)
                                        <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label>Nombre<span class="required">*</span></label>
                                <input id="nombre" name="nombre" class="form-control" type="text"
                                    onkeyup="this.value=this.value.toUpperCase()">
                                <span class="help-block"></span>
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

    <!-- Bootstrap modal -->
    <div id="menu_form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="menu" class="form-horizontal" autocomplete="off">
                        @csrf
                        <input type="hidden" name="msistema_id" id="msistema_id">
                        <div class="form-body">
                            <div class="form-group" id="listarmenu"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnSavemenu" onclick="savemenu()"
                        class="btn btn-primary">Guardar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Bootstrap modal -->

    <!-- Bootstrap modal -->
    <div id="modal_sistemas" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="form_sistemas" class="form-horizontal" autocomplete="off">
                        @csrf
                        <input type="hidden" name="csistema_id" id="csistema_id">
                        <input type="hidden" name="cperfil_id" id="cperfil_id">
                        <div class="form-body">
                            <div class="form-group">
                                <ul id="listarsistema">
                                    {{-- @foreach ($sistemas2 as $pos => $item)
                                <li><label><input type="checkbox" id="checkbox{{$pos}}" name="csistemas[]" value="{{$item->id}}"> {{$item->nombre}}</label></li>
                            @endforeach --}}
                                </ul>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnSaveSistemas" onclick="savesistemas()"
                        class="btn btn-primary">Guardar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Bootstrap modal -->
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
            $('.modal-title').text('Crear Nuevo Perfil');
        };

        function save() {
            $('#btnSave').text('guardando...');
            $('#btnSave').attr('disabled', true);
            var url;
            if (save_method == 'add') {
                url = "{{ url('/') }}/Perfil/ajax_add";
                msgsuccess = "El registro fue creado exitosamente.";
                msgerror = "El registro no se pudo crear verifique las validaciones.";
            } else {
                url = "{{ url('/') }}/Perfil/ajax_update";
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
                url: "{{ url('/') }}/Perfil/ajax_edit/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('[name="id"]').val(data.menu.id);
                    $('[name="sistema_id"]').val(data.menu.sistema_id);
                    $('[name="nombre"]').val(data.menu.nombre);
                    $('#modal_form').modal('show');
                    $('.modal-title').text('Modificar Perfil');
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
                        url: "{{ url('/') }}/Perfil/ajax_delete/" + id,
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
                "ajax": "{{ url('/') }}/Perfil/listarDT/" + $('#sistema').val(),
                "columns": [{
                    data: 'nombre',
                }, {
                    data: 'sistemas',
                    visible: $('#sistema').val() == 4
                }, {
                    data: 'estado'
                }, {
                    data: 'action',
                    orderable: false
                }],
                responsive: true,
                autoWidth: false,
                orderable: false,
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

        function reload_table_principal() {
            table_principal.ajax.reload(null, false);
        }

        function menu(id) {
            $('#msistema_id').val($('#sistema').val());
            $.ajax({
                url: "{{ url('/') }}/Perfil/listarmenu/" + id + "/" + $('#sistema').val(),
                type: "GET",
                success: function(data) {
                    $('#listarmenu').html(data);
                    $('#menu_form').modal('show');
                    //$("ul.checktree").checktree();
                    $('.modal-title').text('Seleccionar menu');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error('Error al obtener datos de ajax.', 'Mensaje');
                }
            });
        };

        function savemenu() {
            $('#btnSavemenu').text('guardando...');
            $('#btnSavemenu').attr('disabled', true);
            $.ajax({
                url: "{{ url('/') }}/Perfil/ajax_add_menu",
                type: "POST",
                data: $('#menu').serialize(),
                dataType: "JSON",
                success: function(data) {
                    console.log(data)
                    if (data.status) {
                        $('#menu_form').modal('hide');
                        toastr.success('El registro fue creado exitosamente.', 'Mensaje');
                    } else {
                        for (var i = 0; i < data.inputerror.length; i++) {
                            $('[name="' + data.inputerror[i] + '"]').parent().addClass('has-error');
                            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                        }
                    }
                    $('#btnSavemenu').text('Guardar');
                    $('#btnSavemenu').attr('disabled', false);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error('El registro no se pudo crear verifique las validaciones.', 'Mensaje');
                    $('#btnSavemenu').text('Guardar');
                    $('#btnSavemenu').attr('disabled', false);
                }
            });
        };

        function estado(id, x) {
            bootbox.confirm("Seguro desea " + (x == 1 ? "desactivar" : "activar") + " este registro?", function(result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ url('/') }}/Perfil/ajax_estado/" + id,
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

        function sistema(perfil) {
            $('#csistema_id').val($('#sistema').val());
            $('#cperfil_id').val(perfil);
            $.ajax({
                url: "{{ url('/') }}/Perfil/listarsistema/" + perfil + "/" + $('#sistema').val(),
                type: "GET",
                success: function(data) {
                    console.log(data);
                    ul = '';
                    data.sistemas.forEach((sis, i) => {
                        ul += '<li><label><input type="checkbox" id="checkbox' + i +
                            '" name="csistemas[]" value="' + sis.id + '" ' + (sis.status == 0 ? '' :
                                'checked') + '> ' + sis.nombre + '</label></li>';
                    });
                    $('#listarsistema').html(ul);
                    $('#modal_sistemas').modal('show');
                    $('#modal_sistemas .modal-title').text('Sistemas a Administrar');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error('Error al obtener datos de ajax.', 'Mensaje');
                }
            });
        };

        function savesistemas() {
            $('#btnSaveSistemas').text('guardando...');
            $('#btnSaveSistemas').attr('disabled', true);
            $.ajax({
                url: "{{ url('/') }}/Perfil/ajax_add_sistema",
                type: "POST",
                data: $('#form_sistemas').serialize(),
                dataType: "JSON",
                success: function(data) {
                    console.log(data)
                    if (data.status) {
                        $('#modal_sistemas').modal('hide');
                        toastr.success('El registro fue creado exitosamente.', 'Mensaje');
                        reload_table_principal();
                    } else {
                        for (var i = 0; i < data.inputerror.length; i++) {
                            $('[name="' + data.inputerror[i] + '"]').parent().addClass('has-error');
                            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                        }
                    }
                    $('#btnSaveSistemas').text('Guardar');
                    $('#btnSaveSistemas').attr('disabled', false);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error('El registro no se pudo crear verifique las validaciones.', 'Mensaje');
                    $('#btnSaveSistemas').text('Guardar');
                    $('#btnSaveSistemas').attr('disabled', false);
                }
            });
        };
    </script>
@endsection
