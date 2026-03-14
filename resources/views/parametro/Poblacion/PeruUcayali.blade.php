@extends('layouts.main', ['titlePage' => 'INDICADOR'])
@section('css')
    <link rel="stylesheet" href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" />
    <style>
        .chart-container {
            position: relative;
            width: 100%;
            height: 500px;
            /* Ajusta según sea necesario */
        }

        .credits {
            position: absolute;
            bottom: 10px;
            /* Ajusta según sea necesario */
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .credits-left {
            position: absolute;
            bottom: 10px;
            left: 10px;
            /* Ajusta según sea necesario */
            text-align: left;
            font-size: 10px;
            color: #666;
        }

        .credits-right {
            position: absolute;
            bottom: 10px;
            right: 10px;
            /* Ajusta según sea necesario */
            text-align: right;
            font-size: 10px;
            color: #666;
        }

        /* #anal1 { */
        /* position: relative; */
        /* } */

        .spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 2em;
            /* Ajusta el tamaño según sea necesario */
        }

        /*  */
        .custom-select-container {
            position: relative;
        }

        .custom-select-container label {
            position: absolute;
            top: -7px;
            left: 10px;
            background-color: white;
            padding: 0 5px;
            font-size: 10px;
            /*color: #0d6efd;*/
        }

        .custom-select-container select {
            /* padding-left: 10px; */
        }
    </style>
@endsection

