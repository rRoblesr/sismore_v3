@extends('layouts.main', ['activePage' => 'importacion', 'titlePage' => ''])

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
    <style>

    </style>
    <style>
        /* body {
                        background-color: #f5f5f5;
                        padding: 20px;
                        font-family: Arial, sans-serif;
                    } */

        .dashboard-container {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .unidades-box {
            background: white;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            /* height: 100%; */
        }

        .unidades-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }

        .unidad-item {
            margin-bottom: 15px;
            position: relative;
        }

        .unidad-nombre {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .unidad-porcentaje {
            font-size: 13px;
            font-weight: bold;
            color: #333;
        }

        .progress-bar-custom {
            height: 8px;
            background-color: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        .color-yellow {
            background-color: #F5A623;
        }

        .color-cyan {
            background-color: #17A2B8;
        }

        .color-red {
            background-color: #DC3545;
        }

        .grafico-container {
            background: white;
            border-radius: 8px;
            padding: 20px;
            min-height: 400px;
        }

        @media (max-width: 768px) {
            .unidades-box {
                margin-bottom: 20px;
            }
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
                    <h3 class="card-title text-white font-14">Presupuesto en Educación</h3>
                </div>
                <div class="card-body pb-0">
                    <div class="form-group row align-items-center vh-5">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <h4 class="page-title font-12">Fuente: Padrón SIAF-WEB</h4>
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
                                <label for="gestion">Unidad Ejecutora</label>
                                <select id="gestion" name="gestion" class="form-control font-11">
                                    <option value="0">TODOS</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="provincia">Categoria de Gasto</label>
                                <select id="provincia" name="provincia" class="form-control font-11">
                                    <option value="0">TODOS</option>
                                </select>
                            </div>
                        </div>


                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="distrito">Categoria Presupuestal</label>
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
                            <p class="mb-0 mt-1 text-truncate">PIM 2025</p>
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
                            <p class="mb-0 mt-1 text-truncate">CERTIFICADO 2025</p>
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
                            <p class="mb-0 mt-1 text-truncate">COMPROMETIDO 2025</p>
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
                            <p class="mb-0 mt-1 text-truncate">DEVENGADO 2025</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Columna Izquierda: Unidades Ejecutoras -->
        <div class="col-md-4 col-lg-4">
            <div class="unidades-box">
                <div class="unidades-title">Unidades Ejecutoras (% Ejecución)</div>

                <div class="unidad-item">
                    <div class="unidad-nombre">
                        <span>DREU</span>
                        <span class="unidad-porcentaje">45.8 %</span>
                    </div>
                    <div class="progress-bar-custom">
                        <div class="progress-fill color-yellow" style="width: 45.8%"></div>
                    </div>
                </div>

                <div class="unidad-item">
                    <div class="unidad-nombre">
                        <span>UGEL PURUS</span>
                        <span class="unidad-porcentaje">80 %</span>
                    </div>
                    <div class="progress-bar-custom">
                        <div class="progress-fill color-cyan" style="width: 80%"></div>
                    </div>
                </div>

                <div class="unidad-item">
                    <div class="unidad-nombre">
                        <span>UGEL ATALAYA</span>
                        <span class="unidad-porcentaje">45.8 %</span>
                    </div>
                    <div class="progress-bar-custom">
                        <div class="progress-fill color-red" style="width: 45.8%"></div>
                    </div>
                </div>

                <div class="unidad-item">
                    <div class="unidad-nombre">
                        <span>UGEL CORONEL PORTILLO</span>
                        <span class="unidad-porcentaje">83.3 %</span>
                    </div>
                    <div class="progress-bar-custom">
                        <div class="progress-fill color-yellow" style="width: 83.3%"></div>
                    </div>
                </div>

                <div class="unidad-item">
                    <div class="unidad-nombre">
                        <span>UGEL PADRE ABAD</span>
                        <span class="unidad-porcentaje">75 %</span>
                    </div>
                    <div class="progress-bar-custom">
                        <div class="progress-fill color-yellow" style="width: 75%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Gráfico -->
        <div class="col-md-8 col-lg-8">
            <div class="grafico-container">
                <div id="chartContainer"></div>
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
                    {{-- <div class="card-widgets">
                        <button type="button" class="btn btn-success-0 btn-xs" onclick="descargarExcel('tabla4')">
                            <i class="fa fa-file-excel"></i> Descargar</button>
                    </div> --}}
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
                cargarGestion();
            });
            $('#gestion').on('change', function() {
                cargarProvincia();
            });
            $('#provincia').on('change', function() {
                cargarDistrito();
            });
            $('#distrito').on('change', function() {
                cargarCards();
            });

            mapData = otros;
            mapData.features.forEach((element, key) => {
                console.log('["' + element.properties['hc-key'] + '", ' + (key + 1) + '],');
            });
            cargarGestion();

            // DATOS DE PRUEBA Y EJECUCIÓN
            // Categorías (Unidades Ejecutoras)
            const categorias = [
                'DREU',
                'UGEL CORONEL PORTILLO',
                'UGEL PADRE ABAD',
                'UGEL ATALAYA',
                'UGEL PURUS'
            ];

            // Series de datos
            const series = [{
                    name: 'PIM',
                    data: [15, 30, 20, 15, 10]
                },
                {
                    name: 'DEVENGADO',
                    data: [12, 25, 15, 10, 8]
                }
            ];

            // Opciones adicionales
            const opciones = {
                yAxisTitle: 'Millones',
                colors: ['#17A2B8', '#DC3545'],
                subtitle: ''
            };

            // Crear el gráfico
            crearGraficoComparativo(
                'chartContainer',
                categorias,
                series,
                'Ejecución del Gasto Presupuestal por Unidades Ejecutoras (PIM vs DEVENGADO)',
                opciones
            );

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
                            anal3 = maps01(div, data.info, '',
                                'Distribución de los Servicios Educativos del Modelo de Servicio de EIB por Provincia'
                            );
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
                                    Servicios Educativos: ${Highcharts.numberFormat(provinciaData.num,0)}<br>
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

        function crearGraficoMatricula(div, config) {
            const defaultOpciones = {
                altura: 500,
                colorBarras: '#00BCD4',
                colorLinea: '#E91E63',
                exportar: true,
                mostrarEtiquetas: true,
                tipoGrafico: 'column', // 'column', 'bar', 'line'
                etiquetaEjeY1: 'Matriculados',
                etiquetaEjeY2: '% Avance',
                nombreSerie1: 'MATRICULADOS',
                nombreSerie2: '% AVANCE'
            };

            // Combinar opciones
            const opciones = {
                ...defaultOpciones,
                ...config.opciones
            };

            // Crear el gráfico
            Highcharts.chart(div, {
                chart: {
                    type: opciones.tipoGrafico,
                    // height: opciones.altura
                },
                title: {
                    text: config.titulo,
                    style: {
                        fontSize: '14px',
                        color: '#666'
                    }
                },
                xAxis: {
                    categories: config.years,
                    crosshair: true,
                },
                yAxis: [{
                    title: {
                        text: opciones.etiquetaEjeY1,
                        style: {
                            color: opciones.colorBarras
                        },
                        enabled: false,
                    },
                    labels: {
                        format: '{value}'
                    }
                }, {
                    title: {
                        text: opciones.etiquetaEjeY2,
                        style: {
                            color: opciones.colorLinea
                        },
                        enabled: false,
                    },
                    labels: {
                        format: '{value}%'
                    },
                    opposite: true,
                    min: 0,
                    max: 100
                }],
                tooltip: {
                    shared: true,
                    formatter: function() {
                        let tooltip = '<b>' + this.x + '</b><br/>';
                        this.points.forEach(function(point) {
                            tooltip += '<span style="color:' + point.color + '">\u25CF</span> ' +
                                point.series.name + ': <b>' +
                                (point.series.name.includes('%') ?
                                    (point.y ? point.y + '%' : 'N/A') :
                                    point.y.toLocaleString()) +
                                '</b><br/>';
                        });
                        return tooltip;
                    }
                },
                legend: {
                    align: 'center',
                    verticalAlign: 'bottom',
                    layout: 'horizontal'
                },
                plotOptions: {
                    column: {
                        dataLabels: {
                            enabled: opciones.mostrarEtiquetas,
                            format: '{point.y:,.0f}',
                            style: {
                                fontSize: '11px'
                            }
                        }
                    },
                    bar: {
                        dataLabels: {
                            enabled: opciones.mostrarEtiquetas,
                            format: '{point.y:,.0f}',
                            style: {
                                fontSize: '11px'
                            }
                        }
                    },
                    line: {
                        dataLabels: {
                            enabled: opciones.mostrarEtiquetas,
                            formatter: function() {
                                return this.y ? this.y + '%' : '';
                            },
                            style: {
                                fontSize: '11px',
                                fontWeight: 'bold'
                            }
                        },
                        marker: {
                            enabled: true,
                            radius: 5
                        }
                    }
                },
                series: [{
                    name: opciones.nombreSerie1,
                    type: opciones.tipoGrafico,
                    data: config.matriculados,
                    color: opciones.colorBarras,
                    yAxis: 0
                }, {
                    name: opciones.nombreSerie2,
                    type: 'line',
                    data: config.porcentajes,
                    color: opciones.colorLinea,
                    yAxis: 1,
                    lineWidth: 2
                }],
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: opciones.exportar,
                    buttons: {
                        contextButton: {
                            menuItems: ['downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG']
                        }
                    }
                }
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

        function crearGraficoComparativo(divId, categories, seriesData, title, options = {}) {
            const defaultColors = ['#17A2B8', '#DC3545'];
            const colors = options.colors || defaultColors;

            Highcharts.chart(divId, {
                chart: {
                    type: 'column',
                    backgroundColor: 'transparent',
                    style: {
                        fontFamily: 'Arial, sans-serif'
                    }
                },
                title: {
                    text: title,
                    align: 'center',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'normal',
                        color: '#666'
                    }
                },
                subtitle: {
                    text: options.subtitle || '',
                    align: 'center'
                },
                xAxis: {
                    categories: categories,
                    crosshair: true,
                    labels: {
                        style: {
                            fontSize: '11px'
                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: options.yAxisTitle || 'Millones'
                    },
                    labels: {
                        style: {
                            fontSize: '11px'
                        }
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:11px">{point.key}</span><br>',
                    pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y:.1f} M</b><br/>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.15,
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.0f} M',
                            style: {
                                fontSize: '10px',
                                fontWeight: 'normal',
                                textOutline: 'none'
                            }
                        }
                    }
                },
                legend: {
                    align: 'center',
                    verticalAlign: 'bottom',
                    backgroundColor: 'transparent',
                    itemStyle: {
                        fontSize: '12px',
                        fontWeight: 'normal'
                    }
                },
                colors: colors,
                series: seriesData,
                credits: {
                    enabled: false
                },
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                align: 'center',
                                verticalAlign: 'bottom',
                                layout: 'horizontal'
                            },
                            yAxis: {
                                labels: {
                                    align: 'left',
                                    x: 0,
                                    y: -5
                                },
                                title: {
                                    text: ''
                                }
                            }
                        }
                    }]
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
    <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>

    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
@endsection
