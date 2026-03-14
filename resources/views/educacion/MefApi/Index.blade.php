@extends('layouts.main', ['titlePage' => 'CONSULTA API MEF - DATOS ABIERTOS'])

@section('css')
<link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<style>
    .query-example {
        background-color: #f8f9fa;
        border-left: 4px solid #007bff;
        padding: 10px;
        margin: 10px 0;
        font-family: 'Courier New', monospace;
        font-size: 12px;
    }
    .btn-copy {
        font-size: 11px;
        padding: 2px 8px;
    }
</style>
@endsection

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-border">
                <div class="card-header bg-transparent pb-0">
                    <h3 class="card-title">CONSULTA API MEF - DATOS ABIERTOS</h3>
                </div>
                <div class="card-body">
                    <!-- Ejemplos de consultas -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h5>Ejemplos de Consultas:</h5>
                            <div class="alert alert-info">
                                <strong>⚠️ Puedes usar:</strong>
                                <ul class="mb-0">
                                    <li><strong>Consultas SQL</strong> con resource_id (UUID)</li>
                                    <li><strong>URLs completas</strong> de la API del MEF</li>
                                </ul>
                                Encuentra datasets en: <a href="https://datosabiertos.mef.gob.pe/" target="_blank">https://datosabiertos.mef.gob.pe/</a>
                            </div>
                            
                            <h6>Opción 1: Consultas SQL</h6>
                            <div class="query-example">
                                <strong>Ejemplo SQL 1: Ejecución Presupuestal:</strong><br>
                                SELECT * FROM "96ac0c23-abbc-4e28-b9f1-7977826f534b" WHERE "SECTOR_NOMBRE" LIKE 'GOBIERNOS REGIONALES' LIMIT 10
                                <button class="btn btn-sm btn-secondary btn-copy float-right" onclick="copyQuery(this)">Copiar</button>
                            </div>
                            <div class="query-example">
                                <strong>Ejemplo SQL 2: Consulta Simple:</strong><br>
                                SELECT * FROM "96ac0c23-abbc-4e28-b9f1-7977826f534b" LIMIT 10
                                <button class="btn btn-sm btn-secondary btn-copy float-right" onclick="copyQuery(this)">Copiar</button>
                            </div>
                            
                            <h6 class="mt-3">Opción 2: URLs Directas</h6>
                            <div class="query-example">
                                <strong>Ejemplo URL 1: Con filtros:</strong><br>
                                https://api.datosabiertos.mef.gob.pe/DatosAbiertos/v1/datastore_search?resource_id=534994e6-2422-4e3e-97aa-bb56acb80c97&DEPARTAMENTO_EJECUTORA=25&limit=100
                                <button class="btn btn-sm btn-secondary btn-copy float-right" onclick="copyQuery(this)">Copiar</button>
                            </div>
                            <div class="query-example">
                                <strong>Ejemplo URL 2: Sin filtros:</strong><br>
                                https://api.datosabiertos.mef.gob.pe/DatosAbiertos/v1/datastore_search?resource_id=534994e6-2422-4e3e-97aa-bb56acb80c97&limit=50
                                <button class="btn btn-sm btn-secondary btn-copy float-right" onclick="copyQuery(this)">Copiar</button>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario de consulta -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="sql_query">Consulta SQL:</label>
                                <textarea class="form-control" id="sql_query" rows="4" placeholder='Ejemplo: SELECT * FROM "locales-educativos-beneficiarios" LIMIT 10'></textarea>
                                <small class="form-text text-muted">
                                    Ingrese una consulta SQL válida. El nombre de la tabla debe estar entre comillas dobles.
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary" onclick="consultarAPI()">
                                <i class="fa fa-search"></i> Consultar
                            </button>
                            <button type="button" class="btn btn-info" onclick="verEnModal()" id="btn_modal" disabled>
                                <i class="fa fa-eye"></i> Ver en Modal
                            </button>
                            <button type="button" class="btn btn-success" onclick="exportarExcel()" id="btn_exportar" disabled>
                                <i class="fa fa-file-excel"></i> Exportar a Excel
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="limpiar()">
                                <i class="fa fa-eraser"></i> Limpiar
                            </button>
                        </div>
                    </div>

                    <!-- Área de resultados -->
                    <div class="row">
                        <div class="col-md-12">
                            <div id="loading" style="display:none;" class="text-center p-3">
                                <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                                <p>Consultando API...</p>
                            </div>
                            <div id="error_message" style="display:none;" class="alert alert-danger"></div>
                            <div id="success_message" style="display:none;" class="alert alert-success"></div>
                            <div id="results_container" style="display:none;">
                                <h5>Resultados: <span id="total_records"></span> registros</h5>
                                <div class="table-responsive">
                                    <table id="tabla_resultados" class="table table-striped table-bordered" style="width:100%; font-size:11px;">
                                        <thead class="bg-primary text-white">
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
    </div>

    <!-- Modal para ver datos -->
    <div class="modal fade" id="modalDatos" tabindex="-1" role="dialog" aria-labelledby="modalDatosLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalDatosLabel">
                        <i class="fa fa-table"></i> Datos de la API MEF
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <strong>Total de registros:</strong> <span id="modal_total_records" class="badge badge-primary">0</span>
                    </div>
                    <div class="table-responsive">
                        <table id="tabla_modal" class="table table-striped table-bordered table-sm" style="width:100%; font-size:11px;">
                            <thead class="bg-dark text-white">
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="exportarExcel()">
                        <i class="fa fa-file-excel"></i> Exportar a Excel
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>

