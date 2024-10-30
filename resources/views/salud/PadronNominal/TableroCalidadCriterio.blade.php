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
                        <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()">
                            <i class="fa fa-redo"></i> Volver</button>
                        <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()">
                            <i class="fa fa-redo"></i> Actualizar</button>
                    </div>
                    <h3 class="card-title text-white font-12">
                        Registro sin Número de Documento(DNI, CNV, CUI)
                    </h3>
                </div>
                <div class="card-body pb-0">
                    <div class="form-group row align-items-center vh-5">
                        <div class="col-lg-3 col-md-4 col-sm-4">
                            <h4 class="page-title font-12">{{ $actualizado }}</h4>
                        </div>

                        <div class="col-lg-3 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="red">RED</label>
                                <select id="red" name="red" class="form-control btn-xs font-11"
                                    onchange="cargarMicrored();cargarCards();">
                                    <option value="0">TODOS</option>
                                    @foreach ($red as $item)
                                        <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>


                        </div>
                        <div class="col-lg-3 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="microred">MICRORED</label>
                                <select id="microred" name="microred" class="form-control btn-xs font-11"
                                    onchange="cargarEstablecimiento();cargarCards();">
                                    <option value="0">TODOS</option>
                                </select>
                            </div>


                        </div>
                        <div class="col-lg-3 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="establecimiento">ESTABLECIMIENTO</label>
                                <select id="establecimiento" name="establecimiento" class="form-control btn-xs font-11"
                                    onchange="cargarCards();">
                                    <option value="0">TODOS</option>
                                </select>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                {{-- <div class="card-header">
                    <h3 class="card-title">Población de niños y niñas menos de 6 años por distrito, segun sexo y edades
                    </h3>
                </div> --}}
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tabla1" class="table table-sm table-striped table-bordered font-11">
                                    <thead>
                                        <tr class="table-success-0 text-white">
                                            <th class="text-center">N°</th>
                                            <th class="text-center">Código Padrón</th>
                                            <th class="text-center">Tipo Documento</th>
                                            <th class="text-center">Apellidos y Nombre</th>
                                            <th class="text-center">Fecha Nacimiento</th>
                                            <th class="text-center">Distrito</th>
                                            <th class="text-center">Centro Poblado</th>
                                            <th class="text-center">Código EESS</th>
                                            <th class="text-center">EESS de Atención</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        var paleta_colores = ['#5eb9aa', '#F9FFFE', '#f5bd22', '#058DC7', '#50B432', '#9D561B', '#DDDF00', '#24CBE5',
            '#64E572', '#9F9655', '#FFF263', '#6AF9C4'
        ];
        var tableprincipal;
        $(document).ready(function() {
            cargarCards();

        });

        function cargarCards() {
            tableprincipal = $('#tabla1').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: true,
                language: table_language,
                destroy: true,
                // ajax: "{{ route('salud.padronnominal.tablerocalidad.criterio.listar') }}",
                // type: "get",
                ajax: {
                    url: "{{ route('salud.padronnominal.tablerocalidad.criterio.listar') }}",
                    type: "GET",
                    data: function(d) {
                        d.importacion = {{ $impMaxAnio }};
                    }
                },
            });
        }

        function cargarMicrored() {
            $.ajax({
                url: "{{ route('salud.padronnominal.tablerocalidad.criterio.microred', ['importacion' => $impMaxAnio, 'red' => 'xred', 'criterio' => $criterio]) }}"
                    .replace('xred', $('#red').val()),
                type: 'GET',
                success: function(data) {
                    $("#microred option").remove();
                    var options = '<option value="0">TODOS</option>';
                    $.each(data, function(index, value) {
                        options += `<option value='${value.id}'>${value.nombre}</option>`;
                    });
                    $("#microred").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarEstablecimiento() {
            $.ajax({
                url: "{{ route('salud.padronnominal.tablerocalidad.criterio.criterio1_establecimiento', ['importacion' => $impMaxAnio, 'red' => 'xred', 'microred' => 'xmicrored', 'criterio' => $criterio]) }}"
                    .replace('xred', $('#red').val())
                    .replace('xmicrored', $('#microred').val()),
                type: 'GET',
                success: function(data) {
                    $("#establecimiento option").remove();
                    var options = '<option value="0">TODOS</option>';
                    $.each(data, function(index, value) {
                        options += `<option value='${value.id}'>${value.nombre}</option>`;
                    });
                    $("#establecimiento").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        // function cargarDistritos() {
        //     $.ajax({
        //         url: "{{ route('ubigeo.distrito.25', '') }}/" + $('#provincia').val(),
        //         type: 'GET',
        //         success: function(data) {
        //             $("#distrito option").remove();
        //             var options = '<option value="0">TODOS</option>';
        //             $.each(data, function(index, value) {
        //                 options += "<option value='" + value.id + "'>" + value.nombre +
        //                     "</option>"
        //             });
        //             $("#distrito").append(options);
        //         },
        //         error: function(jqXHR, textStatus, errorThrown) {
        //             console.log(jqXHR);
        //         },
        //     });
        // }

        // function cargarMes() {
        //     $.ajax({
        //         url: "{{ route('salud.padronnominal.mes', '') }}/" + $('#anio').val(),
        //         type: 'GET',
        //         success: function(data) {
        //             $("#mes option").remove();
        //             var options = '<option value="0">TODOS</option>';

        //             var mesmax = Math.max(...data.map(item => item.id));
        //             // console.log("Mes máximo:", mesmax);
        //             $.each(data, function(ii, vv) {
        //                 ss = vv.id == mesmax ? 'selected' : '';
        //                 options += `<option value='${vv.id}' ${ss}>${vv.mes}</option>`
        //             });
        //             $("#mes").append(options);
        //         },
        //         error: function(jqXHR, textStatus, errorThrown) {
        //             console.log(jqXHR);
        //         },
        //     });
        // }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('salud.padronnominal.tablerocalidad.reporte') }}",
                data: {
                    'div': div,
                    "anio": $('#anio').val(),
                    "mes": $('#mes').val(),
                    "provincia": $('#provincia').val(),
                    "distrito": $('#distrito').val(),
                },
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    switch (div) {
                        case 'head':
                            $('#card1').text(data.card1).counterUp({
                                delay: 10,
                                time: 1000
                            });
                            $('#card2').text(data.card2).counterUp({
                                delay: 10,
                                time: 1000
                            });
                            $('#card3').text(data.card3).counterUp({
                                delay: 10,
                                time: 1000
                            });
                            $('#card4').text(data.card4).counterUp({
                                delay: 10,
                                time: 1000
                            });
                            break;
                        case 'anal1':
                            console.log(data.avance);
                            GaugeSeries('anal1', data.avance, 'Porcentaje de Visitados');
                            break;
                        case 'anal2':
                            GaugeSeries('anal2', data.avance, 'Porcentaje con DNI');
                            break;
                        case 'anal3':
                            GaugeSeries('anal3', data.avance, 'Porcentaje con Seguro Salud');
                            break;
                        case 'anal4':
                            GaugeSeries('anal4', data.avance, 'Porcentaje con EESS de atención');
                            break;
                        case 'tabla1':
                            $('#ctabla1').html(data.excel);
                            break;
                        case 'tabla2':
                            $('#ctabla2').html(data.excel);
                            // $('#tabla2').DataTable({
                            //     responsive: true,
                            //     autoWidth: false,
                            //     ordered: true,
                            //     language: table_language,
                            // });
                            break;

                        default:
                            break;
                    }

                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
        }

        function GaugeSeries(div, data, title) {
            Highcharts.chart(div, {
                chart: {
                    height: 200,
                    type: 'solidgauge',
                    margin: [10, 10, 10, 10],
                    spacing: [10, 10, 10, 10]
                },
                title: {
                    text: title,
                    verticalAlign: 'top',
                    style: {
                        fontSize: '12px',
                        fontWeight: 'normal'
                    }
                },
                pane: {
                    startAngle: 0,
                    endAngle: 360,
                    background: [{ // Fondo del anillo
                        outerRadius: '100%',
                        innerRadius: '80%',
                        backgroundColor: Highcharts.color('#E0E0E0').setOpacity(0.3).get(),
                        borderWidth: 0
                    }]
                },
                yAxis: {
                    min: 0,
                    max: 100,
                    lineWidth: 0,
                    tickPositions: []
                },
                plotOptions: {
                    solidgauge: {
                        dataLabels: {
                            format: '<div style="text-align:center"><span style="font-size:24px">{y}%</span><br/>' +
                                '<span style="font-size:12px;opacity:0.6">Avance</span></div>',
                            y: -25,
                            borderWidth: 0,
                            useHTML: true
                        },
                        // linecap: 'round',
                        // rounded: true
                    }
                },
                series: [{
                    name: 'Avance',
                    data: [{
                        y: data,
                        color: data >= 95 ? '#5eb9aa' : (data >= 75 ? '#f5bd22' :
                            '#ef5350')
                    }],
                    innerRadius: '80%',
                    radius: '100%',
                }],
                tooltip: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: false
                }
            });
        }

        function GaugeSeriesyyy(div, data) {
            Highcharts.chart(div, {
                chart: {
                    height: 165,
                    margin: [0, 0, 0, 0],
                    spacing: [0, 0, 0, 0],
                    type: 'solidgauge'
                },
                yAxis: {
                    min: 0,
                    max: 150,
                    dataClasses: [{
                            from: 0,
                            to: 50,
                            color: '#ef5350'
                        },
                        {
                            from: 51,
                            to: 99,
                            color: '#f5bd22'
                        },
                        {
                            from: 100,
                            to: 150,
                            color: '#5eb9aa'
                        }
                    ],
                    labels: {
                        enabled: false
                    },
                    tickLength: 0,
                    lineWidth: 0, // Remueve la línea del borde del gauge
                    gridLineWidth: 0 // Elimina las líneas de división
                },
                pane: {
                    background: [{
                        // Sin borde, remueve el efecto de seccionado
                        outerRadius: '100%',
                        innerRadius: '80%',
                        borderWidth: 0
                    }]
                },
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: false
                },
                title: {
                    text: ''
                },
                plotOptions: {
                    solidgauge: {
                        dataLabels: {
                            format: '<div style="text-align:center; margin-top: -20px">' +
                                '<div style="font-size:2.5em;">{y}%</div>' +
                                '<div style="font-size:12px; opacity:0.4;">Avance</div>' +
                                '</div>',
                            useHTML: true,
                            borderWidth: 0,
                        }
                    }
                },
                series: [{
                    name: 'Avance',
                    innerRadius: '80%',
                    data: [{
                        y: data,
                        color: data >= 100 ? '#5eb9aa' : (data >= 51 ? '#f5bd22' :
                            '#ef5350')
                    }],
                    radius: '100%',
                }],
                tooltip: {
                    valueSuffix: '%',
                    backgroundColor: '#FFFFFF',
                    borderColor: 'gray',
                    shadow: true,
                    style: {
                        fontSize: '12px'
                    }
                }
            });
        }

        function GaugeSeriesxx(div, data) {
            Highcharts.chart(div, {
                chart: {
                    height: 165,
                    margin: [0, 0, 0, 0],
                    spacing: [0, 0, 0, 0],
                    type: 'solidgauge'
                },
                yAxis: {
                    min: 0,
                    max: 150, // Ajustado al máximo de dataClasses
                    dataClasses: [{
                            from: 0,
                            to: 50,
                            color: '#ef5350'
                        },
                        {
                            from: 51,
                            to: 99,
                            color: '#f5bd22'
                        },
                        {
                            from: 100,
                            to: 150,
                            color: '#5eb9aa'
                        }
                    ],
                    labels: {
                        enabled: false
                    },
                    tickLength: 0,
                    lineColor: 'transparent',
                    gridLineWidth: 0
                },
                pane: {
                    background: {
                        innerRadius: '80%',
                        outerRadius: '100%'
                    }
                },
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: false
                },
                title: {
                    text: ''
                },
                plotOptions: {
                    solidgauge: {
                        dataLabels: {
                            format: '<div style="text-align:center; margin-top: -20px">' +
                                '<div style="font-size:2.5em;">{y}%</div>' +
                                '<div style="font-size:12px; opacity:0.4;">Avance</div>' +
                                '</div>',
                            useHTML: true,
                            borderWidth: 0,
                        }
                    }
                },
                series: [{
                    name: 'Avance',
                    innerRadius: '80%',
                    data: [{
                        y: data,
                        color: data >= 100 ? '#5eb9aa' : (data >= 51 ? '#f5bd22' :
                            '#ef5350') // Ajuste de color por valor
                    }],
                    radius: '100%',
                }],
                tooltip: {
                    valueSuffix: '%',
                    backgroundColor: '#FFFFFF',
                    borderColor: 'gray',
                    shadow: true,
                    style: {
                        fontSize: '12px'
                    }
                }
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
