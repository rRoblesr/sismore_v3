@extends('layouts.main', ['titlePage' => ''])
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
    <style>
        .vertical {
            writing-mode: vertical-lr;
            transform: rotate(180deg);
        }
    </style>
@endsection

@section('content')
    {{-- <div class="card  card-border border border-plomo-0">
    <div
        class="card-header border-success-0 bg-transparent text-white d-flex flex-column flex-md-row justify-content-between align-items-md-center px-2 py-2">
        <h6 class="card-title mb-2 mb-md-0 text-center text-primary text-md-left text-wrap">
            Directorio de establecimientos de salud de Ucayali
        </h6>
        <div class="text-center text-md-right">
            <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()">
                <i class="fa fa-redo"></i> Actualizar
            </button>
        </div>
    </div>
    <div class="card-body p-2">
        <!-- Contenido de la tarjeta -->
    </div>
</div> --}}

    {{-- <div class="card">
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
     
    </div>
</div> --}}

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
                        <select id="anio" name="anio" class="form-control font-12" onchange="cargarMes();">
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
                        <select id="mes" name="mes" class="form-control font-12" onchange="cargarcuadros();">
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
                        <select id="distrito" name="distrito" class="form-control font-12" onchange="cargarcuadros();">
                            <option value="0">TODOS</option>
                        </select>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
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
                            <p class="mb-0 mt-1 text-truncate">Meta del indicador</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card-box-->
        </div>

        <div class="col-md-3">
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
                                {{-- <span onclick="verinformacion(0)" data-toggle="modal" data-target="#info_denominador">
                                    <i class="mdi mdi-rotate-180 mdi-alert-circle" style="color:#43beac;"></i>
                                </span> --}}
                                Avance del Indicador
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card-box-->
        </div>

        <div class="col-md-3">
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
                                {{-- <span onclick="verinformacion(1)" data-toggle="modal" data-target="#info_numerador">
                                    <i class="mdi mdi-rotate-180 mdi-alert-circle" style="color:#43beac;"></i>
                                </span> --}}
                                Población
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card-box-->
        </div>

        <div class="col-md-3">
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
                                Matriculados
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent p-0">
                    {{-- <div class="card-widgets">
                    <button type="button" class="btn btn-success btn-xs"><i
                            class="fa fa-file-excel"></i> Descargar</button>
                </div> --}}
                    <h3 class="text-black font-14 mb-0">Avance acumulado de la evaluación de Cumplimiento por
                        Distrito
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
                    <div id="anal2" style="height: 20rem"></div>
                </div>
            </div>

        </div>

        <div class="col-lg-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent p-0">
                    {{-- <h3 class="text-black text-center font-weight-normal font-11"></h3> --}}
                </div>
                <div class="card-body p-0">
                    <div id="anal3" style="height: 20rem"></div>
                </div>
            </div>

        </div>
    </div>


    <div class="card  card-border border border-plomo-0">
        <div
            class="card-header border-success-0 bg-transparent text-white d-flex flex-column flex-md-row justify-content-between align-items-md-center px-2 py-2">
            <h6 class="card-title mb-2 mb-md-0 text-center text-md-left text-wrap">
                Evaluación de cumplimiento de los registros de niños y niñas
                menores de 6 años del padrón nominal
            </h6>
            <div class="text-center text-md-right">
                <button type="button" class="btn btn-success btn-xs" onclick="descargar0100()">
                    <i class="fa fa-file-excel"></i> Descargar</button>
            </div>
        </div>
        <div class="card-body p-2">
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table id="tabla2" class="table table-sm table-striped table-bordered font-11 m-0">
                            <thead>
                                <tr class="bg-success-0 text-white text-center">
                                    <th>N°</th>
                                    <th>Documento</th>
                                    <th>Provincia</th>
                                    <th>Distrito</th>
                                    <th>Centro Poblado</th>
                                    <th>Area Geografica</th>
                                    <th>EESS Atencion</th>
                                    <th>Codigo Modular</th>
                                    <th>Institucion Educativa</th>
                                    <th>Matriculado</th>
                                </tr>
                            </thead>
                            <tbody></tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--  Modal content for the above example -->
    <div class="modal fade" id="modal-nino" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Niño(a) con datos observados</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="xx" class="table table-striped table-bordered font-12 text-dark">
                                    <tbody>
                                        <tr>
                                            <td class="text-right" style="background-color: #D4F2F0">CÓDIGO PADRÓN</td>
                                            <td id="padron"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">TIPO DOCUMENTO</td>
                                            <td id="tipodoc"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">DOCUMENTO</td>
                                            <td id="doc"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="background-color: #D4F2F0">APELLIDO PATERNO</td>
                                            <td id="apepat"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">APELLIDO MATERNO</td>
                                            <td id="apemat"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">NOMBRES</td>
                                            <td id="nom"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="background-color: #D4F2F0">SEXO</td>
                                            <td id="sexo"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">FECHA DE NACIMIENTO
                                            </td>
                                            <td id="nacimiento"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">EDAD</td>
                                            <td id="edad"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="background-color: #D4F2F0">DEPARTAMENTO</td>
                                            <td id="dep"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">PROVINCIA</td>
                                            <td id="pro"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">DISTRITO</td>
                                            <td id="dis"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="background-color: #D4F2F0">CENTRO POBLADO</td>
                                            <td id="cp"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">DIRECCIÓN</td>
                                            <td id="dir" colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="background-color: #D4F2F0">EESS NACIMIENTO</td>
                                            <td id="esn"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">ULTIMO EESS ATENCIÓN
                                            </td>
                                            <td id="esa"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">VISITA DOMICILIARIA
                                            </td>
                                            <td id="visita"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="background-color: #D4F2F0">ENCONTRADO</td>
                                            <td id="encontrado"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">TIPO DE SEGURO</td>
                                            <td id="seguro"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">PROGRAMA SOCIAL</td>
                                            <td id="programa"></td>
                                        </tr>
                                        <tr>
                                            <td class="" colspan="6"></td>
                                        </tr>
                                        {{-- <tr>
                                            <td>INSTITUTCIÓN EDUCATIVA</td>
                                            <td></td>
                                            <td>NIVEL EDUCATIVO</td>
                                            <td></td>
                                            <td>GRADO Y SECCIÓN</td>
                                            <td></td>
                                        </tr> --}}
                                        <tr>
                                            <td class="text-right" style="background-color: #D4F2F0">APODERADO</td>
                                            <td id="mapoderado"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">TIPO DOCUMENTO</td>
                                            <td id="mtipodoc"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">DOCUMENTO</td>
                                            <td id="mdoc"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="background-color: #D4F2F0">APELLIDO PATERNO
                                                MADRE</td>
                                            <td id="mapepat"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">APELLIDO MATERNO
                                                MADRE</td>
                                            <td id="mapemat"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">NOMBRES MADRE</td>
                                            <td id="mnom"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="background-color: #D4F2F0">CELULAR</td>
                                            <td id="mcel"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">GRADO DE INSTRUCCIÓN
                                            </td>
                                            <td id="mgrado"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">LENGUA HABITUAL</td>
                                            <td id="mlengua"></td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div>

                </div>
            </div>



        </div>
    </div><!-- /.modal -->

    <!--  Modal content for the above example -->
    <div class="modal fade" id="modal-eess" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Datos del Establecimiento de Salud</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="xx2" class="table table-striped table-bordered font-12 text-dark">
                                    <tbody>
                                        <tr>
                                            <td class="text-right" style="background-color: #D4F2F0">CÓDIGO ÚNICO</td>
                                            <td id="eesscui"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">NOMBRE DEL
                                                ESTABLECIMIENTO</td>
                                            <td id="eessnombre" colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="background-color: #D4F2F0">DISA</td>
                                            <td id="eessdisa"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">RED</td>
                                            <td id="eessred"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">MICRORED</td>
                                            <td id="eessmicro"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="background-color: #D4F2F0">DEPARTAMENTO</td>
                                            <td id="eessdep"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">PROVINCIA</td>
                                            <td id="eesspro"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">DISTRITO</td>
                                            <td id="eessdis"></td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div>

                </div>
            </div>



        </div>
    </div><!-- /.modal -->


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
            </div>
        </div>
    </div>

    <div id="modal_info_ipress" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Modal Heading</h5>
                    <button type="button" class="btn btn-success btn-xs ml-auto" onclick="descargar0101()">
                        <i class="fa fa-file-excel"></i> Descargar</button>

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table id="tabla0201" class="table table-sm table-striped table-bordered font-11">
                                <thead>
                                    <tr class="table-success-0 text-white">
                                        <th class="text-center">Nº</th>
                                        <th class="text-center">Tipo Doc. del Niño</th>
                                        <th class="text-center">Documento</th>
                                        <th class="text-center">Nombre del Niño</th>
                                        <th class="text-center">Fecha Nac.</th>
                                        <th class="text-center">Distrito</th>
                                        <th class="text-center">Seguro</th>
                                        <th class="text-center">Doc. Madre</th>
                                        <th class="text-center">Nombre Madre</th>
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
        var anal1, anal2, anal3;
        var tablepadron;
        var table02; // = $('#table2').DataTable();
        var tablex;
        var tablexx;
        $(document).ready(function() {
            // Highcharts.setOptions({
            //     lang: {
            //         thousandsSep: ","
            //     }
            // });
            cargarMes();
            cargarDistritos();
            // cargarcuadros();
        });

        function cargarcuadros() {
            panelGraficas('head');
            panelGraficas('anal1');
            panelGraficas('anal2');
            panelGraficas('anal3');
            panelGraficas('tabla1');
            tabla2('tabla2');
        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('educacion.indicador.conveniofed.detalle.reports') }}",
                data: {
                    'div': div,
                    "anio": $('#anio').val(),
                    "mes": $('#mes').val(),
                    "provincia": $('#provincia').val(),
                    "distrito": $('#distrito').val(),
                    "fuente": {{ $fuente }},
                    "indicador": '{{ $ind->id }}',
                    "codigo": '{{ $ind->codigo }}',
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
                        $('#ri').text('74.7%');
                        $('#gl').text(data.ri + '%');
                        $('#gls').text(data.gl);
                        $('#gln').text(data.gls);
                    } else if (div == "anal1") {
                        gbar('anal1', data.info.categoria,
                            data.info.serie,
                            '',
                            'Porcentaje de Cumplimiento por Distrito',
                        );
                    } else if (div == "anal2") {
                        graficarMatriculados(div,
                            data.info.categorias,
                            data.info.poblacion,
                            data.info.matriculados);
                    } else if (div == "anal3") {
                        graficarMatriculados(div,
                            data.info.categorias,
                            data.info.poblacion,
                            data.info.matriculados);
                    } else if (div == "tabla1") {
                        $('#vtabla1').html(data.excel);
                        // $('#tabla1').DataTable({
                        //     // responsive: false,
                        //     // autoWidth: false,
                        //     // ordered: true,
                        //     // searching: false,
                        //     // bPaginate: false,
                        //     // info: false,
                        //     // language: table_language,
                        //     paging: false,
                        //     info: false,
                        //     searching: false,
                        // });
                    } else if (div == "tabla2") {

                    }

                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
        }

        function tabla2(div) {
            tablex = $('#tabla2').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: false,
                language: table_language,
                destroy: true,
                ajax: {
                    "url": "{{ route('educacion.indicador.conveniofed.detalle.reports') }}",
                    "type": "GET",
                    //"dataType": 'JSON',
                    data: {
                        'div': div,
                        "anio": $('#anio').val(),
                        "mes": $('#mes').val(),
                        "provincia": $('#provincia').val(),
                        "distrito": $('#distrito').val(),
                        "fuente": {{ $fuente }},
                        "indicador": '{{ $ind->id }}',
                        "codigo": '{{ $ind->codigo }}',
                    },
                },
                columnDefs: [{
                    targets: 1,
                    render: function(data, type, row) {
                        return '<a href="javascript:void(0)" onclick="abrirmodalinfoipress(`' + data +
                        '`)">' + data +
                            '</a>';
                    }
                }, {
                    targets: [0, 1, 7, 8, 9],
                    className: 'text-center'
                }, 
                // {
                //     targets: 10,
                //     render: function(data, type, row) {
                //         return data == 1 ?
                //             '<span class="badge badge-pill badge-success" style="font-size:100%;"> Cumple </span>' :
                //             '<span class="badge badge-pill badge-danger" style="font-size:100%;"> No Cumple </span>';
                //     }
                // }
            ],
            });
        }

        function cargarMes() {
            $.ajax({
                url: "{{ route('importacion.listar.mes', ['fuente' => 'fuente', 'anio' => 'anio']) }}"
                    .replace('fuente', {{ $fuente }})
                    .replace('anio', $('#anio').val()),
                type: 'GET',
                success: function(data) {
                    $("#mes option").remove();
                    var options = ''; // '<option value="0"></option>';
                    var ultimovalor = data.length > 0 ? data[data.length - 1].mes_id : null;
                    $.each(data, function(index, value) {
                        ss = (value.mes_id === ultimovalor ? "selected" : "");
                        options += `<option value='${value.mes_id}' ${ss}>${value.mes}</option>`;
                    });
                    $("#mes").append(options);
                    cargarcuadros();
                },
                error: function(jqXHR, textStatus, errorThrown) {
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

        function abrirmodalpadron(padron) {

            $.ajax({
                url: "{{ route('salud.padronnominal.tablerocalidad.criterio.find3', ['fuente' => ':fuente', 'anio' => ':anio', 'mes' => ':mes', 'documento' => ':documento']) }}"
                    .replace(':fuente', {{ $fuente }})
                    .replace(':anio', $('#anio').val())
                    .replace(':mes', $('#mes').val())
                    .replace(':documento', padron),
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    $('#padron').html(data.padron);
                    $('#tipodoc').html(data.tipo_doc == 'Padron' ? '' : data.tipo_doc);
                    $('#doc').html(data.tipo_doc == 'Padron' ? '' : data.num_doc);
                    $('#apepat').html(data.apellido_paterno);
                    $('#apemat').html(data.apellido_materno);
                    $('#nom').html(data.nombre);
                    $('#sexo').html(data.genero == 'M' ? 'MASCULINO' : 'FEMENINO');
                    $('#nacimiento').html(data.fecha_nacimiento);
                    $('#edad').html(data.edad + ' ' + (data.tipo_edad == 'D' ? 'DIAS' : (data.tipo_edad == 'M' ?
                        'MESES' : 'AÑOS')));
                    $('#dep').html(data.departamento);
                    $('#pro').html(data.provincia);
                    $('#dis').html(data.distrito);
                    $('#cp').html(data.centro_poblado_nombre);
                    $('#dir').html(data.direccion);
                    $('#esn').html(data.cui_nacimiento);
                    $('#esa').html(data.cui_atencion);
                    $('#visita').html(data.visita);
                    $('#encontrado').html(data.menor_encontrado);
                    $('#seguro').html(data.seguro);
                    $('#programa').html(data.programa_social);
                    $('#mapoderado').html(data.apoderado);
                    $('#mtipodoc').html(data.tipo_doc_madre);
                    $('#mdoc').html(data.num_doc_madre);
                    $('#mapepat').html(data.apellido_paterno_madre);
                    $('#mapemat').html(data.apellido_materno_madre);
                    $('#mnom').html(data.nombres_madre);
                    $('#mcel').html(data.celular_madre);
                    $('#mgrado').html(data.grado_instruccion);
                    $('#mlengua').html(data.lengua_madre);

                    $('#modal-nino').modal('show');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function abrirmodaleess(cui) {

            $.ajax({
                url: "{{ route('salud.padronnominal.tablerocalidad.criterio.find2', ['importacion' => 0, 'cui' => 'cui']) }}"
                    .replace('cui', cui),
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $('#eesscui').html(data.codigo_unico);
                    $('#eessnombre').html(data.nombre_establecimiento);
                    $('#eessdisa').html(data.disa);
                    $('#eessred').html(data.red);
                    $('#eessmicro').html(data.micro);
                    $('#eessdep').html(data.departamento);
                    $('#eesspro').html(data.provincia);
                    $('#eessdis').html(data.distrito);
                    $('#modal-eess').modal('show');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function abrirmodalinfoipress(codigo_unico) {
            $.ajax({
                url: "{{ route('eess.find.cod_unico.02', ['cod_unico' => ':cod_unico']) }}"
                    .replace(':cod_unico', parseInt(codigo_unico, 10)),
                type: 'GET',
                // dataType: 'json',
                beforeSend: function() {
                    $('#modal_info_ipress .modal-title').text('Cargando...');
                },
                success: function(data) {
                    $('#modal_info_ipress .modal-title').html(`${codigo_unico} ${data.nombre}`);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $('#modal_info_ipress').modal('show');

            tablexx = $('#tabla0201').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: false,
                language: table_language,
                destroy: true,
                ajax: {
                    "url": "{{ route('salud.indicador.pactoregional.detalle.reports') }}",
                    "type": "GET",
                    //"dataType": 'JSON',
                    data: {
                        'div': 'tabla0201',
                        "anio": $('#anio').val(),
                        "mes": $('#mes').val(),
                        "provincia": $('#provincia').val(),
                        "distrito": $('#distrito').val(),
                        "fuente": {{ $fuente }},
                        "indicador": '{{ $ind->id }}',
                        "codigo": '{{ $ind->codigo }}',
                        "cod_unico": parseInt(codigo_unico, 10),
                    },
                },
                columnDefs: [{
                    targets: 9,
                    render: function(data, type, row) {
                        return data == 1 ?
                            '<span class="badge badge-pill badge-success" style="font-size:100%;"> Cumple </span>' :
                            '<span class="badge badge-pill badge-danger" style="font-size:100%;"> No Cumple </span>';
                    }
                }]
            });
        }

        function descargar0100() {
            window.open(
                "{{ route('salud.indicador.pactoregional.sal.pacto1.excel', ['', '', '', '', '', '']) }}/tabla2/{{ $ind->id }}/" +
                $('#anio').val() + "/" + $('#mes').val() + "/" + $('#provincia').val() + "/" + $('#distrito').val());
        }

        function descargar0101() {
            window.open("{{ url('/') }}/INDICADOR/Home/01/Excel/tabla2/" + $('#anio').val() + "/" + $('#provincia')
                .val() + "/" + $('#distrito').val() + "/" + $('#gestion').val() + "/" + ugel_select);
        }

        function verpdf(id) {
            window.open("{{ route('salud.indicador.pactoregional.exportar.pdf', '') }}/" + id);
        };

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

        function graficarMatriculadosx1(div, categorias, poblacion, matriculados) {
            const porcentajes = matriculados.map((val, i) => {
                return parseFloat(((val / poblacion[i]) * 100).toFixed(1));
            });

            Highcharts.chart(div, {
                chart: {
                    zoomType: 'xy',
                    style: {
                        fontSize: '12px' // Fuente global
                    }
                },
                title: {
                    text: '',
                    style: {
                        fontSize: '13px'
                    }
                },
                subtitle: {
                    text: 'Población de niñas y niños de 3 años matriculados por provincia',
                    style: {
                        fontSize: '13px'
                    }
                },
                xAxis: {
                    categories: categorias,
                    crosshair: true
                },
                yAxis: [{
                    title: {
                        text: 'Cantidad'
                    }
                }, {
                    title: {
                        text: 'Porcentaje'
                    },
                    opposite: true,
                    max: 100
                }],
                tooltip: {
                    shared: true
                },
                series: [{
                        name: 'Población',
                        type: 'column',
                        data: poblacion,
                        color: '#00bcd4'
                    },
                    {
                        name: 'Matriculados',
                        type: 'column',
                        data: matriculados,
                        color: '#e91e63'
                    },
                    {
                        name: '% Matriculados',
                        type: 'spline',
                        yAxis: 1,
                        data: porcentajes,
                        tooltip: {
                            valueSuffix: ' %'
                        },
                        marker: {
                            enabled: true
                        },
                        dataLabels: {
                            enabled: true,
                            format: '{y} %'
                        },
                        color: '#d32f2f'
                    }
                ]
            });
        }

        function graficarMatriculadosx2(div, categorias, poblacion, matriculados) {
            const porcentajes = matriculados.map((val, i) => {
                return parseFloat(((val / poblacion[i]) * 100).toFixed(1));
            });

            Highcharts.chart(div, {
                chart: {
                    zoomType: 'xy',
                    style: {
                        fontSize: '11px'
                    }
                },
                title: {
                    text: 'Población de niñas y niños de 3 años matriculados por provincia',
                    style: {
                        fontSize: '11px'
                    }
                },
                xAxis: {
                    categories: categorias,
                    crosshair: true,
                    labels: {
                        style: {
                            fontSize: '11px'
                        }
                    }
                },
                yAxis: [{
                        // Línea (porcentaje) arriba
                        title: {
                            text: 'Porcentaje',
                            style: {
                                fontSize: '11px'
                            }
                        },
                        top: '0%',
                        height: '40%',
                        max: 100,
                        labels: {
                            style: {
                                fontSize: '11px'
                            }
                        }
                    },
                    {
                        // Barras abajo
                        title: {
                            text: 'Cantidad',
                            style: {
                                fontSize: '11px'
                            }
                        },
                        top: '45%',
                        height: '55%',
                        offset: 0,
                        labels: {
                            style: {
                                fontSize: '11px'
                            }
                        }
                    }
                ],
                tooltip: {
                    shared: true,
                    style: {
                        fontSize: '11px'
                    }
                },
                legend: {
                    itemStyle: {
                        fontSize: '11px'
                    }
                },
                series: [{
                        name: 'Población',
                        type: 'column',
                        yAxis: 1, // abajo
                        data: poblacion,
                        color: '#00bcd4'
                    },
                    {
                        name: 'Matriculados',
                        type: 'column',
                        yAxis: 1, // abajo
                        data: matriculados,
                        color: '#e91e63'
                    },
                    {
                        name: '% Matriculados',
                        type: 'spline',
                        yAxis: 0, // arriba
                        data: porcentajes,
                        tooltip: {
                            valueSuffix: ' %'
                        },
                        marker: {
                            enabled: true
                        },
                        dataLabels: {
                            enabled: true,
                            format: '{y} %',
                            style: {
                                fontSize: '11px'
                            }
                        },
                        color: '#d32f2f'
                    }
                ]
            });
        }

        function graficarMatriculados(div, categorias, poblacion, matriculados) {
            const porcentajes = matriculados.map((val, i) => {
                return parseFloat(((val / poblacion[i]) * 100).toFixed(1));
            });

            Highcharts.chart(div, {
                chart: {
                    zoomType: 'xy',
                    style: {
                        fontSize: '11px'
                    }
                },
                // color:["#5eb9aa", "#f5bd22", "#e65310"],// ['#ef5350', '#5eb9a0', '#f5bd22', '#ef5350'],
                title: {
                    text: 'Población de niñas y niños de 3 años matriculados por provincia',
                    style: {
                        fontSize: '11px'
                    }
                },
                xAxis: {
                    categories: categorias,
                    crosshair: true,
                    labels: {
                        style: {
                            fontSize: '11px'
                        }
                    }
                },
                yAxis: [{
                        // Línea (porcentaje) arriba
                        title: {
                            text: null
                        },
                        top: '0%',
                        height: '40%',
                        max: 100,
                        labels: {
                            enabled: false // ❌ Oculta los números del eje Y
                        }
                    },
                    {
                        // Barras (cantidad) abajo
                        title: {
                            text: null
                        },
                        top: '45%',
                        height: '55%',
                        offset: 0,
                        labels: {
                            enabled: false // ❌ Oculta los números del eje Y
                        }
                    }
                ],
                tooltip: {
                    shared: true,
                    style: {
                        fontSize: '11px'
                    }
                },
                legend: {
                    itemStyle: {
                        fontSize: '11px'
                    }
                },

                series: [{
                        name: 'Población',
                        type: 'column',
                        yAxis: 1,
                        data: poblacion,
                        color: '#5eb9aa',
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '11px'
                            }
                        }
                    },
                    {
                        name: 'Matriculados',
                        type: 'column',
                        yAxis: 1,
                        data: matriculados,
                        color: '#e65310',
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '11px'
                            }
                        }
                    },
                    {
                        name: '% Avance',
                        type: 'spline',
                        yAxis: 0,
                        data: porcentajes,
                        showInLegend: false, // 👈 Oculta solo esta leyenda
                        tooltip: {
                            valueSuffix: ' %'
                        },
                        marker: {
                            enabled: true
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: function() {
                                return this.y + ' %';
                            },
                            style: {
                                fontSize: '11px'
                            }
                        },
                        color: '#d32f2f'
                    }
                ]

            });
        }


        function gColumn(div, data, titulo, subtitulo, tooltip) {
            return Highcharts.chart(div, {
                chart: {
                    type: 'column'
                },
                colors: ['#ef5350', '#5eb9a0', '#f5bd22', '#ef5350'],
                title: {
                    text: titulo
                },
                subtitle: {
                    text: subtitulo //null // Si no necesitas un subtítulo, puedes dejarlo como null
                },
                xAxis: {
                    categories: data.categoria, //
                    crosshair: true,
                    labels: {
                        style: {
                            fontSize: '11px' // Ajusta el tamaño de la fuente
                        }
                    },
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: null // Puedes agregar un título si lo necesitas
                    },
                    labels: {
                        style: {
                            fontSize: '11px' // Ajusta el tamaño de la fuente
                        }
                    },
                },
                tooltip: {
                    shared: true, // Muestra los valores de todas las series en el mismo tooltip
                    formatter: function() {
                        let tooltipText = '<b>' + tooltip + ': ' + this.x +
                            '</b><br/>'; // Muestra la categoría (año)
                        this.points.forEach(function(point) {
                            tooltipText += point.series.name + ': ' + Highcharts.numberFormat(Math.abs(
                                point.y), 0) + '<br/>';
                        });
                        return tooltipText;
                    }
                },
                plotOptions: {
                    column: {
                        stacking: data.serie.length > 1 ? 'normal' : null, // Apila las columnas
                        dataLabels: {
                            enabled: true,
                            formatter: function() {
                                return Highcharts.numberFormat(Math.abs(this.y),
                                    0); // Formatea los números con separadores de miles
                            },
                            style: {
                                color: data.serie.length > 1 ? 'white' : 'black',
                                textOutline: 'none',
                                fontSize: '10px'
                            }
                        }
                    }
                },
                series: data.serie,
                legend: {
                    enabled: data.serie.length > 1,
                    itemStyle: {
                        //color: "#333333",
                        // cursor: "pointer",
                        fontSize: "11px",
                        // fontWeight: "normal",
                        // textOverflow: "ellipsis"
                    },
                },
                credits: {
                    enabled: false,
                    text: 'Fuente: RENIEC - PADRÓN NOMINAL | Actualizado: JULIO 2024',
                    href: null,
                    position: {
                        align: 'center',
                        verticalAlign: 'bottom',
                        x: 0,
                        y: -5
                    },
                    style: {
                        color: '#666',
                        fontSize: '10px',
                        textAlign: 'center'
                    }
                }
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

        function gLineaBasica(div, data, titulo, subtitulo, titulovetical, categoriaSeleccionada) {
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
                            },
                            formatter: function() {
                                return this.y + '%';
                            }
                        },
                        /* label: {
                            connectorAllowed: false
                        },
                        pointStart: 2010 */
                    }
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.y}%</b>',
                    shared: true
                },
                series: [{
                    name: 'Cumplen',
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
