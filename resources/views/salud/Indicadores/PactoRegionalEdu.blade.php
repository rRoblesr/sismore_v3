@extends('layouts.main', ['activePage' => 'usuarios', 'titlePage' => ''])
@section('css')
    <style>
        .centrarmodal {
            display: flex;
            justify-content: center;
            align-items: center;
            background: #000000c9 !important;
        }

        .ui-autocomplete {
            z-index: 215000000 !important;
        }

        /*  formateando nav-tabs  */
        .nav-tabs .nav-link:not(.active) {
            /* border-color: transparent !important; */
        }

        .nav-link {
            /* color: #000; */
            font-weight: bold;
        }

        .nav-tabs .nav-item {
            color: #43beac;

            /* background-color: #43beac; */
            /* #0080FF; */
            /* color: #FFF; */
        }

        .nav-tabs .nav-item .nav-link.active {
            /* color: #43beac; */
            /* #0080FF; */

            background-color: #43beac;
            color: #FFF;
        }

        /*  */

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background-color: #fff;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .pricing-header {
            background-color: #17a2b8;
            color: #fff;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }

        .pricing-header h5 {
            font-size: 18px;
            font-weight: bold;
        }

        .card-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .card-body .row {
            margin-bottom: 10px;
        }

        .btn-warning {
            background-color: #ffc107;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .text-green-0 {
            color: #28a745;
        }

        .font-13 {
            font-size: 13px;
        }

        .font-12 {
            font-size: 12px;
            color: #6c757d;
        }

        .text-white {
            color: #fff;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        .mdi {
            font-size: 18px;
        }

        .btn-sm {
            font-size: 14px;
            padding: 8px 16px;
        }

        .card .mt-1.pt-1 {
            margin-top: auto;
        }
    </style>
@endsection

