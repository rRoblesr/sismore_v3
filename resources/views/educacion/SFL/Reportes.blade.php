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
                        {{-- <button type="button" class="btn btn-danger btn-xs"
                            onclick="window.location.href=`{{ route('salud.padronnominal.tablerocalidad.consulta', ['anio' => 'anio', 'mes' => 'mes']) }}`.replace('anio',$('#anio').val()).replace('mes',$('#mes').val())">
                            <i class="fas fa-search"></i> Consultas</button> --}}
                        <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()">
                            <i class="fa fa-redo"></i> Actualizar</button>
                    </div>
                    <h3 class="card-title text-white font-14">Tablero de control del saneamiento físico legal de locales
                        escolares públicos</h3>
                </div>
                <div class="card-body pb-0">
                    <div class="form-group row align-items-center vh-5">
                        <div class="col-lg-5 col-md-4 col-sm-4">
                            <h4 class="page-title font-12">Fuente: DRUE {{ date('Y') }}</h4>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="ugel">UGEL</label>
                                <select id="ugel" name="ugel" class="form-control font-11">
                                    <option value="0">TODOS</option>
                                    {{-- @foreach ($ugel as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->nombre }}</option>
                                    @endforeach --}}
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="modalidad">MODALIDAD</label>
                                <select id="modalidad" name="modalidad" class="form-control font-11">
                                    <option value="0">TODOS</option>
                                    {{-- @foreach ($modalidad as $item)
                                        <option value="{{ $item->tipo }}"> {{ $item->ntipo }}</option>
                                    @endforeach --}}
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="nivel">NIVEL EDUCATIVO</label>
                                <select id="nivel" name="nivel" class="form-control font-11">
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
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card-box border border-plomo-0">
                <div class="media">
                    {{-- <div class="text-center">
                        <img src="{{ asset('/') }}public/img/icon/tableta32px.png" alt="" class=""
                            width="100%" height="100%">
                    </div> --}}
                    <div class="avatar-md mr-2">
                        <i class="mdi mdi-city avatar-title font-30 text-dark"></i>

                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="card1"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">Instituciones Educativas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card-box border border-plomo-0">
                <div class="media">
                    {{-- <div class="text-center">
                        <img src="{{ asset('/') }}public/img/icon/tableta32px.png" alt="" class=""
                            width="100%" height="100%">
                    </div> --}}
                    <div class="avatar-md mr-2">
                        <i class="mdi mdi-city avatar-title font-30 text-dark"></i>

                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="card2"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">Locales escolares </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card-box border border-plomo-0">
                <div class="media">
                    {{-- <div class="text-center">
                        <img src="{{ asset('/') }}public/img/icon/cargador32px.png" alt="" class=""
                            width="100%" height="100%">
                    </div> --}}
                    <div class="avatar-md mr-2">
                        <i class="mdi mdi-thumb-up avatar-title font-30 text-dark"></i>

                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="card3"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">L.E saneados </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card-box border border-plomo-0">
                <div class="media">
                    {{-- <div class="text-center">
                        <img src="{{ asset('/') }}public/img/icon/cargador32px.png" alt="" class=""
                            width="100%" height="100%">
                    </div> --}}
                    <div class="avatar-md mr-2">
                        <i class="mdi mdi-thumb-down avatar-title font-30 text-dark"></i>

                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="card4"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">L.E no saneados</p>
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
                    <div id="anal3" style="height: 25rem"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                    <h3 class="text-black text-center font-weight-normal font-11 m-0"></h3>
                </div>
                <div class="card-body p-0">
                    <div id="anal1" style="height: 25rem"></div>
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
                    <div id="anal2" style="height: 25rem"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                    <h3 class="text-black text-center font-weight-normal font-11 m-0"></h3>
                </div>
                <div class="card-body p-0">
                    <div id="anal4" style="height: 25rem"></div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-12">
            {{-- <div class="card">
                <div class="card-header"> --}}
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-success-0 btn-xs" onclick="descargarExcel('tabla1')">
                            <i class="fa fa-file-excel"></i> Descargar</button>
                    </div>
                    <h3 class="card-title">Locales escolares públicos por ugel, según estados del saneamiento físico legal
                    </h3>
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

    <div class="row">
        <div class="col-lg-12">
            {{-- <div class="card">
                <div class="card-header"> --}}
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-success-0 btn-xs" onclick="descargarExcel('tabla2')">
                            <i class="fa fa-file-excel"></i> Descargar</button>
                    </div>
                    <h3 class="card-title">Instituciones educativas y locales educativos públicos por distritos, según
                        estados del saneamiento físico legal
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
            {{-- <div class="card">
                <div class="card-header"> --}}
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-success-0 btn-xs" onclick="descargarExcel('tabla3')">
                            <i class="fa fa-file-excel"></i> Descargar</button>
                    </div>
                    <h3 class="card-title">Instituciones educativas públicas, según
                        estados del saneamiento físico legal
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" id="ctabla3">

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
        var ubigeo_select = '';
        var anal3;
        var anal3valores = [];
        const spinners = {
            head: ['#card1', '#card2', '#card3', '#card4'],
            anal: ['#anal1', '#anal2', '#anal3', '#anal4'],
            tabla: ['#ctabla1', '#ctabla2', '#ctabla3']
        };
        $(document).ready(function() {
            Object.keys(spinners).forEach(key => {
                SpinnerManager.show(key);
            });
            $('#ugel').on('change', function() {
                cargarModalidad();
            });
            $('#modalidad').on('change', function() {
                cargarNivel();
            });
            $('#nivel').on('change', function() {
                cargarCards();
            });
            mapData = otros;
            mapData.features.forEach((element, key) => {
                console.log('["' + element.properties['hc-key'] + '", ' + (key + 1) + '],');
            });
            cargarUgel();

        });

        function cargarCards() {
            panelGraficas('head');
            panelGraficas('anal1');
            panelGraficas('anal2');
            panelGraficas('anal3');
            panelGraficas('anal4');
            panelGraficas('tabla1');
            panelGraficas('tabla2');
            panelGraficas('tabla3');
        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('educacion.sfl.reportes.reporte') }}",
                data: {
                    'div': div,
                    "ugel": $('#ugel').val(),
                    "modalidad": $('#modalidad').val(),
                    "nivel": $('#nivel').val(),
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    SpinnerManager.show(div);
                },
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
                            gAnidadaColumn(div, data.info.categoria, data.info.series, '',
                                'Locales Escolares Públicos por Provincia, según estado del SFL');

                            break;
                        case 'anal2':
                            gPie(div, data.info, '', 'Locales Escolares Públicos, según estado del SFL', '');
                            break;
                        case 'anal3':
                            // datax = [
                            //     [
                            //         "pe-uc-cp",
                            //         482799
                            //     ],
                            //     [
                            //         "pe-uc-at",
                            //         65674
                            //     ],
                            //     [
                            //         "pe-uc-pa",
                            //         89058
                            //     ],
                            //     [
                            //         "pe-uc-pr",
                            //         3603
                            //     ]
                            // ]
                            anal3valores = data.valores;
                            anal3 = maps01(div, data.info, '',
                                'Avance de saneamiento físico legal, según provincia');
                            break;
                        case 'anal4':
                            gAnidadaColumn(div, data.info.categoria, data.info.series, '',
                                'Locales Escolares Públicos por Provincia, según estado del SFL');
                            break;
                        case 'tabla1':
                            $('#ctabla1').html(data.excel);
                            break;
                        case 'tabla2':
                            $('#ctabla2').html(data.excel);
                            $('#tabla2').DataTable({
                                responsive: true,
                                autoWidth: false,
                                ordered: true,
                                paging: false,
                                searching: false,
                                info: false,
                                language: table_language,
                            });
                            break;
                        case 'tabla3':
                            $('#ctabla3').html(data.excel);
                            $('#tabla3').DataTable({
                                responsive: true,
                                autoWidth: false,
                                ordered: true,
                                language: table_language,
                            });
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

        function cargarUgel() {
            $.ajax({
                url: "{{ route('cubopacto2.sfl.ugel') }}",
                type: 'GET',
                success: function(data) {
                    $("#ugel option").remove();
                    var options = '<option value="0">TODOS</option>';
                    $.each(data, function(index, vv) {
                        options += `<option value='${vv.id}'>${vv.nombre}</option>`;
                    });
                    $("#ugel").append(options);
                    cargarModalidad();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarModalidad() {
            $.ajax({
                url: "{{ route('cubopacto2.sfl.modalidad', ['ugel' => ':ugel']) }}"
                    .replace(':ugel', $('#ugel').val()),
                type: 'GET',
                success: function(data) {
                    $('#modalidad').empty();
                    if (Object.keys(data).length > 1) $('#modalidad').append(
                        '<option value="0">TODOS</option>');
                    $.each(data, function(index, value) {
                        $('#modalidad').append(`<option value='${index}'>${value}</option>`);
                    });
                    cargarNivel()
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarNivel() {
            $.ajax({
                url: "{{ route('cubopacto2.sfl.nivel', ['ugel' => ':ugel', 'modalidad' => ':modalidad']) }}"
                    .replace(':ugel', $('#ugel').val())
                    .replace(':modalidad', $('#modalidad').val()),
                type: 'GET',
                success: function(data) {
                    $("#nivel").empty();
                    $('#nivel').append('<option value="0">TODOS</option>');
                    $.each(data, function(index, value) {
                        $('#nivel').append(`<option value='${index}'>${value}</option>`);
                    });
                    cargarCards();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function abrirmodalcentropoblado(ubigeo) {
            ubigeo_select = ubigeo;
            // console.log(ubigeo_select)]
            panelGraficas('tabla3_1');

            $('#modal-centropoblado').modal('show');

        }

        function abrirmodalconsultas() {
            $('#modal-consulta').modal('show');
        }

        function descargarExcel(div) {
            window.open(
                "{{ route('educacion.sfl.reportes.download.excel', ['div' => ':div', 'ugel' => ':ugel', 'modalidad' => ':modalidad', 'nivel' => ':nivel']) }}"
                .replace(':div', div)
                .replace(':ugel', $('#ugel').val())
                .replace(':modalidad', $('#modalidad').val())
                .replace(':nivel', $('#nivel').val())
            );
        }

        function gAnidadaColumn(div, categoria, series, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    zoomType: 'xy',
                    type: 'column'
                },
                title: {
                    text: titulo,
                    //'Browser market shares in January, 2018'
                },
                subtitle: {
                    text: subtitulo,
                },
                xAxis: [{
                    categories: categoria,
                    crosshair: true
                }],
                yAxis: [{
                        // Primary yAxis
                        //max: 2000000000,
                        labels: {
                            enabled: false,
                        },
                        title: {
                            enabled: false,
                        },
                        /* labels: {
                            format: '{value}°C',
                            style: {
                                color: Highcharts.getOptions().colors[2]
                            }
                        },
                        title: {
                            text: 'Temperature',
                            style: {
                                color: Highcharts.getOptions().colors[2]
                            }
                        }, */
                        //opposite: true,
                    }, {
                        // Secondary yAxis
                        gridLineWidth: 0,
                        labels: {
                            enabled: false,
                        },
                        title: {
                            enabled: false,
                        },
                        /* title: {
                            text: 'Rainfall',
                            style: {
                                color: Highcharts.getOptions().colors[0]
                            }
                        },
                        labels: {
                            format: '{value} mm',
                            style: {
                                color: Highcharts.getOptions().colors[0]
                            }
                        }, */
                        min: -200,
                        max: 150,
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
                        //showInLegend: false,
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            //format: '{point.y:.1f}%',
                            /* formatter: function() {
                                    if (this.y > 1000000) {
                                        return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                                    } else if (this.y > 1000) {
                                        return Highcharts.numberFormat(this.y / 1000, 0) + "K";
                                    } else if (this.y < 101) {
                                        return this.y + "%";
                                    } else {
                                        return this.y;
                                    }
                                }, */
                            style: {
                                fontWeight: 'normal',
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
                    enabled: false
                },
                credits: false,
            });
        }

        function gPie(div, datos, titulo, subtitulo, tituloserie) {
            const colors = ["#5eb9aa", "#e65310", "#f5bd22"];
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
                            // distance: -20,
                            //format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            //format: '{point.percentage:.1f}% ({point.y})',
                            format: '{point.y:,0f} ( {point.percentage:.1f}% )',
                            // format: '{point.percentage:.1f}%',
                            connectorColor: 'silver'
                        }
                    }
                },
                series: [{
                    innerSize: '50%',
                    showInLegend: true,
                    // name: 'Share',
                    data: datos,
                }],
                legend: {
                    itemStyle: {
                        //color: "#333333",
                        cursor: "pointer",
                        fontSize: "10px",
                        fontWeight: "normal",
                        textOverflow: "ellipsis"
                    },
                },
                exporting: {
                    enabled: true
                },
                credits: false,
            });
        }

        function maps01(div, data, titulo, subtitulo) {
            return Highcharts.mapChart(div, {
                chart: {
                    map: mapData
                },
                // states: {
                //     hover: {
                //         color: '#ff5733' // Cambia este color al que desees
                //     }
                // },
                title: {
                    text: titulo, //'Reportes de Mapa'
                },

                subtitle: {
                    text: subtitulo, //'Un descripción de reportes'
                    style: {
                        // fontSize: '11px'
                    }
                },

                mapNavigation: {
                    enabled: true,
                    buttonOptions: {
                        verticalAlign: 'top'
                    }
                },

                colorAxis: {
                    // mixColor: "#e6ebf5",
                    // manColor: "#003399",
                    minColor: '#e0f6f3',
                    maxColor: '#2a7f72',
                    showInLegend: false
                },

                series: [{
                    data: data,
                    name: 'SFL',
                    states: {
                        hover: {
                            color: '#ef5350' // '#BADA55'
                        }
                    },
                    borderColor: '#cac9c9',

                    dataLabels: {
                        enabled: true,
                        useHTML: true, // Permite el uso de etiquetas HTML
                        format: '<div style="text-align:center;">{point.name}<br><span style="font-size:12px;">{point.value:,2f}%</span></div>',
                        // nullFormatter: '0%',
                        // formatter: function() {
                        //     const value = this.point.value || 0;
                        //     return `<div style="text-align:center;">${this.point.name}<br><span style="font-size:12px;">${Highcharts.numberFormat(value, 2)}%</span></div>`;
                        // },
                        style: {
                            fontSize: '10px',
                            fontWeight: 'bold',
                            color: '#FFFFFF',
                            textShadow: '0px 0px 3px #000000' // Aplica sombra negra para simular el borde
                        }
                    },

                }],
                tooltip: {
                    useHTML: true,
                    formatter: function() {
                        // Obtener los datos desde anal3valores
                        const provinciaData = anal3valores[this.point.properties['hc-key']];
                        if (!provinciaData) {
                            return `<strong>${this.point.name}</strong><br>Datos no disponibles`;
                        }

                        return `<div style="text-align:left;">
                                    <strong>${this.point.name}</strong><br>
                                    Numerador: ${provinciaData.num} Locales<br>
                                    Denominador: ${provinciaData.dem} Locales<br>
                                    Avance: ${Highcharts.numberFormat(provinciaData.ind, 2)}%
                                </div>`;
                    },
                    backgroundColor: '#fff', // Fondo blanco translúcido
                    borderColor: '#cccccc', // Borde suave
                    borderRadius: 10, // Bordes redondeados
                    shadow: true, // Sombra para darle un efecto "suave"
                    style: {
                        fontSize: '12px',
                        fontWeight: 'normal',
                        color: '#333333',
                        padding: '10px'
                    }
                },
                legend: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },
            });
        }

        function anal3valores(provincia) {
            return anal3valores[provincia] || null;
        }
    </script>

    {{-- jrmt-mapero --}}
    <script src="https://code.highcharts.com/maps/highmaps.js"></script>
    <script src="https://code.highcharts.com/maps/modules/exporting.js"></script>

    <script src="{{ asset('/') }}public/us-ct-ally.js"></script>
    <script src="{{ asset('/') }}public/us-ct-allz.js"></script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
    <!-- optional -->
    <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>

    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
@endsection
