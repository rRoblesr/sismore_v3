@extends('layouts.main', ['activePage' => 'usuarios', 'titlePage' => 'Ingreso Presupuestal de la región Ucayali'])

@section('css')
    <style>
        .tablex thead th {
            padding: 4px;
            text-align: center;
        }

        .tablex thead td {
            padding: 4px;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
        }

        .tablex tbody td,
        .tablex tbody th,
        .tablex tfoot td,
        .tablex tfoot th {
            padding: 4px;
        }

        .fuentex {
            font-size: 10px;
            font-weight: bold;
        }
    </style>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="card-box">
                <div class="media">
                    <div class="avatar-md rounded-circle mr-2 centrador">
                        {{-- <i class="ion ion-logo-usd avatar-title font-26 text-white"></i> --}}
                        <img src="{{ asset('/') }}public/img/icon/dinero32.png" alt="" class="imagen">
                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold" title="{{ number_format($card1['pim'], 0) }}">
                                <span data-plugin="counterup">
                                    {{ number_format($card1['pim'], 0) }}
                                </span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate" style="font-size: 14px">Presupuesto Ucayali</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <h6 class="">Ejecución
                        <span class="float-right">{{ number_format($card1['eje'], 1) }}%</span>
                    </h6>
                    <div class="progress progress-sm m-0">
                        <div class="progress-bar {{ $card1['eje'] < 51 ? 'bg-danger' : ($card1['eje'] < 76 ? 'bg-warning' : 'bg-success') }}"
                            role="progressbar" aria-valuenow="{{ $card1['eje'] }}" aria-valuemin="0" aria-valuemax="100"
                            style="width: {{ $card1['eje'] }}%">
                            <span class="sr-only">{{ number_format($card1['eje'], 2) }}% Complete</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--FIN CARD-->

        <div class="col-md-6 col-xl-3">
            <div class="card-box">
                <div class="media">
                    <div class="avatar-md rounded-circle mr-2 centrador">
                        {{-- <i class="ion ion-logo-usd avatar-title font-26 text-white"></i> --}}
                        <img src="{{ asset('/') }}public/img/icon/dinero32.png" alt="" class="imagen">
                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold" title="{{ number_format($card2['pim'], 0) }}">
                                <span data-plugin="counterup">
                                    {{ number_format($card2['pim'], 0) }}
                                </span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate" style="font-size: 14px">Gobierno Nacional</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <h6 class="">Ejecución
                        <span class="float-right">{{ number_format($card2['eje'], 1) }}%</span>
                    </h6>
                    <div class="progress progress-sm m-0">
                        <div class="progress-bar {{ $card2['eje'] < 51 ? 'bg-danger' : ($card2['eje'] < 76 ? 'bg-warning' : 'bg-success') }}"
                            role="progressbar" aria-valuenow="{{ $card2['eje'] }}" aria-valuemin="0" aria-valuemax="100"
                            style="width: {{ $card2['eje'] }}%">
                            <span class="sr-only">{{ $card2['eje'] }}% Complete</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--FIN CARD-->

        <div class="col-md-6 col-xl-3">
            <div class="card-box">
                <div class="media">
                    <div class="avatar-md rounded-circle mr-2 centrador">
                        {{-- <i class="ion ion-logo-usd avatar-title font-26 text-white"></i> --}}
                        <img src="{{ asset('/') }}public/img/icon/dinero32.png" alt="" class="imagen">
                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold" title="{{ number_format($card3['pim'], 0) }}">
                                <span data-plugin="counterup">
                                    {{ number_format($card3['pim'], 0) }}
                                </span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate" style="font-size: 14px">Gobierno Regional</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <h6 class="">Ejecución
                        <span class="float-right">{{ number_format($card3['eje'], 1) }}%</span>
                    </h6>
                    <div class="progress progress-sm m-0">
                        <div class="progress-bar {{ $card3['eje'] < 51 ? 'bg-danger' : ($card3['eje'] < 76 ? 'bg-warning' : 'bg-success') }}"
                            role="progressbar" aria-valuenow="{{ $card3['eje'] }}" aria-valuemin="0" aria-valuemax="100"
                            style="width: {{ $card3['eje'] }}%">
                            <span class="sr-only">{{ $card3['eje'] }}% Complete</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--FIN CARD-->

        <div class="col-md-6 col-xl-3">
            <div class="card-box">
                <div class="media">
                    <div class="avatar-md rounded-circle mr-2 centrador">
                        {{-- <i class="ion ion-logo-usd avatar-title font-26 text-white"></i> --}}
                        <img src="{{ asset('/') }}public/img/icon/dinero32.png" alt="" class="imagen">
                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold" title="{{ number_format($card4['pim'], 0) }}">
                                <span data-plugin="counterup">
                                    {{ number_format($card4['pim'], 0) }}
                                </span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate" style="font-size: 14px">Gobiernos Locales</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <h6 class="">Ejecución
                        <span class="float-right">{{ number_format($card4['eje'], 1) }}%</span>
                    </h6>
                    <div class="progress progress-sm m-0">
                        <div class="progress-bar {{ $card4['eje'] < 51 ? 'bg-danger' : ($card4['eje'] < 76 ? 'bg-warning' : 'bg-success') }}"
                            role="progressbar" aria-valuenow="{{ $card4['eje'] }}" aria-valuemin="0" aria-valuemax="100"
                            style="width: {{ $card4['eje'] }}%">
                            <span class="sr-only">{{ $card4['eje'] }}% Complete</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--FIN CARD-->


    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-xl-6">
            <div class="card card-border">
                <div class="card-header border-success-0 bg-transparent p-0">
                    <h3 class="card-title text-primary "></h3>
                </div>
                <div class="card-body p-0">
                    <div id="anal1"></div>{{--  style="min-width:400px;height:300px;margin:0 auto;" --}}
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card card-border">
                <div class="card-header border-success-0 bg-transparent p-0">
                    <h3 class="card-title text-primary "></h3>
                </div>
                <div class="card-body p-0">
                    <div id="anal2"></div>{{--  style="min-width:400px;height:300px;margin:0 auto;" --}}
                </div>
            </div>
        </div>
    </div>
    {{-- end  row --}}

    <div class="row">
        <div class="col-xl-12">
            <div class="card card-border">
                <div class="card-header border-success-0 bg-transparent p-0">
                    <h3 class="card-title text-primary "></h3>
                </div>
                <div class="card-body p-0">
                    <div id="anal3"></div>{{--  style="min-width:400px;height:300px;margin:0 auto;" --}}
                </div>
            </div>
        </div>
    </div>
    {{-- end  row --}}

    <div class="row">
        <div class="col-xl-12">
            <div class="card card-border">
                <div class="card-header border-success-0 bg-transparent p-0">
                    <h3 class="card-title text-primary "></h3>
                </div>
                <div class="card-body p-0">
                    <div id="anal4"></div>{{--  style="min-width:400px;height:300px;margin:0 auto;" --}}
                </div>
            </div>
        </div>
    </div>
    {{-- end  row --}}
