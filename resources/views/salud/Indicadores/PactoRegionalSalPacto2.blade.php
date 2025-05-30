@extends('layouts.main', ['titlePage' => ''])
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
@endsection

@section('content')
    <div class="card">
        <div
            class="card-header bg-success-0 text-white d-flex flex-column flex-md-row justify-content-between align-items-md-center p-2">
            <h6 class="mb-2 mb-md-0 text-center text-md-left text-wrap text-white">
                <i class="fas fa-chart-bar d-none"></i> {{ $ind->nombre }}
            </h6>
            <div class="text-center text-md-right">
                <button type="button" class="btn btn-orange-0 btn-xs my-1" onclick="history.back()" title="VOLVER">
                    <i class="fas fa-arrow-left"></i> Volver</button>
                <button type="button" class="btn btn-orange-0 btn-xs my-1" onclick="verpdf({{ $ind->id }})"
                    title='FICHA TÉCNICA'><i class="fas fa-file"></i> Ficha Técnica</button>
                <button type="button" class="btn btn-orange-0 btn-xs my-1" onclick="location.reload()" title='ACTUALIZAR'>
                    <i class=" fas fa-history"></i> Actualizar</button>
            </div>
        </div>
        <div class="card-body p-2">
            <div class="row">

                <div class="col-md-4 col-12">
                    <h5 class="page-title font-12 my-1">Fuente: Padrón Nominal, <br>{{ $actualizado }}</h5>
                </div>

                <div class="col-md-2 col-6">
                    <div class="custom-select-container my-2">
                        <label for="anio">Año</label>
                        <select id="anio" name="anio" class="form-control font-12"
                            onchange="cargarMes();">
                            @foreach ($anio as $item)
                                <option value="{{ $item->anio }}" {{ $item->anio == $aniomax ? 'selected' : '' }}>
                                    {{ $item->anio }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2 col-6">
                    <div class="custom-select-container my-1">
                        <label for="mes">Mes</label>
                        <select id="mes" name="mes" class="form-control font-12"
                            onchange="cargarcuadros();">
                        </select>
                    </div>
                </div>

                <div class="col-md-2 col-6">
                    <div class="custom-select-container my-1">
                        <label for="provincia">Provincia</label>
                        <select id="provincia" name="provincia" class="form-control font-12"
                            onchange="cargarDistritos();cargarcuadros();">
                            <option value="0">TODOS</option>
                            @foreach ($provincia as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2 col-6">
                    <div class="custom-select-container my-1">
                        <label for="distrito">Distrito</label>
                        <select id="distrito" name="distrito" class="form-control font-12"
                            onchange="cargarcuadros();">
                            <option value="0">TODOS</option>
                        </select>
                    </div>
                </div>

            </div>

        </div>
    </div>


    {{-- <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header bg-success-0">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-orange-0 btn-xs" onclick="history.back()"
                            title="ACTUALIZAR"><i class="fas fa-arrow-left"></i> Volver</button>
                        <button type="button" class="btn btn-orange-0 btn-xs" onclick="verpdf({{ $ind->id }})"
                            title='FICHA TÉCNICA'><i class="fas fa-file"></i> Ficha Técnica</button>
                        <button type="button" class="btn btn-orange-0 btn-xs" onclick="location.reload()"
                            title='ACTUALIZAR'><i class=" fas fa-history"></i>
                            Actualizar</button>
                    </div>
                    <h3 class="card-title text-white">{{ $ind->nombre }}
                    </h3>
                </div>
                <div class="card-body p-2">
                    <div class="form-group row align-items-center vh-5 m-0">
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <h5 class="page-title font-12">Fuente: Padrón Nominal, <br>{{ $actualizado }}</h5>
                        </div>
                        <div class="col-lg-2 col-md-1 col-sm-1  ">

                            <div class="custom-select-container">
                                <label for="anio">Año</label>
                                <select id="anio" name="anio" class="form-control font-12"
                                    onchange="cargarMes();cargarcuadros();">
                                    @foreach ($anio as $item)
                                        <option value="{{ $item->anio }}"
                                            {{ $item->anio == $aniomax ? 'selected' : '' }}>
                                            {{ $item->anio }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-1 col-sm-1  ">
                            <div class="custom-select-container">
                                <label for="mes">Mes</label>
                                <select id="mes" name="mes" class="form-control font-12"
                                    onchange="cargarcuadros();">
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="provincia">Provincia</label>
                                <select id="provincia" name="provincia" class="form-control font-12"
                                    onchange="cargarDistritos();cargarcuadros();">
                                    <option value="0">TODOS</option>
                                    @foreach ($provincia as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="distrito">Distrito</label>
                                <select id="distrito" name="distrito" class="form-control font-12"
                                    onchange="cargarcuadros();">
                                    <option value="0">TODOS</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!--Widget-4 -->
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card-box border border-plomo-0">
                <div class="media">
                    <div class="text-center">
                        {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                                    width="70%" height="70%"> --}}
                        <i class="mdi mdi-finance font-35 text-green-0"></i>
                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="ri"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">Resultado Indicador</p>
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
                        {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                                    width="70%" height="70%"> --}}
                        {{-- <i class=" mdi mdi-city font-35 text-green-0"></i> --}}
                        <i class="fas fa-child font-35 text-green-0"></i>
                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="gl"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">
                                <span onclick="verinformacion(0)" data-toggle="modal" data-target="#info_denominador">
                                    <i class="mdi mdi-rotate-180 mdi-alert-circle" style="color:#43beac;"></i>
                                </span>
                                Denominador
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
                        {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                                    width="70%" height="70%"> --}}
                        <i class="mdi mdi-thumb-up font-35 text-green-0"></i>
                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="gls"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">
                                <span onclick="verinformacion(1)" data-toggle="modal" data-target="#info_numerador">
                                    <i class="mdi mdi-rotate-180 mdi-alert-circle" style="color:#43beac;"></i>
                                </span>
                                Numerador
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
                        {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                                    width="70%" height="70%"> --}}
                        <i class="mdi mdi-thumb-down font-35 text-green-0"></i>
                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="gln"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">
                                No Cumplen
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
                    {{-- <div class="card-widgets">
                                <button type="button" class="btn btn-success btn-xs"><i
                                        class="fa fa-file-excel"></i> Descargar</button>
                            </div> --}}
                    <h3 class="text-black font-14 mb-0">
                        Avance acumulado de la evaluación de Cumplimiento por Distrito
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" style="height: 40rem" id="vtabla1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent p-0">
                    {{-- <h3 class="text-black text-center font-weight-normal font-11"></h3> --}}
                </div>
                <div class="card-body p-0">
                    <div id="anal1" style="height: 42rem"></div>
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
                        <div id="anal2" style="height: 20rem"></div>
                    </figure>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent p-0">
                    {{-- <div class="card-widgets">
                                <button type="button" class="btn btn-success btn-xs" onclick="descargar1()"><i
                                        class="fa fa-file-excel"></i>
                                    Descargar</button>
                            </div> --}}
                    <h3 class="text-black font-14 mb-0">
                        Avance acumulado de la evaluación de Cumplimiento por Red
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" style="height: 18rem" id="vtabla2">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="card  card-border border border-plomo-0">
        <div
            class="card-header border-success-0 bg-transparent text-white d-flex flex-column flex-md-row justify-content-between align-items-md-center px-2 py-2">
            <h6 class="card-title mb-2 mb-md-0 text-center text-md-left text-wrap">
                LISTADO DE ESTABLECIMEINTO DE SALUD QUE FUERON EVALUADOS
            </h6>
            <div class="text-center text-md-right">
                <button type="button" class="btn btn-success btn-xs" onclick="descargar4()"><i
                    class="fa fa-file-excel"></i> Descargar</button>
            </div>
        </div>
        <div class="card-body p-2">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive" id="vtabla3">
                    </div>
                </div>
            </div>
        </div>
    </div> 

    {{-- <div class="card">
        <div
            class="card-header bg-success-0 text-white d-flex flex-column flex-md-row justify-content-between align-items-md-center p-2">
            <h6 class="mb-2 mb-md-0 text-center text-md-left text-wrap text-white">
                <i class="fas fa-chart-bar d-none"></i> LISTADO DE ESTABLECIMEINTO DE SALUD QUE FUERON EVALUADOS
            </h6>
            <div class="text-center text-md-right">
                <button type="button" class="btn btn-success btn-xs" onclick="descargar4()"><i
                    class="fa fa-file-excel"></i> Descargar</button>
            </div>
        </div>
        <div class="card-body p-2">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive" id="vtabla3">
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- <div class="row">
        <div class="col-lg-12">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent p-0">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-success btn-xs" onclick="descargar3()"><i
                                class="fa fa-file-excel"></i> Descargar</button>
                    </div>
                    <h3 class="text-black font-14 mb-0">
                        Listado de establecimientos de salud que fueron evaluados
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" id="vtabla3">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

   

    {{-- <div class="row" style="display: none">
        <div class="col-lg-12">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent p-0">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-success btn-xs" onclick="descargar4()"><i
                                class="fa fa-file-excel"></i> Descargar</button>
                    </div>
                    <h3 class="text-black font-14 mb-0">
                        Evaluación de cumplimiento de los logros esperados por Distrito
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" id="vtabla4">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <div id="modal_microred" class="modal fade font-10" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-16">Microrred</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">

                    {{-- <div class="row"> --}}
                    {{-- <div class="col-lg-6"> --}}
                    <div class="card card-border border border-plomo-0">
                        <div class="card-header border-success-0 bg-transparent p-0">
                            <h3 class="text-black font-14 mb-0">
                                Avance acumulado de la evaluación de Cumplimiento por Microrred
                            </h3>
                            <input type="hidden" name="mred" id="mred" value="">
                        </div>
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-12">{{-- style="height: 40rem"  --}}
                                    <div class="table-responsive" id="vtabla2tabla1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- </div> --}}
                    {{-- </div> --}}

                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-xs btn-danger waves-effect" data-dismiss="modal">Cerrar</button> --}}
                    {{-- <button type="button" class="btn btn-primary btn-xs waves-effect waves-light" onclick="verpdf(8)">Ficha Tecnica</button> --}}
                </div>
            </div>
        </div>
    </div>


    <div id="modal_informacion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Modal Heading</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    {{-- <h5 class="font-16">Text in a modal</h5> --}}
                    {{-- <p></p> --}}
                    {{-- <h5 class="font-16">Text in a modal</h5>
                    <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula.</p>
                    <hr>
                    <h5 class="font-16">Overflowing text to show scroll behavior</h5>
                    <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas
                        eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue
                        laoreet rutrum faucibus dolor auctor.</p>
                        consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                    {{-- <button type="button" class="btn btn-primary waves-effect waves-light">Save changes</button> --}}
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <div id="modal_info_ipress" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Modal Heading</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table id="tabla0301" class="table table-sm table-striped table-bordered font-11">
                                <thead>
                                    <tr class="table-success-0 text-white">
                                        <th class="text-center">Nº</th>
                                        <th class="text-center">DNI</th>
                                        <th class="text-center">Fecha Nac.</th>
                                        <th class="text-center">Distrito</th>
                                        <th class="text-center">Seguro</th>
                                        <th class="text-center">Inician Tratamiento</th>
                                        <th class="text-center">Continuan Tratamiento</th>
                                        <th class="text-center">Terminan Tratamiento</th>
                                        <th class="text-center">No Cumplen en Terminar Tratamiento</th>
                                        <th class="text-center">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                    <!-- button type="button" class="btn btn-primary waves-effect waves-light">Save changes</button -->
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
            cargarMes();
            cargarDistritos();

        });

        function cargarcuadros() {
            panelGraficas('head');
            panelGraficas('anal1');
            panelGraficas('anal2');
            panelGraficas('tabla1');
            panelGraficas('tabla2');
            panelGraficas('tabla3');
            // panelGraficas('tabla4');
        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('salud.indicador.pactoregional.sal.pacto2.reports') }}",
                data: {
                    'div': div,
                    "anio": $('#anio').val(),
                    "mes": $('#mes').val(),
                    "provincia": $('#provincia').val(),
                    "distrito": $('#distrito').val(),
                    "indicador": '{{ $ind->id }}',
                    "codigo": '{{ $ind->codigo }}',
                    "red": $('#mred').val(),
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
                    if (div == "head") {
                        $('#ri').text(data.ri + '%');
                        $('#gl').text(data.gl);
                        $('#gls').text(data.gls);
                        $('#gln').text(data.gln);
                    } else if (div == "anal1") {
                        gbar('anal1', data.info.categoria,
                            data.info.serie,
                            '',
                            'Porcentaje de Cumplimiento por Distrito',
                        );
                    } else if (div == "anal2") {
                        gLineaBasica(div, data.info, '',
                            'Avance mensual de la Evaluación del cumplimiento',
                            '');
                    } else if (div == "tabla1") {
                        $('#vtabla1').html(data.excel);
                        // $('#tabla1').DataTable({
                        //     responsive: true,
                        //     autoWidth: false,
                        //     ordered: true,
                        //     searching: false,
                        //     bPaginate: false,
                        //     info: false,
                        //     language: table_language,
                        // });
                    } else if (div == "tabla2") {
                        $('#vtabla2').html(data.excel);
                    } else if (div == "tabla2tabla1") {
                        $('#vtabla2tabla1').html(data.excel);
                    } else if (div == "tabla3") {
                        $('#vtabla3').html(data.excel);
                        $('#tabla3').DataTable({
                            responsive: true,
                            autoWidth: false,
                            ordered: true,
                            // searching: false,
                            // bPaginate: false,
                            // info: false,
                            language: table_language,
                            columnDefs: [{
                                targets: 1, // Índice de la columna (empieza desde 0, por lo que la columna 2 es índice 1)
                                render: function(data, type, row) {
                                    // Puedes personalizar la URL del enlace aquí
                                    return '<a href="javascript:void(0)" onclick="abrirmodalinfoipress(`' +
                                    data + '`)">' + data +
                                        '</a>';
                                }
                            }]
                        });
                    } else if (div == "tabla4") {
                        $('#vtabla4').html(data.excel);
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
                    var options = data.length > 1 ? '<option value="0">TODOS</option>' : '';
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

        function cargarMes() {
            $.ajax({
                url: "{{ route('salud.indicador.pactoregional.sal.pacto2.find.mes', ['anio' => 'anio']) }}"
                    .replace('anio', $('#anio').val()),
                type: 'GET',
                success: function(data) {
                    $("#mes option").remove();
                    var options = ''; // '<option value="0"></option>';
                    var ultimovalor = data.length > 0 ? data[data.length - 1].mes_id : null;
                    $.each(data, function(index, value) {
                        ss = (value.mes_id === ultimovalor ? "selected" : "");
                        options += `<option value='${value.id}' ${ss}>${value.mes}</option>`;
                    });
                    $("#mes").append(options);
                    cargarcuadros();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function descargar3() { ///{div}/{indicador}/{anio}/{mes}/{provincia}/{distrito}'
            window.open(
                "{{ route('salud.indicador.pactoregional.sal.pacto2.excel', ['', '', '', '', '', '']) }}/tabla3/{{ $ind->id }}/" +
                $('#anio').val() + "/" + $('#mes').val() + "/" + $('#provincia').val() + "/" + $('#distrito').val());
        }

        function descargar4() {
            window.open(
                "{{ route('salud.indicador.pactoregional.sal.pacto2.excel', ['', '', '', '', '', '']) }}/tabla4/{{ $ind->id }}/" +
                $('#anio').val() + "/" + $('#mes').val() + "/" + $('#provincia').val() + "/" + $('#distrito').val());
        }

        function verpdf(id) {
            window.open("{{ route('salud.indicador.pactoregional.exportar.pdf', '') }}/" + id);
        };

        function cargarmicrored(red) {
            $('#mred').val(red);
            $('#modal_microred').modal('show');
            panelGraficas('tabla2tabla1');

        }

        function verinformacion(opcion) {
            $('#modal_informacion .modal-title').text(opcion == 0 ? 'Denominador' : 'Numerador');

            $.ajax({
                url: "{{ route('salud.indicador.pactoregional.find.codigo', '') }}/{{ $ind->codigo }}",
                type: 'GET',
                success: function(data) {
                    $('#modal_informacion .modal-body').text(opcion == 0 ? data.denominador : data.numerador);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $('#modal_informacion').modal('show');
        }

        function abrirmodalinfoipress(codigo_unico) {
            $('#modal_info_ipress .modal-title').html('NIÑOS Y NIÑAS ATENDIDOS');
            $('#modal_info_ipress').modal('show');
            tablexx = $('#tabla0301').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: false,
                language: table_language,
                destroy: true,
                ajax: {
                    url: "{{ route('salud.indicador.pactoregional.sal.pacto2.reports') }}",
                    "type": "GET",
                    //"dataType": 'JSON',
                    data: {
                        'div': 'tabla0301',
                        "anio": $('#anio').val(),
                        "mes": $('#mes').val(),
                        "provincia": $('#provincia').val(),
                        "distrito": $('#distrito').val(),
                        "indicador": '{{ $ind->id }}',
                        "codigo": '{{ $ind->codigo }}',
                        "red": $('#mred').val(),
                        "cod_unico": parseInt(codigo_unico, 10),
                    },
                },
                columnDefs: [{
                    targets: 9, // Índice de la columna (empieza desde 0, por lo que la columna 2 es índice 1)
                    render: function(data, type, row) {
                        // Puedes personalizar la URL del enlace aquí

                        return data ?
                            '<span class="badge badge-pill badge-success" style="font-size:100%;"><i class="mdi mdi-thumb-up"></i> Cumple</span>' :
                            '<span class="badge badge-pill badge-danger" style="font-size:100%;"><i class="mdi mdi-thumb-down"></i> No Cumple</span>';
                    }
                }],
            });
        }

        function gbar(div, categoria, series, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: titulo,
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        // fontSize: '11px'
                    }
                },
                xAxis: {
                    categories: categoria,
                    title: {
                        text: '',
                    },
                    labels: {
                        style: {
                            fontSize: '10px',
                        },
                        // enabled: false,
                    },
                },
                yAxis: {
                    //min: 0,
                    title: {
                        text: '',
                        align: 'high'
                    },
                    labels: {
                        style: {
                            fontSize: '10px',
                        },
                        overflow: 'justify',
                        enabled: false,
                    },
                },
                tooltip: {
                    valueSuffix: ' %'
                },
                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true,
                            format: '{point.y} %'
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
                series: [{
                    name: 'Cumplimiento',
                    showInLegend: false,
                    label: {
                        enabled: false
                    },
                    data: series,
                    // color: '#43beac'
                }],
                credits: {
                    enabled: false
                },
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
                // colors: ['#5eb9aa', '#f5bd22', '#ef5350'],
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
                            formatter: function() {
                                if (this.colorIndex == 0)
                                    return this.y + " %";
                                else
                                    return Highcharts.numberFormat(this.y, 0);
                            },
                            style: {
                                fontSize: '10px',
                                fontWeight: 'normal',
                            },
                        },
                        /* label: {
                            connectorAllowed: false
                        },
                        pointStart: 2010 */
                    }
                },

                // plotOptions: {
                //     /* columns: {
                //         stacking: 'normal'
                //     }, */
                //     series: {
                //         showInLegend: true,
                //         borderWidth: 0,
                //         dataLabels: {
                //             enabled: true,
                //             //format: '{point.y:,.0f}',
                //             //format: '{point.y:.1f}%',
                //             /* formatter: function() {
                //                 if (this.colorIndex == 2)
                //                     return this.y + " %";
                //                 else
                //                     return Highcharts.numberFormat(this.y, 0);
                //             }, */
                //             style: {
                //                 fontWeight: 'normal',
                //                 fontSize: '10px',
                //             }
                //         },
                //     },
                // },

                series: [{
                    name: 'Recuperados',
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

        function gLineaBasica2(div, data, titulo, subtitulo, titulovetical) {
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
                    name: 'Actas Enviadas',
                    // showInLegend: false,
                    data: data.dat
                }, {
                    name: 'Actas Aprobadas',
                    // showInLegend: false,
                    data: data.dat2
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
                tooltip: {
                    //pointFormat: '<span style="color:{point.color}">\u25CF</span> {point.name}<b>{point.y}</b><br/>',
                    shared: true
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
