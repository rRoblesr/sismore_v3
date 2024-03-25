@extends('layouts.main', ['activePage' => 'usuarios', 'titlePage' => ''])

@section('css')
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/buttons.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/fixedHeader.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/scroller.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <style>
        .tablex thead th {
            padding: 6px;
            text-align: center;
        }

        .tablex thead td {
            padding: 6px;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
        }

        .tablex tbody td,
        .tablex tbody th,
        .tablex tfoot td,
        .tablex tfoot th {
            padding: 6px;
        }
    </style>
@endsection

@section('content')
    <div class="content">
        <form class="cmxform form-horizontal tasi-form upload_file">
            @csrf

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-border">
                        <div class="card-header border-success-0 bg-transparent pb-0">
                            <div class="card-widgets">
                                <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()"><i
                                        class="fa fa-redo"></i> Actualizar</button>
                                <button type="button" class="btn btn-primary btn-xs" onclick="add()"><i
                                        class="fa fa-plus"></i>
                                    Nuevo</button>
                            </div>
                            <h3 class="card-title">Fuentes de Importación</h3>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tbprincipal" class="table table-sm table-striped table-bordered"
                                    style="font-size: 12px">
                                    <thead class="cabecera-dataTable">
                                        <tr class="bg-success-0 text-white">
                                            <th>Nº</th>
                                            <th>ID</th>
                                            <th>Sistema</th>
                                            <th>Código</th>
                                            <th>Nombre</th>
                                            <th>Formato</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div> <!-- End row -->
        </form>
    </div>

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
                    <form action="" id="form" name="form" class="form-horizontal" autocomplete="off">
                        @csrf
                        <input type="hidden" id="id" name="id" value="">
                        <div class="form-body">

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Sistema<span class="required font-20">*</span></label>
                                        <select id="sistema" name="sistema" class="form-control">
                                            @foreach ($sistema as $item)
                                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Código<span class="required font-20">*</span></label>
                                        <input type="text" id="codigo" name="codigo" class="form-control"
                                            value="Autogenerado" readonly>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Nombre<span class="required font-20">*</span></label>
                                        <input type="text" id="nombre" name="nombre" class="form-control">
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Formato<span class="required font-20">*</span></label>
                                        <input type="text" id="formato" name="formato" class="form-control">
                                        <span class="help-block"></span>
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
    <div id="modal_ver" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="formver" name="formver" class="form-horizontal" autocomplete="off">
                        @csrf
                        <div class="form-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Codigo Sede<span class="required">*</span></label>
                                        <input id="vcodigo_rer" name="vcodigo_rer" class="form-control" type="text"
                                            maxlength="8" readonly>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Nombre<span class="required">*</span></label>
                                        <input id="vnombre" name="vnombre" class="form-control" type="text"
                                            maxlength="150" readonly>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Ambito</label>
                                        <select id="vambito" name="vambito" class="form-control" readonly>
                                            <option value="Rural">Rural</option>
                                            <option value="Rural/Urbana">Rural/Urbana</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Año Creación</label>
                                        <input id="vanio_creacion" name="vanio_creacion" class="form-control"
                                            type="number" readonly>
                                        <span class="help-block"></span>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Año Implementación </label>
                                        <input id="vanio_implementacion" name="vanio_implementacion" class="form-control"
                                            type="number" readonly>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Fecha Resolución </label>
                                        <input id="vfecha_resolucion" name="vfecha_resolucion" class="form-control"
                                            type="date" readonly>
                                        <span class="help-block"></span>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Numero Resolución </label>
                                        <input id="vnumero_resolucion" name="vnumero_resolucion" class="form-control"
                                            type="text" readonly>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Presupuesto</label>
                                        <input id="vpresupuesto" name="vpresupuesto" class="form-control" type="number"
                                            readonly>
                                        <span class="help-block"></span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Bootstrap modal -->
@endsection

@section('js')
    {{-- autocompletar --}}
    <script src="{{ asset('/') }}public/assets/jquery-ui/jquery-ui.js"></script>

    <!-- third party js -->
    <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.buttons.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/buttons.bootstrap4.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/jszip/jszip.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/pdfmake/pdfmake.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/pdfmake/vfs_fonts.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/buttons.html5.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/buttons.print.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.fixedHeader.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.keyTable.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.scroller.min.js"></script>
    <script>
        var save_method = '';
        var table_principal;
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


            table_principal = $('#tbprincipal').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: true,
                language: table_language,
                // dom: 'Bfrtip',
                // buttons: [{
                //     extend: "excel",
                //     title: null,
                //     className: "btn-sm",
                //     text: '<i class="fa fa-file-excel"></i> Excel',
                //     titleAttr: 'Excel',
                //     exportOptions: {
                //         columns: [0, 1, 2, 3, 4, 5, 6],
                //     },
                // }, {
                //     extend: "pdf",
                //     className: "btn-sm",
                //     title: "REDES EDUCATIVAS",
                //     exportOptions: {
                //         columns: [0, 1, 2, 3, 4, 5, 6],
                //     },
                //     //orientation: 'landscape',
                //     text: '<i class="fa fa-file-pdf"></i> PDF',
                //     titleAttr: 'PDF',
                // }, ],
                ajax: {
                    "headers": {
                        'X-CSRF-TOKEN': $('input[name=_token]').val()
                    },
                    "url": "{{ route('mantenimiento.fuenteimportacion.listar.importados') }}",
                    "type": "POST",
                    //"dataType": 'JSON',
                },

            });
        });

        function add() {
            save_method = 'add';
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#modal_form').modal('show');
            $('.modal-title').text('Crear Fuente Importación');
            $('#id').val("");
        };

        function save() {
            $('#btnSave').text('guardando...');
            $('#btnSave').attr('disabled', true);
            var url;
            if (save_method == 'add') {
                url = "{{ route('mantenimiento.fuenteimportacion.save') }}";
                msgsuccess = "El registro fue creado exitosamente.";
                msgerror = "El registro no se pudo crear verifique las validaciones.";
            } else {
                url = "{{ route('mantenimiento.fuenteimportacion.update') }}";
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
                        reload_table_principal();
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
                url: "{{ route('mantenimiento.fuenteimportacion.find', '') }}/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('[name="id"]').val(data.obj.id);
                    $('[name="identificador"]').val(data.obj.id);
                    $('[name="sistema"]').val(data.obj.sistema_id);
                    $('[name="codigo"]').val(data.obj.codigo);
                    $('[name="nombre"]').val(data.obj.nombre);
                    $('[name="formato"]').val(data.obj.formato);
                    $('#modal_form').modal('show');
                    $('.modal-title').text('Modificar Fuente Importación');
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
                        url: "{{ route('mantenimiento.fuenteimportacion.delete', '') }}/" + id,
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                            $('#modal_form').modal('hide');
                            reload_table_principal(); //listarDT();
                            toastr.success('El registro fue eliminado exitosamente.',
                                'Mensaje');
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

        function reload_table_principal() {
            table_principal.ajax.reload(null, false);
        }

        function estado(id, x) {
            bootbox.confirm("Seguro desea " + (x == 1 ? "desactivar" : "activar") + " este registro?", function(
                result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ url('/') }}/Mantenimiento/RER/ajax_estado/" + id,
                        /* type: "POST", */
                        dataType: "JSON",
                        success: function(data) {
                            console.log(data);
                            reload_table_principal(); //listarDT();
                            if (data.estado)
                                toastr.success('El registro fue Activo exitosamente.',
                                    'Mensaje');
                            else
                                toastr.success('El registro fue Desactivado exitosamente.',
                                    'Mensaje');
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
    </script>
@endsection
