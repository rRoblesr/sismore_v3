@extends('layouts.main', ['titlePage' => 'IMPORTAR DATOS - PADRON NOMINAL'])
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"> --}}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-border">
                <div class="card-header border-success-0 bg-transparent pb-0">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()"><i
                                class="fa fa-redo"></i> Actualizar</button>
                        <button type="button" class="btn btn-success btn-xs waves-effect waves-light" data-toggle="modal"
                            data-target=".bs-example-modal-lg" data-backdrop="static" data-keyboard="false"><i
                                class="ion ion-md-cloud-upload"></i> Importar</button>
                    </div>
                    <h3 class="card-title">HISTORIAL DE IMPORTACIÓN</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap"
                                    style="font-size: 12px">
                                    <thead class="text-primary">
                                        <tr>
                                            <th>N°</th>
                                            <th>Versión</th>
                                            <th>Fuente</th>
                                            <th>Usuario</th>
                                            <th>Área</th>
                                            <th>Registro</th>
                                            <th>Nro Registro</th>
                                            <th>Estado</th>
                                            <th>Acción</th>
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
                                                value="PADRÓN NOMINAL">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="">
                                        <label class="col-form-label">Fecha Versión</label>
                                        <div class="">
                                            <input type="date" class="form-control" name="fechaActualizacion"
                                                placeholder="Ingrese fecha actualizacion" autofocus required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="">
                                        <label class="col-form-label">Archivo</label>
                                        <div class="">
                                            <input type="file" name="file" class="form-control" required>
                                        </div>
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
                                                class="ion ion-md-cloud-upload"></i> Guardar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.modal -->

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
                        <table id="siagie-matricula" class="table table-striped table-bordered" style="font-size:12px">
                            {{-- width:7200px; --}}
                            <thead class="text-primary">
                                <th>PADRON</th>
                                <th>CNV</th>
                                <th>CUI</th>
                                <th>DNI</th>
                                <th>NUM_DOC</th>
                                <th>TIPO_DOC</th>
                                <th>APELLIDO_PATERNO</th>
                                <th>APELLIDO_MATERNO</th>
                                <th>NOMBRE</th>
                                <th>GENERO</th>
                                <th>FECHA_NACIMIENTO</th>
                                <th>DIRECCION</th>
                                <th>UBIGEO</th>
                                <th>CENTRO_POBLADO</th>
                                <th>AREA_CCPP</th>
                                <th>CUI_NACIMIENTO</th>
                                <th>CUI_ATENCION</th>
                                <th>SEGURO</th>
                                <th>PROGRAMA_SOCIAL</th>
                                <th>VISITA</th>
                                <th>MENOR_ENCONTRADO</th>
                                <th>CODIGO_IE</th>
                                <th>NOMBRE_IE</th>
                                <th>TIPO_DOC_MADRE</th>
                                <th>NUM_DOC_MADRE</th>
                                <th>APELLIDO_PATERNO_MADRE</th>
                                <th>APELLIDO_MATERNO_MADRE</th>
                                <th>NOMBRES_MADRE</th>
                                <th>CELULAR_MADRE</th>
                                <th>GRADO_INSTRUCCION</th>
                                <th>LENGUA_MADRE</th>
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

    <!-- Modal -->
    <div id="modalProceso" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalProcesoLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalProcesoLabel">Proceso en Ejecución</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Presiona el botón para iniciar el proceso.</p>

                    <!-- Barra de Progreso -->
                    <div class="progress">
                        <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                            role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
                            aria-valuemax="100">
                        </div>
                    </div>

                    <p class="mt-2 text-center"><strong id="progressText">0%</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnIniciar" class="btn btn-primary" onclick="iniciarProceso() ">Iniciar
                        Proceso</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var table_principal = '';
        $(document).ready(function() {
            $('.upload_file').on('submit', upload);

            table_principal = $('#datatable').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: true,
                language: table_language,
                ajax: "{{ route('edu.imporpadronnominal.listar.importados') }}",
                type: "POST",
            });
        });

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
                url: "{{ route('edu.imporpadronnominal.guardar') }}",
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

        /* metodo para eliminar una importacion */
        function geteliminar(id) {
            bootbox.confirm("¿Seguro desea eliminar esta importación?", function(result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ route('edu.imporpadronnominal.eliminar', '') }}/" + id,
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
                processing: true,
                serverSide: true,
                responsive: false,
                autoWidth: false,
                ordered: true,
                destroy: true, // Este permite reconfigurar la tabla si ya existe
                language: table_language,
                ajax: {
                    headers: {
                        'X-CSRF-TOKEN': $('input[name=_token]').val()
                    },
                    url: "{{ route('edu.imporpadronnominal.listarimportados','importacion_id') }}".replace('importacion_id',id),
                    type: "POST",
                    
                },
                columns: [{
                        data: 'padron',
                        name: 'padron'
                    },
                    {
                        data: 'cnv',
                        name: 'cnv'
                    },
                    {
                        data: 'cui',
                        name: 'cui'
                    },
                    {
                        data: 'dni',
                        name: 'dni'
                    },
                    {
                        data: 'num_doc',
                        name: 'num_doc'
                    },
                    {
                        data: 'tipo_doc',
                        name: 'tipo_doc'
                    },
                    {
                        data: 'apellido_paterno',
                        name: 'apellido_paterno'
                    },
                    {
                        data: 'apellido_materno',
                        name: 'apellido_materno'
                    },
                    {
                        data: 'nombre',
                        name: 'nombre'
                    },
                    {
                        data: 'genero',
                        name: 'genero'
                    },
                    {
                        data: 'fecha_nacimiento',
                        name: 'fecha_nacimiento'
                    },
                    {
                        data: 'direccion',
                        name: 'direccion'
                    },
                    {
                        data: 'ubigeo',
                        name: 'ubigeo'
                    },
                    {
                        data: 'centro_poblado',
                        name: 'centro_poblado'
                    },
                    {
                        data: 'area_ccpp',
                        name: 'area_ccpp'
                    },
                    {
                        data: 'cui_nacimiento',
                        name: 'cui_nacimiento'
                    },
                    {
                        data: 'cui_atencion',
                        name: 'cui_atencion'
                    },
                    {
                        data: 'seguro',
                        name: 'seguro'
                    },
                    {
                        data: 'programa_social',
                        name: 'programa_social'
                    },
                    {
                        data: 'visita',
                        name: 'visita'
                    },
                    {
                        data: 'menor_encontrado',
                        name: 'menor_encontrado'
                    },
                    {
                        data: 'codigo_ie',
                        name: 'codigo_ie'
                    },
                    {
                        data: 'nombre_ie',
                        name: 'nombre_ie'
                    },
                    {
                        data: 'tipo_doc_madre',
                        name: 'tipo_doc_madre'
                    },
                    {
                        data: 'num_doc_madre',
                        name: 'num_doc_madre'
                    },
                    {
                        data: 'apellido_paterno_madre',
                        name: 'apellido_paterno_madre'
                    },
                    {
                        data: 'apellido_materno_madre',
                        name: 'apellido_materno_madre'
                    },
                    {
                        data: 'nombres_madre',
                        name: 'nombres_madre'
                    },
                    {
                        data: 'celular_madre',
                        name: 'celular_madre'
                    },
                    {
                        data: 'grado_instruccion',
                        name: 'grado_instruccion'
                    },
                    {
                        data: 'lengua_madre',
                        name: 'lengua_madre'
                    }
                ]
            });

            $('#modal-siagie-matricula').modal('show');
            $('#modal-siagie-matricula .modal-title').text('Importado');
        }

        // function abrirModalProceso(importacion) {
        //     $('#progressBar').css('width', '0%').attr('aria-valuenow', 0).removeClass('bg-success').addClass(
        //         'progress-bar-animated');
        //     $('#progressText').text("0%");
        //     $('#modalProceso').modal('show');
        // }

        // function iniciarProceso() {
        //     let progress = 0;
        //     let interval = setInterval(function() {
        //         if (progress >= 100) {
        //             clearInterval(interval);
        //             $('#progressText').text("¡Proceso Completado!");
        //             $('#progressBar').removeClass('progress-bar-animated').addClass('bg-success');
        //         } else {
        //             progress += 10; // Incremento del progreso
        //             $('#progressBar').css('width', progress + '%').attr('aria-valuenow', progress);
        //             $('#progressText').text(progress + '%');
        //         }
        //     }, 500); // Se ejecuta cada 500ms
        // }

        ////
        function abrirModalProceso(importacion) {
            // Reiniciar barra de progreso
            $('#progressBar').css('width', '0%').attr('aria-valuenow', 0).removeClass('bg-success').addClass(
                'progress-bar-animated');
            $('#progressText').text("0%");

            // Mostrar modal
            $('#modalProceso').modal('show');

            // Llamar AJAX para ejecutar proceso en el backend
            $.ajax({
                url: "{{-- route('edu.imporpadronnominal.procesar.3', ['importacion' => ':importacion']) --}}"
                    .replace(':importacion', importacion),
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                }, // CSRF Token
                beforeSend: function() {
                    iniciarBarraProgreso();
                },
                success: function(response) {
                    console.log(response);
                    $('#progressText').text("¡Proceso Completado!");
                    $('#progressBar').removeClass('progress-bar-animated').addClass('bg-success');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("Error: " + textStatus, errorThrown);
                    $('#progressText').text("Error en el proceso.");
                    $('#progressBar').removeClass('progress-bar-animated').addClass('bg-danger');
                }
            });
        }

        function iniciarBarraProgreso() {
            let progress = 0;
            let interval = setInterval(function() {
                if (progress >= 100) {
                    clearInterval(interval);
                } else {
                    progress += 10; // Incremento del progreso
                    $('#progressBar').css('width', progress + '%').attr('aria-valuenow', progress);
                    $('#progressText').text(progress + '%');
                }
            }, 500); // Se ejecuta cada 500ms
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
