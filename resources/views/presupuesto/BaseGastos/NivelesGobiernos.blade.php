@extends('layouts.main', ['activePage' => 'usuarios', 'titlePage' => 'Niveles de Gobierno'])
@section('css')
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/buttons.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/fixedHeader.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/scroller.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
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

        .centrador {
            position: relative;
            /* width: 400px;
                                    height: 400px; */
            /* background-color: red; */
        }

        .imagen {
            position: absolute;
            /* width: 100px; */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: auto;
        }
    </style>
@endsection

@section('content')
    <div class="content">
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
                        <h6 class="">Ejecución(<span style="font-weight: normal">DEV/PIA</span>)
                            <span class="float-right">{{ number_format($card1['eje'], 1) }}%</span>
                        </h6>
                        <div class="progress progress-sm m-0">
                            <div class="progress-bar {{ $card1['eje'] < 51 ? 'bg-danger' : ($card1['eje'] < 76 ? 'bg-warning' : 'bg-success') }}"
                                role="progressbar" aria-valuenow="{{ $card1['eje'] }}" aria-valuemin="0"
                                aria-valuemax="100" style="width: {{ $card1['eje'] }}%">
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
                        <h6 class="">Ejecución(<span style="font-weight: normal">DEV/PIM</span>)
                            <span class="float-right">{{ number_format($card2['eje'], 1) }}%</span>
                        </h6>
                        <div class="progress progress-sm m-0">
                            <div class="progress-bar {{ $card2['eje'] < 51 ? 'bg-danger' : ($card2['eje'] < 76 ? 'bg-warning' : 'bg-success') }}"
                                role="progressbar" aria-valuenow="{{ $card2['eje'] }}" aria-valuemin="0"
                                aria-valuemax="100" style="width: {{ $card2['eje'] }}%">
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
                        <h6 class="">Ejecución(<span style="font-weight: normal">CERT/PIM</span>)
                            <span class="float-right">{{ number_format($card3['eje'], 1) }}%</span>
                        </h6>
                        <div class="progress progress-sm m-0">
                            <div class="progress-bar {{ $card3['eje'] < 51 ? 'bg-danger' : ($card3['eje'] < 76 ? 'bg-warning' : 'bg-success') }}"
                                role="progressbar" aria-valuenow="{{ $card3['eje'] }}" aria-valuemin="0"
                                aria-valuemax="100" style="width: {{ $card3['eje'] }}%">
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
                        <h6 class="">Ejecución(<span style="font-weight: normal">DEV/CERT</span>)
                            <span class="float-right">{{ number_format($card4['eje'], 1) }}%</span>
                        </h6>
                        <div class="progress progress-sm m-0">
                            <div class="progress-bar {{ $card4['eje'] < 51 ? 'bg-danger' : ($card4['eje'] < 76 ? 'bg-warning' : 'bg-success') }}"
                                role="progressbar" aria-valuenow="{{ $card4['eje'] }}" aria-valuemin="0"
                                aria-valuemax="100" style="width: {{ $card4['eje'] }}%">
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
                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent p-0">
                        <h3 class="card-title text-primary "></h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="anal1"></div>{{--  style="min-width:400px;height:300px;margin:0 auto;" --}}
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent p-0">
                        <h3 class="card-title text-primary "></h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="anal4"></div>{{--  style="min-width:400px;height:300px;margin:0 auto;" --}}
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}

        <div class="row">
            <div class="col-xl-6">

                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent p-0">
                        <h3 class="card-title text-primary "></h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="anal2"></div>{{--  style="min-width:400px;height:300px;margin:0 auto;" --}}
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent p-0">
                        <h3 class="card-title text-primary "></h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="anal5"></div>{{-- style="min-width:400px;height:300px;margin:0 auto;" --}}
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}


        <div class="row">
            <div class="col-xl-12">
                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent p-0">
                        <h3 class="card-title text-primary "></h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="anal7"></div>{{-- style="min-width:400px;height:300px;margin:0 auto;" --}}
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}

        <div class="row">
            <div class="col-xl-12">
                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent p-0">
                        <h3 class="card-title text-primary "></h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="anal9"></div>
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}

        <div class="row">
            <div class="col-xl-12">
                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent p-0">
                        <h3 class="card-title text-primary "></h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="anal8"></div>{{--  style="min-width:400px;height:300px;margin:0 auto;" --}}
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}



        <div class="row">
            <div class="col-xl-12 principal">
                <div class="card card-border">{{--  bg-transparent pb-0 mb-0 --}}
                    <div class="card-header border-primary">
                        <div class="card-widgets">{{-- impormatricula.download --}}
                            <button type="button" class="btn btn-success btn-xs"
                                onclick="javascript:location=`{{ route('basegastos.download.excel.principal01') }}`"><i
                                    class="fa fa-file-excel"></i>
                                Excel</button>
                        </div>
                        <h3 class="card-title"></h3>
                    </div>
                    <div class="card-body pb-0 pt-0">
                        <div class="table-responsive" id="vista1">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}

    </div>
