@extends('layouts.main', ['activePage' => 'usuarios', 'titlePage' => ''])
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
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

        /*  formateando nav-tabs  */
        .nav-tabs .nav-link:not(.active) {
            /* border-color: transparent !important; */
        }

        .nav-link {
            /* color: #000; */
            font-weight: bold;
        }

        .nav-tabs .nav-item {
            color: #43beac;

            /* background-color: #43beac; */
            /* #0080FF; */
            /* color: #FFF; */
        }

        .nav-tabs .nav-item .nav-link.active {
            /* color: #43beac; */
            /* #0080FF; */

            background-color: #43beac;
            color: #FFF;
        }

        /* Asegúrate de mantener las fuentes originales */
        /* table { */
        /* font-family: Arial, sans-serif; */
        /* border-collapse: collapse; */
        /* width: 100%; */
        /* } */

        /* th, */
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* th { */
        /* padding-top: 12px; */
        /* padding-bottom: 12px; */
        /* text-align: left; */
        /* background-color: #4CAF50; */
        /* color: white; */
        /* } */

        /* Estilo para sombrear la fila cuando se pasa el mouse sobre ella */
        tr {
            transition: background-color 0.3s;
            /* Añade una transición suave */
        }

        tbody tr:hover {
            background-color: #ddd !important;
        }
    </style>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card card-border">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                    {{-- <div class="card-widgets"> --}}
                    {{-- <button type="button" class="btn btn-orange-0 btn-xs" onclick="" title='XXX'><i
                                class="fas fa-file"></i> Instituciones Educativas</button> --}}
                    {{-- <button type="button" class="btn btn-orange-0 btn-xs" onclick="location.reload()"
                            title='ACTUALIZAR'><i class=" fas fa-history"></i> Actualizar</button> --}}
                    {{-- @if ($registrador > 0)
                            <button class="btn btn-xs btn-primary waves-effect waves-light" data-toggle="modal"
                                data-target="#modal_form" title="Agregar Actas" onclick="abrirnuevo()"> <i
                                    class="fa fa-file"></i>
                                Nuevo</button> &nbsp;
                        @endif --}}

                    {{-- </div> --}}
                    <h3 class="card-title text-white"></h3>
                </div>
                <div class="card-body pb-0">
                    <div class="row">
                        @if ($registrador > 0)
                            {{-- solo por municipios --}}
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <h4 class="page-title font-16">HOMOLOGACION DE ACTAS</h4>
                            </div>
                            <input type="hidden" id="vmunicipio" name="vmunicipio"
                                value="{{ $muni->count() == 1 ? $muni[0]->id : 0 }}">

                            <div class="col-lg-1 col-md-1 col-sm-1">
                                <div class="custom-select-container">
                                    <label for="vanio">AÑO</label>
                                    <select id="vanio" name="vanio" class="form-control font-12"
                                        onchange="cargarTablaMainMensualM()">
                                        @foreach ($anio as $item)
                                            <option value="{{ $item->anio }}"
                                                {{ $item->anio == date('Y') ? 'selected' : '' }}>{{ $item->anio }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            <div class="col-lg-3 col-md-2 col-sm-2">
                                <div class="custom-select-container">
                                    <label for="vred">RED</label>
                                    <select id="vred" name="vred" class="form-control font-12"
                                        onchange="cargarmicrored(),cargarTablaMainMensualM()">
                                        <option value="0">TODOS</option>
                                    </select>
                                </div>

                            </div>

                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="custom-select-container">
                                    <label for="vmicrored">MICRORED</label>
                                    <select id="vmicrored" name="vmicrored" class="form-control font-12"
                                        onchange="cargarTablaMainMensualM()">{{-- vcargareess(); --}}
                                        <option value="0">TODOS</option>
                                    </select>
                                </div>

                            </div>

                            <div class="col-lg-3 col-md-2 col-sm-2">
                                <div class="custom-select-container">
                                    <label for="veess">ESTABLECIMIENTO</label>
                                    <select id="veess" name="veess" class="form-control font-12"
                                        onchange="cargarTablaMainMensualM()">
                                        <option value="0">TODOS</option>
                                    </select>
                                </div>

                            </div>
                            {{-- <div class="col-lg-1 col-md-1 col-sm-1">
                                <input type="date" id="vfechaf" name="vfechaf" class="form-control font-11"
                                    value="{{ date('Y-m-d') }}"
                                    onchange="cargarTablaMainO()">
                            </div> --}}
                        @else
                            {{-- para todos menos municipios --}}
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <h4 class="page-title font-16">HOMOLOGACION DE ACTAS</h4>
                            </div>

                            <div class="col-lg-1 col-md-1 col-sm-1">
                                <div class="custom-select-container">
                                    <label for="vanio">AÑO</label>
                                    <select id="vanio" name="vanio" class="form-control font-12"
                                        onchange="">
                                        @foreach ($anio as $item)
                                            <option value="{{ $item->anio }}"
                                                {{ $item->anio == date('Y') ? 'selected' : '' }}>
                                                {{ $item->anio }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            <div class="col-lg-3 col-md-2 col-sm-2">
                                <div class="custom-select-container">
                                    <label for="vmunicipio">MUNICIPIOS</label>
                                    <select id="vmunicipio" name="vmunicipio" class="form-control font-12"
                                        onchange="limpiarfiltros();cargarred();cargarTablaMainMensualM();">
    
                                        @if ($muni->count() > 1)
                                            <option value="0">TODOS</option>
                                        @endif
    
                                        @foreach ($muni as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->codigo }}|
                                                {{ $item->nombre }}
                                            </option>
                                        @endforeach
    
                                    </select>
                                </div>

                            </div>

                            <div class="col-lg-3 col-md-2 col-sm-2">
                                <div class="custom-select-container">
                                    <label for="vred">RED</label>
                                    <select id="vred" name="vred" class="form-control font-12"
                                        onchange="cargarmicrored(),cargarTablaMainMensualM();">
                                        <option value="0">TODOS</option>
                                    </select>
                                </div>

                            </div>

                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="custom-select-container">
                                    <label for="vmicrored">MICRORED</label>
                                    <select id="vmicrored" name="vmicrored" class="form-control font-12"
                                        onchange="cargarTablaMainMensualM();">
                                        <option value="0">TODOS</option>
                                    </select>
                                </div>
                                
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>


    @if ($registrador > 0)
        {{-- @if (false) --}}
        {{-- tabla 1 --}}
        <div class="row d-none">
            <div class="col-md-12">
                <div class="card card-border">
                    <div class="card-header border-success-0 bg-transparent">
                        <div class="card-widgets d-flex">
                            <div class="d-flex align-items-center">

                                <button class="btn btn-xs btn-success" title="DESCARGAR EXCEL" onclick="descargarO()">
                                    <i class="fa fa-file-excel"></i></button>
                            </div>
                        </div>
                        <h4 class="card-title p-0">Lista de actas registradas por Establecimientos</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="tabla" class="table table-sm table-striped table-bordered font-12">
                                <thead class="cabecera-dataTable table-success-0 text-white">
                                    @if ($registrador > 0)
                                        <tr>
                                            <th class="text-center">Nº</th>
                                            <th class="text-center">CODIGO UNICO</th>
                                            <th class="text-center">ESTABLECIMIENTO</th>
                                            <th class="text-center">FECHA INICIAL</th>
                                            <th class="text-center">FECHA FINAL</th>
                                            <th class="text-center">FECHA ENVIO</th>
                                            <th class="text-center">N° ARCHIVOS</th>
                                            <th class="text-center">ACCIÓN</th>
                                        </tr>
                                    @else
                                        <tr>
                                            <th class="text-center">Nº</th>
                                            <th class="text-center">RED</th>
                                            <th class="text-center">MICRORED</th>
                                            <th class="text-center">CODIGO UNICO</th>
                                            <th class="text-center">ESTABLECIMIENTO</th>
                                            <th class="text-center">N° ARCHIVOS</th>
                                            <th class="text-center">ACCIÓN</th>
                                        </tr>
                                    @endif

                                </thead>
                                <tbody></tbody>
                                @if ($registrador > 0)
                                    <tfoot class="table-success-0 text-white">
                                        <tr>
                                            <td class="text-center" colspan="6">TOTAL DE ACTAS REGISTRADAS</td>
                                            <td class="text-center tabla_tfoot">0</td>
                                            <td class="text-center"></td>
                                        </tr>
                                    </tfoot>
                                @else
                                    <tfoot class="table-success-0 text-white">
                                        <tr>
                                            <td class="text-center" colspan="5">TOTAL DE ACTAS REGISTRADAS</td>
                                            <td class="text-center tabla_tfoot">0</td>
                                            <td class="text-center"></td>
                                        </tr>
                                    </tfoot>
                                @endif

                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- End row -->
    @else
    @endif

    {{-- tabla 2 --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card card-border">
                <div class="card-header border-success-0 bg-transparent">
                    <div class="card-widgets d-flex">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-xs btn-success" title="DESCARGAR EXCEL" onclick="descargarO()">
                                <i class="fa fa-file-excel"></i></button>
                        </div>
                    </div>
                    <h4 class="card-title p-0">lista de Establecimientos</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" id="vtabla2">
                        {{-- <table id="tab-la2" class="table table-sm table-striped table-bordered font-12"> --}}
                        {{-- <thead class="cabecera-dataTable table-success-0 text-white"></thead>
                    <tbody></tbody>
                    <tfoot class="table-success-0 text-white"></tfoot> --}}
                        {{-- </table> --}}
                    </div>

                </div>
            </div>
        </div>
    </div> <!-- End row -->


    <!-- Bootstrap modal -->
    <div id="modal_form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-14"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="form" class="form-horizontal" autocomplete="off">
                        @csrf
                        <input type="hidden" id="mfmes" name="mfmes">
                        <input type="hidden" id="mfubigeo" name="mfubigeo">
                        <input type="hidden" id="mfid" name="mfid">
                        <input type="hidden" id="mfeess" name="mfeess">
                        {{-- <input type="hidden" id="mfe--ess" name="mfee--ss"> --}}
                        <div class="form-body">
                            {{-- <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="small">Establecimiento<span class="required">*</span></label>
                                        <select id="mfeess" name="mfeess"
                                            class="form-control form-control-sm"></select>
                                        <span class="help-block"></span>
                                    </div> 
                                </div>
                            </div> --}}
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="small">Fecha Inicial<span class="required">*</span></label>
                                        <input id="mffechai" name="mffechai" class="form-control form-control-sm"
                                            type="date">
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small">Fecha Final<span class="required">*</span></label>
                                        <input id="mffechaf" name="mffechaf" class="form-control form-control-sm"
                                            type="date">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="small">Fecha Envio<span class="required">*</span></label>
                                        <input id="mffechae" name="mffechae" class="form-control form-control-sm"
                                            type="date">
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small">Numero de Archivos<span class="required">*</span></label>
                                        <input id="mfarchivos" name="mfarchivos" class="form-control form-control-sm"
                                            type="number" value="0">
                                        <span class="help-block"></span>
                                    </div>

                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-xs btn-primary" id="btnSave"
                                    onclick="save()">Guardar</button>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tabla_registros" class="table table-sm table-striped table-bordered font-12">
                                    <thead class="cabecera-dataTable table-success-0 text-white">
                                        <tr>
                                            <th class="text-center">Nº</th>
                                            <th class="text-center">FECHA INICIAL</th>
                                            <th class="text-center">FECHA FINAL</th>
                                            <th class="text-center">FECHA ENVIO</th>
                                            <th class="text-center">N° ARCHIVOS</th>
                                            <th class="text-center">ACCIÓN</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot class="table-success-0 text-white">
                                        <tr>
                                            <td class="text-center" colspan="4">TOTAL DE ARCHIVOS</td>
                                            <td class="text-center tabla_registros_tfoot">0</td>
                                            <td class="text-center"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
                {{-- <div class="modal-footer"></div> --}}
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- End Bootstrap modal -->
@endsection
@section('js')
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
    <script type="text/javascript">
        var save_method;
        var registrador = '{{ $registrador }}';
        var mes_actual = {{ date('m') }};
        var table_principal;
        var table_seguimiento;
        var table_registros;
        var paleta_colores = ['#5eb9aa', '#F9FFFE', '#f5bd22', '#058DC7', '#50B432', '#9D561B', '#DDDF00', '#24CBE5',
            '#64E572', '#9F9655', '#FFF263', '#6AF9C4'
        ];
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
            Highcharts.setOptions({
                lang: {
                    thousandsSep: ","
                }
            });

            // getmes();
            cargarred();
            cargarmicrored();
            // cargareess();
            // vcargareess();

            if (registrador == 0) {
                cargarTablaMainMensualM(); //por ahora todos
                // cargarTablaMainO(); //otros menos los municipios
            } else {
                cargarTablaMainMensualM(); //por ahora todos
                // cargarTablaMainM(); //solo 1 por municipio
            }

        });

        // function cargarTablaMainO() {
        //     table_principal = $('#tabla').DataTable({
        //         responsive: true,
        //         autoWidth: false,
        //         ordered: false,
        //         language: table_language,
        //         destroy: true,
        //         ajax: {
        //             "url": "{{ route('eess.listar.registro') }}",
        //             "type": "GET",
        //             "data": {
        //                 'municipio': $('#vmunicipio').val(),
        //                 'red': $('#vred').val(),
        //                 'microred': $('#vmicrored').val(),
        //                 'fechai': $('#vfechai').val(),
        //                 'fechaf': $('#vfechaf').val(),
        //                 'registrador': '{{ $registrador }}',
        //             },
        //         },
        //         columnDefs: [{
        //                 targets: 0,
        //                 className: 'text-center'
        //             },
        //             {
        //                 targets: 1,
        //                 className: 'text-center'
        //             },
        //             {
        //                 targets: 2,
        //                 className: 'text-center'
        //             },
        //             {
        //                 targets: 3,
        //                 className: 'text-center'
        //             },
        //             {
        //                 targets: 4,
        //                 className: 'text-left'
        //             },
        //             {
        //                 targets: 5,
        //                 className: 'text-center'
        //             },
        //             {
        //                 targets: 6,
        //                 className: 'text-center'
        //             }
        //         ],
        //         drawCallback: function(settings) {
        //             var api = this.api();
        //             // Calcular el total de registros
        //             // var totalRecords = api.rows().count();
        //             // Calcular la suma de la edad (asumiendo que la edad está en la columna 2)
        //             var totalAge = api.column(5).data().reduce(function(a, b) {
        //                 return parseInt(a) + parseInt(b);
        //             }, 0);
        //             // Actualizar el contenido del tfoot
        //             $('.tabla_tfoot').html(totalAge);
        //         }
        //     });
        // }

        // function cargarTablaMainM() {
        //     console.log('cargarTablaMainM()');
        //     table_principal = $('#tabla').DataTable({
        //         responsive: true,
        //         autoWidth: false,
        //         ordered: false,
        //         language: table_language,
        //         destroy: true,
        //         ajax: {
        //             "url": "{{ route('imporpadronactas.registro.listar.2') }}",
        //             "type": "GET",
        //             "data": {
        //                 'municipio': $('#vmunicipio').val(),
        //                 'red': $('#vred').val(),
        //                 'microred': $('#vmicrored').val(),
        //                 'fechai': $('#vfechai').val(),
        //                 'fechaf': $('#vfechaf').val(),
        //                 'eess': $('#veess').val(),
        //                 'registrador': '{{ $registrador }}',
        //             },
        //         },
        //         columnDefs: [{
        //                 targets: 0,
        //                 className: 'text-center'
        //             },
        //             {
        //                 targets: 1,
        //                 className: 'text-center'
        //             },
        //             {
        //                 targets: 2,
        //                 className: 'text-left'
        //             },
        //             {
        //                 targets: 3,
        //                 className: 'text-center'
        //             },
        //             {
        //                 targets: 4,
        //                 className: 'text-center'
        //             },
        //             {
        //                 targets: 5,
        //                 className: 'text-center'
        //             },
        //             {
        //                 targets: 6,
        //                 className: 'text-center'
        //             }

        //         ],
        //         drawCallback: function(settings) {
        //             var api = this.api();
        //             // Calcular el total de registros
        //             // var totalRecords = api.rows().count();
        //             // Calcular la suma de la edad (asumiendo que la edad está en la columna 2)
        //             var totalAge = api.column(6).data().reduce(function(a, b) {
        //                 return parseInt(a) + parseInt(b);
        //             }, 0);
        //             // Actualizar el contenido del tfoot
        //             $('.tabla_tfoot').html(totalAge);
        //         }
        //     });
        // }

        function cargarTablaMainMensualM() {
            console.log('{{ $registrador }}');
            $.ajax({
                url: "{{ route('eess.listar.registro.2') }}",
                data: {
                    'sector': 2,
                    'municipio': $('#vmunicipio').val(),
                    'red': $('#vred').val(),
                    'microred': $('#vmicrored').val(),
                    // 'fechai': $('#vfechai').val(),
                    // 'fechaf': $('#vfechaf').val(),
                    'eess': $('#veess').val(),
                    'anio': $('#vanio').val(),
                    'registrador': '{{ $registrador }}',
                },
                type: 'GET',
                success: function(data) {
                    // console.log(data);
                    $('#vtabla2').html(data.tabla);
                    $('#tabla2').DataTable({
                        language: table_language,
                        destroy: true,
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function getmes() {
            var mesNombre = ["ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE",
                "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"
            ];
            var today = new Date();
            var ano = today.getFullYear();
            var mes = today.getMonth() + 1;

            var meses = [];
            mesA = mes;
            mes = $('#anio').val() == ano ? mes : 12;

            for (i = 1; i <= mes; i++) {
                meses.push({
                    'id': mes,
                    'nombre': mesNombre[i - 1]
                });
            }
            // console.log(meses);

            $("#mes option").remove();
            var options = '';
            $.each(meses, function(index, value) {
                ss = (mesA == value.id ? "selected" : "");
                options += `<option value = '${value.id}' ${ss}>${value.nombre}</option>`;
            });
            $("#mes").append(options);
        }

        function cargarred() {
            // $('#vmicrored option' ).remove();
            $.ajax({
                url: "{{ route('eess.cargarred') }}",
                data: {
                    'sector': 2,
                    'municipio': $('#vmunicipio').val(),
                },
                type: 'GET',
                success: function(data) {
                    $("#vred option").remove();
                    var options = data.red.length > 1 ? '<option value="0">TODOS</option>' : '';
                    $.each(data.red, function(index, value) {
                        //ss = (id == value.id ? "selected" : "");
                        options += `<option value='${value.id}'>${value.nombre}</option>`;
                    });
                    $("#vred").append(options);
                    cargarmicrored();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarmicrored() {
            $('#vmicrored option').remove();
            $.ajax({
                url: "{{ route('eess.cargarmicrored') }}",
                data: {
                    'sector': 2,
                    'municipio': $('#vmunicipio').val(),
                    'red': $('#vred').val(),
                },
                type: 'GET',
                success: function(data) {
                    $("#vmicrored option").remove();
                    var options = data.micro.length > 1 ? '<option value="0">TODOS</option>' : '';
                    $.each(data.micro, function(index, value) {
                        //ss = (id == value.id ? "selected" : "");
                        options += `<option value='${value.id}'>${value.nombre}</option>`;
                    });
                    $("#vmicrored").append(options);

                    // vcargareess();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        // function cargareess() {
        //     $.ajax({
        //         url: "{{ route('eess.cargareess') }}",
        //         data: {
        //             'sector': 2,
        //             'municipio': $('#vmunicipio').val(),
        //             // 'red': $('#vred').val(),
        //         },
        //         type: 'GET',
        //         success: function(data) {
        //             $("#mfeess option").remove();
        //             var options = data.eess.length > 1 ? '<option value="0">SELECCIONAR</option>' : '';
        //             $.each(data.eess, function(index, value) {
        //                 //ss = (id == value.id ? "selected" : "");
        //                 options +=
        //                     `<option value='${value.id}'>${value.cod_unico.toString().padStart(8,'0')}|${value.nombre_establecimiento}</option>`;
        //             });
        //             $("#mfeess").append(options);
        //         },
        //         error: function(jqXHR, textStatus, errorThrown) {
        //             console.log(jqXHR);
        //         },
        //     });
        // }

        // function vcargareess() {
        //     $.ajax({
        //         url: "{{ route('eess.cargareess') }}",
        //         data: {
        //             'sector': 2,
        //             'municipio': $('#vmunicipio').val(),
        //             'red': $('#vred').val(),
        //             'microred': $('#vmicrored').val(),
        //         },
        //         type: 'GET',
        //         success: function(data) {
        //             $("#veess option").remove();
        //             var options = data.eess.length > 1 ?
        //                 '<option value="0">SELECCIONAR ESTABLECIMIENTO</option>' : '';
        //             $.each(data.eess, function(index, value) {
        //                 //ss = (id == value.id ? "selected" : "");
        //                 options +=
        //                     `<option value='${value.id}'>${value.cod_unico.toString().padStart(8,'0')}|${value.nombre_establecimiento}</option>`;
        //             });
        //             $("#veess").append(options);
        //         },
        //         error: function(jqXHR, textStatus, errorThrown) {
        //             console.log(jqXHR);
        //         },
        //     });
        // }

        function limpiarfiltros() {
            $('#vred').val('0');
            $('#vmicrored').val('0');
        }

        function datos(eess) {
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $.ajax({
                url: "{{ route('eess.find', '') }}/" + eess,
                type: 'GET',
                success: function(data) {
                    $('.modal-title').html('Registrar Actas Homologadas');
                    // $('#card-title-seguimiento').text('EE.SS: ' + data.eess.nombre_establecimiento);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
            $('#mfee--ss').val(eess);
            $('#mffechae').val($('#vfechaf').val());
            $('#mfubigeo').val($('#vmunicipio').val());
            cargarseguimiento(eess);
        }

        function limpiarfrm() {
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
        }

        // function abrirnuevo() {
        //     //modal :modal_form
        //     //from  :form
        //     console.log('abrirnuevo()');
        //     $('#mfubi---geo').val($('#vmunicipio').val());
        //     $('#mod__al_form .modal-title').html('Nuevo Registro');
        //     // cargareess();
        //     save_method = 'add';
        // }

        function abrir_actas_registadas(eess, registrador, eess_nombre, mes) {
            //modal :modal_form
            //from  :form
            console.log('abrir_actas_registadas()');
            $('#mfmes').val(mes);
            if (registrador > 0) {
                $('#mfubigeo').val($('#vmunicipio').val());
                $('#mfeess').val(eess);
                $('#mffechai').val(fechaAM($('#vanio').val(), (mes < 10 ? '0' : '') + mes, true));
                $('#mffechaf').val(fechaAM($('#vanio').val(), (mes < 10 ? '0' : '') + mes, false));
                if (mes == mes_actual)
                    $('#mffechae').val(datex());
                else
                    $('#mffechae').val(fechaAM($('#vanio').val(), (mes < 10 ? '0' : '') + mes, false));
                $('#modal_form .modal-title').html('NUEVO REGISTRO EN ' + eess_nombre);
                save_method = 'add';
                $('#btnSave').text('Guardar');
            } else {
                $('#form').addClass('d-none');
                $('#modal_form .modal-title').html('NUMERO DE ACTAS EN ' + eess_nombre);
            }
            cargarregistros(eess, mes);
            $('#modal_form').modal('show');
        }

        function modificar_acta(id) {
            //modal :modal_form
            //from  :form
            save_method = 'update';

            $('#btnSave').text('Modificar');
            console.log('modificar_acta(id)');
            // limpiarfrm();
            $('#mfubigeo').val($('#vmunicipio').val());
            $.ajax({
                url: "{{ route('imporpadronactas.registro.find', '') }}/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#mfid').val(data.pd.id);
                    $('#mfeess').val(data.pd.establecimiento_id);
                    $('#mffechai').val(data.pd.fecha_inicial);
                    $('#mffechaf').val(data.pd.fecha_final);
                    $('#mffechae').val(data.pd.fecha_envio);
                    $('#mfarchivos').val(data.pd.nro_archivos);

                    $('#modal_form .modal-title').html('MODIFICAR REGISTRO');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error(
                        'No se puede eliminar este registro por seguridad de su base de datos, Contacte al Administrador del Sistema',
                        'Mensaje');
                }
            });
        }

        function save() {
            //modal :modal_form
            //from  :form
            $('#btnSave').text('guardando...');
            $('#btnSave').attr('disabled', true);
            var url;
            if (save_method == 'add') {
                url = "{{ route('imporpadronactas.registro.guardar') }}";
                msgsuccess = "El registro fue creado exitosamente.";
                msgerror = "El registro no se pudo crear verifique las validaciones.";
            } else {
                url = "{{ route('imporpadronactas.registro.modificar') }}";
                msgsuccess = "El registro fue actualizado exitosamente.";
                msgerror = "El registro no se pudo actualizar. Verifique la operación";
            }
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function(data) {
                    var mes = $('#mfmes').val();
                    // console.log(data)
                    if (data.status) {
                        // limpiarfrm();
                        table_registros.ajax.reload(null, false);
                        cargarTablaMainMensualM();
                        // table_principal.ajax.reload(null, false);
                        toastr.success(msgsuccess, 'Mensaje');
                        $('#mffechai').val(fechaAM($('#vanio').val(), (mes < 10 ? '0' : '') + mes, true));
                        $('#mffechaf').val(fechaAM($('#vanio').val(), (mes < 10 ? '0' : '') + mes, false));
                        if (mes == mes_actual)
                            $('#mffechae').val(datex());
                        else
                            $('#mffechae').val(fechaAM($('#vanio').val(), (mes < 10 ? '0' : '') + mes, false));
                        $('#mfarchivos').val(0);
                        $('#btnSave').text('GUARDAR');
                        // if (save_method != 'add') {
                        //     $('#modal_form').modal('hide');
                        // }
                    } else {
                        for (var i = 0; i < data.inputerror.length; i++) {
                            $('[name="' + data.inputerror[i] + '"]').parent().addClass('has-error');
                            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                        }
                    }
                    $('#btnSave').text('Guardar');
                    $('#btnSave').attr('disabled', false);
                    save_method = 'add';
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error(msgerror, 'Mensaje');
                    $('#btnSave').text('Guardar');
                    $('#btnSave').attr('disabled', false);
                }
            });
        };

        function cargarseguimiento(eess) {
            table_seguimiento = $('#tabla_seguimiento').DataTable({
                responsive: true,
                autoWidth: false,
                // ordered: false,
                destroy: true,
                searching: false,
                info: false,
                paging: false,
                lengthChange: false,
                language: table_language,
                // language: {
                //     "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json",
                //     "lengthMenu": "",
                //     "search": ""
                // },
                destroy: true,
                ajax: {
                    "url": "{{ route('imporpadronactas.registro.listar') }}",
                    "type": "GET",
                    "data": {
                        'municipio': $('#vmunicipio').val(),
                        'red': $('#vred').val(),
                        'microred': $('#vmicrored').val(),
                        'fechaf': $('#vfechaf').val(),
                        'eess': eess,
                    },
                },
                columnDefs: [{
                        targets: 0,
                        className: 'text-center'
                    },
                    {
                        targets: 1,
                        className: 'text-center'
                    },
                    {
                        targets: 2,
                        className: 'text-center'
                    },
                    {
                        targets: 3,
                        className: 'text-center'
                    },
                    {
                        targets: 4,
                        className: 'text-center'
                    }
                ],
                drawCallback: function(settings) {
                    var api = this.api();
                    // Calcular el total de registros
                    // var totalRecords = api.rows().count();
                    // Calcular la suma de la edad (asumiendo que la edad está en la columna 2)
                    var totalAge = api.column(3).data().reduce(function(a, b) {
                        return parseInt(a) + parseInt(b);
                    }, 0);
                    // Actualizar el contenido del tfoot
                    $('.tabla_seguimiento_tfoot').html(totalAge);
                }
            });
        }

        function eliminar_acta(id) {
            bootbox.confirm("¿Seguro desea eliminar esta importación?", function(result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ route('imporpadronactas.registro.eliminar', '') }}/" + id,
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                            // table_seguimiento.ajax.reload(null, false);
                            // table_principal.ajax.reload(null, false);
                            table_registros.ajax.reload(null, false);
                            cargarTablaMainMensualM();
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

        // function verdatos(eess) {
        //     $('.modal-title').html('Actas Homologadas');
        //     cargar--registros(eess);
        // }

        function cargarregistros(eess, mes) {
            table_registros = $('#tabla_registros').DataTable({
                responsive: true,
                autoWidth: false,
                // ordered: false,
                destroy: true,
                searching: false,
                info: false,
                paging: false,
                lengthChange: false,
                language: table_language,
                // language: {
                //     "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json",
                //     "lengthMenu": "",
                //     "search": ""
                // },
                destroy: true,
                ajax: {
                    "url": "{{ route('imporpadronactas.registro.listar.resumen') }}",
                    "type": "GET",
                    "data": {
                        'municipio': $('#vmunicipio').val(),
                        'red': $('#vred').val(),
                        'microred': $('#vmicrored').val(),
                        'fechai': $('#vfechai').val(),
                        'fechaf': $('#vfechaf').val(),
                        'fecha': $('#vanio').val() + "-" + (mes < 10 ? '0' : '') + mes + "-",
                        'eess': eess,
                        'registrador': '{{ $registrador }}',
                    },
                },
                columnDefs: [{
                        targets: 0,
                        className: 'text-center'
                    },
                    {
                        targets: 1,
                        className: 'text-center'
                    },
                    {
                        targets: 2,
                        className: 'text-center'
                    },
                    {
                        targets: 3,
                        className: 'text-center'
                    },
                    {
                        targets: 4,
                        className: 'text-center'
                    },
                    {
                        targets: 5,
                        className: 'text-center'
                    }
                ],
                drawCallback: function(settings) {
                    var api = this.api();
                    // Calcular el total de registros
                    // var totalRecords = api.rows().count();
                    // Calcular la suma de la edad (asumiendo que la edad está en la columna 2)
                    var totalAge = api.column(4).data().reduce(function(a, b) {
                        return parseInt(a) + parseInt(b);
                    }, 0);
                    // Actualizar el contenido del tfoot
                    $('.tabla_registros_tfoot').html(totalAge);
                }
            });
        }

        function descargar1() {
            // window.open("{{ url('/') }}/Man/SFL/Download/EXCEL/" + $('#ugel').val() + "/" + $('#provincia').val() + "/" + $('#distrito').val() + "/" + $('#estado').val());
            {{-- window.open("{{ route('imporpadronactas.registro.excel', ['', '', '', '', '', '']) }}/" + $('#vmunicipio')
                .val() + "/" + $('#vred').val() + "/" + $('#vmicrored').val() + "/" + $('#vfechai').val() + "/" +
                $('#vfechaf').val() + "/{{ $registrador }}"); --}}
        }

        function descargarO() {
            window.open("{{ route('imporpadronactas.registro.excel', ['', '', '', '', '', '', '', '']) }}/otros/" +
                $('#vanio').val() + "/" + $('#vmunicipio').val() + "/" + $('#vred').val() + "/" +
                $('#vmicrored').val() + "/0/0/{{ $registrador }}");
        }

        function formatofechax(fechaISO) {
            var fecha = new Date(fechaISO); // usa zona colombiana
            var dia = String(fecha.getDate()).padStart(2, '0');
            var mes = String(fecha.getMonth() + 1).padStart(2, '0'); // Los meses empiezan desde 0
            var anio = fecha.getFullYear();
            var fechaFormateada = dia + '/' + mes + '/' + anio;
            return fechaFormateada;
        }

        function formatofecha(fechaISO) {
            var partesFecha = fechaISO.split('-');
            var anio = partesFecha[0];
            var mes = partesFecha[1];
            var dia = partesFecha[2];
            var fechaFormateada = dia + '/' + mes + '/' + anio;
            return fechaFormateada;
        }

        function fechaAM(anio, mes, rango) {
            var fecha = `${anio}-${mes}-`;
            var ultimoDia = new Date(anio, mes, 0).getDate(); //obtenerUltimoDiaDelMes(anio, mes);
            var fecha1 = fecha + "01";
            var fecha2 = fecha + (ultimoDia < 10 ? "0" + ultimoDia : ultimoDia);

            if (rango) return fecha + "01";
            return fecha + (ultimoDia < 10 ? "0" + ultimoDia : ultimoDia);
            // console.log(fecha1); // "2024-07-01"
            // console.log(fecha2); // "2024-07-31"

        }

        function datex() {
            // Obtener la fecha actual
            var fechaActual = new Date();

            // Extraer el año, mes y día
            var anio = fechaActual.getFullYear();
            var mes = ("0" + (fechaActual.getMonth() + 1)).slice(-2); // Los meses en JavaScript son de 0 a 11
            var dia = ("0" + fechaActual.getDate()).slice(-2);

            // Formatear la fecha en "YYYY-MM-DD"
            var fechaHoy = anio + "-" + mes + "-" + dia;

            console.log(fechaHoy);
            return fechaHoy;
        }
    </script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <!-- optional -->
    <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
@endsection
