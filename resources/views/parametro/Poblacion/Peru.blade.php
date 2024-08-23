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

        /* #anal1 {
                                                                                                    position: relative;
                                                                                                } */

        .spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 2em;
            /* Ajusta el tamaño según sea necesario */
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
                        <button class="btn btn-xs btn-primary waves-effect waves-light" data-toggle="modal"
                            data-target="#modal_form" title="Agregar Actas" onclick="abrirnuevo()"> <i
                                class="fa fa-file"></i>
                            Nuevo</button> &nbsp;
                    @endif --}}

                    </div>
                    <h3 class="card-title text-white">POBLACIÓN ESTIMADA Y PROYECTADA 2021-2030</h3>
                </div>
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-lg-7 col-md-4 col-sm-4">
                            <h4 class="page-title font-12">Fuente: INEI</h4>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1  ">
                            <select id="vanio" name="vanio" class="form-control form-control-sm"
                                onchange="cargarCards();">
                                <option value="0">AÑO</option>
                                @foreach ($anios as $item)
                                    <option value="{{ $item->anio }}" {{ $item->anio == date('Y') ? 'selected' : '' }}>
                                        {{ $item->anio }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <select id="vdepartamento" name="vdepartamento" class="form-control form-control-sm"
                                onchange="cargarCards();">
                                <option value="0">DEPARTAMENTO</option>
                                @foreach ($departamento as $item)
                                    <option value="{{ $item->codigo }}"> {{ $item->departamento }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <select id="vsexo" name="vsexo" class="form-control form-control-sm"
                                onchange="cargarCards();">
                                <option value="0">SEXO</option>
                                <option value="1">HOMBRE</option>
                                <option value="2">MUJER</option>
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
                        <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                            width="70%" height="70%">
                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="card1"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">Población Perú</p>
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
                        <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                            width="70%" height="70%">
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
                    <div class="text-center">
                        <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                            width="70%" height="70%">
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
                    <div class="text-center">
                        <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                            width="70%" height="70%">
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
                    <figure class="highcharts-figure p-0 m-0">
                        <div id="anal1" style="height: 35rem"></div>
                    </figure>
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
                    <figure class="highcharts-figure p-0 m-0">
                        <div id="anal2" style="height: 35rem"></div>
                    </figure>
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
                    <figure class="highcharts-figure p-0 m-0">
                        <div id="anal3" style="height: 20rem"></div>
                    </figure>
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
                    <figure class="highcharts-figure p-0 m-0">
                        <div id="anal4" style="height: 20rem"></div>
                    </figure>
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
        // var distrito_select = 0;
        var anal1;
        var anal2;
        var anal3;
        var anal4;
        var anal5;
        var anal6;
        $(document).ready(function() {
            Highcharts.setOptions({
                lang: {
                    thousandsSep: ","
                }
            });
            cargarCards();
        });

        function cargarCards() {
            panelGraficas('head');
            panelGraficas('anal1');
            panelGraficas('anal2');
            panelGraficas('anal3');
            panelGraficas('anal4');
            panelGraficas('tabla1');
        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('poblacionprincipal.peru.tablas') }}",
                data: {
                    'div': div,
                    "anio": $('#vanio').val(),
                    "departamento": $('#vdepartamento').val(),
                    "sexo": $('#vsexo').val(),
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
                    if (div == 'head') {
                        $('#card1').text(data.card1);
                        $('#card2').text(data.card2);
                        $('#card3').text(data.card3);
                        $('#card4').text(data.card4);

                    } else if (div == "anal1") {
                        var xxx = [
                            ["pe-lo", 1],
                            ["pe-uc", 2],
                            ["pe-ic", 3],
                            ["pe-ju", 4],
                            ["pe-tu", 5],
                            ["pe-ar", 6],
                            ["pe-cj", 7],
                            ["pe-ay", 8],
                            ["pe-ta", 9],
                            ["pe-am", 10],
                            ["pe-pi", 11],
                            ["pe-cs", 12],
                            ["pe-lb", 13],
                            ["pe-hv", 14],
                            ["pe-sm", 15],
                            ["pe-ll", 16],
                            ["pe-mq", 17],
                            ["pe-ap", 18],
                            ["pe-cl", 19],
                            ["pe-hc", 20],
                            ["pe-md", 21],
                            ["pe-pa", 22],
                            ["pe-3341", 23],
                            ["pe-lr", 24],
                            ["pe-an", 25],
                            ["pe-145", 26]
                        ]
                        anal1 = maps01(div, xxx, '', '');
                    } else if (div == "anal2") {
                        anal2 = gbar2(div, data.info, '',
                            'Pirámide poblacional, según sexo  y grupo etario', '');
                    } else if (div == "anal3") {
<<<<<<< HEAD
                        // var dataxx = {
                        //     categoria: ['2021', '2022', '2023', '2024', '2025', '2026', '2027', '2028', '2029', '2030'],
                        //     serie: [5, 6, 6.5, 6.2, 6.8, 7, 7.1, 6.9, 6.7, 6.4],
                        // }

                        // var datax = {
                        //     categoria: ["2021", "2022", "2023", "2024", "2025", "2026", "2027", "2028", "2029", "2030"],
                        //     serie: [330, 334, 337, 340, 344, 347, 350, 352, 355, 358]
                        // }
                        // console.log(datax);
=======
>>>>>>> 059e587a5e07ba5902bb90942ee410ce0db68234
                        anal3 = gLinea(div, data.info, '',
                            'Población estimada y proyectada, periodo 2021-2030');
                    } else if (div == "anal4") {
                        anal4 = gLinea(div, data.info, '',
                            'Población estimada de 0 a 5 años, periodo 2021-2030');
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

        function cargarDistritos() {
            $.ajax({
                url: "{{ route('ubigeo.distrito.25', '') }}/" + $('#vprovincia').val(),
                type: 'GET',
                success: function(data) {
                    $("#vdistrito option").remove();
                    var options = '<option value="0">DISTRITO</option>';
                    $.each(data, function(index, value) {
                        //ss = (id == value.id ? "selected" : "");
                        options += "<option value='" + value.id + "'>" + value.nombre +
                            "</option>"
                    });
                    $("#vdistrito").append(options);
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
<<<<<<< HEAD
                            // format: '{point.y:.1f} mil',
=======
                            // format: '{point.y:.1f}',
>>>>>>> 059e587a5e07ba5902bb90942ee410ce0db68234
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
<<<<<<< HEAD
                    // pointFormat: '{series.name}: <b>{point.y:.1f} mil</b>'
=======
                    // pointFormat: '{series.name}: <b>{point.y:.1f}</b>'
>>>>>>> 059e587a5e07ba5902bb90942ee410ce0db68234
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
                xAxis: [{
                    categories: data.categoria,
                    reversed: false,
                    labels: {
                        step: 1
                    },
                    lineWidth: 0, // Desactiva la línea del eje X
                    tickWidth: 0 // Desactiva las marcas de graduación (ticks)
                }],
                yAxis: {

                    title: {
                        text: null
                    },
                    labels: {
                        enabled: false,
                        formatter: function() {
                            return Math.abs(this.value) + '';
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
                                color: 'black', // Color del texto
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
                        let tooltipText = '<b>' + this.x + '</b><br/>'; // Muestra la categoría (edad)
                        this.points.forEach(function(point) {
                            tooltipText += point.series.name + ': ' + Highcharts.numberFormat(Math.abs(
                                point.y), 0) + '<br/>';
                        });
                        return tooltipText;
                    }
                },
                series: [{
                    name: 'Hombres',
                    data: data.men,
                    color: '#66BB6A'
                }, {
                    name: 'Mujeres',
                    data: data.women,
                    color: '#388E3C'
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

        function gColumn1(div, data, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'column'
                },
                title: {
                    text: titulo
                },
                subtitle: {
                    text: subtitulo //null // Si no necesitas un subtítulo, puedes dejarlo como null
                },
                xAxis: {
                    categories: data.categoria, //
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: null // Puedes agregar un título si lo necesitas
                    }
                },
                tooltip: {
                    shared: true, // Muestra los valores de todas las series en el mismo tooltip
                    formatter: function() {
                        let tooltipText = '<b>Año: ' + this.x + '</b><br/>'; // Muestra la categoría (año)
                        this.points.forEach(function(point) {
                            tooltipText += point.series.name + ': ' + Highcharts.numberFormat(Math.abs(
                                point.y), 0) + '<br/>';
                        });
                        return tooltipText;
                    }
                },
                plotOptions: {
                    column: {
                        stacking: 'normal', // Apila las columnas
                        dataLabels: {
                            enabled: true,
                            formatter: function() {
                                return Highcharts.numberFormat(Math.abs(this.y),
                                    0); // Formatea los números con separadores de miles
                            },
                            style: {
                                color: 'black',
                                textOutline: 'none'
                            }
                        }
                    }
                },
                series: [{
                    name: 'Hombre',
                    data: data.men, //
                    color: '#00BFAE' // Color turquesa
                }, {
                    name: 'Mujer',
                    data: data.women, // [22, 25, 28, 25, 27, 24],
                    color: '#EC407A' // Color rosado
                }],
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

        function maps01(div, data, titulo, subtitulo) {
            return Highcharts.mapChart(div, {
                chart: {
                    map: 'countries/pe/pe-all'
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
                    min: 0,
                    max: 30,
                    //tickPixelInterval: 100,
                    maxColor: "#e6ebf5",
                    minColor: "#003399",
                    /*maxColor: '#F1EEF6',
                    minColor: '#900037'*/
                },

                series: [{
                    data: data,
                    name: 'Población',
                    states: {
                        hover: {
                            color: '#BADA55'
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        format: '{point.value}°'
                    }
                }],
                credits: {
                    enabled: false
                },
            });
        }
    </script>

    {{-- jrmt-mapero --}}
    <script src="https://code.highcharts.com/maps/highmaps.js"></script>
    <script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/mapdata/countries/pe/pe-all.js"></script>

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
