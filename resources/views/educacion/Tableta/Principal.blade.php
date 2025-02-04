@extends('layouts.main', ['activePage' => 'importacion', 'titlePage' => ''])

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
    <style>
        .tablex thead th {
            padding: 6px;
            text-align: center;
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
            <div class="form-group row align-items-center vh-5">
                <div class="col-lg-7 col-md-4 col-sm-4">
                    <h4 class="page-title font-16">DISTRIBUCIÓN DE TABLETAS</h4>
                </div>
                <div class="col-lg-1 col-md-2 col-sm-2">
                    <select id="anio" name="anio" class="form-control font-11"
                        onchange="cargarDistritos(),cargarCards();">
                        <option value="0">AÑO</option>
                        @foreach ($anios as $item)
                            <option value="{{ $item->id }}" {{ $item->anio == $maxAnio ? 'selected' : '' }}>
                                {{ $item->anio }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <select id="provincia" name="provincia" class="form-control font-11"
                        onchange="cargarDistritos(),cargarCards();">
                        <option value="0">PROVINCIA</option>
                        @foreach ($provincias as $item)
                            <option value="{{ $item->id }}"> {{ $item->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <select id="distrito" name="distrito" class="form-control font-11" onchange="cargarCards();">
                        <option value="0">DISTRITO</option>
                        @foreach ($distritos as $item)
                            <option value="{{ $item->id }}">{{ $item->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>



            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card-box border border-plomo-0">
                        <div class="media">
                            <div class="text-center">
                                <img src="{{ asset('/') }}public/img/icon/tableta32px.png" alt="" class=""
                                    width="100%" height="100%">
                            </div>
                            <div class="media-body align-self-center">
                                <div class="text-right">
                                    <h4 class="font-20 my-0 font-weight-bold">
                                        <span data-plugin="counterup" id="bono"></span>
                                    </h4>
                                    <p class="mb-0 mt-1 text-truncate">Tabletas Programadas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card-box border border-plomo-0">
                        <div class="media">
                            <div class="text-center">
                                <img src="{{ asset('/') }}public/img/icon/tableta32px.png" alt="" class=""
                                    width="100%" height="100%">
                            </div>
                            <div class="media-body align-self-center">
                                <div class="text-right">
                                    <h4 class="font-20 my-0 font-weight-bold">
                                        <span data-plugin="counterup" id="dado"></span>
                                    </h4>
                                    <p class="mb-0 mt-1 text-truncate">Tabletas Entregadas</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card-box border border-plomo-0">
                        <div class="media">
                            <div class="text-center">
                                <img src="{{ asset('/') }}public/img/icon/cargador32px.png" alt="" class=""
                                    width="100%" height="100%">
                            </div>
                            <div class="media-body align-self-center">
                                <div class="text-right">
                                    <h4 class="font-20 my-0 font-weight-bold">
                                        <span data-plugin="counterup" id="chip"></span>
                                    </h4>
                                    <p class="mb-0 mt-1 text-truncate">Cargadores Programados</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card-box border border-plomo-0">
                        <div class="media">
                            <div class="text-center">
                                <img src="{{ asset('/') }}public/img/icon/cargador32px.png" alt="" class=""
                                    width="100%" height="100%">
                            </div>
                            <div class="media-body align-self-center">
                                <div class="text-right">
                                    <h4 class="font-20 my-0 font-weight-bold">
                                        <span data-plugin="counterup" id="solar"></span>
                                    </h4>
                                    <p class="mb-0 mt-1 text-truncate">Cargadores Entregados</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-4">
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

                <div class="col-lg-4">
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

                <div class="col-lg-4">
                    <div class="card card-border border border-plomo-0">
                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                            <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11"></h3>
                        </div>
                        <div class="card-body p-0">
                            <figure class="highcharts-figure p-0">
                                <div id="anal3" style="height: 20rem"></div>
                            </figure>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        {{-- <div class="card-header">
                            <h3 class="card-title">Bordered Table</h3>
                        </div> --}}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive" id="ctabla1">
                                        {{-- <table class="table table-bordered mb-0">
                                            <thead>
                                                <tr class="table-secondary">
                                                    <th>UGEL</th>
                                                    <th colspan="3">TABLETAS</th>
                                                    <th colspan="3">TABLETAS CON CHIP</th>
                                                    <th colspan="3">CARGADORES SOLARES</th>
                                                </tr>
                                                <tr class="table-secondary">
                                                    <th></th>
                                                    <th>RECIBIDO</th>
                                                    <th>ENTREGADO</th>
                                                    <th>AVANCE</th>
                                                    <th>RECIBIDO</th>
                                                    <th>ENTREGADO</th>
                                                    <th>AVANCE</th>
                                                    <th>RECIBIDO</th>
                                                    <th>ENTREGADO</th>
                                                    <th>AVANCE</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>UGEL ATALAYA</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>UGEL CORONEL PORTILLA</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>UGEL PADREA ABAD</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>UGEL PURUS</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr class="table-secondary">
                                                    <td>TOTAL</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table> --}}
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
                            <h3 class="card-title">Bordered Table</h3>
                        </div> --}}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive" id="ctabla2">
                                        <table class="table table-bordered mb-0">
                                            {{-- <thead>
                                                <tr class="table-secondary">
                                                    <th>CODIGO MODULAR </th>
                                                    <th>INSTITUCIÓN EDUCATIVA</th>
                                                    <th colspan="3">TABLETAS</th>
                                                    <th colspan="3">TABLETAS CON CHIP</th>
                                                    <th colspan="3">CARGADORES SOLARES</th>
                                                </tr>
                                                <tr class="table-secondary">
                                                    <th></th>
                                                    <th></th>
                                                    <th>RECIBIDO</th>
                                                    <th>ENTREGADO</th>
                                                    <th>AVANCE</th>
                                                    <th>RECIBIDO</th>
                                                    <th>ENTREGADO</th>
                                                    <th>AVANCE</th>
                                                    <th>RECIBIDO</th>
                                                    <th>ENTREGADO</th>
                                                    <th>AVANCE</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>

                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>

                                                <tr class="table-secondary">
                                                    <td>TOTAL</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody> --}}
                                        </table>
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
        var paleta_colores = ['#5eb9aa', '#F9FFFE', '#f5bd22', '#058DC7', '#50B432', '#9D561B', '#DDDF00', '#24CBE5',
            '#64E572', '#9F9655', '#FFF263', '#6AF9C4'
        ];
        $(document).ready(function() {
            cargarCards();

        });

        function cargarCards() {
            $.ajax({
                url: "{{ route('tableta.principal.head') }}",
                data: {
                    "anio": $('#anio').val(),
                    "provincia": $('#provincia').val(),
                    "distrito": $('#distrito').val(),
                    "area": $('#area').val(),
                },
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#bono').text(data.valor1);
                    $('#dado').text(data.valor2);
                    $('#chip').text(data.valor3);
                    $('#solar').text(data.valor4);

                    panelGraficas('anal1');
                    panelGraficas('anal2');
                    panelGraficas('anal3');
                    panelGraficas('tabla1');
                    panelGraficas('tabla2');
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

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('tableta.principal.tabla') }}",
                data: {
                    'div': div,
                    "anio": $('#anio').val(),
                    "provincia": $('#provincia').val(),
                    "distrito": $('#distrito').val(),
                    "area": 0,
                },
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    if (div == "anal1") {
                        gAnidadaColumn(div,
                            data.info.categoria,
                            data.info.series,
                            '',
                            'Distribución de Tabletas y Cargadores Solares, según Nivel Educativo'
                        );
                    }
                    if (div == "anal2") {
                        gAnidadaColumn(div,
                            data.info.categoria,
                            data.info.series,
                            '',
                            'Distribución de Tabletas y Cargadores Solares, según Ámbito Geográfico'
                        );
                    }
                    if (div == "anal3") {
                        gAnidadaColumn(div,
                            data.info.categoria,
                            data.info.series,
                            '',
                            'Distribución de Tabletas y Cargadores Solares, según Beneficiarios'
                        );
                    }
                    if (div == "tabla1") {
                        $('#ctabla1').html(data.excel);
                    }
                    if (div == "tabla2") {
                        $('#ctabla2').html(data.excel);
                        $('#tabla2').DataTable({
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
                    enabled: false,
                    //text: subtitulo,
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
                        dataLabels: {
                            enabled: true,
                            //format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            format: '{point.y:,0f} ( {point.percentage:.1f}% )',
                            connectorColor: 'silver'
                        }
                    }
                },
                /* plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            //format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            format: '{point.percentage:.1f}% ({point.y})',
                            connectorColor: 'silver'
                        }
                    }
                }, */
                series: [{
                    showInLegend: true,
                    //name: 'Share',
                    data: datos,
                }],
                exporting: {
                    enabled: false
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

        function gsemidona(div, valor, colors) {
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
                        colors: colors,
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

        function gAnidadaColumn(div, categoria, series, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    zoomType: 'xy',
                },
                title: {
                    text: titulo, //'Browser market shares in January, 2018'
                },
                subtitle: {
                    text: subtitulo,
                },
                xAxis: [{
                    categories: categoria,
                    crosshair: true
                }],
                yAxis: [{ // Primary yAxis
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
                    }, { // Secondary yAxis
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
