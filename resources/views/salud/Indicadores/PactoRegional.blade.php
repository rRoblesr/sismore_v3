@extends('layouts.main', ['activePage' => 'usuarios', 'titlePage' => ''])
@section('css')
    <style>
        .tablex thead th {
            padding: 2px;
            text-align: center;
        }

        .tablex thead td {
            padding: 2px;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
        }

        .tablex tbody td,
        .tablex tbody th,
        .tablex tfoot td,
        .tablex tfoot th {
            padding: 2px;
        }

        .fuentex {
            font-size: 10px;
            font-weight: bold;
        }


        .link {
            color: #000000;
        }

        .link:hover {
            color: #0000FF;
        }
    </style>
@endsection

@section('content')
    <div class="content">

        <div class="form-group row align-items-center vh-5">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <h4 class="page-title font-16">PACTO REGIONAL</h4>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
                <select id="provincia" name="provincia" class="form-control btn-xs font-11"
                    onchange="cargarDistritos(),cargarCards();">
                    <option value="0">AÑO</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
                <select id="provincia" name="provincia" class="form-control btn-xs font-11"
                    onchange="cargarDistritos(),cargarCards();">
                    <option value="0">PROVINCIA</option>

                </select>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
                <select id="distrito" name="distrito" class="form-control btn-xs font-11" onchange="cargarCards();">
                    <option value="0">DISTRITO</option>

                </select>
            </div>
        </div>

        <div class="row pricing-plan">
            <div class="col-md-12">
                <div class="row">
                    @foreach ($inds as $key => $item)
                        <div class="col-md-6 col-xl-3">
                            <div class="card text-center">
                                <div class="pricing-header bg-success-0 p-0 rounded-top">
                                    <h5 class="text-white font-14 font-weight-normal"><i class="mdi mdi-shield-cross"
                                            style="font-size: 20px"></i> Indicador {{ $key + 1 }}</h5>
                                    {{-- <h1 class="text-white font-44 font-weight-normal">$19</h1> --}}
                                    {{-- <h5 class="text-white font-17 mt-4">Starter Pack</h5> --}}
                                </div>
                                <div class="pb-4 pl-4 pr-4">
                                    <ul class="list-unstyled mt-0">
                                        <li class="mt-0 pt-0">
                                            {{-- <i class="mdi mdi-finance font-44 text-green-0"></i></li>
                                        <li class="mt-0 pt-0 font-16">Avance</li>
                                        <li class="mt-0 pt-0 font-40 font-weight-bold">98.8 % --}}
                                        <li class="m-0 pt-0">

                                            <figure class="p-0 m-0">
                                                <div id="gra{{ $item->codigo }}"></div>{{-- graDITSALUD01 --}}
                                            </figure>
                                        </li>
                                        </li>
                                        <li class="mt-0 pt-0 font-12">Actualizado: 20/02/2024</li>
                                        <li class="mt-0 pt-0 font-20 font-weight-bold">Meta: 71%</li>
                                        <li class="mt-0 pt-0">
                                            <span class="badge badge-success" style="font-size: 100%">
                                                <i class="mdi mdi-thumb-up"></i> CUMPLE</span>
                                        </li>
                                        <li class="mt-1 pt-1">
                                            <p class="font-12" style="height: 6rem">{{ $item->nombre }}</p>
                                        </li>

                                    </ul>
                                    <div class="mt-1 pt-1">
                                        {{-- <button class="btn btn-primary width-md waves-effect waves-light">Sign Up</button> --}}
                                        <a href="{{ route('salud.indicador.pactoregional.detalle', $item->id) }}"
                                            class="btn btn-warning btn-sm text-dark  width-md waves-effect waves-light">Ver
                                            detalle</a>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end col -->
                    @endforeach

                </div>
                <!-- end row -->
            </div>
            <!-- end Col-10 -->
        </div>
        <!-- end row -->

        <div id="modal_datosindicador" class="modal fade font-10" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-12" id="myModalLabel">Datos del indicador</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="form_datosindicador" name="form" class="form-horizontal"
                            autocomplete="off">
                            @csrf
                            <input type="hidden" id="indicador" name="indicador" value="">
                            <div class="form-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Indicador</label>
                                            <textarea class="form-control" name="indicadornombre" id="indicadornombre" cols="30" rows="2"
                                                placeholder="Definición del indicador"></textarea>
                                            {{-- <input id="indicadornombre" name="indicadornombre" class="form-control"
                                        type="text" placeholder="Nombre del indicador"> --}}
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Definición</label>
                                            <textarea class="form-control" name="indicadordescripcion" id="indicadordescripcion" cols="30" rows="5"
                                                placeholder="Definición del indicador"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Instrumento de gestion</label>
                                            <input id="indicadorinstrumento" name="indicadorinstrumento"
                                                class="form-control" type="text" placeholder="Fuente de datos">
                                            <span class="help-block"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Tipo de indicador</label>
                                            <input id="indicadortipo" name="indicadortipo" class="form-control"
                                                type="text" placeholder="Fuente de datos">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Fuente de datos</label>
                                            <input id="indicadorfuentedato" name="indicadorfuentedato"
                                                class="form-control" type="text" placeholder="Fuente de datos">
                                            <span class="help-block"></span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        {{-- <button type="button" class="btn btn-xs btn-danger waves-effect" data-dismiss="modal">Cerrar</button> --}}
                        {{-- <button type="button" class="btn btn-primary btn-xs waves-effect waves-light" onclick="verpdf(8)">Ficha Tecnica</button> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        var paleta_colores = ['#5eb9aa', '#F9FFFE', '#f5bd22', '#058DC7', '#50B432', '#9D561B', '#DDDF00', '#24CBE5',
            '#64E572', '#9F9655', '#FFF263', '#6AF9C4'
        ];
        $(document).ready(function() {
            Highcharts.setOptions({
                lang: {
                    thousandsSep: ","
                }
            });
            // cargarCards();
            GaugeSeries('graDITSALUD01', 71);
            GaugeSeries('graDITSALUD02', 82);
            GaugeSeries('graDITSALUD03', 92);
            GaugeSeries('graDITSALUD04', 99);
            GaugeSeries('graDITSALUD05', 62);
        });

        function cargarCards() {
            $.ajax({
                url: "{{ route('panelcontrol.educacion.head') }}",
                data: {
                    "provincia": $('#provincia').val(),
                    "distrito": $('#distrito').val(),
                    // "tipogestion": $('#tipogestion').val(),
                    // "ambito": $('#ambito').val(),
                },
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // $('#basico').text(data.valor1);
                    // $('#ebr').text(data.valor2);
                    // $('#ebe').text(data.valor3);
                    // $('#eba').text(data.valor4);
                    // $('#ibasico').text(data.ind1 + '%');
                    // $('#iebr').text(data.ind2 + '%');
                    // $('#iebe').text(data.ind3 + '%');
                    // $('#ieba').text(data.ind4 + '%');
                    // //$('#bbasico').css('width','100px');
                    // $('#bbasico').css('width', data.ind1 + '%')
                    //     .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                    //     .addClass(data.ind1 > 84 ? 'bg-success-0' : (data.ind1 > 49 ? 'bg-warning-0' :
                    //         'bg-orange-0'));
                    // $('#bebr').css('width', data.ind2 + '%').removeClass(
                    //         'bg-success-0 bg-orange-0 bg-warning-0')
                    //     .addClass(data.ind2 > 84 ? 'bg-success-0' : (data.ind2 > 49 ? 'bg-warning-0' :
                    //         'bg-orange-0'));
                    // $('#bebe').css('width', data.ind3 + '%').removeClass(
                    //         'bg-success-0 bg-orange-0 bg-warning-0')
                    //     .addClass(data.ind3 > 84 ? 'bg-success-0' : (data.ind3 > 49 ? 'bg-warning-0' :
                    //         'bg-orange-0'));
                    // $('#beba').css('width', data.ind4 + '%').removeClass(
                    //         'bg-success-0 bg-orange-0 bg-warning-0')
                    //     .addClass(data.ind4 > 84 ? 'bg-success-0' : (data.ind4 > 49 ? 'bg-warning-0' :
                    //         'bg-orange-0'));
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });

            // GaugeSeries('gra01DITSALUD01');

            //panelGraficas('container1');
            //panelGraficas('container2');
            //panelGraficas('container3');
            // panelGraficas('anal1');
            // panelGraficas('anal2');
            // panelGraficas('anal3');
            // panelGraficas('anal4');
            // panelGraficas('siagie001');
            // panelGraficas('censodocente001');
            // panelGraficas('dtanal1');
            // panelGraficas('dtanal2');
            // panelGraficas('dtanal3');
            // panelGraficas('skills001');
            // panelGraficas('skills002');
            // panelGraficas('skills003');
            // panelGraficas('skills004');
            // panelGraficas('skills005');
            // panelGraficas('skills006');
            // panelGraficas('skills007');
            // panelGraficas('skills008');
            // panelGraficas('skills009');
            // panelGraficas('skills010');
            // panelGraficas('tabla1');
            /* panelGraficas('iiee1');
            panelGraficas('iiee2');
            panelGraficas('iiee3');
            panelGraficas('iiee4');
            panelGraficas('iiee5');
            panelGraficas('iiee6'); */
        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('panelcontrol.educacion.graficas') }}",
                data: {
                    'div': div,
                    "anio": 2024,
                    "provincia": $('#provincia').val(),
                    "distrito": $('#distrito').val(),
                    // "tipogestion": $('#tipogestion').val(),
                    // "ambito": $('#ambito').val(),
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    if (div == "siagie001") {
                        $('#' + div).html(
                            '<span><i class="fa fa-spinner fa-spin"></i></span>');
                    } else if (div == "censodocente001") {
                        $('#' + div).html(
                            '<span><i class="fa fa-spinner fa-spin"></i></span>');
                    } else {
                        // $('#' + div).html(
                        //     '<span><i class="fa fa-spinner fa-spin"></i></span>');
                    }
                },
                success: function(data) {
                    switch (div) {
                        case "siagie001":
                            gAnidadaColumn(div,
                                data.info.cat,
                                data.info.dat,
                                '',
                                'Numero de estudiantes matriculados en educacion basica regular, periodo 2018 - 2023',
                                data.info.maxbar
                            );
                            $('#span-siagie001-fuente').html("Fuente: " + data.reg.fuente);
                            $('#span-siagie001-fecha').html("Actualizado: " + data.reg.fecha);
                            break;
                        case "censodocente001":
                            gAnidadaColumn(div,
                                data.info.cat,
                                data.info.dat,
                                '',
                                'Numero de docentes en educacion basica regular, periodo 2018 - 2023',
                                data.info.maxbar
                            );
                            $('#span-censodocente001-fuente').html("Fuente: " + data.reg.fuente);
                            $('#span-censodocente001-fecha').html("Actualizado: " + data.reg.fecha);
                            break;
                        case "container1":
                            gsemidona(div, 0, ['#5eb9aa', '#F9FFFE']);
                            $('#span-container1-fuente').html("Fuente: " + 'MINEDU');
                            $('#span-container1-fecha').html("Actualizado: " + '31/12/2022');
                            break;
                        case "container2":
                            gsemidona(div, 0, ['#5eb9aa',
                                '#F9FFFE'
                            ]); // ['#f5bd22', '#FDEEC7']);
                            $('#span-container2-fuente').html("Fuente: " + 'MINEDU');
                            $('#span-container2-fecha').html("Actualizado: " + '31/12/2022');
                            break;
                        case "container3":
                            gsemidona(div, 0, ['#5eb9aa', '#F9FFFE']); // ['#e65310', '#FDD1BD']);
                            $('#span-container3-fuente').html("Fuente: " + 'MINEDU');
                            $('#span-container3-fecha').html("Actualizado: " + '31/12/2022');
                            break;
                        case "dtanal1":
                            gsemidona(div, data.info.indicador, ['#5eb9aa', '#F9FFFE']);
                            $('#span-dtanal1-fuente').html("Fuente: " + data.info.fuente);
                            $('#span-dtanal1-fecha').html("Actualizado: " + data.info.fecha);
                            break;
                        case "dtanal2":
                            gsemidona(div, data.info.indicador, ['#5eb9aa', '#F9FFFE']);
                            $('#span-dtanal2-fuente').html("Fuente: " + data.info.fuente);
                            $('#span-dtanal2-fecha').html("Actualizado: " + data.info.fecha);
                            break;
                        case "dtanal3":
                            gsemidona(div, data.info.indicador, ['#5eb9aa', '#F9FFFE']);
                            $('#span-dtanal3-fuente').html("Fuente: " + data.info.fuente);
                            $('#span-dtanal3-fecha').html("Actualizado: " + data.info.fecha);
                            break;
                        case "iiee1":
                            gsemidona(div, 99.1, ['#5eb9aa', '#F9FFFE']);
                            $('#span-iiee1-fuente').html("Fuente: " + 'MINEDU');
                            $('#span-iiee1-fecha').html("Actualizado: " + '31/12/2022');
                            break;
                        case "iiee2":
                            gsemidona(div, 76.0, ['#5eb9aa', '#F9FFFE']); // ['#f5bd22', '#FDEEC7']);
                            $('#span-iiee2-fuente').html("Fuente: " + 'MINEDU');
                            $('#span-iiee2-fecha').html("Actualizado: " + '31/12/2022');
                            break;
                        case "iiee3":
                            gsemidona(div, 94.9, ['#5eb9aa', '#F9FFFE']); // ['#e65310', '#FDD1BD']);
                            $('#span-iiee3-fuente').html("Fuente: " + 'MINEDU');
                            $('#span-iiee3-fecha').html("Actualizado: " + '31/12/2022');
                            break;
                        case "iiee4":
                            gsemidona(div, 99.1, ['#5eb9aa', '#F9FFFE']);
                            $('#span-iiee4-fuente').html("Fuente: " + 'MINEDU');
                            $('#span-iiee4-fecha').html("Actualizado: " + '31/12/2022');
                            break;
                        case "iiee5":
                            gsemidona(div, 76.0, ['#5eb9aa', '#F9FFFE']); // ['#f5bd22', '#FDEEC7']);
                            $('#span-iiee5-fuente').html("Fuente: " + 'MINEDU');
                            $('#span-iiee5-fecha').html("Actualizado: " + '31/12/2022');
                            break;
                        case "iiee6":
                            gsemidona(div, 94.9, ['#5eb9aa', '#F9FFFE']); // ['#e65310', '#FDD1BD']);
                            $('#span-iiee6-fuente').html("Fuente: " + 'MINEDU');
                            $('#span-iiee6-fecha').html("Actualizado: " + '31/12/2022');
                            break;

                        case "skills001":
                            $('.skills001 h6 span').html(data.info.indicador + "%");
                            $('.skills001 .progress-bar').css('width', data.info.indicador + '%')
                                .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                                .addClass(data.info.indicador > 84 ? 'bg-success-0' :
                                    (data.info.indicador > 49 ? 'bg-warning-0' : 'bg-orange-0'));
                            break;
                        case "skills002":
                            $('.skills002 h6 span').html(data.info.indicador + "%");
                            $('.skills002 .progress-bar').css('width', data.info.indicador + '%')
                                .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                                .addClass(data.info.indicador > 84 ? 'bg-success-0' :
                                    (data.info.indicador > 49 ? 'bg-warning-0' : 'bg-orange-0'));
                        case "skills003":
                            $('.skills003 h6 span').html(data.info.indicador + "%");
                            $('.skills003 .progress-bar').css('width', data.info.indicador + '%')
                                .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                                .addClass(data.info.indicador > 84 ? 'bg-success-0' :
                                    (data.info.indicador > 49 ? 'bg-warning-0' : 'bg-orange-0'));
                            break;
                        case "skills004":
                            $('.skills004 h6 span').html(data.info.indicador + "%");
                            $('.skills004 .progress-bar').css('width', data.info.indicador + '%')
                                .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                                .addClass(data.info.indicador > 84 ? 'bg-success-0' :
                                    (data.info.indicador > 49 ? 'bg-warning-0' : 'bg-orange-0'));
                            break;
                        case "skills005":
                            $('.skills005 h6 span').html(data.info.indicador + "%");
                            $('.skills005 .progress-bar').css('width', data.info.indicador + '%')
                                .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                                .addClass(data.info.indicador > 84 ? 'bg-success-0' :
                                    (data.info.indicador > 49 ? 'bg-warning-0' : 'bg-orange-0'));
                            $('#span-skills005-fuente').html("Fuente: " + data.reg.fuente);
                            $('#span-skills005-fecha').html("Actualizado: " + data.reg.fecha);
                            break;
                        case "skills006":
                            $('.skills006 h6 span').html(data.info.indicador + "%");
                            $('.skills006 .progress-bar').css('width', data.info.indicador + '%')
                                .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                                .addClass(data.info.indicador > 84 ? 'bg-success-0' :
                                    (data.info.indicador > 49 ? 'bg-warning-0' : 'bg-orange-0'));

                            break;
                        case "skills007":
                            $('.skills007 h6 span').html(data.info.indicador + "%");
                            $('.skills007 .progress-bar').css('width', data.info.indicador + '%')
                                .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                                .addClass(data.info.indicador > 84 ? 'bg-success-0' :
                                    (data.info.indicador > 49 ? 'bg-warning-0' : 'bg-orange-0'));
                            break;
                        case "skills008":
                            $('.skills008 h6 span').html(data.info.indicador + "%");
                            $('.skills008 .progress-bar').css('width', data.info.indicador + '%')
                                .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                                .addClass(data.info.indicador > 84 ? 'bg-success-0' :
                                    (data.info.indicador > 49 ? 'bg-warning-0' : 'bg-orange-0'));
                            break;
                        case "skills009":
                            $('.skills009 h6 span').html(data.info.indicador + "%");
                            $('.skills009 .progress-bar').css('width', data.info.indicador + '%')
                                .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                                .addClass(data.info.indicador > 84 ? 'bg-success-0' :
                                    (data.info.indicador > 49 ? 'bg-warning-0' : 'bg-orange-0'));
                            break;
                        case "skills010":
                            $('.skills010 h6 span').html(data.info.indicador + "%");
                            $('.skills010 .progress-bar').css('width', data.info.indicador + '%')
                                .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                                .addClass(data.info.indicador > 84 ? 'bg-success-0' :
                                    (data.info.indicador > 49 ? 'bg-warning-0' : 'bg-orange-0'));
                            $('#span-skills010-fuente').html("Fuente: " + data.reg.fuente);
                            $('#span-skills010-fecha').html("Actualizado: " + data.reg.fecha);
                            break;
                        case "tabla1":
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
                            break;
                        default:
                            break;
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
                url: "{{ route('plaza.cargardistritos', '') }}/" + $('#provincia').val(),
                type: 'GET',
                success: function(data) {
                    $("#distrito option").remove();
                    var options = '<option value="0">DISTRITO</option>';
                    $.each(data.distritos, function(index, value) {
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

        function datosIndicador(id) {
            $.ajax({
                url: "{{ route('mantenimiento.indicadorgeneral.buscar.1', '') }}/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    console.log(data);
                    if (data.ie) {
                        $('#indicador').val(data.ie.id);
                        $('#indicadornombre').val(data.ie.nombre);
                        $('#indicadordescripcion').val(data.ie.descripcion);
                        $('#indicadorinstrumento').val(data.ie.instrumento);
                        $('#indicadortipo').val(data.ie.tipo);
                        $('#indicadorfuentedato').val(data.ie.fuente_dato);
                        $('#modal_datosindicador .modal-footer').html(
                            '<button type="button" class="btn btn-xs btn-danger waves-effect" data-dismiss="modal">Cerrar</button><button type="button" class="btn btn-primary btn-xs waves-effect waves-light" onclick="verpdf(' +
                            id + ')">Ficha Tecnica</button>');
                        $('#modal_datosindicador').modal('show');
                    } else {
                        toastr.error('ERROR, Indicador no encontrado, consulte al administrador', 'Mensaje');
                    }
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR DE INDICADOR");
                    console.log(jqXHR);
                },
            });
        };

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
                    enabled: false,
                    //text: subtitulo,
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
                            format: '{point.y:,0f} ( {point.percentage:.1f}% )',
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
                exporting: {
                    enabled: false
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

        function gsemidona(div, valor, colors) {
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
                        colors: colors,
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

        function gAnidadaColumn(div, categoria, series, titulo, subtitulo, maxBar) {
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
                        fontSize: '10px',
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
                        // labels: {
                        //     //format: '{value}°C',
                        //     //style: {
                        //     //    color: Highcharts.getOptions().colors[2]
                        //     //}
                        // },
                        title: {
                            enabled: false,
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
                            /* formatter: function() {
                                if (this.colorIndex == 2)
                                    return this.y + " %";
                                else
                                    return Highcharts.numberFormat(this.y, 0);
                            }, */
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

        function GaugeSeries(div, data) {
            Highcharts.chart(div, {
                chart: {
                    height: 165,
                    margin: [0, 0, 0, 0],
                    spacing: [0, 0, 0, 0],
                    type: 'solidgauge'
                },
                yAxis: {
                    min: 0,
                    max: 100,
                    stops: [
                        [0.1, '#33A29D'], // green
                        // [0.5, '#DDDF0D'], // yellow
                        // [0.9, '#DF5353'] // red
                    ],
                    lineWidth: 0,
                    minorTickInterval: null,
                    tickAmount: 0,

                },
                pane: {
                    background: {
                        innerRadius: '80%',
                        outerRadius: '100%'
                    }
                },
                accessibility: {
                    // typeDescription: 'The gauge chart with 1 data point.'
                },
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: false,
                },
                title: {
                    text: ''
                },

                plotOptions: {
                    series: {
                        // className: 'highcharts-live-kpi',
                        dataLabels: {
                            format: '<div style="text-align:center; margin-top: -20px">' +
                                '<div style="font-size:2.5em;">{y}%</div>' +
                                '<div style="font-size:12px; opacity:0.4; text-align: center;">Avance</div>' +
                                '</div>',
                            useHTML: true,
                            borderWidth: 0,

                        }
                    }
                },
                series: [{
                    name: 'Avance',
                    // data:[80],
                    innerRadius: '80%',
                    data: [{
                        y: data,
                        colorIndex: '50'
                    }],
                    radius: '100%',
                }],
                xAxis: {
                    accessibility: {
                        // description: 'Days'
                    }
                },
                lang: {
                    accessibility: {
                        // chartContainerLabel: 'CPU usage. Highcharts interactive chart.'
                    }
                },
                tooltip: {
                    valueSuffix: '%'
                }

            });

        }
    </script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <!-- optional -->
    <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>

    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script> --}}
@endsection
