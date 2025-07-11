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
                        <button type="button" class="btn btn-orange-0 btn-xs" onclick="verpdf(6)" title='FICHA TÉCNICA'><i
                                class="fas fa-file"></i> Ficha Técnica</button>
                        <button type="button" class="btn btn-orange-0 btn-xs" onclick="location.reload()"
                            title='ACTUALIZAR'><i class=" fas fa-history"></i>
                            Actualizar</button>
                        {{-- <button type="button" class="btn btn-orange-0 btn-xs" onclick="printer()" title='IMPRIMIR'><i
                                class="fa fa-print"></i></button> --}}

                    </div>
                    <h3 class="card-title text-white">Número de estudiantes matriculados en Educación Básica
                    </h3>
                </div>
                <div class="card-body pb-0">
                    <div class="form-group row align-items-center vh-5">
                        <div class="col-lg-5 col-md-5 col-sm-5">
                            <h5 class="page-title font-12">SIAGIE - MINEDU, {{ $actualizado }}</h5>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1  ">
                            <select id="anio" name="anio" class="form-control font-11" onchange="cargarCards();">
                                <option value="0">AÑO</option>
                                @foreach ($anios as $item)
                                    <option value="{{ $item->anio }}" {{ $item->anio == $aniomax ? 'selected' : '' }}>
                                        {{ $item->anio }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <select id="provincia" name="provincia" class="form-control font-11"
                                onchange="cargarDistritos();cargarCards();">
                                <option value="0">PROVINCIA</option>
                                @foreach ($provincia as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <select id="distrito" name="distrito" class="form-control font-11" onchange="cargarCards();">
                                <option value="0">DISTRITO</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <select id="gestion" name="gestion" class="form-control font-11" onchange="cargarCards();">
                                <option value="0">TIPO DE GESTIÓN</option>
                                <option value="12">PUBLICA</option>
                                <option value="3">PRIVADA</option>
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
                                <span data-plugin="counterup" id="basico"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">Matriculados</p>
                        </div>
                    </div>
                </div>
                <div class="mt-0 font-9">
                    <h6 class="">Avance <span class="float-right" id="ibasico">0%</span></h6>
                    <div class="progress progress-sm m-0">
                        <div class="progress-bar bg-success-0" role="progressbar" aria-valuenow="90" aria-valuemin="0"
                            aria-valuemax="100" style="width: 100%" id="bbasico">
                            <span class="sr-only">0% Complete</span>
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
                                <span data-plugin="counterup" id="ebr"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">
                                <a href="{{ route('matriculageneral.ebr.principal') }}" title="Ir a Matricula EBR">Matricula
                                    EBR</a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-0 font-9">
                    <h6 class="">Avance <span class="float-right" id="iebr">0%</span></h6>
                    <div class="progress progress-sm m-0">
                        <div class="progress-bar bg-success-0" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                            aria-valuemax="100" style="width: 100%" id="bebr">
                            <span class="sr-only">0% Complete</span>
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
                                <span data-plugin="counterup" id="ebe"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">
                                <a href="{{ route('matriculageneral.ebe.principal') }}"
                                    title="Ir a Matricula EBE">Matricula EBE</a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-0 font-9">
                    <h6 class="">Avance <span class="float-right" id="iebe">0%</span></h6>
                    <div class="progress progress-sm m-0">
                        <div class="progress-bar bg-success-0" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                            aria-valuemax="100" style="width: 100%" id="bebe">
                            <span class="sr-only">0% Complete</span>
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
                                <span data-plugin="counterup" id="eba"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">
                                <a href="{{ route('matriculageneral.eba.principal') }}"title="Ir a Matricula EBA">Matricula
                                    EBA</a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-0 font-9">
                    <h6 class="">Avance <span class="float-right" id="ieba">0%</span></h6>
                    <div class="progress progress-sm m-0">
                        <div class="progress-bar bg-success-0" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                            aria-valuemax="100" style="width: 100%" id="beba">
                            <span class="sr-only">0% Complete</span>
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
                    {{-- <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                <span class="anal1-fuente">Fuente:</span>
                                <span class="float-right anal1-fecha">Actualizado:</span>
                            </div> --}}
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
                <div class="card-header border-success-0 bg-transparent p-0">
                    {{-- <h3 class="text-black text-center font-weight-normal font-11"></h3> --}}
                </div>
                <div class="card-body p-0">
                    <figure class="highcharts-figure p-0 m-0">
                        <div id="anal3" style="height: 20rem"></div>
                    </figure>
                    {{-- <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                <span class="anal3-fuente">Fuente:</span>
                                <span class="float-right anal3-fecha">Actualizado:</span>
                            </div> --}}
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
                    {{-- <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                <span class="anal4-fuente">Fuente:</span>
                                <span class="float-right anal4-fecha">Actualizado:</span>
                            </div> --}}
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
                    <h3 class="text-black font-14">Avance de la matricula mensual según unidad de gestion educativa
                        local</h3>
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" id="vtabla1">
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
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-primary btn-xs" onclick="cargarTablaNivel('tabla2', 0)"
                            title='Actualizar Tabla'><i class=" fas fa-history"></i> Actualizar</button>
                        <button type="button" class="btn btn-success btn-xs" onclick="descargar2()"><i
                                class="fa fa-file-excel"></i> Descargar</button>
                    </div>
                    <h3 class="text-black font-14">Avance de la matricula mensual según nivel y modalidad</h3>
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" id="vtabla2">
                            </div>
                        </div>
                    </div>
                    {{-- <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                <span class="float-left vtabla2-fuente">Fuente:</span>
                                <span class="float-right vtabla2-fecha">Actualizado:</span>
                            </div> --}}
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
            cargarCards();
        });

        function cargarCards() {
            $.ajax({
                url: "{{ route('indicador.nuevos.01.head') }}",
                data: {
                    "anio": $('#anio').val(),
                    "provincia": $('#provincia').val(),
                    "distrito": $('#distrito').val(),
                    "gestion": $('#gestion').val(),
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    $('#basico').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                    $('#ebr').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                    $('#ebe').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                    $('#eba').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    $('#basico').text(data.valor1);
                    $('#ebr').text(data.valor2);
                    $('#ebe').text(data.valor3);
                    $('#eba').text(data.valor4);
                    $('#ibasico').text(data.ind1 + '%');
                    $('#iebr').text(data.ind2 + '%');
                    $('#iebe').text(data.ind3 + '%');
                    $('#ieba').text(data.ind4 + '%');
                    //$('#bbasico').css('width','100px');
                    $('#bbasico').css('width', data.ind1 + '%')
                        .removeClass('bg-success-0 bg-orange-0 bg-warning-0')
                        .addClass(data.ind1 > 95 ? 'bg-success-0' : (data.ind1 > 75 ? 'bg-warning-0' :
                            'bg-orange-0'));
                    $('#bebr').css('width', data.ind2 + '%')
                        .removeClass('bg-success-0 bg-orange-0 bg-warning-0')
                        .addClass(data.ind2 > 95 ? 'bg-success-0' : (data.ind2 > 75 ? 'bg-warning-0' :
                            'bg-orange-0'));
                    $('#bebe').css('width', data.ind3 + '%')
                        .removeClass('bg-success-0 bg-orange-0 bg-warning-0')
                        .addClass(data.ind3 > 95 ? 'bg-success-0' : (data.ind3 > 75 ? 'bg-warning-0' :
                            'bg-orange-0'));
                    $('#beba').css('width', data.ind4 + '%')
                        .removeClass('bg-success-0 bg-orange-0 bg-warning-0')
                        .addClass(data.ind4 > 95 ? 'bg-success-0' : (data.ind4 > 75 ? 'bg-warning-0' :
                            'bg-orange-0'));
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });

            panelGraficas('anal1');
            panelGraficas('anal2');
            panelGraficas('anal3');
            panelGraficas('anal4');
            panelGraficas('tabla1');
            panelGraficas('tabla2');
        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('indicador.nuevos.01.tabla') }}",
                data: {
                    'div': div,
                    "anio": $('#anio').val(),
                    "provincia": $('#provincia').val(),
                    "distrito": $('#distrito').val(),
                    "gestion": $('#gestion').val(),
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
                    if (div == "anal1") {
                        gAnidadaColumn(div,
                            data.info.categoria, data.info.series, '',
                            'Número de estudiantes matriculados en educación básica, ' +
                            data.reg.periodo,
                            data.info.maxbar
                        );
                        $('.anal1-fuente').html('Fuente: ' + data.reg.fuente);
                        $('.anal1-fecha').html('Actualizado: ' + data.reg.fecha);
                    } else if (div == "anal2") {
                        gLineaBasica(div, data.info, '',
                            'Matricula educativa acumulada mensual en educación básica', '');
                        $('.anal2-fuente').html('Fuente: ' + data.reg.fuente);
                        $('.anal2-fecha').html('Actualizado: ' + data.reg.fecha);
                    } else if (div == "anal3") {
                        gPie2(div, data.info, '', 'Numero de estudiantes matriculados según sexo', '');
                        $('.anal3-fuente').html('Fuente: ' + data.reg.fuente);
                        $('.anal3-fecha').html('Actualizado: ' + data.reg.fecha);
                    } else if (div == "anal4") {
                        gPie(div, data.info, '', 'Numero de estudiantes matriculados según área geográfica',
                            '');
                        $('.anal4-fuente').html('Fuente: ' + data.reg.fuente);
                        $('.anal4-fecha').html('Actualizado: ' + data.reg.fecha);
                    } else if (div == "tabla1") {
                        $('#vtabla1').html(data.excel);
                        $('.vtabla1-fuente').html('Fuente: ' + data.reg.fuente);
                        $('.vtabla1-fecha').html('Actualizado: ' + data.reg.fecha);
                        $('#tabla1').DataTable({
                            responsive: true,
                            autoWidth: false,
                            ordered: true,
                            searching: false,
                            bPaginate: false,
                            info: false,
                            language: table_language,
                        });
                    } else if (div == "tabla2") {
                        $('#vtabla2').html(data.excel);
                        $('.vtabla2-fuente').html('Fuente: ' + data.reg.fuente);
                        $('.vtabla2-fecha').html('Actualizado: ' + data.reg.fecha);
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

        function descargar1() {
            window.open("{{ url('/') }}/INDICADOR/Home/01/Excel/tabla1/" + $('#anio').val() + "/" + $('#provincia')
                .val() + "/" + $('#distrito').val() + "/" + $('#gestion').val() + "/0");
        }

        function descargar2() {
            window.open("{{ url('/') }}/INDICADOR/Home/01/Excel/tabla2/" + $('#anio').val() + "/" + $('#provincia')
                .val() + "/" + $('#distrito').val() + "/" + $('#gestion').val() + "/" + ugel_select);
        }

        function verpdf(id) {
            window.open("{{ route('mantenimiento.indicadorgeneral.exportar.pdf', '') }}/" + id);
        };

        function printer() {
            window.print();
            // var escalaPersonalizada = 0.6; // Cambia esto al valor de escala deseado
            // var style = document.createElement('style');
            // style.type = 'text/css';
            // style.media = 'print';
            // // style.innerHTML = '@page { size: auto; margin: 0mm; transform: scale(' + escalaPersonalizada +
            // //     '); } @media print { body { transform: scale(' + escalaPersonalizada + '); } }';
            // style.innerHTML = '@page { transform: scale(' + escalaPersonalizada +
            //     '); } @media print { body { transform: scale(' + escalaPersonalizada + '); } }';
            // document.head.appendChild(style);
            // window.print();
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

        function gAnidadaColumnxxx(div, categoria, series, titulo, subtitulo, maxBar) {
            // Validaciones de entrada
            if (!div || !categoria || !series) {
                console.error('Parámetros requeridos faltantes');
                return;
            }

            const rango = categoria.length;
            const porMaxBar = maxBar * 0.5;

            // Configuración mejorada del gráfico
            const chartConfig = {
                chart: {
                    zoomType: 'xy',
                    backgroundColor: '#ffffff',
                    style: {
                        fontFamily: 'Arial, sans-serif'
                    }
                },

                // Paleta de colores más moderna
                colors: ['#2E8B57', '#FF6B6B', '#FFD93D', '#6BCF7F', '#4ECDC4'],

                title: {
                    text: titulo,
                    style: {
                        fontSize: '16px',
                        fontWeight: 'bold',
                        color: '#333333'
                    }
                },

                subtitle: {
                    text: subtitulo,
                    style: {
                        fontSize: '12px',
                        color: '#666666'
                    }
                },

                xAxis: [{
                    categories: categoria,
                    crosshair: {
                        width: 2,
                        color: '#cccccc',
                        dashStyle: 'dash'
                    },
                    labels: {
                        style: {
                            fontSize: '11px',
                            color: '#666666'
                        },
                        rotation: categoria.length > 8 ? -45 : 0 // Rotar si hay muchas categorías
                    },
                    gridLineWidth: 1,
                    gridLineColor: '#f0f0f0'
                }],

                yAxis: [{ // Eje primario - Matriculados
                        max: maxBar > 0 ? maxBar + porMaxBar : null,
                        min: 0,
                        labels: {
                            enabled: true,
                            style: {
                                fontSize: '11px',
                                color: '#2E8B57'
                            },
                            formatter: function() {
                                return Highcharts.numberFormat(this.value, 0);
                            }
                        },
                        title: {
                            text: 'Número de Matriculados',
                            style: {
                                fontSize: '12px',
                                fontWeight: 'bold',
                                color: '#2E8B57'
                            }
                        },
                        gridLineColor: '#f0f0f0'
                    },
                    { // Eje secundario - Porcentajes
                        gridLineWidth: 0,
                        labels: {
                            enabled: true,
                            style: {
                                fontSize: '11px',
                                color: '#FF6B6B'
                            },
                            formatter: function() {
                                return this.value + '%';
                            }
                        },
                        title: {
                            text: 'Porcentaje (%)',
                            style: {
                                fontSize: '12px',
                                fontWeight: 'bold',
                                color: '#FF6B6B'
                            }
                        },
                        min: 0,
                        max: 120,
                        opposite: true
                    }
                ],

                series: series,

                plotOptions: {
                    series: {
                        showInLegend: true,
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            formatter: function() {
                                if (this.series.yAxis.opposite) { // Eje secundario (porcentajes)
                                    return this.y.toFixed(1) + "%";
                                } else { // Eje primario (números)
                                    return Highcharts.numberFormat(this.y, 0);
                                }
                            },
                            style: {
                                fontWeight: 'normal',
                                fontSize: '10px',
                                textOutline: 'none'
                            }
                        }
                    },
                    column: {
                        borderRadius: 3,
                        shadow: false
                    },
                    line: {
                        marker: {
                            enabled: true,
                            radius: 4
                        },
                        lineWidth: 3
                    }
                },

                tooltip: {
                    shared: true,
                    useHTML: true,
                    backgroundColor: 'rgba(255, 255, 255, 0.95)',
                    borderColor: '#cccccc',
                    borderRadius: 8,
                    shadow: true,
                    formatter: function() {
                        let tooltip = `<b>${this.x}</b><br/>`;
                        this.points.forEach(function(point) {
                            const valor = point.series.yAxis.opposite ?
                                point.y.toFixed(1) + '%' :
                                Highcharts.numberFormat(point.y, 0);
                            tooltip +=
                                `<span style="color:${point.color}">●</span> ${point.series.name}: <b>${valor}</b><br/>`;
                        });
                        return tooltip;
                    }
                },

                legend: {
                    itemStyle: {
                        color: "#333333",
                        cursor: "pointer",
                        fontSize: "11px",
                        fontWeight: "normal"
                    },
                    itemHoverStyle: {
                        color: '#000000'
                    },
                    align: 'center',
                    verticalAlign: 'bottom',
                    layout: 'horizontal'
                },

                exporting: {
                    enabled: true,
                    buttons: {
                        contextButton: {
                            menuItems: [
                                'viewFullscreen',
                                'separator',
                                'downloadPNG',
                                'downloadJPEG',
                                'downloadPDF',
                                'downloadSVG',
                                'separator',
                                'downloadCSV',
                                'downloadXLS'
                            ]
                        }
                    }
                },

                credits: {
                    enabled: false
                },

                // Configuración responsive
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            title: {
                                style: {
                                    fontSize: '14px'
                                }
                            },
                            subtitle: {
                                style: {
                                    fontSize: '10px'
                                }
                            },
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                }
            };

            // Crear el gráfico
            try {
                return Highcharts.chart(div, chartConfig);
            } catch (error) {
                console.error('Error al crear el gráfico:', error);
                document.getElementById(div).innerHTML = '<p>Error al cargar el gráfico</p>';
            }
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
