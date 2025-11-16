@extends('layouts.main', ['activePage' => 'importacion', 'titlePage' => ''])

@section('css')
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
                        {{-- <button type="button" class="btn btn-danger btn-xs"
                            onclick="window.location.href=`{{ route('salud.padronnominal.tablerocalidad.consulta', ['anio' => 'anio', 'mes' => 'mes']) }}`.replace('anio',$('#anio').val()).replace('mes',$('#mes').val())">
                            <i class="fas fa-search"></i> Consultas</button> --}}
                        <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()">
                            <i class="fa fa-redo"></i> Actualizar</button>
                    </div>
                    <h3 class="card-title text-white font-14">Cobertura de Plazas en Educación</h3>
                </div>
                <div class="card-body pb-0">
                    <div class="form-group row align-items-center vh-5">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <h4 class="page-title font-12">Fuente: DREU - NEXUS {{ date('Y') }}</h4>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="anio">Año</label>
                                <select id="anio" name="anio" class="form-control font-11">
                                    @foreach ($anios as $item)
                                        <option value="{{ $item }}" {{ $item == $aniomax ? 'selected' : '' }}>
                                            {{ $item }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="gestion">Unidad de Gestión</label>
                                <select id="gestion" name="gestion" class="form-control font-11">
                                    <option value="0">TODOS</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="provincia">Provincia</label>
                                <select id="provincia" name="provincia" class="form-control font-11">
                                    <option value="0">TODOS</option>
                                    {{-- @foreach ($ugel as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->nombre }}</option>
                                    @endforeach --}}
                                </select>
                            </div>
                        </div>


                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="distrito">Distrito</label>
                                <select id="distrito" name="distrito" class="form-control font-11">
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
                    {{-- <div class="text-center">
                        <img src="{{ asset('/') }}public/img/icon/tableta32px.png" alt="" class=""
                            width="100%" height="100%">
                    </div> --}}
                    <div class="avatar-md mr-2">
                        <i class="fa fa-users avatar-title font-30 text-dark"></i>

                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="card1"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">Docentes</p>
                        </div>
                    </div>
                </div>
                <div class="mt-0 font-9">
                    <h6 class="">Avance <span class="float-right" id="card1i">0%</span></h6>
                    <div class="progress progress-sm m-0">
                        <div class="progress-bar bg-success-0" role="progressbar" aria-valuenow="90" aria-valuemin="0"
                            aria-valuemax="100" style="width: 100%" id="card1b">
                            <span class="sr-only">0% Complete</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card-box border border-plomo-0">
                <div class="media">
                    {{-- <div class="text-center">
                        <img src="{{ asset('/') }}public/img/icon/tableta32px.png" alt="" class=""
                            width="100%" height="100%">
                    </div> --}}
                    <div class="avatar-md mr-2">
                        <i class="fa fa-users avatar-title font-30 text-dark"></i>

                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="card2"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">Auxiliar de Educación</p>
                        </div>
                    </div>
                </div>
                <div class="mt-0 font-9">
                    <h6 class="">Avance <span class="float-right" id="card2i">0%</span></h6>
                    <div class="progress progress-sm m-0">
                        <div class="progress-bar bg-success-0" role="progressbar" aria-valuenow="90" aria-valuemin="0"
                            aria-valuemax="100" style="width: 100%" id="card2b">
                            <span class="sr-only">0% Complete</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card-box border border-plomo-0">
                <div class="media">
                    {{-- <div class="text-center">
                        <img src="{{ asset('/') }}public/img/icon/cargador32px.png" alt="" class=""
                            width="100%" height="100%">
                    </div> --}}
                    <div class="avatar-md mr-2">
                        <i class="fa fa-users avatar-title font-30 text-dark"></i>

                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="card3"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">Promotores Educativos</p>
                        </div>
                    </div>
                </div>
                <div class="mt-0 font-9">
                    <h6 class="">Avance <span class="float-right" id="card3i">0%</span></h6>
                    <div class="progress progress-sm m-0">
                        <div class="progress-bar bg-success-0" role="progressbar" aria-valuenow="90" aria-valuemin="0"
                            aria-valuemax="100" style="width: 100%" id="card3b">
                            <span class="sr-only">0% Complete</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card-box border border-plomo-0">
                <div class="media">
                    {{-- <div class="text-center">
                        <img src="{{ asset('/') }}public/img/icon/cargador32px.png" alt="" class=""
                            width="100%" height="100%">
                    </div> --}}
                    <div class="avatar-md mr-2">
                        <i class="fa fa-users avatar-title font-30 text-dark"></i>

                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="card4"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">Personal Administrativo</p>
                        </div>
                    </div>
                </div>
                <div class="mt-0 font-9">
                    <h6 class="">Avance <span class="float-right" id="card4i">0%</span></h6>
                    <div class="progress progress-sm m-0">
                        <div class="progress-bar bg-success-0" role="progressbar" aria-valuenow="90" aria-valuemin="0"
                            aria-valuemax="100" style="width: 100%" id="card4b">
                            <span class="sr-only">0% Complete</span>
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
                    <div id="anal1" style="height: 25rem"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                    <h3 class="text-black text-center font-weight-normal font-11 m-0"></h3>
                </div>
                <div class="card-body p-0">
                    <div id="anal2" style="height: 25rem"></div>
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
                    <div id="anal3" style="height: 25rem"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                    <h3 class="text-black text-center font-weight-normal font-11 m-0"></h3>
                </div>
                <div class="card-body p-0">
                    <div id="anal4" style="height: 25rem"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            {{-- <div class="card">
                <div class="card-header"> --}}
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-success-0 btn-xs" onclick="descargarExcel('tabla1')">
                            <i class="fa fa-file-excel"></i> Descargar</button>
                    </div>
                    <h3 class="card-title">NÚMERO DE PLAZAS DOCENTE Y AUXILIARES DE EDUCACIÓN POR UGEL, SEGÚN SITUACIÓN
                        LABORAL</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" id="ctabla1">

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            {{-- <div class="card">
                <div class="card-header"> --}}
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-success-0 btn-xs" onclick="descargarExcel('tabla2')">
                            <i class="fa fa-file-excel"></i> Descargar</button>
                    </div>
                    <h3 class="card-title">NÚMERO DE PLAZAS DOCENTE Y AUXILIARES DE EDUCACIÓN POR LEY DE CONTRATO, SEGÚN
                        SITUACIÓN LABORAL</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" id="ctabla2">

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            {{-- <div class="card">
                <div class="card-header"> --}}
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-success-0 btn-xs" onclick="descargarExcel('tabla3')">
                            <i class="fa fa-file-excel"></i> Descargar</button>
                    </div>
                    <h3 class="card-title">NÚMERO DE PLAZAS DOCENTE Y AUXILIARES DE EDUCACIÓN POR DISTRITO, SEGÚN SITUACIÓN
                        LABORAL</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" id="ctabla3">

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            {{-- <div class="card">
                <div class="card-header"> --}}
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-success-0 btn-xs" onclick="descargarExcel('tabla4')">
                            <i class="fa fa-file-excel"></i> Descargar</button>
                    </div>
                    <h3 class="card-title">NÚMERO DE PLAZAS POR INSTITUCIÓN EDUCATIVAS</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" id="ctabla4">
                            </div>
                        </div>
                    </div>
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
        var ubigeo_select = '';
        var anal3;
        var anal3valores = [];
        const spinners = {
            head: ['#card1', '#card2', '#card3', '#card4', '#card1i', '#card2i', '#card3i', '#card4i', '#card1b',
                '#card2b', '#card3b', '#card4b'
            ],
            anal: ['#anal1', '#anal2', '#anal3', '#anal4'],
            tabla: ['#ctabla1', '#ctabla2', '#ctabla3', '#ctabla4']
        };
        $(document).ready(function() {
            Object.keys(spinners).forEach(key => {
                SpinnerManager.show(key);
            });
            $('#anio').on('change', function() {
                cargarUgel();
            });
            $('#ugel').on('change', function() {
                cargarModalidad();
            });
            $('#modalidad').on('change', function() {
                cargarNivel();
            });
            $('#nivel').on('change', function() {
                cargarCards();
            });
            mapData = otros;
            mapData.features.forEach((element, key) => {
                console.log('["' + element.properties['hc-key'] + '", ' + (key + 1) + '],');
            });
            cargarUgel();

        });

        function cargarCards() {
            panelGraficas('head');
            panelGraficas('anal1');
            panelGraficas('anal2');
            panelGraficas('anal3');
            panelGraficas('anal4');
            panelGraficas('tabla1');
            panelGraficas('tabla2');
            panelGraficas('tabla3');
            panelGraficas('tabla4');
        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('educacion.nexus.reportes.reporte') }}",
                data: {
                    'div': div,
                    "anio": $('#anio').val(),
                    "ugel": $('#ugel').val(),
                    "modalidad": $('#modalidad').val(),
                    "nivel": $('#nivel').val(),
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    SpinnerManager.show(div);
                },
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
                            $('#card1i').text(data.pcard1 + '%');
                            $('#card2i').text(data.pcard2 + '%');
                            $('#card3i').text(data.pcard3 + '%');
                            $('#card4i').text(data.pcard4 + '%');
                            $('#card1b').css('width', data.pcard1 + '%')
                                .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                                .addClass(data.pcard1 > 96 ? 'bg-success-0' : (data.pcard1 > 76 ?
                                    'bg-warning-0' :
                                    'bg-orange-0'));
                            $('#card2b').css('width', data.pcard2 + '%')
                                .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                                .addClass(data.pcard2 > 96 ? 'bg-success-0' : (data.pcard2 > 76 ?
                                    'bg-warning-0' :
                                    'bg-orange-0'));
                            $('#card3b').css('width', data.pcard3 + '%')
                                .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                                .addClass(data.pcard3 > 96 ? 'bg-success-0' : (data.pcard3 > 76 ?
                                    'bg-warning-0' :
                                    'bg-orange-0'));
                            $('#card4b').css('width', data.pcard4 + '%')
                                .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                                .addClass(data.pcard4 > 96 ? 'bg-success-0' : (data.pcard4 > 76 ?
                                    'bg-warning-0' :
                                    'bg-orange-0'));
                            break;
                        case 'anal1':
                            anal3valores = data.valores;
                            anal3 = maps01(div, data.info, '',
                                'Porcentaje de Plazas Docentes por Provincia');
                            break;
                        case 'anal2':
                            crearGraficoLineasAcumuladas(div, data.info, '',
                                'Acumulado Mensual de Plazas Docentes, periodo 2025', '#cc0000');
                            break;
                        case 'anal3':
                            crearGraficoDistribucionPlazas(div, data,
                                'Distribución de Plazas Docentes de Educación, según Sexo');
                            break;
                        case 'anal4':
                            crearGraficoDistribucionPlazas(div, data,
                                'Distribución de Plazas Docentes de Educación, según Tipo de Trabajador');
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
                            //     paging: false,
                            //     searching: false,
                            //     info: false,
                            //     language: table_language,
                            // });
                            break;
                        case 'tabla3':
                            $('#ctabla3').html(data.excel);
                            // $('#tabla3').DataTable({
                            //     responsive: true,
                            //     autoWidth: false,
                            //     ordered: true,
                            //     language: table_language,
                            // });
                            break;
                        case 'tabla4':
                            $('#ctabla4').html(data.excel);
                            // Destruir si ya existe (opcional, para evitar errores al recargar)
                            if ($.fn.DataTable.isDataTable('#tabla4')) {
                                $('#tabla4').DataTable().destroy();
                            }
                            $('#tabla4').DataTable({
                                responsive: true,
                                autoWidth: false,
                                ordered: true,
                                language: table_language,
                                footerCallback: function() {
                                    var api = this.api();
                                    // Índices de las columnas a sumar (0-based)
                                    var columnas = [8, 9, 10, 11, 12];
                                    columnas.forEach(function(colIndex) {
                                        // Calcular la suma solo si el valor es numérico
                                        var total = api
                                            // .column(colIndex, { page: 'current' })
                                            .column(colIndex, {
                                                page: 'all',
                                                search: 'applied'
                                            })
                                            .data()
                                            .reduce(function(a, b) {
                                                // Convertir a número, ignorar NaN
                                                var num = parseFloat(b);
                                                return a + (isNaN(num) ? 0 : num);
                                            }, 0);

                                        // Formatear número (opcional)
                                        var formatted = total.toLocaleString('es-PE', {
                                            minimumFractionDigits: 0,
                                            maximumFractionDigits: 0
                                        });

                                        // Mostrar en el footer
                                        $(api.column(colIndex).footer()).html(formatted);
                                    });
                                }
                            });
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

        function cargarUgel() {
            $.ajax({
                url: "{{ route('educacion.nexus.filtro.ugel', ['anio' => ':anio']) }}"
                    .replace(':anio', $('#anio').val()),
                type: 'GET',
                success: function(data) {
                    $('#ugel').empty();
                    if (Object.keys(data).length > 1)
                        $('#ugel').append('<option value="0">TODOS</option>');
                    $.each(data, function(index, value) {
                        $('#ugel').append(`<option value='${index}'>${value}</option>`);
                    });
                    cargarModalidad();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarModalidad() {
            $.ajax({
                url: "{{ route('educacion.nexus.filtro.modalidad', ['anio' => ':anio', 'ugel' => ':ugel']) }}"
                    .replace(':anio', $('#anio').val())
                    .replace(':ugel', $('#ugel').val()),
                type: 'GET',
                success: function(data) {
                    $('#modalidad').empty();
                    if (Object.keys(data).length > 1)
                        $('#modalidad').append('<option value="0">TODOS</option>');
                    $.each(data, function(index, value) {
                        $('#modalidad').append(`<option value='${index}'>${value}</option>`);
                    });
                    cargarNivel();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarNivel() {
            $.ajax({
                url: "{{ route('educacion.nexus.filtro.nivel', ['anio' => ':anio', 'ugel' => ':ugel', 'modalidad' => ':modalidad']) }}"
                    .replace(':anio', $('#anio').val())
                    .replace(':ugel', $('#ugel').val())
                    .replace(':modalidad', $('#modalidad').val()),
                type: 'GET',
                success: function(data) {
                    $("#nivel").empty();
                    if (Object.keys(data).length > 1)
                        $('#nivel').append('<option value="0">TODOS</option>');
                    $.each(data, function(index, value) {
                        $('#nivel').append(`<option value='${index}'>${value}</option>`);
                    });
                    cargarCards();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function abrirmodalcentropoblado(ubigeo) {
            ubigeo_select = ubigeo;
            // console.log(ubigeo_select)]
            panelGraficas('tabla3_1');

            $('#modal-centropoblado').modal('show');

        }

        function abrirmodalconsultas() {
            $('#modal-consulta').modal('show');
        }

        function descargarExcel(div) {
            window.open(
                "{{ route('educacion.nexus.reportes.download.excel', ['div' => ':div', 'anio' => ':anio', 'ugel' => ':ugel', 'modalidad' => ':modalidad', 'nivel' => ':nivel']) }}"
                .replace(':div', div)
                .replace(':anio', $('#anio').val())
                .replace(':ugel', $('#ugel').val())
                .replace(':modalidad', $('#modalidad').val())
                .replace(':nivel', $('#nivel').val())
            );
        }

        function gAnidadaColumn(div, categoria, series, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    zoomType: 'xy',
                    type: 'column'
                },
                title: {
                    text: titulo,
                    //'Browser market shares in January, 2018'
                },
                subtitle: {
                    text: subtitulo,
                },
                xAxis: [{
                    categories: categoria,
                    crosshair: true
                }],
                yAxis: [{
                        // Primary yAxis
                        //max: 2000000000,
                        labels: {
                            enabled: false,
                        },
                        title: {
                            enabled: false,
                        },
                        /* labels: {
                            format: '{value}°C',
                            style: {
                                color: Highcharts.getOptions().colors[2]
                            }
                        },
                        title: {
                            text: 'Temperature',
                            style: {
                                color: Highcharts.getOptions().colors[2]
                            }
                        }, */
                        //opposite: true,
                    }, {
                        // Secondary yAxis
                        gridLineWidth: 0,
                        labels: {
                            enabled: false,
                        },
                        title: {
                            enabled: false,
                        },
                        /* title: {
                            text: 'Rainfall',
                            style: {
                                color: Highcharts.getOptions().colors[0]
                            }
                        },
                        labels: {
                            format: '{value} mm',
                            style: {
                                color: Highcharts.getOptions().colors[0]
                            }
                        }, */
                        min: -200,
                        max: 150,
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
                        //showInLegend: false,
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            //format: '{point.y:.1f}%',
                            /* formatter: function() {
                                    if (this.y > 1000000) {
                                        return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                                    } else if (this.y > 1000) {
                                        return Highcharts.numberFormat(this.y / 1000, 0) + "K";
                                    } else if (this.y < 101) {
                                        return this.y + "%";
                                    } else {
                                        return this.y;
                                    }
                                }, */
                            style: {
                                fontWeight: 'normal',
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
                    enabled: false
                },
                credits: false,
            });
        }

        function gPie(div, datos, titulo, subtitulo, tituloserie) {
            const colors = ["#5eb9aa", "#e65310", "#f5bd22"];
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
                    style: {
                        fontSize: '11px',
                    }
                },
                tooltip: {
                    //pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>',
                    pointFormat: '<b>{point.percentage:.1f}% ({point.y:,0f})</b>',
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
                        colors,
                        dataLabels: {
                            enabled: true,
                            // distance: -20,
                            //format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            //format: '{point.percentage:.1f}% ({point.y})',
                            format: '{point.y:,0f} ( {point.percentage:.1f}% )',
                            // format: '{point.percentage:.1f}%',
                            connectorColor: 'silver'
                        }
                    }
                },
                series: [{
                    innerSize: '50%',
                    showInLegend: true,
                    // name: 'Share',
                    data: datos,
                }],
                legend: {
                    itemStyle: {
                        //color: "#333333",
                        cursor: "pointer",
                        fontSize: "10px",
                        fontWeight: "normal",
                        textOverflow: "ellipsis"
                    },
                },
                exporting: {
                    enabled: true
                },
                credits: false,
            });
        }

        function maps01(div, data, titulo, subtitulo) {
            return Highcharts.mapChart(div, {
                chart: {
                    map: mapData
                },
                // states: {
                //     hover: {
                //         color: '#ff5733' // Cambia este color al que desees
                //     }
                // },
                title: {
                    text: titulo, //'Reportes de Mapa'
                },

                subtitle: {
                    text: subtitulo, //'Un descripción de reportes'
                    style: {
                        // fontSize: '11px'
                    }
                },

                mapNavigation: {
                    enabled: true,
                    buttonOptions: {
                        verticalAlign: 'top'
                    }
                },

                colorAxis: {
                    // mixColor: "#e6ebf5",
                    // manColor: "#003399",
                    minColor: '#e0f6f3',
                    maxColor: '#2a7f72',
                    showInLegend: false
                },

                series: [{
                    data: data,
                    name: 'NEXUS',
                    states: {
                        hover: {
                            color: '#ef5350' // '#BADA55'
                        }
                    },
                    borderColor: '#cac9c9',

                    // dataLabels: {
                    //     enabled: true,
                    //     useHTML: true, // Permite el uso de etiquetas HTML
                    //     format: '<div style="text-align:center;">{point.name}<br><span style="font-size:12px;">{point.value:,2f}%</span></div>',
                    //     // nullFormatter: '0%',
                    //     // formatter: function() {
                    //     //     const value = this.point.value || 0;
                    //     //     return `<div style="text-align:center;">${this.point.name}<br><span style="font-size:12px;">${Highcharts.numberFormat(value, 2)}%</span></div>`;
                    //     // },
                    //     style: {
                    //         fontSize: '10px',
                    //         fontWeight: 'bold',
                    //         color: '#FFFFFF',
                    //         textShadow: '0px 0px 3px #000000' // Aplica sombra negra para simular el borde
                    //     }
                    // },

                    dataLabels: {
                        enabled: true,
                        useHTML: true,
                        formatter: function() {
                            const value = this.point.value !== undefined ? this.point.value : 0;
                            return `<div style="text-align:center;">
                                        ${this.point.name}<br>
                                        <span style="font-size:12px;">${Highcharts.numberFormat(value, 1)}%</span>
                                    </div>`;
                        },
                        style: {
                            fontSize: '10px',
                            fontWeight: 'bold',
                            color: '#FFFFFF',
                            textShadow: '0px 0px 3px #000000'
                        }
                    },

                }],
                tooltip: {
                    useHTML: true,
                    formatter: function() {
                        // Obtener los datos desde anal3valores
                        const provinciaData = anal3valores[this.point.properties['hc-key']];
                        if (!provinciaData) {
                            return `<strong>${this.point.name}</strong><br>Datos no disponibles`;
                        }

                        return `<div style="text-align:left;">
                                    <strong>${this.point.name}</strong><br>
                                    Docentes: ${Highcharts.numberFormat(provinciaData.num,0)}<br>
                                    Participación: ${Highcharts.numberFormat(provinciaData.ind, 1)}%
                                </div>`;
                    },
                    backgroundColor: '#fff', // Fondo blanco translúcido
                    borderColor: '#cccccc', // Borde suave
                    borderRadius: 10, // Bordes redondeados
                    shadow: true, // Sombra para darle un efecto "suave"
                    style: {
                        fontSize: '12px',
                        fontWeight: 'normal',
                        color: '#333333',
                        padding: '10px'
                    }
                },
                legend: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },
            });
        }

        function anal3valores(provincia) {
            return anal3valores[provincia] || null;
        }

        function crearGraficoLineasAcumuladas(div, data, titulo = 'Gráfico', subtitulo = '', colorLinea = '#cc0000') {
            Highcharts.chart(div, {
                chart: {
                    type: 'line',
                    // backgroundColor: '#f9f9f9',
                    borderRadius: 10,
                    spacingTop: 20,
                    spacingBottom: 20,
                    spacingLeft: 20,
                    spacingRight: 20
                },
                title: {
                    text: titulo,
                    style: {
                        fontSize: '16px',
                        fontWeight: 'bold',
                        color: '#333'
                    }
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        // fontSize: '12px',
                        // color: '#666'
                    }
                },
                xAxis: {
                    categories: data
                        .categoria, // ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'],
                    tickmarkPlacement: 'on',
                    lineWidth: 0,
                    gridLineWidth: 1,
                    gridLineColor: '#ddd',
                    labels: {
                        style: {
                            fontSize: '12px',
                            color: '#555'
                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: ''
                    },
                    gridLineColor: '#ddd',
                    labels: {
                        style: {
                            fontSize: '12px',
                            color: '#555'
                        }
                    }
                },
                plotOptions: {
                    line: {
                        lineWidth: 2,
                        marker: {
                            enabled: true,
                            radius: 4,
                            fillColor: '#fff',
                            lineColor: colorLinea,
                            lineWidth: 2
                        },
                        states: {
                            hover: {
                                lineWidth: 3
                            }
                        }
                    }
                },
                series: [{
                    name: 'Plazas Docentes',
                    data: data.data,
                    color: colorLinea,
                    dataLabels: {
                        enabled: true,
                        format: '{y}',
                        style: {
                            fontSize: '12px',
                            fontWeight: 'bold',
                            color: '#333',
                            textOutline: '2px contrast'
                        },
                        verticalAlign: 'bottom',
                        y: -10
                    }
                }],
                tooltip: {
                    shared: true,
                    useHTML: true,
                    formatter: function() {
                        return `<b>${this.points[0].series.name}</b><br/>${this.x}: <b>${this.y} plazas</b>`;
                    },
                    backgroundColor: '#fff',
                    borderColor: '#ccc',
                    borderRadius: 8,
                    shadow: true,
                    style: {
                        fontSize: '12px',
                        padding: '8px'
                    }
                },
                legend: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            chart: {
                                spacingTop: 10,
                                spacingBottom: 10,
                                spacingLeft: 10,
                                spacingRight: 10
                            },
                            xAxis: {
                                labels: {
                                    style: {
                                        fontSize: '10px'
                                    }
                                }
                            },
                            yAxis: {
                                labels: {
                                    style: {
                                        fontSize: '10px'
                                    }
                                }
                            },
                            plotOptions: {
                                line: {
                                    marker: {
                                        radius: 3
                                    }
                                }
                            },
                            series: [{
                                dataLabels: {
                                    style: {
                                        fontSize: '10px'
                                    },
                                    y: -8
                                }
                            }]
                        }
                    }]
                }
            });
        }

        function crearGraficoDistribucionPlazas(div, data, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'pie',
                    // backgroundColor: '#f5f5f5',
                    style: {
                        fontFamily: 'Arial, sans-serif'
                    }
                },
                title: {
                    text: '',
                    style: {
                        fontSize: '14px',
                        fontWeight: '500',
                        color: '#666'
                    }
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        // fontSize: '12px',
                        // color: '#666'
                    }
                },
                tooltip: {
                    pointFormat: '<b>{point.percentage:.1f}%</b><br/>Total: {point.y}'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        innerSize: '60%', // Esto crea el efecto de dona
                        dataLabels: {
                            enabled: true,
                            format: '{point.percentage:.0f}%',
                            distance: -30,
                            style: {
                                fontWeight: 'bold',
                                color: 'white',
                                fontSize: '16px',
                                textOutline: 'none'
                            }
                        },
                        showInLegend: true
                    }
                },
                legend: {
                    align: 'center',
                    verticalAlign: 'bottom',
                    layout: 'horizontal',
                    itemStyle: {
                        fontSize: '12px',
                        fontWeight: 'normal',
                        color: '#666'
                    },
                    symbolRadius: 0,
                    symbolHeight: 12,
                    symbolWidth: 12
                },
                credits: {
                    enabled: false
                },
                series: [{
                    name: 'Plazas',
                    colorByPoint: true,
                    data: data
                }]
            });
        }
    </script>

    {{-- jrmt-mapero --}}
    <script src="https://code.highcharts.com/maps/highmaps.js"></script>
    <script src="https://code.highcharts.com/maps/modules/exporting.js"></script>

    <script src="{{ asset('/') }}public/us-ct-ally.js"></script>
    <script src="{{ asset('/') }}public/us-ct-allz.js"></script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
    <!-- optional -->
    <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>

    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
@endsection
