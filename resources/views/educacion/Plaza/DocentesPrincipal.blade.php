@extends('layouts.main', ['activePage' => 'importacion', 'titlePage' => ''])

@section('css')
    {{-- <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" /> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />

    <style>
        .tablex thead th {
            padding: 2px;
            text-align: center;
        }

        .tablex thead td {
            padding: 2px;
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
    </style>
@endsection

@section('content')
    <div class="content">
        <input type="hidden" id="hoja" value="1">

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body mb-0 pb-0">
                        <form action="" name="form_parametros" id="form_parametros" method="POST">
                            @csrf
                            {{-- <input type="hidden" id="importacion_id" name="importacion_id"> --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="titulo_Indicadores  mb-0">RESUMEN DE PLAZAS</p>
                                </div>
                                <div class="col-md-6 text-right">
                                    <div class="form-group row">
                                        <div class="col-md-4"></div>
                                        {{-- <label class="col-md-2 col-form-label">Año</label> --}}
                                        <div class="col-md-3">
                                            <select id="anio" name="anio" class="form-control btn-xs"
                                                onchange="cargardatos();">
                                                @foreach ($anios as $item)
                                                    <option value="{{ $item->anio }}"> {{ $item->anio }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- <label class="col-md-2 col-form-label">Ugel</label> --}}
                                        <div class="col-md-5">
                                            <select id="ugel" name="ugel" class="form-control btn-xs"
                                                onchange="cargardatos()">
                                                <option value="0">UGEL</option>
                                                @foreach ($ugels as $ugel)
                                                    <option value="{{ $ugel['id'] }}">{{ $ugel['nombre'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        {{-- <div class="progress progress-sm m-0">
                            <div class="progress-bar bg-info" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                aria-valuemax="100" style="width: 100%"></div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
        <!-- End row -->


        <div class="row">
            @php
                $color = ['info', 'purple', 'success', 'primary', 'pink', 'dark', 'warning', 'secondary'];
                $img = [' mdi mdi-home-group', ' mdi mdi-water-outline', ' mdi mdi-water', ' mdi mdi-spray-bottle'];
            @endphp
            {{-- //@foreach ($data as $pos => $dato) --}}
            <div class="col-md-6 col-xl-3">
                <div class="card-box">
                    <div class="media">
                        <div class="avatar-md bg-primary rounded-circle mr-2">
                            {{-- <i class="{{ $img[$pos] }} avatar-title font-26 text-white"></i> --}}
                            <i class="ion-md-person avatar-title font-26 text-white"></i>
                        </div>
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold opt1"><span data-plugin="counterup">0</span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate"><span>DOCENTES</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card-box">
                    <div class="media">
                        <div class="avatar-md bg-primary rounded-circle mr-2">
                            {{-- <i class="{{ $img[$pos] }} avatar-title font-26 text-white"></i> --}}
                            <i class="ion-md-person avatar-title font-26 text-white"></i>
                        </div>
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold  opt2"><span data-plugin="counterup">0</span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">ADMINISTRATIVOS</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card-box">
                    <div class="media">
                        <div class="avatar-md bg-primary rounded-circle mr-2">
                            {{-- <i class="{{ $img[$pos] }} avatar-title font-26 text-white"></i> --}}
                            <i class="ion-md-person avatar-title font-26 text-white"></i>
                        </div>
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold opt3"><span data-plugin="counterup">0</span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">CAS</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card-box">
                    <div class="media">
                        <div class="avatar-md bg-primary rounded-circle mr-2">
                            {{-- <i class="{{ $img[$pos] }} avatar-title font-26 text-white"></i> --}}
                            <i class="ion-md-person avatar-title font-26 text-white"></i>
                        </div>
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold opt4"><span data-plugin="counterup">0</span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">PEC</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- //@endforeach --}}

        </div>
        <!-- end row -->

        <div class="row">
            <div class="col-xl-6">
                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent p-0">
                        <h3 class="card-title text-primary "></h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="anal7"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent p-0">
                        <h3 class="card-title text-primary "></h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="anal8"></div>
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}

        <div class="row">
            <div class="col-xl-6">
                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent p-0">
                        <h3 class="card-title text-primary "></h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="anal9"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent p-0">
                        <h3 class="card-title text-primary "></h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="anal10"></div>
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}

        <div class="row">
            <div class="col-xl-6">
                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent p-0">
                        <h3 class="card-title text-primary "></h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="anal1"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent p-0">
                        <h3 class="card-title text-primary "></h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="anal2"></div>
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}

        <div class="row">
            <div class="col-xl-6">
                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent p-0">
                        <h3 class="card-title text-primary "></h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="anal3"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent p-0">
                        <h3 class="card-title text-primary "></h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="anal4"></div>
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}

        <div class="row">
            <div class="col-xl-6">
                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent p-0">
                        <h3 class="card-title text-primary "></h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="anal5"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent p-0">
                        <h3 class="card-title text-primary "></h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="anal6"></div>
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}


        <div class="row">
            <div class="col-xl-12">
                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent pb-0 m-0">
                        <h3 class="card-title ">TOTAL DE PLAZAS DE CONTRATADOS Y NOMBRADOS
                            {{-- SEGUN UGEL Y --}} NIVEL EDUCATIVO</h3>
                    </div>
                    <div class="card-body pb-0 pt-0">
                        <div class="table-responsive" id="vista1">
                        </div>
                        {{-- <p class="text-muted font-13 m-0 p-0 text-right">
                            Fuente: Sistema de Administración y Control de Plazas – NEXUS, ultima actualizacion del <span
                                id="fechaActualizacion"></span>
                        </p> --}}
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}

        <div class="row">
            <div class="col-xl-12">
                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent pb-0 m-0">
                        <h3 class="card-title ">TOTAL DE PLAZAS SEGÚN TIPO DE TRABAJADOR Y POR UGEL</h3>
                    </div>
                    <div class="card-body pb-0 pt-0">
                        <div class="table-responsive" id="vista2">
                        </div>
                        {{-- <p class="text-muted font-13 m-0 p-0 text-right">
                            Fuente: Sistema de Administración y Control de Plazas – NEXUS, ultima actualizacion del <span
                                id="fechaActualizacion"></span>
                        </p> --}}
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}

        <div class="row">
            <div class="col-xl-12">
                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent pb-0 m-0">
                        <h3 class="card-title ">TOTAL DE PLAZAS SEGÚN UGEL Y SITUACION LABORAL</h3>
                    </div>
                    <div class="card-body pb-0 pt-0">
                        <div class="table-responsive" id="vista3">
                        </div>
                        {{-- <p class="text-muted font-13 m-0 p-0 text-right">
                            Fuente: Sistema de Administración y Control de Plazas – NEXUS, ultima actualizacion del <span
                                id="fechaActualizacion"></span>
                        </p> --}}
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}

        <div class="row">
            <div class="col-xl-12">
                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent pb-0 m-0">
                        <h3 class="card-title ">TOTAL DE PLAZAS SEGÚN UGEL Y SITUACION LABORAL</h3>
                    </div>
                    <div class="card-body pb-0 pt-0">
                        <div class="table-responsive" id="vista4">
                        </div>
                        <p class="text-muted font-13 m-0 p-0 text-right">
                            Fuente: Sistema de Administración y Control de Plazas – NEXUS, ultima actualizacion del <span
                                id="fechaActualizacion"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}

        <div class="row">
            <div class="col-xl-12">
                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent pb-0 m-0">
                        <h3 class="card-title ">Numero de docentes bilingues segun lengua originaria</h3>
                    </div>
                    <div class="card-body pb-0 pt-0">
                        <div class="table-responsive" id="vista5">
                        </div>
                        <p class="text-muted font-13 m-0 p-0 text-right">
                            Fuente: Sistema de Administración y Control de Plazas – NEXUS, ultima actualizacion del <span
                                id="fechaActualizacion"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}

        <div class="row">
            <div class="col-xl-12">
                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent pb-0 m-0">
                        <h3 class="card-title ">Profesionales de otras carreras que ejercen la Docencia de Nivel secundario
                            en instituciones públicas</h3>
                    </div>
                    <div class="card-body pb-0 pt-0">
                        <div class="table-responsive" id="vista6">
                        </div>
                        <p class="text-muted font-13 m-0 p-0 text-right">
                            Fuente: Sistema de Administración y Control de Plazas – NEXUS, ultima actualizacion del <span
                                id="fechaActualizacion"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}

        {{-- <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="progress progress-sm m-0">
                            <div class="progress-bar bg-info" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                aria-valuemax="100" style="width: 100%"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <p class="titulo_Indicadores  mb-0"></p>
                            </div>
                            <div class="col-md-10 text-right">
                                <p class="texto_dfuente  mb-0"> Fuente: Sistema de Administración y Control de Plazas –
                                    NEXUS, ultima actualizacion del <span id="fechaActualizacion"></span>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div> <!-- End col -->
        </div> <!-- End row --> --}}

    </div>
