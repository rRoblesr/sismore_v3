@extends('layouts.main', ['titlePage' => ''])
@section('css')
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
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
                    <div class="card-header bg-transparent p-3">
                        <div class="card-widgets">
                            <button type="button" class="btn btn-purple btn-xs" onclick="location.reload()"><i
                                    class="fa fa-redo"></i> Actualizar</button>

                        </div>
                        <h3 class="card-title text-white text-center">AVANCE MATRICULA SEGÚN SIAGIE- MINEDU ACTUALIZADO AL
                            {{ $fecha }}
                            {{-- <a href="javascript:location.reload()" class="btn btn-danger btn-xs" title="ACTUALIZAR PAGINA"><i
                                    class="fa fa-redo"></i> Actualizar</a> --}}
                        </h3>
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
                                            <option value="{{ $item->id }}">{{ $item->anio }}</option>
                                        @endforeach
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
                                <label class="col-md-1 col-form-label">Gestion</label>
                                <div class="col-md-2">
                                    <select id="gestion" name="gestion" class="form-control p-0"
                                        onchange="cargartabla0()">
                                        <option value="0">Todos</option>
                                        @foreach ($gestions as $prov)
                                            <option value="{{ $prov['id'] }}">{{ $prov['nombre'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="col-md-1 col-form-label">Área</label>
                                <div class="col-md-2">
                                    <select id="area" name="area" class="form-control p-0"
                                        onchange="cargartabla0()">
                                        <option value="0">Todos</option>
                                        @foreach ($areas as $prov)
                                            <option value="{{ $prov->id }}">{{ $prov->nombre }}</option>
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
        {{-- tablaa 0 --}}
        <div class="row">
            <div class="col-xl-12">
                <div class="card card-border">
                    <div class="card-header border-primary bg-transparent pb-0 mb-0">
                        <h3 class="card-title">Avance de la matricula de gestion educativa local</h3>
                    </div>
                    <div class="card-body pb-0 pt-0">
                        <div class="table-responsive" id="vista0">
                        </div>
                        {{-- <p class="text-muted font-13 m-0 p-0 text-right">
                            Fuente: ESCALE - MINEDU – PADRON WEB, ultima actualizacion del 12/07/2022
                        </p> --}}
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}

        {{-- tablaa 1 --}}
        <div class="row">
            <div class="col-xl-12">
                <div class="card card-border">
                    <div class="card-header border-primary bg-transparent pb-0 mb-0">
                        <h3 class="card-title">Avance de la matricula según nivel y modalidad educativa local</h3>
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


    <!-- Bootstrap modal -->
    <div id="modal_rojos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive" id="vistarojo">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    {{-- <button type="button" id="btnSaveentidad" onclick="saveentidad()"
                        class="btn btn-primary">Guardar</button> --}}
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Bootstrap modal -->
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            Highcharts.setOptions({
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

        function cargartabla0() {
            $.ajax({
                url: "{{ route('matriculadetalle.avance.tabla0') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    $('#vista0').html(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('matriculadetalle.avance.tabla1') }}",
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
                url: "{{ route('matriculadetalle.avance.grafica1') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    gLineaBasica('gra1', data, '', 'MATRICULA ACUMULADA MENSUAL', '');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function openrojos(mes, nivel, ano) {

            $.ajax({
                url: "{{ url('/') }}/MatriculaDetalle/rojos/" + mes + '/' + nivel + '/' + ano,
                type: "GET",
                //dataType: "JSON",
                success: function(data) {
                    $('#modal_rojos').modal('show');
                    $('#modal_rojos .modal-title').text('Avance de Matricula por Institución Educativa');

                    $('#vistarojo').html(data);

                    $('#tabla1rojos').DataTable({
                        "language": table_language,
                    });


                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error("El registro no se pudo crear verifique las validaciones.", 'Mensaje');
                }
            });
        };
    </script>

    <script type="text/javascript">
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
    <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script> --}}
    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script> --}}
@endsection
