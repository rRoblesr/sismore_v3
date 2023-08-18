@extends('layouts.main',['activePage'=>'importacion','titlePage'=>''])

@section('css')
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script>
@endsection

@section('content')
    <div class="content">
        <input type="hidden" id="hoja" value="1">

        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body">


                        <div class="row">
                            <div class="col-md-6">
                                <p class="titulo_Indicadores  mb-0">REPORTE DE DOCENTES</p>
                            </div>
                            <div class="col-md-6 text-right">
                                <p class="texto_dfuente  mb-0"> Fuente: Sistema de Administración y Control de Plazas – NEXUS
                                </p>
                                {{-- <p class="texto_dfuente  mb-0"> $fecha_version </p> --}}
                            </div>
                        </div>

                        <div class="progress progress-sm m-0">
                            <div class="progress-bar bg-info" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                aria-valuemax="100" style="width: 100%"></div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-md-1 col-form-label">Año:</label>
                                    <div class="col-md-2">
                                        <select id="anio" name="anio" class="form-control"
                                            onchange="cargar_fechas_matricula();">
                                            @foreach ($anios as $item)
                                                <option value="{{ $item->anio }}"> {{ $item->anio }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label class="col-md-1 col-form-label">Fecha:</label>
                                    <div class="col-md-2">
                                        <select id="fechas" name="fechas" class="form-control" onchange="cambia_fecha();">
                                            @foreach ($fechas as $item)
                                                <option value="{{ $item->importacion_id }}">
                                                    {{ $item->fechaActualizacion }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div> <!-- End col -->
                        </div> <!-- End row -->

                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="datos01" class="form-group row">
                                    Cargando datos.....
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- card-body -->

                </div>

            </div> <!-- End col -->
        </div> <!-- End row -->


    </div>
@endsection

@section('js')
    {{-- https://www.youtube.com/watch?v=HU-hffAZqYw --}}

    <script type="text/javascript">
        $(document).ready(function() {
            cargar_resumen_porUgel();
        });


        function cargar_resumen_porUgel() {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                url: "{{ url('/') }}/CuadroAsigPersonal/Docentes/ReportePrincipal/" + 1 + "/" + $('#fechas')
                    .val(),
                type: 'post',
            }).done(function(data) {
                $('#datos01').html(data);
            }).fail(function() {
                alert("Lo sentimos a ocurrido un error");
            });

            // alert("1");
        }
    </script>
@endsection
