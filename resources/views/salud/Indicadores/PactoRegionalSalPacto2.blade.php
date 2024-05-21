@extends('layouts.main', ['titlePage' => ''])
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
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
                                    title="ACTUALIZAR"><i class="fas fa-arrow-left"></i> Volver</button>
                                <button type="button" class="btn btn-orange-0 btn-xs" onclick="verpdf({{ $ind->id }})"
                                    title='FICHA TÉCNICA'><i class="fas fa-file"></i> Ficha Técnica</button>
                                <button type="button" class="btn btn-orange-0 btn-xs" onclick="location.reload()"
                                    title='ACTUALIZAR'><i class=" fas fa-history"></i>
                                    Actualizar</button>
                            </div>
                            <h3 class="card-title text-white">{{ $ind->nombre }}
                            </h3>
                        </div>
                        <div class="card-body p-2">
                            <div class="form-group row align-items-center vh-5 m-0">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <h5 class="page-title font-12">Fuente: Padrón Nominal, <br>{{ $actualizado }}</h5>
                                </div>
                                <div class="col-lg-1 col-md-1 col-sm-1  ">
                                    <select id="anio" name="anio" class="form-control btn-xs font-11 p-0"
                                        onchange="cargarcuadros();">
                                        @foreach ($anio as $item)
                                            <option value="{{ $item->anio }}"
                                                {{ $item->anio == $aniomax ? 'selected' : '' }}>
                                                {{ $item->anio }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-1 col-md-1 col-sm-1  ">
                                    <select id="mes" name="mes" class="form-control btn-xs font-11 p-0"
                                        onchange="cargarcuadros();">
                                        <option value="0">MES</option>
                                        @foreach ($mes as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->mes }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <select id="provincia" name="provincia" class="form-control btn-xs font-11"
                                        onchange="cargarDistritos();cargarcuadros();">
                                        <option value="0">PROVINCIA</option>
                                        @foreach ($provincia as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <select id="distrito" name="distrito" class="form-control btn-xs font-11"
                                        onchange="cargarcuadros();">
                                        <option value="0">DISTRITO</option>
                                    </select>
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
                                        <span data-plugin="counterup" id="ri"></span>
                                    </h4>
                                    <p class="mb-0 mt-1 text-truncate">Resultado Indicador</p>
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
                                        <span data-plugin="counterup" id="gl"></span>
                                    </h4>
                                    <p class="mb-0 mt-1 text-truncate">
                                        Gobiernos Locales
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
                                        <span data-plugin="counterup" id="gls"></span>
                                    </h4>
                                    <p class="mb-0 mt-1 text-truncate">
                                        GL Cumplen
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
                                        <span data-plugin="counterup" id="gln"></span>
                                    </h4>
                                    <p class="mb-0 mt-1 text-truncate">
                                        GL No Cumplen
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
                            <h3 class="text-black font-14 mb-0">
                                Avance acumulado de la evaluación de Cumplimiento por Distrito
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
                            {{-- <h3 class="text-black text-center font-weight-normal font-11"></h3> --}}
                        </div>
                        <div class="card-body p-0">
                            <figure class="highcharts-figure p-0 m-0">
                                <div id="anal1" style="height: 20rem"></div>
                            </figure>
                        </div>
                    </div>

                    <div class="card card-border border border-plomo-0">
                        <div class="card-header border-success-0 bg-transparent p-0">
                            {{-- <div class="card-widgets">
                                <button type="button" class="btn btn-success btn-xs" onclick="descargar1()"><i
                                        class="fa fa-file-excel"></i>
                                    Descargar</button>
                            </div> --}}
                            <h3 class="text-black font-14 mb-0">
                                Avance acumulado de la evaluación de Cumplimiento por Red
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive" style="height: 18rem" id="vtabla2">
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
                            <div class="card-widgets">
                                <button type="button" class="btn btn-success btn-xs" onclick="descargar3()"><i
                                        class="fa fa-file-excel"></i> Descargar</button>
                            </div>
                            <h3 class="text-black font-14 mb-0">
                                Listado de establecimientos de salud que fueron evaluados
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

            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-border border border-plomo-0">
                        <div class="card-header border-success-0 bg-transparent p-0">
                            <div class="card-widgets">
                                <button type="button" class="btn btn-success btn-xs" onclick="descargar4()"><i
                                        class="fa fa-file-excel"></i> Descargar</button>
                            </div>
                            <h3 class="text-black font-14 mb-0">
                                Evaluación de cumplimiento de los logros esperados por Distrito
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive" id="vtabla4">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="modal_microred" class="modal fade font-10" tabindex="-1" role="dialog"
                aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-16" id="myModalLabel">Datos del indicador</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">

                            {{-- <div class="row"> --}}
                            {{-- <div class="col-lg-6"> --}}
                            <div class="card card-border border border-plomo-0">
                                <div class="card-header border-success-0 bg-transparent p-0">
                                    <h3 class="text-black font-14 mb-0">
                                        Avance acumulado de la evaluación de Cumplimiento por Red
                                    </h3>
                                </div>
                                <div class="card-body p-0">
                                    <div class="row">
                                        <div class="col-12">{{-- style="height: 40rem"  --}}
                                            <div class="table-responsive" id="vtabla2tabla1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- </div> --}}
                            {{-- </div> --}}

                        </div>
                        <div class="modal-footer">
                            {{-- <button type="button" class="btn btn-xs btn-danger waves-effect" data-dismiss="modal">Cerrar</button> --}}
                            {{-- <button type="button" class="btn btn-primary btn-xs waves-effect waves-light" onclick="verpdf(8)">Ficha Tecnica</button> --}}
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
            // panelGraficas('anal2');
            panelGraficas('tabla1');
            panelGraficas('tabla2');
            panelGraficas('tabla3');
            panelGraficas('tabla4');
        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('salud.indicador.pactoregional.sal.pacto2.reports') }}",
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
                        $('#ri').text(data.ri + '%');
                        $('#gl').text(data.gl);
                        $('#gls').text(data.gls);
                        $('#gln').text(data.gln);
                    } else if (div == "anal1") {
                        gLineaBasica(div, data.info, '',
                            'Avance mensual de la Evaluación del cumplimiento',
                            '');
                    } else if (div == "anal2") {
                        console.log(data.info);
                        gLineaBasica2(div, data.info, '',
                            'Numero de actas de homolagación registradas en el sistema de padrón nominal por mes',
                            '');
                    } else if (div == "tabla1") {
                        $('#vtabla1').html(data.excel);
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
                    } else if (div == "tabla2tabla1") {
                        $('#vtabla2tabla1').html(data.excel);
                    } else if (div == "tabla3") {
                        $('#vtabla3').html(data.excel);
                        $('#tabla3').DataTable({
                            responsive: true,
                            autoWidth: false,
                            ordered: true,
                            // searching: false,
                            // bPaginate: false,
                            // info: false,
                            language: table_language,
                        });
                    } else if (div == "tabla4") {
                        $('#vtabla4').html(data.excel);
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
                    var options = '<option value="0">DISTRITO</option>';
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

        function descargar3() { ///{div}/{indicador}/{anio}/{mes}/{provincia}/{distrito}'
            window.open(
                "{{ route('salud.indicador.pactoregional.sal.pacto2.excel', ['', '', '', '', '', '']) }}/tabla3/{{ $ind->id }}/" +
                $('#anio').val() + "/" + $('#mes').val() + "/" + $('#provincia').val() + "/" + $('#distrito').val());
        }

        function descargar4() {
            window.open(
                "{{ route('salud.indicador.pactoregional.sal.pacto2.excel', ['', '', '', '', '', '']) }}/tabla4/{{ $ind->id }}/" +
                $('#anio').val() + "/" + $('#mes').val() + "/" + $('#provincia').val() + "/" + $('#distrito').val());
        }

        function verpdf(id) {
            window.open("{{ route('salud.indicador.pactoregional.exportar.pdf', '') }}/" + id);
        };

        function cargarmicrored(red) {
            $('#modal_microred').modal('show');
            panelGraficas('tabla2tabla1');
            
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
                // colors: ['#5eb9aa', '#f5bd22', '#ef5350'],
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
                            formatter: function() {
                                if (this.colorIndex == 0)
                                    return this.y + " %";
                                else
                                    return Highcharts.numberFormat(this.y, 0);
                            },
                            style: {
                                fontSize: '10px',
                                fontWeight: 'normal',
                            },
                        },
                        /* label: {
                            connectorAllowed: false
                        },
                        pointStart: 2010 */
                    }
                },

                // plotOptions: {
                //     /* columns: {
                //         stacking: 'normal'
                //     }, */
                //     series: {
                //         showInLegend: true,
                //         borderWidth: 0,
                //         dataLabels: {
                //             enabled: true,
                //             //format: '{point.y:,.0f}',
                //             //format: '{point.y:.1f}%',
                //             /* formatter: function() {
                //                 if (this.colorIndex == 2)
                //                     return this.y + " %";
                //                 else
                //                     return Highcharts.numberFormat(this.y, 0);
                //             }, */
                //             style: {
                //                 fontWeight: 'normal',
                //                 fontSize: '10px',
                //             }
                //         },
                //     },
                // },

                series: [{
                    name: 'Recuperados',
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
