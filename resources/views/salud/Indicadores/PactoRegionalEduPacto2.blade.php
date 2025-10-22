@extends('layouts.main', ['titlePage' => ''])
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
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
                            <h5 class="page-title font-12">Fuente: , <br>{{ $actualizado }}</h5>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1  ">
                            <div class="custom-select-container">
                                <label for="anio">AÑO</label>
                                <select id="anio" name="anio" class="form-control font-11 p-0">
                                    {{-- <option value="0">TODOS</option> --}}
                                    @foreach ($anio as $item)
                                        <option value="{{ $item->anio }}" {{ $item->anio == $aniomax ? 'selected' : '' }}>
                                            {{ $item->anio }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="mes">MES</label>
                                <select id="mes" name="mes" class="form-control font-11">
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="provincia">PROVINCIA</label>
                                <select id="provincia" name="provincia" class="form-control font-11">
                                    <option value="0">TODOS</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="distrito">DISTRITO</label>
                                <select id="distrito" name="distrito" class="form-control font-11">
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
                                <span onclick="" data-toggle="modal" data-target="#info_denominador">
                                    <i class="mdi mdi-rotate-180 mdi-alert-circle" style="color:#43beac;"></i>
                                </span>
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
                                <span onclick="" data-toggle="modal" data-target="#info_denominador">
                                    <i class="mdi mdi-rotate-180 mdi-alert-circle" style="color:#43beac;"></i>
                                </span>
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
                    {{-- <div class="card-widgets">
                                <button type="button" class="btn btn-success btn-xs"><i
                                        class="fa fa-file-excel"></i> Descargar</button>
                            </div> --}}
                    <h3 class="text-black font-14 mb-0">Avance de Locales Escolares por Distrito
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" style="height: 40rem" id="vtabla2">
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
                    {{-- <figure class="highcharts-figure p-0 m-0"> --}}
                    <div id="anal1" style="height: 20rem"></div>
                    {{-- </figure> --}}
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent p-0">
                    <h3 class="text-black text-center font-weight-normal font-11 m-0"></h3>
                </div>
                <div class="card-body p-0">
                    {{-- <figure class="highcharts-figure p-0 m-0"> --}}
                    <div id="anal2" style="height: 20rem"></div>
                    {{-- </figure> --}}
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
                    <h3 class="text-black font-14 mb-0">Evaluación de cumplimiento de los logros esperados por
                        distrito
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" id="vtabla3">
                            </div>
                        </div>
                    </div>
                    {{-- <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                <span class="float-left vtabla1-fuente">Fuente:</span>
                                <span class="float-right vtabla1-fecha">Actualizado:</span>
                            </div> --}}
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
                    <h3 class="text-black font-14 mb-0">Listado de Instituciones Educativas Pùblicas, Segùn estado
                        de
                        Saneamiento Fisico Legal
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" id="vtabla4">
                            </div>
                        </div>
                    </div>
                    {{-- <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                <span class="float-left vtabla1-fuente">Fuente:</span>
                                <span class="float-right vtabla1-fecha">Actualizado:</span>
                            </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        var ugel_select = 0;
        const spinners = {
            head: ['#rin', '#nsa', '#ssa', '#loc'],
            anal: ['#anal1', '#anal2'],
            tabla: ['#vtabla1', '#vtabla2', '#vtabla3', '#vtabla4']
        };
        $(document).ready(function() {
            Object.keys(spinners).forEach(key => {
                SpinnerManager.show(key);
            });
            Highcharts.setOptions({
                lang: {
                    thousandsSep: ","
                }
            });
            $('#anio').on('change', function() {
                cargarMes();
            });
            $('#mes').on('change', function() {
                cargarProvincia();
            });
            $('#provincia').on('change', function() {
                cargarDistritos();
            });
            $('#distrito').on('change', function() {
                cargarcuadros();
            });
            cargarMes();
        });

        function cargarcuadros() {
            panelGraficas('head');
            panelGraficas('anal1');
            panelGraficas('anal2');
            panelGraficas('tabla1');
            panelGraficas('tabla2');
            panelGraficas('tabla3');
            panelGraficas('tabla4');
        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('salud.indicador.pactoregional.edu.pacto2.reports') }}",
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
                    // SpinnerManager.show(div);
                },
                success: function(data) {
                    if (div == "head") {
                        $('#rin').text(data.rin).counterUp({
                            delay: 10,
                            time: 1000,
                            callback: function() {
                                $('#rin').append('%');
                            }
                        });
                        $('#nsa').text(data.nsa).counterUp({
                            delay: 10,
                            time: 1000
                        });
                        $('#ssa').text(data.ssa).counterUp({
                            delay: 10,
                            time: 1000
                        });
                        $('#loc').text(data.loc).counterUp({
                            delay: 10,
                            time: 1000
                        });
                    } else if (div == "anal1") {
                        gcolumn2(div,
                            data.info.categoria,
                            data.info.series,
                            '',
                            'Locales escolares públicos con saneamiento físico legal por provincia'
                        );
                    } else if (div == "anal2") {
                        gPie2(div, data.info, '', 'Numero de estudiantes matriculados según sexo', '');
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
                        // $('.vtabla2-fuente').html('Fuente: ]]' + data.reg.fuente);
                        // $('.vtabla2-fecha').html('Actualizado: ' + data.reg.fecha);
                    } else if (div == "tabla3") {
                        $('#vtabla3').html(data.excel);
                        // $('.vtabla2-fuente').html('Fuente: ]]' + data.reg.fuente);
                        // $('.vtabla2-fecha').html('Actualizado: ' + data.reg.fecha);
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

        function cargarMes() {
            $.ajax({
                url: "{{ route('educacion.pactoregional.pacto2.mes', ['anio' => ':anio']) }}"
                    .replace(':anio', $('#anio').val()),
                type: 'GET',
                success: function(data) {
                    $("#mes option").remove();
                    var options = '<option value="0">TODOS</option>';
                    // var mesmax = 0;
                    // $.each(data, function(index, value) {
                    //     if (value.id > mesmax) {
                    //         mesmax = value.id;
                    //     }
                    // });
                    $.each(data, function(index, value) {
                        // var isSelected = (value.id == mesmax) ? "selected" : "";
                        options += `<option value='${value.id}'>${value.nombre}</option>`;
                    });
                    $("#mes").append(options);
                    cargarProvincia();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("Error al cargar meses:", textStatus, errorThrown);
                }
            });
        }

        function cargarProvincia() {
            $.ajax({
                url: "{{ route('educacion.pactoregional.pacto2.provincia', ['anio' => ':anio', 'mes' => ':mes']) }}"
                    .replace(':anio', $('#anio').val())
                    .replace(':mes', $('#mes').val()),
                type: 'GET',
                success: function(data) {
                    $("#provincia option").remove();
                    var options = '<option value="0">TODOS</option>';
                    $.each(data, function(index, value) {
                        options += `<option value='${value.id}'>${value.nombre}</option>`;
                    });
                    $("#provincia").append(options);
                    cargarDistritos();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("Error al cargar meses:", textStatus, errorThrown);
                }
            });
        }

        function cargarDistritos() {
            $.ajax({
                url: "{{ route('educacion.pactoregional.pacto2.distrito', ['anio' => ':anio', 'mes' => ':mes', 'provincia' => ':provincia']) }}"
                    .replace(':anio', $('#anio').val())
                    .replace(':mes', $('#mes').val())
                    .replace(':provincia', $('#provincia').val()),
                type: 'GET',
                success: function(data) {
                    $("#distrito option").remove();
                    var options = '<option value="0">TODOS</option>';
                    $.each(data, function(index, value) {
                        options += `<option value='${value.id}'>${value.nombre}</option>`;
                    });
                    $("#distrito").append(options);
                    cargarcuadros();
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

        function gcolumn2(div, categoria, series, titulo, subtitulo) {
            const colors = ['#5eb9aa', '#ef5350', '#f5bd22', '#ef5350'];
            Highcharts.chart(div, {
                chart: {
                    type: 'column'
                },
                accessibility: {
                    enabled: false
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
                accessibility: {
                    enabled: false
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
    </script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <!-- optional -->
    <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>

    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
@endsection