@endsection

@section('js')
    <script type="text/javascript">
        var nombre_mes = ["ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SETIEMBRE", "OCTUBRE",
            "NOVIEMBRE", "DICIEMBRE"
        ];
        $(document).ready(function() { //paleta_colores
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

            cargardatos();

        });

        function cargardatos() {
            $.ajax({
                url: "{{ route('nexus.contratacion.head') }}",
                type: 'POST',
                data: $('#form_parametros').serialize(),
                dataType: 'JSON',
                success: function(data) {
                    $('.opt1').html(data.info.opt1.toLocaleString('en-IN'));
                    $('.opt2').html(data.info.opt2.toLocaleString('en-IN'));
                    $('.opt3').html(data.info.opt3.toLocaleString('en-IN'));
                    $('.opt4').html(data.info.opt4.toLocaleString('en-IN'));
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('nexus.contratacion.gra1') }}",
                type: 'POST',
                data: $('#form_parametros').serialize(),
                dataType: 'JSON',
                beforeSend: function() {
                    $('#anal1').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    gSimpleColumn('anal1', data.info.v1, '',
                        'PLAZAS SEGUN UNIDAD DE GESTION EDUCATIVA<br>Fuente:NEXUS', '');

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('nexus.contratacion.gra2') }}",
                type: 'POST',
                data: $('#form_parametros').serialize(),
                dataType: 'JSON',
                beforeSend: function() {
                    $('#anal2').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    gSimpleColumn('anal2', data.info.v2, '',
                        'PLAZAS SEGUN TIPO DE NIVEL EDUCATIVO<br>Fuente:NEXUS', '');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('nexus.contratacion.gra3') }}",
                type: 'POST',
                data: $('#form_parametros').serialize(),
                dataType: 'JSON',
                beforeSend: function() {
                    $('#anal3').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    gSimpleColumn('anal3', data.info.v3, '',
                        'PLAZAS SEGUN TIPO DE NIVEL EDUCATIVO<br>Fuente:NEXUS', '');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('nexus.contratacion.gra4') }}",
                type: 'POST',
                data: $('#form_parametros').serialize(),
                dataType: 'JSON',
                beforeSend: function() {
                    $('#anal4').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    gSimpleColumn('anal4', data.info.v4, '',
                        'PLAZAS SEGUN TIPO DE NIVEL EDUCATIVO<br>Fuente:NEXUS', '');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('nexus.contratacion.gra5') }}",
                type: 'POST',
                data: $('#form_parametros').serialize(),
                dataType: 'JSON',
                beforeSend: function() {
                    $('#anal5').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    gSimpleColumn('anal5', data.info.v5, '',
                        'PLAZAS SEGUN TIPO DE NIVEL EDUCATIVO<br>Fuente:NEXUS', '');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('nexus.contratacion.gra6') }}",
                type: 'POST',
                data: $('#form_parametros').serialize(),
                dataType: 'JSON',
                beforeSend: function() {
                    $('#anal6').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    gSimpleColumn('anal6', data.info.v6, '',
                        'PLAZAS SEGUN TIPO DE NIVEL EDUCATIVO<br>Fuente:NEXUS', '');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('nexus.contratacion.gra7') }}",
                type: 'POST',
                data: $('#form_parametros').serialize(),
                dataType: 'JSON',
                beforeSend: function() {
                    $('#anal7').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    gSimpleColumn('anal7', data.info.v7, '',
                        'PLAZAS DE EDUCACIÓN POR AÑOS<br>Fuente:NEXUS', '');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('nexus.contratacion.gra8') }}",
                type: 'POST',
                data: $('#form_parametros').serialize(),
                dataType: 'JSON',
                beforeSend: function() {
                    $('#anal8').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    /* gSimpleColumn('anal8', data.info.v8, '',
                        'PLAZAS DE EDUCACIÓN POR MESES<br>Fuente:NEXUS', ''); */
                    glineal('anal8', data.info.v8.categoria, data.info.v8.series, '',
                        'PLAZAS DE EDUCACIÓN POR MESES<br>Fuente:NEXUS');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('nexus.contratacion.gra9') }}",
                type: 'POST',
                data: $('#form_parametros').serialize(),
                dataType: 'JSON',
                beforeSend: function() {
                    $('#anal9').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    gSimpleColumn('anal9', data.info.v9, '',
                        'PLAZAS DE PERSONAL DE EDUCACIÓN POR AÑOS<br>Fuente:NEXUS', '');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('nexus.contratacion.gra10') }}",
                type: 'POST',
                data: $('#form_parametros').serialize(),
                dataType: 'JSON',
                beforeSend: function() {
                    $('#anal10').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    /* gSimpleColumn('anal10', data.info.v10, '',
                        'PLAZAS DE PERSONAL DE EDUCACIÓN POR MESES<br>Fuente:NEXUS', ''); */
                    glineal('anal10', data.info.v10.categoria, data.info.v10.series, '',
                        'PLAZAS DE EDUCACIÓN POR MESES<br>Fuente:NEXUS');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('nexus.contratacion.dt1') }}",
                type: 'POST',
                data: $('#form_parametros').serialize(),
                dataType: 'JSON',
                success: function(data) {
                    $('#fechaActualizacion').html(data.info.fecha);
                    $('#vista1').html(data.info.DT.table);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('nexus.contratacion.dt2') }}",
                type: 'POST',
                data: $('#form_parametros').serialize(),
                dataType: 'JSON',
                success: function(data) {
                    $('#fechaActualizacion').html(data.info.fecha);
                    $('#vista2').html(data.info.DT.table);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('nexus.contratacion.dt3') }}",
                type: 'POST',
                data: $('#form_parametros').serialize(),
                dataType: 'JSON',
                success: function(data) {
                    $('#fechaActualizacion').html(data.info.fecha);
                    $('#vista3').html(data.info.DT.table);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('nexus.contratacion.dt4') }}",
                type: 'POST',
                data: $('#form_parametros').serialize(),
                dataType: 'JSON',
                success: function(data) {
                    $('#fechaActualizacion').html(data.info.fecha);
                    $('#vista4').html(data.info.DT.table);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('nexus.contratacion.dt5') }}",
                type: 'POST',
                data: $('#form_parametros').serialize(),
                dataType: 'JSON',
                success: function(data) {
                    $('#fechaActualizacion').html(data.info.fecha);
                    $('#vista5').html(data.info.DT);
                    $('#tabla5').DataTable({
                        // responsive: true,
                        // autoWidth: false,
                        // ordered: true,
                        // searching: false,
                        // bPaginate: false,
                        // info: false,
                        language: table_language,
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('nexus.contratacion.dt6') }}",
                type: 'POST',
                data: $('#form_parametros').serialize(),
                dataType: 'JSON',
                success: function(data) {
                    $('#fechaActualizacion').html(data.info.fecha);
                    $('#vista6').html(data.info.DT);
                    // $('#tabla5').DataTable({
                    //     responsive: true,
                    //     language: table_language,
                    // });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }
        /* function cargarMes() {
            $.ajax({
                url: "{{ url('/') }}/Plaza/Mes/" + $('#anio').val()   ,
                type: 'get',
                dataType: 'JSON',
                data: {
                    'anio': $('#anio').val()
                },
                success: function(data) {
                    $("#mes option").remove();
                    var options = ''; // '<option value="">SELECCIONAR</option>';
                    $.each(data.meses, function(index, value) {
                        options += "<option value='" + value.mes + "'>" + nombre_mes[value.mes - 1] +
                            "</option>"
                    });
                    $("#mes").append(options);
                    cargarUltimo();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        } */

        /* function cargarUltimo() {
            $.ajax({
                url: "{{ url('/') }}/Plaza/UltimoImportado/" + $('#anio').val() + "/0",
                type: 'get',
                dataType: 'JSON',
                success: function(data) {
                    var f = new Date(data.importado.fechaActualizacion);
                    $('#fechaActualizacion').html(getFecha(f));
                    $('#importacion_id').val(data.importado.id);
                    cargarVista();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        } */

        function getFecha(f) {
            var d = f.getDate() < 10 ? "0" + f.getDate() : f.getDate();
            var m = f.getMonth() + 1;
            m = m < 10 ? "0" + m : m;
            var y = f.getYear() + 1900;
            return d + "/" + m + "/" + y
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
                    text: subtitulo,
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
                    //headerFormat: '<span style="font-size: 10px">{point.key}</span><br/>',
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> Hay: <b>{point.y}</b><br/>',
                    //pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.conteo}</b><br/>',
                    shared: true
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            /* format: '{point.y:.1f}%', */
                            style: {
                                fontWeight: 'normal',
                            }
                        },
                    }
                },
                credits: false,
            });
        }

        function gSimpleColumnx(div, datax, titulo, subtitulo, tituloserie) { //tiene otras opciones
            Highcharts.chart(div, {
                chart: {
                    type: 'column',
                },
                title: {
                    enabled: false,
                    text: titulo,
                },
                subtitle: {
                    text: subtitulo,
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
                    //headerFormat: '<span style="font-size: 10px">{point.key}</span><br/>',
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> Hay: <b>{point.conteo}</b><br/>',
                    //pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.conteo}</b><br/>',
                    shared: true
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.1f}%',
                        },
                    }
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
                    text: titulo,
                },
                subtitle: {
                    text: subtitulo,
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
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            connectorColor: 'silver'
                        }
                    }
                },
                series: [{
                    name: 'Share',
                    data: datos,
                }],
                credits: false,
            });
        }

        function gBasicColumn(div, categorias, datos, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'column'
                },
                title: {
                    text: titulo /*  'Monthly Average Rainfall' */
                },
                subtitle: {
                    text: subtitulo /*  'Source: WorldClimate.com' */
                },
                xAxis: {
                    categories: categorias,
                    /* crosshair: true */
                },
                yAxis: {

                    min: 0,
                    title: {
                        text: 'Rainfall (mm)',
                        enabled: false
                    }
                },
                /*tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },*/
                /* plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                }, */
                tooltip: {
                    //headerFormat: '<span style="font-size: 10px">{point.key}</span><br/>',
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> Hay: <b>{point.y}</b><br/>',
                    //pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.conteo}</b><br/>',
                    shared: true
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            /* format: '{point.y:.1f}%', */
                        },
                    }
                },
                series: datos,
                credits: false,
            });
        }

        function glineal(div, categoria, series, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'spline'
                },
                title: {
                    text: titulo,
                },
                subtitle: {
                    text: subtitulo,
                },
                xAxis: {
                    categories: categoria
                },
                yAxis: {
                    title: {
                        enabled: false,
                        text: 'Number of Employees'
                    },
                    min: 0,
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },

                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            /* formatter: function() {
                                if (this.y > 1000000) {
                                    return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                                } else if (this.y > 1000) {
                                    return Highcharts.numberFormat(this.y / 1000, 0) + "K";
                                } else {
                                    return this.y;
                                }
                            }, */
                            style: {
                                fontWeight: 'normal',
                            }
                        },
                    }
                },
                series: series,
                legend: {
                    align: 'center', //right//left//center
                    verticalAlign: 'bottom', //top//middle//bottom
                    layout: 'horizontal', //horizontal//vertical//proximate
                    itemStyle: {
                        "color": "#333333",
                        "cursor": "pointer",
                        "fontSize": "10px",
                        "fontWeight": "normal", //bold
                        "textOverflow": "ellipsis"
                    },
                },
                credits: false,

            });
        }
    </script>

    {{-- <script src="https://code.highcharts.com/highcharts.js"></script> --}}
    {{-- <script src="https://code.highcharts.com/modules/exportingÇ.js"></script>    <!-- optional --> --}}
    {{-- <script src="https://code.highcharts.com/modules/offline-exporting.js"></script> --}}
    {{-- <script src="https://code.highcharts.com/modules/export-data.js"></script> --}}
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script>

    {{--     <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script> --}}
@endsection
