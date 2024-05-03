@extends('layouts.main', ['titlePage' => 'INDICADOR'])
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
    <style>
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header bg-success-0 cabecera">
                        <div class="card-widgets">
                            {{-- <button type="button" class="btn btn-orange-0 btn-xs"
                                onclick="location.href=`{{ route('matriculageneral.niveleducativo.principal') }}`"
                                title=''><i class="fas fa-align-justify"></i> Nivel Educativo</button> --}}
                            {{-- <button type="button" class="btn btn-orange-0 btn-xs" onclick="verpdf(6)"
                                    title='FICHA TÉCNICA'><i class="fas fa-file"></i> Ficha Técnica</button> --}}
                            <button type="button" class="btn btn-orange-0 btn-xs" onclick="location.reload()"
                                title='ACTUALIZAR'><i class=" fas fa-history"></i> Actualizar</button>
                            {{-- <button type="button" class="btn btn-orange-0 btn-xs" onclick="location.reload()"
                                    title='IMPRIMIR'><i class="fa fa-print"></i></button> --}}
                        </div>
                        <h3 class="card-title text-white">Porcentaje de locales educativos conectados a red de agua
                            potable
                        </h3>
                    </div>
                    <div class="card-body py-0">
                        <div class="form-group row align-items-center vh-5">
                            <div class="col-lg-5 col-md-5 col-sm-5">
                                <h5 class="page-title font-11">CENSO EDUCATIVO-MINEDU, <br>{{ $actualizado }}</h5>
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
                                <select id="provincia" name="provincia" class="form-control btn-xs font-11"
                                    onchange="cargarDistritos();cargarCards();">
                                    <option value="0">PROVINCIA</option>
                                    @foreach ($provincia as $item)
                                        <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <select id="distrito" name="distrito" class="form-control btn-xs font-11"
                                    onchange="cargarCards();">
                                    <option value="0">DISTRITO</option>
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

                            <input type="hidden" id="servicio" name="servicio" value="1">

                            {{-- <div class="col-lg-2 col-md-2 col-sm-2">
                                <select id="servicio" name="servicio" class="form-control btn-xs font-11"
                                    onchange="cargarCards();">
                                    <option value="1">AGUA</option>
                                    <option value="2">DESAGUE</option>
                                    <option value="3">LUZ</option>
                                    <option value="4">TRES SERVICIOS</option>
                                    <option value="5">INTERNET</option>
                                </select>
                            </div> --}}

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
                        <div class="media-body align-self-center card1">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold">
                                    <span data-plugin="counterup"></span>
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
                            <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                                width="70%" height="70%">
                        </div>
                        <div class="media-body align-self-center card2">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold">
                                    <span data-plugin="counterup"></span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">Total de II.EE</p>
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
                        <div class="media-body align-self-center card3">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold">
                                    <span data-plugin="counterup"></span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">II.EE con Agua</p>
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
                        <div class="media-body align-self-center card4">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold">
                                    <span data-plugin="counterup"></span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">II.EE sin Agua</p>
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
                            <div id="anal3" style="height: 47.5rem"></div>
                        </figure>
                        {{-- <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                            <span class="ana-l1-fuente">Fuente:</span>
                            <span class="float-right ana-l1-fecha">Actualizado:</span>
                        </div> --}}
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card card-border border border-plomo-0 vtabla1">
                    <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                        <h3 class="text-black font-14">Locales Educativos conectados a red de agua potable, según Distritos
                        </h3>
                    </div>
                    <div class="card-body pt-0 pb-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive" id="vtabla1">
                                </div>
                            </div>
                        </div>
                        {{-- <div class="font-weight-bold text-muted ml-2 mr-2 font-9 p-0">
                            <span class="float-left vtabla1-fuente">Fuente: </span>
                            <span class="float-right vtabla1-fecha">Actualizado:</span>
                        </div> --}}
                    </div>
                </div>
            </div>

        </div>

        <div class="row d-none">
            <div class="col-lg-12">
                <div class="card card-border border border-plomo-0 vtabla2">
                    <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                        <div class="card-widgets">
                            <button type="button" class="btn btn-success btn-xs" onclick="descargar2()"><i
                                    class="fa fa-file-excel"></i> Descargar</button>
                        </div>
                        <h3 class="text-black font-14">Locales Educativos conectados, según Provincia</h3>
                    </div>
                    <div class="card-body pt-0 pb-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive" id="vtabla2">
                                </div>
                            </div>
                        </div>
                        {{-- <div class="font-weight-bold text-muted ml-2 mr-2 font-9 p-0">
                            <span class="float-left vtabxla1-fuente">Fuente: </span>
                            <span class="float-right vtaxbla1-fecha">Actualizado:</span>
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
                            <button type="button" class="btn btn-success btn-xs" onclick="descargar3()"><i
                                    class="fa fa-file-excel"></i> Descargar</button>
                        </div>
                        <h3 class="text-black font-14">Locales Educativos conectados</h3>
                    </div>
                    <div class="card-body pt-0 pb-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive" id="vtabla3">
                                </div>
                            </div>
                        </div>
                        {{-- <div class="font-weight-bold text-muted ml-2 mr-2 font-9 p-0">
                            <span class="float-left vtabxla1-fuente">Fuente: </span>
                            <span class="float-right vtaxbla1-fecha">Actualizado:</span>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap modal -->
        <div id="modal_centropoblado" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="text-black font-14">
                            Número de estudiantes matriculados en educación básica regular por
                            centro poblado, según nivel educativo
                        </h5>
                        &nbsp;
                        <button type="button" class="btn btn-success btn-xs text-right" onclick="descargar3()">
                            <i class="fa fa-file-excel"></i> Descargar</button>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive" id="vtabla3">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Bootstrap modal -->
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        var distrito_select = 0;
        var distrito_select = 0;
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
            // tservicio = $('#servicio').val();
            switch ($('#servicio').val()) {
                case '1':
                    $('.cabecera h3').text('Porcentajes de Locales Escolares Públicos conectados a red de Agua Potable');
                    $('.vtabla1 h3').text('Locales Educativos conectados a red de agua potable, según Distritos');
                    $('.vtabla2 h3').text('Locales Educativos conectados a red de agua potable, según Provincia');
                    break;
                case '2':
                    $('.cabecera h3').text('Porcentajes de Locales Educativos conectados a red de Desague');
                    $('.vtabla1 h3').text('Locales Educativos conectados a red de desague, según Distritos');
                    $('.vtabla2 h3').text('Locales Educativos conectados a red de desague, según Provincia');
                    break;
                case '3':
                    $('.cabecera h3').text('Porcentajes de Locales Educativos conectados a red de Electricidad');
                    $('.vtabla1 h3').text('Locales Educativos conectados a red de electricidad, según Distritos');
                    $('.vtabla2 h3').text('Locales Educativos conectados a red de electricidad, según Provincia');
                    break;
                case '4':
                    $('.cabecera h3').text('Porcentajes de Locales Educativos con los tres Servicios Basicos');
                    $('.vtabla1 h3').text('Locales Educativos con los tres servicios basicos, según Distritos');
                    $('.vtabla2 h3').text('Locales Educativos con los tres servicios basicos, según Provincia');
                    break;
                case '5':
                    $('.cabecera h3').text('Porcentajes de Locales Educativos que cuentan con acceso a Internet');
                    $('.vtabla1 h3').text('Locales Educativos que cuentan con acceso a Internet, según Distritos');
                    $('.vtabla2 h3').text('Locales Educativos que cuentan con acceso a Internet, según Provincia');
                    break;
                default:
                    break;
            }
            panelGraficas('head');
            panelGraficas('anal1');
            panelGraficas('anal2');
            panelGraficas('anal3');
            panelGraficas('tabla1');
            // panelGraficas('tabla2');
            panelGraficas('tabla3');
        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('serviciosbasicos.aguapotable.tablas') }}",
                data: {
                    'div': div,
                    "anio": $('#anio').val(),
                    "provincia": $('#provincia').val(),
                    "distrito": $('#distrito').val(),
                    "area": $('#area').val(),
                    "servicio": $('#servicio').val(),
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    // if (div == "tabla1") {
                    //     $('#v' + div).html(
                    //         '<span><i class="fa fa-spinner fa-spin"></i></span>');
                    // } else if (div == "tabla2") {
                    //     $('#v' + div).html(
                    //         '<span><i class="fa fa-spinner fa-spin"></i></span>');
                    // } else {
                    //     $('#' + div).html(
                    //         '<span><i class="fa fa-spinner fa-spin"></i></span>');
                    // }
                },
                success: function(data) {
                    if (div == 'head') {
                        $('.card1 span').text(data.valor1 + "%");
                        $('.card2 span').text(data.valor2);
                        $('.card3 span').text(data.valor3);
                        $('.card4 span').text(data.valor4);

                        $('.card3 p').text('II.EE con ' + data.tservicio);
                        $('.card4 p').text('II.EE sin ' + data.tservicio);
                    } else if (div == "anal1") {
                        var anal3titulo = '';
                        switch ($('#servicio').val()) {
                            case '1':
                                anal3titulo =
                                    'Locales Educativos conectados a red de agua potable, según Distritos';
                                break;
                            case '2':
                                anal3titulo =
                                    'Locales Educativos conectados a red de desague, según Distritos';
                                break;
                            case '3':
                                anal3titulo =
                                    'Locales Educativos conectados a red de electricidad, según Distritos';
                                break;
                            case '4':
                                anal3titulo =
                                    'Locales Educativos con los tres servicios basicos, según Distritos';
                                break;
                            case '5':
                                anal3titulo =
                                    'Locales Educativos que cuentan con acceso a Internet, según Distritos';
                                break;
                            default:
                                break;
                        }
                        gAnidadaColumn4(div,
                            data.info.categoria,
                            data.info.series,
                            '',
                            'Distribución de Tabletas y Cargadores Solares, según Nivel Educativo',
                            data.alto
                        );
                    } else if (div == "anal2") {
                        var anal3titulo = '';
                        switch ($('#servicio').val()) {
                            case '1':
                                anal3titulo =
                                    'Locales Educativos conectados a red de agua potable, según Distritos';
                                break;
                            case '2':
                                anal3titulo =
                                    'Locales Educativos conectados a red de desague, según Distritos';
                                break;
                            case '3':
                                anal3titulo =
                                    'Locales Educativos conectados a red de electricidad, según Distritos';
                                break;
                            case '4':
                                anal3titulo =
                                    'Locales Educativos con los tres servicios basicos, según Distritos';
                                break;
                            case '5':
                                anal3titulo =
                                    'Locales Educativos que cuentan con acceso a Internet, según Distritos';
                                break;
                            default:
                                break;
                        }
                        gAnidadaColumn3(div,
                            data.info.categoria,
                            data.info.series,
                            '',
                            'Distribución de Tabletas y Cargadores Solares, según Nivel Educativo'
                        );
                    } else if (div == "anal3") {
                        var anal3titulo = '';
                        switch ($('#servicio').val()) {
                            case '1':
                                anal3titulo =
                                    'Locales Educativos conectados a red de agua potable, según Distritos';
                                break;
                            case '2':
                                anal3titulo =
                                    'Locales Educativos conectados a red de desague, según Distritos';
                                break;
                            case '3':
                                anal3titulo =
                                    'Locales Educativos conectados a red de electricidad, según Distritos';
                                break;
                            case '4':
                                anal3titulo =
                                    'Locales Educativos con los tres servicios basicos, según Distritos';
                                break;
                            case '5':
                                anal3titulo =
                                    'Locales Educativos que cuentan con acceso a Internet, según Distritos';
                                break;
                            default:
                                break;
                        }
                        gbar02(div, [], data.info, '', anal3titulo);
                        // $('.ana-l1-fuente').html('Fuente: ' + data.reg.fuente);
                        // $('.ana-l1-fecha').html('Actualizado: ' + data.reg.fecha);
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
                    } else if (div == "tabla2") {
                        $('#vtabla2').html(data.excel);
                        // $('.vtabla1-fuente').html('Fuente: ' + data.reg.fuente);
                        // $('.vtabla1-fecha').html('Actualizado: ' + data.reg.fecha);
                        $('#tabla2').DataTable({
                            responsive: true,
                            autoWidth: false,
                            ordered: true,
                            searching: false,
                            bPaginate: false,
                            info: false,
                            language: table_language,
                        });
                    } else if (div == "tabla3") {
                        $('#vtabla3').html(data.excel);
                        // $('.vtabla1-fuente').html('Fuente: ' + data.reg.fuente);
                        // $('.vtabla1-fecha').html('Actualizado: ' + data.reg.fecha);
                        $('#tabla3').DataTable({
                            responsive: true,
                            autoWidth: false,
                            // ordered: true,
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


        /* function descargar1() {
            window.open("{{ url('/') }}/MatriculaGeneral/EBR/Excel/tabla1/" + $('#anio').val() + "/" + $('#ugel')
                .val() + "/" + $('#gestion').val() + "/" + $('#area').val() + "/0");
        } */

        function descargar2() {
            window.open("{{ url('/') }}/ServiciosBasicos/Excel/tabla2/" + $('#anio').val() + "/" + $('#ugel')
                .val() + "/" + $('#gestion').val() + "/" + $('#area').val() + "/" + $('#servicio').val());
        }

        function descargar3() {
            window.open("{{ url('/') }}/ServiciosBasicos/Excel/tabla3/" + $('#anio').val() + "/" + $('#ugel')
                .val() + "/" + $('#gestion').val() + "/" + $('#area').val() + "/" + $('#servicio').val());
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
        function gAnidadaColumn4(div, categoria, series, titulo, subtitulo, maxBar) {
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
                            formatter: function() {
                                if (this.colorIndex == 2)
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
        function gAnidadaColumn3(div, categoria, series, titulo, subtitulo) {
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
                        //showInLegend: false,
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            //format: '{point.y:.1f}%',
                            formatter: function() {
                                if (this.colorIndex == 3)
                                    return this.y + " %";
                                else
                                    return Highcharts.numberFormat(this.y, 0);
                            },
                            style: {
                                fontWeight: 'normal',
                                fontSize: '10px',
                            }
                            // style: {
                            //     fontWeight: 'normal',
                            // }
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

        function gbar01(div, categoria, series, titulo, subtitulo) {
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

        function gbar02(div, categoria, series, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'bar'
                },
                colors: ['#5eb9aa', '#f5bd22', '#ef5350'],
                title: {
                    text: titulo, // 'Historic World Population by Region'
                },
                subtitle: {
                    text: subtitulo,
                    /*  'Source: <a ' +
                                            'href="https://en.wikipedia.org/wiki/List_of_continents_and_continental_subregions_by_population"' +
                                            'target="_blank">Wikipedia.org</a>' */
                },
                xAxis: {
                    //categories:categoria,// ['Africa', 'America', 'Asia', 'Europe', 'Oceania'],
                    type: "category",
                    title: {
                        text: '', // null
                    },
                    enabled: false,
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    }
                },
                yAxis: {
                    //min: 0,
                    title: {
                        text: '', // 'Population (millions)',
                        align: 'high'
                    },
                    /* labels: {
                        overflow: 'justify'
                    } */
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    }
                },
                // tooltip: {
                //     valueSuffix: ' %'
                // },
                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true,
                            format: '{point.y}'
                        }
                    }
                },
                legend: {
                    enabled: false, //
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    x: -40,
                    y: 80,
                    floating: true,
                    borderWidth: 1,
                    backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
                    shadow: true
                },

                //series: series,
                /*  [{
                                    name: 'Year 1990',
                                    data: [631, 727, 3202, 721, 26]
                                }, {
                                    name: 'Year 2000',
                                    data: [814, 841, 3714, 726, 31]
                                }, {
                                    name: 'Year 2010',
                                    data: [1044, 944, 4170, 735, 40]
                                }, {
                                    name: 'Year 2018',
                                    data: [1276, 1007, 4561, 746, 42]
                                }] */
                /* showInLegend: tituloserie != '',
                        name: tituloserie,
                        label: {
                            enabled: false
                        },
                        colorByPoint: false, */
                series: [{
                    name: 'Ejecución',
                    showInLegend: false,
                    label: {
                        enabled: false
                    },
                    data: series,
                    /* [{
                                                name: "Chrome",
                                                y: 63.06,
                                            },
                                            {
                                                name: "Safari",
                                                y: 19.84,
                                            },
                                            {
                                                name: "Firefox",
                                                y: 4.18,
                                            },
                                            {
                                                name: "Edge",
                                                y: 4.12,
                                            },
                                            {
                                                name: "Opera",
                                                y: 2.33,
                                            },
                                            {
                                                name: "Internet Explorer",
                                                y: 0.45,
                                            },
                                            {
                                                name: "Other",
                                                y: 1.582,
                                            }
                                        ] */
                }],
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

    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script> --}}
@endsection