<script>
let currentData = null;
let dataTable = null;

function copyQuery(button) {
    const queryDiv = $(button).parent();
    const queryText = queryDiv.contents().filter(function() {
        return this.nodeType === 3;
    }).text().trim();
    
    $('#sql_query').val(queryText);
    
    // Feedback visual
    $(button).text('¡Copiado!').removeClass('btn-secondary').addClass('btn-success');
    setTimeout(function() {
        $(button).text('Copiar').removeClass('btn-success').addClass('btn-secondary');
    }, 2000);
}

function consultarAPI() {
    const sql = $('#sql_query').val().trim();
    
    if (!sql) {
        showError('Por favor ingrese una consulta SQL');
        return;
    }

    $('#loading').show();
    $('#error_message').hide();
    $('#success_message').hide();
    $('#results_container').hide();
    $('#btn_exportar').prop('disabled', true);
    $('#btn_modal').prop('disabled', true);

    $.ajax({
        url: "{{ route('mefapi.consultar') }}",
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            sql: sql
        },
        success: function(response) {
            $('#loading').hide();
            
            if (response.success === false || response.error) {
                showError(response.error || response.message || 'Error desconocido');
                return;
            }

            // Detectar dónde están los registros (estructura standard vs variaciones)
            let records = null;
            if (response.result && response.result.records) {
                records = response.result.records;
            } else if (response.records) {
                records = response.records;
            }

            if (!records) {
                showError('No se encontraron datos en la respuesta');
                return;
            }

            currentData = records;
            mostrarResultados(currentData);
            $('#btn_exportar').prop('disabled', false);
            $('#btn_modal').prop('disabled', false);
            showSuccess('Consulta exitosa. ' + currentData.length + ' registros encontrados.');
        },
        error: function(xhr) {
            $('#loading').hide();
            let errorMsg = 'Error al consultar la API';
            
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMsg = xhr.responseJSON.error;
                if (xhr.responseJSON.message) {
                    errorMsg += ': ' + xhr.responseJSON.message;
                }
            }
            
            showError(errorMsg);
        }
    });
}

