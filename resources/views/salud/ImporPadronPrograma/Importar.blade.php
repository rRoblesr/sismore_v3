@extends('layouts.main', ['titlePage' => 'IMPORTAR DATOS - ESTABLECIMIENTO'])
@section('css')
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <style>


    </style>
@endsection
@section('content')
    {{-- <div class="card card-border border border-plomo-0">
        <div
            class="card-header border-success-0 bg-transparent text-white d-flex flex-column flex-md-row justify-content-between align-items-md-center px-2 py-2">
            <h6 class="card-title mb-2 mb-md-0 text-center text-md-left text-wrap">
                Número de establecimientos de salud y centros de apoyo activos por institucion, según categoría a nivel
                regional
            </h6>
            <div class="text-center text-md-right">
                <button type="button" class="btn btn-success-0 btn-xs" onclick="descargarExcel('tabla2')">
                    <i class="fa fa-file-excel"></i> Descargar</button>
            </div>
        </div> --}}
    {{-- <div class="card card-border"> --}}
    <div class="card card-border border border-plomo-0">
        <div
            class="card-header border-success-0 bg-transparent text-white d-flex flex-column flex-md-row justify-content-between align-items-md-center px-2 py-2">
            <h6 class="card-title mb-2 mb-md-0 text-center text-md-left text-wrap">
                HISTORIAL DE IMPORTACIÓN
            </h6>
            <div class="text-center text-md-right">
                <a href="{{ route('imporpadronprograma.exportar.plantilla') }}" class="btn btn-success-0 btn-xs">
                    <i class="ion ion-md-cloud-download"></i> Plantilla</a>

                <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()"><i class="fa fa-redo"></i>
                    Actualizar</button>
                <button type="button" class="btn btn-success btn-xs waves-effect waves-light" data-toggle="modal"
                    data-target=".bs-example-modal-lg" data-backdrop="static" data-keyboard="false"><i
                        class="ion ion-md-cloud-upload"></i> Importar</button>
            </div>
        </div>
        {{-- <div class="card-header border-success-0 bg-transparent pb-0">
            <div class="card-widgets">
                <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()"><i class="fa fa-redo"></i>
                    Actualizar</button>
                <button type="button" class="btn btn-success btn-xs waves-effect waves-light" data-toggle="modal"
                    data-target=".bs-example-modal-lg" data-backdrop="static" data-keyboard="false"><i
                        class="ion ion-md-cloud-upload"></i> Importar</button>
            </div>
            <h3 class="card-title">HISTORIAL DE IMPORTACIÓN</h3>
        </div> --}}
        <div class="card-body p-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="datatable" class="table font-12 table-striped table-bordered dt-responsive nowrap">
                            <thead class="bg-success-0 text-white">
                                <tr>
                                    <th>N°</th>
                                    <th>Usuario</th>
                                    <th>Programa</th>
                                    <th>Servicio</th>
                                    <th>Fecha Registro</th>
                                    <th>Total Reg.</th>
                                    <th>Guardado</th>
                                    <th>Observados</th>
                                    {{-- <th>Estado</th> --}}
                                    <th>Acción</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Importación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form class="cmxform form-horizontal tasi-form upload_file">
                        @csrf
                        <input type="hidden" name="comentario" value="">

                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label class="col-form-label">Programa</label>
                                <select name="programa" id="programa" class="form-control" required>
                                    <option value="">SELECCIONAR</option>
                                    <option value="1">CUNAMAS</option>
                                    <option value="2">JUNTOS</option>
                                </select>
                            </div>

                            <div class="col-lg-6">
                                <label class="col-form-label">Fecha Registro</label>
                                <input type="date" class="form-control" name="fechaActualizacion"
                                    value="{{ date('Y-m-d') }}" placeholder="Ingrese fecha actualización" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-form-label">Archivo</label>
                            <div class="input-group">
                                <input type="file" name="file" id="archivoSubir" class="d-none" accept=".xls,.xlsx"
                                    required>
                                <input type="text" class="form-control" id="nombreArchivo"
                                    placeholder="Selecciona un archivo" readonly>
                                <button class="btn btn-primary" type="button"
                                    onclick="document.getElementById('archivoSubir').click();">
                                    <i class="fas fa-upload"></i> Buscar
                                </button>
                            </div>
                        </div>

                        <div class="form-group pwrapper" style="display:none;">
                            <div class="progress progress_wrapper">
                                <div class="progress-bar progress-bar-striped bg-info progress-bar-animated progress_bar"
                                    role="progressbar" style="width:0%">0%</div>
                            </div>
                        </div>

                        <div class="form-group text-center">
                            <button class="btn btn-primary waves-effect waves-light" type="submit">
                                <i class="ion ion-md-cloud-upload"></i> Cargar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-siagie-matricula" class="modal fade centrarmodal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lista de Matriculados</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="siagie-matricula" class="table table-sm font-10 table-striped table-bordered">
                            <thead class="text-primary">
                                <th>SERVICIO</th>
                                <th>ANIO</th>
                                <th>MES</th>
                                <th>TIPO_DOC_MENOR</th>
                                <th>NUM_DOC_MENOR</th>
                                <th>APE_PAT_MENOR</th>
                                <th>APE_MAT_MENOR</th>
                                <th>NOMBRE_MENOR</th>
                                <th>SEXO_MENOR</th>
                                <th>FEC_NAC_MENOR</th>
                                <th>TELEFONO</th>
                                <th>DIRECCION</th>
                                <th>REFERENCIA</th>
                                <th>UBIGEO_DISTRITO</th>
                                <th>UBIGEO_CCPP</th>
                                <th>LATITUD</th>
                                <th>LONGITUD</th>
                                <th>NUM_DOC_APODERADO</th>
                                <th>APE_PAT_APODERADO</th>
                                <th>APE_MAT_APODERADO</th>
                                <th>NOMBRE_APODERADO</th>
                            </thead>
                            <tbody>
                                <!-- Aquí se insertarán los datos -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-siagie-matricula2" class="modal fade centrarmodal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button class="btn btn-success btn-xs ml-auto" title="DESCARGAR LISTA DE REGISTROS CON ERRORES"
                        onclick="descargarPadron()">
                        <i class="fa fa-download"></i> Descargar
                    </button>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="siagie-matricula2" class="table table-sm font-10 table-striped table-bordered"
                            style="width: 3000px">
                            <thead class="text-white bg-success-0">
                                <th style="width: 100px">SERVICIO</th>
                                <th style="width: 100px">ANIO</th>
                                <th style="width: 100px">MES</th>
                                <th style="width: 100px">TIPO_DOC_MENOR</th>
                                <th style="width: 100px">NUM_DOC_MENOR</th>
                                <th style="width: 100px">APE_PAT_MENOR</th>
                                <th style="width: 100px">APE_MAT_MENOR</th>
                                <th style="width: 100px">NOMBRE_MENOR</th>
                                <th style="width: 100px">SEXO_MENOR</th>
                                <th style="width: 100px">FEC_NAC_MENOR</th>
                                <th style="width: 100px">TELEFONO</th>
                                <th style="width: 300px">DIRECCION</th>
                                <th style="width: 300px">REFERENCIA</th>
                                <th style="width: 100px">UBIGEO_DISTRITO</th>
                                <th style="width: 100px">UBIGEO_CCPP</th>
                                <th style="width: 100px">LATITUD</th>
                                <th style="width: 100px">LONGITUD</th>
                                <th style="width: 100px">NUM_DOC_APODERADO</th>
                                <th style="width: 100px">APE_PAT_APODERADO</th>
                                <th style="width: 100px">APE_MAT_APODERADO</th>
                                <th style="width: 100px">NOMBRE_APODERADO</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var table_principal = '';
        var importacion_id_x;
        $(document).ready(function() {
            $('.upload_file').on('submit', upload);

            table_principal = $('#datatable').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: true,
                language: table_language,
                ajax: "{{ route('imporpadronprograma.listar.importados') }}",
                type: "POST",
                columnDefs: [{
                    className: "text-center",
                    targets: [0, 4, 5, 6, 7]
                }]
            });

        });

        document.getElementById('archivoSubir').addEventListener('change', function() {
            let fileName = this.files.length ? this.files[0].name : "Selecciona un archivo";
            document.getElementById('nombreArchivo').value = fileName;
        });

        function errores(id) {
            $.ajax({
                url: "{{ route('imporpadronprograma.errores', '') }}/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // $('#valor1').text(`${data.total} registros procesados`);
                    // $('#valor2').text(`${data.ok} registros guardados`);
                    // $('#valor3').text(`${data.error} registros tienen errores`);
                    // if (data.error > 0)
                    //     $('#window-error').show();
                    // else
                    //     $('#window-error').hide();

                    // Swal.fire({
                    //     type: "success",
                    //     title: "Good job!",
                    //     text: "You clicked the button!",
                    //     confirmButtonColor: "#348cd4"
                    // });

                    // Swal.fire({
                    //     type: "error",
                    //     title: "Oops...",
                    //     text: "Something went wrong!",
                    //     confirmButtonColor: "#348cd4",
                    //     footer: '<a href="">Why do I have this issue?</a>'
                    // });

                    // Swal.fire({
                    //     type: data.error == 0 ? "success" : "error",
                    //     // title: data.error == 0 ? "Satisfactorio" : "Observados",
                    //     title: `<span class="${data.error == 0 ? "text-primary" : "text-danger"}">${data.error == 0 ? "Satisfactorio" : "Observados"}</span>`,
                    //     html: `
                //         <span style="color: #007bff;">${data.total} registros procesados</span><br>
                //         <span style="color: #28a745;">${data.ok} registros guardados</span><br>
                //         <span style="color: #dc3545;">${data.error} registros tienen errores</span><br>
                //     `,
                    //     confirmButtonColor: "#348cd4",
                    //     confirmButtonText: "Confirmar"
                    // }).then((event) => {
                    //     if (event.isConfirmed) {
                    //         console.log('aqui');
                    //         $('.bs-example-modal-lg').modal('hide');
                    //     }
                    // });

                    Swal.fire({
                        type: data.error == 0 ? "success" : "warning", // Cambié 'type' por 'icon'
                        title: `<span class="${data.error == 0 ? "text-primary" : "text-warning"}">${data.error == 0 ? "Satisfactorio" : "Advertencia"}</span>`,
                        html: `
                                <span style="color: #007bff;">${data.total.toLocaleString('en-US')} Registros Procesados</span><br>
                                <span style="color: #28a745;">${data.ok.toLocaleString('en-US')} Registros Guardados</span><br>
                                <span style="color: #dc3545;">${data.error.toLocaleString('en-US')} Registros Observados</span><br>
                            `,
                        confirmButtonColor: "#348cd4",
                        // confirmButtonText: "Confirmar"
                    }).then((result) => {
                        $('.bs-example-modal-lg').modal('hide');
                    });

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error(
                        'No se puede eliminar este registro por seguridad de su base de datos, Contacte al Administrador del Sistema',
                        'Mensaje');
                }
            });

        }

        function descargarPadron() {

            // Generar la URL con la ruta que necesitas
            var url = "{{ route('imporpadronprograma.exportar.padron', ['importacion_id' => '__importacion_id__']) }}";
            url = url.replace('__importacion_id__', importacion_id_x);

            // Redirigir al usuario a la URL generada
            window.location.href = url;
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
                url: "{{ route('imporpadronprograma.guardar') }}",
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
                    form.trigger('reset');
                    toastr.success('El archivo se ha subido correctamente al servidor.', 'Subida Exitosa');
                    errores(res.importacion_id);
                    setTimeout(() => {
                        wrapper.fadeOut();
                        progress_bar.removeClass('bg-success bg-danger').addClass('bg-info');
                        progress_bar.css('width', '0%');
                        table_principal.ajax.reload();
                    }, 1500);
                } else {console.log('ddddd');
                    progress_bar.css('width', '100%');
                    progress_bar.html(res.msg);
                    form.trigger('reset');
                    //alert(res.msg);

                    Swal.fire({
                        type: "error", // Cambié 'type' por 'icon'
                        title: `<span class=""text-danger">"Advertencia"</span>`,
                        html: `${res.msg}`,
                        confirmButtonColor: "#348cd4",
                        // confirmButtonText: "Confirmar"
                    }).then((result) => {
                        $('.bs-example-modal-lg').modal('hide');
                    });
                }
            }).fail(err => {
                progress_bar.removeClass('bg-success bg-info').addClass('bg-danger');
                //progress_bar.html('Hubo un error!!');
                progress_bar.html('Archivo desconocido');
            }).always(() => {
                $('button', form).attr('disabled', false);
            });
        }

        /* metodo para eliminar una importacion */
        function geteliminar(id) {
            bootbox.confirm("¿Seguro desea eliminar esta importación?", function(result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ route('imporpadronprograma.eliminar', '') }}/" + id,
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                            $('#modal_form').modal('hide');
                            table_principal.ajax.reload();
                            toastr.success('El registro fue eliminado exitosamente.', 'Mensaje');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            toastr.error(
                                'No se puede eliminar este registro por seguridad de su base de datos, Contacte al Administrador del Sistema',
                                'Mensaje');
                        }
                    });
                }
            });
        };

        /* metodo para la vista seleccionada de la importacion */
        function monitor(id) {
            $('#siagie-matricula').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "responsive": true,
                    "autoWidth": false,
                    "scrollX": true,
                    "ordered": true,
                    "destroy": true,
                    "language": table_language,
                    "ajax": {
                        "headers": {
                            'X-CSRF-TOKEN': $('input[name=_token]').val()
                        },
                        "url": "{{ route('imporpadronprograma.listarimportados') }}",
                        "data": {
                            "importacion_id": id
                        },
                        "type": "POST",
                        "dataType": 'JSON',
                    },
                    "columns": [{
                            data: 'servicio',
                            name: 'servicio'
                        },
                        {
                            data: 'anio',
                            name: 'anio'
                        },
                        {
                            data: 'mes',
                            name: 'mes'
                        },
                        {
                            data: 'tipo_doc',
                            name: 'tipo_doc'
                        },
                        {
                            data: 'num_doc_m',
                            name: 'num_doc_m'
                        },
                        {
                            data: 'ape_pat_m',
                            name: 'ape_pat_m'
                        },
                        {
                            data: 'ape_mat_m',
                            name: 'ape_mat_m'
                        },
                        {
                            data: 'nombre_m',
                            name: 'nombre_m'
                        },
                        {
                            data: 'sexo',
                            name: 'sexo'
                        },
                        {
                            data: 'fec_nac_m',
                            name: 'fec_nac_m'
                        },
                        {
                            data: 'telefono',
                            name: 'telefono'
                        },
                        {
                            data: 'direccion',
                            name: 'direccion'
                        },
                        {
                            data: 'referencia',
                            name: 'referencia'
                        },
                        {
                            data: 'ubigeo',
                            name: 'ubigeo'
                        },
                        {
                            data: 'ubigeo_ccpp',
                            name: 'ubigeo_ccpp'
                        },
                        {
                            data: 'latitud',
                            name: 'latitud'
                        },
                        {
                            data: 'longitud',
                            name: 'longitud'
                        },
                        {
                            data: 'num_doc_a',
                            name: 'num_doc_a'
                        },
                        {
                            data: 'ape_pat_a',
                            name: 'ape_pat_a'
                        },
                        {
                            data: 'ape_mat_a',
                            name: 'ape_mat_a'
                        },
                        {
                            data: 'nombre_a',
                            name: 'nombre_a'
                        },
                    ],
                }

            );

            $('#modal-siagie-matricula').on('shown.bs.modal', function() {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
            });

            $('#modal-siagie-matricula').modal('show');
            $('#modal-siagie-matricula .modal-title').text('Importado');
        }

        function monitor2xx(id) {
            importacion_id_x = id;
            $('#siagie-matricula2').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "responsive": true,
                    "autoWidth": false,
                    "scrollX": true,
                    "ordered": true,
                    "destroy": true,
                    "language": table_language,
                    "ajax": {
                        "headers": {
                            'X-CSRF-TOKEN': $('input[name=_token]').val()
                        },
                        "url": "{{ route('imporpadronprograma.listarimportados2') }}",
                        "data": {
                            "importacion_id": id
                        },
                        "type": "POST",
                        "dataType": 'JSON',
                    },
                    "columns": [{
                            data: 'servicio',
                            name: 'servicio'
                        },
                        {
                            data: 'anio',
                            name: 'anio'
                        },
                        {
                            data: 'mes',
                            name: 'mes'
                        },
                        {
                            data: 'tipo_doc_m',
                            name: 'tipo_doc_m'
                        },
                        {
                            data: 'num_doc_m',
                            name: 'num_doc_m'
                        },
                        {
                            data: 'ape_pat_m',
                            name: 'ape_pat_m'
                        },
                        {
                            data: 'ape_mat_m',
                            name: 'ape_mat_m'
                        },
                        {
                            data: 'nombre_m',
                            name: 'nombre_m'
                        },
                        {
                            data: 'sexo_m',
                            name: 'sexo_m'
                        },
                        {
                            data: 'fec_nac_m',
                            name: 'fec_nac_m'
                        },
                        {
                            data: 'telefono',
                            name: 'telefono'
                        },
                        {
                            data: 'direccion',
                            name: 'direccion'
                        },
                        {
                            data: 'referencia',
                            name: 'referencia'
                        },
                        {
                            data: 'ubigeo_distrito',
                            name: 'ubigeo_distrito'
                        },
                        {
                            data: 'ubigeo_ccpp',
                            name: 'ubigeo_ccpp'
                        },
                        {
                            data: 'latitud',
                            name: 'latitud'
                        },
                        {
                            data: 'longitud',
                            name: 'longitud'
                        },
                        {
                            data: 'num_doc_a',
                            name: 'num_doc_a'
                        },
                        {
                            data: 'ape_pat_a',
                            name: 'ape_pat_a'
                        },
                        {
                            data: 'ape_mat_a',
                            name: 'ape_mat_a'
                        },
                        {
                            data: 'nombre_a',
                            name: 'nombre_a'
                        },
                    ],
                }

            );

            // $('#modal-siagie-matricula2').on('shown.bs.modal', function() {
            //     $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
            // });

            $('#modal-siagie-matricula2').modal('show');
            $('#modal-siagie-matricula2 .modal-title').text('Importados Observados');
        }

        function monitor2(id) {
            importacion_id_x = id;
            $('#siagie-matricula2').DataTable({
                "processing": true,
                "serverSide": true,
                // "responsive": true,
                "autoWidth": false,
                // "scrollX": true,
                "ordered": true,
                "destroy": true,
                "language": table_language,
                "ajax": {
                    "headers": {
                        'X-CSRF-TOKEN': $('input[name=_token]').val()
                    },
                    "url": "{{ route('imporpadronprograma.listarimportados2') }}",
                    "data": {
                        "importacion_id": id
                    },
                    "type": "POST",
                    "dataType": 'JSON',
                },
                "columns": [{
                        data: 'servicio',
                        name: 'servicio'
                    },
                    {
                        data: 'anio',
                        name: 'anio'
                    },
                    {
                        data: 'mes',
                        name: 'mes'
                    },
                    {
                        data: 'tipo_doc_m',
                        name: 'tipo_doc_m'
                    },
                    {
                        data: 'num_doc_m',
                        name: 'num_doc_m'
                    },
                    {
                        data: 'ape_pat_m',
                        name: 'ape_pat_m'
                    },
                    {
                        data: 'ape_mat_m',
                        name: 'ape_mat_m'
                    },
                    {
                        data: 'nombre_m',
                        name: 'nombre_m'
                    },
                    {
                        data: 'sexo_m',
                        name: 'sexo_m'
                    },
                    {
                        data: 'fec_nac_m',
                        name: 'fec_nac_m'
                    },
                    {
                        data: 'telefono',
                        name: 'telefono'
                    },
                    {
                        data: 'direccion',
                        name: 'direccion'
                    },
                    {
                        data: 'referencia',
                        name: 'referencia'
                    },
                    {
                        data: 'ubigeo_distrito',
                        name: 'ubigeo_distrito'
                    },
                    {
                        data: 'ubigeo_ccpp',
                        name: 'ubigeo_ccpp'
                    },
                    {
                        data: 'latitud',
                        name: 'latitud'
                    },
                    {
                        data: 'longitud',
                        name: 'longitud'
                    },
                    {
                        data: 'num_doc_a',
                        name: 'num_doc_a'
                    },
                    {
                        data: 'ape_pat_a',
                        name: 'ape_pat_a'
                    },
                    {
                        data: 'ape_mat_a',
                        name: 'ape_mat_a'
                    },
                    {
                        data: 'nombre_a',
                        name: 'nombre_a'
                    },
                ],
            });

            // Mostrar el modal con el título correspondiente
            $('#modal-siagie-matricula2').modal('show');
            $('#modal-siagie-matricula2 .modal-title').text('Importados Observados');
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
