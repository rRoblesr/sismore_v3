@extends('layouts.main', ['titlePage' => 'IMPORTAR DATOS - ' . $fuente->formato])
@section('css')
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"> --}}
@endsection
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-border">
                    <div class="card-header border-success-0 bg-transparent pb-0">
                        <div class="card-widgets">
                            <button type="button" class="btn btn-warning btn-xs" onclick="location.reload()"><i
                                    class="fa fa-redo"></i> Actualizar</button>
                            @if (auth()->user()->id == 49)
                                <button type="button" class="btn btn-secondary btn-xs" onclick="abrirModalEditarImportacionMeta()">
                                    <i class="fa fa-edit"></i> Editar Registro</button>
                            @endif
                            <button type="button" class="btn btn-success btn-xs"
                                onclick="javascript:window.open('https://docs.google.com/spreadsheets/d/1-eFsw4nbmA9xHf9g7MdF4lZavOmOKTl5/edit#gid=1842841396','_blank');"><i
                                    class="fa fa-file-excel"></i>
                                Plantilla</button>
                            <button type="button" class="btn btn-danger btn-xs"
                                onclick="javascript:window.open('https://1drv.ms/x/s!AgffhPHh-Qgo0AEnoULq3wbXGnu-?e=d81hlQ','_blank');"><i
                                    class="mdi mdi-file-pdf-outline"></i>
                                Manual</button>
                            {{-- <button type="button" class="btn btn-primary btn-xs waves-effect waves-light"
                                data-toggle="modal" data-target=".bs-example-modal-lg" data-backdrop="static"
                                data-keyboard="false"><i class="ion ion-md-cloud-upload"></i> Importar</button> --}}
                        </div>
                        <h3 class="card-title">HISTORIAL DE IMPORTACION GASTO PRESUPUESTAL</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap"
                                        style="font-size: 12px">
                                        <thead class="text-primary">
                                            <tr class="bg-success-1 text-white">
                                                <th>N°</th>
                                                <th>Tipo Presupuesto</th>
                                                <th>Fecha Versión</th>
                                                {{-- <th>Fuente</th> --}}
                                                <th>Usuario</th>
                                                <th>Área</th>
                                                <th>Registro</th>
                                                {{-- <th>Comentario</th> --}}
                                                <th>Estado</th>
                                                <th>Accion</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>


                    </div>
                    <!-- card-body -->

                </div>

            </div> <!-- End col -->
        </div> <!-- End row -->


        <!--  Modal content for the above example -->
        <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
            aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myLargeModalLabel">Importar</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <form class="cmxform form-horizontal tasi-form upload_file">
                                    @csrf
                                    <input type="hidden" id="ccomment" name="comentario" value="">
                                    <div class="form-group">
                                        <div class="">
                                            <label class="col-form-label">Fuente de datos</label>
                                            <div class="">
                                                <input type="text" class="form-control" readonly="readonly"
                                                    value="{{ $fuente->nombre }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="">
                                            <label class="col-form-label">Fecha Versión</label>
                                            <div class="">
                                                <input type="datetime-local" class="form-control" name="fechaActualizacion"
                                                    placeholder="Ingrese fecha actualizacion" autofocus required>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="form-group">
                                        <div class="">
                                            <label class="col-form-label">Archivo</label>
                                            <div class="">
                                                <input type="file" name="file" class="form-control" required>
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="form-group">
                                        <label class="col-form-label">Archivo</label>
                                        <div class="input-group">
                                            <input id="file" name="file" class="form-control d-none" type="file"
                                                accept=".xls,.xlsx">
                                            <input id="nfile" name="nfile" class="form-control" type="text"
                                                placeholder="Seleccione Archivo" readonly>
                                            <span class="input-group-append">
                                                <label for="file" class="btn btn-primary btn-file-documento">
                                                    <i class="fas fa-cloud-upload-alt"></i> </label>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group row  mt-0 mb-0">
                                        {{-- <label class="col-md-2 col-form-label"></label> --}}
                                        <div class="col-md-12">
                                            <div class="pwrapper m-0" style="display:none;">
                                                <div class="progress progress_wrapper">
                                                    <div class="progress-bar progress-bar-striped bg-info progress-bar-animated progress_bar"
                                                        role="progressbar" style="width:0%">0%</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <div class="col-lg-12 text-center">
                                            <button class="btn btn-primary waves-effect waves-light" type="submit"><i
                                                    class="ion ion-md-cloud-upload"></i> Importar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.modal -->

        @if (auth()->user()->id == 49)
            <div id="modal-editar-importacion-meta" class="modal fade centrarmodal" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Registro de Importación</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form class="cmxform form-horizontal tasi-form edit_importacion_meta">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="col-form-label">Importación</label>
                                    <select id="importacion_id_meta" name="importacion_id" class="form-control" required></select>
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">Fecha Versión</label>
                                    <input type="datetime-local" id="fechaActualizacion_meta" name="fechaActualizacion"
                                        class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">Fecha Registro</label>
                                    <input type="datetime-local" id="fechaRegistro_meta" name="fechaRegistro"
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">Estado</label>
                                    <select id="estado_meta" name="estado" class="form-control" required>
                                        <option value="PR">PROCESADO</option>
                                        <option value="PE">PENDIENTE</option>
                                        <option value="EL">ELIMINADO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif


        <!-- Bootstrap modal -->
        <div id="modal-siagie-matricula" class="modal fade centrarmodal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="siagie-matricula" class="table table-striped table-bordered"
                                style="font-size:12px">
                                {{-- width:7200px; --}}
                                <thead class="text-primary">
                                    <td>ANIO</td>
                                    <td>MES</td>
                                    <td>COD_TIPO_GOB</td>
                                    <td>TIPO_GOBIERNO</td>
                                    <td>COD_SECTOR</td>
                                    <td>SECTOR</td>
                                    <td>COD_PLIEGO</td>
                                    <td>PLIEGO</td>
                                    <td>COD_UBIGEO</td>
                                    <td>SEC_EJEC</td>
                                    <td>COD_UE</td>
                                    <td>UNIDAD_EJECUTORA</td>
                                    <td>SEC_FUNC</td>
                                    <td>COD_CAT_PRES</td>
                                    <td>CATEGORIA_PRESUPUESTAL</td>
                                    <td>TIPO_PROD_PROY</td>
                                    <td>COD_PROD_PROY</td>
                                    <td>PRODUCTO_PROYECTO</td>
                                    <td>TIPO_ACT_ACC_OBRA</td>
                                    <td>COD_ACT_ACC_OBRA</td>
                                    <td>ACTIVIDAD_ACCION_OBRA</td>
                                    <td>COD_FUN</td>
                                    <td>FUNCION</td>
                                    <td>COD_DIV_FUN</td>
                                    <td>DIVISION_FUNCIONAL</td>
                                    <td>COD_GRU_FUN</td>
                                    <td>GRUPO_FUNCIONAL</td>
                                    <td>META</td>
                                    <td>COD_FINA</td>
                                    <td>FINALIDAD</td>
                                    <td>COD_FUE_FIN</td>
                                    <td>FUENTE_FINANCIAMIENTO</td>
                                    <td>COD_RUB</td>
                                    <td>RUBRO</td>
                                    <td>COD_TIPO_REC</td>
                                    <th>TIPO_RECURSO</th>
                                    <th>COD_CAT_GAS</th>
                                    <th>CATEGORIA_GASTO</th>
                                    <th>COD_TIPO_TRANS</th>
                                    <th>COD_GEN</th>
                                    <th>GENERICA</th>
                                    <th>COD_SUBGEN</th>
                                    <th>SUBGENERICA</th>
                                    <th>COD_SUBGEN_DET</th>
                                    <th>SUBGENERICA_DETALLE</th>
                                    <th>COD_ESP</th>
                                    <th>ESPECIFICA</th>
                                    <th>COD_ESP_DET</th>
                                    <th>ESPECIFICA_DETALLE</th>
                                    <th>PIA</th>
                                    <th>PIM</th>
                                    <th>CERTIFICADO</th>
                                    <th>COMPROMISO_ANUAL</th>
                                    <th>COMPROMISO_MENSUAL</th>
                                    <th>DEVENGADO</th>
                                    <th>GIRADO</th>
                                </thead>
                                <tbody>

                                </tbody>

                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- End Bootstrap modal -->

        <div id="modal-procesar-gastos" class="modal fade centrarmodal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Procesar Importación de Gastos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="procg_importacion_id" value="">
                        <p class="mb-3">
                            Seleccione el proceso que desea ejecutar para la importación seleccionada.
                        </p>
                        <div class="text-center">
                            <div class="d-inline-block mr-2">
                                <button type="button" id="btn-procg-base" class="btn btn-primary btn-sm">
                                    <i class="fa fa-database"></i> Procesar Base de Gastos
                                </button>
                                <button type="button" id="btn-procg-base-ojito"
                                    class="btn btn-outline-primary btn-sm ml-1" title="Ver estado">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                            <div class="d-inline-block">
                                <button type="button" id="btn-procg-cubo" class="btn btn-success btn-sm">
                                    <i class="fa fa-cube"></i> Procesar Cubo de Gastos
                                </button>
                                <button type="button" id="btn-procg-cubo-ojito"
                                    class="btn btn-outline-success btn-sm ml-1" title="Ver estado">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal para actualizar importación -->
        <div id="modal-actualizar" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Actualizar Archivo de Gastos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="cmxform form-horizontal tasi-form update_file">
                            @csrf
                            <input type="hidden" id="upd_importacion_id" name="importacion_id" value="">

                            <div class="alert alert-warning">
                                <i class="fa fa-exclamation-triangle"></i>
                                <strong>Advertencia:</strong> Esta acción eliminará los registros de gastos asociados a esta
                                importación y cargará los nuevos datos del archivo Excel seleccionado.
                            </div>

                            <div class="form-group">
                            <label class="col-form-label">Tipo de Archivo</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipo_archivo" id="type_csv" value="csv" checked onchange="toggleSeparador(true)">
                                    <label class="form-check-label" for="type_csv">CSV (Texto)</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipo_archivo" id="type_xlsx" value="xlsx" onchange="toggleSeparador(false)">
                                    <label class="form-check-label" for="type_xlsx">Excel (XLSX)</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-form-label">Fecha Versión</label>
                            <div class="">{{-- datetime-local --}}
                                <input type="date" class="form-control" name="fechaActualizacion"
                                    id="fechaActualizacion_upd" placeholder="Ingrese fecha actualizacion" required>
                            </div>
                        </div>

                        <div class="form-group" id="div_separador_csv">
                            <label class="col-form-label">Separador CSV</label>
                            <div class="">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="csv_separator" id="sep_comma" value="," checked>
                                    <label class="form-check-label" for="sep_comma">Coma (,)</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="csv_separator" id="sep_semicolon" value=";">
                                    <label class="form-check-label" for="sep_semicolon">Punto y coma (;)</label>
                                </div>
                            </div>
                        </div>

                            <div class="form-group">
                                <label class="col-form-label">Archivo (Excel o CSV)</label>
                                <div class="input-group">
                                    <input id="file_upd" name="file" class="form-control d-none" type="file"
                                        accept=".csv,.txt" required>
                                    <input id="nfile_upd" name="nfile" class="form-control" type="text"
                                        placeholder="Seleccione Archivo" readonly>
                                    <span class="input-group-append">
                                        <label for="file_upd" class="btn btn-primary btn-file-documento">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                        </label>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row mt-0 mb-0">
                                <div class="col-md-12">
                                    <div class="pwrapper_upd m-0" style="display:none;">
                                        <div class="progress progress_wrapper">
                                            <div class="progress-bar progress-bar-striped bg-info progress-bar-animated progress_bar_upd"
                                                role="progressbar" style="width:0%">0%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-12 text-center">
                                    <button class="btn btn-warning waves-effect waves-light" type="submit">
                                        <i class="fa fa-sync"></i> Actualizar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('js')
    <script>
        var table_principal = '';
        var importacionesMetaCache = [];
        $(document).ready(function() {
            $('.upload_file').on('submit', upload);
            $('.update_file').on('submit', uploadUpdate);
            $('.edit_importacion_meta').on('submit', guardarImportacionMeta);

            table_principal = $('#datatable').DataTable({
                responsive: true,
                autoWidth: false,
                order: true,
                language: table_language,
                ajax: "{{ route('imporgastos.listar.importados') }}",
                type: "POST",
            });

            $("#file").on("change", function() {
                var fileName = $(this).val().split("\\").pop();
                $('#nfile').val(fileName);

            });

            $('#btn-procg-base').on('click', function() {
                ejecutarProcesoG('base');
            });

            $('#btn-procg-cubo').on('click', function() {
                ejecutarProcesoG('cubo');
            });
            $('#btn-procg-base-ojito').on('click', function() {
                verificarEstadoG('base');
            });
            $('#btn-procg-cubo-ojito').on('click', function() {
                verificarEstadoG('cubo');
            });

            $("#file_upd").on("change", function() {
                var fileName = $(this).val().split("\\").pop();
                $('#nfile_upd').val(fileName);
            });
        });

        function abrirModalEditarImportacionMeta() {
            $('#modal-editar-importacion-meta').modal('show');
            cargarImportacionesMeta();
        }

        // Abrir modal para un registro específico desde la fila de la tabla
        function abrirModalEditarImportacion(id, fechaIso, estado, registroIso) {
            @if (auth()->user()->id == 49)
                var $sel = $('#importacion_id_meta');
                if (!$sel.find('option[value="' + id + '"]').length) {
                    var label = id + ' - ' + (fechaIso ? fechaIso.replace('T', ' ') : '');
                    $sel.append(new Option(label, id));
                }
                $sel.val(id);
                if (fechaIso) {
                    $('#fechaActualizacion_meta').val(fechaIso.substring(0, 16));
                }
                if (estado) {
                    $('#estado_meta').val(estado);
                }
                if (registroIso) {
                    $('#fechaRegistro_meta').val(registroIso.substring(0, 16));
                }
                $('#modal-editar-importacion-meta').modal('show');
            @endif
        }

        function cargarImportacionesMeta() {
            $.ajax({
                url: "{{ route('imporgastos.importacion.meta.list') }}",
                type: 'GET',
                dataType: 'json',
                success: function(rows) {
                    importacionesMetaCache = Array.isArray(rows) ? rows : [];
                    var $sel = $('#importacion_id_meta');
                    $sel.empty();
                    importacionesMetaCache.forEach(function(r) {
                        var label = r.id + ' - ' + (r.fechaActualizacion || '');
                        $sel.append(new Option(label, r.id));
                    });
                    if (importacionesMetaCache.length) {
                        $sel.val(importacionesMetaCache[0].id).trigger('change');
                    }
                }
            });
        }

        $(document).on('change', '#importacion_id_meta', function() {
            var id = parseInt($(this).val() || '0', 10);
            var row = importacionesMetaCache.find(function(r) {
                return parseInt(r.id, 10) === id;
            });
            if (!row) return;
            if (row.fechaActualizacion) {
                var dt = row.fechaActualizacion.replace(' ', 'T').substring(0, 16);
                $('#fechaActualizacion_meta').val(dt);
            }
            if (row.updated_at) {
                var dr = row.updated_at.replace(' ', 'T').substring(0, 16);
                $('#fechaRegistro_meta').val(dr);
            }
            if (row.estado) {
                $('#estado_meta').val(row.estado);
            }
        });

        function guardarImportacionMeta(e) {
            e.preventDefault();
            var form = $(this);
            var data = new FormData(form.get(0));
            $.ajax({
                type: 'POST',
                url: "{{ route('imporgastos.importacion.meta.update') }}",
                data: data,
                dataType: 'json',
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function() {
                    $('button', form).attr('disabled', true);
                },
                success: function(resp) {
                    if (resp && resp.status === 200) {
                        $('#modal-editar-importacion-meta').modal('hide');
                        table_principal.ajax.reload();
                    } else {
                        alert(resp && resp.msg ? resp.msg : 'No se pudo actualizar.');
                    }
                },
                error: function(xhr) {
                    var msg = 'No se pudo actualizar.';
                    if (xhr.responseJSON && xhr.responseJSON.msg) msg = xhr.responseJSON.msg;
                    alert(msg);
                },
                complete: function() {
                    $('button', form).attr('disabled', false);
                }
            });
        }

        function upload(e) {
            e.preventDefault();
            let form = $(this),
                wrapper = $('.pwrapper'),
                /* wrapper_f = $('.wrapper_files'), */
                progress_bar = $('.progress_bar'),
                data = new FormData(form.get(0));

            progress_bar.removeClass('bg-success bg-danger').addClass('bg-info');
            progress_bar.css('width', '0%');
            progress_bar.html('Preparando...');

            wrapper.fadeIn();

            $.ajax({
                xhr: function() {
                    let xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(e) {
                        if (e.lengthComputable) {
                            let percentComplete = Math.floor((e.loaded / e.total) * 100);
                            progress_bar.css('width', percentComplete + '%');
                            progress_bar.html(percentComplete + '%');
                        }
                    }, false);
                    return xhr;
                },
                type: "POST",
                url: "{{ route('imporgastos.gastos.guardar') }}",
                dataType: "json",
                contentType: false,
                processData: false,
                cache: false,
                data: data,
                beforeSend: () => {
                    $('button', form).attr('disabled', true);
                }
            }).done(res => {
                if (res.status === 200) {
                    progress_bar.removeClass('bg-info').addClass('bg-success');
                    progress_bar.html('Listo!');
                    $('.bs-example-modal-lg').modal('hide');
                    Swal.fire({
                        title: "¡Importación Exitosa!",
                        // text: "You clicked the button!",
                        type: "success",
                        confirmButtonColor: "#348cd4"
                    })
                    form.trigger('reset');

                    setTimeout(() => {
                        wrapper.fadeOut();
                        progress_bar.removeClass('bg-success bg-danger').addClass('bg-info');
                        progress_bar.css('width', '0%');
                        table_principal.ajax.reload();
                    }, 1500);
                } else {
                    progress_bar.css('width', '100%');
                    progress_bar.html(res.msg);
                    form.trigger('reset');
                    //alert(res.msg);
                }
            }).fail(err => {
                progress_bar.removeClass('bg-success bg-info').addClass('bg-danger');
                //progress_bar.html('Hubo un error!!');
                progress_bar.html('Archivo desconocido');
            }).always(() => {
                $('button', form).attr('disabled', false);
            });
        }

        function verificarEstadoG(tipo) {
            var importacion_id = $('#procg_importacion_id').val();
            if (!importacion_id) return;

            var url = tipo === 'base' ?
                "{{ route('imporgastos.verificar.base', 'id_placeholder') }}" :
                "{{ route('imporgastos.verificar.cubo', 'id_placeholder') }}";
            url = url.replace('id_placeholder', importacion_id);

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'JSON',
                success: function(res) {
                    if (tipo === 'base') {
                        var html = `
                            <div class="text-left">
                                <p class="mb-1"><strong>Resumen del proceso:</strong></p>
                                <p class="mb-1">Base generada: ${res.base ? 'Sí' : 'No'}</p>
                                <p class="mb-1">Registros de detalle: ${res.detalle}</p>
                                <p class="mb-1">Año de referencia: ${res.anio ?? '-'}</p>
                            </div>`;
                        Swal.fire({
                            title: 'Estado del proceso de Base de Gastos',
                            html: html,
                            type: 'info',
                            showCancelButton: true,
                            confirmButtonText: 'Descargar registros',
                            cancelButtonText: 'Cerrar',
                            confirmButtonColor: '#3085d6'
                        }).then((result) => {
                            if (result.value && res.detalle > 0) {
                                var urlDesc =
                                    "{{ route('imporgastos.descargar.base', 'id_placeholder') }}";
                                urlDesc = urlDesc.replace('id_placeholder', importacion_id);
                                window.open(urlDesc, '_blank');
                            }
                        });
                    } else {
                        var filas = '';
                        if (res.anios && res.anios.length) {
                            res.anios.forEach(function(x) {
                                filas +=
                                    `<tr><td>${x.anio}</td><td class="text-right">${x.registros}</td></tr>`;
                            });
                        }
                        var html = `
                            <div class="text-left">
                                <p class="mb-2"><strong>Total de registros consolidados:</strong> ${res.total}</p>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead><tr><th>Año</th><th class="text-right">Registros</th></tr></thead>
                                        <tbody>${filas}</tbody>
                                    </table>
                                </div>
                            </div>`;
                        Swal.fire({
                            title: 'Estado del proceso de Cubo de Gastos',
                            html: html,
                            type: 'info',
                            showCancelButton: true,
                            confirmButtonText: 'Descargar registros',
                            cancelButtonText: 'Cerrar',
                            confirmButtonColor: '#28a745'
                        }).then((result) => {
                            if (result.value && res.total > 0) {
                                var urlDesc =
                                    "{{ route('imporgastos.descargar.cubo', 'id_placeholder') }}";
                                urlDesc = urlDesc.replace('id_placeholder', importacion_id);
                                window.open(urlDesc, '_blank');
                            }
                        });
                    }
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo obtener el estado.', 'error');
                }
            });
        }

        function abrirProcesosG(id) {
            $('#procg_importacion_id').val(id);
            $('#modal-procesar-gastos').modal('show');
        }

        function ejecutarProcesoG(tipo) {
            var importacion_id = $('#procg_importacion_id').val();
            if (!importacion_id) return;

            var url = tipo === 'base' ?
                "{{ route('imporgastos.procesar.base', 'id_placeholder') }}" :
                "{{ route('imporgastos.procesar.cubo', 'id_placeholder') }}";
            url = url.replace('id_placeholder', importacion_id);

            Swal.fire({
                title: '¿Está seguro?',
                text: tipo === 'base' ?
                    'Se procesará la base de gastos (normalización).' : 'Se procesará el cubo de gastos.',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, procesar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (!result.value) return;

                Swal.fire({
                    title: 'Procesando...',
                    text: 'Por favor espere.',
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    }
                });

                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'JSON',
                    headers: {
                        'X-CSRF-TOKEN': $('input[name=_token]').val()
                    },
                    success: function(res) {
                        Swal.fire(
                            res.status ? '¡Éxito!' : 'Error',
                            res.msg,
                            res.status ? 'success' : 'error'
                        );
                        if (res.status) {
                            table_principal.ajax.reload();
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Error',
                            'Ocurrió un error al procesar la importación.',
                            'error'
                        );
                    }
                });
            });
        }

        function geteliminar(id) {
            bootbox.confirm("Seguro desea Eliminar este IMPORTACION?", function(result) {
                if (result === true) {
                    var url = "{{ route('imporgastos.eliminar', ':id') }}";
                    url = url.replace(':id', id);
                    $.ajax({
                        url: url,
                        type: "DELETE",
                        dataType: "JSON",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        beforeSend: function() {
                            $('#eliminar' + id).html(
                                '<span><i class="fa fa-spinner fa-spin"></i></span>');
                        },
                        success: function(data) {
                            table_principal.ajax.reload();
                            toastr.success('El registro fue eliminado exitosamente.', 'Mensaje');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $('#eliminar' + id).html('<span><i class="fa fa-trash"></i></span>');
                            var msg = jqXHR.responseJSON && jqXHR.responseJSON.message ? jqXHR
                                .responseJSON.message :
                                'No se puede eliminar este registro por seguridad de su base de datos, Contacte al Administrador del Sistema';
                            toastr.error(msg, 'Mensaje');
                        }
                    });
                }
            });
        };

        function monitor(id) {
            var url = "{{ route('imporgastos.listarimportados', 55555) }}";
            url = url.replace('55555', id);
            $('#siagie-matricula').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "responsive": false,
                    "autoWidth": false,
                    "order": true,
                    "destroy": true,
                    "language": table_language,
                    "ajax": {
                        "headers": {
                            'X-CSRF-TOKEN': $('input[name=_token]').val()
                        },
                        "url": url,
                        "type": "POST",
                        "dataType": 'JSON',
                    },
                    "columns": [{
                            data: 'anio',
                            name: 'anio'
                        },
                        {
                            data: 'mes',
                            name: 'mes'
                        },
                        {
                            data: 'cod_niv_gob',
                            name: 'cod_niv_gob'
                        },
                        {
                            data: 'nivel_gobierno',
                            name: 'nivel_gobierno'
                        },
                        {
                            data: 'cod_sector',
                            name: 'cod_sector'
                        },
                        {
                            data: 'sector',
                            name: 'sector'
                        },
                        {
                            data: 'cod_pliego',
                            name: 'cod_pliego'
                        },
                        {
                            data: 'pliego',
                            name: 'pliego'
                        },
                        {
                            data: 'cod_ubigeo',
                            name: 'cod_ubigeo'
                        },
                        {
                            data: 'sec_ejec',
                            name: 'sec_ejec'
                        },
                        {
                            data: 'cod_ue',
                            name: 'cod_ue'
                        },
                        {
                            data: 'unidad_ejecutora',
                            name: 'unidad_ejecutora'
                        },
                        {
                            data: 'sec_func',
                            name: 'sec_func'
                        },
                        {
                            data: 'cod_cat_pres',
                            name: 'cod_cat_pres'
                        },
                        {
                            data: 'categoria_presupuestal',
                            name: 'categoria_presupuestal'
                        },
                        {
                            data: 'tipo_prod_proy',
                            name: 'tipo_prod_proy'
                        },
                        {
                            data: 'cod_prod_proy',
                            name: 'cod_prod_proy'
                        },
                        {
                            data: 'producto_proyecto',
                            name: 'producto_proyecto'
                        },
                        {
                            data: 'tipo_act_acc_obra',
                            name: 'tipo_act_acc_obra'
                        },
                        {
                            data: 'cod_act_acc_obra',
                            name: 'cod_act_acc_obra'
                        },
                        {
                            data: 'actividad_accion_obra',
                            name: 'actividad_accion_obra'
                        },
                        {
                            data: 'cod_fun',
                            name: 'cod_fun'
                        },
                        {
                            data: 'funcion',
                            name: 'funcion'
                        },
                        {
                            data: 'cod_div_fun',
                            name: 'cod_div_fun'
                        },
                        {
                            data: 'division_funcional',
                            name: 'division_funcional'
                        },
                        {
                            data: 'cod_gru_fun',
                            name: 'cod_gru_fun'
                        },
                        {
                            data: 'grupo_funcional',
                            name: 'grupo_funcional'
                        },
                        {
                            data: 'meta',
                            name: 'meta'
                        },
                        {
                            data: 'cod_fina',
                            name: 'cod_fina'
                        },
                        {
                            data: 'finalidad',
                            name: 'finalidad'
                        },
                        {
                            data: 'cod_fue_fin',
                            name: 'cod_fue_fin'
                        },
                        {
                            data: 'fuente_financiamiento',
                            name: 'fuente_financiamiento'
                        },
                        {
                            data: 'cod_rub',
                            name: 'cod_rub'
                        },
                        {
                            data: 'rubro',
                            name: 'rubro'
                        },
                        {
                            data: 'cod_tipo_rec',
                            name: 'cod_tipo_rec'
                        },
                        {
                            data: 'tipo_recurso',
                            name: 'tipo_recurso'
                        },
                        {
                            data: 'cod_cat_gas',
                            name: 'cod_cat_gas'
                        },
                        {
                            data: 'categoria_gasto',
                            name: 'categoria_gasto'
                        },
                        {
                            data: 'cod_tipo_trans',
                            name: 'cod_tipo_trans'
                        },
                        {
                            data: 'cod_gen',
                            name: 'cod_gen'
                        },
                        {
                            data: 'generica',
                            name: 'generica'
                        },
                        {
                            data: 'cod_subgen',
                            name: 'cod_subgen'
                        },
                        {
                            data: 'subgenerica',
                            name: 'subgenerica'
                        },
                        {
                            data: 'cod_subgen_det',
                            name: 'cod_subgen_det'
                        },
                        {
                            data: 'subgenerica_detalle',
                            name: 'subgenerica_detalle'
                        },
                        {
                            data: 'cod_esp',
                            name: 'cod_esp'
                        },
                        {
                            data: 'especifica',
                            name: 'especifica'
                        },
                        {
                            data: 'cod_esp_det',
                            name: 'cod_esp_det'
                        },
                        {
                            data: 'especifica_detalle',
                            name: 'especifica_detalle'
                        },
                        {
                            data: 'pia',
                            name: 'pia'
                        },
                        {
                            data: 'pim',
                            name: 'pim'
                        },
                        {
                            data: 'certificado',
                            name: 'certificado'
                        },
                        {
                            data: 'compromiso_anual',
                            name: 'compromiso_anual'
                        },
                        {
                            data: 'compromiso_mensual',
                            name: 'compromiso_mensual'
                        },
                        {
                            data: 'devengado',
                            name: 'devengado'
                        },
                        {
                            data: 'girado',
                            name: 'girado'
                        },

                    ],
                }

            );

            $('#modal-siagie-matricula').modal('show');
            $('#modal-siagie-matricula .modal-title').text('Importado');
        }

        function toggleSeparador(isCsv) {
            if (isCsv) {
                $('#div_separador_csv').show();
                $('#file_upd').attr('accept', '.csv,.txt');
                // Select default comma
                $('#sep_comma').prop('checked', true);
            } else {
                $('#div_separador_csv').hide();
                $('#file_upd').attr('accept', '.xls,.xlsx');
            }
        }

        $(document).ready(function() {
            toggleSeparador(true); // Default to CSV
        });

        function abrirModalActualizar(id, fecha) {
            $('#upd_importacion_id').val(id);
            if (fecha) {
                $('#fechaActualizacion_upd').val(fecha);
            }
            $('#modal-actualizar').modal('show');
        }

        function uploadUpdate(e) {
            e.preventDefault();
            let form = $(this),
                wrapper = $('.pwrapper_upd'),
                progress_bar = $('.progress_bar_upd'),
                data = new FormData(form.get(0));

            progress_bar.removeClass('bg-success bg-danger').addClass('bg-info');
            progress_bar.css('width', '0%');
            progress_bar.html('Preparando...');
            wrapper.fadeIn();

            $.ajax({
                xhr: function() {
                    let xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(e) {
                        if (e.lengthComputable) {
                            let percentComplete = Math.floor((e.loaded / e.total) * 100);
                            progress_bar.css('width', percentComplete + '%');
                            progress_bar.html(percentComplete + '%');
                        }
                    }, false);
                    return xhr;
                },
                type: "POST",
                url: "{{ route('imporgastos.gastos.actualizar') }}",
                dataType: "json",
                contentType: false,
                processData: false,
                cache: false,
                data: data,
                beforeSend: () => {
                    $('button', form).attr('disabled', true);
                }
            }).done(res => {
                if (res.status === 200) {
                    progress_bar.removeClass('bg-info').addClass('bg-success');
                    progress_bar.html('¡Actualizado!');
                    $('#modal-actualizar').modal('hide');
                    Swal.fire({
                        title: "¡Actualización Exitosa!",
                        type: "success",
                        confirmButtonColor: "#348cd4"
                    });
                    form.trigger('reset');
                    setTimeout(() => {
                        wrapper.fadeOut();
                        progress_bar.removeClass('bg-success bg-danger').addClass('bg-info');
                        progress_bar.css('width', '0%');
                        table_principal.ajax.reload();
                    }, 1500);
                } else {
                    progress_bar.css('width', '100%');
                    progress_bar.html(res.msg);
                    alert(res.msg);
                }
            }).fail(err => {
                progress_bar.removeClass('bg-success bg-info').addClass('bg-danger');
                progress_bar.html('Error en la actualización');
            }).always(() => {
                $('button', form).attr('disabled', false);
            });
        }
    </script>
    <script src="{{ asset('/') }}public/assets/libs/jquery-validation/jquery.validate.min.js"></script>
    <!-- Validation init js-->
    <script src="{{ asset('/') }}public/assets/js/pages/form-validation.init.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>
@endsection