@section('content')
    @php
        $aniomax = $anios->max('anio');
        $aniomin = $anios->min('anio');
    @endphp
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card ">
                <div class="card-header bg-success-0 ">
                    <div class="card-widgets">
                        {{-- <button type="button" class="btn btn-orange-0 btn-xs" onclick="" title='XXX'><i
                            class="fas fa-file"></i> Instituciones Educativas</button> --}}
                        <button type="button" class="btn btn-orange-0 btn-xs" onclick="location.reload()"
                            title='ACTUALIZAR'><i class=" fas fa-history"></i> Actualizar</button>
                        {{-- @if ($registrador > 0)
                        <button class="btn btn-xs btn-primary waves-effect waves-light" data-toggle="moda   l"
                            data-target="#modal_form" title="Agregar Actas" onclick="abrirnuevo()"> <i
                                class="fa fa-file"></i>
                            Nuevo</button> &nbsp;
                    @endif --}}

                    </div>
                    <h3 class="card-title text-white">POBLACIÓN ESTIMADA DEL DEPARTAMENTO DE UCAYALI</h3>
                </div>
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-lg-6 col-md-4 col-sm-4">
                            <h4 class="page-title font-12">Fuente: INEI - MINSA 
                                <span id="anio_seleccionado">{{ $aniomax }}</span></h4>
                            </h4>
                        </div>
                        <div class="col-lg-2 col-md-1 col-sm-1  ">
                            <div class="custom-select-container">
                                <label for="vanio">AÑO</label>
                                <select id="vanio" name="vanio" class="form-control form-control-sm"
                                    onchange="panelGraficas('anal1');cargarCards();$('#anio_seleccionado').html(this.value);">
                                    @foreach ($anios as $item)
                                        <option value="{{ $item->anio }}"
                                            {{ $item->anio == date('Y') ? 'selected' : '' }}>
                                            {{ $item->anio }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="vprovincia" class="">PROVINCIA</label>
                                <select id="vprovincia" name="vprovincia" class="form-control form-control-sm"
                                    onchange="cargar_distritos();panelGraficas('anal1');cargarCards();">
                                    <option value="0">TODOS</option>
                                    @foreach ($provincia as $item)
                                        <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="vdistrito" class="">DISTRITO</label>
                                <select id="vdistrito" name="vdistrito" class="form-control form-control-sm"
                                    onchange="cargarCards();">
                                    <option value="0">TODOS</option>
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
                    {{-- <div class="text-center">
                        <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                            width="70%" height="70%">
                    </div> --}}
                    <div class="avatar-md mr-2">
                        <i class="ion ion-ios-people avatar-title font-40 text-dark"></i>
                        {{-- <i class="ion ion-ios-people avatar-title font-26 text-white"></i> --}}
                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="card1"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">Población</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card-box-->
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card-box border border-plomo-0">
                <div class="media">
                    {{-- <div class="text-center">
                        <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                            width="70%" height="70%">
                    </div> --}}
                    <div class="avatar-md mr-2">
                        <i class="mdi mdi-human-male avatar-title font-44 text-dark"></i>
                        {{-- <i class="ion ion-ios-people avatar-title font-26 text-white"></i> --}}
                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="card2"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">Población Hombre</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card-box-->
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card-box border border-plomo-0">
                <div class="media">
                    {{-- <div class="text-center">
                        <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                            width="70%" height="70%">
                    </div> --}}
                    <div class="avatar-md mr-2">
                        <i class="ion ion-ios-woman avatar-title font-40 text-dark"></i>
                        {{-- <i class="ion ion-ios-people avatar-title font-26 text-white"></i> --}}
                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="card3"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">Población Mujer</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card-box-->
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card-box border border-plomo-0">
                <div class="media">
                    {{-- <div class="text-center">
                        <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                            width="70%" height="70%">
                    </div> --}}
                    <div class="avatar-md mr-2">
                        <i class="fas fa-child avatar-title font-40 text-dark"></i>
                        {{-- <i class="ion ion-ios-people avatar-title font-26 text-white"></i> --}}
                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="card4"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">Población 0 a 5 años</p>
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
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    {{-- <h3 class="text-black text-center font-weight-normal font-11"></h3> --}}
                </div>
                <div class="card-body p-0">
                    {{-- <figure class="highcharts-figure p-0 m-0"> --}}
                    <div id="anal1" style="height: 35rem"></div>
                    {{-- </figure> --}}
                    {{-- <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                            <span class="anal1-fuente">Fuente:</span>
                            <span class="float-right anal1-fecha">Actualizado:</span>
                        </div> --}}
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    {{-- <h3 class="text-black text-center font-weight-normal font-11"></h3> --}}
                </div>
                <div class="card-body p-0">
                    {{-- <figure class="highcharts-figure p-0 m-0"> --}}
                    <div id="anal2" style="height: 35rem"></div>
                    {{-- </figure> --}}
                    {{-- <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                            <span class="anal2-fuente">Fuente:</span>
                            <span class="float-right anal2-fecha">Actualizado:</span>
                        </div> --}}
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    {{-- <h3 class="text-black text-center font-weight-normal font-11"></h3> --}}
                </div>
                <div class="card-body p-0">
                    {{-- <figure class="highcharts-figure p-0 m-0"> --}}
                    <div id="anal3" style="height: 20rem"></div>
                    {{-- </figure> --}}
                    {{-- <div class="credits-left">Fuente: RENIEC - PADRÓN NOMINAL</div>
                    <div class="credits-right">Actualizado: JULIO 2024</div> --}}
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    {{-- <h3 class="text-black text-center font-weight-normal font-11"></h3> --}}
                </div>
                <div class="card-body p-0">
                    {{-- <figure class="highcharts-figure p-0 m-0"> --}}
                    <div id="anal4" style="height: 20rem"></div>
                    {{-- </figure> --}}
                    {{-- <div class="credits-left">Fuente: RENIEC - PADRÓN NOMINAL</div>
                    <div class="credits-right">Actualizado: JULIO 2024</div> --}}
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    {{-- <h3 class="text-black text-center font-weight-normal font-11"></h3> --}}
                </div>
                <div class="card-body p-0">
                    {{-- <figure class="highcharts-figure p-0 m-0"> --}}
                    <div id="anal5" style="height: 20rem"></div>
                    {{-- </figure> --}}
                    {{-- <div class="credits-left">Fuente: RENIEC - PADRÓN NOMINAL</div>
                    <div class="credits-right">Actualizado: JULIO 2024</div> --}}
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    {{-- <h3 class="text-black text-center font-weight-normal font-11"></h3> --}}
                </div>
                <div class="card-body p-0">
                    {{-- <figure class="highcharts-figure p-0 m-0"> --}}
                    <div id="anal6" style="height: 20rem"></div>
                    {{-- </figure> --}}
                    {{-- <div class="credits-left">Fuente: RENIEC - PADRÓN NOMINAL</div>
                    <div class="credits-right">Actualizado: JULIO 2024</div> --}}
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-success btn-xs" onclick="descargar1()"><i
                                class="fa fa-file-excel"></i> Descargar</button>
                    </div>
                    <h3 class="text-black font-14">
                        Población estimada por departamento, segùn sexo y rango de edades
                    </h3>
                </div>
                <div class="card-body pt-0 pb-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" id="vtabla1">
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
        var distrito_select = 0;
        var anal1;
        var anal2;
        var anal3;
        var anal4;
        var anal5;
        var anal6;
        let selectedCode = null;
        let originalColors = {};
        var mapData;

        $(document).ready(function() {
            Highcharts.setOptions({
                lang: {
                    thousandsSep: ","
                }
            });
            cargar_distritos();
            mapData = otros;

            mapData.features.forEach((element, key) => {
                console.log('["' + element.properties['hc-key'] + '", ' + (key + 1) + '],');
            }); 
            panelGraficas('anal1');
            cargarCards();
        });

        function cargarCards() {
            panelGraficas('head');
            // panelGraficas('anal1');
            panelGraficas('anal2');
            panelGraficas('anal3');
            panelGraficas('anal4');
            panelGraficas('anal5');
            panelGraficas('anal6');
            panelGraficas('tabla1');
        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('poblacionprincipal.peru.ucayali.tablas') }}",
                data: {
                    'div': div,
                    "anio": $('#vanio').val(),
                    "provincia": $('#vprovincia').val(),
                    "distrito": $('#vdistrito').val(),
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    if (div == "head") {
                        $('#card1').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span>');
                        $('#card2').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span>');
                        $('#card3').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span>');
                        $('#card4').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span>');
                    } else if (div == "anal1") {
                        if ($('#vprovincia').val() == 0) {
                            $('#' + div).html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span>');
                        }
                    } else if (div == "anal2") {
                        $('#' + div).html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span>');
                    } else if (div == "anal3") {
                        $('#' + div).html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span>');
                    } else if (div == "anal4") {
                        $('#' + div).html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span>');
                    } else if (div == "anal5") {
                        $('#' + div).html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span>');
                    } else if (div == "anal6") {
                        $('#' + div).html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span>');
                    } else if (div == "tabla1") {
                        $('#v' + div).html(
                            '<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span>');
                    }

                },
                success: function(data) {
                    var mapa_selected = '';

                    if ($('#vprovincia').val() > 0) {
                        mapa_selected = $('#vprovincia option:selected').text() + ': ';
                    }

                    if (div == 'head') {
                        $('#card1').text(data.card1);
                        $('#card2').text(data.card2);
                        $('#card3').text(data.card3);
                        $('#card4').text(data.card4);

                    } else if (div == "anal1") {
                        // console.log(data.info);

                        if ($('#vprovincia').val() == 0) {
                            anal1 = maps01(div, data.info, '',
                                'Población Estimada y Proyectada, según Provincia y Distritos');
                        } else if ($('#vprovincia').val() > 0) {
                            var serie = anal1.series[0];
                            var depa = $('#vprovincia').val();
                            var point = serie.points.find(
                                p => p.properties['fips'] && p.properties['fips'].substring(2) === depa
                            );

                            if (point) {
                                if (!originalColors[point.properties['hc-key']]) {
                                    originalColors[point.properties['hc-key']] = point
                                        .color; // Almacena el color original
                                }
                                // Remover selección previa si existe
                                if (selectedCode) {
                                    let prevPoint = serie.points.find(
                                        p => p.properties['hc-key'] === selectedCode
                                    );
                                    if (prevPoint) {
                                        prevPoint.update({
                                            color: originalColors[selectedCode] || Highcharts
                                                .getOptions().colors[0]
                                        });
                                    }
                                }

                                // Resaltar el nuevo departamento
                                point.update({
                                    color: '#bada55'
                                });

                                // Actualizar el código seleccionado
                                selectedCode = point.properties['hc-key'];
                            }
                        }


                    } else if (div == "anal2") {
                        anal2 = gbar2(div, data.info, '',
                            mapa_selected + 'Pirámide poblacional, según sexo  y grupo etario', '');
                    } else if (div == "anal3") {
                        anal3 = gColumnx(div, data.info, '',
                            'Población estimada y proyectada, periodo {{ $aniomin }}-{{ $aniomax }}', 'Año')
                    } else if (div == "anal4") {
                        anal4 = gPie2(div, data.info, '',
                            'Porcentaje de la Población Estimada, según etapa de vida');
                    } else if (div == "anal5") {
                        anal5 = gColumnx(div, data.info, '',
                            'Población estimada y proyectada, periodo {{ $aniomin }}-{{ $aniomax }}', 'Año')
                    } else if (div == "anal6") {
                        anal6 = gColumnx(div, data.info, '',
                            'Población estimada y proyectada, periodo {{ $aniomin }}-{{ $aniomax }}', 'Etapa Vida')
                    } else if (div == "tabla1") {
                        $('#vtabla1').html(data.excel);
                        // $('.vtabla1-fuente').html('Fuente: ' + data.reg.fuente);
                        // $('.vtabla1-fecha').html('Actualizado: ' + data.reg.fecha);
                        $('#tabla1').DataTable({
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

        function cargarTablaDistritos(div, provincia) {
            provincia_select = provincia;
            $.ajax({
                url: "{{ route('matriculageneral.ebr.tablas') }}",
                data: {
                    'div': div,
                    "anio": $('#anio').val(),
                    "ugel": $('#ugel').val(),
                    "gestion": $('#gestion').val(),
                    "area": $('#area').val(),
                    "provincia": provincia
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    $('#vtabla2').html(
                        '<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    if (div == "tabla2") {
                        $('#vtabla2').html(data.excel);
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

        function cargarTablaCentroPoblado(div, distrito) {
            distrito_select = distrito;
            $('#modal_centropoblado').modal('show');
            $.ajax({
                url: "{{ route('matriculageneral.ebr.tablas') }}",
                data: {
                    'div': div,
                    "anio": $('#anio').val(),
                    "ugel": $('#ugel').val(),
                    "gestion": $('#gestion').val(),
                    "area": $('#area').val(),
                    "provincia": distrito
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    $('#vtabla3').html(
                        '<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    if (div == "tabla3") {
                        $('#vtabla3').html(data.excel);
                        $('#tabla3').DataTable({
                            "language": table_language,
                        });
                    }
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
        }

        function cargar_distritos() {
            $.ajax({
                url: "{{ route('ubigeo.distrito.25', '') }}/" + $('#vprovincia').val(),
                type: 'GET',
                success: function(data) {
                    $("#vdistrito option").remove();
                    var options = '<option value="0">TODOS</option>';
                    $.each(data, function(index, value) {
                        options += `<option value='${value.id}'>${value.nombre}</option>`
                    });
                    $("#vdistrito").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function descargar1() {
            window.open(
                "{{ route('poblacionprincipal.peru.ucayali.descargar', ['', '', '', '', '']) }}/tabla1/" +
                $('#vanio').val() + "/" + $('#vprovincia').val() + "/" + $('#vdistrito').val() + "/0");
        }

        function verpdf(id) {
            window.open("{{ route('mantenimiento.indicadorgeneral.exportar.pdf', '') }}/" + id);
        };

        function gbar2(div, data, titulo, subtitulo, tituloserie) {
            return Highcharts.chart(div, {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: titulo
                },
                subtitle: {
                    text: subtitulo
                },
                legend: {
                    enabled: true,
                    itemStyle: {
                        //color: "#333333",
                        // cursor: "pointer",
                        fontSize: "11px",
                        // fontWeight: "normal",
                        // textOverflow: "ellipsis"
                    },
                },
                xAxis: [{
                    categories: data.categoria,
                    reversed: false,
                    labels: {
                        step: 1,
                        style: {
                            fontSize: '11px' // Ajusta el tamaño de la fuente
                        }
                    },
                    lineWidth: 0, // Desactiva la línea del eje X
                    tickWidth: 0, // Desactiva las marcas de graduación (ticks)
                }],
                yAxis: {

                    title: {
                        text: null
                    },
                    labels: {
                        enabled: false,
                        formatter: function() {
                            return Math.abs(this.value) + '';
                        },
                        style: {
                            fontSize: '10px' // Ajusta el tamaño de la fuente
                        }
                    },
                },
                plotOptions: {
                    // series: {
                    //     stacking: 'normal'
                    // }
                    series: {
                        stacking: 'normal',
                        dataLabels: {
                            enabled: true, // Habilita la visualización de los valores en las barras
                            formatter: function() {
                                return Highcharts.numberFormat(Math.abs(this.y),
                                    0); // Formatea los números con separadores de miles
                            },
                            backgroundColor: 'none', // Elimina el fondo de las etiquetas
                            borderRadius: 0, // Opcional: elimina cualquier borde redondeado
                            padding: 0, // Opcional: elimina cualquier relleno
                            style: {
                                color: 'white', // Color del texto
                                textOutline: 'none' // Elimina el contorno del texto
                            }
                        }
 
                    }
                },
                tooltip: {
                    shared: true, 
                    formatter: function() {
                        let tooltipText = '<b>Grupo Etario: ' + this.points[0].key +
                            '</b><br/>'; // Muestra la categoría (edad)
                        this.points.forEach(function(point) {
                            tooltipText += point.series.name + ': ' + Highcharts.numberFormat(Math.abs(
                                point.y), 0) + '<br/>';
                        });
                        return tooltipText;
                    }
                },
                colors: ['#5eb9a0', '#ef5350', '#f5bd22', '#ef5350'],
                series: [{
                    name: 'Hombres',
                    data: data.men,
                    // color: '#66BB6A'
                }, {
                    name: 'Mujeres',
                    data: data.women,
                    // color: '#388E3C'
                }],
                credits: {
                    enabled: false
                }
            });
        }        
        
        function gColumnx(div, data, titulo, subtitulo, tooltip) {
            return Highcharts.chart(div, {
                chart: {
                    type: 'column'
                },
                colors: ['#5eb9a0', '#ef5350', '#f5bd22', '#ef5350'],
                title: {
                    text: titulo
                },
                subtitle: {
                    text: subtitulo //null // Si no necesitas un subtítulo, puedes dejarlo como null
                },
                xAxis: {
                    categories: data.categoria, //
                    crosshair: true,
                    labels: {
                        style: {
                            fontSize: '11px' // Ajusta el tamaño de la fuente
                        }
                    },
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: null // Puedes agregar un título si lo necesitas
                    },
                    labels: {
                        style: {
                            fontSize: '11px' // Ajusta el tamaño de la fuente
                        }
                    },
                },
                tooltip: {
                    shared: true, // Muestra los valores de todas las series en el mismo tooltip
                    formatter: function() {
                        let tooltipText = '<b>' + tooltip + ': ' + this.points[0].key +
                            '</b><br/>'; // Muestra la categoría (año)
                        this.points.forEach(function(point) {
                            tooltipText += point.series.name + ': ' + Highcharts.numberFormat(Math.abs(
                                point.y), 0) + '<br/>';
                        });
                        return tooltipText;
                    }
                },
                plotOptions: {
                    column: {
                        stacking: data.serie.length > 1 ? 'normal' : null, // Apila las columnas
                        dataLabels: {
                            enabled: true,
                            formatter: function() {
                                return Highcharts.numberFormat(Math.abs(this.y),
                                    0); // Formatea los números con separadores de miles
                            },
                            style: {
                                color: data.serie.length > 1 ? 'white' : 'black',
                                textOutline: 'none',
                                fontSize: '10px'
                            }
                        }
                    }
                },
                series: data.serie,
                legend: {
                    enabled: data.serie.length > 1,
                    itemStyle: {
                        //color: "#333333",
                        // cursor: "pointer",
                        fontSize: "11px",
                        // fontWeight: "normal",
                        // textOverflow: "ellipsis"
                    },
                },
                credits: {
                    enabled: false,
                    text: 'Fuente: RENIEC - PADRÓN NOMINAL | Actualizado: JULIO 2024',
                    href: null,
                    position: {
                        align: 'center',
                        verticalAlign: 'bottom',
                        x: 0,
                        y: -5
                    },
                    style: {
                        color: '#666',
                        fontSize: '10px',
                        textAlign: 'center'
                    }
                }
            });
        }
        
        function gPie2(div, serie, titulo, subtitulo) {
            return Highcharts.chart(div, {
                chart: {
                    type: 'pie',
                    // borderColor: '#CCC', // Borde gris claro
                    // borderWidth: 2, // Ancho del borde
                    // plotShadow: true // Sombra alrededor del gráfico
                },
                title: {
                    text: titulo
                },
                subtitle: {
                    text: subtitulo
                },
                tooltip: {
                    headerFormat: '<span style="font-size: 11px">{point.key}</span><br/>',
                    pointFormatter: function() {
                        return this.series.name + ': <b>' +
                            Highcharts.numberFormat(this.y, 0) +
                            '</b><br>Porcentaje: <b>' +
                            Highcharts.numberFormat(this.percentage, 1) +
                            '%</b>';
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '{point.name} <br><b>{point.percentage:.1f} %</b>',
                            style: {
                                fontWeight: 'bold'
                            }
                        },
                        showInLegend: false
                    }
                },
                series: serie,
                credits: {
                    enabled: false,
                }
            });
        }

        function maps01(div, data, titulo, subtitulo) {
            return Highcharts.mapChart(div, {
                chart: {
                    // map: 'countries/pe/pe-allx'
                    // map: 'countries/pe/pe-pv-all'
                    map: mapData
                },

                title: {
                    text: titulo, //'Reportes de Mapa'
                },

                subtitle: {
                    text: subtitulo, //'Un descripción de reportes'
                    style: {
                        fontSize: '11px'
                    }
                },

                mapNavigation: {
                    enabled: true,
                    buttonOptions: {
                        verticalAlign: 'top'
                    }
                },

                colorAxis: {
                    // min: 150000,
                    // max: 15000000,
                    // tickPixelInterval: 5000000,
                    mixColor: "#e6ebf5",
                    manColor: "#003399",
                    /*maxColor: '#F1EEF6',
                    minColor: '#900037'*/
                    // legend: {
                    //     enabled: false // Desactiva la leyenda de color
                    // }
                },

                series: [{
                    data: data,
                    name: 'Población',
                    states: {
                        hover: {
                            color: '#BADA55'
                        }
                    },
                    // dataLabels: {
                    //     enabled: true,
                    //     // format: '{point.value}°',
                    //     format: '{point.name}'
                    // }
                    borderColor: '#cac9c9',
                    // borderWidth: 1.5,

                    dataLabels: {
                        enabled: true,
                        // format: '{point.name} {point.value}',
                        // format: '{point.name}',
                        useHTML: true, // Permite el uso de etiquetas HTML
                        // format: '<div style="text-align:center;">{point.name}<br><span style="font-size:12px;">{point.value}</span></div>', // Centra y da un salto de línea

                        // color: '#FFFFFF',
                        // style: {
                        //     fontSize: '10px',
                        //     fontWeight: 'bold'
                        // }

                        format: '<div style="text-align:center;">{point.name}<br><span style="font-size:12px;">{point.value:,0f}</span></div>',
                        style: {
                            fontSize: '10px',
                            fontWeight: 'bold',
                            color: '#FFFFFF',
                            textShadow: '0px 0px 3px #000000' // Aplica sombra negra para simular el borde
                        }
                    },
                    // legend: {
                    //     enabled: false
                    // },
                    point: {
                        // events: {
                        //     // click: function() {
                        //     //     // alert('Departamento: ' + this.name + '\nPoblación: ' + this.value);
                        //     //     // console.log(this.properties['fips']);
                        //     //     var codigo=this.properties['fips'].substring(2);
                        //     //     console.log(codigo);
                        //     //     $('#vdepartamento').val(codigo);
                        //     //     cargarCards();
                        //     // }
                        //     click: function() {
                        //         let point = this;
                        //         let code = point.properties['hc-key'];

                        //         // Remover selección previa
                        //         if (selectedCode) {
                        //             this.series.chart.series[0].points.forEach(function(p) {
                        //                 if (p.properties['hc-key'] === selectedCode) {
                        //                     p.update({
                        //                         color: Highcharts.getOptions().colors[
                        //                             0] // Color original
                        //                     });
                        //                 }
                        //             });
                        //         }

                        //         // Marcar el punto seleccionado
                        //         point.update({
                        //             color: '#FF0000' // Color de selección
                        //         });

                        //         // Almacenar el código del departamento seleccionado
                        //         selectedCode = code;

                        //         alert('Código: ' + code + '\nDepartamento: ' + point.name +
                        //             '\nPoblación: ' + point.value);
                        //     }
                        // }
                        events: {
                            mouseOver: function() {
                                if (!originalColors[this.properties['hc-key']]) {
                                    originalColors[this.properties['hc-key']] = this
                                        .color; // Almacena el color original
                                }
                            },
                            click: function() {
                                let point = this;
                                let code = point.properties['hc-key'];

                                // Remover selección previa
                                // console.log('selectedCode e:' + code);
                                if (selectedCode) {
                                    this.series.chart.series[0].points.forEach(function(p) {
                                        if (p.properties['hc-key'] === selectedCode) {
                                            p.update({
                                                color: originalColors[selectedCode] ||
                                                    Highcharts.getOptions().colors[
                                                        0] // Color original
                                            });
                                        }
                                    });
                                }

                                // Marcar el punto seleccionado
                                point.update({
                                    color: '#bada55' // Color de selección
                                });

                                // Almacenar el código del departamento seleccionado
                                selectedCode = code;

                                // alert('Código: ' + code + '\nDepartamento: ' + point.name +
                                //     '\nPoblación: ' + point.value);

                                var codigo = this.properties['fips'].substring(2);
                                // console.log(codigo);
                                $('#vprovincia').val(codigo);
                                cargar_distritos();
                                cargarCards();
                            },
                            dblclick: function() {
                                alert("asda");
                                // let point = this;
                                // let code = point.properties['hc-key'];

                                // alert('Doble clic en: ' + point.name + '\nCódigo: ' + code +
                                //     '\nPoblación: ' + point.value);

                            }
                        }
                    }
                }],
                tooltip: {
                    formatter: function() {
                        return '<b>' + this.point.name + '</b><br>' +
                            'Población: ' + Highcharts.numberFormat(this.point.value, 0);
                    }
                },

                // tooltip: {
                //     backgroundColor: 'none',
                //     borderWidth: 0,
                //     shadow: false,
                //     useHTML: true,
                //     padding: 0,
                //     pointFormat: '<span class="f32"><span class="flag ' +
                //         '{point.properties.hc-key}">' +
                //         '</span></span> {point.name}<br>' +
                //         '<span style="font-size:30px">{point.value}/km²</span>',
                //     positioner: function() {
                //         return {
                //             x: 0,
                //             y: 250
                //         };
                //     }
                // },
                credits: {
                    enabled: false
                },
            });
        }
    </script>

    {{-- jrmt-mapero --}}
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highmaps.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    {{-- <script src="https://code.highcharts.com/mapdata/countries/pe/pe-all.js"></script> --}}

    {{-- <script src="{{ asset('/') }}public/pe-pv-states.js"></script> --}}
    {{-- <script src="{{ asset('/') }}public/us-ct-all.js"></script> --}}
    <script src="{{ asset('/') }}public/us-ct-ally.js"></script>
    <script src="{{ asset('/') }}public/us-ct-allz.js"></script>
    {{-- <script src="{{ asset('/') }}public/pe-allx.js"></script> --}}

    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script> --}}
    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script> --}}

    <!-- optional -->
    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/offline-exporting.js"></script> --}}
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>

    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script> --}}
@endsection
