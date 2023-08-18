@extends('layouts.main', ['titlePage' => ''])
@section('css')
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
    </style>
@endsection
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-fill bg-primary  mb-0">
                    <div class="card-header bg-transparent">
                        <div class="card-widgets">
                            <button type="button" class="btn btn-purple btn-xs" onclick="location.reload()"><i
                                    class="fa fa-redo"></i> Actualizar</button>
                        </div>
                        <h3 class="card-title text-white text-center">COBERTURA DE PLAZAS - NEXUS ACTUALIZADO AL
                            {{ $fecha }}{{--  <a href="javascript:location.reload()" class="btn btn-warning"
                                title="ACTUALIZAR PAGINA"><i class="fa fa-redo"></i></a> --}}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-2">
                        <form id="form_opciones" name="form_opciones" action="POST">
                            @csrf
                            <input type="hidden" id="importacion" name="importacion" value="{{ $importacion_id }}">
                            <div class="form-group row mb-0">
                                <label class="col-md-1 col-form-label">Año</label>
                                <div class="col-md-2">
                                    <select id="ano" name="ano" class="form-control p-0"
                                        onchange="cargartabla0()">
                                        @foreach ($anios as $item)
                                            <option value="{{ $item->ano }}">{{ $item->ano }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="col-md-1 col-form-label">Tipo</label>
                                <div class="col-md-2">
                                    <select id="tipo" name="tipo" class="form-control p-0"
                                        onchange="cargarnivelmodalidad();cargartabla0()">
                                        <option value="0">Todos</option>
                                        @foreach ($tipo as $prov)
                                            <option value="{{ $prov->tipo }}">{{ $prov->tipo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="col-md-1 col-form-label">Nivel</label>
                                <div class="col-md-2">
                                    <select id="nivel" name="nivel" class="form-control p-0"
                                        onchange="cargartabla0()">
                                        <option value="0">Todos</option>
                                    </select>
                                </div>
                                <label class="col-md-1 col-form-label">Ugel</label>
                                <div class="col-md-2">
                                    <select id="ugel" name="ugel" class="form-control p-0"
                                        onchange="cargartabla0()">
                                        <option value="0">Todos</option>
                                        @foreach ($ugels as $ugel)
                                            <option value="{{ $ugel['id'] }}">{{ $ugel['nombre'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="col-md-1">
                                    <a href="javascript:location.reload()" class="btn btn-primary"><i
                                            class="fa fa-redo"></i></a>
                                </div> --}}

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

        {{-- tablaa 1 --}}
        <div class="row">
            <div class="col-xl-12">
                <div class="card card-border">
                    <div class="card-header border-primary bg-transparent pb-0 mb-0">
                        <h3 class="card-title">Avance de la CONTRATACIÓN de plazas </h3>
                    </div>
                    <div class="card-body pb-0 pt-0">
                        <div class="table-responsive" id="vista1">
                        </div>
                        {{-- <p class="text-muted font-13 m-0 p-0 text-right">
                            Fuente: ESCALE - MINEDU – PADRON WEB, ultima actualizacion del 12/07/2022
                        </p> --}}
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}

        {{-- tablaa 2 --}}
        <div class="row">
            <div class="col-xl-12">
                <div class="card card-border">
                    <div class="card-header border-primary bg-transparent pb-0 mb-0">
                        <h3 class="card-title">Total de la CONTRATACIÓN de plazas Según tipo trabajdor con SITUACIÓN laboral
                        </h3>
                    </div>
                    <div class="card-body pb-0 pt-0">
                        <div class="table-responsive" id="vista2">
                        </div>
                        {{-- <p class="text-muted font-13 m-0 p-0 text-right">
                            Fuente: ESCALE - MINEDU – PADRON WEB, ultima actualizacion del 12/07/2022
                        </p> --}}
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}

        {{-- grafica 1 --}}
        <div class="row">
            <div class="col-xl-12">
                <div class="card card-border">
                    <div class="card-header border-primary bg-transparent p-0">
                        <h3 class="card-title"></h3>
                    </div>
                    <div class="card-body pb-0 pt-0">
                        <div id="gra1"></div>
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}

    </div>
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            Highcharts.setOptions({
                //colors: Highcharts.map(Highcharts.getOptions().colors, function(color) {
                colors: Highcharts.map(paleta_colores, function(color) {
                    return {
                        radialGradient: {
                            cx: 0.5,
                            cy: 0.3,
                            r: 0.7
                        },
                        stops: [
                            [0, color],
                            [1, Highcharts.color(color).brighten(-0.3).get('rgb')] // darken
                        ]
                    };
                }),
                lang: {
                    thousandsSep: ","
                }
            });
            cargartabla0();
        });

        function cargarnivelmodalidad() {
            $.ajax({
                url: "{{ url('/') }}/NivelModalidad/Buscar/" + $('#tipo').val(),
                type: 'get',
                success: function(data) {
                    console.log(data);
                    $('#nivel option ').remove();
                    var opt = '<option value="">TODOS</option>';
                    $.each(data, function(index, value) {
                        opt += '<option value="' + value.id + '">' + value.nombre +
                            '</option>';
                    });
                    $('#nivel').append(opt);

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargartabla0() {
            $.ajax({
                url: "{{ route('nexus.cobertura.tabla1') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    $('#vista1').html(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('nexus.cobertura.tabla2') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    $('#vista2').html(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('nexus.cobertura.grafica1') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    gLineaBasica('gra1', data, '', 'CONTRATACIÓN DE PLAZAS ACUMULADA MENSUAL', '');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });


        }

        function gLineaBasica(div, data, titulo, subtitulo, titulovetical) {
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
                    min: 0,
                },
                xAxis: {
                    categories: data['cat'],
                    accessibility: {
                        rangeDescription: 'Range: 2010 to 2017'
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
                series: [{
                    name: 'Matriculados',
                    showInLegend: false,
                    data: data['dat']
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
                credits: false,

            });
        }
    </script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script> --}}
    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script> --}}
@endsection
