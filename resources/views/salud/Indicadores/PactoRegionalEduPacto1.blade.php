@extends('layouts.main', ['titlePage' => ''])
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
    <style>
        /* Cambia el tamaño de fuente del campo de búsqueda */
        .dataTables_wrapper .dataTables_filter {
            font-size: 10px;
        }

        /* Cambia el tamaño de fuente del selector de cantidad de registros */
        .dataTables_wrapper .dataTables_length {
            font-size: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header bg-success-0">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-orange-0 btn-xs" onclick="history.back()" title="ACTUALIZAR"><i
                                class="fas fa-arrow-left"></i> Volver</button>
                        <button type="button" class="btn btn-orange-0 btn-xs" onclick="verpdf({{ $ind->id }})"
                            title='FICHA TÉCNICA'><i class="fas fa-file"></i> Ficha Técnica</button>
                        <button type="button" class="btn btn-orange-0 btn-xs" onclick="location.reload()"
                            title='ACTUALIZAR'><i class=" fas fa-history"></i>
                            Actualizar</button>
                    </div>
                    <h3 class="card-title text-white">
                        {{ $ind->nombre }}
                    </h3>
                </div>
                <div class="card-body p-2">
                    <div class="form-group row align-items-center vh-5 m-0">
                        <div class="col-lg-5 col-md-5 col-sm-5">
                            <h5 class="page-title font-12">Fuente: Padrón Nominal, SIAGIE <br>{{ $actualizado }}
                            </h5>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1  ">
                            <div class="custom-select-container">
                                <label for="anio">AÑO</label>
                                <select id="anio" name="anio" class="form-control btn-xs font-11 p-0"
                                    onchange="cargarcuadros();">
                                    @foreach ($anio as $item)
                                        <option value="{{ $item->anio }}"
                                            {{ $item->anio == date('Y') ? 'selected' : '' }}>
                                            {{ $item->anio }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="mes">MES </label>
                                <select id="mes" name="mes" class="form-control btn-xs font-11"
                                    onchange="cargarcuadros();">
                                    @foreach ($mes as $item)
                                        <option value="{{ $item->id }}"{{ $item->id == $aniomin ? ' selected ' : '' }}>
                                            {{ $item->mes }}</option>
                                    @endforeach
                                </select>
                            </div>


                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="provincia">PROVINCIA</label>
                                <select id="provincia" name="provincia" class="form-control btn-xs font-11"
                                    onchange="cargarDistritos();">
                                    <option value="0">TODOS</option>
                                    @foreach ($provincia as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>


                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="distrito">DISTRITO</label>
                                <select id="distrito" name="distrito" class="form-control btn-xs font-11"
                                    onchange="cargarcuadros();">
                                    <option value="0">TODOS</option>
                                </select>
                            </div>


                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Widget-4 -->
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card-box border border-plomo-0">
                <div class="media">
                    <div class="text-center">
                        {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                                    width="70%" height="70%"> --}}
                        <i class="mdi mdi-finance font-35 text-green-0"></i>
                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="rin"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">Resultado del Indicador</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card-box-->
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card-box border border-plomo-0">
                <div class="media">
                    <div class="text-center">
                        {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                                    width="70%" height="70%"> --}}
                        <i class=" mdi mdi-city font-35 text-green-0"></i>
                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="loc"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">
                                Denominador
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card-box-->
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card-box border border-plomo-0">
                <div class="media">
                    <div class="text-center">
                        {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                                    width="70%" height="70%"> --}}
                        <i class="mdi mdi-thumb-up font-35 text-green-0"></i>
                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="ssa"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">
                                Numerador
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card-box-->
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card-box border border-plomo-0">
                <div class="media">
                    <div class="text-center">
                        {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                                    width="70%" height="70%"> --}}
                        <i class="mdi mdi-thumb-down font-35 text-green-0"></i>
                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="nsa"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">
                                Brecha
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- portles --}}

    <div class="row">

        <div class="col-lg-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent p-0">
                    {{-- <div class="card-widgets">
                                <button type="button" class="btn btn-success btn-xs"><i
                                        class="fa fa-file-excel"></i> Descargar</button>
                            </div> --}}
                    <h3 class="text-black font-14 mb-0">Avance acumulado de la evaluación de Cumplimiento por
                        Distrito
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" style="height: 40rem" id="vtabla1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent p-0">
                    <h3 class="text-black text-center font-weight-normal font-11 m-0"></h3>
                </div>
                <div class="card-body p-0">
                    <div id="anal1" style="height: 20rem"></div>
                </div>
            </div>

            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent p-0">
                    <h3 class="text-black text-center font-weight-normal font-11 m-0"></h3>
                </div>
                <div class="card-body p-0">
                    <div id="anal2" style="height: 20rem"></div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent p-0">
                    {{-- <div class="card-widgets">
                                <button type="button" class="btn btn-success btn-xs" onclick="descargar1()"><i
                                        class="fa fa-file-excel"></i> Descargar</button>
                            </div> --}}
                    <h3 class="text-black font-14 mb-0">
                        Matrícula de estudiantes de educación incial por edades y sexo, según nivel educativo
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" id="vtabla2">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent p-0">
                    {{-- <div class="card-widgets">
                                <button type="button" class="btn btn-success btn-xs" onclick="descargar1()"><i
                                        class="fa fa-file-excel"></i> Descargar</button>
                            </div> --}}
                    <h3 class="text-black font-14 mb-0">
                        Evaluación de los cumplimientos de los logros esperados por distritos
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" id="vtabla3">
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
        var ugel_select = 0;
        var anal1, anal2;
        $(document).ready(function() {
            Highcharts.setOptions({
                lang: {
                    thousandsSep: ","
                }
            });
            cargarDistritos();
            cargarcuadros();
        });

        function cargarcuadros() {
            panelGraficas('head');
            panelGraficas('anal1');
            panelGraficas('anal2');
            panelGraficas('tabla1');
            panelGraficas('tabla2');
            panelGraficas('tabla3');
        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('salud.indicador.pactoregional.edu.pacto1.reports') }}",
                data: {
                    'div': div,
                    "anio": $('#anio').val(),
                    "mes": $('#mes').val(),
                    "provincia": $('#provincia').val(),
                    "distrito": $('#distrito').val(),
                    "indicador": '{{ $ind->id }}',
                    "codigo": '{{ $ind->codigo }}',
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    if (div == "tabla1") {
                        $('#v' + div).html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                    } else if (div == "tabla2") {
                        $('#v' + div).html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                    } else {
                        $('#' + div).html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                    }
                },
                success: function(data) {
                    if (div == "head") {
                        $('#rin').text(data.rin + '%');
                        $('#nsa').text(data.nsa);
                        $('#ssa').text(data.ssa);
                        $('#loc').text(data.loc);
                    } else if (div == "anal1") {
                        anal1 = gLinea1(div)
                        anal1.setTitle({
                            text: ''
                        }, {
                            text: 'Avance Mensual de la evaluación del cumplimiento'
                        });
                        // anal1.series[0].setData([22, 25, 32]);
                        anal1.series[0].setData(data.info.serie);
                        anal1.xAxis[0].update({
                            categories: data.info.categoria, //['4 Años', '5 Años', '6 Años']
                        });
                    } else if (div == "anal2") {
                        anal2 = gcolumn1(div);
                        anal2.setTitle({
                            text: ''
                        }, {
                            text: 'Tasa neta de matrícula inicial de 3 a 5 años'
                        });

                        anal2.yAxis[0].update({
                            max: 2 * data.alto
                        });
                        anal2.series[0].setData(data.info.serie[0]);
                        anal2.series[1].setData(data.info.serie[1]);
                        anal2.series[2].setData(data.info.serie[2]);

                        // chart.series[0].setData([63434, 70731, 71536]);
                        // chart.series[1].setData([7169, 12146, 13739]);
                        // chart.series[2].setData([11.3, 17.1, 19.2]);
                        // chart.series[2].setData([50.0, 60.5, 65.3]);
                        // chart.xAxis[0].update({
                        //     categories: ['4 Años', '5 Años', '6 Años']
                        // });
                    } else if (div == "tabla1") {
                        $('#vtabla1').html(data.excel);
                        // $('.vtabla1-fuente').html('Fuente: ' + data.reg.fuente);
                        // $('.vtabla1-fecha').html('Actualizado: ' + data.reg.fecha);
                        // $('#tabla1').DataTable({
                        //     responsive: true,
                        //     autoWidth: false,
                        //     ordered: true,
                        //     searching: false,
                        //     bPaginate: false,
                        //     info: false,
                        //     language: table_language,
                        // });
                    } else if (div == "tabla2") {
                        $('#vtabla2').html(data.excel);
                        $('#tabla2').DataTable({
                            responsive: true,
                            autoWidth: false,
                            ordered: true,
                            searching: false,
                            bPaginate: false,
                            info: false,
                            language: table_language,
                        });
                    } else if (div == "tabla3") {
                        $('#vtabla3').html(data.excel);
                        $('#tabla3').DataTable({
                            responsive: true,
                            autoWidth: false,
                            ordered: true,
                            searching: false,
                            bPaginate: false,
                            info: false,
                            language: table_language,
                        });
                    } else if (div == "tabla4") {
                        $('#vtabla4').html(data.excel);
                        $('#tabla4').DataTable({
                            responsive: true,
                            autoWidth: false,
                            ordered: true,
                            // searching: false,
                            // bPaginate: false,
                            // info: false,
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

        function cargarTablaNivel(div, ugel) {
            $.ajax({
                url: "{{ route('indicador.nuevos.01.tabla') }}",
                data: {
                    'div': div,
                    "anio": $('#anio').val(),
                    "provincia": $('#provincia').val(),
                    "distrito": $('#distrito').val(),
                    "gestion": $('#gestion').val(),
                    "ugel": ugel
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    ugel_select = ugel;
                    if (div == "tabla1") {
                        $('#v' + div).html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                    } else if (div == "tabla2") {
                        $('#v' + div).html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                    } else {
                        $('#' + div).html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                    }
                },
                success: function(data) {
                    if (div == "tabla2") {
                        $('#vtabla2').html(data.excel);
                    }
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
        }

        function cargarDistritos() {
            $.ajax({
                url: "{{ route('ubigeo.distrito.25', '') }}/" + $('#provincia').val(),
                type: 'GET',
                success: function(data) {
                    $("#distrito option").remove();
                    var options = '<option value="0">TODOS</option>';
                    $.each(data, function(index, value) {
                        //ss = (id == value.id ? "selected" : "");
                        options += "<option value='" + value.id + "'>" + value.nombre +
                            "</option>"
                    });
                    $("#distrito").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function descargar1() {
            window.open("{{ url('/') }}/INDICADOR/Home/01/Excel/tabla1/" + $('#anio').val() + "/" + $('#provincia')
                .val() + "/" + $('#distrito').val() + "/" + $('#gestion').val() + "/0");
        }

        function descargar2() {
            window.open("{{ url('/') }}/INDICADOR/Home/01/Excel/tabla2/" + $('#anio').val() + "/" + $('#provincia')
                .val() + "/" + $('#distrito').val() + "/" + $('#gestion').val() + "/" + ugel_select);
        }

        function verpdf(id) {
            window.open("{{ route('salud.indicador.pactoregional.exportar.pdf', '') }}/" + id);
        };

        function gLinea1(div) {
            return Highcharts.chart(div, {
                chart: {
                    type: 'line',
                    // borderRadius: 10,
                    // borderWidth: 1,
                    // borderColor: '#000'
                },
                title: {
                    text: ''
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    categories: [],
                    labels: {
                        style: {
                            fontSize: '10px' // Ajusta el tamaño de la fuente
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: ''
                    },
                    // max: 8,
                    // tickInterval: 1,
                    labels: {
                        style: {
                            fontSize: '10px' // Ajusta el tamaño de la fuente
                        }
                    }
                },
                series: [{
                    name: 'Avance',
                    data: [],
                    marker: {
                        symbol: 'circle',
                        radius: 4
                    },
                    color: '#d2232a'
                }],
                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.1f}%',
                            style: {
                                color: '#000'
                            },
                            align: 'right',
                            crop: false,
                            overflow: 'none'
                        }
                    }
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.y:.1f}%</b>'
                },
                legend: {
                    enabled: false // Ocultar la leyenda
                },
                credits: {
                    enabled: false
                }
            });
        }

        function gcolumn1(div) {
            return Highcharts.chart(div, {
                chart: {
                    type: 'column', // Tipo de gráfico principal
                    // borderColor: '#CCC',
                    // borderWidth: 2,
                    plotShadow: true
                },
                // color: ['#5eb9aa', '#ef5350', '#f5bd22', '#ef5350'],
                colors: ['#5eb9aa', '#f5bd22', '#ef5350'],
                title: {
                    text: '' // 'Tasa neta de matrícula inicial de 3 a 5 años'
                },
                xAxis: {
                    categories: ['3 Años', '4 Años', '5 Años'], // Categorías del eje X
                    crosshair: true,
                    labels: {
                        style: {
                            fontSize: '11px',
                            //color:'blue',
                        }
                    }
                },
                // yAxis: {
                //     min: 0,
                //     title: {
                //         text: ''
                //     },
                //     labels: {
                //         style: {
                //             fontSize: '10px',
                //             //color:'blue',
                //         }
                //     }
                // },
                yAxis: [{ // Eje Y principal para las columnas
                    min: 0,
                    // max:40000,
                    title: {
                        text: ''
                    },
                    labels: {
                        style: {
                            fontSize: '11px',
                            //color:'blue',
                        }
                    }
                }, { // Eje Y secundario para la línea
                    title: {
                        text: ''
                    },
                    labels: {
                        enabled: false,
                        style: {
                            fontSize: '11px',
                            //color:'blue',
                        }
                    },
                    opposite: true, // Ponerlo al lado derecho
                    min: -150
                }],
                tooltip: {
                    shared: true
                },
                plotOptions: {
                    // pointPadding: 0.2, // Ajuste de separación entre barras
                    // borderWidth: 0, // Sin borde
                    column: {
                        dataLabels: {
                            enabled: true
                        }
                    },
                    // series: {
                    //     groupPadding: 0.1 // Ajusta la separación entre grupos de barras
                    // }
                },
                series: [{
                    name: 'Población',
                    data: [18, 20, 30], // Datos de la población
                    // color: '#00B4D8' // Color de la barra de Población
                    // zIndex: 0 // Asegura que las barras estén debajo de la línea
                }, {
                    name: 'Matriculados',
                    data: [18, 20, 30], // Datos de los matriculados
                    // color: '#F4A261' // Color de la barra de Matriculados
                    // zIndex: 0 // Asegura que las barras estén debajo de la línea
                }, {
                    type: 'line',
                    name: 'Porcentaje',
                    data: [45.8, 59.2, 62.7], // Datos de porcentaje
                    // color: '#B22222', // Color de la línea
                    yAxis: 1, // Referencia al eje secundario
                    marker: {
                        enabled: true,
                        radius: 5
                    },
                    showInLegend: false,
                    dataLabels: {
                        enabled: true,
                        format: '{y} %', // Formato de los labels del porcentaje
                        style: {
                            fontWeight: 'bold'
                        }
                    }
                }],
                legend: {
                    itemStyle: {
                        //color: "#333333",
                        //cursor: "pointer",
                        fontSize: "11px",
                        //fontWeight: "normal",
                        //textOverflow: "ellipsis"
                    },
                },
                credits: {
                    enabled: false
                }
            });
        }

        function gcolumn2(div, categoria, series, titulo, subtitulo) {
            const colors = ['#5eb9aa', '#ef5350', '#f5bd22', '#ef5350'];
            Highcharts.chart(div, {
                chart: {
                    type: 'column'
                },
                colors,
                title: {
                    text: titulo,
                    //align: 'left'
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        fontSize: '11px',
                    }
                    //align: 'left'
                },
                xAxis: {
                    categories: categoria,
                    labels: {
                        style: {
                            fontSize: '10px',
                            //color:'blue',
                        }
                    }
                    //crosshair: true,
                    //accessibility: {
                    //    description: 'Countries'
                    //}
                },
                yAxis: {
                    min: 0,
                    title: {
                        enabled: false,
                        text: '1000 metric tons (MT)'
                    },
                    labels: {
                        enabled: true,
                        style: {
                            //color: Highcharts.getOptions().colors[2],
                            fontSize: '10px',
                        }
                    },

                },
                tooltip: {
                    //valueSuffix: ' (1000 MT)'
                },
                plotOptions: {
                    series: {
                        //stacking: 'normal', //normal, overlap, percent,stream
                        pointPadding: 0.1, //size de colunma
                        borderWidth: 0 //borde de columna
                    },
                    column: {
                        dataLabels: {
                            enabled: true,
                            //inside: true,
                            style: {
                                fontWeight: 'normal',
                                fontSize: '10px',
                                //color: 'white',
                                //textShadow:false,//quita sombra//para versiones antiguas
                                textOutline: false, //quita sombra
                            }
                        }
                    },
                },
                legend: {
                    itemStyle: {
                        //color: "#333333",
                        //cursor: "pointer",
                        fontSize: "10px",
                        //fontWeight: "normal",
                        //textOverflow: "ellipsis"
                    },
                },
                series: series,
                credits: {
                    enabled: false
                },
            });
        }

        function gSimpleColumn(div, datax, titulo, subtitulo, tituloserie) {

            Highcharts.chart(div, {
                chart: {
                    type: 'column',
                },
                title: {
                    enabled: false,
                    text: titulo,
                },
                subtitle: {
                    enabled: false,
                    //text: subtitulo,
                },
                xAxis: {
                    type: 'category',
                },
                yAxis: {
                    /* max: 100, */
                    title: {
                        enabled: false,
                        text: 'Porcentaje',
                    }
                },
                /* colors: [
                    '#8085e9',
                    '#2b908f',
                ], */
                series: [{
                    showInLegend: tituloserie != '',
                    name: tituloserie,
                    label: {
                        enabled: false
                    },
                    colorByPoint: false,
                    data: datax,
                }],
                tooltip: {
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> Hay: <b>{point.y}</b><br/>',
                    shared: true
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                        },
                    }
                },
                exporting: {
                    enabled: false
                },
                credits: false,
            });
        }

        function gPie(div, datos, titulo, subtitulo, tituloserie) {
            const colors = ["#5eb9aa", "#f5bd22", "#e65310"];
            Highcharts.chart(div, {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    enabled: false,
                    text: titulo, //'Browser market shares in January, 2018'
                },
                subtitle: {
                    enabled: true,
                    text: subtitulo,
                    style: {
                        fontSize: '11px',
                    }
                },
                tooltip: {
                    //pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>',
                    pointFormat: '<b>{point.percentage:.1f}% ({point.y:,0f})</b>',
                    style: {
                        fontSize: '10px'
                    }
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                }, //labels:{style:{fontSize:'10px'},}
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        colors,
                        dataLabels: {
                            enabled: true,
                            // distance: -20,
                            //format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            //format: '{point.percentage:.1f}% ({point.y})',
                            format: '{point.y:,0f} ( {point.percentage:.1f}% )',
                            // format: '{point.percentage:.1f}%',
                            connectorColor: 'silver',
                        },
                    }
                },
                legend: {
                    itemStyle: {
                        //color: "#333333",
                        cursor: "pointer",
                        fontSize: "10px",
                        fontWeight: "normal",
                        textOverflow: "ellipsis"
                    },
                },
                series: [{
                    innerSize: '50%',
                    showInLegend: true,
                    //name: 'Share',
                    data: datos,
                }],
                exporting: {
                    enabled: true
                },
                credits: false,
            });
        }

        function gPie2(div, datos, titulo, subtitulo, tituloserie) {
            // const colors = ["#5eb9aa", "#f5bd22", "#e65310"];
            const colors = ['#5eb9aa', '#ef5350', '#f5bd22', '#ef5350'];
            Highcharts.chart(div, {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    enabled: false,
                    text: titulo, //'Browser market shares in January, 2018'
                },
                subtitle: {
                    enabled: true,
                    text: subtitulo,
                    style: {
                        fontSize: '11px',
                    }
                },
                tooltip: {
                    //pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>',
                    pointFormat: '<b>{point.percentage:.1f}% ({point.y:,0f})</b>',
                    style: {
                        fontSize: '10px'
                    }
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                }, //labels:{style:{fontSize:'10px'},}
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        colors,
                        dataLabels: {
                            enabled: true,
                            // distance: -20,
                            //format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            //format: '{point.percentage:.1f}% ({point.y})',
                            format: '{point.y:,0f} ( {point.percentage:.1f}% )',
                            // format: '{point.percentage:.1f}%',
                            connectorColor: 'silver',
                        },
                    }
                },
                legend: {
                    itemStyle: {
                        //color: "#333333",
                        cursor: "pointer",
                        fontSize: "10px",
                        fontWeight: "normal",
                        textOverflow: "ellipsis"
                    },
                },
                series: [{
                    innerSize: '50%',
                    showInLegend: true,
                    //name: 'Share',
                    data: datos,
                }],
                exporting: {
                    enabled: true
                },
                credits: false,
            });
        }

        function gBasicColumn(div, categorias, datos, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'column'
                },
                title: {
                    text: titulo
                },
                subtitle: {
                    text: subtitulo
                },
                xAxis: {
                    categories: categorias,
                },
                yAxis: {

                    min: 0,
                    title: {
                        text: 'Rainfall (mm)',
                        enabled: false
                    }
                },

                tooltip: {
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> Hay: <b>{point.y}</b><br/>',
                    shared: true
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                        },
                    }
                },
                series: datos,
                credits: false,
            });
        }

        function gsemidona(div, valor) {
            Highcharts.chart(div, {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: 0,
                    plotShadow: false,
                    height: 200,
                },
                title: {
                    text: valor + '%', // 'Browser<br>shares<br>January<br>2022',
                    align: 'center',
                    verticalAlign: 'middle',
                    y: 15, //60,
                    style: {
                        //fontWeight: 'bold',
                        //color: 'orange',
                        fontSize: '30'
                    }
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        dataLabels: {
                            enabled: true,
                            distance: -50,
                            style: {
                                fontWeight: 'bold',
                                color: 'white'
                            },

                        },
                        startAngle: -90,
                        endAngle: 90,
                        center: ['50%', '50%'], //['50%', '75%'],
                        size: '120%',
                        borderColor: '#98a6ad',
                        color: '#fff'
                    }
                },
                series: [{
                    type: 'pie',
                    name: 'Avance',
                    innerSize: '65%',
                    data: [
                        ['', valor],
                        //['Edge', 11.97],
                        //['Firefox', 5.52],
                        //['Safari', 2.98],
                        //['Internet Explorer', 1.90],
                        {
                            name: '',
                            y: 100 - valor,
                            dataLabels: {
                                enabled: false
                            }
                        }
                    ]
                }],
                exporting: {
                    enabled: false
                },
                credits: false
            });
        }

        function gLineaBasica(div, data, titulo, subtitulo, titulovetical) {
            const colors = ["#5eb9aa", "#f5bd22", "#e65310"];
            Highcharts.chart(div, {
                title: {
                    text: titulo
                },
                subtitle: {
                    text: subtitulo
                },
                yAxis: {
                    title: {
                        text: titulovetical
                    },
                    labels: {
                        style: {
                            fontSize: '10px'
                        }
                    },
                    min: 0,
                },
                xAxis: {
                    categories: data.cat,
                    labels: {
                        style: {
                            fontSize: '10px'
                        }
                    }
                    /* accessibility: {
                        rangeDescription: 'Range: 2010 to 2017'
                    } */
                },
                /* legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                }, */
                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '10px',
                                fontWeight: 'normal',
                            }
                        },
                        /* label: {
                            connectorAllowed: false
                        },
                        pointStart: 2010 */
                    }
                },
                series: [{
                    name: 'Actas Enviadas',
                    showInLegend: false,
                    data: data.dat
                }],
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                },
                exporting: {
                    enabled: true,
                },
                credits: false,

            });
        }

        function gLineaBasica2(div, data, titulo, subtitulo, titulovetical) {
            const colors = ["#5eb9aa", "#f5bd22", "#e65310"];
            Highcharts.chart(div, {
                title: {
                    text: titulo
                },
                subtitle: {
                    text: subtitulo
                },
                yAxis: {
                    title: {
                        text: titulovetical
                    },
                    labels: {
                        style: {
                            fontSize: '10px'
                        }
                    },
                    min: 0,
                },
                xAxis: {
                    categories: data.cat,
                    labels: {
                        style: {
                            fontSize: '10px'
                        }
                    }
                    /* accessibility: {
                        rangeDescription: 'Range: 2010 to 2017'
                    } */
                },
                /* legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                }, */
                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '10px',
                                fontWeight: 'normal',
                            }
                        },
                        /* label: {
                            connectorAllowed: false
                        },
                        pointStart: 2010 */
                    }
                },
                series: [{
                    name: 'Actas Enviadas',
                    // showInLegend: false,
                    data: data.dat
                }, {
                    name: 'Actas Aprobadas',
                    // showInLegend: false,
                    data: data.dat2
                }],
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                },
                tooltip: {
                    //pointFormat: '<span style="color:{point.color}">\u25CF</span> {point.name}<b>{point.y}</b><br/>',
                    shared: true
                },
                exporting: {
                    enabled: true,
                },
                credits: false,

            });
        }

        function gAnidadaColumn(div, categoria, series, titulo, subtitulo, maxBar) {
            var rango = categoria.length;
            var posPorcentaje = rango * 2 + 1;
            var cont = 0;
            var porMaxBar = maxBar * 0.5;
            Highcharts.chart(div, {
                chart: {
                    zoomType: 'xy',
                },
                colors: ['#5eb9aa', '#f5bd22', '#ef5350'],
                title: {
                    text: titulo, //'Browser market shares in January, 2018'
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        fontSize: '10px',
                    }
                },
                xAxis: [{
                    categories: categoria,
                    crosshair: true,
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    }
                }],
                yAxis: [{ // Primary yAxis
                        max: maxBar > 0 ? maxBar + porMaxBar : null,
                        labels: {
                            enabled: true,
                            style: {
                                //color: Highcharts.getOptions().colors[2],
                                fontSize: '10px',
                            }
                        },
                        // labels: {
                        //     //format: '{value}°C',
                        //     //style: {
                        //     //    color: Highcharts.getOptions().colors[2]
                        //     //}
                        // },
                        title: {
                            enabled: false,
                            text: 'Matriculados',
                            style: {
                                //color: Highcharts.getOptions().colors[2],
                                fontSize: '11px',
                            }
                        },
                        //opposite: true,
                    }, { // Secondary yAxis
                        gridLineWidth: 0, //solo indica el tamaño de la linea
                        labels: {
                            enabled: false,
                        },
                        title: {
                            enabled: false,
                        },
                        /* title: {
                            //text: 'Rainfall',
                            text: '%Indicador',
                            //style: {
                            //    color: Highcharts.getOptions().colors[0]
                            //}
                        }, */
                        /* labels: {
                            //format: '{value} mm',
                            format: '{value} %',
                            //style: {
                            //   color: Highcharts.getOptions().colors[0]
                            //}
                        }, */
                        //min: -200,
                        min: -600,
                        max: 400,
                        opposite: true,
                    },
                    /* { // Tertiary yAxis
                        gridLineWidth: 0,
                        title: {
                            text: 'Sea-Level Pressure',
                            style: {
                                color: Highcharts.getOptions().colors[1]
                            }
                        },
                        labels: {
                            format: '{value} mb',
                            style: {
                                color: Highcharts.getOptions().colors[1]
                            }
                        },
                        opposite: true
                    } */
                ],
                series: series,
                plotOptions: {
                    /* columns: {
                        stacking: 'normal'
                    }, */
                    series: {
                        showInLegend: true,
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            //format: '{point.y:.1f}%',
                            /* formatter: function() {
                                if (this.colorIndex == 2)
                                    return this.y + " %";
                                else
                                    return Highcharts.numberFormat(this.y, 0);
                            }, */
                            style: {
                                fontWeight: 'normal',
                                fontSize: '10px',
                            }
                        },
                    },
                },
                tooltip: {
                    shared: true,
                },
                legend: {
                    itemStyle: {
                        //"color": "#333333",
                        "cursor": "pointer",
                        "fontSize": "10px",
                        "fontWeight": "normal",
                        "textOverflow": "ellipsis"
                    },
                },
                exporting: {
                    enabled: true
                },
                credits: false,
            });
        }
    </script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
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
