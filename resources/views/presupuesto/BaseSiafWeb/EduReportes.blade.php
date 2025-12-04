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
                    <h3 class="card-title text-white font-14">Presupuesto en Educación</h3>
                </div>
                <div class="card-body pb-0">
                    <div class="form-group row align-items-center vh-5">
                        <div class="col-lg-3 col-md-3 col-sm-4">
                            <h4 class="page-title font-12">Fuente: Padrón SIAF-WEB</h4>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="anio">Año</label>
                                <select id="anio" name="anio" class="form-control font-11">
                                    @foreach ($anios as $item)
                                        <option value="{{ $item->anio }}" {{ $item->anio == $aniomax ? 'selected' : '' }}>
                                            {{ $item->anio }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="ue">Unidad Ejecutora</label>
                                <select id="ue" name="ue" class="form-control font-11">
                                    <option value="0">TODOS</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="cg">Categoria de Gasto</label>
                                <select id="cg" name="cg" class="form-control font-11">
                                    <option value="0">TODOS</option>
                                </select>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-32 col-sm-2">
                            <div class="custom-select-container">
                                <label for="cp">Categoria Presupuestal</label>
                                <select id="cp" name="cp" class="form-control font-11">
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
                <div class="mt-0 font-9">
                    <h6 class="">Ejecución (DEV/PIM) <span class="float-right" id="card1i">0%</span></h6>
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
                            <p class="mb-0 mt-1 text-truncate">CERTIFICADO 2025</p>
                        </div>
                    </div>
                </div>
                <div class="mt-0 font-9">
                    <h6 class="">Ejecución (CERT/PIM) <span class="float-right" id="card2i">0%</span></h6>
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
                            <p class="mb-0 mt-1 text-truncate">COMPROMETIDO 2025</p>
                        </div>
                    </div>
                </div>
                <div class="mt-0 font-9">
                    <h6 class="">Ejecución (COMP/PIM) <span class="float-right" id="card3i">0%</span></h6>
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
                            <p class="mb-0 mt-1 text-truncate">DEVENGADO 2025</p>
                        </div>
                    </div>
                </div>
                <div class="mt-0 font-9">
                    <h6 class="">Ejecución (DEV/CERT) <span class="float-right" id="card4i">0%</span></h6>
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
        <div class="col-lg-4 col-md-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    {{-- <h3 class="text-black text-center font-weight-normal font-12 m-0">
                        Unidades Ejecutoras (% Ejecución)
                    </h3> --}}
                    <h3 class="card-title font-12">
                        Unidades Ejecutoras (% Ejecución)
                    </h3>
                </div>
                <div class="card-body py-0" style="height: 24rem" id="progress1">
                    {{-- <div class="mt-4">
                        <h6 class="text-uppercase">Target <span class="float-right">60%</span></h6>
                        <div class="progress progress-sm m-0">
                            <div class="progress-bar bg-info" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                aria-valuemax="100" style="width: 60%">
                                <span class="sr-only">60% Complete</span>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-md-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                    <h3 class="text-black text-center font-weight-normal font-12 m-0"></h3>
                </div>
                <div class="card-body p-0">
                    {{-- <div id="anal2" style="height: 25rem"></div> --}}
                    <div id="anal1"></div>
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
                    <div id="anal2" style="height: 25rem"></div>
                </div>
            </div>
        </div>

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

    </div>

    <div class="row">
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
                        Reporte de Ejecución Presupuestaria por Unidad Ejecutora
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
            head: ['#card1', '#card2', '#card3', '#card4'
                /* , '#card1i', '#card2i', '#card3i', '#card4i', '#card1b',
                                '#card2b', '#card3b', '#card4b' */
            ],
            anal: ['#anal1', '#anal2', '#anal3', '#anal4'],
            tabla: ['#ctabla1', '#ctabla2', '#ctabla3', '#ctabla4']
        };

        // DATOS DE PRUEBA Y EJECUCIÓN
        // Categorías (Unidades Ejecutoras)
        const categorias2 = [
            'ene',
            'feb',
            'mar',
            'abr',
            'may',
            'jun',
            'jul',
            'ago',
            'set',
            'oct',
            'nov',
            'dic',
        ];

        // Series de datos
        const series2 = [{
            name: 'PIM',
            data: [15567987, 30567987, 20567987, 15567987, 10567987, 10567987,
                15567987, 30567987, 20567987, 15567987, 10567987, 10567987
            ]
        }];

        // Opciones adicionales
        const opciones2 = {
            yAxisTitle: '',
            colors: ['#43beac', '#ef5350'],
            subtitle: ''
        };

        const categorias3 = ['ene',
            'feb',
            'mar',
            'abr',
            'may',
            'jun',
            'jul',
            'ago',
            'set',
            'oct',
            'nov',
            'dic',
        ];

        const series3 = [{
            name: "avance",
            data: [0, 10, 15, 20, 25, 30, 40, 50, 60, 70, 80, 108]
        }];

        $(document).ready(function() {
            Object.keys(spinners).forEach(key => {
                SpinnerManager.show(key);
            });
            $('#anio').on('change', function() {
                cargarEjecutora();
            });
            $('#ue').on('change', function() {
                cargarGasto();
            });
            $('#cg').on('change', function() {
                cargarPresupuesto();
            });
            $('#cp').on('change', function() {
                cargarCards();
            });

            cargarEjecutora();
        });

        function cargarCards() {
            panelGraficas('head');
            panelGraficas('progress1');
            panelGraficas('anal1');
            panelGraficas('anal2');
            panelGraficas('anal3');
            panelGraficas('anal4');
            panelGraficas('anal5');
            panelGraficas('tabla1');
        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('presupuesto.educacion.reportes.reporte') }}",
                data: {
                    'div': div,
                    "anio": $('#anio').val(),
                    "ue": $('#ue').val(),
                    "cg": $('#cg').val(),
                    "cp": $('#cp').val(),
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
                        case 'progress1':
                            var contenedor = $('#progress1');
                            contenedor.empty();
                            data.forEach(function(item) {
                                $color = item.avance > 96 ? 'bg-success-0' : (item.avance > 76 ?
                                    'bg-warning-0' : 'bg-orange-0');
                                var progresoHtml = `
                                    <div class="mt-4 ${item.estado?'table-warning':''} p-1 rounded">
                                        <h6 class="text-uppercase">${item.ue} <span class="float-right">${item.avance}%</span></h6>
                                        <div class="progress progress-sm m-0">
                                            <div class="progress-bar ${$color}" role="progressbar" aria-valuenow="${item.avance}" aria-valuemin="0"
                                                aria-valuemax="100" style="width: ${item.avance}%">
                                                <span class="sr-only">${item.avance}% Complete</span>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                contenedor.append(progresoHtml);
                            });

                            break;
                        case 'anal1':
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
                                    data: [15567987, 30567987, 20567987, 15567987, 10567987]
                                },
                                {
                                    name: 'DEVENGADO',
                                    data: [12567987, 25567987, 15567987, 10567987, 8567987]
                                }
                            ];
                            // Opciones adicionales
                            const opciones = {
                                yAxisTitle: '',
                                colors: ['#43beac', '#ef5350'],
                                subtitle: ''
                            };
                            // Crear el gráfico
                            crearGraficoComparativo(
                                div,
                                data.info.categorias,
                                data.info.series,
                                'Ejecución del Gasto Presupuestal por Unidades Ejecutoras (PIM vs DEVENGADO)',
                                opciones
                            );
                            break;
                        case 'anal2':
                            crearGraficoComparativo(
                                div,
                                data.info.categorias,
                                data.info.series,
                                'Certificado presupuestal mensual - año 2025',
                                opciones2
                            );
                            break;
                        case 'anal3':
                            gLineaMultiplePorcentual(div, {
                                cat: data.info.categorias,
                                dat: data.info.series
                            }, {
                                title: '',
                                subtitle: 'Certificado presupuestal mensual( % ) - año 2025',
                                yAxisTitle: '',
                                // legend: false, // opcional: si quieres forzar ocultar
                                dataLabels: true // por defecto true
                            });
                            break;
                        case 'anal4':
                            crearGraficoComparativo(
                                div,
                                data.info.categorias,
                                data.info.series,
                                'Ejecución del Gasto Presupuestal por Unidades Ejecutoras (PIM vs DEVENGADO)',
                                opciones2
                            );
                            break;
                        case 'anal5':
                            gLineaMultiplePorcentual(div, {
                                cat: data.info.categorias,
                                dat: data.info.series
                            }, {
                                title: '',
                                subtitle: 'Devengado presupuestal mensual( % ) - año 2025',
                                yAxisTitle: '',
                                // legend: false, // opcional: si quieres forzar ocultar
                                dataLabels: true // por defecto true
                            });
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

        function cargarEjecutora() {
            $.ajax({
                url: "{{ route('presupuesto.saifweb.detalle.select.ue', ['anio' => ':anio']) }}"
                    .replace(':anio', $('#anio').val()),
                type: 'GET',
                success: function(data) {
                    $('#ue').empty();
                    if (Object.keys(data).length > 1)
                        $('#ue').append('<option value="0">TODOS</option>');
                    $.each(data, function(index, value) {
                        $('#ue').append(`<option value='${index}'>${value}</option>`);
                    });
                    cargarGasto();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarGasto() {
            $.ajax({
                url: "{{ route('presupuesto.saifweb.detalle.select.cg', ['anio' => ':anio', 'ue' => ':ue']) }}"
                    .replace(':anio', $('#anio').val())
                    .replace(':ue', $('#ue').val()),
                type: 'GET',
                success: function(data) {
                    $('#cg').empty();
                    if (Object.keys(data).length > 1)
                        $('#cg').append('<option value="0">TODOS</option>');
                    $.each(data, function(index, value) {
                        $('#cg').append(`<option value='${index}'>${value}</option>`);
                    });
                    cargarPresupuesto();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarPresupuesto() {
            $.ajax({
                url: "{{ route('presupuesto.saifweb.detalle.select.cp', ['anio' => ':anio', 'ue' => ':ue', 'cg' => ':cg']) }}"
                    .replace(':anio', $('#anio').val())
                    .replace(':ue', $('#ue').val())
                    .replace(':cg', $('#cg').val()),
                type: 'GET',
                success: function(data) {
                    $('#cp').empty();
                    if (Object.keys(data).length > 1)
                        $('#cp').append('<option value="0">TODOS</option>');
                    $.each(data, function(index, value) {
                        $('#cp').append(`<option value='${index}'>${value}</option>`);
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

        function gLineaMultiplexx(div, data, titulo, subtitulo, titulovetical) {
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

        function crearGraficoComparativoxx(divId, categories, seriesData, title, options = {}) {
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
                        text: options.yAxisTitle || ''
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

        function crearGraficoComparativoxxx(divId, categories, seriesData, title, options = {}) {
            // Función auxiliar: formateo abreviado (K/M)
            function formatShort(value) {
                const abs = Math.abs(value);
                if (abs >= 1e6) {
                    return (value / 1e6).toFixed(0).replace(/\.0$/, '') + ' M';
                } else if (abs >= 1e3) {
                    return (value / 1e3).toFixed(0).replace(/\.0$/, '') + ' K';
                }
                return value.toString();
            }

            // Función auxiliar: formateo completo con comas (ej. 15567987 → "15,567,987")
            function formatFull(value) {
                return value.toLocaleString('en-US', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });
            }

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
                        text: options.yAxisTitle || ''
                    },
                    labels: {
                        style: {
                            fontSize: '11px'
                        }
                    }
                },
                tooltip: {
                    shared: true,
                    useHTML: true,
                    formatter: function() {
                        let s = `<span style="font-size:11px">${this.x}</span><br/>`;
                        this.points.forEach(point => {
                            const full = formatFull(point.y);
                            const short = formatShort(point.y);
                            s +=
                                `<span style="color:${point.series.color}">${point.series.name}</span>: <b>${full}</b> (${short})<br/>`;
                        });
                        return s;
                    }
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.15,
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            formatter: function() {
                                return formatShort(this.y);
                            },
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

        function crearGraficoComparativo(divId, categories, seriesData, title, options = {}) {
            // 🔢 Funciones auxiliares de formato
            function formatShort(value) {
                const abs = Math.abs(value);
                if (abs >= 1e6) {
                    return (value / 1e6).toFixed(0).replace(/\.0$/, '') + ' M';
                } else if (abs >= 1e3) {
                    return (value / 1e3).toFixed(0).replace(/\.0$/, '') + ' K';
                }
                return value.toString();
            }

            function formatFull(value) {
                return value.toLocaleString('en-US', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });
            }

            // 🎯 Detectar si es una sola serie
            const isSingleSeries = seriesData.length === 1;

            // 🎨 Colores
            const defaultColors = ['#17A2B8', '#DC3545'];
            const colors = options.colors || defaultColors;

            // 📋 Configuración de leyenda (oculta si solo hay 1 serie)
            const legendConfig = {
                align: 'center',
                verticalAlign: 'bottom',
                backgroundColor: 'transparent',
                itemStyle: {
                    fontSize: '12px',
                    fontWeight: 'normal'
                },
                enabled: !isSingleSeries // ← clave: desactiva si solo hay una serie
            };

            // 📊 Crear gráfico
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
                        text: options.yAxisTitle || ''
                    },
                    labels: {
                        style: {
                            fontSize: '11px'
                        }
                    }
                },
                tooltip: {
                    shared: true,
                    useHTML: true,
                    formatter: function() {
                        let s = `<span style="font-size:11px">${this.key}</span><br/>`;
                        this.points.forEach(point => {
                            const full = formatFull(point.y);
                            const short = formatShort(point.y);
                            if (isSingleSeries) {
                                // Solo valor (sin nombre de la serie)
                                s += `<b>${full}</b> (${short})<br/>`;
                            } else {
                                // Nombre de serie + valor
                                s +=
                                    `<span style="color:${point.series.color}">${point.series.name}</span>: <b>${full}</b> (${short})<br/>`;
                            }
                        });
                        return s;
                    }
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.15,
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            formatter: function() {
                                return formatShort(this.y);
                            },
                            style: {
                                fontSize: '10px',
                                fontWeight: 'normal',
                                textOutline: 'none'
                            }
                        }
                    }
                },
                legend: legendConfig, // ← usa la configuración condicional
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
                                layout: 'horizontal',
                                enabled: !isSingleSeries // mantener coherencia en responsive
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

        function gLineaMultiple(div, data, titulo, subtitulo = '', titulovetical = '', options = {}) {
            // 🔢 Formato abreviado (K/M) y completo
            function formatShort(value) {
                const abs = Math.abs(value);
                if (abs >= 1e6) return (value / 1e6).toFixed(1).replace(/\.0$/, '') + ' M';
                if (abs >= 1e3) return (value / 1e3).toFixed(1).replace(/\.0$/, '') + ' K';
                return value.toString();
            }

            function formatFull(value) {
                return value.toLocaleString('en-US', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });
            }

            // 🎯 Detectar si es una sola serie
            const isSingleSeries = data.dat.length === 1;

            // 🎨 Colores
            const defaultColors = [
                '#17A2B8', '#FF6B35', '#28A745', '#DC3545', '#6F42C1', '#FFC107', '#007BFF'
            ];
            const colors = options.colors || defaultColors;

            // 📋 Leyenda: oculta automáticamente si solo hay 1 serie (a menos que se fuerce con { legend: true })
            const userWantsLegend = options.legend === true;
            const userHidesLegend = options.legend === false;
            const showLegend = userWantsLegend || (!userHidesLegend && !isSingleSeries);

            // 📈 Gráfico
            Highcharts.chart(div, {
                chart: {
                    type: 'line',
                    backgroundColor: 'transparent',
                    style: {
                        fontFamily: 'Arial, sans-serif'
                    }
                },
                title: {
                    text: titulo,
                    align: 'center',
                    style: {
                        fontSize: '16px',
                        fontWeight: 'bold',
                        color: '#333'
                    }
                },
                subtitle: {
                    text: subtitulo,
                    align: 'center',
                    style: {
                        fontSize: '12px',
                        color: '#666'
                    }
                },
                xAxis: {
                    categories: data.cat,
                    crosshair: true,
                    labels: {
                        style: {
                            fontSize: '11px'
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: titulovetical,
                        style: {
                            fontSize: '12px'
                        }
                    },
                    labels: {
                        style: {
                            fontSize: '11px'
                        }
                    },
                    gridLineWidth: 1
                },
                tooltip: {
                    shared: true,
                    useHTML: true,
                    formatter: function() {
                        let s = `<span style="font-size:12px">${this.x}</span><br/>`;
                        this.points.forEach(point => {
                            const full = formatFull(point.y);
                            const short = formatShort(point.y);
                            if (isSingleSeries) {
                                // ✅ Solo valor — sin nombre de la serie
                                s += `<b>${full}</b> (${short})<br/>`;
                            } else {
                                // Nombre + valor
                                s += `<span style="color:${point.series.color}">\u25CF</span> ` +
                                    `<b>${point.series.name}</b>: ${full} (${short})<br/>`;
                            }
                        });
                        return s;
                    }
                },
                plotOptions: {
                    line: {
                        marker: {
                            enabled: true,
                            radius: 4
                        },
                        lineWidth: 2,
                        states: {
                            hover: {
                                lineWidth: 3
                            }
                        },
                        dataLabels: {
                            enabled: options.dataLabels !== false,
                            formatter: function() {
                                return formatShort(this.y);
                            },
                            style: {
                                fontSize: '10px',
                                fontWeight: 'normal',
                                textOutline: 'none'
                            },
                            y: -8
                        }
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle',
                    backgroundColor: 'transparent',
                    itemStyle: {
                        fontSize: '12px',
                        fontWeight: 'normal'
                    },
                    enabled: showLegend // ✅ Automático: false si 1 serie (a menos que se fuerce)
                },
                series: data.dat,
                colors: colors,
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
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom',
                                enabled: showLegend
                            },
                            yAxis: {
                                title: {
                                    text: ''
                                }
                            }
                        }
                    }]
                }
            });
        }

        function gLineaMultiplePorcentual(div, data, options = {}) {
            // 🔢 Formato para porcentajes
            function formatPercent(value, decimals = 1) {
                return Number(value).toFixed(decimals).replace(/\.0+$/, '') + '%';
            }

            // 🎯 Detectar si es una sola serie
            const isSingleSeries = data.dat.length === 1;

            // 🎨 Colores
            const defaultColors = [
                '#17A2B8', '#FF6B35', '#28A745', '#DC3545', '#6F42C1', '#FFC107', '#007BFF'
            ];
            const colors = options.colors || defaultColors;

            // 📋 Leyenda: oculta automáticamente si solo hay 1 serie (a menos que se fuerce)
            const userWantsLegend = options.legend === true;
            const userHidesLegend = options.legend === false;
            const showLegend = userWantsLegend || (!userHidesLegend && !isSingleSeries);

            // 📈 Gráfico
            Highcharts.chart(div, {
                chart: {
                    type: 'line',
                    backgroundColor: 'transparent',
                    style: {
                        fontFamily: 'Arial, sans-serif'
                    }
                },
                title: {
                    text: options.title || '',
                    align: 'center',
                    style: {
                        fontSize: '16px',
                        fontWeight: 'bold',
                        color: '#333'
                    }
                },
                subtitle: {
                    text: options.subtitle || '',
                    align: 'center',
                    style: {
                        fontSize: '14px',
                        color: '#666'
                    }
                },
                xAxis: {
                    categories: data.cat,
                    crosshair: true,
                    labels: {
                        style: {
                            fontSize: '11px'
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: options.yAxisTitle || '',
                        style: {
                            fontSize: '12px'
                        }
                    },
                    labels: {
                        format: '{value}%',
                        style: {
                            fontSize: '11px'
                        }
                    },
                    min: 0,
                    // max: 100, // ❌ no fijar, permitir >100% (como 108%)
                    gridLineWidth: 1
                },
                tooltip: {
                    shared: true,
                    useHTML: true,
                    formatter: function() {
                        let s = `<span style="font-size:12px">${this.key}</span><br/>`;
                        this.points.forEach(point => {
                            // ✅ SIEMPRE mostrar el nombre, incluso con 1 serie (tu requerimiento)
                            const pct = formatPercent(point.y, 1); // 1 decimal, pero sin .0 innecesario
                            s += `<span style="color:${point.series.color}">\u25CF</span> ` +
                                `<b>${point.series.name}</b>: ${pct}<br/>`;
                        });
                        return s;
                    }
                },
                plotOptions: {
                    line: {
                        marker: {
                            enabled: true,
                            radius: 4
                        },
                        lineWidth: 2,
                        states: {
                            hover: {
                                lineWidth: 3
                            }
                        },
                        dataLabels: {
                            enabled: options.dataLabels !== false,
                            formatter: function() {
                                return formatPercent(this.y, 0); // sin decimales en los labels (ej: 10%)
                            },
                            style: {
                                fontSize: '10px',
                                fontWeight: 'normal',
                                textOutline: 'none'
                            },
                            y: -8
                        }
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle',
                    backgroundColor: 'transparent',
                    itemStyle: {
                        fontSize: '12px',
                        fontWeight: 'normal'
                    },
                    enabled: showLegend
                },
                series: data.dat,
                colors: colors,
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
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom',
                                enabled: showLegend
                            },
                            yAxis: {
                                title: {
                                    text: ''
                                },
                                labels: {
                                    format: '{value}%'
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