@endsection


@section('js')
    {{-- highcharts --}}
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>



    <script type="text/javascript">
        $(document).ready(function() {
            Highcharts.setOptions({
                // colors: Highcharts.map(paleta_colores, function(color) {
                //     return {
                //         radialGradient: {
                //             cx: 0.5,
                //             cy: 0.3,
                //             r: 0.7
                //         },
                //         stops: [
                //             [0, color],
                //             [1, Highcharts.color(color).brighten(-0.3).get('rgb')] // darken
                //         ]
                //     };
                // }),
                lang: {
                    thousandsSep: ","
                }
            });

            /*
             *AJAX PARA LA PRESENTACION DE LA PRIMERA GRAFICA 1
             */
            $.ajax({
                url: "{{ route('baseingresos.ingresopresupuestal.grafica01') }}",
                data: {
                    'importacion_id': {{ $impI->id }}
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    $('#anal1').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    gPie('anal1', data.info,
                        '',
                        'Ingreso Presupuestal de la Region Ucayali',
                        '');
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });

            /*
             *AJAX PARA LA PRESENTACION DE LA PRIMERA GRAFICA 2
             */
            $.ajax({
                url: "{{ route('baseingresos.ingresopresupuestal.grafica02') }}",
                data: {
                    'importacion_id': {{ $impI->id }}
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    $('#anal2').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    gAnidadaColumn(
                        'anal2',
                        data.data.categoria,
                        data.data.series,
                        '',
                        'Recaudación De Ingresos Según Tipo De Gobierno'
                    );
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 2");
                    console.log(jqXHR);
                },
            });

            /*
             *AJAX PARA LA PRESENTACION DE LA PRIMERA GRAFICA 2
             */
            $.ajax({
                url: "{{ route('baseingresos.ingresopresupuestal.grafica03') }}",
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    $('#anal3').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    glineal(
                        'anal3',
                        data.data.categoria,
                        data.data.series,
                        '',
                        'Pim De Ingresos Según Tipo De Gobierno'
                    );
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 2");
                    console.log(jqXHR);
                },
            });


            /*
             *AJAX PARA LA PRESENTACION DE LA PRIMERA GRAFICA 2
             */
            $.ajax({
                url: "{{ route('baseingresos.ingresopresupuestal.grafica04') }}",
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    $('#anal4').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    glineal(
                        'anal4',
                        data.data.categoria,
                        data.data.series,
                        '',
                        'Recaudación De Ingresos Según Tipo De Gobierno'
                    );
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 2");
                    console.log(jqXHR);
                },
            });

        });
    </script>

    <script type="text/javascript">
        function gColumnDrilldown(div, data1, data2, titulo, subtitulo, tituloserie) {
            Highcharts.chart(div, {
                chart: {
                    type: 'column',
                },
                title: {
                    enabled: false,
                    text: titulo,
                    //align:'left',
                },
                subtitle: {
                    //align:'left',
                    text: subtitulo,
                    style: {
                        fontSize: '11px'
                    }
                },
                accessibility: {
                    announceNewData: {
                        enabled: true,
                    }
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    },
                },
                yAxis: {
                    /* max: 100, */
                    title: {
                        enabled: false,
                        text: 'Porcentaje',
                    },
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    },
                },
                legend: {
                    enabled: false,
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y}%',
                        },
                    },
                    drilldown: {
                        series: {
                            //borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: '{point.y}',
                            },
                            format: '{point.y}',
                        },
                    }
                    /* series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            //format: '{point.y:.1f}%',
                            formatter: function() {
                                if (this.y > 1000000) {
                                    return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                                } else if (this.y > 1000) {
                                    return Highcharts.numberFormat(this.y / 1000, 0) + "K";
                                } else {
                                    return this.y;
                                }
                            },
                        },
                    } */
                },
                tooltip: {
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> Hay: <b>{point.y}%</b><br/>',
                    shared: true,
                    /* headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>' */
                },
                series: [{
                    showInLegend: tituloserie != '',
                    name: tituloserie,
                    label: {
                        enabled: false
                    },
                    colorByPoint: false,
                    data: data1,

                }],
                drilldown: {
                    breadcrumbs: {
                        position: {
                            align: 'right',
                        }
                    },
                    series: data2,
                },
                credits: false,
            });
        }

        function gColumnDrilldown2(div, categoria, serie1, data2, titulo, subtitulo, tituloserie) {
            Highcharts.chart(div, {
                chart: {
                    type: 'column',
                },
                title: {
                    enabled: false,
                    text: titulo,
                    //align:'left',
                },
                subtitle: {
                    //align:'left',
                    text: subtitulo,
                    style: {
                        fontSize: '11px'
                    }
                },
                accessibility: {
                    announceNewData: {
                        enabled: true,
                    }
                },
                xAxis: {
                    //type: 'category',
                    categories: categoria,
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    },
                },
                yAxis: {
                    /* max: 100, */
                    title: {
                        enabled: false,
                        text: 'Porcentaje',
                    },
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    },
                },
                legend: {
                    enabled: false,
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y}%',
                        },
                    },
                    drilldown: {
                        series: {
                            //borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: '{point.y}',
                            },
                            format: '{point.y}',
                        },
                    }
                    /* series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            //format: '{point.y:.1f}%',
                            formatter: function() {
                                if (this.y > 1000000) {
                                    return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                                } else if (this.y > 1000) {
                                    return Highcharts.numberFormat(this.y / 1000, 0) + "K";
                                } else {
                                    return this.y;
                                }
                            },
                        },
                    } */
                },
                tooltip: {
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> Hay: <b>{point.y}%</b><br/>',
                    shared: true,
                    /* headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>' */
                },
                series: serie1
                    /* [{
                                        showInLegend: tituloserie != '',
                                        name: tituloserie,
                                        label: {
                                            enabled: false
                                        },
                                        colorByPoint: false,
                                        data: data1,

                                    }] */
                    ,
                drilldown: {
                    breadcrumbs: {
                        position: {
                            align: 'right',
                        }
                    },
                    series: data2,
                },
                credits: false,
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
                    text: subtitulo,
                    style: {
                        fontSize: '11px'
                    }
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    },
                },
                yAxis: {
                    /* max: 100, */
                    title: {
                        enabled: false,
                        text: 'Porcentaje',
                    },
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    },
                },
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
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> Hay: <b>{point.y}%</b><br/>',
                    shared: true
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y}%',
                        },
                        point: {
                            cursor: 'pointer',
                            events: {
                                click: function() {
                                    //alert('Category: ' + this.category + ', value: ' + this.y);
                                    alert(this.options);
                                    //location.href = 'https://en.wikipedia.org/wiki/' +this.options.key;
                                    //alert('hola ronald');
                                },
                            },
                        },


                    }
                    /* series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            //format: '{point.y:.1f}%',
                            formatter: function() {
                                if (this.y > 1000000) {
                                    return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                                } else if (this.y > 1000) {
                                    return Highcharts.numberFormat(this.y / 1000, 0) + "K";
                                } else {
                                    return this.y;
                                }
                            },
                        },
                    } */
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
                    text: titulo, //'Browser market shares in January, 2018'
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        fontSize: '11px'
                    }
                },
                tooltip: {
                    //pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>',
                    pointFormat: '{series.name}: <b>{point.y:,.0f} ({point.percentage:.1f}%)</b>',
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
                            format: '{point.y:,.0f} ({point.percentage:.1f}%)',
                            //format: '{point.percentage:.1f}%',
                            connectorColor: 'silver',
                            style: {
                                fontWeight: 'normal',
                            }
                        }
                    },
                    series: {
                        //allowPointSelect: true
                        style: {
                            fontSize: '10pt'
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
                legend: {
                    //align: 'center', //right//left//center
                    //verticalAlign: 'bottom', //top//middle//bottom
                    //layout: 'horizontal', //horizontal//vertical//proximate
                    itemStyle: {
                        "color": "#333333",
                        "cursor": "pointer",
                        "fontSize": "10px",
                        "fontWeight": "normal",
                        "textOverflow": "ellipsis"
                    },
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
                    text: subtitulo,
                    style: {
                        fontSize: '11px'
                    }
                },
                xAxis: {
                    categories: categorias,
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    },
                },
                yAxis: {

                    min: 0,
                    title: {
                        text: 'Rainfall (mm)',
                        enabled: false
                    },
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    },
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

        function gAnidadaColumn(div, categoria, series, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'column',
                },
                title: {
                    text: titulo, //'Browser market shares in January, 2018'
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        fontSize: '11px'
                    }
                },
                xAxis: {
                    categories: categoria,
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    },
                },
                yAxis: {
                    allowDecimals: false,
                    min: 0,
                    title: {
                        enabled: false,
                        text: 'Porcentaje',
                    },
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    },
                },
                series: series,
                plotOptions: {
                    columns: {
                        stacking: 'normal'
                    },
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            //format: '{point.y:.1f}%',
                            formatter: function() {
                                if (this.y > 1000000) {
                                    return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                                } else if (this.y > 1000) {
                                    return Highcharts.numberFormat(this.y / 1000, 0) + "K";
                                } else {
                                    return this.y;
                                }
                            },
                            style: {
                                fontWeight: 'normal',
                            }
                        },
                        /* label:{
                            style:{
                                fontWeight:'normal',
                            }
                        } */
                    }
                },
                legend: {
                    //align: 'center', //right//left//center
                    //verticalAlign: 'bottom', //top//middle//bottom
                    //layout: 'horizontal', //horizontal//vertical//proximate
                    itemStyle: {
                        "color": "#333333",
                        "cursor": "pointer",
                        "fontSize": "10px",
                        "fontWeight": "normal",
                        "textOverflow": "ellipsis"
                    },
                },
                credits: false,
            });
        }

        function barra(div) {
            Highcharts.chart(div, {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Major trophies for some English teams',
                    align: 'left'
                },
                xAxis: {
                    categories: ['Arsenal', 'Chelsea', 'Liverpool', 'Manchester United']
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Count trophies'
                    },
                    stackLabels: {
                        enabled: true,
                        style: {
                            fontWeight: 'bold',
                            color: ( // theme
                                Highcharts.defaultOptions.title.style &&
                                Highcharts.defaultOptions.title.style.color
                            ) || 'gray',
                            textOutline: 'none'
                        }
                    }
                },
                legend: {
                    align: 'left',
                    x: 70,
                    verticalAlign: 'top',
                    y: 70,
                    floating: true,
                    backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || 'white',
                    borderColor: '#CCC',
                    borderWidth: 1,
                    shadow: false
                },
                tooltip: {
                    headerFormat: '<b>{point.x}</b><br/>',
                    pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
                },
                plotOptions: {
                    column: {
                        stacking: 'normal',
                        dataLabels: {
                            enabled: true
                        }
                    }
                },
                series: [{
                    name: 'BPL',
                    data: [3, 5, 1, 13]
                }, {
                    name: 'FA Cup',
                    data: [14, 8, 8, 12]
                }, {
                    name: 'CL',
                    data: [0, 2, 6, 3]
                }]
            });
        }

        function glineal(div, categoria, series, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'spline'
                },
                title: {
                    text: titulo, //'Browser market shares in January, 2018'
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        fontSize: '11px'
                    }
                },
                xAxis: {
                    categories: categoria,
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    },
                    /* accessibility: {
                        rangeDescription: 'Range: 2015 to 2025'
                    } */
                },
                yAxis: {
                    title: {
                        enabled: false,
                        text: 'Number of Employees'
                    },
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    },
                    /* allowDecimals: false,
                    min: 0,
                    title: {
                        enabled: false,
                        text: 'Porcentaje',
                    } */
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },

                plotOptions: {
                    /* series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            //format: '{point.y:.1f}%',
                            formatter: function() {
                                if (this.y > 1000000) {
                                    return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                                } else if (this.y > 1000) {
                                    return Highcharts.numberFormat(this.y / 1000, 0) + "K";
                                } else {
                                    return this.y;
                                }
                            },
                        },
                    } */
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            formatter: function() {
                                if (this.y > 1000000) {
                                    return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                                } else if (this.y > 1000) {
                                    return Highcharts.numberFormat(this.y / 1000, 0) + "K";
                                } else {
                                    return this.y;
                                }
                            },
                            style: {
                                fontWeight: 'normal',
                            }
                        },
                        /*  point: {
                             cursor: 'pointer',
                             events: {
                                 click: function() {
                                     //alert('Category: ' + this.category + ', value: ' + this.y);
                                     alert(this.options);
                                     //location.href = 'https://en.wikipedia.org/wiki/' +this.options.key;
                                     //alert('hola ronald');
                                 },
                             },
                         }, */


                    }
                    /* spline: {
                        marker: {
                            radius: 4,
                            lineColor: '#666666',
                            lineWidth: 1
                        }
                    } */
                },
                /*  plotOptions: {
                     series: {
                         label: {
                             connectorAllowed: false
                         },
                         pointStart: 2010
                     }
                 }, */

                series: series,
                legend: {
                    align: 'center', //right//left//center
                    verticalAlign: 'bottom', //top//middle//bottom
                    layout: 'horizontal', //horizontal//vertical//proximate
                    itemStyle: {
                        "color": "#333333",
                        "cursor": "pointer",
                        "fontSize": "10px",
                        "fontWeight": "normal", //bold
                        "textOverflow": "ellipsis"
                    },
                },
                /* responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'vertical', //horizontal
                                align: 'right', //center//right//left
                                verticalAlign: 'top'//bottom//middle
                            }
                        }
                    }]
                }, */
                credits: false,

            });
        }
    </script>
@endsection
