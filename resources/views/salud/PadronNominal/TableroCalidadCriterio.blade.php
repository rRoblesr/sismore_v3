@extends('layouts.main', ['activePage' => 'importacion', 'titlePage' => ''])

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
    <style>

    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header bg-success-0">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-orange-0 btn-xs" onclick="history.back()" title='ACTUALIZAR'><i
                                class="fas fa-arrow-left"></i> Volver</button>
                        <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()">
                            <i class="fa fa-redo"></i> Actualizar</button>
                    </div>
                    <h3 class="card-title text-white font-12">
                        {{-- Registros sin Número de Documento (DNI, CNV, CUI) del Menor --}}
                        {{ $title }}
                    </h3>
                </div>

                <div class="card-body pb-0">
                    <div class="form-group row align-items-center vh-5">

                        <div class="col-lg-3 col-md-4 col-sm-4">
                            <h4 class="page-title font-12">{{ $actualizado }}</h4>
                        </div>

                        <div class="col-lg-3 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="edades">Edad del Menor</label>
                                <select id="edades" name="edades" class="form-control btn-xs font-11"
                                    onchange="cargarProvincia();cargarCards();">
                                    <option value="0">TODOS</option>
                                    @foreach ($edades as $item)
                                        <option value="{{ $item->edades_id }}">{{ $item->edades }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="provincia">Provincia</label>
                                <select id="provincia" name="provincia" class="form-control btn-xs font-11"
                                    onchange="cargarDistrito();cargarCards();">
                                    <option value="0">TODOS</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="distrito">Distrito</label>
                                <select id="distrito" name="distrito" class="form-control btn-xs font-11"
                                    onchange="cargarCards();">
                                    <option value="0">TODOS</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- <div class="card-body pb-0">
                    <div class="form-group row align-items-center vh-5">

                        <div class="col-lg-3 col-md-4 col-sm-4">
                            <h4 class="page-title font-12">{{ $actualizado }}</h4>
                        </div>

                        <div class="col-lg-3 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="red">RED</label>
                                <select id="red" name="red" class="form-control btn-xs font-11"
                                    onchange="cargarMicrored();cargarCards();">
                                    <option value="0">TODOS</option>
                                    @foreach ($red as $item)
                                        <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="microred">MICRORED</label>
                                <select id="microred" name="microred" class="form-control btn-xs font-11"
                                    onchange="cargarEstablecimiento();cargarCards();">
                                    <option value="0">TODOS</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="establecimiento">ESTABLECIMIENTO</label>
                                <select id="establecimiento" name="establecimiento" class="form-control btn-xs font-11"
                                    onchange="cargarCards();">
                                    <option value="0">TODOS</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-success-0 btn-xs" onclick="descargarExcel()">
                            <i class="fa fa-file-excel"></i> Descargar</button>
                    </div>
                    <h3 class="card-title">LISTA DE REGISTROS OBSERVADOS {{ $criterio < 10 ? 'DEL MENOR' : 'DE LA MADRE' }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                @if (in_array($criterio, range(1, 9)))
                                    <table id="tabla1" class="table table-sm table-striped table-bordered font-10">
                                        <thead>
                                            <tr class="table-success-0 text-white">
                                                <th class="text-center">N°</th>
                                                <th class="text-center">Cód. Padrón</th>
                                                <th class="text-center">Tipo Doc.</th>
                                                <th class="text-center">Documento</th>
                                                <th class="text-center">Nombres del Menor</th>
                                                <th class="text-center">Fecha Nacimiento</th>
                                                <th class="text-center">Seguro</th>
                                                <th class="text-center">Visitado</th>
                                                <th class="text-center">Encontrado</th>
                                                <th class="text-center">Distrito</th>
                                                <th class="text-center">Cod. EESS</th>
                                                <th class="text-center">EESS de Atención</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                @else
                                    <table id="tabla1" class="table table-sm table-striped table-bordered font-10">
                                        <thead>
                                            <tr class="table-success-0 text-white">
                                                <th class="text-center">N°</th>
                                                <th class="text-center">Cód. Padrón</th>
                                                <th class="text-center">Tipo Doc.</th>
                                                <th class="text-center">Documento</th>
                                                <th class="text-center">Nombres de la Madre</th>
                                                <th class="text-center">Celular</th>
                                                <th class="text-center">Grado instrucción</th>
                                                <th class="text-center">Lengua Habitual</th>
                                                <th class="text-center">Distrito</th>
                                                <th class="text-center">Cod. EESS</th>
                                                <th class="text-center">EESS de Atención</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                @endif


                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                    <h3 class="text-black text-center font-weight-normal font-11 m-0"></h3>
                </div>
                <div class="card-body p-0">
                    <div id="anal1" style="height: 20rem"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                    <h3 class="text-black text-center font-weight-normal font-11 m-0"></h3>
                </div>
                <div class="card-body p-0">
                    <div id="anal2" style="height: 20rem"></div>
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
        var paleta_colores = ['#5eb9aa', '#F9FFFE', '#f5bd22', '#058DC7', '#50B432', '#9D561B', '#DDDF00', '#24CBE5',
            '#64E572', '#9F9655', '#FFF263', '#6AF9C4'
        ];
        var tableprincipal;
        var criterio = {{ $criterio }};
        var anal1;
        var anal2;
        var column;
        if (criterio < 10) {
            column = [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                }, {
                    data: 'padron',
                    name: 'padron'
                },
                {
                    data: 'atipodoc',
                    name: 'atipodoc'
                },
                {
                    data: 'adoc',
                    name: 'adoc'
                },
                {
                    data: 'anombre',
                    name: 'anombre'
                },
                {
                    data: 'aedad',
                    name: 'aedad'
                },
                {
                    data: 'aseguro',
                    name: 'aseguro'
                },
                {
                    data: 'avisita',
                    name: 'avisita'
                },
                {
                    data: 'aencontrado',
                    name: 'aencontrado'
                },
                {
                    data: 'adistrito',
                    name: 'adistrito'
                },
                {
                    data: 'acui',
                    name: 'acui'
                },
                {
                    data: 'aeesss',
                    name: 'aeesss'
                }
            ]
        } else {
            column = [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                }, {
                    data: 'padron',
                    name: 'padron'
                },
                {
                    data: 'atipodoc',
                    name: 'atipodoc'
                },
                {
                    data: 'adoc',
                    name: 'adoc'
                },
                {
                    data: 'anombre',
                    name: 'anombre'
                },
                {
                    data: 'aedad',
                    name: 'aedad'
                },
                {
                    data: 'aseguro',
                    name: 'aseguro'
                },
                {
                    data: 'avisita',
                    name: 'avisita'
                },
                {
                    data: 'adistrito',
                    name: 'adistrito'
                },
                {
                    data: 'acui',
                    name: 'acui'
                },
                {
                    data: 'aeesss',
                    name: 'aeesss'
                }
            ]
        };
        $(document).ready(function() {
            cargarProvincia();
            cargarDistrito();
            cargarCards();

        });

        function cargarCards() {
            tableprincipal = $('#tabla1').DataTable({
                responsive: true,
                autoWidth: false,
                processing: true, // Indica que los datos se procesan en el servidor
                serverSide: true, // Habilita la paginación en el servidor
                ordered: true,
                language: table_language,
                destroy: true,
                ajax: {
                    url: "{{ route('salud.padronnominal.tablerocalidad.criterio.listar3') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Agrega el token CSRF
                    },
                    data: function(d) {
                        d.importacion = {{ $importacion }};
                        d.criterio = {{ $criterio }};
                        d.edades = $('#edades').val();
                        d.provincia = $('#provincia').val();
                        d.distrito = $('#distrito').val();
                        d.desa = 0;
                    }
                },
                columns: column,

                columnDefs: [{
                        className: 'text-center',
                        targets: [0, 1, 2, 3, 5, 6, 7, 8]
                    },
                    {
                        targets: 1,
                        render: function(data, type, row) {
                            return `<a href="javascript:void(0)" onclick="abrirmodalpadron(${data})">${data}</a>`;
                        }
                    },
                    {
                        targets: 9,
                        render: function(data, type, row) {

                            if (criterio == 10 || criterio == 11 || criterio == 12 || criterio == 13) {
                                return data ?
                                    `<a href="javascript:void(0)" onclick="abrirmodaleess(${parseInt(data, 10)})">${data}</a>` :
                                    '';
                            } else {
                                return data;
                            }

                        }
                    },
                    {
                        targets: 10,
                        render: function(data, type, row) {
                            if (criterio == 10 || criterio == 11 || criterio == 12 || criterio == 13) {
                                return data;
                            } else {
                                return data ?
                                    `<a href="javascript:void(0)" onclick="abrirmodaleess(${parseInt(data, 10)})">${data}</a>` :
                                    '';

                            }
                        }
                    }
                ]
            });

            tableprincipalxxx = $('#tabla1xxx').DataTable({
                responsive: true,
                autoWidth: false,
                processing: true, // Indica que los datos se procesan en el servidor
                serverSide: true, // Habilita la paginación en el servidor
                ordered: true,
                language: table_language,
                destroy: true,
                ajax: {
                    url: "{{ route('salud.padronnominal.tablerocalidad.criterio.listar') }}",
                    type: "GET",
                    data: function(d) {
                        d.importacion = {{ $importacion }};
                        d.criterio = {{ $criterio }};
                        d.edades = $('#edades').val();
                        d.provincia = $('#provincia').val();
                        d.distrito = $('#distrito').val();
                        d.desa = 0;
                    }
                },
                columns: [{
                        data: 'item',
                        name: 'item'
                    }, {
                        data: 'padron',
                        name: 'padron'
                    },
                    {
                        data: 'tipo_doc',
                        name: 'tipo_doc'
                    },
                    {
                        data: 'num_doc',
                        name: 'num_doc'
                    },
                    {
                        data: 'nombrecompleto',
                        name: 'nombrecompleto'
                    },
                    {
                        data: 'nedad',
                        name: 'nedad'
                    },
                    {
                        data: 'nseguro',
                        name: 'nseguro'
                    },
                    {
                        data: 'nvisita',
                        name: 'nvisita'
                    },
                    {
                        data: 'nencontrado',
                        name: 'nencontrado'
                    },
                    {
                        data: 'ndistrito',
                        name: 'ndistrito'
                    },
                    {
                        data: 'ncui_atencion',
                        name: 'ncui_atencion'
                    },
                    {
                        data: 'nestablecimiento',
                        name: 'nestablecimiento'
                    }
                ]
                // columnDefs: [{
                //         className: 'text-center',
                //         targets: [0, 1, 2, 3, 5, 6, 7, 8]
                //     },
                //     {
                //         targets: 1,
                //         render: function(data, type, row) {
                //             return `<a href="#" onclick="abrirmodalpadron(${data})">${data}</a>`;
                //         }
                //     },
                //     {
                //         targets: 9,
                //         render: function(data, type, row) {

                //             if (criterio == 10 || criterio == 11 || criterio == 12 || criterio == 13) {
                //                 return data ?
                //                     `<a href="#" onclick="abrirmodaleess(${parseInt(data, 10)})">${data}</a>` :
                //                     '';
                //             } else {
                //                 return data;
                //             }

                //         }
                //     },
                //     {
                //         targets: 10,
                //         render: function(data, type, row) {
                //             if (criterio == 10 || criterio == 11 || criterio == 12 || criterio == 13) {
                //                 return data;
                //             } else {
                //                 return data ?
                //                     `<a href="#" onclick="abrirmodaleess(${parseInt(data, 10)})">${data}</a>` :
                //                     '';

                //             }
                //         }
                //     }
                // ]
            });

            tableprincipalx = $('#tabla1x').DataTable({
                responsive: true,
                autoWidth: false,
                processing: true, // Indica que los datos se procesan en el servidor
                serverSide: true, // Habilita la paginación en el servidor
                ordered: true,
                language: table_language,
                destroy: true,
                ajax: {
                    url: "{{ route('salud.padronnominal.tablerocalidad.criterio.listar') }}",
                    type: "GET",
                    data: function(d) {
                        d.importacion = {{ $importacion }};
                        d.criterio = {{ $criterio }};
                        d.edades = $('#edades').val();
                        d.provincia = $('#provincia').val();
                        d.distrito = $('#distrito').val();
                        d.desa = 0;
                    }
                },
                columnDefs: [{
                        className: 'text-center',
                        targets: [0, 1, 2, 3, 5, 6, 7, 8]
                    },
                    {
                        targets: 1,
                        render: function(data, type, row) {
                            return `<a href="#" onclick="abrirmodalpadron(${data})">${data}</a>`;
                        }
                    },
                    {
                        targets: 9,
                        render: function(data, type, row) {

                            if (criterio == 10 || criterio == 11 || criterio == 12 || criterio == 13) {
                                return data ?
                                    `<a href="#" onclick="abrirmodaleess(${parseInt(data, 10)})">${data}</a>` :
                                    '';
                            } else {
                                return data;
                            }

                        }
                    },
                    {
                        targets: 10,
                        render: function(data, type, row) {
                            if (criterio == 10 || criterio == 11 || criterio == 12 || criterio == 13) {
                                return data;
                            } else {
                                return data ?
                                    `<a href="#" onclick="abrirmodaleess(${parseInt(data, 10)})">${data}</a>` :
                                    '';

                            }
                        }
                    }
                ]
            });

            tableprincipalxx = $('#tabla1xx').DataTable({
                processing: true,
                serverSide: true,
                responsive: false,
                autoWidth: false,
                ordered: true,
                destroy: true, // Este permite reconfigurar la tabla si ya existe
                language: table_language,
                ajax: {

                    url: "{{ route('salud.padronnominal.tablerocalidad.criterio.listar2') }}",
                    type: "GET",
                    data: function(d) {
                        d.importacion = {{ $importacion }};
                        d.criterio = {{ $criterio }};
                        d.edades = $('#edades').val();
                        d.provincia = $('#provincia').val();
                        d.distrito = $('#distrito').val();
                        d.desa = 0;
                    }
                },
                columns: [{
                        data: 'padron',
                        name: 'padron'
                    },
                    {
                        data: 'num_doc',
                        name: 'num_doc'
                    },
                    {
                        data: 'tipo_doc',
                        name: 'tipo_doc'
                    }, {
                        data: 'padron',
                        name: 'padron'
                    },
                    {
                        data: 'num_doc',
                        name: 'num_doc'
                    },
                    {
                        data: 'tipo_doc',
                        name: 'tipo_doc'
                    }, {
                        data: 'padron',
                        name: 'padron'
                    },
                    {
                        data: 'num_doc',
                        name: 'num_doc'
                    },
                    {
                        data: 'tipo_doc',
                        name: 'tipo_doc'
                    }, {
                        data: 'padron',
                        name: 'padron'
                    },
                    {
                        data: 'num_doc',
                        name: 'num_doc'
                    },
                    {
                        data: 'tipo_doc',
                        name: 'tipo_doc'
                    },
                ]
            });
            panelGraficas('anal1');
            panelGraficas('anal2');
        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('salud.padronnominal.tablerocalidad.criterio.reporte') }}",
                data: {
                    div: div,
                    importacion: {{ $importacion }},
                    criterio: {{ $criterio }},
                    edades: $('#edades').val(),
                    provincia: $('#provincia').val(),
                    distrito: $('#distrito').val(),
                    desa: 0,
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    if (div == "anal1" || div == "anal2") {
                        $('#' + div).html(`
                                            <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
                                                <span class="spinner">
                                                    <i class="fa fa-spinner fa-spin"></i>
                                                </span>
                                            </div>
                                        `);
                    }
                },
                success: function(data) {
                    if (div == "anal1") {
                        var cflx = cfl('{{ $title }}');
                        anal1 = gColumn(div, data.info, '',
                            cflx + ', según edades',
                            'Edad')
                    } else if (div == "anal2") {
                        var cflx = cfl('{{ $title }}');
                        anal2 = gColumn(div, data.info, '',
                            cflx + ', según provincia',
                            'Provincia')
                    }
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
        }

        function cfl(string) {
            return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
        }

        function cargarCards_xxx() {
            tableprincipal = $('#tabla1').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: true,
                language: table_language,
                destroy: true,
                // ajax: "{{ route('salud.padronnominal.tablerocalidad.criterio.listar') }}",
                // type: "get",
                ajax: {
                    url: "{{ route('salud.padronnominal.tablerocalidad.criterio.listar') }}",
                    type: "GET",
                    data: function(d) {
                        d.importacion = {{ $importacion }};
                        d.criterio = {{ $criterio }};
                        d.establecimiento = $('#establecimiento').val();
                        d.microred = $('#microred').val();
                        d.red = $('#red').val();
                        d.desa = 0;
                    }
                },
                columnDefs: [{
                    className: 'text-center',
                    targets: [0, 1, 2, 3, 5, 6, 8]
                }, {
                    targets: 1,
                    render: function(data, type, row) {
                        // return '<a href="/ruta/detalle/' + row + '">' + data + '</a>';
                        return `<a href="#" onclick="abrirmodalpadron(${data})">${data}</a>`;
                    }
                }, {
                    targets: 8,
                    render: function(data, type, row) {
                        // return '<a href="/ruta/detalle/' + row + '">' + data + '</a>';
                        // console.log(parseInt(data, 10));
                        return data ?
                            `<a href="#" onclick="abrirmodaleess(${parseInt(data, 10)})">${data}</a>` :
                            '';
                    }
                }]
            });
        }

        function abrirmodalpadron(padron) {

            $.ajax({
                url: "{{ route('salud.padronnominal.tablerocalidad.criterio.find1', ['importacion' => $importacion, 'padron' => 'padron']) }}"
                    .replace('padron', padron),
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
                url: "{{ route('salud.padronnominal.tablerocalidad.criterio.find2', ['importacion' => $importacion, 'cui' => 'cui']) }}"
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

        function cargarProvincia() {
            $.ajax({
                url: "{{ route('salud.padronnominal.tablerocalidad.criterio.provincia', ['importacion' => $importacion, 'criterio' => $criterio, 'edad' => 'edad']) }}"
                    .replace('edad', $('#edades').val()),
                type: 'GET',
                success: function(data) {
                    $("#provincia option").remove();
                    var options = '<option value="0">TODOS</option>';
                    $.each(data, function(index, value) {
                        options += `<option value='${value.provincia_id}'>${value.provincia}</option>`;
                    });
                    $("#provincia").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarDistrito() {
            $.ajax({
                url: "{{ route('salud.padronnominal.tablerocalidad.criterio.distrito', ['importacion' => $importacion, 'criterio' => $criterio, 'edad' => 'edad', 'provincia' => 'provincia']) }}"
                    .replace('edad', $('#edades').val())
                    .replace('provincia', $('#provincia').val()),
                type: 'GET',
                success: function(data) {
                    $("#distrito option").remove();
                    var options = '<option value="0">TODOS</option>';
                    $.each(data, function(index, value) {
                        options += `<option value='${value.distrito_id}'>${value.distrito}</option>`;
                    });
                    $("#distrito").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function panelGraficasadasd(div) {
            $.ajax({
                url: "{{ route('salud.padronnominal.tablerocalidad.reporte') }}",
                data: {
                    'div': div,
                    "anio": $('#anio').val(),
                    "mes": $('#mes').val(),
                    "provincia": $('#provincia').val(),
                    "distrito": $('#distrito').val(),
                },
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    switch (div) {
                        case 'head':
                            $('#card1').text(data.card1).counterUp({
                                delay: 10,
                                time: 1000
                            });
                            $('#card2').text(data.card2).counterUp({
                                delay: 10,
                                time: 1000
                            });
                            $('#card3').text(data.card3).counterUp({
                                delay: 10,
                                time: 1000
                            });
                            $('#card4').text(data.card4).counterUp({
                                delay: 10,
                                time: 1000
                            });
                            break;
                        case 'anal1':
                            // console.log(data.avance);
                            GaugeSeries('anal1', data.avance, 'Porcentaje de Visitados');
                            break;
                        case 'anal2':
                            GaugeSeries('anal2', data.avance, 'Porcentaje con DNI');
                            break;
                        case 'anal3':
                            GaugeSeries('anal3', data.avance, 'Porcentaje con Seguro Salud');
                            break;
                        case 'anal4':
                            GaugeSeries('anal4', data.avance, 'Porcentaje con EESS de atención');
                            break;
                        case 'tabla1':
                            $('#ctabla1').html(data.excel);
                            break;
                        case 'tabla2':
                            $('#ctabla2').html(data.excel);
                            // $('#tabla2').DataTable({
                            //     responsive: true,
                            //     autoWidth: false,
                            //     ordered: true,
                            //     language: table_language,
                            // });
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

        function descargarExcel() {
            window.open(
                "{{ route('salud.padronnominal.tablerocalidad.criterio.exportar.excel', ['div' => 'div', 'importacion' => $importacion, 'criterio' => $criterio, 'edades' => 'edades', 'provincia' => 'provincia', 'distrito' => 'distrito']) }}"
                .replace('edades', $('#edades').val())
                .replace('provincia', $('#provincia').val())
                .replace('distrito', $('#distrito').val())
            );
        }

        function gColumn(div, data, titulo, subtitulo, tooltip) {
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
                    // text: 'Fuente: RENIEC - PADRÓN NOMINAL | Actualizado: JULIO 2024',
                    // href: null,
                    // position: {
                    //     align: 'center',
                    //     verticalAlign: 'bottom',
                    //     x: 0,
                    //     y: -5
                    // },
                    // style: {
                    //     color: '#666',
                    //     fontSize: '10px',
                    //     textAlign: 'center'
                    // }
                }
            });
        }

        function GaugeSeries(div, data, title) {
            Highcharts.chart(div, {
                chart: {
                    height: 200,
                    type: 'solidgauge',
                    margin: [10, 10, 10, 10],
                    spacing: [10, 10, 10, 10]
                },
                title: {
                    text: title,
                    verticalAlign: 'top',
                    style: {
                        fontSize: '12px',
                        fontWeight: 'normal'
                    }
                },
                pane: {
                    startAngle: 0,
                    endAngle: 360,
                    background: [{ // Fondo del anillo
                        outerRadius: '100%',
                        innerRadius: '80%',
                        backgroundColor: Highcharts.color('#E0E0E0').setOpacity(0.3).get(),
                        borderWidth: 0
                    }]
                },
                yAxis: {
                    min: 0,
                    max: 100,
                    lineWidth: 0,
                    tickPositions: []
                },
                plotOptions: {
                    solidgauge: {
                        dataLabels: {
                            format: '<div style="text-align:center"><span style="font-size:24px">{y}%</span><br/>' +
                                '<span style="font-size:12px;opacity:0.6">Avance</span></div>',
                            y: -25,
                            borderWidth: 0,
                            useHTML: true
                        },
                        // linecap: 'round',
                        // rounded: true
                    }
                },
                series: [{
                    name: 'Avance',
                    data: [{
                        y: data,
                        color: data >= 95 ? '#5eb9aa' : (data >= 75 ? '#f5bd22' :
                            '#ef5350')
                    }],
                    innerRadius: '80%',
                    radius: '100%',
                }],
                tooltip: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: false
                }
            });
        }

        function GaugeSeriesyyy(div, data) {
            Highcharts.chart(div, {
                chart: {
                    height: 165,
                    margin: [0, 0, 0, 0],
                    spacing: [0, 0, 0, 0],
                    type: 'solidgauge'
                },
                yAxis: {
                    min: 0,
                    max: 150,
                    dataClasses: [{
                            from: 0,
                            to: 50,
                            color: '#ef5350'
                        },
                        {
                            from: 51,
                            to: 99,
                            color: '#f5bd22'
                        },
                        {
                            from: 100,
                            to: 150,
                            color: '#5eb9aa'
                        }
                    ],
                    labels: {
                        enabled: false
                    },
                    tickLength: 0,
                    lineWidth: 0, // Remueve la línea del borde del gauge
                    gridLineWidth: 0 // Elimina las líneas de división
                },
                pane: {
                    background: [{
                        // Sin borde, remueve el efecto de seccionado
                        outerRadius: '100%',
                        innerRadius: '80%',
                        borderWidth: 0
                    }]
                },
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: false
                },
                title: {
                    text: ''
                },
                plotOptions: {
                    solidgauge: {
                        dataLabels: {
                            format: '<div style="text-align:center; margin-top: -20px">' +
                                '<div style="font-size:2.5em;">{y}%</div>' +
                                '<div style="font-size:12px; opacity:0.4;">Avance</div>' +
                                '</div>',
                            useHTML: true,
                            borderWidth: 0,
                        }
                    }
                },
                series: [{
                    name: 'Avance',
                    innerRadius: '80%',
                    data: [{
                        y: data,
                        color: data >= 100 ? '#5eb9aa' : (data >= 51 ? '#f5bd22' :
                            '#ef5350')
                    }],
                    radius: '100%',
                }],
                tooltip: {
                    valueSuffix: '%',
                    backgroundColor: '#FFFFFF',
                    borderColor: 'gray',
                    shadow: true,
                    style: {
                        fontSize: '12px'
                    }
                }
            });
        }

        function GaugeSeriesxx(div, data) {
            Highcharts.chart(div, {
                chart: {
                    height: 165,
                    margin: [0, 0, 0, 0],
                    spacing: [0, 0, 0, 0],
                    type: 'solidgauge'
                },
                yAxis: {
                    min: 0,
                    max: 150, // Ajustado al máximo de dataClasses
                    dataClasses: [{
                            from: 0,
                            to: 50,
                            color: '#ef5350'
                        },
                        {
                            from: 51,
                            to: 99,
                            color: '#f5bd22'
                        },
                        {
                            from: 100,
                            to: 150,
                            color: '#5eb9aa'
                        }
                    ],
                    labels: {
                        enabled: false
                    },
                    tickLength: 0,
                    lineColor: 'transparent',
                    gridLineWidth: 0
                },
                pane: {
                    background: {
                        innerRadius: '80%',
                        outerRadius: '100%'
                    }
                },
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: false
                },
                title: {
                    text: ''
                },
                plotOptions: {
                    solidgauge: {
                        dataLabels: {
                            format: '<div style="text-align:center; margin-top: -20px">' +
                                '<div style="font-size:2.5em;">{y}%</div>' +
                                '<div style="font-size:12px; opacity:0.4;">Avance</div>' +
                                '</div>',
                            useHTML: true,
                            borderWidth: 0,
                        }
                    }
                },
                series: [{
                    name: 'Avance',
                    innerRadius: '80%',
                    data: [{
                        y: data,
                        color: data >= 100 ? '#5eb9aa' : (data >= 51 ? '#f5bd22' :
                            '#ef5350') // Ajuste de color por valor
                    }],
                    radius: '100%',
                }],
                tooltip: {
                    valueSuffix: '%',
                    backgroundColor: '#FFFFFF',
                    borderColor: 'gray',
                    shadow: true,
                    style: {
                        fontSize: '12px'
                    }
                }
            });
        }
    </script>


    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>

    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
    <!-- optional -->
    <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script> --}}
@endsection
