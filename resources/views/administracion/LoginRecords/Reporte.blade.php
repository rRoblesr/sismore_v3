@extends('layouts.main', ['activePage' => 'usuarios', 'titlePage' => 'REPORTE DE ACCESOS'])

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endsection

@section('content')
    <div class="content">

        <div class="row">
            <div class="col-md-4 mb-2">
                <label for="fecha_inicio">Fecha Inicio</label>
                <input type="date" id="fecha_inicio" class="form-control" value="{{ date('Y-m-d', strtotime('-30 days')) }}">
            </div>
            <div class="col-md-4 mb-2">
                <label for="fecha_fin">Fecha Fin</label>
                <input type="date" id="fecha_fin" class="form-control" value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-4 mb-2">
                <label for="sistema">Sistema</label>
                <select id="sistema" class="form-control">
                    <option value="">Todos los sistemas</option>
                    @foreach ($sistemas as $sistema)
                        <option value="{{ $sistema->id }}">{{ $sistema->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-xl-3">
                <div class="card-box border border-plomo-0">
                    <div class="media">
                        <div class="avatar-md mr-2">
                            <i class="mdi mdi-login avatar-title font-30 text-success"></i>
                        </div>
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold" id="card1">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">Total Accesos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card-box border border-plomo-0">
                    <div class="media">
                        <div class="avatar-md mr-2">
                            <i class="mdi mdi-account-multiple avatar-title font-30 text-primary"></i>
                        </div>
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold" id="card2">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">Usuarios Únicos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card-box border border-plomo-0">
                    <div class="media">
                        <div class="avatar-md mr-2">
                            <i class="mdi mdi-calendar-today avatar-title font-30 text-warning"></i>
                        </div>
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold" id="card3">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">Accesos Hoy</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card-box border border-plomo-0">
                    <div class="media">
                        <div class="avatar-md mr-2">
                            <i class="mdi mdi-domain avatar-title font-30 text-info"></i>
                        </div>
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold" id="card4">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">Top Entidad</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-8">
                <div class="card card-border">
                    <div class="card-header border-success-0 bg-transparent pb-0">
                        <h3 class="card-title text-primary">Tendencia de Accesos</h3>
                    </div>
                    <div class="card-body">
                        <div id="grafico1" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card card-border">
                    <div class="card-header border-success-0 bg-transparent pb-0">
                        <h3 class="card-title text-primary">Accesos por Sistema (Top 10)</h3>
                    </div>
                    <div class="card-body">
                        <div id="grafico2" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-border">
                            <div class="card-header border-success-0 bg-transparent pb-2 pl-0">
                                <h4 class="card-title">Historial de Accesos</h4>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table id="tabla" class="table table-striped table-bordered font-12">
                                        <thead class="cabecera-dataTable table-success-0 text-white">
                                            <tr>
                                                <th class="text-center">Nº</th>
                                                <th class="text-center">Usuario</th>
                                                <th class="text-center">Entidad</th>
                                                <th class="text-center">Oficina</th>
                                                <th class="text-center">IP</th>
                                                <th class="text-center">Login</th>
                                                <th class="text-center">Logout</th>
                                                <th class="text-center">Navegador</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('js')
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script>
        $(document).ready(function() {
            function cargarResumen() {
                $.ajax({
                    url: "{{ route('loginrecords.reporte.resumen') }}",
                    type: "GET",
                    dataType: "json",
                    data: {
                        fecha_inicio: $('#fecha_inicio').val(),
                        fecha_fin: $('#fecha_fin').val(),
                        sistema: $('#sistema').val()
                    },
                    success: function(data) {
                        $('#card1').text(data.card1);
                        $('#card2').text(data.card2);
                        $('#card3').text(data.card3);
                        $('#card4').text(data.card4);

                        Highcharts.chart('grafico1', {
                            chart: { type: 'line' },
                            title: { text: '' },
                            xAxis: { 
                                categories: data.grafico1.map(item => item.fecha),
                                crosshair: true
                            },
                            yAxis: { min: 0, title: { text: 'Accesos' } },
                            tooltip: {
                                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
                                footerFormat: '</table>',
                                shared: true,
                                useHTML: true
                            },
                            plotOptions: {
                                column: {
                                    pointPadding: 0.2,
                                    borderWidth: 0
                                }
                            },
                            series: [{
                                name: 'Accesos',
                                data: data.grafico1.map(item => parseInt(item.total)),
                                color: '#5eb9aa'
                            }],
                            credits: { enabled: false }
                        });

                        Highcharts.chart('grafico2', {
                            chart: { type: 'pie' },
                            title: { text: '' },
                            tooltip: { pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>' },
                            plotOptions: {
                                pie: {
                                    allowPointSelect: true,
                                    cursor: 'pointer',
                                    dataLabels: { enabled: true, format: '<b>{point.name}</b>: {point.y}' }
                                }
                            },
                            series: [{
                                name: 'Accesos',
                                colorByPoint: true,
                                data: data.grafico2
                            }],
                            credits: { enabled: false }
                        });
                    }
                });
            }

            cargarResumen();

            var tabla = $('#tabla').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: false,
                destroy: true,
                language: table_language,
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('loginrecords.reporte.listar') }}",
                    type: "GET",
                    data: function(d) {
                        d.fecha_inicio = $('#fecha_inicio').val();
                        d.fecha_fin = $('#fecha_fin').val();
                        d.sistema = $('#sistema').val();
                    }
                },
                columnDefs: [
                    { targets: 0, className: 'text-center' },
                    { targets: 4, className: 'text-center' },
                    { targets: 5, className: 'text-center' },
                    { targets: 6, className: 'text-center' }
                ]
            });

            $('#fecha_inicio, #fecha_fin, #sistema').change(function() {
                cargarResumen();
                tabla.ajax.reload();
            });
        });
    </script>
@endsection
