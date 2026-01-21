@extends('layouts.main', ['activePage' => 'importacion', 'titlePage' => ''])

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
    <style>
        #btn-mapa-eib-reset {
            position: absolute;
            bottom: 8px;
            right: 8px;
            z-index: 10;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .highcharts-tooltip {
            z-index: 9999 !important;
        }

        .highcharts-tooltip span {
            background: #ffffff !important;
            opacity: 1 !important;
        }
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
                    <h3 class="card-title text-white font-14">Educación Interculural Bilingüe</h3>
                </div>
                <div class="card-body pb-0">
                    <div class="form-group row align-items-center vh-5">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <h4 class="page-title font-12">Fuente: Padrón EIB, SIAGIE, NEXUS</h4>
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
                            <p class="mb-0 mt-1 text-truncate">Servicios Educativos</p>
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
                            <p class="mb-0 mt-1 text-truncate">Lenguas Originarias</p>
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
                            <p class="mb-0 mt-1 text-truncate">Total Matriculados</p>
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
                            <p class="mb-0 mt-1 text-truncate">Total Docentes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="card card-border border border-plomo-0">
                <div
                    class="card-header border-success-0 bg-transparent pb-0 pt-0 d-flex justify-content-between align-items-center">
                    <h3 class="text-black text-center font-weight-normal font-11 m-0"></h3>
                </div>
                <div class="card-body p-0 position-relative">
                    <button type="button" id="btn-mapa-eib-reset" class="btn btn-xs btn-outline-secondary d-none"
                        title="Ver mapa completo" data-toggle="tooltip">
                        <i class="fa fa-expand"></i>
                    </button>
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
        <div class="col-lg-6 col-md-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                    <h3 class="text-black text-center font-weight-normal font-11 m-0"></h3>
                </div>
                <div class="card-body p-0">
                    <div id="anal5" style="height: 25rem"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                    <h3 class="text-black text-center font-weight-normal font-11 m-0"></h3>
                </div>
                <div class="card-body p-0">
                    <div id="anal6" style="height: 25rem"></div>
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
                    {{-- <div class="card-widgets">
                        <button type="button" class="btn btn-success-0 btn-xs" onclick="descargarExcel('tabla1')">
                            <i class="fa fa-file-excel"></i> Descargar</button>
                    </div> --}}
                    <h3 class="card-title">
                        NÚMERO DE SERVICIOS EDUCATIVOS, ESTUDIANTES, DOCENTES, AUXILIARES DE EDUCACIÓN Y PROMOTORES
                        EDUCATIVOS POR ÁMBITO GEOGRÁFICO, SEGÚN FORMA DE ATENCIÓN
                    </h3>
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
                    {{-- <div class="card-widgets">
                        <button type="button" class="btn btn-success-0 btn-xs" onclick="descargarExcel('tabla2')">
                            <i class="fa fa-file-excel"></i> Descargar</button>
                    </div> --}}
                    <h3 class="card-title">
                        NÚMERO DE SERVICIOS EDUCATIVOS, ESTUDIANTES, DOCENTES, AUXILIARES DE EDUCACIÓN Y PROMOTORES
                        EDUCATIVOS POR ÁMBITO GEOGRÁFICO, SEGÚN NIVEL EDUCATIVO
                    </h3>
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
                    {{-- <div class="card-widgets">
                        <button type="button" class="btn btn-success-0 btn-xs" onclick="descargarExcel('tabla3')">
                            <i class="fa fa-file-excel"></i> Descargar</button>
                    </div> --}}
                    <h3 class="card-title">
                        NÚMERO DE SERVICIOS EDUCATIVOS, ESTUDIANTES, DOCENTES, AUXILIARES DE EDUCACIÓN Y PROMOTORES
                        EDUCATIVOS POR ÁMBITO GEOGRÁFICO, SEGÚN LENGUA ORIGINARIA
                    </h3>
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
                    <h3 class="card-title">
                        NÚMERO DE INSTITUCIONES EDUCATIVAS BILINGÜES POR NIVEL EDUCATIVO, FORMA DE ATENCIÓN, LENGUA
                        ORIGINARIA, ESTUDIANTES Y DOCENTES
                    </h3>
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
        var anal1Chart;
        var selectedProvinciaCodigo = null;
        var anal1Distritos = {};
        var anal1DistritosValores = {};
        var provinciaNombreToCodigo = {};
        var ubigeoToDistritoHcKey = {};
        var mapLevel = 'provincia';
        const spinners = {
            head: ['#card1', '#card2', '#card3', '#card4', '#card1i', '#card2i', '#card3i', '#card4i', '#card1b',
                '#card2b', '#card3b', '#card4b'
            ],
            anal1: ['#anal1'],
            anal2: ['#anal2'],
            anal3: ['#anal3'],
            anal4: ['#anal4'],
            anal5: ['#anal5'],
            anal6: ['#anal6'],
            tabla1: ['#ctabla1'],
            tabla2: ['#ctabla2'],
            tabla3: ['#ctabla3'],
            tabla4: ['#ctabla4']
        };
        $(document).ready(function() {
            if (window.Highcharts && Highcharts.setOptions) {
                Highcharts.setOptions({
                    lang: {
                        contextButtonTitle: 'Menú de exportación',
                        viewFullscreen: 'Ver en pantalla completa',
                        printChart: 'Imprimir gráfico',
                        downloadPNG: 'Descargar imagen PNG',
                        downloadJPEG: 'Descargar imagen JPEG',
                        downloadSVG: 'Descargar imagen SVG vectorial',
                        downloadPDF: 'Descargar documento PDF',
                        downloadCSV: 'Descargar CSV',
                        downloadXLS: 'Descargar XLS',
                        viewData: 'Ver tabla de datos'
                    },
                    exporting: {
                        buttons: {
                            contextButton: {
                                menuItems: [
                                    'viewFullscreen',
                                    'printChart',
                                    'downloadPNG',
                                    'downloadPDF'
                                ]
                            }
                        }
                    }
                });
            }
            Object.keys(spinners).forEach(key => {
                SpinnerManager.show(key);
            });
            $('#anio').on('change', function() {
                cargarGestion();
            });
            $('#gestion').on('change', function() {
                cargarProvincia();
            });
            $('#provincia').on('change', function() {
                var valor = $(this).val();
                if (valor === '0') {
                    $('#btn-mapa-eib-reset').addClass('d-none');
                    selectedProvinciaCodigo = null;
                    mapLevel = 'provincia';
                } else {
                    $('#btn-mapa-eib-reset').removeClass('d-none');
                }
                cargarDistrito();
            });
            $('#distrito').on('change', function() {
                cargarCards();
            });

            mapData = otros;
            provinciaNombreToCodigo = {};
            if (otros && otros.features) {
                otros.features.forEach(function(element) {
                    if (element.properties && element.properties.name && element.properties['hc-key']) {
                        var nombre = String(element.properties.name).toUpperCase().trim();
                        provinciaNombreToCodigo[nombre] = element.properties['hc-key'];
                    }
                });
            }
            ubigeoToDistritoHcKey = {};
            if (otros2 && otros2.features) {
                otros2.features.forEach(function(element) {
                    if (element.ubigeo && element.properties && element.properties['hc-key']) {
                        ubigeoToDistritoHcKey[element.ubigeo] = element.properties['hc-key'];
                    }
                });
            }
            cargarGestion();

            $('#btn-mapa-eib-reset').on('click', function() {
                selectedProvinciaCodigo = null;
                $('#provincia').val('0');
                $('#distrito').empty().append('<option value="0">TODOS</option>');
                $('#btn-mapa-eib-reset').addClass('d-none');
                mapLevel = 'provincia';
                mapData = otros;
                cargarCards();
            });

        });

        function cargarCards() {
            panelGraficas('head');
            panelGraficas('anal1');
            panelGraficas('anal2');
            panelGraficas('anal3');
            panelGraficas('anal4');
            panelGraficas('anal5');
            panelGraficas('anal6');
            panelGraficas('tabla1');
            panelGraficas('tabla2');
            panelGraficas('tabla3');
            panelGraficas('tabla4');
        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('educacion.padron.eib.reportes.reporte') }}",
                data: {
                    'div': div,
                    "anio": $('#anio').val(),
                    "gestion": $('#gestion').val(),
                    "provincia": $('#provincia').val(),
                    "distrito": $('#distrito').val(),
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
                            break;
                        case 'anal1':
                            anal3valores = data.valores;
                            anal1Distritos = data.infoDistritos || {};

                            // Ensure mappings are populated (lazy init)
                            if (typeof otros !== 'undefined' && Object.keys(provinciaNombreToCodigo).length ===
                                0) {
                                otros.features.forEach(function(element) {
                                    if (element.properties && element.properties.name && element
                                        .properties['hc-key']) {
                                        var nombre = String(element.properties.name).toUpperCase()
                                        .trim();
                                        provinciaNombreToCodigo[nombre] = element.properties['hc-key'];
                                    }
                                });
                            }
                            if (typeof otros2 !== 'undefined' && Object.keys(ubigeoToDistritoHcKey).length ===
                                0) {
                                otros2.features.forEach(function(element) {
                                    if (element.ubigeo && element.properties && element.properties[
                                            'hc-key']) {
                                        ubigeoToDistritoHcKey[element.ubigeo] = element.properties[
                                            'hc-key'];
                                    }
                                });
                            }

                            var provinciaValor = $('#provincia').val();
                            var usarDistritos = false;
                            var codigoProvincia = null;
                            if (provinciaValor && provinciaValor !== '0') {
                                var nombreSel = $('#provincia option:selected').text().toUpperCase().trim();
                                codigoProvincia = provinciaNombreToCodigo[nombreSel] || null;

                                // FIX: Hardcode explícito para Padre Abad
                                if (nombreSel === 'PADRE ABAD') {
                                    codigoProvincia = 'pe-uc-pa';
                                }

                                if (codigoProvincia && anal1Distritos[codigoProvincia]) {
                                    usarDistritos = true;
                                }
                            }
                            if (usarDistritos) {
                                selectedProvinciaCodigo = codigoProvincia;
                                mapLevel = 'distrito';
                                var nombreProvinciaSel = $('#provincia option:selected').text();
                                mapData = construirMapaDistritosProvincia(nombreProvinciaSel);
                                $('#btn-mapa-eib-reset').removeClass('d-none');
                                var datosDistritos = construirDatosDistritos(codigoProvincia);
                                anal1Chart = maps01(div, datosDistritos, '',
                                    'Distribución de los Servicios Educativos del Modelo de Servicio de EIB por Distrito'
                                    );
                            } else {
                                selectedProvinciaCodigo = null;
                                mapLevel = 'provincia';
                                mapData = otros;
                                if (provinciaValor && provinciaValor !== '0') {
                                    $('#btn-mapa-eib-reset').removeClass('d-none');
                                } else {
                                    $('#btn-mapa-eib-reset').addClass('d-none');
                                }
                                anal1Chart = maps01(div, data.info, '',
                                    'Distribución de los Servicios Educativos del Modelo de Servicio de EIB por Provincia'
                                    );
                            }
                            break;
                        case 'anal2':
                            gAnidadaColumn(div,
                                data.info.categoria,
                                data.info.series,
                                '',
                                'Matrícula educativa del modelo de servicio de EIB, periodo 2019-2025',
                                data.info.maxbar
                            );
                            break;
                        case 'anal3':
                            crearGraficoDistribucionPlazas(div, data,
                                'Distribución de la matricula educativa del modelo de servicio de EIB, según Nivel Educativo'
                            );
                            break;
                        case 'anal4':
                            gLineaMultiple(div, data, '',
                                'Matricula educativa del modelo de servicio EIB por años, segun Nivel Educativo',
                                '');
                            break;
                        case 'anal5':
                            crearGraficoDistribucionPlazas(div, data,
                                'Distribución de Docentes del Modelo de Servicio EIB, según Condición Laboral'
                            );
                            break;
                        case 'anal6':
                            xxxx(div,
                                data.categories,
                                data.series
                            );
                            // crearGraficoDistribucionPlazas(div, data,
                            //     'Distribución de Plazas Docentes de Educación, según Tipo de Trabajador');
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
                            // if ($.fn.DataTable.isDataTable('#tabla4')) {
                            //     $('#tabla4').DataTable().destroy();
                            // }
                            $('#tabla4').DataTable({
                                responsive: true,
                                autoWidth: false,
                                ordered: true,
                                language: table_language,
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

        function cargarGestion() {
            $.ajax({
                url: "{{ route('educacion.cubo.padron.eib.select.gestion', ['anio' => ':anio']) }}"
                    .replace(':anio', $('#anio').val()),
                type: 'GET',
                success: function(data) {
                    $('#gestion').empty();
                    if (Object.keys(data).length > 1)
                        $('#gestion').append('<option value="0">TODOS</option>');
                    $.each(data, function(index, value) {
                        $('#gestion').append(`<option value='${index}'>${value}</option>`);
                    });
                    cargarProvincia();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarProvincia() {
            $.ajax({
                url: "{{ route('educacion.cubo.padron.eib.select.provincia', ['anio' => ':anio', 'ugel' => ':gestion']) }}"
                    .replace(':anio', $('#anio').val())
                    .replace(':gestion', $('#gestion').val()),
                type: 'GET',
                success: function(data) {
                    $('#provincia').empty();
                    if (Object.keys(data).length > 1)
                        $('#provincia').append('<option value="0">TODOS</option>');
                    $.each(data, function(index, value) {
                        $('#provincia').append(`<option value='${index}'>${value}</option>`);
                    });
                    cargarDistrito();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarDistrito() {
            $.ajax({
                url: "{{ route('educacion.cubo.padron.eib.select.distrito', ['anio' => ':anio', 'ugel' => ':gestion', 'provincia' => ':provincia']) }}"
                    .replace(':anio', $('#anio').val())
                    .replace(':gestion', $('#gestion').val())
                    .replace(':provincia', $('#provincia').val()),
                type: 'GET',
                success: function(data) {
                    $('#distrito').empty();
                    if (Object.keys(data).length > 1)
                        $('#distrito').append('<option value="0">TODOS</option>');
                    $.each(data, function(index, value) {
                        $('#distrito').append(`<option value='${index}'>${value}</option>`);
                    });
                    cargarCards();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function descargarExcel(div) {
            window.open(
                "{{ route('educacion.padron.eib.reportes.download.excel', ['div' => ':div', 'anio' => ':anio', 'ugel' => ':ugel', 'provincia' => ':provincia', 'distrito' => ':distrito']) }}"
                .replace(':div', div)
                .replace(':anio', $('#anio').val())
                .replace(':ugel', $('#gestion').val())
                .replace(':provincia', $('#provincia').val())
                .replace(':distrito', $('#distrito').val())
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

        function construirDatosDistritos(codigoProvincia) {
            anal1DistritosValores = {};
            var lista = anal1Distritos[codigoProvincia] || [];
            var totalProvincia = 0;
            lista.forEach(function(item) {
                totalProvincia += item.conteo;
            });
            if (totalProvincia === 0) {
                totalProvincia = 1;
            }
            var datos = [];
            lista.forEach(function(item) {
                var hcKey = ubigeoToDistritoHcKey[item.distrito_codigo] || null;
                if (!hcKey) {
                    return;
                }
                var porcentaje = (100 * item.conteo) / totalProvincia;
                var valorRedondeado = Math.round(porcentaje * 10) / 10;
                datos.push([hcKey, valorRedondeado]);
                anal1DistritosValores[hcKey] = {
                    num: item.conteo,
                    dem: totalProvincia,
                    ind: valorRedondeado,
                    name: item.distrito
                };
            });
            return datos;
        }

        function construirMapaDistritosProvincia(nombreProvincia) {
            if (!otros2 || !otros2.features) {
                return otros2;
            }
            var nombre = (nombreProvincia || '').toUpperCase().trim();

            var provinceUbigeo = null;
            var provinceHcKey = null;

            if (otros && otros.features) {
                var provFeature = otros.features.find(function(element) {
                    var pName = '';
                    if (element.properties && element.properties.name) {
                        pName = String(element.properties.name).toUpperCase().trim();
                    }
                    return pName === nombre;
                });
                if (provFeature) {
                    if (provFeature.ubigeo) {
                        provinceUbigeo = String(provFeature.ubigeo);
                    }
                    if (provFeature.properties && provFeature.properties['hc-key']) {
                        provinceHcKey = String(provFeature.properties['hc-key']);
                    }
                }
            }

            // FIX: Hardcode explícito para Padre Abad para asegurar visualización (sobreescribe cualquier valor anterior)
            if (nombre === 'PADRE ABAD') {
                provinceUbigeo = '2503';
                provinceHcKey = 'pe-uc-pa';
            }

            var featuresFiltradas = otros2.features.filter(function(element) {
                var padre = '';
                if (element.padre) {
                    padre = String(element.padre).toUpperCase().trim();
                }

                var matchName = (padre === nombre);
                var matchUbigeo = false;
                if (provinceUbigeo && element.ubigeo) {
                    matchUbigeo = String(element.ubigeo).startsWith(provinceUbigeo);
                }

                var matchHcKey = false;
                if (provinceHcKey && element.properties && element.properties['hc-key']) {
                    matchHcKey = String(element.properties['hc-key']).startsWith(provinceHcKey + '-');
                }

                return matchName || matchUbigeo || matchHcKey;
            });
            return {
                title: otros2.title,
                version: otros2.version,
                type: otros2.type,
                copyright: otros2.copyright,
                copyrightShort: otros2.copyrightShort,
                copyrightUrl: otros2.copyrightUrl,
                crs: otros2.crs,
                "hc-transform": otros2["hc-transform"],
                features: featuresFiltradas
            };
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
                    minColor: '#e0f6f3',
                    maxColor: '#2a7f72',
                    // dataClasses: [{
                    //     to: 50,
                    //     color: '#ef5350', // Rojo
                    //     name: '< 50%'
                    // }, {
                    //     from: 50,
                    //     to: 95,
                    //     color: '#f5bd22', // Amarillo
                    //     name: '50% - 95%'
                    // }, {
                    //     from: 95,
                    //     color: '#66bb6a', // Verde
                    //     name: '> 95%'
                    // }],
                    showInLegend: false
                },

                series: [{
                    data: data,
                    name: 'NEXUS',
                    states: {
                        hover: {
                            // color: '#87CEEB' // SkyBlue
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
                    point: {
                        events: {
                            click: function() {
                                if (mapLevel === 'provincia') {
                                    var nombre = this.name ? this.name.toUpperCase().trim() : '';
                                    var encontrado = false;
                                    $('#provincia option').each(function() {
                                        if ($(this).text().toUpperCase().trim() === nombre) {
                                            var valor = $(this).val();
                                            $('#provincia').val(valor);
                                            encontrado = true;
                                            return false;
                                        }
                                    });
                                    if (encontrado) {
                                        $('#btn-mapa-eib-reset').removeClass('d-none');
                                        cargarDistrito();
                                    }
                                } else if (mapLevel === 'distrito') {
                                    var nombreDistrito = this.name ? this.name.toUpperCase().trim() :
                                    '';
                                    var encontradoDistrito = false;
                                    $('#distrito option').each(function() {
                                        if ($(this).text().toUpperCase().trim() ===
                                            nombreDistrito) {
                                            var valorDistrito = $(this).val();
                                            $('#distrito').val(valorDistrito);
                                            encontradoDistrito = true;
                                            return false;
                                        }
                                    });
                                    if (encontradoDistrito) {
                                        cargarCards();
                                    }
                                }
                            }
                        }
                    }
                }],
                tooltip: {
                    useHTML: true,
                    formatter: function() {
                        var codigo = this.point.properties && this.point.properties['hc-key'] ? this.point
                            .properties['hc-key'] : null;
                        if (mapLevel === 'distrito') {
                            var distritoData = codigo ? anal1DistritosValores[codigo] : null;
                            if (!distritoData) {
                                return `<strong>${this.point.name}</strong><br>Datos no disponibles`;
                            }
                            return `<div style="text-align:left;">
                                        <strong>${this.point.name}</strong><br>
                                        Servicios Educativos: ${Highcharts.numberFormat(distritoData.num,0)}<br>
                                        Participación: ${Highcharts.numberFormat(distritoData.ind, 1)}%
                                    </div>`;
                        } else {
                            var provinciaData = codigo ? anal3valores[codigo] : null;
                            if (!provinciaData) {
                                return `<strong>${this.point.name}</strong><br>Datos no disponibles`;
                            }
                            return `<div style="text-align:left;">
                                        <strong>${this.point.name}</strong><br>
                                        Servicios Educativos: ${Highcharts.numberFormat(provinciaData.num,0)}<br>
                                        Participación: ${Highcharts.numberFormat(provinciaData.ind, 1)}%
                                    </div>`;
                        }
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
                    /* min:0, */
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

        function gAnidadaColumn(div, categoria, series, titulo, subtitulo, maxBar) {
            var rango = categoria.length;
            var posPorcentaje = rango * 2 + 1;
            var cont = 0;
            var porMaxBar = maxBar * 0.5;
            Highcharts.chart(div, {
                chart: {
                    zoomType: 'xy',
                },
                colors: ['#5eb9aa', '#ef5350', '#f5bd22'],
                title: {
                    text: titulo, //'Browser market shares in January, 2018'
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        fontSize: '12px',
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
                        // title: {
                        //     enabled: false,
                        // },
                        /* labels: {
                            //format: '{value}°C',
                            //style: {
                            //    color: Highcharts.getOptions().colors[2]
                            //}
                        }, */
                        title: {
                            text: 'Matriculados',
                            style: {
                                //color: Highcharts.getOptions().colors[2],
                                fontSize: '11px',
                            },
                            enabled: false,
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
                        min: -300,
                        max: 120,
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
                            formatter: function() {
                                if (this.colorIndex == 1)
                                    return this.y + " %";
                                else
                                    return Highcharts.numberFormat(this.y, 0);
                            },
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

        function xxxx(div, categories, series) {
            Highcharts.chart(div, {
                chart: {
                    type: 'column'
                },
                title: {
                    text: ''
                },
                subtitle: {
                    text: 'Docentes del Modelo de Servicio de EIB por Nivel Educativo, según Condición Laboral'
                },
                xAxis: {
                    categories: categories
                },
                yAxis: {
                    title: {
                        text: 'Cantidad de plazas',
                        enabled: false,
                    }
                },
                series: series,
                plotOptions: {
                    column: {
                        dataLabels: {
                            enabled: true
                        }
                    }
                }
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/svg2pdf.js/2.2.0/svg2pdf.min.js"></script>
    <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>

    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
@endsection
