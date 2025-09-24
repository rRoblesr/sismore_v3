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

    <div id="modal_datosindicador" class="modal fade font-10" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
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
                                        <input id="indicadorinstrumento" name="indicadorinstrumento" class="form-control"
                                            type="text" placeholder="Fuente de datos">
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Fuente de datos</label>
                                        <input id="indicadorfuentedato" name="indicadorfuentedato" class="form-control"
                                            type="text" placeholder="Fuente de datos">
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
@endsection
@section('js')
    <script type="text/javascript">
        var paleta_colores = ['#5eb9aa', '#F9FFFE', '#f5bd22', '#058DC7', '#50B432', '#9D561B', '#DDDF00', '#24CBE5',
            '#64E572', '#9F9655', '#FFF263', '#6AF9C4'
        ];
        const spinners = {
            num: [
                @foreach ($indedu as $item)
                    '#num{{ $item->codigo }}',
                @endforeach
            ],
            den: [
                @foreach ($indedu as $item)
                    '#den{{ $item->codigo }}',
                @endforeach
            ],
            actualizado: [
                @foreach ($indedu as $item)
                    '#actualizado{{ $item->codigo }}',
                @endforeach
            ],
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
                    // $('#actualizado' + codigo).text('');
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
@endsection