@endsection


@section('js')
    {{-- highcharts --}}
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <!-- third party js -->
    <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.buttons.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/buttons.bootstrap4.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/jszip/jszip.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/pdfmake/pdfmake.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/pdfmake/vfs_fonts.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/buttons.html5.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/buttons.print.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.fixedHeader.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.keyTable.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.scroller.min.js"></script>



    <script type="text/javascript">
        $(document).ready(function() {
            Highcharts.setOptions({
                colors: Highcharts.map(paleta_colores, function(color) {
                    return {
                        radialGradient: {
                            cx: 0.5,
                            cy: 0.3,
                            r: 0.7
                        },
                        stops: [
                            [0, color],
                            [1, Highcharts.color(color).brighten(-0.3).get('rgb')] // darken
                        ]
                    };
                }),
                lang: {
                    thousandsSep: ","
                }
            });

            /*
             *AJAX PARA LA PRESENTACION DE LA PRIMERA GRAFICA 1
             */
            $.ajax({
                url: "{{ route('basegastos.nivgob.graficas.1') }}",
                data: {
                    basegastos_id: {{ $bgs->id }}
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    $('#anal1').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    //console.log(data)
                    gPie('anal1', data.info,
                        '',
                        'Distribución del Presupuesto  de la Región Ucayali', /* <br><b class="fuentex">Fuente: SIAF-MEF</b> */
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
                url: "{{ route('basegastos.nivgob.graficas.2') }}",
                data: {
                    basegastos_id: {{ $bgs->id }}
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    $('#anal2').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    //console.log(data)
                    gPie('anal2', data.info,
                        '',
                        'Distribución del Presupuesto en Inversiones.',
                        '');
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 2");
                    console.log(jqXHR);
                },
            });

            /*
             *AJAX PARA LA PRESENTACION DE LA PRIMERA GRAFICA 3
             */
            /* $.ajax({
                url: "{{ url('/') }}/Home/Presupuesto/gra3/{{ $impI->id }}",
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    $('#anal3').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    //console.log(data)
                    gPie('anal3', data.info,
                        '',
                        'Ingreso Presupuestal de la Region Ucayali',
                        '');
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 3");
                    console.log(jqXHR);
                },
            }); */

            /*
             *AJAX PARA LA PRESENTACION DE LA PRIMERA GRAFICA 4
             */
            $.ajax({
                url: "{{ route('basegastos.nivgob.graficas.4') }}",
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    $('#anal7').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    glineal(
                        'anal7',
                        data.data.categoria,
                        data.data.series,
                        '',
                        'Evolución del PIM del Sector Público de la región de Ucayali',
                        'Año');
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 7");
                    console.log(jqXHR);
                },
            });

            /*
             *AJAX PARA LA PRESENTACION DE LA PRIMERA GRAFICA 5
             */
            $.ajax({
                url: "{{ route('basegastos.nivgob.graficas.5') }}",
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    $('#anal8').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    //gSimpleColumn('anal8', data.base,'','Evaluación Del Presupuesto En Inversión Pública En La Región De Ucayali','');
                    glineal(
                        'anal8',
                        data.data.categoria,
                        data.data.series,
                        '',
                        'Evolución del PIM en Inversión Pública de la región de Ucayali',
                        'Año');
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 8");
                    console.log(jqXHR);
                },
            });

            /*
             *AJAX PARA LA PRESENTACION DE LA PRIMERA GRAFICA 9
             */
            $.ajax({
                url: "{{ route('basegastos.nivgob.graficas.7') }}",
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    $('#anal9').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    glineal(
                        'anal9',
                        data.data.categoria,
                        data.data.series,
                        '',
                        'Evolución del PIM en Actividades Pública de la región de Ucayali',
                        'Año');
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 9");
                    console.log(jqXHR);
                },
            });

            /*
             *AJAX PARA LA PRESENTACION DE LA PRIMERA tabla 1
             */
            $.ajax({
                url: "{{ route('basegastos.nivgob.graficas.8') }}",
                data: {
                    basegastos_id: {{ $bgs->id }}
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    $('#anal4').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    //console.log(data.data['categoria'])
                    gAnidadaColumn(
                        'anal4',
                        data.data.categoria,
                        data.data.series,
                        '',
                        'Ejecución Presupuestal Según Tipo De Gobierno'
                    );
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 4");
                    console.log(jqXHR);
                },
            });

            /*
             *AJAX PARA LA PRESENTACION DE LA PRIMERA tabla 2
             */
            $.ajax({
                //url: "{{ url('/') }}/Home/Presupuesto/tabla2/{{ $impG->id }}",
                url: "{{ route('basegastos.nivgob.graficas.9') }}",
                data: {
                    basegastos_id: {{ $bgs->id }}
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    $('#anal5').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    //console.log(data.data['categoria'])
                    gAnidadaColumn(
                        'anal5',
                        data.data.categoria,
                        data.data.series,
                        '',
                        'Ejecución Presupuestal en Inversiones Según Tipo De Gobierno'
                    );
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 5");
                    console.log(jqXHR);
                },
            });

            /*
             *AJAX PARA LA PRESENTACION DE LA PRIMERA tabla 3
             */
            /* $.ajax({
                url: "{{ url('/') }}/Home/Presupuesto/tabla3/{{ $impI->id }}",
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    $('#anal6').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    //console.log(data)
                    gAnidadaColumn(
                        'anal6',
                        data.data['categoria'],
                        data.data['series'],
                        '',
                        'Recaudación De Ingresos Según Tipo De Gobierno'
                    );
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 6");
                    console.log(jqXHR);
                },
            }); */

            /*
             *AJAX PARA LA PRESENTACION DE LA PRIMERA tabla 1
             */
            $.ajax({
                //url: "{{ route('tabla.home.presupuesto') }}",
                url: "{{ route('basegastos.nivgob.graficas.0') }}",
                type: "GET",
                beforeSend: function() {
                    $('#vista1').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    $('#vista1').html(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
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
                },
                accessibility: {
                    announceNewData: {
                        enabled: true,
                    }
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
                },
                accessibility: {
                    announceNewData: {
                        enabled: true,
                    }
                },
                xAxis: {
                    //type: 'category',
                    categories: categoria
                },
                yAxis: {
                    /* max: 100, */
                    title: {
                        enabled: false,
                        text: 'Porcentaje',
                    }
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
                },
                xAxis: {
                    categories: categoria
                },
                yAxis: {
                    allowDecimals: false,
                    min: 0,
                    title: {
                        enabled: false,
                        text: 'Porcentaje',
                    }
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
                },
                xAxis: {
                    categories: categoria
                    /* accessibility: {
                        rangeDescription: 'Range: 2015 to 2025'
                    } */
                },
                yAxis: {
                    title: {
                        enabled: false,
                        text: 'Number of Employees'
                    }
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
