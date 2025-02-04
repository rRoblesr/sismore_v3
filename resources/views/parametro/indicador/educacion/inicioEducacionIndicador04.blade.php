@extends('layouts.main', ['titlePage' => 'INDICADOR'])
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
    <style>
        .tablex thead th {
            padding: 6px;
            text-align: center !important;
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
            text-align: center;
        }

        .fuentex {
            font-size: 10px;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header bg-success-0">
                            <div class="card-widgets">
                                <button type="button" class="btn btn-orange-0 btn-xs" onclick="history.back()"
                                    title='VOLVER'><i class="fas fa-arrow-left"></i> Volver</button>
                                <button type="button" class="btn btn-orange-0 btn-xs" onclick=""><i
                                        class="ion ion-logo-usd"></i> Ficha Técnica</button>
                                <button type="button" class="btn btn-orange-0 btn-xs" onclick="location.reload()"><i
                                        class="ion ion-logo-usd"></i> Actualizar</button>
                                <button type="button" class="btn btn-orange-0 btn-xs" onclick="printer()"
                                    title='IMPRIMIR'><i class="fa fa-print"></i></button>
                            </div>
                            <h3 class="card-title text-white">Porcentaje De Docentes Titulados En Educación Secundaria</h3>
                        </div>
                        <div class="card-body pb-0">
                            <div class="form-group row align-items-center vh-5">
                                <div class="col-lg-5 col-md-5 col-sm-5">
                                    <h5 class="page-title font-12">{{ $actualizado }}</h5>
                                </div>
                                <div class="col-lg-1 col-md-1 col-sm-1  ">
                                    <div class="custom-select-container">
                                        <label for="anio">AÑO</label>
                                        <select id="anio" name="anio" class="form-control font-11"
                                            onchange="cargarCards();">
                                            {{-- <option value="0">TODOS</option> --}}
                                            @foreach ($anios as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $item->anio == $anioMax ? 'selected' : '' }}>
                                                    {{ $item->anio }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="custom-select-container">
                                        <label for="provincia">PROVINCIA</label>
                                        <select id="provincia" name="provincia" class="form-control font-11"
                                            onchange="cargarDistritos();cargarCards();">
                                            <option value="0">TODOS</option>
                                            @foreach ($provincias as $item)
                                                <option value="{{ $item->id }}"> {{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="custom-select-container">
                                        <label for="distrito">DISTRITO</label>
                                        <select id="distrito" name="distrito" class="form-control font-11"
                                            onchange="cargarCards();">
                                            <option value="0">TODOS</option>
                                            @foreach ($distritos as $item)
                                                <option value="{{ $item->id }}">{{ $item->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="custom-select-container">
                                        <label for="tipogestion">TIPO DE GESTIÓN</label>
                                        <select id="tipogestion" name="tipogestion" class="form-control font-11"
                                            onchange="cargarCards();">
                                            <option value="0">TODOS</option>
                                            <option value="12">PUBLICA</option>
                                            <option value="3">PRIVADA</option>
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
                                <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                                    width="70%" height="70%">
                            </div>
                            <div class="media-body align-self-center">
                                <div class="text-right">
                                    <h4 class="font-20 my-0 font-weight-bold">
                                        <span data-plugin="counterup" id="valor1">
                                        </span>
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
                                <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                                    width="70%" height="70%">
                            </div>
                            <div class="media-body align-self-center">
                                <div class="text-right">
                                    <h4 class="font-20 my-0 font-weight-bold">
                                        <span data-plugin="counterup" id="valor2">
                                        </span>
                                    </h4>
                                    <p class="mb-0 mt-1 text-truncate">Total de Docentes</p>
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
                                <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                                    width="70%" height="70%">
                            </div>
                            <div class="media-body align-self-center">
                                <div class="text-right">
                                    <h4 class="font-20 my-0 font-weight-bold">
                                        <span data-plugin="counterup" id="valor3">
                                        </span>
                                    </h4>
                                    <p class="mb-0 mt-1 text-truncate">Docentes con Título</p>
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
                                <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                                    width="70%" height="70%">
                            </div>
                            <div class="media-body align-self-center">
                                <div class="text-right">
                                    <h4 class="font-20 my-0 font-weight-bold">
                                        <span data-plugin="counterup" id="valor4">
                                        </span>
                                    </h4>
                                    <p class="mb-0 mt-1 text-truncate">Docentes sin Título</p>
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
                        <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                            <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11"></h3>
                        </div>
                        <div class="card-body p-0">
                            <figure class="highcharts-figure m-0">
                                <div id="dsanal0" style="height: 20rem"></div>
                            </figure>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card card-border border border-plomo-0">
                        <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                            <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11"></h3>
                        </div>
                        <div class="card-body p-0">
                            <figure class="highcharts-figure m-0">
                                <div id="dsanal1" style="height: 20rem"></div>
                            </figure>

                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card card-border border border-plomo-0">
                        <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                            <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11"></h3>
                        </div>
                        <div class="card-body p-0">
                            <figure class="highcharts-figure m-0">
                                <div id="dsanal2" style="height: 20rem"></div>
                            </figure>

                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card card-border border border-plomo-0">
                        <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                            <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11"></h3>
                        </div>
                        <div class="card-body p-0">
                            <figure class="highcharts-figure m-0">
                                <div id="dsanal3" style="height: 20rem"></div>
                            </figure>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-border border border-plomo-0">
                        <div class="card-header border-success-0 bg-transparent pb-0">
                            <div class="card-widgets">
                                <button type="button" class="btn btn-success btn-xs" onclick="descargar2()"><i
                                        class="fa fa-file-excel"></i> Descargar</button>
                            </div>
                            <h3 class="card-title">Número de personal docente con título pedagógico en educación
                                Secundaria,
                                según ugel
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


            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-border border border-plomo-0">
                        <div class="card-header border-success-0 bg-transparent pb-0">
                            <div class="card-widgets">
                                <button type="button" class="btn btn-success btn-xs" onclick="descargar1()"><i
                                        class="fa fa-file-excel"></i> Descargar</button>
                            </div>
                            <h3 class="card-title">Docente con Título en Educación Secundaria, Según Instituciones
                                Educativas</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive" id="ctabla1">
                                    </div>

                                </div>
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
        $(document).ready(function() {
            Highcharts.setOptions({
                lang: {
                    //decimalPoint: '.',
                    thousandsSep: ','
                }
            });
            cargarCards();
        });

        function cargarCards() {
            $.ajax({
                url: "{{ route('panelcontrol.educacion.indicador.nuevos.04.head') }}",
                data: {
                    "anio": $('#anio').val(),
                    "provincia": $('#provincia').val(),
                    "distrito": $('#distrito').val(),
                    "tipogestion": $('#tipogestion').val(),
                },
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#valor1').text(data.valor1 + "%");
                    $('#valor2').text(data.valor2);
                    $('#valor3').text(data.valor3);
                    $('#valor4').text(data.valor4);
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
            panelGraficas('dsanal0');
            panelGraficas('dsanal1');
            panelGraficas('dsanal2');
            panelGraficas('dsanal3');
            panelGraficas('ctabla1');
            panelGraficas('ctabla2');
        }

        function printer() {
            window.print();
            // var escalaPersonalizada = 0.6; // Cambia esto al valor de escala deseado
            // var style = document.createElement('style');
            // style.type = 'text/css';
            // style.media = 'print';
            // // style.innerHTML = '@page { size: auto; margin: 0mm; transform: scale(' + escalaPersonalizada +
            // //     '); } @media print { body { transform: scale(' + escalaPersonalizada + '); } }';
            // style.innerHTML = '@page { transform: scale(' + escalaPersonalizada +
            //     '); } @media print { body { transform: scale(' + escalaPersonalizada + '); } }';
            // document.head.appendChild(style);
            // window.print();
        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('panelcontrol.educacion.indicador.nuevos.04.tabla') }}",
                data: {
                    'div': div,
                    "anio": $('#anio').val(),
                    "provincia": $('#provincia').val(),
                    "distrito": $('#distrito').val(),
                    "tipogestion": $('#tipogestion').val(),
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    $('#' + div).html(
                        '<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    if (div == "dsanal0") {
                        gAnidadaColumn(div,
                            data.info.categoria,
                            data.info.series,
                            '',
                            'Porcentaje de Docentes Titulados en Educación Secundaria',
                            data.info.maxbar
                        );
                    } else if (div == "dsanal1") {
                        gPie(div, data.puntos, '',
                            'Docentes con Título Pedagógico en Educación Secundaria, según Sexo', '');
                    } else if (div == "dsanal2") {
                        gPie(div, data.puntos, '',
                            'Docentes con Título Pedagógico en Educación Secundaria, según Condición Laboral',
                            '');
                    } else if (div == "dsanal3") {
                        gPie(div, data.puntos, '',
                            'Docentes con Título Pedagógico en Educación Secundaria, según Ámbito Geográfico',
                            '');
                    } else if (div == "ctabla1") {
                        $('#ctabla1').html(data.excel);
                        $('#tabla1').DataTable({
                            responsive: true,
                            autoWidth: false,
                            ordered: true,
                            language: table_language,
                        });
                    } else if (div == "ctabla2") {
                        $('#ctabla2').html(data.excel);
                        $('#tabla2').DataTable({
                            responsive: true,
                            autoWidth: false,
                            ordered: true,
                            searching: false,
                            bPaginate: false,
                            info: false,
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

        function cargarDistritos() {
            $.ajax({
                url: "{{ route('plaza.cargardistritos', '') }}/" + $('#provincia').val(),
                type: 'GET',
                success: function(data) {
                    $("#distrito option").remove();
                    var options = '<option value="0">DISTRITO</option>';
                    $.each(data.distritos, function(index, value) {
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
            window.open("{{ url('/') }}/INDICADOR/Home/04/Excel/" + $('#anio').val() + "/" + $('#provincia')
                .val() + "/" + $('#distrito').val() + "/" + $('#tipogestion').val());
        }

        function descargar2() {
            window.open("{{ url('/') }}/INDICADOR/Home/04/Excel/tabla2/" + $('#anio').val() + "/" + $('#provincia')
                .val() + "/" + $('#distrito').val() + "/" + $('#tipogestion').val());
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
                        fontSize: "11px",
                        //fontWeight: 'bold',
                        //color: 'white'
                    },
                },
                tooltip: {
                    //pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>',
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>',
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        colors,
                        dataLabels: {
                            enabled: true,
                            //format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            //format: '{point.percentage:.1f}% ({point.y})',
                            format: '{point.y:,0f} ( {point.percentage:.1f}% )',
                            connectorColor: 'silver'
                        }
                    }
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
                    min: 0,
                },
                xAxis: {
                    categories: data.cat,
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
                        },
                        /* label: {
                            connectorAllowed: false
                        },
                        pointStart: 2010 */
                    }
                },
                series: [{
                    name: 'Matriculados',
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
                        fontSize: '11px',
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
                        /* labels: {
                            //format: '{value}°C',
                            //style: {
                            //    color: Highcharts.getOptions().colors[2]
                            //}
                        }, */
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
                            formatter: function() {
                                if (this.colorIndex == 2)
                                    return this.y + " %";
                                else
                                    return Highcharts.numberFormat(this.y, 0);
                            },
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
