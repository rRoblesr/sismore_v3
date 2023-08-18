@extends('layouts.main',['activePage'=>'importacion','titlePage'=>''])

@section('css')
@endsection

@section('content')
    <div class="content">
        <input type="hidden" id="hoja" value="1">

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="" name="form_parametros">
                            <input type="hidden" id="importacion_id" name="importacion_id">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="titulo_Indicadores  mb-0">REPORTE DE PLAZAS DOCENTES</p>
                                </div>
                                <div class="col-md-6 text-right">
                                            <div class="form-group row">
                                                <div class="col-md-4"></div>
                                                <label class="col-md-4 col-form-label">Año:</label>
                                                <div class="col-md-4">
                                                    <select id="anio" name="anio" class="form-control"
                                                        onchange="cargarUltimo();">
                                                        @foreach ($anios as $item)
                                                            <option value="{{ $item->anio }}"> {{ $item->anio }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                </div>
                            </div>
                        </form>
                        <div class="progress progress-sm m-0">
                            <div class="progress-bar bg-info" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                aria-valuemax="100" style="width: 100%"></div>
                        </div>
                        <br>
                        
                        <div class="row">
                            @php
                                $color = ['info', 'purple', 'success', 'primary', 'pink', 'dark', 'warning', 'secondary'];
                                $img = [' mdi mdi-home-group', ' mdi mdi-water-outline', ' mdi mdi-water', ' mdi mdi-spray-bottle'];
                            @endphp
                            {{-- //@foreach ($data as $pos => $dato) --}}
                            <div class="col-md-6 col-xl-3">
                                <div class="card-box">
                                    <div class="media">
                                        <div class="avatar-md bg-primary rounded-circle mr-2">
                                            {{-- <i class="{{ $img[$pos] }} avatar-title font-26 text-white"></i> --}}
                                            <i class="ion-md-person avatar-title font-26 text-white"></i>
                                        </div>
                                        <div class="media-body align-self-center">
                                            <div class="text-right">
                                                <h4 class="font-20 my-0 font-weight-bold opt1"><span
                                                        data-plugin="counterup">5</span>
                                                </h4>
                                                <p class="mb-0 mt-1 text-truncate"><span>DOCENTES</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="card-box">
                                    <div class="media">
                                        <div class="avatar-md bg-primary rounded-circle mr-2">
                                            {{-- <i class="{{ $img[$pos] }} avatar-title font-26 text-white"></i> --}}
                                            <i class="ion-md-person avatar-title font-26 text-white"></i>
                                        </div>
                                        <div class="media-body align-self-center">
                                            <div class="text-right">
                                                <h4 class="font-20 my-0 font-weight-bold  opt2"><span
                                                        data-plugin="counterup">5</span>
                                                </h4>
                                                <p class="mb-0 mt-1 text-truncate">ADMINISTRATIVOS</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="card-box">
                                    <div class="media">
                                        <div class="avatar-md bg-primary rounded-circle mr-2">
                                            {{-- <i class="{{ $img[$pos] }} avatar-title font-26 text-white"></i> --}}
                                            <i class="ion-md-person avatar-title font-26 text-white"></i>
                                        </div>
                                        <div class="media-body align-self-center">
                                            <div class="text-right">
                                                <h4 class="font-20 my-0 font-weight-bold opt3"><span
                                                        data-plugin="counterup">5</span>
                                                </h4>
                                                <p class="mb-0 mt-1 text-truncate">CAS</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="card-box">
                                    <div class="media">
                                        <div class="avatar-md bg-primary rounded-circle mr-2">
                                            {{-- <i class="{{ $img[$pos] }} avatar-title font-26 text-white"></i> --}}
                                            <i class="ion-md-person avatar-title font-26 text-white"></i>
                                        </div>
                                        <div class="media-body align-self-center">
                                            <div class="text-right">
                                                <h4 class="font-20 my-0 font-weight-bold opt4"><span
                                                        data-plugin="counterup">5</span>
                                                </h4>
                                                <p class="mb-0 mt-1 text-truncate">PEC</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- //@endforeach --}}

                        </div>
                        <!-- end row -->

                        <br>

                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card card-border card-primary">
                                    <div class="card-header border-primary bg-transparent p-0">
                                        <h3 class="card-title text-primary "></h3>
                                    </div>
                                    <div class="card-body">
                                        <div id="anal1"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card card-border card-primary">
                                    <div class="card-header border-primary bg-transparent p-0">
                                        <h3 class="card-title text-primary "></h3>
                                    </div>
                                    <div class="card-body">
                                        <div id="anal2"></div>
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
                                    <div class="card-body">
                                        <div id="anal3"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card card-border card-primary">
                                    <div class="card-header border-primary bg-transparent p-0">
                                        <h3 class="card-title text-primary "></h3>
                                    </div>
                                    <div class="card-body">
                                        <div id="anal4"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- end  row --}}



                        {{-- fin --}}
                        <div class="progress progress-sm m-0">
                            <div class="progress-bar bg-info" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                aria-valuemax="100" style="width: 100%"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <p class="titulo_Indicadores  mb-0"></p>
                            </div>
                            <div class="col-md-10 text-right">
                                <p class="texto_dfuente  mb-0"> Fuente: Sistema de Administración y Control de Plazas –
                                    NEXUS, ultima actualizacion del <span id="fechaActualizacion"></span>
                                </p>
                            </div>
                        </div>

                    </div>
                    <!-- card-body -->

                </div>

            </div> <!-- End col -->
        </div> <!-- End row -->


    </div>
@endsection

@section('js')
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script>

    <script type="text/javascript">
        var nombre_mes = ["ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SETIEMBRE", "OCTUBRE",
            "NOVIEMBRE", "DICIEMBRE"
        ];
        $(document).ready(function() {
            //cargar_resumen_porUgel();
            cargarMes();
        });

        function cargarMes() {
            $.ajax({
                url: "{{ url('/') }}/Plaza/Mes/" + $('#anio').val(),
                type: 'get',
                dataType: 'JSON',
                data: {
                    'anio': $('#anio').val()
                },
                success: function(data) {
                    $("#mes option").remove();
                    var options = ''; // '<option value="">SELECCIONAR</option>';
                    $.each(data.meses, function(index, value) {
                        options += "<option value='" + value.mes + "'>" + nombre_mes[value.mes - 1] +
                            "</option>"
                    });
                    $("#mes").append(options);
                    cargarUltimo();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarUltimo() {
            $.ajax({
                url: "{{ url('/') }}/Plaza/UltimoImportado/" + $('#anio').val() + "/0" ,
                type: 'get',
                dataType: 'JSON',
                success: function(data) {
                    console.log(data);
                    var f = new Date(data.importado.fechaActualizacion);
                    $('#fechaActualizacion').html(getFecha(f));
                    $('#importacion_id').val(data.importado.id);
                    cargarVista();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function getFecha(f) {
            var d = f.getDate() < 10 ? "0" + f.getDate() : f.getDate();
            var m = f.getMonth() + 1;
            m = m < 10 ? "0" + m : m;
            var y = f.getYear() + 1900;
            return d + "/" + m + "/" + y
        }

        function cargarVista() {
            $.ajax({
                url: "{{ url('/') }}/Plaza/Docentes/" + $('#importacion_id').val(),
                type: 'get',
                dataType: 'JSON',
                success: function(data) {
                    console.log(data.info);
                    $('.opt1').html(data.info.opt1);
                    $('.opt2').html(data.info.opt2);
                    $('.opt3').html(data.info.opt3);
                    $('.opt4').html(data.info.opt4);
                    gBasicColumn('anal1', data.info.v1.categoria, data.info.v1.data, '',
                        'DOCENTES POR NIVEL EDUCATIVO');
                    gBasicColumn('anal2', data.info.v2.categoria, data.info.v2.data, '',
                        'DOCENTES y AUXILIARES DE EDUCACION POR UGEL');
                    gBasicColumn('anal3', data.info.v3.categoria, data.info.v3.data, '',
                        'TRABAJADORES ADMINISTRATIVOS POR UGEL');
                    gSimpleColumn('anal4', data.info.v4, '', 'TRABAJADORES PEC POR UGEL', '');
                },
                error: function(jqXHR, textStatus, errorThrown) {
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
                    colorByPoint: true,
                    data: datax,
                }],
                tooltip: {
                    //headerFormat: '<span style="font-size: 10px">{point.key}</span><br/>',
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> Hay: <b>{point.y}</b><br/>',
                    //pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.conteo}</b><br/>',
                    shared: true
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            /* format: '{point.y:.1f}%', */
                        },
                    }
                },
                credits: false,
            });
        }

        function gSimpleColumnx(div, datax, titulo, subtitulo, tituloserie) { //tiene otras opciones
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
                    colorByPoint: true,
                    data: datax,
                }],
                tooltip: {
                    //headerFormat: '<span style="font-size: 10px">{point.key}</span><br/>',
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> Hay: <b>{point.conteo}</b><br/>',
                    //pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.conteo}</b><br/>',
                    shared: true
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.1f}%',
                        },
                    }
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
                    text: titulo,
                },
                subtitle: {
                    text: subtitulo,
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
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            connectorColor: 'silver'
                        }
                    }
                },
                series: [{
                    name: 'Share',
                    data: datos,
                }],
                credits: false,
            });
        }

        function gBasicColumn(div, categorias, datos, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'column'
                },
                title: {
                    text: titulo /*  'Monthly Average Rainfall' */
                },
                subtitle: {
                    text: subtitulo /*  'Source: WorldClimate.com' */
                },
                xAxis: {
                    categories: categorias,
                    /* crosshair: true */
                },
                yAxis: {

                    min: 0,
                    title: {
                        text: 'Rainfall (mm)',
                        enabled: false
                    }
                },
                /*tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },*/
                /* plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                }, */
                tooltip: {
                    //headerFormat: '<span style="font-size: 10px">{point.key}</span><br/>',
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> Hay: <b>{point.y}</b><br/>',
                    //pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.conteo}</b><br/>',
                    shared: true
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            /* format: '{point.y:.1f}%', */
                        },
                    }
                },
                series: datos,
                credits: false,
            });
        }
    </script>
@endsection
