@extends('layouts.main', ['titlePage' => ''])
@section('css')
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
    </style>
@endsection
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-fill bg-primary  mb-0">
                    <div class="card-header bg-transparent">
                        <div class="card-widgets">
                            <button type="button" class="btn btn-purple btn-xs" onclick="location.reload()"><i
                                    class="fa fa-redo"></i> Actualizar</button>
                        </div>
                        <h3 class="card-title text-white text-center">EDUCACION INTERCULTURA BILINGUE (EIB) SEGÚN SIAGIE- MINEDU
                            ACTUALIZADO AL {{$fecha}} {{-- <a href="javascript:location.reload()" class="btn btn-warning"
                            title="ACTUALIZAR PAGINA"><i class="fa fa-redo"></i></a></h3> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-2">
                        <form id="form_opciones" name="form_opciones" action="POST">
                            @csrf
                            <div class="form-group row mb-0">
                                <label class="col-md-1 col-form-label">Año</label>
                                <div class="col-md-2">
                                    <select id="ano" name="ano" class="form-control  p-0" onchange="cargartabla0()">
                                        @foreach ($anios as $item)
                                            <option value="{{ $item->id }}">{{ $item->anio }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="col-md-1 col-form-label">Ugel</label>
                                <div class="col-md-2">
                                    <select id="ugel" name="ugel" class="form-control p-0" onchange="cargartabla0()">
                                        <option value="0">Todos</option>
                                        @foreach ($ugels as $ugel)
                                            <option value="{{ $ugel['id'] }}">{{ $ugel['nombre'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="col-md-1 col-form-label">Gestion</label>
                                <div class="col-md-2">
                                    <select id="gestion" name="gestion" class="form-control p-0" onchange="cargartabla0()">
                                        <option value="0">Todos</option>
                                        @foreach ($gestions as $prov)
                                            <option value="{{ $prov['id'] }}">{{ $prov['nombre'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="col-md-1 col-form-label">Área</label>
                                <div class="col-md-2">
                                    <select id="area" name="area" class="form-control p-0" onchange="cargartabla0()">
                                        <option value="0">Todos</option>
                                        @foreach ($areas as $prov)
                                            <option value="{{ $prov->id }}">{{ $prov->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="col-md-1">
                                    <a href="javascript:location.reload()" class="btn btn-primary"><i
                                            class="fa fa-redo"></i></a>
                                </div> --}}

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row">
            <div class="col-md-3 col-xl-3">
                <div class="card-box">
                    <div class="media">
                        <div class="avatar-md bg-success rounded-circle mr-2">
                            <i class=" ion-md-home avatar-title font-26 text-white"></i>
                        </div>
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold">
                                    <span data-plugin="counterup">{{ number_format($data['rer']) }}
                                    </span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">Servicios Educativos</p>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- end card-box-->
            </div>

            <div class="col-md-3 col-xl-3">
                <div class="card-box">
                    <div class="media">
                        <div class="avatar-md bg-success rounded-circle mr-2">
                            <i class=" ion ion-md-home avatar-title font-26 text-white"></i>
                        </div>
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold">
                                    <span data-plugin="counterup">
                                        {{ number_format($data['pres']) }}
                                    </span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">Locales</p>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- end card-box-->
            </div>

            <div class="col-md-3 col-xl-3">
                <div class="card-box">
                    <div class="media">
                        <div class="avatar-md bg-info rounded-circle mr-2">
                            <i class=" ion ion-md-person avatar-title font-26 text-white"></i>
                        </div>
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold">
                                    <span data-plugin="counterup">
                                        {{ number_format($data['alumnos']) }}
                                    </span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">Estudiantes</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card-box-->
            </div>

            <div class="col-md-3 col-xl-3">
                <div class="card-box">
                    <div class="media">
                        <div class="avatar-md bg-info rounded-circle mr-2">
                            <i class=" ion ion-md-person avatar-title font-26 text-white"></i>
                        </div>
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold">
                                    <span data-plugin="counterup">
                                        {{ number_format($data['docentes']) }}
                                    </span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">Docentes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- end row --}}


        {{-- grafica 1 --}}
        <div class="row">
            <div class="col-xl-6">
                <div class="card card-border">
                    <div class="card-header border-primary bg-transparent p-0">
                        <h3 class="card-title"></h3>
                    </div>
                    <div class="card-body pb-0 pt-0">
                        <div id="gra1"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card card-border">
                    <div class="card-header border-primary bg-transparent p-0">
                        <h3 class="card-title"></h3>
                    </div>
                    <div class="card-body pb-0 pt-0">
                        <div id="gra2"></div>
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}

        {{-- grafica 1 --}}
        <div class="row">
            <div class="col-xl-6">
                <div class="card card-border">
                    <div class="card-header border-primary bg-transparent p-0">
                        <h3 class="card-title"></h3>
                    </div>
                    <div class="card-body pb-0 pt-0">
                        <div id="gra3"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card card-border">
                    <div class="card-header border-primary bg-transparent p-0">
                        <h3 class="card-title"></h3>
                    </div>
                    <div class="card-body pb-0 pt-0">
                        <div id="gra4"></div>
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}
    </div>
    {{-- <div class="row">
        <div class="col-xl-12 principal">
            <div class="card card-border">
                <div class="card-header border-primary bg-transparent pb-0 mb-0">
                    <h3 class="card-title"></h3>
                </div>
                <div class="card-body pb-0 pt-0">
                    <div class="table-responsive" id="vista1">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 principal">
            <div class="card card-border">
                <div class="card-header border-primary bg-transparent pb-0 mb-0">
                    <h3 class="card-title"></h3>
                </div>
                <div class="card-body pb-0 pt-0">
                    <div class="table-responsive" id="vista2">
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function() {

            cargartabla0();
        });

        function cargartabla0() {

            $.ajax({
                url: "{{ route('matriculadetalle.eib.grafica1') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    gLineaMultiple('gra1', data, '', 'ESTUDIANTES MATRICULADOS SEGÚN NIVEL', '');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('matriculadetalle.eib.grafica2') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    gLineaBasica('gra2', data, '', 'MATRICULA ACUMULADA MENSUAL', '');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('matriculadetalle.eib.grafica3') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    gPie('gra3', data, '', 'MATRICULADOS SEGÚN NIVEL EDUCATIVO', '');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('matriculadetalle.eib.grafica4') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    gPie('gra4', data, '', 'ESTUDIANTES SEGÚN GENERO', '');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            /* $.ajax({
                url: "{{ route('matriculadetalle.eib.tabla1') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    $('#vista1').html(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
            $.ajax({
                url: "{{ route('matriculadetalle.eib.tabla2') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    $('#vista2').html(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            }); */
        }

        function gLineaBasica(div, data, titulo, subtitulo, titulovetical) {
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
                    /* min:0, */
                },
                xAxis: {
                    categories: data['cat'],
                    accessibility: {
                        rangeDescription: 'Range: 2010 to 2017'
                    }
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
                    data: data['dat']
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
                credits: false,

            });
        }

        function gLineaMultiple(div, data, titulo, subtitulo, titulovetical) {
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
                    /* min:0, */
                },
                xAxis: {
                    categories: data['cat'],
                    accessibility: {
                        rangeDescription: 'Range: 2010 to 2017'
                    }
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
                series: data['dat'],
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
                            format: '{point.yx} ( {point.percentage:.1f}% )',
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
                credits: false,
            });
        }
    </script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script> --}}
    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script> --}}
@endsection
