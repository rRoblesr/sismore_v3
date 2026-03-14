@extends('layouts.main', ['activePage' => 'importacion', 'titlePage' => 'REPORTE LOCALES BENEFICIADOS'])

@section('css')
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/fixedHeader.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/scroller.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- Highcharts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>


@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header bg-success-0">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()">
                            <i class="fa fa-redo"></i> Actualizar</button>
                    </div>
                    <h3 class="card-title text-white font-14">Listado de Locales Educativos beneficiarios del Programa de
Mantenimiento para el año fiscal 2026</h3>
                </div>
                <div class="card-body pb-0">
                    <div class="form-group row align-items-center vh-5">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <h4 class="page-title font-12 m-0">Fuente: PRONIED <br>{{ $actualizado }}</h4>
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
                                <label for="ugel">UGEL</label>
                                <select id="ugel" name="ugel" class="form-control font-11">
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

    <!-- Cards -->
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="card-box border border-plomo-0">
                <div class="media">
                    <div class="avatar-md mr-2">
                        <i class="fa fa-building avatar-title font-30 text-dark"></i>
                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span id="card1">0</span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">Locales Beneficiados</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="card-box border border-plomo-0">
                <div class="media">
                    <div class="avatar-md mr-2">
                        <i class="fa fa-money-bill-alt avatar-title font-30 text-dark"></i>
                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span id="card2">0</span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">Monto Asignado Total</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="card-box border border-plomo-0">
                <div class="media">
                    <div class="avatar-md mr-2">
                        <i class="fa fa-tools avatar-title font-30 text-dark"></i>
                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span id="card3">0</span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">Total Instituciones Educativas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    <h3 class="card-title font-12">Evolución del Monto Asignado por Año</h3>
                </div>
                <div class="card-body">
                     <div id="grafica_anios" style="height: 400px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    <h3 class="card-title font-12">LISTADO DE LOCALES EDUCATIVOS BENEFICIARIOS</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabla_locales" class="table table-striped table-bordered font-12" style="width:100%">
                                <thead class="cabecera-dataTable table-success-0 text-white">
                                    <tr>
                                        <th>CÓDIGO LOCAL</th>
                                        <th>UGEL</th>
                                        <th>PROVINCIA</th>
                                        <th>DISTRITO</th>
                                        <th>MONTO TOTAL</th>
                                        <th>N° SERVICIOS</th>
                                        <th>ACCIONES</th>
                                    </tr>
                                </thead>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal_servicios" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Servicios del Local</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <p id="contenido_servicios" style="white-space: pre-wrap;"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    

@endsection

