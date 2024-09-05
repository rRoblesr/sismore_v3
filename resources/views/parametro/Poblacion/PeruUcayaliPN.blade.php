@extends('layouts.main', ['titlePage' => 'INDICADOR'])
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
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
                    <h3 class="card-title text-white">POBLACIÓN DE NIÑOS Y NIÑAS MENORES DE 6 AÑOS DEL DEPARTAMENTO DE
                        UCAYALI</h3>
                </div>
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <h4 class="page-title font-12">Fuente: Reniec - Padrón Nominal</h4>
                        </div>
                        <div class="col-lg-2 col-md-1 col-sm-1  ">
                            <div class="custom-select-container">
                                <label for="vanio">AÑO</label>
                                <select id="vanio" name="vanio" class="form-control form-control-sm"
                                    onchange="cargar_mes();">
                                    @foreach ($anios as $item)
                                        <option value="{{ $item->anio }}"
                                            {{ $item->anio == date('Y') ? 'selected' : '' }}>
                                            {{ $item->anio }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-1 col-sm-1  ">
                            <div class="custom-select-container">
                                <label for="vmes">MES</label>
                                <select id="vmes" name="vmes" class="form-control form-control-sm"
                                    onchange="cargar_provincia();">
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-1 col-sm-1  ">
                            <div class="custom-select-container">
                                <label for="vprovincia">PROVINCIA</label>
                                <select id="vprovincia" name="vprovincia" class="form-control form-control-sm"
                                    onchange="cargar_distrito();">
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-1 col-sm-1  ">
                            <div class="custom-select-container">
                                <label for="vdistrito">DISTRITO</label>
                                <select id="vdistrito" name="vdistrito" class="form-control form-control-sm"
                                    onchange="cargarCards();">
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
                            <p class="mb-0 mt-1 text-truncate">Población Total</p>
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
                            <p class="mb-0 mt-1 text-truncate">Población Niños</p>
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
                            <p class="mb-0 mt-1 text-truncate">Población Niñas</p>
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
                            <p class="mb-0 mt-1 text-truncate">CNV Electronico</p>
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
                    <div id="anal1" style="height: 20rem"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    {{-- <h3 class="text-black text-center font-weight-normal font-11"></h3> --}}
                </div>
                <div class="card-body p-0">
                    <div id="anal2" style="height: 20rem"></div>
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
                        Población de niños y niñas menores de 6 años por tipo de seguro salud, según sexo y edades
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

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-success btn-xs" onclick="descargar1()"><i
                                class="fa fa-file-excel"></i> Descargar</button>
                    </div>
                    <h3 class="text-black font-14">
                        Población de niños y niñas menores de 6 años por distrito, según sexo y edades
                    </h3>
                </div>
                <div class="card-body pt-0 pb-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" id="vtabla2">
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
        // var distrito_select = 0;
        var anal1;
        var anal2;
        let selectedCode = null;
        let originalColors = {};

        $(document).ready(function() {
            Highcharts.setOptions({
                lang: {
                    thousandsSep: ","
                }
            });
            cargar_mes();

        });

        function cargarCards() {
            panelGraficas('head');
            panelGraficas('anal1');
            panelGraficas('anal2');
            panelGraficas('tabla1');
            panelGraficas('tabla2');
        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('poblacionprincipal.peru.ucayali.pn.tablas') }}",
                data: {
                    'div': div,
                    "anio": $('#vanio').val(),
                    "mes": $('#vmes').val(),
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
                        $('#' + div).html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span>');
                    } else if (div == "anal2") {
                        $('#' + div).html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span>');
                    } else if (div == "tabla1") {
                        $('#v' + div).html(
                            '<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span>');
                    } else if (div == "tabla2") {
                        $('#v' + div).html(
                            '<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span>');
                    }

                },
                success: function(data) {
                    if (div == 'head') {
                        $('#card1').text(data.card1);
                        $('#card2').text(data.card2);
                        $('#card3').text(data.card3);
                        $('#card4').text(data.card4);
                    } else if (div == "anal1") {
                        anal1 = gColumnx(div, data.info, '',
                            'Población de niños y niñas menores de 6 años, según sexo, periodo 2019- 2024',
                            'Año')
                    } else if (div == "anal2") {
                        anal2 = gColumnx(div, data.info, '',
                            'Población de niños y niñas menores de 6 años, según sexo', 'Etapa Vida')
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
                    }
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
        }

        function cargar_mes() {
            $.ajax({
                url: "{{ route('poblacionprincipal.peru.ucayali.pn.mes') }}",
                data: {
                    anio: $('#vanio').val()
                },
                type: 'GET',
                success: function(data) {
                    $("#vmes option").remove();
                    var options = '<option value="0">TODOS</option>';
                    $.each(data.mes, function(index, value) {
                        ss = (value.id == data.selected ? "selected" : "");
                        options += `<option value='${value.id}' ${ss}>${value.mes}</option>`;
                    });
                    $("#vmes").append(options);
                    cargar_provincia();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargar_provincia() {
            $.ajax({
                url: "{{ route('poblacionprincipal.peru.ucayali.pn.provincia') }}",
                data: {
                    anio: $('#vanio').val(),
                    mes: $('#vmes').val()
                },
                type: 'GET',
                success: function(data) {
                    $("#vprovincia option").remove();
                    var options = '<option value="0">TODOS</option>';
                    $.each(data.provincia, function(index, value) {
                        // ss = (value.id == data.selected ? "selected" : "");
                        options += `<option value='${value.id}'>${value.nombre}</option>`;
                    });
                    $("#vprovincia").append(options);
                    cargar_distrito();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargar_distrito() {
            $.ajax({
                url: "{{ route('poblacionprincipal.peru.ucayali.pn.distrito') }}",
                data: {
                    anio: $('#vanio').val(),
                    mes: $('#vmes').val(),
                    provincia: $('#vprovincia').val()
                },
                type: 'GET',
                success: function(data) {
                    $("#vdistrito option").remove();
                    var options = '<option value="0">TODOS</option>';
                    $.each(data.distrito, function(index, value) {
                        // ss = (value.id == data.selected ? "selected" : "");
                        options += `<option value='${value.id}'>${value.nombre}</option>`;
                    });
                    $("#vdistrito").append(options);
                    cargarCards();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function descargar1() {
            window.open("{{ url('/') }}/MatriculaGeneral/EBR/Excel/tabla1/" + $('#anio').val() + "/" + $('#ugel')
                .val() + "/" + $('#gestion').val() + "/" + $('#area').val() + "/0");
        }

        function descargar2() {
            window.open("{{ url('/') }}/MatriculaGeneral/EBR/Excel/tabla2/" + $('#anio').val() + "/" + $('#ugel')
                .val() + "/" + $('#gestion').val() + "/" + $('#area').val() + "/" + provincia_select);
        }

        function descargar3() {
            window.open("{{ url('/') }}/MatriculaGeneral/EBR/Excel/tabla3/" + $('#anio').val() + "/" + $('#ugel')
                .val() + "/" + $('#gestion').val() + "/" + $('#area').val() + "/" + distrito_select);
        }

        function verpdf(id) {
            window.open("{{ route('mantenimiento.indicadorgeneral.exportar.pdf', '') }}/" + id);
        };

        function gLinea(div, data, titulo, subtitulo) {
            return Highcharts.chart(div, {
                chart: {
                    type: 'line',
                    // borderRadius: 10,
                    // borderWidth: 1,
                    // borderColor: '#000'
                },
                title: {
                    text: titulo
                },
                subtitle: {
                    text: subtitulo
                },
                xAxis: {
                    categories: data.categoria,
                    labels: {
                        style: {
                            fontSize: '10px' // Ajusta el tamaño de la fuente
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: ''
                    },
                    // max: 8,
                    // tickInterval: 1,
                    labels: {
                        style: {
                            fontSize: '10px' // Ajusta el tamaño de la fuente
                        }
                    }
                },
                series: [{
                    name: 'Población',
                    data: data.serie,
                    marker: {
                        symbol: 'circle',
                        radius: 4
                    },
                    color: '#d2232a'
                }],
                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: true,
                            // format: '{point.y:.1f} mil',
                            style: {
                                color: '#000'
                            },
                            align: 'right',
                            crop: false,
                            overflow: 'none'
                        }
                    }
                },
                tooltip: {
                    // pointFormat: '{series.name}: <b>{point.y:.1f} mil</b>'
                },
                legend: {
                    enabled: false // Ocultar la leyenda
                },
                credits: {
                    enabled: false
                }
            });
        }

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

                        // dataLabels: {
                        //     enabled: true,
                        //     formatter: function() {
                        //         let point = this.point;
                        //         let value = Math.abs(this.y);
                        //         let shapeArgs = point.shapeArgs;
                        //         let barWidth = shapeArgs.width; // Ancho de la barra
                        //         let dataLabelWidth = this.series.chart.renderer.fontMetrics().h * (value
                        //             .toString().length * 0.6); // Ancho estimado del texto de la etiqueta

                        //         // Si el ancho del texto es mayor al ancho de la barra, lo posicionamos afuera
                        //         if (dataLabelWidth > barWidth) {
                        //             this.align = 'right';
                        //             this.x = this.series.options.stacking === 'normal' ? 5 : 10;
                        //         } else {
                        //             this.align = 'center';
                        //             this.x = 0;
                        //         }

                        //         return Highcharts.numberFormat(value, 0);
                        //     },
                        //     inside: true, // Se coloca dentro por defecto
                        //     overflow: 'none', // No permitir que sobresalga fuera del gráfico
                        //     crop: false, // Evita que las etiquetas se recorten
                        //     style: {
                        //         color: 'black',
                        //         textOutline: 'none'
                        //     }
                        // }

                        // dataLabels: {
                        //     enabled: true,
                        //     formatter: function() {
                        //         let point = this.point;
                        //         let value = Math.abs(this.y);
                        //         let barWidth = point.shapeArgs.width; // Ancho de la barra

                        //         // Crear un SVG temporary para calcular el ancho del texto
                        //         let svgText = this.series.chart.renderer.text(
                        //             Highcharts.numberFormat(value, 0),
                        //             0,
                        //             0
                        //         ).add(this.series.group);

                        //         let textWidth = svgText.getBBox().width; // Obtener el ancho del texto
                        //         svgText.destroy(); // Eliminar el texto temporal

                        //         // Verificar si el ancho del texto es mayor al ancho de la barra
                        //         if (textWidth > barWidth) {
                        //             this.align = 'right';
                        //             this.x = this.series.options.stacking === 'normal' ? 5 : 10;
                        //         } else {
                        //             this.align = 'center';
                        //             this.x = 0;
                        //         }

                        //         return Highcharts.numberFormat(value, 0);
                        //     },
                        //     inside: true, // Se coloca dentro por defecto
                        //     overflow: 'none', // No permitir que sobresalga fuera del gráfico
                        //     crop: false, // Evita que las etiquetas se recorten
                        //     style: {
                        //         color: 'black',
                        //         textOutline: 'none'
                        //     }
                        // }

                        // dataLabels: {
                        //     enabled: true,
                        //     formatter: function() {
                        //         let point = this.point;
                        //         let value = Math.abs(this.y);
                        //         let barWidth = point.shapeArgs.width;

                        //         let svgText = this.series.chart.renderer.text(
                        //             Highcharts.numberFormat(value, 0),
                        //             0,
                        //             0
                        //         ).add(this.series.group);

                        //         let textWidth = svgText.getBBox().width;
                        //         svgText.destroy();

                        //         if (textWidth > barWidth) {
                        //             this.align = 'right';
                        //             this.x = this.series.options.stacking === 'normal' ? 5 : 10;
                        //         } else {
                        //             this.align = 'center';
                        //             this.x = 0;
                        //         }

                        //         return Highcharts.numberFormat(value, 0);
                        //     },
                        //     inside: true,
                        //     overflow: 'none',
                        //     crop: false,
                        //     style: {
                        //         color: 'black',
                        //         textOutline: 'none'
                        //     }
                        // }
                    }
                },
                tooltip: {
                    shared: true, // Muestra los valores de todas las series en el mismo tooltip
                    // formatter: function() {
                    //     return '<b>' + this.series.name + ', edad ' + this.point.category + '</b><br/>' +
                    //         'Población: ' + Highcharts.numberFormat(Math.abs(this.point.y), 0);
                    // },
                    formatter: function() {
                        let tooltipText = '<b>Grupo Etéreo:' + this.x +
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
                    //name: 'Share',
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

        function gPie2(div, serie, titulo, subtitulo) {
            return Highcharts.chart(div, {
                chart: {
                    type: 'pie',
                    borderColor: '#CCC', // Borde gris claro
                    borderWidth: 2, // Ancho del borde
                    plotShadow: true // Sombra alrededor del gráfico
                },
                title: {
                    text: titulo
                },
                subtitle: {
                    text: subtitulo
                },
                tooltip: {
                    // pointFormat: '{series.name}: <b>{point.y:.0f}</b><br>Porcentaje: <b>{point.percentage:.1f}%</b>'
                    // pointFormatter: function() {return this.series.name + ': <b>' +Highcharts.numberFormat(this.y, 0, ',', '.') +'</b><br>Porcentaje: <b>' +Highcharts.numberFormat(this.percentage, 1, ',', '.') +'%</b>';},
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

        function gPie2x(div, serie, titulo, subtitulo) {
            // const colors = ["#5eb9aa", "#f5bd22", "#e65310"];
            return Highcharts.chart(div, {
                chart: {
                    type: 'pie',
                    borderColor: '#CCC', // Borde gris claro
                    borderWidth: 2, // Ancho del borde
                    plotShadow: true // Sombra alrededor del gráfico
                },
                title: {
                    text: titulo
                },
                subtitle: {
                    text: subtitulo
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.y:.0f}</b><br>Porcentaje: <b>{point.percentage:.1f}%</b>'
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
                // [{
                //     name: 'Porcentaje',
                //     colorByPoint: true,
                //     data: [{
                //         name: 'Adulto',
                //         y: 20.3,
                //         color: '#00CED1' // Color del sector (Turquesa)
                //     }, {
                //         name: 'Adulto Mayor',
                //         y: 20.3,
                //         color: '#FFA500' // Color del sector (Naranja)
                //     }, {
                //         name: 'Joven',
                //         y: 20.3,
                //         color: '#8A2BE2' // Color del sector (Violeta)
                //     }, {
                //         name: 'Adolescente',
                //         y: 20.3,
                //         color: '#FF0000' // Color del sector (Rojo)
                //     }, {
                //         name: 'Niño',
                //         y: 15.8,
                //         color: '#1E90FF' // Color del sector (Azul)
                //     }]
                // }]
                credits: {
                    enabled: false,
                }
            });
            // Highcharts.chart('anal4', {
            //     chart: {
            //         type: 'pie'
            //     },
            //     title: {
            //         text: 'Porcentaje de la Población Estimada, según etapa de vida'
            //     },
            //     tooltip: {
            //         pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            //     },
            //     plotOptions: {
            //         pie: {
            //             allowPointSelect: true,
            //             cursor: 'pointer',
            //             dataLabels: {
            //                 enabled: true,
            //                 format: '<b>{point.name}</b>: {point.percentage:.1f} %',
            //                 distance: -50, // Para que los labels estén dentro de la porción del gráfico
            //                 style: {
            //                     color: 'white',
            //                     textOutline: '0px contrast'
            //                 }
            //             },
            //             showInLegend: true
            //         }
            //     },
            //     series: [{
            //         name: 'Población',
            //         colorByPoint: true,
            //         data: [{
            //             name: 'Niño',
            //             y: 10,
            //             color: '#1E90FF' // Azul
            //         }, {
            //             name: 'Adolescente',
            //             y: 13,
            //             color: '#FF4500' // Rojo
            //         }, {
            //             name: 'Joven',
            //             y: 20,
            //             color: '#800080' // Morado
            //         }, {
            //             name: 'Adulto',
            //             y: 27,
            //             color: '#FFD700' // Amarillo
            //         }, {
            //             name: 'Adulto Mayor',
            //             y: 30,
            //             color: '#40E0D0' // Turquesa
            //         }]
            //     }]
            // });
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
                        let tooltipText = '<b>' + tooltip + ': ' + this.x +
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
                title: {
                    text: titulo
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        fontSize: '11px',
                    }
                },
                yAxis: {
                    title: {
                        text: titulovetical
                    },
                    min: 0,
                    labels: {
                        style: {
                            fontSize: '10px'
                        }
                    }
                },
                xAxis: {
                    categories: data.cat,
                    /* accessibility: {
                        rangeDescription: 'Range: 2010 to 2017'
                    } */
                    labels: {
                        style: {
                            fontSize: '10px'
                        }
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
                    name: 'Matriculados',
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
                    enabled: true,
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
                    text: subtitulo,
                    style: {
                        fontSize: '11px',
                    }
                },
                colors: ["#5eb9aa", "#f5bd22", "#e65310"],
                yAxis: {
                    title: {
                        text: titulovetical
                    },
                    min: 0,
                    labels: {
                        style: {
                            fontSize: '10px'
                        }
                    }
                },
                xAxis: {
                    categories: data.cat,
                    accessibility: {
                        rangeDescription: 'Range: 2010 to 2017'
                    },
                    labels: {
                        style: {
                            fontSize: '10px'
                        }
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
                series: data.dat,
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
                            },
                            enabled: false
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

        function gAnidadaColumn2(div, categoria, series, titulo, subtitulo, maxBar) {
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
                            },
                            enabled: false
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
                                // if (this.colorIndex == 1)
                                return this.y + " %";
                                // else
                                //     return Highcharts.numberFormat(this.y, 0);
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
                    enabled: false
                },
                credits: false,
            });
        }

        function gbar(div, categoria, series, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'bar',
                    //marginLeft: 50,
                    //marginBottom: 90
                },
                colors: ['#5eb9aa', '#ef5350'],
                title: {
                    text: titulo,
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        fontSize: '11px',
                    }
                },
                xAxis: {
                    categories: categoria,
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    }
                },
                yAxis: {
                    labels: {
                        enabled: true,
                        style: {
                            //color: Highcharts.getOptions().colors[2],
                            fontSize: '10px',
                        }
                    },
                    title: {
                        text: '',
                        enabled: false,
                    },
                },
                plotOptions: {
                    series: {
                        stacking: 'normal', //normal, overlap, percent,stream
                        pointPadding: 0, //size de colunma
                        borderWidth: 0 //borde de columna
                    },
                    bar: {
                        dataLabels: {
                            enabled: true,
                            inside: true,
                            style: {
                                fontWeight: 'normal',
                                fontSize: '10px',
                                color: 'white',
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
                tooltip: {
                    shared: true,
                },
                credits: {
                    enabled: false
                },
            });
        }

        function gAnidadaColumnx(div, categoria, series, titulo, subtitulo) {
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
                        showInLegend: false,
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
                                } else if (this.y < 101) {
                                    return this.y + "%";
                                } else {
                                    return this.y;
                                }
                            },
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

    {{-- jrmt-mapero --}}
    <script src="https://code.highcharts.com/maps/highmaps.js"></script>
    <script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/mapdata/countries/pe/pe-all.js"></script>

    <script src="{{ asset('/') }}public/pe-pv-states.js"></script>
    <script src="{{ asset('/') }}public/us-ct-all.js"></script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>

    <!-- optional -->
    <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script> --}}
@endsection
