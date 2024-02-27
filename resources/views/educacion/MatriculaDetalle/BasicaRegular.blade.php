@extends('layouts.main', ['titlePage' => ''])
@section('css')
    <!-- Magnific -->
    {{-- <link rel="stylesheet" href="{{ asset('/') }}public/assets/libs/magnific-popup/magnific-popup.css" /> --}}
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <style>
        .tablex thead th {
            padding: 5px;
            text-align: center;
        }

        .tablex thead td {
            padding: 5px;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
        }

        .tablex tbody td,
        .tablex tbody th,
        .tablex tfoot td,
        .tablex tfoot th {
            padding: 5px;
        }

        .ax {
            color: #317eeb;
            text-decoration: none;
            background-color: transparent;
        }

        .ax:hover {
            color: #1259bd;
            text-decoration: none;
        }
    </style>
@endsection
@section('content')
    <div class="content">

        <div class="row">
            <div class="col-lg-12">
                <div class="card card-fill bg-success-0  mb-0">
                    <div class="card-header bg-transparent">
                        <div class="card-widgets">
                            <button type="button" class="btn btn-orange-0 btn-xs" onclick="location.reload()"><i
                                    class="fa fa-history"></i></button>
                        </div>
                        <h3 class="card-title text-white text-center">EDUCACION BÁSICA REGULAR (EBR) SEGÚN SIAGIE- MINEDU
                            ACTUALIZADO AL {{ $fecha }} {{-- <a href="javascript:location.reload()" class="btn btn-warning" title="ACTUALIZAR PAGINA"><i
                                class="fa fa-redo"></i></a></h3> --}}
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
                            <input type="hidden" id="distrito" name="distrito" value="0">
                            <input type="hidden" id="provincia" name="provincia" value="0">
                            <div class="form-group row mb-0">
                                <div class="col-md-4"></div>
                                <div class="col-md-2">
                                    <select id="ano" name="ano" class="form-control" onchange="cargartabla0()">
                                        @foreach ($anios as $item)
                                            <option value="{{ $item->id }}">{{ $item->anio }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select id="ugel" name="ugel" class="form-control" onchange="cargartabla0()">
                                        <option value="0">Ugel</option>
                                        @foreach ($ugels as $ugel)
                                            <option value="{{ $ugel['id'] }}">{{ $ugel['nombre'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select id="gestion" name="gestion" class="form-control" onchange="cargartabla0()">
                                        <option value="0">Gestión</option>
                                        @foreach ($gestions as $prov)
                                            <option value="{{ $prov['id'] }}">{{ $prov['nombre'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select id="area" name="area" class="form-control" onchange="cargartabla0()">
                                        <option value="0">Área</option>
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

        <div class="row">
            <div class="col-lg-12">
                <div class="portfolioFilter">
                    <a href="#" class="waves-effect waves-light" id="principal" onclick="principalok()">PRINCIPAL</a>
                    <a href="#" class="waves-effect waves-light" id="inicial" onclick="inicialok()">INICIAL</a>
                    <a href="#" class="waves-effect waves-light" id="primaria" onclick="primariaok()">PRIMARIA</a>
                    <a href="#" class="waves-effect waves-light" id="secundaria"
                        onclick="secundariaok()">SECUNDARIA</a>
                    <a href="#" class="waves-effect waves-light" id="pronoi" onclick="pronoiok()">PRONOEI</a>
                    {{-- <a href="#" data-filter=".prin-cipal" class="waves-effect waves-light current">PRIN-CIPAL</a>
                    <a href="#" data-filter=".inicial" class="waves-effect waves-light">INICIAL</a>
                    <a href="#" data-filter=".primaria" class="waves-effect waves-light">PRIMARIA</a>
                    <a href="#" data-filter=".secundaria" class="waves-effect waves-light">SECUNDARIA</a>
                    <a href="#" data-filter=".pronoi" class="waves-effect waves-light">PRONOEI</a> --}}
                </div>
            </div>
        </div>

        <div class="port">{{-- mb-3 mt-4 --}}
            <div class="portfolioContainer row">

                <div class="col-xl-6 principal">
                    <div class="card card-border">
                        <div class="card-header border-primary bg-transparent p-0">
                            <h3 class="card-title"></h3>
                        </div>
                        <div class="card-body pb-0 pt-0">
                            <div id="gra1"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 principal">
                    <div class="card card-border">
                        <div class="card-header border-primary bg-transparent p-0">
                            <h3 class="card-title"></h3>
                        </div>
                        <div class="card-body pb-0 pt-0">
                            <div id="gra2"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 principal">
                    <div class="card card-border">
                        <div class="card-header border-primary bg-transparent p-0">
                            <h3 class="card-title"></h3>
                        </div>
                        <div class="card-body pb-0 pt-0">
                            <div id="gra3"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 principal">
                    <div class="card card-border">
                        <div class="card-header border-primary bg-transparent p-0">
                            <h3 class="card-title"></h3>
                        </div>
                        <div class="card-body pb-0 pt-0">
                            <div id="gra4"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 principal">
                    <div class="card card-border">
                        <div class="card-header border-primary bg-transparent pb-0 mb-0">
                            <h3 class="card-title">Matricula educativa por genero según ugel</h3>
                        </div>
                        <div class="card-body pb-0 pt-0">
                            <div class="table-responsive" id="vista1">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 principal">
                    <div class="card card-border">
                        <div class="card-header border-primary bg-transparent pb-0 mb-0">
                            <h3 class="card-title">Matricula educativa por ciclo según ugel</h3>
                        </div>
                        <div class="card-body pb-0 pt-0">
                            <div class="table-responsive" id="vista2">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- INICIAL --}}
                <div class="col-xl-12 inicial">
                    <div class="card card-border">
                        <div class="card-header border-primary bg-transparent pb-0 mb-0">
                            <h3 class="card-title">TOTAL MATRICULA NIVEL INICIAL POR CICLO, EDAD Y SEXO SEGÚN UGEL</h3>
                        </div>
                        <div class="card-body pb-0 pt-0">
                            <div class="table-responsive" id="vista3">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 inicial" id="guiaEBR3_1">
                    <div class="card card-border">
                        <div class="card-header border-primary bg-transparent pb-0 mb-0">
                            <h3 class="card-title">TOTAL MATRICULA NIVEL INICIAL POR CICLO, EDAD Y SEXO SEGÚN DISTRITOS
                                <a href="javascript:void(0)" class="btn btn-primary btn-xs" onclick="cargarvista3_1(0);"
                                    title="MOSTRAR TODO"><i class="fa fa-redo"></i></a>
                            </h3>
                        </div>
                        <div class="card-body pb-0 pt-0">
                            <div class="table-responsive" id="vista3_1">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-xl-12 inicial">
                    <div class="card card-border">
                        <div class="card-header border-primary bg-transparent pb-0 mb-0">
                            <h3 class="card-title">TOTAL MATRICULA NIVEL INICIAL POR CICLO, EDAD Y SEXO SEGÚN CENTRO POBLADO EN EL DISTRITO DE <span id="vista3_3_title"></span></h3>
                        </div>
                        <div class="card-body pb-0 pt-0">
                            <div class="table-responsive" id="vista3_3">
                            </div>
                        </div>
                    </div>
                </div> --}}

                <div class="col-xl-12 inicial" id="guiaEBR3_2">
                    <div class="card card-border">
                        <div class="card-header border-primary bg-transparent pb-0 mb-0">
                            <h3 class="card-title">TOTAL MATRICULA NIVEL INICIAL POR CICLO, EDAD Y SEXO SEGÚN SERVICIOS
                                EDUCATIVOS EN EL DISTRITO <span id="vista3_2_title"></span> <a href="javascript:void(0)"
                                    class="btn btn-primary btn-xs" onclick="cargarvista3_2(0);" title="MOSTRAR TODO"><i
                                        class="fa fa-redo"></i></a></h3>
                        </div>
                        <div class="card-body pb-0 pt-0">
                            <div class="table-responsive" id="vista3_2">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FIN INICIAL --}}

                {{-- PRIMARIA --}}
                <div class="col-xl-12 primaria">
                    <div class="card card-border">
                        <div class="card-header border-primary bg-transparent pb-0 mb-0">
                            <h3 class="card-title">TOTAL MATRICULA NIVEL PRIMARIA POR CICLO, EDAD Y SEXO SEGÚN UGEL AL</h3>
                        </div>
                        <div class="card-body pb-0 pt-0">
                            <div class="table-responsive" id="vista4">
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xl-12 primaria" id="guiaEBR4_1">
                    <div class="card card-border">
                        <div class="card-header border-primary bg-transparent pb-0 mb-0">
                            <h3 class="card-title">TOTAL MATRICULA NIVEL PRIMARIA POR CICLO, EDAD Y SEXO SEGÚN DISTRITOS
                                <a href="javascript:void(0)" class="btn btn-primary btn-xs" onclick="cargarvista4_1(0);"
                                    title="MOSTRAR TODO"><i class="fa fa-redo"></i></a>
                            </h3>
                        </div>
                        <div class="card-body pb-0 pt-0">
                            <div class="table-responsive" id="vista4_1">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-xl-12 primaria">
                    <div class="card card-border">
                        <div class="card-header border-primary bg-transparent pb-0 mb-0">
                            <h3 class="card-title">TOTAL MATRICULA NIVEL PRIMARIA POR CICLO, EDAD Y SEXO SEGÚN CENTRO POBLADO EN EL DISTRITO DE <span id="vista3_3_title"></span></h3>
                        </div>
                        <div class="card-body pb-0 pt-0">
                            <div class="table-responsive" id="vista4_3">
                            </div>
                        </div>
                    </div>
                </div> --}}

                <div class="col-xl-12 primaria" id="guiaEBR4_2">
                    <div class="card card-border">
                        <div class="card-header border-primary bg-transparent pb-0 mb-0">
                            <h3 class="card-title">TOTAL MATRICULA NIVEL PRIMARIA POR CICLO, EDAD Y SEXO SEGÚN SERVICIOS
                                EDUCATIVOS EN EL DISTRITO <span id="vista4_2_title"></span><a href="javascript:void(0)"
                                    class="btn btn-primary btn-xs" onclick="cargarvista4_2(0);" title="MOSTRAR TODO"><i
                                        class="fa fa-redo"></i></a></h3>
                        </div>
                        <div class="card-body pb-0 pt-0">
                            <div class="table-responsive" id="vista4_2">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FIN PRIMARIA --}}

                {{-- SECUNDARIA --}}
                <div class="col-xl-12 secundaria">
                    <div class="card card-border">
                        <div class="card-header border-primary bg-transparent pb-0 mb-0">
                            <h3 class="card-title">TOTAL MATRICULA NIVEL SECUNDARIA POR CICLO, EDAD Y SEXO SEGÚN UGEL AL
                            </h3>
                        </div>
                        <div class="card-body pb-0 pt-0">
                            <div class="table-responsive" id="vista5">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 secundaria" id="guiaEBR5_1">
                    <div class="card card-border">
                        <div class="card-header border-primary bg-transparent pb-0 mb-0">
                            <h3 class="card-title">TOTAL MATRICULA NIVEL SECUNDARIA POR CICLO, EDAD Y SEXO SEGÚN DISTRITOS
                                <a href="javascript:void(0)" class="btn btn-primary btn-xs" onclick="cargarvista5_1(0);"
                                    title="MOSTRAR TODO"><i class="fa fa-redo"></i></a>
                            </h3>
                        </div>
                        <div class="card-body pb-0 pt-0">
                            <div class="table-responsive" id="vista5_1">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-xl-12 secundaria">
                    <div class="card card-border">
                        <div class="card-header border-primary bg-transparent pb-0 mb-0">
                            <h3 class="card-title">TOTAL MATRICULA NIVEL SECUNDARIA POR CICLO, EDAD Y SEXO SEGÚN CENTRO POBLADO EN EL DISTRITO DE <span id="vista3_3_title"></span></h3>
                        </div>
                        <div class="card-body pb-0 pt-0">
                            <div class="table-responsive" id="vista5_3">
                            </div>
                        </div>
                    </div>
                </div> --}}

                <div class="col-xl-12 secundaria" id="guiaEBR5_2">
                    <div class="card card-border">
                        <div class="card-header border-primary bg-transparent pb-0 mb-0">
                            <h3 class="card-title">TOTAL MATRICULA NIVEL SECUNDARIA POR CICLO, EDAD Y SEXO SEGÚN SERVICIOS
                                EDUCATIVOS EN EL DISTRITO <span id="vista5_2_title"></span><a href="javascript:void(0)"
                                    class="btn btn-primary btn-xs" onclick="cargarvista5_2(0);" title="MOSTRAR TODO"><i
                                        class="fa fa-redo"></i></a></h3>
                        </div>
                        <div class="card-body pb-0 pt-0">
                            <div class="table-responsive" id="vista5_2">
                            </div>
                        </div>
                    </div>
                </div>
                {{-- FIN SECUNDARIA --}}

                {{-- PRONOI --}}
                <div class="col-xl-6 pronoi">
                    <div class="card card-border">
                        <div class="card-header border-primary bg-transparent p-0">
                            <h3 class="card-title"></h3>
                        </div>
                        <div class="card-body pb-0 pt-0">
                            <div id="gra5" style="height: 400px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 pronoi">
                    <div class="card card-border">
                        <div class="card-header border-primary bg-transparent p-0">
                            <h3 class="card-title"></h3>
                        </div>
                        <div class="card-body pb-0 pt-0">
                            <div id="gra6" style="height: 400px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 pronoi">
                    <div class="card card-border">
                        <div class="card-header border-primary bg-transparent pb-0 mb-0">
                            <h3 class="card-title">total Matricula educativa por genero según ugel</h3>
                        </div>
                        <div class="card-body pb-0 pt-0">
                            <div class="table-responsive" id="vista6" style="height: 215px;">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 pronoi">
                    <div class="card card-border">
                        <div class="card-header border-primary bg-transparent p-0">
                            <h3 class="card-title"></h3>
                        </div>
                        <div class="card-body pb-0 pt-0">
                            <div id="gra7" style="height: 400px;"></div>
                        </div>
                    </div>
                </div>
                {{-- FIN PRONOI --}}

            </div>
        </div>


    </div>
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
            principalok();
            cargarvista3_1(0);
            cargarvista3_2(0);
            cargarvista4_1(0);
            cargarvista4_2(0);
            cargarvista5_1(0);
            cargarvista5_2(0);
        });

        function principalok() {
            $('.principal').show();
            $('.inicial').hide();
            $('.primaria').hide();
            $('.secundaria').hide();
            $('.pronoi').hide();
            $('#principal').addClass('current');
            $('#inicial').removeClass('current');
            $('#primaria').removeClass('current');
            $('#secundaria').removeClass('current');
            $('#pronoi').removeClass('current');
        }

        function inicialok() {
            $('.principal').hide();
            $('.inicial').show();
            $('.primaria').hide();
            $('.secundaria').hide();
            $('.pronoi').hide();
            $('#principal').removeClass('current');
            $('#inicial').addClass('current');
            $('#primaria').removeClass('current');
            $('#secundaria').removeClass('current');
            $('#pronoi').removeClass('current');
        }

        function primariaok() {
            $('.principal').hide();
            $('.inicial').hide();
            $('.primaria').show();
            $('.secundaria').hide();
            $('.pronoi').hide();
            $('#principal').removeClass('current');
            $('#inicial').removeClass('current');
            $('#primaria').addClass('current');
            $('#secundaria').removeClass('current');
            $('#pronoi').removeClass('current');
        }

        function secundariaok() {
            $('.principal').hide();
            $('.inicial').hide();
            $('.primaria').hide();
            $('.secundaria').show();
            $('.pronoi').hide();
            $('#principal').removeClass('current');
            $('#inicial').removeClass('current');
            $('#primaria').removeClass('current');
            $('#secundaria').addClass('current');
            $('#pronoi').removeClass('current');
        }

        function pronoiok() {
            $('.principal').hide();
            $('.inicial').hide();
            $('.primaria').hide();
            $('.secundaria').hide();
            $('.pronoi').show();
            $('#principal').removeClass('current');
            $('#inicial').removeClass('current');
            $('#primaria').removeClass('current');
            $('#secundaria').removeClass('current');
            $('#pronoi').addClass('current');
        }

        function cargartabla0() {

            $.ajax({
                url: "{{ route('matriculadetalle.ebr.grafica1') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    gLineaMultiple('gra1', data, '', 'ESTUDIANTES MATRICULADOS SEGÚN NIVEL', '');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('matriculadetalle.ebr.grafica2') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    gLineaBasica('gra2', data, '', 'MATRICULA ACUMULADA MENSUAL', '');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('matriculadetalle.ebr.grafica3') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    gPie('gra3', data, '', 'MATRICULADOS SEGÚN NIVEL EDUCATIVO', '');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('matriculadetalle.ebr.grafica4') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    gPie('gra4', data, '', 'ESTUDIANTES SEGÚN GENERO', '');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });


            $.ajax({
                url: "{{ route('matriculadetalle.ebr.tabla1') }}",
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
                url: "{{ route('matriculadetalle.ebr.tabla2') }}",
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
                url: "{{ route('matriculadetalle.ebr.tabla3') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    $('#vista3').html(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            /* $.ajax({
                url: "{{ route('matriculadetalle.ebr.tabla3_1') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    $('#vista3_1').html(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            }); */

            $.ajax({
                url: "{{ route('matriculadetalle.ebr.tabla4') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    $('#vista4').html(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            /* $.ajax({
                url: "{{ route('matriculadetalle.ebr.tabla4_1') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    $('#vista4_1').html(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            }); */

            $.ajax({
                url: "{{ route('matriculadetalle.ebr.tabla5') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    $('#vista5').html(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            /* $.ajax({
                url: "{{ route('matriculadetalle.ebr.tabla5_1') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    $('#vista5_1').html(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            }); */

            $.ajax({
                url: "{{ route('matriculadetalle.ebr.grafica5') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    gLineaMultiple('gra5', data, '', 'ESTUDIANTES MATRICULADOS SEGÚN NIVEL', '');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('matriculadetalle.ebr.grafica6') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    gLineaBasica('gra6', data, '', 'MATRICULA ACUMULADA MENSUAL', '');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('matriculadetalle.ebr.grafica7') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    gPie('gra7', data, '', 'ESTUDIANTES SEGÚN GENERO', '');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('matriculadetalle.ebr.tabla6') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    $('#vista6').html(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarvista3_1(provincia) {
            $('#provincia').val(provincia);
            $.ajax({
                url: "{{ route('matriculadetalle.ebr.tabla3_1') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    $('#vista3_1').html(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarvista3_2(distrito) {
            //event.preventDefault();
            $('#distrito').val(distrito);
            $.ajax({
                url: "{{ route('matriculadetalle.ebr.tabla3_2') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    $('#vista3_2_title').html(data.distrito);
                    $('#vista3_2').html(data.tabla);
                    $('#tablaEBR3_2').DataTable({
                        "language": table_language,
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        /* function cargarvista3_3(distrito) {
            event.preventDefault();
            $('#distrito').val(distrito);
            $.ajax({
                url: "{{ route('matriculadetalle.ebr.tabla3_3') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    $('#vista3_3_title').html(data.distrito);
                    $('#vista3_3').html(data.tabla);
                    $('#tablaEBR3_3').DataTable({"language": table_language,});
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        } */
        function cargarvista4_1(provincia) {
            $('#provincia').val(provincia);
            $.ajax({
                url: "{{ route('matriculadetalle.ebr.tabla4_1') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    $('#vista4_1').html(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarvista4_2(distrito) {
            $('#distrito').val(distrito);
            $.ajax({
                url: "{{ route('matriculadetalle.ebr.tabla4_2') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    $('#vista4_2_title').html(data.distrito);
                    $('#vista4_2').html(data.tabla);
                    $('#tablaEBR4_2').DataTable({
                        "language": table_language,
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarvista5_1(provincia) {
            $('#provincia').val(provincia);
            $.ajax({
                url: "{{ route('matriculadetalle.ebr.tabla5_1') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    $('#vista5_1').html(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarvista5_2(distrito) {
            $('#distrito').val(distrito);
            $.ajax({
                url: "{{ route('matriculadetalle.ebr.tabla5_2') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    $('#vista5_2_title').html(data.distrito);
                    $('#vista5_2').html(data.tabla);
                    $('#tablaEBR5_2').DataTable({
                        "language": table_language,
                    });
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
                    min: 0
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

        function gLineaMultiple(div, data, titulo, subtitulo, titulovetical) {
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
                series: data['dat'],
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

        function gPie(div, datos, titulo, subtitulo, tituloserie) {
            Highcharts.chart(div, {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: titulo, //'Browser market shares in January, 2018'
                },
                subtitle: {
                    text: subtitulo,
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
                            format: '{point.yx} ( {point.percentage:.1f}% )',
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
                credits: false,
            });
        }
    </script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script> --}}
    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script> --}}

    <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>

    <!-- Vendor js -->
    {{-- <script src="assets/js/vendor.min.js"></script> --}}

    <!-- isotope filter plugin -->
    {{-- <script src="{{ asset('/') }}public/assets/libs/isotope/isotope.pkgd.min.js"></script> --}}

    <!-- Magnific -->
    {{-- <script src="{{ asset('/') }}public/assets/libs/magnific-popup/jquery.magnific-popup.min.js"></script> --}}

    <!-- Gallery Init-->
    {{-- <script src="{{ asset('/') }}public/assets/js/pages/gallery.init.js"></script> --}}
@endsection
