@extends('layouts.main', ['titlePage' => 'INDICADOR'])
@section('css')
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css"
        rel="stylesheet"type="text/css" />
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header bg-success-0">
                        <div class="card-widgets">
                            <button type="button" class="btn btn-orange-0 btn-xs"
                                onclick="location.href=`{{ route('matriculageneral.niveleducativo.eba.principal') }}`"
                                title=''><i class="fas fa-align-justify"></i> Nivel Educativo</button>
                            {{-- <button type="button" class="btn btn-orange-0 btn-xs" onclick="verpdf(6)"
                                    title='FICHA TÉCNICA'><i class="fas fa-file"></i> Ficha Técnica</button> --}}
                            <button type="button" class="btn btn-orange-0 btn-xs" onclick="location.reload()"
                                title='ACTUALIZAR'><i class=" fas fa-history"></i> Actualizar</button>
                            {{-- <button type="button" class="btn btn-orange-0 btn-xs" onclick="location.reload()"
                                    title='IMPRIMIR'><i class="fa fa-print"></i></button> --}}
                        </div>
                        <h3 class="card-title text-white">Educación Básica Alternativa</h3>
                    </div>
                    <div class="card-body pb-0">
                        <div class="form-group row align-items-center vh-5">
                            <div class="col-lg-5 col-md-5 col-sm-5">
                                <h5 class="page-title font-12">SIAGIE - MINEDU, {{ $actualizado }}</h5>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1  ">
                                <select id="anio" name="anio" class="form-control btn-xs font-11"
                                    onchange="cargarCards();">
                                    <option value="0">AÑO</option>
                                    @foreach ($anios as $item)
                                        <option value="{{ $item->id }}" {{ $item->anio == $aniomax ? 'selected' : '' }}>
                                            {{ $item->anio }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <select id="ugel" name="ugel" class="form-control btn-xs font-11"
                                    onchange="cargarCards();">
                                    <option value="0">UGEL</option>
                                    @foreach ($ugel as $item)
                                        <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <select id="gestion" name="gestion" class="form-control btn-xs font-11"
                                    onchange="cargarCards();">
                                    <option value="0">TIPO DE GESTIÓN</option>
                                    <option value="12">PUBLICA</option>
                                    <option value="3">PRIVADA</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <select id="area" name="area" class="form-control btn-xs font-11"
                                    onchange="cargarCards();">
                                    <option value="0">ÁMBITO GEOGRÁFICO</option>
                                    @foreach ($area as $item)
                                        <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                    @endforeach
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
                                <p class="mb-0 mt-1 text-truncate">Total Estudiantes</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-0 font-9">
                        <h6 class="">Avance <span class="float-right" id="icard1">0%</span></h6>
                        <div class="progress progress-sm m-0">
                            <div class="progress-bar bg-success-0" role="progressbar" aria-valuenow="90" aria-valuemin="0"
                                aria-valuemax="100" style="width: 100%" id="bcard1">
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
                                    <span data-plugin="counterup" id="card2"></span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">Estudiantes Inicial</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-0 font-9">
                        <h6 class="">Avance <span class="float-right" id="icard2">0%</span></h6>
                        <div class="progress progress-sm m-0">
                            <div class="progress-bar bg-success-0" role="progressbar" aria-valuenow="90"
                                aria-valuemin="0" aria-valuemax="100" style="width: 100%" id="bcard2">
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
                                    <span data-plugin="counterup" id="card3"></span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">Estudiantes Intermedio</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-0 font-9">
                        <h6 class="">Avance <span class="float-right" id="icard3">0%</span></h6>
                        <div class="progress progress-sm m-0">
                            <div class="progress-bar bg-success-0" role="progressbar" aria-valuenow="90"
                                aria-valuemin="0" aria-valuemax="100" style="width: 100%" id="bcard3">
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
                                    <span data-plugin="counterup" id="card4"></span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">Estudiantes Avanzado</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-0 font-9">
                        <h6 class="">Avance <span class="float-right" id="icard4">0%</span></h6>
                        <div class="progress progress-sm m-0">
                            <div class="progress-bar bg-success-0" role="progressbar" aria-valuenow="90"
                                aria-valuemin="0" aria-valuemax="100" style="width: 100%" id="bcard4">
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
                    <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
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
                    <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
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
                    <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
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
                    <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                        {{-- <h3 class="text-black text-center font-weight-normal font-11"></h3> --}}
                    </div>
                    <div class="card-body p-0">
                        <figure class="highcharts-figure p-0 m-0">
                            <div id="anal5" style="height: 20rem"></div>
                        </figure>
                        {{-- <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                            <span class="anal5-fuente">Fuente:</span>
                            <span class="float-right anal5-fecha">Actualizado:</span>
                        </div> --}}
                    </div>
                </div>
            </div>

        </div>

        <div class="row d-none">
            <div class="col-lg-6">
                <div class="card card-border border border-plomo-0">
                    <div class="card-header border-success-0 bg-transparent p-0">
                        {{-- <h3 class="text-black text-center font-weight-normal font-11"></h3> --}}
                    </div>
                    <div class="card-body p-0">
                        <figure class="highcharts-figure p-0 m-0">
                            <div id="anal7" style="height: 20rem"></div>
                        </figure>
                        {{-- <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                            <span class="anal7-fuente">Fuente:</span>
                            <span class="float-right anal7-fecha">Actualizado:</span>
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
                            <div id="anal8" style="height: 20rem"></div>
                        </figure>
                        {{-- <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                            <span class="anal8-fuente">Fuente:</span>
                            <span class="float-right anal8-fecha">Actualizado:</span>
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
                        <h3 class="text-black font-14">Matricula educativa de estudiantes por Provincia, según Nivel
                            Educativo y Sexo</h3>
                    </div>
                    <div class="card-body pt-0 pb-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive" id="vtabla1">
                                </div>
                            </div>
                        </div>
                        {{-- <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                            <span class="vtabla1-fuente">Fuente:</span>
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
                            <button type="button" class="btn btn-primary btn-xs"
                                onclick="cargarTablaDistritos('tabla2', 0)" title='Actualizar Tabla'><i
                                    class=" fas fa-history"></i> Actualizar</button>
                            <button type="button" class="btn btn-success btn-xs" onclick="descargar2()"><i
                                    class="fa fa-file-excel"></i> Descargar</button>
                        </div>
                        <h3 class="text-black font-14">Matricula educativa de estudiantes por Distrito, según Nivel
                            Educativo y Sexo</h3>
                    </div>
                    <div class="card-body pt-0 pb-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive" id="vtabla2">
                                </div>
                            </div>
                        </div>
                        {{-- <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                            <span class="vtabla2-fuente">Fuente:</span>
                            <span class="float-right vtabla2-fecha">Actualizado:</span>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('js')
    <script type="text/javascript">
        var provincia_select = 0;
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
            //panelGraficas('anal4');
            panelGraficas('anal5');
            // panelGraficas('anal6');
            // panelGraficas('anal7');
            // panelGraficas('anal8');
            panelGraficas('tabla1');
            panelGraficas('tabla2');
        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('matriculageneral.eba.tablas') }}",
                data: {
                    'div': div,
                    "anio": $('#anio').val(),
                    "ugel": $('#ugel').val(),
                    "gestion": $('#gestion').val(),
                    "area": $('#area').val(),
                    "provincia": 0
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {

                    if (div == "tabla1") {
                        $('#v' + div).html(
                            '<span><i class="fa fa-spinner fa-spin"></i></span>');
                    } else if (div == "tabla2") {
                        $('#v' + div).html(
                            '<span><i class="fa fa-spinner fa-spin"></i></span>');
                    } else {
                        $('#' + div).html(
                            '<span><i class="fa fa-spinner fa-spin"></i></span>');
                    }
                },
                success: function(data) {
                    if (div == 'head') {
                        $('#card1').text(data.valor1);
                        $('#card2').text(data.valor2);
                        $('#card3').text(data.valor3);
                        $('#card4').text(data.valor4);
                        $('#icard1').text(data.ind1 + '%');
                        $('#icard2').text(data.ind2 + '%');
                        $('#icard3').text(data.ind3 + '%');
                        $('#icard4').text(data.ind4 + '%');
                        //$('#bbasico').css('width','100px');
                        $('#bcard1').css('width', data.ind1 + '%')
                            .removeClass('bg-success-0 bg-orange-0 bg-warning-0')
                            .addClass(data.ind1 > 84 ? 'bg-success-0' : (data.ind1 > 49 ? 'bg-orange-0' :
                                'bg-warning-0'));
                        $('#bcard2').css('width', data.ind2 + '%').removeClass(
                                'bg-success-0 bg-orange-0 bg-warning-0')
                            .addClass(data.ind2 > 84 ? 'bg-success-0' : (data.ind2 > 49 ? 'bg-orange-0' :
                                'bg-warning-0'));
                        $('#bcard3').css('width', data.ind3 + '%').removeClass(
                                'bg-success-0 bg-orange-0 bg-warning-0')
                            .addClass(data.ind3 > 84 ? 'bg-success-0' : (data.ind3 > 49 ? 'bg-orange-0' :
                                'bg-warning-0'));
                        $('#bcard4').css('width', data.ind4 + '%').removeClass(
                                'bg-success-0 bg-orange-0 bg-warning-0')
                            .addClass(data.ind4 > 84 ? 'bg-success-0' : (data.ind4 > 49 ? 'bg-orange-0' :
                                'bg-warning-0'));
                    } else if (div == "anal1") {
                        gAnidadaColumn(div,
                            data.info.categoria, data.info.series, '',
                            'Número de estudiantes matriculados en educación básica Alternativa, período ' +
                            data.reg.periodo,
                            data.info.maxbar
                        );
                        $('.anal1-fuente').html('Fuente: ' + data.reg.fuente);
                        $('.anal1-fecha').html('Actualizado: ' + data.reg.fecha);
                    } else if (div == "anal2") {
                        gLineaBasica(div, data.info, '',
                            'Evolución mensual de la matricula educativa en educación básica Alternativa período 2023',
                            '');
                        $('.anal2-fuente').html('Fuente: ' + data.reg.fuente);
                        $('.anal2-fecha').html('Actualizado: ' + data.reg.fecha);
                    } else if (div == "anal3") {
                        gLineaMultiple(div, data.info, '',
                            'Numero de estudiantes matriculados en educación básica Alternativa, según nivel educativo,periodo 2018-2023',
                            '');
                        $('.anal3-fuente').html('Fuente: ' + data.reg.fuente);
                        $('.anal3-fecha').html('Actualizado: ' + data.reg.fecha);
                    } else if (div == "anal4") {
                        gPie(div, data.info, '',
                            'Numero de estudiantes matriculados en educación básica, según nivel educativo',
                            '');
                        $('.anal4-fuente').html('Fuente: ' + data.reg.fuente);
                        $('.anal4-fecha').html('Actualizado: ' + data.reg.fecha);
                    } else if (div == "anal5") {
                        gPie(div, data.info, '',
                            'Número de estudiantes matriculados en educación básica Alternativa, según sexo',
                            '');
                        $('.anal5-fuente').html('Fuente: ' + data.reg.fuente);
                        $('.anal5-fecha').html('Actualizado: ' + data.reg.fecha);
                    } else if (div == "anal6") {
                        gAnidadaColumn2(div,
                            data.info.categoria, data.info.series, '', '', data.info.maxbar
                        );
                        $('.anal6-fuente').html('Fuente: ' + data.reg.fuente);
                        $('.anal6-fecha').html('Actualizado: ' + data.reg.fecha);
                    } else if (div == "anal7") {
                        gbar(div, data.info.categoria, data.info.series, '',
                            'Número de estudiantes extranjeros matriculados en educación básica por sexo Alternativa, según país de nacimiento',
                            '');
                        $('.anal7-fuente').html('Fuente: ' + data.reg.fuente);
                        $('.anal7-fecha').html('Actualizado: ' + data.reg.fecha);
                    } else if (div == "anal8") {
                        gbar(div, data.info.categoria, data.info.series, '',
                            'Número de estudiantes con discapacidad matriculados en educación básica Alternativa por sexo, según tipo de discapacidad',
                            '');
                        $('.anal8-fuente').html('Fuente: ' + data.reg.fuente);
                        $('.anal8-fecha').html('Actualizado: ' + data.reg.fecha);
                    } else if (div == "tabla1") {
                        provincia_select = 0;
                        $('#vtabla1').html(data.excel);
                        $('.vtabla1-fuente').html('Fuente: ' + data.reg.fuente);
                        $('.vtabla1-fecha').html('Actualizado: ' + data.reg.fecha);
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

        function cargarTablaDistritos(div, provincia) {
            provincia_select = provincia;
            $.ajax({
                url: "{{ route('matriculageneral.eba.tablas') }}",
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
                    }
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
        }

        function cargarTablaCentroPoblado(div, distrito) {
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
            window.open("{{ url('/') }}/MatriculaGeneral/EBA/Excel/tabla1/" + $('#anio').val() + "/" + $('#ugel')
                .val() + "/" + $('#gestion').val() + "/" + $('#area').val() + "/0");
        }

        function descargar2() {
            window.open("{{ url('/') }}/MatriculaGeneral/EBA/Excel/tabla2/" + $('#anio').val() + "/" + $('#ugel')
                .val() + "/" + $('#gestion').val() + "/" + $('#area').val() + "/" + provincia_select);
        }

        function verpdf(id) {
            window.open("{{ route('mantenimiento.indicadorgeneral.exportar.pdf', '') }}/" + id);
        };

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
                        max: 160,
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

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <!-- optional -->
    <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>

    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script> --}}
@endsection