@section('content')
    <div class="content">

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card card-border border border-plomo-0">
                    <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                        <h3 class="card-title text-white"></h3>
                    </div>
                    <div class="card-body pb-0 pt-3">
                        <div class="form-group row">
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <h4 class="page-title font-16">PACTO REGIONAL</h4>
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-6">
                                <div class="custom-select-container">
                                    <label for="anio">AÑO</label>
                                    <select id="anio" name="anio" class="form-control font-11 p-0"
                                        onchange="cargarpacto1();">
                                        @foreach ($anio as $item)
                                            <option value="{{ $item }}" {{ $item == $aniomax ? 'selected' : '' }}>
                                                {{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <div class="custom-select-container">
                                    <label for="provincia">PROVINCIA</label>
                                    <select id="provincia" name="provincia" class="form-control font-11"
                                        onchange="cargarDistritos();">
                                        <option value="0">TODOS</option>
                                        @foreach ($provincia as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <div class="custom-select-container">
                                    <label for="distrito">DISTRITO</label>
                                    <select id="distrito" name="distrito" class="form-control font-11"
                                        onchange="cargarpacto1();">
                                        <option value="0">TODOS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-6 text-center">
                                <button type="button" class="btn btn-orange-0 btn-xs" onclick="location.reload()"
                                    title='ACTUALIZAR'>
                                    <i class="fas fa-history"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>






        {{-- <div class="row">

            @foreach ($indedu as $key => $item)
                <div class="col-md-6 col-xl-3">
                    <div class="card text-center border border-success-0">
                        <div class="pricing-header bg-success-0 p-0 rounded-top">
                            <div class="card-widgets">
                                <span onclick="datosIndicador({{ $item->id }})"><i
                                        class="mdi mdi-rotate-180 mdi-alert-circle"
                                        style="color:#FFF;font-size: 20px;"></i>&nbsp;&nbsp;</span>
                            </div>
                            <h5 class="text-white font-14 font-weight-normal mt-1 mb-1"><i class="mdi mdi-school"
                                    style="font-size: 20px"></i>
                                Indicador {{ $key + 1 }}</h5>
                        </div>
                        <div class="pb-4 pl-4 pr-4">
                            <ul class="list-unstyled mt-0">
                                <li class="mt-0 pt-0">

                                <li class="m-0 pt-0">
                                    <figure class="p-0 m-0">
                                        <div id="gra{{ $item->codigo }}"></div>
                                    </figure>
                                </li>
                                </li>
                                <li class="mt-0 pt-0 font-10" id="actualizado{{ $item->codigo }}">
                                </li>

                                <li class="mt-1 pt-1">
                                    <div class="row">
                                        <div class="col-6 p-0">
                                            <span class="text-green-0 font-weight-bold font-13" style="font-size: 100%">
                                                <i class="mdi mdi-arrow-up-bold"></i>
                                                Numerador
                                            </span>
                                            <div class="font-weight-bold" id="num{{ $item->codigo }}">100</div>
                                        </div>
                                        <div class="col-6 p-0">
                                            <span class="text-green-0 font-weight-bold font-13" style="font-size: 100%">
                                                <i class="mdi mdi-arrow-down-bold"></i>
                                                Denominador
                                            </span>
                                            <div class="font-weight-bold" id="den{{ $item->codigo }}">100</div>
                                        </div>
                                    </div>
                                </li>
                                <li class="mt-1 pt-1">
                                    <p class="font-12" style="height: 5rem;">
                                        {{ $item->nombre }}</p>
                                </li>

                            </ul>
                            <div class="mt-1 pt-1">
                                <button class="btn btn-primary width-md waves-effect waves-light">Sign Up</button>
                                <a href="{{ route('salud.indicador.pactoregional.detalle', $item->id) }}"
                                    class="btn btn-warning btn-sm text-dark  width-md waves-effect waves-light">
                                    Ver detalle</a>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div> --}}
        <!-- end row -->

        <div class="row">
            @foreach ($indedu as $key => $item)
                <div class="col-md-6 col-xl-3">
                    <div class="card text-center border border-success-0" style="height: 100%;">
                        <div class="pricing-header bg-success-0 p-0 rounded-top">
                            <div class="card-widgets">
                                <span onclick="datosIndicador({{ $item->id }})">
                                    <i class="mdi mdi-rotate-180 mdi-alert-circle"
                                        style="color:#FFF;font-size: 20px;"></i>&nbsp;&nbsp;
                                </span>
                            </div>
                            <h5 class="text-white font-14 font-weight-normal mt-1 mb-1">
                                <i class="mdi mdi-school" style="font-size: 20px"></i> Indicador {{ $key + 1 }}
                            </h5>
                        </div>
                        <div class="px-1 d-flex flex-column" style="flex-grow: 1;">
                            <ul class="list-unstyled mt-0" style="margin-bottom: 0;">
                                <li class="m-0 pt-0">
                                    <figure class="p-0 m-0" style="height: 160px; width: 100%;">
                                        <div id="gra{{ $item->codigo }}" style="height: 100%;"></div>
                                    </figure>
                                </li>
                                <li class="mt-0 pt-0 font-10" id="actualizado{{ $item->codigo }}">Actualizado: 02/04/2024</li>
                                <li class="mt-1 pt-1" style="margin-bottom: 0;">
                                    <div class="row">
                                        <div class="col-6 p-0 text-center">
                                            <span class="text-green-0 font-weight-bold font-12">
                                                <i class="mdi mdi-arrow-up-bold"></i> Numerador
                                            </span>
                                            <div class="font-weight-bold" id="num{{ $item->codigo }}">100</div>
                                        </div>
                                        <div class="col-6 p-0 text-center">
                                            <span class="text-green-0 font-weight-bold font-12">
                                                <i class="mdi mdi-arrow-down-bold"></i> Denominador
                                            </span>
                                            <div class="font-weight-bold" id="den{{ $item->codigo }}">100</div>
                                        </div>
                                    </div>
                                </li>
                                <li class="mt-1 pt-1" style="margin-bottom: 0;">
                                    <p class="font-11"
                                        style="word-wrap: break-word; word-break: break-word; white-space: normal; margin: 0;">
                                        {{ $item->nombre }}
                                    </p>
                                </li>
                            </ul>
                        </div>
                        <!-- Botón en el footer -->
                        <div class="card-footer text-center bg-white" style="padding: 10px 15px; margin-top: auto;">
                            <a href="{{ route('salud.indicador.pactoregional.detalle', $item->id) }}"
                                class="btn btn-warning btn-sm text-dark width-md waves-effect waves-light py-1">
                                <i class="mdi mdi-eye"></i> Ver detalle
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div id="modal_datosindicador" class="modal fade font-10" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-16" id="myModalLabel">Datos del indicador</h5>
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
                                            <textarea class="form-control" name="indicadordescripcion" id="indicadordescripcion" cols="30" rows="3"
                                                placeholder="Definición del indicador"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Numerador</label>
                                            <textarea class="form-control" name="indicadornumerador" id="indicadornumerador" cols="30" rows="5"
                                                placeholder="Definición del indicador"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Denominador</label>
                                            <textarea class="form-control" name="indicadordenominador" id="indicadordenominador" cols="30" rows="5"
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
            cargarDistritos();
            cargarpacto1();
        });

        function cargarpacto1() {
            cargarActualizar('DIT-EDU-01');
            cargarActualizar('DIT-EDU-02');
            cargarActualizar('DIT-EDU-06');
            // cargarActualizar('DIT-EDU-04');
        }

        function cargarActualizar(codigo) {

            $.ajax({
                url: "{{ route('salud.indicador.pactoregional.actualizar') }}",
                data: {
                    "anio": $('#anio').val(),
                    "distrito": $('#distrito').val(),
                    "codigo": codigo,
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    GaugeSeries('gra' + codigo, 0);
                    $('#actualizado' + codigo).text('');
                },
                success: function(data) {
                    console.log(data);
                    GaugeSeries('gra' + codigo, data.avance);
                    $('#actualizado' + codigo).text(data.actualizado);
                    $('#meta' + codigo).text('Meta: ' + data.meta);
                    $('#cumple' + codigo).html(data.cumple ?
                        '<span class="badge badge-success m-2" style="font-size: 90%; width:100px"> <i class="mdi mdi-thumb-up"></i> CUMPLE</span>' :
                        '<span class="badge badge-danger m-2" style="font-size: 90%; width:100px"> <i class="mdi mdi-thumb-down"></i> NO CUMPLE</span>'
                    );
                    $('#num' + codigo).text(data.num);
                    $('#den' + codigo).text(data.den);
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR cargarActualizar");
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
                    var options = '<option value="0">TODOS</option>';
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
                        $('#indicadornumerador').val(data.ie.numerador);
                        $('#indicadordenominador').val(data.ie.denominador);
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
            //colors: ['#5eb9aa', '#f5bd22', '#ef5350'],
            Highcharts.chart(div, {
                chart: {
                    height: 165,
                    margin: [0, 0, 0, 0],
                    spacing: [0, 0, 0, 0],
                    type: 'solidgauge'
                },
                // yAxis: {
                //     min: 0,
                //     max: 100,
                //     // stops: [
                //     //     [0.5, '#ef5350'], // red DF5353
                //     //     [0.9, '#f5bd22'], // yellow
                //     //     [1, '#5eb9aa'], // green 33A29D
                //     // ],
                //     dataClasses: [{
                //         from: 0,
                //         to: 50,
                //         color: '#ef5350'
                //     }, {
                //         from: 51,
                //         to: 99,
                //         color: '#f5bd22'
                //     }, {
                //         from: 100,
                //         to: 150,
                //         color: '#5eb9aa'
                //     }],
                //     // starOnTick:true,
                //     lineWidth: 0,
                //     tickInterval: null,
                //     minorTickInterval: null,
                //     // minorTickWidth:null,
                //     tickAmount: 0,
                //     labels: {
                //         enabled: false,
                //     }

                // },
                yAxis: {
                    labels: {
                        style: {
                            display: 'none'
                        }
                    },
                    tickLength: 0,
                    lineColor: 'transparent',
                    minorTickLength: 0,
                    minorGridLineWidth: 0,
                    gridLineWidth: 0,

                    min: 0,
                    max: 100,
                    dataClasses: [{
                        from: 0,
                        to: 75,
                        color: '#ef5350'
                    }, {
                        from: 76,
                        to: 95,
                        color: '#f5bd22'
                    }, {
                        from: 95,
                        to: 150,
                        color: '#5eb9aa'
                    }],
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
