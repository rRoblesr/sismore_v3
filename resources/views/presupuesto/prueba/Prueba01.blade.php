@extends('layouts.main', ['activePage' => 'usuarios', 'titlePage' => 'Unidad Ejecutora'])
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
    <style>
        .tablex thead th {
            padding: 6px;
            text-align: center;
            font-size: 12px;
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
            padding: 5px;
            font-size: 12px;
        }

        .ui-autocomplete {
            z-index: 215000000 !important;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-border">
                <div class="card-header bg-transparent pb-0">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()"><i
                                class="fa fa-redo"></i> Actualizar</button>

                    </div>
                    <h3 class="card-title">FILTRO</h3>
                </div>
                <div class="card-body">
                </div>
            </div>
        </div>
    </div>
    <!-- End row -->

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        {{-- <div class="card-header card-header-primary">
                                <h4 class="card-title">Relacion de Usuarios </h4>
                            </div> --}}

                        <div class="card-body">
                            <button type="button" class="btn btn-primary btn-xs" onclick="cargare1o0()"><i
                                    class="fa fa-redo"></i> opcion 1 prueba 0</button>


                            <button type="button" class="btn btn-success btn-xs" onclick="cargare1o1()"><i
                                    class="fa fa-redo"></i> opcion 1 prueba 1</button>


                            <button type="button" class="btn btn-warning btn-xs" onclick="cargare1o2()"><i
                                    class="fa fa-redo"></i> opcion 1 prueba 2</button>
                            <br>


                            <button type="button" class="btn btn-primary btn-xs" onclick="cargare2o0()"><i
                                    class="fa fa-redo"></i> opcion 2 prueba 0</button>


                            <button type="button" class="btn btn-success btn-xs" onclick="cargare2o1()"><i
                                    class="fa fa-redo"></i> opcion 2 prueba 1</button>


                            <button type="button" class="btn btn-warning btn-xs" onclick="cargare2o2()"><i
                                    class="fa fa-redo"></i> opcion 2 prueba 2</button>
                            <br>

                            <button type="button" class="btn btn-primary btn-xs" onclick="cargare3o0()"><i
                                    class="fa fa-redo"></i> opcion 3 prueba 0</button>


                            <button type="button" class="btn btn-success btn-xs" onclick="cargare3o1()"><i
                                    class="fa fa-redo"></i> opcion 3 prueba 1</button>


                            <button type="button" class="btn btn-warning btn-xs" onclick="cargare3o2()"><i
                                    class="fa fa-redo"></i> opcion 3 prueba 2</button>

                        </div>

                        <div class=""></div>
                    </div>
                </div>
            </div> <!-- End row -->
        </div>
    </div> <!-- End row -->

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
                            {{-- <div class="form-group">
                                    <label>Secuencia Ejecutora<span class="required">*</span></label>
                                    <input id="secuencia_ejecutora" name="secuencia_ejecutora" class="form-control" type="text"
                                        onkeyup="this.value=this.value.toUpperCase()">
                                    <span class="help-block"></span>
                                </div> --}}
                            {{-- <div class="form-group">
                                    <label>Codigo<span class="required">*</span></label>
                                    <input id="codigo_ue" name="codigo_ue" class="form-control" type="text">
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group">
                                    <label>Unidad Ejecutora<span class="required">*</span></label>
                                    <input id="unidad_ejecutora" name="unidad_ejecutora" class="form-control" type="text">
                                    <span class="help-block"></span>
                                </div> --}}
                            <div class="form-group">
                                <label>Nombre Ejecutora<span class="required">*</span></label>
                                <input id="nombre_ejecutora" name="nombre_ejecutora" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label>Abreviado<span class="required">*</span></label>
                                <input id="abreviatura" name="abreviatura" class="form-control" type="text">
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
@endsection


@section('js')
    {{-- highcharts --}}
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

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
        $(document).ready(function() {
            var save_method = '';
            var table_principal;
            cargarsector();
            $("input").change(function() {
                $(this).parent().parent().removeClass('has-error');
                $(this).next().empty();
            });
            $("textarea").change(function() {
                $(this).parent().parent().removeClass('has-error');
                $(this).next().empty();
            });
            $("select").change(function() {
                $(this).parent().parent().removeClass('has-error');
                $(this).next().empty();
            });
            /* listarDT(); */
            /* se eliminara */
        });

        function add() {
            save_method = 'add';
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#modal_form').modal('show');
            $('.modal-title').text('Crear Nueva Unidad Ejecutora');
        };

        function save() {
            $('#btnSave').text('guardando...');
            $('#btnSave').attr('disabled', true);
            var url;
            if (save_method == 'add') {
                url = "{{ url('/') }}/UnidadEjecutora/ajax_add";
                msgsuccess = "El registro fue creado exitosamente.";
                msgerror = "El registro no se pudo crear verifique las validaciones.";
            } else {
                url = "{{ url('/') }}/UnidadEjecutora/ajax_update";
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
                            /* $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error'); */
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
        }; //

        function cargare1o0() {
            $.ajax({
                url: 'https://api.mef.ehg.pe/DatosAbiertos/v1/datastore_search?resource_id=d1a99fff-daed-4e35-b299-300d641fd66c&limit=5',
                dataType: 'JSON',
                success: function(data) {
                    console.log(data);
                },
            });
        }

        function cargare1o1() {
            $.ajax({
                url: 'https://api.mef.ehg.pe/DatosAbiertos/v1/datastore_search',
                data: {
                    resource_id: 'd1a99fff-daed-4e35-b299-300d641fd66c',
                    limit: 100,
                },
                dataType: 'JSON',
                cache: false,
                success: function(data) {
                    console.log(data);
                },
            });
        }

        function cargare1o2() {
            $.ajax({
                url: 'https://api.mef.ehg.pe/DatosAbiertos/v1/datastore_search?resource_id=d1a99fff-daed-4e35-b299-300d641fd66c&q=jones',
                dataType: 'JSON',
                success: function(data) {
                    console.log(data);
                },
            });
        }

        function cargare2o0() {
            $.ajax({
                url: 'https://api.mef.ehg.pe/DatosAbiertos/v1/datastore_search?resource_id=d1a99fff-daed-4e35-b299-300d641fd66c&q=jones',
                dataType: 'JSON',
                success: function(data) {
                    console.log(data);
                },
            });
        }

        function cargare2o1() {
            $.ajax({
                url: 'https://api.mef.ehg.pe/DatosAbiertos/v1/datastore_search',
                data: {
                    resource_id: 'd1a99fff-daed-4e35-b299-300d641fd66c',
                    q: 'jones',
                },
                dataType: 'JSON',
                cache: false,
                success: function(data) {
                    console.log(data);
                },
            });
        }

        function cargare2o2() {
            limitx = 10;
            var url =
                'https://api.mef.ehg.pe/DatosAbiertos/v1/datastore_search?resource_id=d1a99fff-daed-4e35-b299-300d641fd66c&filters={"DEPARTAMENTO_EJECUTORA_NOMBRE": "UCAYALI"}';
            if (limitx > 0)
                url += '&limit=' + limitx;
            $.ajax({
                url: url,
                //url: 'https://api.mef.ehg.pe/DatosAbiertos/v1/datastore_search?resource_id=d1a99fff-daed-4e35-b299-300d641fd66c&filters={"DEPARTAMENTO_EJECUTORA_NOMBRE": "UCAYALI"}&limit=100',
                type: "GET",
                dataType: 'json',
                //cache: false,
                success: function(data) {
                    console.log(data);
                },
            });
        }

        function cargare3o0() {
            $.ajax({
                url: `https://api.mef.ehg.pe/DatosAbiertos/v1/datastore_search_sql?sql=SELECT * FROM "d1a99fff-daed-4e35-b299-300d641fd66c" WHERE "SECTOR" LIKE 'GOBIERNOS LOCALES' LIMIT 10`,
                dataType: 'JSON',
                success: function(data) {
                    console.log(data);
                },
            });
        }

        function cargare3o1() {
            $.ajax({
                url: 'https://api.mef.ehg.pe/DatosAbiertos/v1/datastore_search_sql?sql=SELECT count(_id) FROM d1a99fff-daed-4e35-b299-300d641fd66c WHERE DEPARTAMENTO_EJECUTORA_NOMBRE="UCAYALI"',
                /*  data: {
                     sql: 'SELECT count(_id) FROM d1a99fff-daed-4e35-b299-300d641fd66c WHERE DEPARTAMENTO_EJECUTORA_NOMBRE="UCAYALI"',
                 }, */
                dataType: 'JSON',
                //cache: false,
                success: function(data) {
                    console.log(data);
                },
            });
        }

        function cargary() {
            $.ajax({
                url: 'https://api.mef.ehg.pe/DatosAbiertos/v1/datastore_search',
                data: {
                    resource_id: 'd1a99fff-daed-4e35-b299-300d641fd66c',
                    limit: 5,
                    q: 'jones'
                },
                dataType: 'JSON',
                //cache:false,
                success: function(data) {
                    console.log(data);
                    alert('Total results found: ' + data.result.total);
                },
            });
        }

        function edit(id) {
            save_method = 'update';
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $.ajax({
                url: "{{ url('/') }}/UnidadEjecutora/ajax_edit/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('[name="id"]').val(data.ue.id);
                    $('[name="secuencia_ejecutora"]').val(data.ue.secuencia_ejecutora);
                    $('[name="codigo_ue"]').val(data.ue.codigo_ue);
                    $('[name="unidad_ejecutora"]').val(data.ue.unidad_ejecutora);
                    $('[name="nombre_ejecutora"]').val(data.ue.nombre_ejecutora);
                    $('[name="abreviatura"]').val(data.ue.abreviatura);
                    $('#modal_form').modal('show');
                    $('.modal-title').text('Modificar Unidad Ejecutora');
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
                        url: "{{ url('/') }}/UnidadEjecutora/ajax_delete/" + id,
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
            table_principal = $('#tablemain').DataTable({
                "ajax": {
                    url: "{{ route('unidadejecutora.listar') }}",
                    "data": {
                        'gobierno': $('#gobierno').val(),
                        'sector': $('#sector').val(),
                        'pliego': $('#pliego').val()
                    },
                },
                "columns": [
                    /* {
                                            data: 'secuencia_ejecutora'
                                        }, */
                    {
                        data: 'codigo_ue'
                    },
                    {
                        data: 'unidad_ejecutora'
                    },
                    {
                        data: 'nombre_ejecutora'
                    },
                    {
                        data: 'abreviatura'
                    },
                    {
                        data: 'action',
                        orderable: false
                    }
                ],
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
    </script>

    <script type="text/javascript">
        function cargarsector() {
            $.ajax({
                url: "{{ route('basegastos.cargarsector') }}",
                data: {
                    'gobierno': $('#gobierno').val(),
                },
                type: 'get',
                success: function(data) {
                    $('#sector option ').remove();
                    var opt = '<option value="0">TODOS</option>';
                    $.each(data.sectors, function(index, vv) {
                        opt += '<option value="' + vv.id + '">' + vv.nombre + '</option>';
                    });
                    $('#sector').append(opt);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarpliego() {
            $.ajax({
                url: "{{ route('pliego.cargarpliego') }}",
                data: {
                    'sector': $('#sector').val(),
                },
                type: 'get',
                success: function(data) {
                    $('#pliego option ').remove();
                    var opt = '<option value="0">TODOS</option>';
                    $.each(data.pliegos, function(index, vv) {
                        opt += '<option value="' + vv.id + '">' + vv.nombre + '</option>';
                    });
                    $('#pliego').append(opt);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }
    </script>
@endsection