function mostrarResultados(data) {
    if (!data || data.length === 0) {
        showError('No se encontraron registros');
        return;
    }

    // Destruir DataTable anterior si existe
    if (dataTable) {
        dataTable.destroy();
    }

    // Obtener columnas
    const columns = Object.keys(data[0]);
    
    // Crear encabezados
    let thead = '<tr>';
    columns.forEach(col => {
        thead += '<th>' + col.toUpperCase() + '</th>';
    });
    thead += '</tr>';
    $('#tabla_resultados thead').html(thead);

    // Crear filas
    let tbody = '';
    data.forEach(row => {
        tbody += '<tr>';
        columns.forEach(col => {
            tbody += '<td>' + (row[col] !== null ? row[col] : '') + '</td>';
        });
        tbody += '</tr>';
    });
    $('#tabla_resultados tbody').html(tbody);

    $('#total_records').text(data.length);
    $('#results_container').show();

    // Inicializar DataTable
    dataTable = $('#tabla_resultados').DataTable({
        "language": table_language,
        "pageLength": 25,
        "ordering": true,
        "searching": true
    });
}

function exportarExcel() {
    const sql = $('#sql_query').val().trim();
    
    if (!sql) {
        showError('Por favor ingrese una consulta SQL');
        return;
    }

    showSuccess('Generando archivo Excel...');
    
    // Crear un formulario temporal para enviar la petición
    const form = $('<form>', {
        'method': 'POST',
        'action': "{{ route('mefapi.exportar') }}"
    });
    
    form.append($('<input>', {
        'type': 'hidden',
        'name': '_token',
        'value': '{{ csrf_token() }}'
    }));
    
    form.append($('<input>', {
        'type': 'hidden',
        'name': 'sql',
        'value': sql
    }));
    
    $('body').append(form);
    form.submit();
    form.remove();
}

function verEnModal() {
    if (!currentData || currentData.length === 0) {
        showError('No hay datos para mostrar');
        return;
    }

    // Definir configuración de lenguaje de forma segura
    let dtLanguage = (typeof table_language !== 'undefined') ? table_language : {
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    };

    // Destruir DataTable del modal si existe
    if ($.fn.DataTable.isDataTable('#tabla_modal')) {
        $('#tabla_modal').DataTable().destroy();
    }
    $('#tabla_modal tbody').empty();
    $('#tabla_modal thead').empty();

    // Obtener columnas
    const columns = Object.keys(currentData[0]);
    
    // Crear encabezados
    let thead = '<tr>';
    columns.forEach(col => {
        thead += '<th>' + col.toUpperCase() + '</th>';
    });
    thead += '</tr>';
    $('#tabla_modal thead').html(thead);

    // Crear filas
    let tbody = '';
    currentData.forEach(row => {
        tbody += '<tr>';
        columns.forEach(col => {
            tbody += '<td>' + (row[col] !== null ? row[col] : '') + '</td>';
        });
        tbody += '</tr>';
    });
    $('#tabla_modal tbody').html(tbody);

    $('#modal_total_records').text(currentData.length);

    // Mostrar el modal primero
    $('#modalDatos').modal('show');
    
    // Inicializar DataTable después de que el modal sea visible para evitar problemas de ancho
    $('#modalDatos').one('shown.bs.modal', function () {
        if (!$.fn.DataTable.isDataTable('#tabla_modal')) {
            $('#tabla_modal').DataTable({
                "language": dtLanguage,
                "pageLength": 10,
                "ordering": true,
                "searching": true,
                "scrollX": true,
                "autoWidth": false
            });
        } else {
             $('#tabla_modal').DataTable().columns.adjust().draw();
        }
    });
}

function limpiar() {
    $('#sql_query').val('');
    $('#results_container').hide();
    $('#error_message').hide();
    $('#success_message').hide();
    $('#btn_exportar').prop('disabled', true);
    $('#btn_modal').prop('disabled', true);
    currentData = null;
    
    if (dataTable) {
        dataTable.destroy();
        dataTable = null;
    }
}

function showError(message) {
    $('#error_message').html('<i class="fa fa-exclamation-triangle"></i> ' + message).show();
    setTimeout(function() {
        $('#error_message').fadeOut();
    }, 5000);
}

function showSuccess(message) {
    $('#success_message').html('<i class="fa fa-check-circle"></i> ' + message).show();
    setTimeout(function() {
        $('#success_message').fadeOut();
    }, 3000);
}
</script>
@endsection