@section('js')
<script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script type="text/javascript">
    var tabla_locales;
    $(document).ready(function() {
        // Carga inicial en cadena
        cargarUgeles();
        
        $('#anio').on('change', function() {
            cargarUgeles(); // Reinicia toda la cadena
        });

        $('#ugel').on('change', function() {
            cargarProvincias(); // Reinicia desde provincias
        });

        $('#provincia').on('change', function() {
            cargarDistritos(); // Reinicia desde distritos
        });

        $('#distrito').on('change', function() {
            cargarData(); // Solo actualiza datos
        });

        // Evento para abrir modal
        $(document).on('click', '.btn-servicios', function() {
            var cod_local = $(this).data('codlocal');
            $('#myModalLabel').text('Instituciones Educativas del Local: ' + cod_local);
            $('#contenido_servicios').html('<div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x text-success"></i><br>Cargando información...</div>');
            $('#modal_servicios').modal('show');
            
            $.ajax({
                url: "{{ route('reportelocalesbeneficiados.reporte') }}",
                type: 'GET',
                data: {
                    div: 'cargar_instituciones',
                    cod_local: cod_local
                },
                success: function(data) {
                    if (data.html) {
                        $('#contenido_servicios').html(data.html);
                    } else {
                        $('#contenido_servicios').html(data); // Fallback if direct string
                    }
                },
                error: function() {
                    $('#contenido_servicios').html('<div class="alert alert-danger">Error al cargar los datos.</div>');
                }
            });
        });
    });

    function cargarUgeles() {
        $.ajax({
            url: "{{ route('reportelocalesbeneficiados.reporte') }}",
            type: 'GET',
            data: {
                div: 'cargar_ugeles',
                anio: $('#anio').val()
            },
            success: function(data) {
                var select = $('#ugel');
                var selected = select.val(); // Intentar mantener selección si existe
                select.empty();
                select.append('<option value="0">TODOS</option>');
                $.each(data, function(i, item) {
                    select.append('<option value="' + item.id + '">' + item.nombre + '</option>');
                });
                // Si el valor previo sigue existiendo, seleccionarlo (útil si solo cambia año y la ugel existe)
                // Pero por defecto mejor resetear a 0 al cambiar año
                // select.val(selected); 
                
                // Continuar cadena
                cargarProvincias();
            }
        });
    }

    function cargarProvincias() {
        $.ajax({
            url: "{{ route('reportelocalesbeneficiados.reporte') }}",
            type: 'GET',
            data: {
                div: 'cargar_provincias',
                anio: $('#anio').val(),
                ugel_id: $('#ugel').val()
            },
            success: function(data) {
                var select = $('#provincia');
                select.empty();
                select.append('<option value="0">TODOS</option>');
                $.each(data, function(i, item) {
                    select.append('<option value="' + item.id + '">' + item.nombre + '</option>');
                });
                
                // Continuar cadena
                cargarDistritos();
            }
        });
    }

    function cargarDistritos() {
        $.ajax({
            url: "{{ route('reportelocalesbeneficiados.reporte') }}",
            type: 'GET',
            data: {
                div: 'cargar_distritos',
                anio: $('#anio').val(),
                ugel_id: $('#ugel').val(),
                provincia_id: $('#provincia').val()
            },
            success: function(data) {
                var select = $('#distrito');
                select.empty();
                select.append('<option value="0">TODOS</option>');
                $.each(data, function(i, item) {
                    select.append('<option value="' + item.id + '">' + item.nombre + '</option>');
                });
                
                // Finalizar cadena cargando datos
                cargarData();
            }
        });
    }

    function cargarData() {
        cargarHead();
        cargarGraficaAnios();
        cargarTabla();
    }

    function cargarTabla() {
        if ($.fn.DataTable.isDataTable('#tabla_locales')) {
            $('#tabla_locales').DataTable().destroy();
        }

        tabla_locales = $('#tabla_locales').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "autoWidth": false,
            "ajax": {
                "url": "{{ route('reportelocalesbeneficiados.reporte') }}",
                "type": "GET",
                "data": {
                    "div": "tabla",
                    "anio": $('#anio').val(),
                    "ugel_id": $('#ugel').val(),
                    "provincia_id": $('#provincia').val(),
                    "distrito_id": $('#distrito').val()
                }
            },
            "order": [[ 0, "asc" ]],
            "columns": [
                    { data: 'cod_local', name: 'edu_impor_locales_beneficiados.cod_local' },
                    { data: 'ugel', name: 'edu_ugel.nombre' },
                    { data: 'provincia', name: 'prov.nombre' },
                    { data: 'distrito', name: 'dist.nombre' },
                    { data: 'monto_total', name: 'monto_total', searchable: false },
                    { data: 'numero_servicios', name: 'edu_impor_locales_beneficiados.numero_servicios' },
                    { data: 'servicios_btn', name: 'servicios_btn', orderable: false, searchable: false, className: "text-center" }
                ],
                "language": table_language
            });
        }

    function cargarHead() {
        $.ajax({
            url: "{{ route('reportelocalesbeneficiados.reporte') }}",
            type: 'GET',
            data: {
                div: 'head',
                anio: $('#anio').val(),
                ugel_id: $('#ugel').val(),
                provincia_id: $('#provincia').val(),
                distrito_id: $('#distrito').val()
            },
            success: function(data) {
                if(data.error) {
                    $('#card1').text('0');
                    $('#card2').text('0');
                    $('#card3').text('0');
                    return;
                }
                $('#card1').text(data.card1);
                $('#card2').text(data.card2);
                $('#card3').text(data.card3);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
            }
        });
    }

    function cargarGraficaAnios() {
        $.ajax({
            url: "{{ route('reportelocalesbeneficiados.reporte') }}",
            type: 'GET',
            data: {
                div: 'grafica_anios',
                anio: $('#anio').val(),
                ugel_id: $('#ugel').val(),
                provincia_id: $('#provincia').val(),
                distrito_id: $('#distrito').val()
            },
            success: function(data) {
                Highcharts.chart('grafica_anios', {
                    chart: { type: 'column' },
                    title: { text: '' },
                    xAxis: { 
                        categories: data.categories,
                        crosshair: true
                    },
                    yAxis: { 
                        min: 0,
                        title: { text: 'Monto (S/)' } 
                    },
                    legend: {
                        enabled: false
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>S/ {point.y:,.2f}</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: 'S/ {point.y:,.2f}'
                            }
                        }
                    },
                    series: [{
                        name: 'Monto Asignado',
                        data: data.data,
                        color: '#317eeb'
                    }]
                });
            }
        });
    }
</script>
@endsection
