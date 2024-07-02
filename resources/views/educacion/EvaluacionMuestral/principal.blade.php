@extends('layouts.main', ['titlePage' => 'INDICADOR'])
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
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header bg-success-0">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-orange-0 btn-xs"
                            onclick="location.href=`{{ route('logrosaprendizaje.evaluacionmuestral.iiee') }}`"
                            title='XXX'><i class="fas fa-file"></i> Instituciones Educativas</button>
                        <button type="button" class="btn btn-orange-0 btn-xs" onclick="location.reload()"
                            title='ACTUALIZAR'><i class=" fas fa-history"></i>
                            Actualizar</button>{{-- {{ route('indicador.nuevos.01.print') }} --}}
                        {{-- <button type="button" class="btn btn-orange-0 btn-xs" onclick="printer()"
                                    title='IMPRIMIR'><i class="fa fa-print"></i></button> --}}

                    </div>
                    <h3 class="card-title text-white">
                        logros de aprendizaje - evaluación muestral
                    </h3>
                </div>
                <div class="card-body pb-0">
                    <div class="form-group row align-items-center vh-5">
                        <div class="col-lg-5 col-md-5 col-sm-5">
                            <h5 class="page-title font-12">Fuente: UMC - MINEDU, {{ $actualizado }}</h5>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1">
                            <select id="vanio" name="vanio" class="form-control form-control-sm"
                                onchange="cargarnivel();cargarCards();">
                                <option value="0">AÑO</option>
                                @foreach ($anios as $item)
                                    <option value="{{ $item->anio }}" {{ $item->anio == $aniomax ? 'selected' : '' }}>
                                        {{ $item->anio }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <select id="vnivel" name="vnivel" class="form-control form-control-sm"
                                onchange="cargargrado();cargarCards();">
                                <option value="0">NIVEL</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <select id="vgrado" name="vgrado" class="form-control form-control-sm"
                                onchange="cargarcurso();cargarCards();">
                                <option value="0">GRADO</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <select id="vcurso" name="vcurso" class="form-control form-control-sm"
                                onchange="cargarCards();">
                                <option value="0">CURSO</option>
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
                        {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class="" width="70%" height="70%"> --}}
                        <i class="mdi mdi-lightbulb font-35 text-dark"></i>
                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="card1"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">
                                <span onclick="" data-toggle="modal" data-target="#ver_mediapromedio">
                                    <i class="mdi mdi-rotate-180 mdi-alert-circle" style="color:#43beac;"></i>
                                </span>
                                Media Promedio
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
                        {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class="" width="70%" height="70%"> --}}
                        <i class="mdi mdi-chart-bar font-35 text-dark"></i>
                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="card2"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">
                                <span onclick="" data-toggle="modal" data-target="#ver_logros">
                                    <i class="mdi mdi-rotate-180 mdi-alert-circle" style="color:#43beac;"></i>
                                </span>
                                Logro Satisfactorio
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
                        {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class="" width="70%" height="70%"> --}}
                        <i class="fas fa-user-friends font-35 text-dark"></i>
                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="card3"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">
                                Estudiantes Evaluados
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
                        {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class="" width="70%" height="70%"> --}}
                        <i class="fas fa-school font-35 text-dark"></i>
                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="card4"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">
                                II.EE Evaluadas
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
                    {{-- <h3 class="text-black text-center font-weight-normal font-11"></h3> --}}
                </div>
                <div class="card-body p-0">
                    <figure class="highcharts-figure p-0 m-0">
                        <div id="anal1" style="height: 20rem"></div>
                    </figure>
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
                        <div id="anal2" style="height: 20rem"></div>
                    </figure>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent p-0">
                    {{-- <h3 class="text-black text-center font-weight-normal font-11"></h3> --}}
                </div>
                <div class="card-body p-0">
                    <figure class="highcharts-figure p-0 m-0">
                        <div id="anal3" style="height: 20rem"></div>
                    </figure>
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
                        <div id="anal4" style="height: 20rem"></div>
                    </figure>
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
                        Resultados de los logros de aprendizaje por provincia, según niveles de logro en <span
                            id="vtabla1-title"></span>
                    </h3>
                </div>
                <div class="card-body pt-0">
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
                        <button type="button" class="btn btn-primary btn-xs" onclick="cargartabla1_1()"
                            title='Actualizar Tabla'><i class=" fas fa-history"></i> Actualizar</button>
                        <button type="button" class="btn btn-success btn-xs" onclick="descargar2()"><i
                                class="fa fa-file-excel"></i> Descargar</button>
                    </div>
                    <h3 class="text-black font-14">Resultados de los logros de aprendizaje por distrito, según niveles de
                        logro en <span id="vtabla1_1-title"></span></h3>
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" id="vtabla1_1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="ver_mediapromedio" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header py-0">
                    {{-- <h5 class="modal-title"></h5> --}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <img src="{{ asset('/') }}public/img/la-em-info1.jpeg" alt="" class="img-fluid">
                </div>
                {{-- <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
             </div> --}}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="ver_logros" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header py-0">
                    {{-- <h5 class="modal-title"></h5> --}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <img src="{{ asset('/') }}public/img/la-em-info2.jpeg" alt="" class="img-fluid">
                </div>
                {{-- <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
         </div> --}}
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        var ugel_select = 0;
        var vprovincia = 0;
        $(document).ready(function() {
            Highcharts.setOptions({
                lang: {
                    thousandsSep: ","
                }
            });
            cargarnivel();
            cargarCards();
        });

        function cargarCards() {
            cargarCardsDiv('head');
            cargarCardsDiv('anal1');
            cargarCardsDiv('anal2');
            cargarCardsDiv('anal3');
            cargarCardsDiv('anal4');
            cargarCardsDiv('tabla1');
            cargarCardsDiv('tabla1_1');
        }

        function cargarCardsDiv(div) {
            $.ajax({
                url: "{{ route('logrosaprendizaje.evaluacionmuestral.reporte') }}",
                data: {
                    'div': div,
                    "anio": $('#vanio').val(),
                    "nivel": $('#vnivel').val(),
                    "grado": $('#vgrado').val(),
                    "curso": $('#vcurso').val(),
                    "provincia": vprovincia,
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    if (div == "head") {
                        $('#card1').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                        $('#card2').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                        $('#card3').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                        $('#card4').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                    } else if (div == "tabla1") {
                        $('#vtabla1').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                    } else if (div == "tabla1_1") {
                        $('#vtabla1_1').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                    } else {
                        $('#' + div).html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                    }
                },
                success: function(data) {
                    // console.log('data');
                    // console.log(data);
                    if (div == "head") {
                        $('#card1').text(data.card1);
                        $('#card2').text(data.card2 + '%');
                        $('#card3').text(data.card3);
                        $('#card4').text(data.card4);

                    } else if (div == "anal1") {
                        gColumn1(div, data.categoria, data.data, '',
                            'Resultado de logros de aprendizaje por años, según nivel de desempeño', '');
                    } else if (div == "anal2") {
                        // console.log(data.categoria);
                        gColumn2(div, data.categoria, data.data, '',
                            'Resultado de logros de aprendizaje por tipo de gestión, según niveles de logro',
                            '');
                    } else if (div == "anal3") {
                        gColumn2(div, data.categoria, data.data, '',
                            'Resultado de logros de aprendizaje por área geográfica, según niveles de logro',
                            '');
                    } else if (div == "anal4") {
                        gColumn2(div, data.categoria, data.data, '',
                            'Resultado de logros de aprendizaje por sexo, según niveles de logro',
                            '');
                    } else if (div == "tabla1") {
                        $('#vtabla1-title').html($('#vcurso option:selected').text());
                        $('#vtabla1').html(data.excel);
                        $('#tabla1').DataTable({
                            "paging": false, // Desactiva la paginación
                            "searching": false, // Desactiva la búsqueda
                            info: false,
                            "ordering": true, // Mantiene la funcionalidad de ordenar
                        });
                        // $('#tabla1').DataTable({
                        //     responsive: true,
                        //     autoWidth: false,
                        //     ordered: true,
                        //     searching: false,
                        //     bPaginate: false,
                        //     info: false,
                        //     language: table_language,
                        // });
                    } else if (div == "tabla1_1") {
                        $('#vtabla1_1-title').html($('#vcurso option:selected').text());
                        $('#vtabla1_1').html(data.excel);
                        $('#tabla1_1').DataTable({
                            "paging": false, // Desactiva la paginación
                            "searching": false, // Desactiva la búsqueda
                            info: false,
                            "ordering": true // Mantiene la funcionalidad de ordenar
                        });
                    }

                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
        }

        function subcargarcard(div, provincia) {
            vprovincia = provincia;
            cargarCardsDiv(div);
        }

        function cargartabla1_1(){
            vprovincia = 0;
            cargarCardsDiv('tabla1_1');
        }

        function cargarnivel() {
            $.ajax({
                url: "{{ route('logrosaprendizaje.evaluacionmuestral.cargarnivel', '') }}/" + $('#vanio').val(),
                type: 'GET',
                success: function(data) {
                    $("#vnivel option").remove();
                    var options = data.length == 1 ? '' : ''; // '<option value="0">NIVEL</option>';
                    $.each(data, function(index, value) {
                        //ss = (id == value.id ? "selected" : "");
                        options += "<option value='" + value.nivel + "'>" + value.nivel +
                            "</option>"
                    });
                    $("#vnivel").append(options);

                    // if (data.length == 1)
                    cargargrado();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargargrado() {
            $.ajax({
                url: "{{ route('logrosaprendizaje.evaluacionmuestral.cargargrado', ['', '']) }}/" +
                    $('#vanio').val() + '/' + $('#vnivel').val(),
                type: 'GET',
                success: function(data) {
                    $("#vgrado option").remove();
                    var options = data.length == 1 ? '' : ''; //'<option value="0">GRADO</option>';
                    $.each(data, function(index, value) {
                        //ss = (id == value.id ? "selected" : "");
                        options += "<option value='" + value.grado + "'>" + value.grado +
                            "</option>"
                    });
                    $("#vgrado").append(options);

                    // if (data.length == 1)
                    cargarcurso();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarcurso() {
            $.ajax({
                url: "{{ route('logrosaprendizaje.evaluacionmuestral.cargarcurso', ['', '', '']) }}/" +
                    $('#vanio').val() + '/' + $('#vnivel').val() + '/' + $('#vgrado').val(),
                type: 'GET',
                success: function(data) {
                    $("#vcurso option").remove();
                    var options = data.length == 1 ? '' : ''; // '<option value="0">CURSO</option>';
                    $.each(data, function(index, value) {
                        //ss = (id == value.id ? "selected" : "");
                        options += "<option value='" + value.id + "'>" + value.curso +
                            "</option>"
                    });
                    $("#vcurso").append(options);

                    cargarCards();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function descargar1() {
            window.open(
                "{{ route('logrosaprendizaje.evaluacionmuestral.reporte.export', ['', '', '', '', '', '']) }}/tabla1/" +
                $('#vanio').val() + "/" + $('#vnivel').val() + "/" + $('#vgrado').val() + "/" + $('#vcurso').val() +
                "/0");
        }

        function descargar2() {
            window.open(
                "{{ route('logrosaprendizaje.evaluacionmuestral.reporte.export', ['', '', '', '', '', '']) }}/tabla1_1/" +
                $('#vanio').val() + "/" + $('#vnivel').val() + "/" + $('#vgrado').val() + "/" + $('#vcurso').val() +
                "/0");
        }

        function verpdf(id) {
            window.open("{{ route('mantenimiento.indicadorgeneral.exportar.pdf', '') }}/" + id);
        };

        function gColumn1(div, categoria, data, titulo, subtitulo, tituloserie) {

            Highcharts.chart(div, {
                chart: {
                    type: 'column'
                },
                // colors: ['#939393', '#f5bd22', '#ef5350', '#5eb9aa'],
                colors: ['#5eb9aa', '#ef5350', '#f5bd22', '#939393'],
                title: {
                    text: ''
                },
                subtitle: {
                    text: subtitulo
                },
                xAxis: {
                    categories: categoria, //['2019', '2022', '2023']
                },
                yAxis: {
                    min: 0,
                    max: 100,
                    title: {
                        text: ''
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.1f} %</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                legend: {
                    reversed: true
                },
                plotOptions: {
                    series: {
                        stacking: 'normal',
                        dataLabels: {
                            enabled: true,
                            format: '{point.y}%' // Muestra el valor con un símbolo de porcentaje
                        }
                    }
                },
                series: data,
                // [{
                //     name: 'Previo al inicio',
                //     data: [30, 25, 20],
                //     // color: '#CCCCCC'
                // }, {
                //     name: 'En inicio',
                //     data: [15, 16, 20],
                //     // color: '#FF5733'
                // }, {
                //     name: 'En proceso',
                //     data: [25, 20, 20],
                //     // color: '#FFC300'
                // }, {
                //     name: 'Satisfactorio',
                //     data: [30, 40, 38],
                //     // color: '#33FF57'
                // }]
                exporting: {
                    enabled: true
                },
                credits: false,
            });
        }

        function gColumn2(div, categoria, data, titulo, subtitulo, tituloserie) {
            Highcharts.chart(div, {
                chart: {
                    type: 'column'
                },
                // colors: ['#5eb9aa', '#ef5350', '#f5bd22', '#939393'],
                colors: ['#939393', '#f5bd22', '#ef5350', '#5eb9aa'],
                title: {
                    text: ''
                },
                subtitle: {
                    text: subtitulo
                },
                xAxis: {
                    categories: categoria, // ['PUBLICO', 'PRIVADO'],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: ''
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.1f} %</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.1f} %'
                        }
                    }
                },
                series: data,
                //  [{
                //     name: 'Previo al inicio',
                //     data: [5, 10]

                // }, {
                //     name: 'En inicio',
                //     data: [10, 15]

                // }, {
                //     name: 'En proceso',
                //     data: [20, 25]

                // }, {
                //     name: 'Satisfactorio',
                //     data: [30, 40]

                // }]
                exporting: {
                    enabled: true
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
