@section('css')
    <style>
        .link {
            color: #000000;
        }

        .link:hover {
            color: #0000FF;
        }
    </style>
@endsection

<div>
    <div id="container-speed" class="chart-container"></div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="form-group row align-items-center vh-5">
            <div class="col-lg-4 col-md-4 col-sm-4">
                <h4 class="page-title font-16">MODULO SALUD</h4>
            </div>
            {{-- <div class="col-lg-2 col-md-2 col-sm-2">
                <select id="provincia" name="provincia" class="form-control btn-xs font-11"
                    onchange="cargarDistritos(),cargarCards();">
                    <option value="0">PROVINCIA</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
                <select id="distrito" name="distrito" class="form-control btn-xs font-11" onchange="cargarCards();">
                    <option value="0">DISTRITO</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
                <select id="tipogestion" name="tipogestion" class="form-control btn-xs font-11"
                    onchange="cargarCards();">
                    <option value="0">TIPO DE GESTIÓN</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
                <select id="ambito" name="ambito" class="form-control btn-xs font-11" onchange="cargarCards();">
                    <option value="0">ÁMBITO</option>
                </select>
            </div> --}}
        </div>

        <!--Widget-4 -->

        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card-box border border-plomo-0">
                    <div class="media">
                        <div class="text-center">
                            {{-- <i class="ion ion-logo-usd avatar-title font-26 text-white"></i> --}}
                            <img src="{{ asset('/') }}public/img/icon/servicios.png" alt="" class=""
                                width="70%" height="70%">
                        </div>
                        {{-- <div class="avatar-md bg-success rounded-circle mr-2">
                                <i class=" ion-md-home avatar-title font-26 text-white"></i>
                            </div> --}}
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold">
                                    <span data-plugin="counterup" id="servicios">308</span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">Establecimiento de Salud</p>
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
                            {{-- <i class="ion ion-logo-usd avatar-title font-26 text-white"></i> --}}
                            <img src="{{ asset('/') }}public/img/icon/locales.png" alt="" class=""
                                width="70%" height="70%">
                        </div>
                        {{-- <div class="avatar-md bg-info rounded-circle mr-2">
                                <i class=" ion ion-md-person avatar-title font-26 text-white"></i>
                            </div> --}}
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold">
                                    <span data-plugin="counterup" id="locales">3,674</span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">Personal de Salud </p>
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
                            {{-- <i class="ion ion-logo-usd avatar-title font-26 text-white"></i> --}}
                            <img src="{{ asset('/') }}public/img/icon/matriculas.png" alt="" class=""
                                width="70%" height="70%">
                        </div>
                        {{-- <div class="avatar-md bg-info rounded-circle mr-2">
                                <i class=" ion ion-md-person avatar-title font-26 text-white"></i>
                            </div> --}}
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold">
                                    <span data-plugin="counterup" id="matriculados">506</span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">Medicos</p>
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
                            {{-- <i class="ion ion-logo-usd avatar-title font-26 text-white"></i> --}}
                            <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                                width="70%" height="70%">
                        </div>
                        {{-- <div class="avatar-md bg-info rounded-circle mr-2">
                                <i class=" ion ion-md-person avatar-title font-26 text-white"></i>
                            </div> --}}
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold">
                                    <span data-plugin="counterup" id="docentes">607,284</span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">Asegurados al SIS</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-6">
                <div class="card card-border border border-plomo-0">
                    <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                        <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11"></h3>
                    </div>
                    <div class="card-body p-0">
                        <figure class="highcharts-figure p-0">
                            <div id="anal1" style="height: 20rem"></div>
                        </figure>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card card-border border border-plomo-0">
                    <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                        <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11"></h3>
                    </div>
                    <div class="card-body p-0">
                        <figure class="highcharts-figure p-0">
                            <div id="anal2" style="height: 20rem"></div>
                        </figure>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div id="modal_datosindicador" class="modal fade font-10" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-12" id="myModalLabel">Datos del indicador</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form action="" id="form_datosindicador" name="form" class="form-horizontal"
                    autocomplete="off">
                    @csrf
                    <input type="hidden" id="indicador" name="indicador" value="">
                    <div class="form-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Indicador</label>
                                    <textarea class="form-control" name="indicadornombre" id="indicadornombre" cols="30" rows="2"
                                        placeholder="Definición del indicador"></textarea>
                                    {{-- <input id="indicadornombre" name="indicadornombre" class="form-control"
                                        type="text" placeholder="Nombre del indicador"> --}}
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Definición</label>
                                    <textarea class="form-control" name="indicadordescripcion" id="indicadordescripcion" cols="30" rows="5"
                                        placeholder="Definición del indicador"></textarea>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Instrumento de gestion</label>
                                    <input id="indicadorinstrumento" name="indicadorinstrumento" class="form-control"
                                        type="text" placeholder="Fuente de datos">
                                    <span class="help-block"></span>
                                </div>
                                <div class="col-md-6">
                                    <label>Tipo de indicador</label>
                                    <input id="indicadortipo" name="indicadortipo" class="form-control"
                                        type="text" placeholder="Fuente de datos">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Fuente de datos</label>
                                    <input id="indicadorfuentedato" name="indicadorfuentedato" class="form-control"
                                        type="text" placeholder="Fuente de datos">
                                    <span class="help-block"></span>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-xs btn-danger waves-effect" data-dismiss="modal">Cerrar</button> --}}
                {{-- <button type="button" class="btn btn-primary btn-xs waves-effect waves-light" onclick="verpdf(8)">Ficha Tecnica</button> --}}
            </div>
        </div>
    </div>
