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
                            <button type="button" class="btn btn-success btn-xs"
                                onclick="javascript:window.open('https://docs.google.com/spreadsheets/d/1DKVRmfZgTc5kNKT1Uj5YRFKLOZOlTitx/edit?usp=sharing&ouid=108127328164905589197&rtpof=true&sd=true','_blank');"><i
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
                        <h3 class="card-title">HISTORIAL DE IMPORTACION </h3>
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
                                                <input type="date" class="form-control" name="fechaActualizacion"
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
                                    <th>TIPO</th>
                                    <th>COD_GOB_REG</th>
                                    <th>GOBIERNOS_REGIONALES</th>
                                    <th>PIA</th>
                                    <th>PIM</th>
                                    <th>CERTIFICACION</th>
                                    <th>COMPROMISO_ANUAL</th>
                                    <th>COMPROMISO_MENSUAL</th>
                                    <th>DEVENGADO</th>
                                    <th>GIRADO</th>
                                    <th>AVANCE</th>
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

        <!-- Modal para procesar -->
        <div id="modal-procesar-proyectos" class="modal fade centrarmodal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Procesar Importación de Consulta Amigable</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="procp_importacion_id" value="">
                        <p class="mb-3">
                            Seleccione el proceso que desea ejecutar para la importación seleccionada.
                        </p>
                        <div class="text-center">
                            <div class="d-inline-block mr-2">
                                <button type="button" id="btn-procp-base" class="btn btn-primary btn-sm">
                                    <i class="fa fa-database"></i> Procesar Base de Consulta Amigable
                                </button>
                                <button type="button" id="btn-procp-base-ojito"
                                    class="btn btn-outline-primary btn-sm ml-1" title="Ver estado">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                            <div class="d-inline-block">
                                <a href="#" id="btn-descargar-base" class="btn btn-success btn-sm" target="_blank">
                                    <i class="fa fa-download"></i> Descargar Base Procesada
                                </a>
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
                        <h5 class="modal-title">Actualizar Archivo de Consulta Amigable</h5>
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
                                <strong>Advertencia:</strong> Esta acción eliminará los registros de consulta amigable asociados a esta
                                importación y cargará los nuevos datos del archivo Excel seleccionado.
                            </div>

                            <div class="form-group">
                                <label class="col-form-label">Fecha Versión</label>
                                <div class="">
                                    <input type="date" class="form-control" name="fechaActualizacion"
                                        id="fechaActualizacion_upd" placeholder="Ingrese fecha actualizacion" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-form-label">Archivo (Excel)</label>
                                <div class="input-group">
                                    <input id="file_upd" name="file" class="form-control d-none" type="file"
                                        accept=".xls,.xlsx" required>
                                    <input id="nfile_upd" name="nfile" class="form-control" type="text"
                                        placeholder="Seleccione Archivo" readonly>
                                    <span class="input-group-append">
                                        <label for="file_upd" class="btn btn-primary btn-file-documento">
                                            <i class="fas fa-cloud-upload-alt"></i> </label>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row  mt-0 mb-0">
                                <div class="col-md-12">
                                    <div class="pwrapper_upd m-0" style="display:none;">
                                        <div class="progress progress_wrapper_upd">
                                            <div class="progress-bar progress-bar-striped bg-info progress-bar-animated progress_bar_upd"
                                                role="progressbar" style="width:0%">0%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row ">
                                <div class="col-lg-12 text-center">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit"><i
                                            class="ion ion-md-cloud-upload"></i> Actualizar</button>
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
        $(document).ready(function() {
            $('.upload_file').on('submit', upload);

            table_principal = $('#datatable').DataTable({
                responsive: true,
                autoWidth: false,
                order: true,
                language: table_language,
                ajax: "{{ route('imporconsultaamigable.listar.importados') }}",
                type: "POST",
            });

            $("#file").on("change", function() {
                var fileName = $(this).val().split("\\").pop();
                $('#nfile').val(fileName);

            });

            $('.update_file').on('submit', uploadUpdate);

            $("#file_upd").on("change", function() {
                var fileName = $(this).val().split("\\").pop();
                $('#nfile_upd').val(fileName);
            });

            $('#btn-procp-base').click(function() {
                procesarBase();
            });

            $('#btn-procp-base-ojito').click(function() {
                verificarBase();
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
                url: "{{ route('imporconsultaamigable.guardar') }}",
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

        function geteliminar(id) {
            bootbox.confirm("Seguro desea Eliminar este IMPORTACION?", function(result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ route('imporconsultaamigable.eliminar', 'PLACEHOLDER') }}".replace('PLACEHOLDER', id),
                        type: "GET",
                        dataType: "JSON",
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
                            toastr.error(
                                'No se puede eliminar este registro por seguridad de su base de datos, Contacte al Administrador del Sistema',
                                'Mensaje');
                        }
                    });
                }
            });
        };

        function monitor(id) {
            var url = "{{ route('imporconsultaamigable.listarimportados', 55555) }}";
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
                    "columns": [
                    { 
                        data: 'tipo', 
                        name: 'tipo',
                        render: function(data) {
                            switch (parseInt(data, 10)) {
                                case 1: return 'ACTIVIDADES/PROYECTOS';
                                case 2: return 'ACTIVIDADES';
                                case 3: return 'PROYECTOS';
                                default: return (data === null || data === undefined) ? '' : data;
                            }
                        }
                    },
                        { data: 'cod_gob_reg', name: 'cod_gob_reg' },
                        { data: 'gobiernos_regionales', name: 'gobiernos_regionales' },
                        { data: 'pia', name: 'pia' },
                        { data: 'pim', name: 'pim' },
                        { data: 'certificacion', name: 'certificacion' },
                        { data: 'compromiso_anual', name: 'compromiso_anual' },
                        { data: 'compromiso_mensual', name: 'compromiso_mensual' },
                        { data: 'devengado', name: 'devengado' },
                        { data: 'girado', name: 'girado' },
                        { data: 'avance', name: 'avance' },
                    ],
                }

            );

            $('#modal-siagie-matricula').modal('show');
            $('#modal-siagie-matricula .modal-title').text('Importado');
        }

        function abrirModalActualizar(id, fecha) {
            $('#upd_importacion_id').val(id);
            $('#fechaActualizacion_upd').val(fecha);
            $('#modal-actualizar').modal('show');
        }

        function abrirProcesos(id) {
            $('#procp_importacion_id').val(id);
            // Actualizar enlace de descarga
            let urlDescarga = "{{ route('imporconsultaamigable.descargar.base', 'PLACEHOLDER') }}";
            urlDescarga = urlDescarga.replace('PLACEHOLDER', id);
            $('#btn-descargar-base').attr('href', urlDescarga);
            
            $('#modal-procesar-proyectos').modal('show');
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
                url: "{{ route('imporconsultaamigable.actualizar') }}",
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
                    $('#modal-actualizar').modal('hide');
                    Swal.fire({
                        title: "¡Actualización Exitosa!",
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
                }
            }).fail(err => {
                progress_bar.removeClass('bg-success bg-info').addClass('bg-danger');
                let msg = 'Error desconocido';
                if(err.responseJSON && err.responseJSON.msg) msg = err.responseJSON.msg;
                else if(err.responseText) msg = err.responseText;
                progress_bar.html('Error: ' + msg);
            }).always(() => {
                $('button', form).attr('disabled', false);
            });
        }

        function procesarBase() {
            let id = $('#procp_importacion_id').val();
            let btn = $('#btn-procp-base');
            let originalText = btn.html();

            btn.html('<i class="fa fa-spinner fa-spin"></i> Procesando...').prop('disabled', true);

            $.ajax({
                url: "{{ route('imporconsultaamigable.procesar.base', 'PLACEHOLDER') }}".replace('PLACEHOLDER', id),
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {
                        toastr.success(data.msg, 'Mensaje');
                    } else {
                        toastr.error(data.msg, 'Mensaje');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    let msg = 'Error al procesar base.';
                    if(jqXHR.responseJSON && jqXHR.responseJSON.msg) msg = jqXHR.responseJSON.msg;
                    toastr.error(msg, 'Mensaje');
                },
                complete: function() {
                    btn.html(originalText).prop('disabled', false);
                }
            });
        }

        function verificarBase() {
            let id = $('#procp_importacion_id').val();
            $.ajax({
                url: "{{ route('imporconsultaamigable.verificar.base', 'PLACEHOLDER') }}".replace('PLACEHOLDER', id),
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {
                        alert("Estado Base: " + (data.base ? 'Generada' : 'No generada') + "\nRegistros Detalle: " + data.detalle);
                    } else {
                        toastr.error(data.msg, 'Mensaje');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error('Error al verificar base.', 'Mensaje');
                }
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
