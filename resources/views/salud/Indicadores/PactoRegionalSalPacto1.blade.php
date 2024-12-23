@extends('layouts.main', ['titlePage' => ''])
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header bg-success-0">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-orange-0 btn-xs" onclick="history.back()" title="ACTUALIZAR"><i
                                class="fas fa-arrow-left"></i> Volver</button>
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
                                <select id="anio" name="anio" class="form-control form-control-sm font-11"
                                    onchange="cargarcuadros();">
                                    @foreach ($anio as $item)
                                        <option value="{{ $item->anio }}" {{ $item->anio == $aniomax ? 'selected' : '' }}>
                                            {{ $item->anio }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-1 col-sm-1  ">
                            <div class="custom-select-container">
                                <label for="mes">Mes</label>
                                <select id="mes" name="mes" class="form-control form-control-sm font-11"
                                    onchange="cargarcuadros();">
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="provincia">Provincia</label>
                                <select id="provincia" name="provincia" class="form-control form-control-sm font-11"
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
                                <select id="distrito" name="distrito" class="form-control form-control-sm font-11"
                                    onchange="cargarcuadros();">
                                    <option value="0">TODOS</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                        <i class=" mdi mdi-city font-35 text-green-0"></i>
                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="gl"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">
                                Total Niños y Niñas
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
                                Cumplen
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

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent p-0">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-success btn-xs" onclick="descargar1()"><i
                                class="fa fa-file-excel"></i> Descargar</button>
                    </div>
                    <h3 class="text-black font-14 mb-0">Evaluación de cumplimiento de los registros de niños y niñas
                        menores de 6 años del padrón nominal
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table id="padronTable" class="table table-sm table-striped table-bordered font-11">
                                    <thead>
                                        <tr class="table-success-0 text-white">
                                            <th class="text-center">Nº</th>
                                            <th class="text-center">Tipo Doc.</th>
                                            <th class="text-center">Documento</th>
                                            <th class="text-center">Nombre</th>
                                            <th class="text-center">Fecha Nac.</th>
                                            <th class="text-center">Distrito</th>
                                            <th class="text-center">Seguro</th>
                                            <th class="text-center">Cód. EESS</th>
                                            <th class="text-center">EESS de Atención</th>
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
@endsection

@section('js')
    <script type="text/javascript">
        var ugel_select = 0;
        var anal1, anal2, anal3;
        var tablepadron;
        $(document).ready(function() {
            // Highcharts.setOptions({
            //     lang: {
            //         thousandsSep: ","
            //     }
            // });
            cargarMes();
            cargarDistritos();
            cargarcuadros();
        });

        function cargarcuadros() {
            panelGraficas('head');
            panelGraficas('anal1');
            panelGraficas('anal2');
            panelGraficas('anal3');
            panelGraficas('tabla1');
            // panelGraficas('tabla2');
            tabla2('tabla2');
        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('salud.indicador.pactoregional.detalle.reports') }}",
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
                            'Porcentaje Mensual de la Evaluación',
                            '', 'CALLERIA');
                    } else if (div == "anal3") {
                        anal3 = gColumnx(div, data.info, '',
                            'Población de niños y niñas menores de 6 años, según sexo', 'Etapa Vida')
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
                    } else if (div == "tabla2") {}

                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
        }

        function tabla2(div) {
            tablepadron = $('#padronTable').DataTable({
                responsive: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ordered: true,
                language: table_language,
                destroy: true,
                ajax: {
                    url: "{{ route('salud.indicador.pactoregional.detalle.reports.2') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
                        d.div = div;
                        d.anio = $('#anio').val();
                        d.mes = $('#mes').val();
                        d.provincia = $('#provincia').val();
                        d.distrito = $('#distrito').val();
                        d.fuente = {{ $fuente }};
                        d.indicador = '{{ $ind->id }}';
                        d.codigo = '{{ $ind->codigo }}';
                    }
                },
                columns: [{
                        data: 'item',
                        name: 'item',
                    },
                    {
                        data: 'tipo_doc',
                        name: 'tipo_doc',
                    },
                    {
                        data: 'num_doc',
                        name: 'num_doc',
                    },
                    {
                        data: 'nombre_completo',
                        name: 'nombre_completo',
                    },
                    {
                        data: 'nacimiento',
                        name: 'nacimiento',
                    },
                    {
                        data: 'distrito',
                        name: 'distrito',
                    },
                    {
                        data: 'seguro',
                        name: 'seguro',
                    },
                    {
                        data: 'ipress',
                        name: 'ipress',
                    },
                    {
                        data: 'nombre_establecimiento',
                        name: 'nombre_establecimiento',
                    },
                    {
                        data: 'num_doc_madre',
                        name: 'num_doc_madre',
                    },
                    {
                        data: 'nombre_completo_madre',
                        name: 'nombre_completo_madre',
                    },
                    {
                        data: 'estado',
                        name: 'estado',
                    }
                ],
                columnDefs: [{
                    className: 'text-center',
                    targets: [0, 1, 2, 4, 6, 7, 9, 11]
                }, {
                    targets: 2,
                    render: function(data, type, row) {
                        // return '<a href="/ruta/detalle/' + row + '">' + data + '</a>';
                        return `<a href="javascript:void(0)" onclick="abrirmodalpadron('${data}')">${data}</a>`;
                    }
                }, {
                    targets: 7,
                    render: function(data, type, row) {
                        // return '<a href="/ruta/detalle/' + row + '">' + data + '</a>';
                        // console.log(parseInt(data, 10));
                        return data ?
                            `<a href="javascript:void(0)" onclick="abrirmodaleess(${parseInt(data, 10)})">${data}</a>` :
                            '';
                    }
                }],
            });

        }

        function cargarTablaNivel(div, ugel) {
            $.ajax({
                url: "{{ route('indicador.nuevos.01.tabla') }}",
                data: {
                    'div': div,
                    "anio": $('#anio').val(),
                    "mes": $('#mes').val(),
                    "provincia": $('#provincia').val(),
                    "distrito": $('#distrito').val(),
                    "gestion": $('#gestion').val(),
                    "ugel": ugel
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    ugel_select = ugel;
                    if (div == "tabla1") {
                        $('#v' + div).html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                    } else if (div == "tabla2") {
                        $('#v' + div).html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                    } else {
                        $('#' + div).html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                    }
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

        function descargar1() {
            window.open(
                "{{ route('salud.indicador.pactoregional.sal.pacto1.excel', ['', '', '', '', '', '']) }}/tabla2/{{ $ind->id }}/" +
                $('#anio').val() + "/" + $('#mes').val() + "/" + $('#provincia').val() + "/" + $('#distrito').val());
        }

        function descargar2() {
            window.open("{{ url('/') }}/INDICADOR/Home/01/Excel/tabla2/" + $('#anio').val() + "/" + $('#provincia')
                .val() + "/" + $('#distrito').val() + "/" + $('#gestion').val() + "/" + ugel_select);
        }

        function verpdf(id) {
            window.open("{{ route('salud.indicador.pactoregional.exportar.pdf', '') }}/" + id);
        };

        function gColumnx(div, data, titulo, subtitulo, tooltip) {
            return Highcharts.chart(div, {
                chart: {
                    type: 'column'
                },
                colors: ['#5eb9a0', '#ef5350', '#f5bd22', '#ef5350'],
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

        function gColumn(div, categorias, datos, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'column' // Cambia el tipo de 'line' a 'column'
                },
                title: {
                    text: titulo, // 'Número de actas de homologación registradas en el sistema de padrón nominal por mes'
                },
                subtitle: {
                    text: subtitulo // 'Número de actas de homologación registradas en el sistema de padrón nominal por mes'
                },
                xAxis: {
                    categories: categorias, // ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SET', 'OCT', 'NOV', 'DIC']
                    labels: {
                        style: {
                            fontSize: '10px'
                        }
                    },
                },
                yAxis: {
                    min: 0,
                    title: {
                        enabled: false,
                        // text: 'Número de actas'
                    },
                    labels: {
                        style: {
                            fontSize: '10px'
                        }
                    }
                },
                plotOptions: {
                    // column: {
                    //     dataLabels: {
                    //         enabled: true,
                    //         format: '{y}'
                    //     }
                    // },
                    series: {
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '10px',
                                fontWeight: 'normal',
                            }
                        },
                    }
                },
                tooltip: {
                    shared: true,
                    headerFormat: '<b>{point.key}</b><br/>',
                    pointFormat: '{series.name}: {point.y}<br/>'
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
                series: datos,
                /* [{
                                   name: 'Actas Enviadas',
                                   data: [76, 28, 53, 46, 100, 10, 0, 0, 0, 0, 0, 0]
                               }, {
                                   name: 'Actas Aprobadas',
                                   data: [10, 8, 9, 9, 14, 10, 0, 0, 0, 0, 0, 0]
                               }] */
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
