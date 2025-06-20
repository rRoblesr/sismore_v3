@extends('layouts.main', ['activePage' => 'importacion', 'titlePage' => ''])

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
    <style>

    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header bg-success-0">
                    <div class="card-widgets">
                        {{-- <button type="button" class="btn btn-orange-0 btn-xs" onclick="history.back()" title='Volver'><i
                                class="fas fa-arrow-left"></i> Volver</button> --}}
                        <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()">
                            <i class="fa fa-redo"></i> Actualizar</button>
                    </div>
                    <h3 class="card-title text-white font-12">
                        Reportes del Padrón Nominal - {{ $actualizado }}
                    </h3>
                </div>
                <div class="card-body pb-0">
                    <div class="form-group row align-items-center vh-5">

                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="anio">Año</label>
                                <select id="anio" name="anio" class="form-control form-control-sm"
                                    onchange="cargarMes();">
                                    @foreach ($anios as $item)
                                        <option value="{{ $item->anio }}" {{ $item->anio==$aniomax?'selected':'' }}>{{ $item->anio }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="mes">Mes</label>
                                <select id="mes" name="mes" class="form-control form-control-sm"
                                    onchange="cargarCards()">
                                    <option value="0">TODOS</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="edades">Edad del Menor</label>
                                <select id="edades" name="edades" class="form-control form-control-sm"
                                    onchange="cargarCards()">
                                    <option value="0">TODOS</option>
                                    {{--  @foreach ($edades as $item)
                                        <option value="{{ $item->edades_id }}">{{ $item->edades }}</option>
                                    @endforeach --}}
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="indicador">Indicador</label>
                                <select id="indicador" name="indicador" class="form-control form-control-sm"
                                    onchange="cargarCards()">
                                    {{-- <option value="0">TODOS</option> --}}
                                    <option value="1">Niñas y Niños con DNI</option>
                                    <option value="2">Niñas y Niños con DNI de 0 a 30 días</option>
                                    <option value="3">Niñas y Niños con DNI menores a 60 días </option>
                                    <option value="4">Niñas y Niños con Seguro de Salud</option>
                                    <option value="5">Niñas y Niños con Programas Sociales</option>
                                    <option value="6">Niñas y Niños con Establecimientos de Salud de Atención</option>
                                    <option value="7">Niñas y Niños con Visita Domiciliaria</option>
                                    <option value="8">Niñas y Niños No Encontrados</option>
                                    <option value="9">Niñas y Niños Visitados y No Encontrados</option>
                                    <option value="10">Niñas y Niños con Institución Educativa</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                    <h3 class="text-black text-center font-weight-normal font-11 m-0"></h3>
                </div>
                <div class="card-body p-0">
                    <div class="row" style="height: 36rem">
                        <div class="col-12">
                            <div class="table-responsive" id="ctabla1">

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                    <h3 class="text-black text-center font-weight-normal font-11 m-0"></h3>
                </div>
                <div class="card-body p-0">
                    <div id="anal1" style="height: 36rem"></div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-success-0 btn-xs" onclick="descargarExcel()">
                            <i class="fa fa-file-excel"></i> Descargar</button>
                    </div>
                    <h3 class="card-title">resumen de cumplimiento por distrito
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" id="ctabla2">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-centropoblado" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title" id="myLargeModalLabel">resumen de cumplimiento por centro poblado
                    </h5>
                    <div class="card-widgets">
                        <button type="button" class="btn btn-success-0 btn-xs" onclick="descargarExcel2()">
                            <i class="fa fa-file-excel"></i> Descargar</button>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" id="ctabla0201">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.modal -->
@endsection

@section('js')
    <script type="text/javascript">
        var paleta_colores = ['#5eb9aa', '#F9FFFE', '#f5bd22', '#058DC7', '#50B432', '#9D561B', '#DDDF00', '#24CBE5',
            '#64E572', '#9F9655', '#FFF263', '#6AF9C4'
        ];
        var tableprincipal;
        var ubigeo_select;
        $(document).ready(function() {
            cargarMes();
        });

        function cargarCards() {
            panelGraficas('anal1');
            panelGraficas('tabla1');
            panelGraficas('tabla2');
        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('salud.padronnominal.tablerocalidad.indicador.reporte') }}",
                data: {
                    div: div,
                    anio: $('#anio').val(),
                    mes: $('#mes').val(),
                    edades: $('#edades').val(),
                    indicador: $('#indicador').val(),
                    "ubigeo": ubigeo_select,
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    if (div == "anal1" || div == "anal2") {
                        $('#' + div).html(`
                                        <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
                                            <span class="spinner">
                                                <i class="fa fa-spinner fa-spin"></i>
                                            </span>
                                        </div>
                                    `);
                    } else {
                        $('#c' + div).html(`
                                        <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
                                            <span class="spinner">
                                                <i class="fa fa-spinner fa-spin"></i>
                                            </span>
                                        </div>
                                    `);
                    }
                },
                success: function(data) {
                    if (div == "anal1") {
                        gbar('anal1', data.info.categoria,
                            data.info.serie,
                            '',
                            'Porcentaje de Cumplimiento por Distrito',
                        );
                    } else if (div == "tabla1") {
                        $('#ctabla1').html(data.excel);
                        // $('#tabla2').DataTable({
                        //     responsive: true,
                        //     autoWidth: false,
                        //     ordered: true,
                        //     language: table_language,
                        // });
                    } else if (div == "tabla2") {
                        $('#ctabla2').html(data.excel);
                        // $('#tabla2').DataTable({
                        //     responsive: true,
                        //     autoWidth: false,
                        //     ordered: true,
                        //     language: table_language,
                        // });
                    } else if (div == "tabla0201") {
                        $('#ctabla0201').html(data.excel);
                        $('#tabla0201').DataTable({
                            responsive: true,
                            autoWidth: false,
                            ordered: true,
                            language: table_language,
                        });
                    }
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
        }

        function abrirmodalcentropoblado2(ubigeo) {
            ubigeo_select = ubigeo;
            // console.log(ubigeo_select)]
            panelGraficas('tabla0201');

            $('#modal-centropoblado').modal('show');

        }

        function cargarMes() {
            $.ajax({
                url: "{{ route('salud.padronnominal.mes', ['anio' => 'anio']) }}".replace('anio', $('#anio')
                    .val()),
                type: 'GET',
                success: function(data) {
                    $("#mes option").remove();
                    var options = ''; // '<option value="0">TODOS</option>';

                    var mesmax = Math.max(...data.map(item => item.id));
                    $.each(data, function(ii, vv) {
                        ss = vv.id == mesmax ? 'selected' : '';
                        options += `<option value='${vv.id}' ${ss}>${vv.mes}</option>`
                    });
                    $("#mes").append(options);
                    cargarEdades();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarEdades() {
            $.ajax({
                url: "{{ route('salud.padronnominal.edades', ['anio' => 'anio', 'mes' => 'mes']) }}"
                    .replace('anio', $('#anio').val())
                    .replace('mes', $('#mes').val()),
                type: 'GET',
                success: function(data) {
                    $("#edades option").remove();
                    var options = '<option value="0">TODOS</option>';
                    // var mesmax = Math.max(...data.map(item => item.id));
                    $.each(data, function(ii, vv) {
                        ss = ''; // vv.id == mesmax ? 'selected' : '';
                        options += `<option value='${vv.edades_id}' ${ss}>${vv.edades}</option>`
                    });
                    $("#edades").append(options);
                    cargarCards();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function descargarExcel() {
            window.open(
                "{{ route('salud.padronnominal.tablerocalidad.indicador.exportar.excel', ['div' => 'tabla2', 'anio' => 'anio', 'mes' => 'mes', 'edades' => 'edades', 'indicador' => 'indicador', 'ubigeo' => 'ubigeo']) }}"
                .replace('anio', $('#anio').val())
                .replace('mes', $('#mes').val())
                .replace('edades', $('#edades').val())
                .replace('indicador', $('#indicador').val())
                .replace('ubigeo', '0')
            );
        }

        function descargarExcel2() {
            window.open(
                "{{ route('salud.padronnominal.tablerocalidad.indicador.exportar.excel2', ['div' => 'tabla0201', 'anio' => 'anio', 'mes' => 'mes', 'edades' => 'edades', 'indicador' => 'indicador', 'ubigeo' => 'ubigeo']) }}"
                .replace('anio', $('#anio').val())
                .replace('mes', $('#mes').val())
                .replace('edades', $('#edades').val())
                .replace('indicador', $('#indicador').val())
                .replace('ubigeo', ubigeo_select)
            );
        }

        function gbar(div, categoria, series, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: titulo,
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        // fontSize: '11px'
                    }
                },
                xAxis: {
                    categories: categoria,
                    title: {
                        text: '',
                    },
                    labels: {
                        style: {
                            fontSize: '10px',
                        },
                        // enabled: false,
                    },
                },
                yAxis: {
                    //min: 0,
                    title: {
                        text: '',
                        align: 'high'
                    },
                    labels: {
                        style: {
                            fontSize: '10px',
                        },
                        overflow: 'justify',
                        enabled: false,
                    },
                },
                tooltip: {
                    valueSuffix: ' %'
                },
                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true,
                            format: '{point.y} %'
                        }
                    }
                },
                legend: {
                    enabled: false, //
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    x: -40,
                    y: 80,
                    floating: true,
                    borderWidth: 1,
                    backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
                    shadow: true
                },
                series: [{
                    name: 'Cumplimiento',
                    showInLegend: false,
                    label: {
                        enabled: false
                    },
                    data: series,
                    // color: '#43beac'
                }],
                credits: {
                    enabled: false
                },
            });
        }
    </script>


    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
    <!-- optional -->
    <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>

    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script> --}}
@endsection
