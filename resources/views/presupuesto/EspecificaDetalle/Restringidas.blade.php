@extends('layouts.main', ['activePage' => 'usuarios', 'titlePage' => 'Partidas Restringidas'])
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
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-success-0 pt-2">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()"><i
                                class="fa fa-redo"></i> Actualizar</button>
                        <button type="button" class="btn btn-primary btn-xs" onclick="add()">
                            <i class="fa fa-plus"></i> Agregar</button>
                    </div>
                    <h3 class="card-title text-white">PARTIDAS RESTRINGIDAS</h3>
                </div>
                <div class="card-body pt-2 pb-0">
                    <form class="form-horizontal" id="form-filtro">
                        @csrf
                        <div class="form">
                            <div class="form-group row">
                                <div class="col-md-6"></div>
                                <div class="col-md-1">
                                    {{-- <label class="col-form-label">Año</label> --}}
                                    <div class="">
                                        <select class="form-control btn-xs font-11" name="anio" id="anio"
                                            onchange="listarDT();">
                                            @foreach ($anios as $item)
                                                <option value="{{ $item->anio }}"
                                                    {{ $item->anio == $anio ? 'selected' : '' }}>
                                                    {{ $item->anio }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    {{-- <label class=" col-form-label">Genérica </label> --}}
                                    <div class="">
                                        <select class="form-control btn-xs font-11" name="generica" id="generica"
                                            onchange="cargarsg();listarDT();">
                                            <option value="0">GENÉRICA</option>
                                            @foreach ($generica as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ '2.' . $item->codigo . ' ' . $item->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    {{-- <label class="col-form-label">Sub Genérica </label> --}}
                                    <div class="">
                                        <select class="form-control btn-xs font-11" name="sg" id="sg"
                                            onchange="listarDT();">
                                            <option value="0">SUB GENÉRICA</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
                        {{-- <div class="card-header card-header-primary bg-transparent pb-0">
                            <div class="card-widgets">
                                <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()"><i
                                        class="fa fa-redo"></i> Actualizar</button>
                                <button type="button" class="btn btn-primary btn-xs" onclick="add()">
                                    <i class="fa fa-plus"></i> Agregar</button>
                            </div>
                            <h4 class="card-title">Relacion de Usuarios </h4>
                        </div> --}}
                        <div class="card-body">
                            {{-- <div class="row justify-content-between">
                                <div class="col-4"></div>
                                <div class="col-4">
                                    <div class="row justify-content-end">
                                        <button type="button" class="btn btn-primary btn-xs" onclick="add()">
                                            <i class="fa fa-plus"></i> Agregar</button>
                                    </div>
                                </div>
                            </div><br> --}}
                            <div class="table-responsive">
                                <table id="tablemain" class="table table-sm table-striped table-bordered font-11">
                                    <thead class="cabecera-dataTable bg-success-1 text-white">
                                        <!--th>Nº</th-->
                                        {{-- <th>Partida</th> --}}
                                        <th>Generica</th>
                                        {{-- <th>Partida</th> --}}
                                        <th>Especifica Detalle</th>
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


    <!-- Bootstrap modal -->
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
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="col-form-label">Año</label>
                                            <input type="text" class="form-control" id="fanio" name="fanio"
                                                value="{{ date('Y') }}" readonly>
                                            {{-- <div class="">
                                                <select class="form-control" name="fanio" id="fanio"
                                                    onchange="listarpartidas();" disabled>
                                                    @foreach ($anios as $item)
                                                        <option value="{{ $item->anio }}"
                                                            {{ $item->anio == date('Y') ? 'selected' : '' }}>
                                                            {{ $item->anio }}</option>
                                                    @endforeach
                                                </select>
                                            </div> --}}
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-form-label">Generica<span class="required">*</span></label>
                                            <select name="fgenerica" id="fgenerica" class="form-control"
                                                onchange="cargarsg2();listarpartidas()">
                                                <option value="0">Seleccionar</option>
                                                @foreach ($generica as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ '2.' . $item->codigo . ' ' . $item->nombre }}</option>
                                                @endforeach
                                            </select>
                                            <span class="help-block"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-form-label">Sub Genérica </label>
                                            <div class="">
                                                <select class="form-control" name="fsg" id="fsg"
                                                    onchange="cargarespecifica2();listarpartidas();">
                                                    <option value="0">TODOS</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-form-label">Sub Generica Detalle</label>
                                            <div class="">
                                                <select class="form-control" name="fsubgenericadetalle"
                                                    id="fsubgenericadetalle" onchange="listarpartidas();">
                                                    <option value="0">TODOS</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            {{-- <div class="col-lg-12">
                                <div class="card-widgets">seleccionar todas las partidas
                                    <div class="pretty p-switch">
                                        <input type="checkbox" id="checkbox" name="checkbox[]" value="1" checked
                                            title="Liberar">
                                        <div class="state p-success"><label></label></div>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-lg-12">
                                <div class="card card-border">
                                    <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                                        <div class="card-widgets">seleccionar todas las partidas
                                            <div class="pretty p-switch">
                                                <input type="checkbox" id="checkbox" name="checkbox[]" value="1"
                                                    checked title="Liberar">
                                                <div class="state p-success"><label></label></div>
                                            </div>
                                        </div>
                                        <h3 class="card-title"></h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form">
                                            <div class="table-responsive">
                                                <table id="tablepartidas"
                                                    class="table table-striped table-bordered tablex">
                                                    <thead class="bg-success-1 text-white">
                                                        <!--th>Nº</th-->
                                                        <th>Partida</th>
                                                        <th>Especifica Detalle</th>
                                                        <th>Acción
                                                        </th>
                                                    </thead>
                                                </table>
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
                    <button type="button" id="btnSaveRestringidas" onclick="saverestringidas()"
                        class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Bootstrap modal -->
@endsection


@section('js')
    {{-- highcharts --}}
    {{--  <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script> --}}

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
            var tablepartidas;

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
            listarDT();
        });

        function add() {
            save_method = 'add';
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#modal_form').modal('show');
            $('.modal-title').text('Agregar Partida');
            listarpartidas();
        };


        function borrar(id) {
            bootbox.confirm("Seguro desea Eliminar este registro?", function(result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ route('espdet.partidasrestringidas.borrar') }}",
                        data: {
                            'id': id,
                        },
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
                    url: "{{ route('espdet.partidasrestringidas.listar') }}",
                    data: {
                        anio: $('#anio').val(),
                        generica: $('#generica').val(),
                        sg: $('#sg').val(),
                    },
                },
                "columns": [{
                    data: 'gen'
                }, {
                    data: 'espdet'
                }, {
                    data: 'action',
                    //orderable: false
                }],
                responsive: true,
                autoWidth: false,
                ordering: false,
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

        function listarpartidas() {
            tablepartidas = $('#tablepartidas').DataTable({
                "ajax": {
                    url: "{{ route('espdet.listar') }}",
                    data: {
                        anio: $('#fanio').val(),
                        generica: $('#fgenerica').val(),
                        sg: $('#fsg').val(),
                        subgenericadetalle: $('#fsubgenericadetalle').val(),
                    },
                },
                "columns": [{
                    data: 'partida'
                }, {
                    data: 'nombre'
                }, {
                    data: 'action',
                    //orderable: false
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

        {{--
        function asignar(id, partida) {
            $.ajax({
                url: "{{ route('espdet.partidasrestringidas.asignar') }}",
                data: {
                    'especificadetalle_id': id,
                    'partida': partida,
                },
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    console.log(data);
                    table_principal.ajax.reload(null, true);
                    tablepartidas.ajax.reload(null, true);

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }; --}}

        {{--
        function quitar(id) {
            $.ajax({
                url: "{{ route('espdet.partidasrestringidas.quitar') }}",
                data: {
                    'id': id,
                },
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    console.log(data);
                    table_principal.ajax.reload(null, true);
                    tablepartidas.ajax.reload(null, true);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }; --}}

        function cargarsg() {
            var gg = $('select[name="generica"] option:selected').text()
            var cod = gg.trim().split(' ');
            $.ajax({
                url: "{{ route('subgenerica.cargarsg') }}",
                data: {
                    'generica': $('#generica').val(),
                },
                type: 'get',
                success: function(data) {
                    $('#sg option ').remove();
                    var opt = '<option value="0">SUB GENÉRICA</option>';
                    $.each(data.query, function(index, vv) {
                        opt += '<option value="' + vv.id + '">' + cod[0] + '.' + vv.codigo +
                            ' ' + vv
                            .nombre +
                            '</option>';
                    });
                    $('#sg').append(opt);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarsg2() {
            var gg = $('select[name="fgenerica"] option:selected').text()
            var cod = gg.trim().split(' ');
            $.ajax({
                url: "{{ route('subgenerica.cargarsg') }}",
                data: {
                    'generica': $('#fgenerica').val(),
                },
                type: 'get',
                success: function(data) {
                    $('#fsg option ').remove();
                    var opt = '<option value="0">TODOS</option>';
                    $.each(data.query, function(index, vv) {
                        opt += '<option value="' + vv.id + '">' + cod[0] + '.' + vv.codigo + ' ' + vv
                            .nombre +
                            '</option>';
                    });
                    $('#fsg').append(opt);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarespecifica2() {
            var gg = $('select[name="fsg"] option:selected').text()
            var cod = gg.trim().split(' ');
            $.ajax({
                url: "{{ route('subgenericadetalle.cargar') }}",
                data: {
                    'subgenerica': $('#fsg').val(),
                },
                type: 'get',
                success: function(data) {
                    $('#fsubgenericadetalle option ').remove();
                    var opt = '<option value="0">TODOS</option>';
                    $.each(data.query, function(index, vv) {
                        opt += '<option value="' + vv.id + '">' + cod[0] + '.' + vv.codigo + ' ' + vv
                            .nombre +
                            '</option>';
                    });
                    $('#fsubgenericadetalle').append(opt);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function saverestringidas() {
            var ncheck = $('input[type="checkbox"]:checked');
            if (ncheck.length > 0) {
                $.ajax({
                    url: "{{ route('espdet.partidasrestringidas.guardar') }}",
                    data: $('#form').serialize(),
                    type: 'GET',
                    dataType: 'JSON',
                    success: function(data) {
                        $('#modal_form').modal('hide');
                        table_principal.ajax.reload(null, true);
                        tablepartidas.ajax.reload(null, true);
                        toastr.success('Registros Guardados exitosamente.', 'Mensaje');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                    },
                });
            } else {
                alert("Necesita selecionar Para Continuar");
            }
        }
    </script>

    <script type="text/javascript">
        //Vacio
    </script>
@endsection
