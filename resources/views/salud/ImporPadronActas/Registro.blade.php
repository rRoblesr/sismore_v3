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
    </style>
@endsection

@section('content')
    <div class="row">



        @if ($registrador > 0)
            {{--  --}}
            <div class="col-lg-4 col-md-6 col-sm-6">
                <h4 class="page-title font-16">HOMOLOGACION DE ACTAS</h4>
            </div>
            <input type="hidden" id="vmunicipio" name="vmunicipio" value="{{ $muni->count() == 1 ? $muni[0]->id : 0 }}">

            <div class="col-lg-3 col-md-2 col-sm-2">
                <select id="vred" name="vred" class="form-control btn-xs font-11"
                    onchange="cargarmicrored(),cargartabla2()">
                    <option value="0">RED</option>
                </select>
            </div>

            <div class="col-lg-2 col-md-2 col-sm-2">
                <select id="vmicrored" name="vmicrored" class="form-control btn-xs font-11"
                    onchange="vcargareess();cargartabla2()">
                    <option value="0">MICRORED</option>
                </select>
            </div>

            <div class="col-lg-3 col-md-2 col-sm-2">
                <select id="veess" name="veess" class="form-control btn-xs font-11" onchange="cargartabla2()">
                    <option value="0">ESTABLECIMIENTO</option>
                </select>
            </div>
            {{-- <div class="col-lg-1 col-md-1 col-sm-1">
                <input type="date" id="vfechaf" name="vfechaf" class="form-control btn-xs font-11"
                    value="{{ date('Y-m-d') }}"
                    onchange="cargartabla()">
            </div> --}}
        @else
            <div class="col-lg-4 col-md-6 col-sm-6">
                <h4 class="page-title font-16">HOMOLOGACION DE ACTAS</h4>
            </div>
            <div class="col-lg-3 col-md-2 col-sm-2">
                <select id="vmunicipio" name="vmunicipio" class="form-control btn-xs font-11"
                    onchange="limpiarfiltros();cargarred(),cargartabla()">

                    @if ($muni->count() > 1)
                        <option value="0">MUNICIPIOS</option>
                    @endif

                    @foreach ($muni as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->codigo }}|
                            {{ $item->nombre }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="col-lg-3 col-md-2 col-sm-2">
                <select id="vred" name="vred" class="form-control btn-xs font-11"
                    onchange="cargarmicrored(),cargartabla()">
                    <option value="0">RED</option>
                </select>
            </div>

            <div class="col-lg-2 col-md-2 col-sm-2">
                <select id="vmicrored" name="vmicrored" class="form-control btn-xs font-11" onchange="cargartabla()">
                    <option value="0">MICRORED</option>
                </select>
            </div>
        @endif

    </div>

    {{-- tabla 1 --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card card-border">
                <div class="card-header border-success-0 bg-transparent">
                    <div class="card-widgets d-flex">
                        <div class="d-flex align-items-center">
                            @if ($registrador == 0)
                                <label for="vfechai" class="small mb-0 mr-2">Fecha&nbsp;Inicial&nbsp;</label>
                                <input type="date" id="vfechai" name="vfechai"
                                    class="form-control form-control-sm font-11 mr-2" value="{{ date('Y-m-d') }}"
                                    onchange="cargartabla()">
                                <label for="vfechaf" class="small mb-0 mr-2">Fecha&nbsp;Final&nbsp;</label>
                                <input type="date" id="vfechaf" name="vfechaf"
                                    class="form-control form-control-sm font-11 mr-2" value="{{ date('Y-m-d') }}"
                                    onchange="cargartabla()">
                            @else
                                <button class="btn btn-xs btn-primary waves-effect waves-light" data-toggle="modal"
                                    data-target="#modal_form" title="Agregar Actas" onclick="abrirnuevo()"> <i
                                        class="fa fa-file"></i>
                                    Nuevo</button> &nbsp;
                            @endif
                            <button class="btn btn-xs btn-success" title="DESCARGAR EXCEL" onclick="descargar1()">
                                <i class="fa fa-file-excel"></i></button>
                        </div>
                    </div>
                    <h4 class="card-title p-0">lista de actas [ {{ $ent->entidadn }} ]</h4>
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
                                        <td class="text-center" colspan="6">TOTAL DE ARCHIVOS</td>
                                        <td class="text-center tabla_tfoot">0</td>
                                        <td class="text-center"></td>
                                    </tr>
                                </tfoot>
                            @else
                                <tfoot class="table-success-0 text-white">
                                    <tr>
                                        <td class="text-center" colspan="5">TOTAL DE ARCHIVOS</td>
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
    </div> <!-- End row -->

    {{-- tabla 2 --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card card-border">
                <div class="card-header border-success-0 bg-transparent">
                    <div class="card-widgets d-flex">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-xs btn-success" title="DESCARGAR EXCEL" onclick="descargar2()">
                                <i class="fa fa-file-excel"></i></button>
                        </div>
                    </div>
                    <h4 class="card-title p-0">lista de actas [ {{ $ent->entidadn }} ]</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="tabla2" class="table table-sm table-striped table-bordered font-12 ">
                            <thead class="cabecera-dataTable table-success-0 text-white"></thead>
                            <tbody></tbody>
                            <tfoot class="table-success-0 text-white"></tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div> <!-- End row -->

    <!-- Bootstrap modal -->
    <div id="modal_form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
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
                        <input type="hidden" id="mfubigeo" name="mfubigeo">
                        <input type="hidden" id="mfid" name="mfid">
                        {{-- <input type="hidden" id="mfe--ess" name="mfee--ss"> --}}
                        <div class="form-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="small">Establecimiento<span class="required">*</span></label>
                                        <select id="mfeess" name="mfeess"
                                            class="form-control form-control-sm"></select>
                                        <span class="help-block"></span>
                                    </div>

                                </div>
                            </div>
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
                            <div class="text-right">
                                <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-xs btn-primary" id="btnSave"
                                    onclick="save()">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
                {{-- <div class="modal-footer"></div> --}}
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- End Bootstrap modal -->


    <!-- Bootstrap modal -->
    <div id="modal_registros" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-14"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-border">
                                <div class="card-body p-0">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table id="tabla_registros"
                                                    class="table table-sm table-striped table-bordered font-12">
                                                    <thead class="cabecera-dataTable table-success-0 text-white">
                                                        <tr>
                                                            <th class="text-center">Nº</th>
                                                            <th class="text-center">FECHA INICIAL</th>
                                                            <th class="text-center">FECHA FINAL</th>
                                                            <th class="text-center">FECHA ENVIO</th>
                                                            <th class="text-center">N° ARCHIVOS</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                    <tfoot class="table-success-0 text-white">
                                                        <tr>
                                                            <td class="text-center" colspan="4">TOTAL DE ARCHIVOS</td>
                                                            <td class="text-center tabla_registros_tfoot">0</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
            cargareess();
            vcargareess();

            if (registrador == 0) {
                cargartabla();
            } else {
                cargartabla2();
                // datos();
            }
            cargartabla2x();
            // vcargaralert();

        });

        function cargartabla() {
            table_principal = $('#tabla').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: false,
                destroy: true,
                language: table_language,
                destroy: true,
                ajax: {
                    "url": "{{ route('eess.listar.registro') }}",
                    "type": "GET",
                    "data": {
                        'municipio': $('#vmunicipio').val(),
                        'red': $('#vred').val(),
                        'microred': $('#vmicrored').val(),
                        'fechai': $('#vfechai').val(),
                        'fechaf': $('#vfechaf').val(),
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
                        className: 'text-left'
                    },
                    {
                        targets: 5,
                        className: 'text-center'
                    },
                    {
                        targets: 6,
                        className: 'text-center'
                    }
                ],
                drawCallback: function(settings) {
                    var api = this.api();
                    // Calcular el total de registros
                    // var totalRecords = api.rows().count();
                    // Calcular la suma de la edad (asumiendo que la edad está en la columna 2)
                    var totalAge = api.column(5).data().reduce(function(a, b) {
                        return parseInt(a) + parseInt(b);
                    }, 0);
                    // Actualizar el contenido del tfoot
                    $('.tabla_tfoot').html(totalAge);
                }
            });
        }

        function cargartabla2() {
            console.log('cargartabla2()');
            table_principal = $('#tabla').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: false,
                destroy: true,
                language: table_language,
                destroy: true,
                ajax: {
                    "url": "{{ route('imporpadronactas.registro.listar.2') }}",
                    "type": "GET",
                    "data": {
                        'municipio': $('#vmunicipio').val(),
                        'red': $('#vred').val(),
                        'microred': $('#vmicrored').val(),
                        'fechai': $('#vfechai').val(),
                        'fechaf': $('#vfechaf').val(),
                        'eess': $('#veess').val(),
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
                    },
                    {
                        targets: 6,
                        className: 'text-center'
                    }

                ],
                drawCallback: function(settings) {
                    var api = this.api();
                    // Calcular el total de registros
                    // var totalRecords = api.rows().count();
                    // Calcular la suma de la edad (asumiendo que la edad está en la columna 2)
                    var totalAge = api.column(6).data().reduce(function(a, b) {
                        return parseInt(a) + parseInt(b);
                    }, 0);
                    // Actualizar el contenido del tfoot
                    $('.tabla_tfoot').html(totalAge);
                }
            });
        }

        function cargartabla2x() {
            $.ajax({
                url: "{{ route('eess.listar.registro.2') }}",
                data: {
                    'sector': 2,
                    'municipio': $('#vmunicipio').val(),
                    'red': $('#vred').val(),
                    'microred': $('#vmicrored').val(),
                    'fechai': $('#vfechai').val(),
                    'fechaf': $('#vfechaf').val(),
                    'eess': $('#veess').val(),
                    'anio': 2024,
                    'registrador': '{{ $registrador }}',
                },
                type: 'GET',
                success: function(data) {
                    console.log(data);
                    $('#tabla2').html(data.tabla);
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
                    var options = data.red.length > 1 ? '<option value="0">RED</option>' : '';
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
                    var options = data.micro.length > 1 ? '<option value="0">MICRORED</option>' : '';
                    $.each(data.micro, function(index, value) {
                        //ss = (id == value.id ? "selected" : "");
                        options += `<option value='${value.id}'>${value.nombre}</option>`;
                    });
                    $("#vmicrored").append(options);

                    vcargareess();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargareess() {
            $.ajax({
                url: "{{ route('eess.cargareess') }}",
                data: {
                    'sector': 2,
                    'municipio': $('#vmunicipio').val(),
                    // 'red': $('#vred').val(),
                },
                type: 'GET',
                success: function(data) {
                    $("#mfeess option").remove();
                    var options = data.eess.length > 1 ? '<option value="0">SELECCIONAR</option>' : '';
                    $.each(data.eess, function(index, value) {
                        //ss = (id == value.id ? "selected" : "");
                        options +=
                            `<option value='${value.id}'>${value.cod_unico.toString().padStart(8,'0')}|${value.nombre_establecimiento}</option>`;
                    });
                    $("#mfeess").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function vcargareess() {
            $.ajax({
                url: "{{ route('eess.cargareess') }}",
                data: {
                    'sector': 2,
                    'municipio': $('#vmunicipio').val(),
                    'red': $('#vred').val(),
                    'microred': $('#vmicrored').val(),
                },
                type: 'GET',
                success: function(data) {
                    $("#veess option").remove();
                    var options = data.eess.length > 1 ?
                        '<option value="0">SELECCIONAR ESTABLECIMIENTO</option>' : '';
                    $.each(data.eess, function(index, value) {
                        //ss = (id == value.id ? "selected" : "");
                        options +=
                            `<option value='${value.id}'>${value.cod_unico.toString().padStart(8,'0')}|${value.nombre_establecimiento}</option>`;
                    });
                    $("#veess").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }



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
            $('#mfeess').val('0');
            $('#mffechai').val('');
            $('#mffechaf').val('');
            $('#mffechae').val('');
            $('#mfarchivos').val('0');
        }

        function abrirnuevo() {
            //modal :modal_form
            //from  :form
            console.log('abrirnuevo()');
            $('#mfubigeo').val($('#vmunicipio').val());
            $('#modal_form .modal-title').html('Nuevo Registro');
            // cargareess();
            save_method = 'add';

        }

        function editseguimiento(id) {
            //modal :modal_form
            //from  :form
            save_method = 'update';
            console.log('editseguimiento()');
            limpiarfrm();
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
                    $('#modal_form .modal-title').html('Modificar Registro');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error(
                        'No se puede eliminar este registro por seguridad de su base de datos, Contacte al Administrador del Sistema',
                        'Mensaje');
                }
            });
            // cargareess();
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
                    console.log(data)
                    if (data.status) {
                        limpiarfrm();

                        // table_seguimiento.ajax.reload(null, false);
                        table_principal.ajax.reload(null, false);
                        toastr.success(msgsuccess, 'Mensaje');
                        if (save_method != 'add') {
                            $('#modal_form').modal('hide');
                        }
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

        function eliminarseguimiento(id) {
            bootbox.confirm("¿Seguro desea eliminar esta importación?", function(result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ route('imporpadronactas.registro.eliminar', '') }}/" + id,
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                            // table_seguimiento.ajax.reload(null, false);
                            table_principal.ajax.reload(null, false);
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

        function verdatos(eess) {
            $('.modal-title').html('Actas Homologadas');
            cargarregistros(eess);
        }

        function cargarregistros(eess) {
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
            window.open("{{ route('imporpadronactas.registro.excel', ['', '', '', '', '', '']) }}/" + $('#vmunicipio')
                .val() + "/" + $('#vred').val() + "/" + $('#vmicrored').val() + "/" + $('#vfechai').val() + "/" +
                $('#vfechaf').val() + "/{{ $registrador }}");
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
    </script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <!-- optional -->
    <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
@endsection