</div>

@section('js')
    <script type="text/javascript">
        var paleta_colores = ['#5eb9aa', '#F9FFFE', '#f5bd22', '#058DC7', '#50B432', '#9D561B', '#DDDF00', '#24CBE5',
            '#64E572', '#9F9655', '#FFF263', '#6AF9C4'
        ];
        $(document).ready(function() {
            Highcharts.setOptions({
                lang: {
                    thousandsSep: ","
                }
            });
            // cargarCards();
            var data = {
                'cat': ['2014', '2015', '2016', '2017', '2018', '2019', '2020', '2021', '2022', '2023', ],
                'dat': [0.596, 0.543, 0.571, 0.591, 0.564, 0.537, 0.572, 0.60809847527, 0.6575241822, 0.594, ]
            };
            gLineaBasica('anal1', data, '',
                'Porcentaje de niñas y niños menores de cinco años de edad con desnutricion cronica(PATRON OMS)',
                '');
            var data = {
                'cat': ['2014', '2015', '2016', '2017', '2018', '2019', '2020', '2021', '2022', '2023', ],
                'dat': [0.261, 0.24, 0.248, 0.194, 0.178, 0.177, 0.174, 0.17541049234, 0.196901, 0.191, ]
            };
            // var dataInPercentage = data['dat'].map(function(value) {
            //     return value * 100;
            // });
            // // console.log(dataInPercentage);
            gLineaBasica('anal2', data, '',
                'Porcentaje de niñas y niños entre 6 a 35 meses  de edad con anemia',
                '');

        });

        function cargarCards() {
            $.ajax({
                url: "{{ route('panelcontrol.educacion.head') }}",
                data: {
                    "provincia": $('#provincia').val(),
                    "distrito": $('#distrito').val(),
                    "tipogestion": $('#tipogestion').val(),
                    "ambito": $('#ambito').val(),
                },
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#basico').text(data.valor1);
                    $('#ebr').text(data.valor2);
                    $('#ebe').text(data.valor3);
                    $('#eba').text(data.valor4);
                    $('#ibasico').text(data.ind1 + '%');
                    $('#iebr').text(data.ind2 + '%');
                    $('#iebe').text(data.ind3 + '%');
                    $('#ieba').text(data.ind4 + '%');
                    //$('#bbasico').css('width','100px');
                    $('#bbasico').css('width', data.ind1 + '%')
                        .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                        .addClass(data.ind1 > 84 ? 'bg-success-0' : (data.ind1 > 49 ? 'bg-warning-0' :
                            'bg-orange-0'));
                    $('#bebr').css('width', data.ind2 + '%').removeClass(
                            'bg-success-0 bg-orange-0 bg-warning-0')
                        .addClass(data.ind2 > 84 ? 'bg-success-0' : (data.ind2 > 49 ? 'bg-warning-0' :
                            'bg-orange-0'));
                    $('#bebe').css('width', data.ind3 + '%').removeClass(
                            'bg-success-0 bg-orange-0 bg-warning-0')
                        .addClass(data.ind3 > 84 ? 'bg-success-0' : (data.ind3 > 49 ? 'bg-warning-0' :
                            'bg-orange-0'));
                    $('#beba').css('width', data.ind4 + '%').removeClass(
                            'bg-success-0 bg-orange-0 bg-warning-0')
                        .addClass(data.ind4 > 84 ? 'bg-success-0' : (data.ind4 > 49 ? 'bg-warning-0' :
                            'bg-orange-0'));
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });


        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('panelcontrol.educacion.graficas') }}",
                data: {
                    'div': div,
                    "anio": 2024,
                    "provincia": $('#provincia').val(),
                    "distrito": $('#distrito').val(),
                    "tipogestion": $('#tipogestion').val(),
                    "ambito": $('#ambito').val(),
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    if (div == "siagie001") {
                        $('#' + div).html(
                            '<span><i class="fa fa-spinner fa-spin"></i></span>');
                    } else if (div == "censodocente001") {
                        $('#' + div).html(
                            '<span><i class="fa fa-spinner fa-spin"></i></span>');
                    } else {
                        // $('#' + div).html(
                        //     '<span><i class="fa fa-spinner fa-spin"></i></span>');
                    }
                },
                success: function(data) {
                    switch (div) {
                        case "xx":

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
                        // formatter: function() {
                        //     return this.value + '%';
                        // },
                        formatter: function() {
                            return (this.value * 100).toFixed(1) + '%';
                        },
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
                    },
                    line: {
                        dataLabels: {
                            enabled: true,
                            // format: '{y:.2f}%',
                            formatter: function() {
                                return (this.y * 100).toFixed(1) + '%';
                            }
                        }
                    }
                },
                series: [{
                    name: 'Ucayali',
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
                colors: ['#5eb9aa', '#ef5350', '#f5bd22', '#ef5350'],
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
                        title: {
                            enabled: false,
                        },
                        /* labels: {
                            //format: '{value}°C',
                            //style: {
                            //    color: Highcharts.getOptions().colors[2]
                            //}
                        }, */
                        title: {
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
                        min: 0,
                        max: 120,
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
                                if (this.colorIndex == 1)
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

    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script> --}}
@endsection
