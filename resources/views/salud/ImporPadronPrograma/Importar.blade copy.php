@extends('layouts.main', ['titlePage' => 'IMPORTAR DATOS - ESTABLECIMIENTO'])
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
                            <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()"><i
                                    class="fa fa-redo"></i> Actualizar</button>
                            <button type="button" class="btn btn-success btn-xs waves-effect waves-light"
                                data-toggle="modal" data-target=".bs-example-modal-lg" data-backdrop="static"
                                data-keyboard="false"><i class="ion ion-md-cloud-upload"></i> Importar</button>
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
                                                <th>Usuario</th>
                                                <th>Programa</th>
                                                <th>Servicio</th>
                                                <th>Fecha Registro</th>
                                                <th>Registros Subidos</th>
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
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myLargeModalLabel">Importar</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <form class="cmxform form-horizontal tasi-form upload_file">
                            @csrf
                            <input type="hidden" name="comentario" value="">

                            {{-- <div class="form-group">
                                <label class="col-form-label">Fuente de datos</label>
                                <input type="text" class="form-control" value="PROGRAMAS" readonly>
                            </div> --}}

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

                            {{-- <div class="form-group">
                                <label class="col-form-label">Archivo</label>
                                <input type="file" name="file" class="form-control" accept=".xls,.xlsx" required>
                            </div> --}}

                            <div class="form-group">
                                <label class="col-form-label">Archivo</label>
                                <div class="input-group">
                                    <input type="file" name="file" id="archivoSubir" class="d-none"
                                        accept=".xls,.xlsx" required>
                                    <input type="text" class="form-control" id="nombreArchivo"
                                        placeholder="Selecciona un archivo" readonly>
                                    <button class="btn btn-primary" type="button"
                                        onclick="document.getElementById('archivoSubir').click();">
                                        <i class="fas fa-upload"></i> Buscar
                                    </button>
                                </div>
                            </div>

                            {{-- <div class="form-group">
                                <label class="col-form-label">Archivo</label>
                                <div class="custom-file">
                                    <input type="file" name="file" class="custom-file-input" id="archivoInput"
                                        accept=".xls,.xlsx" >
                                    <label class="custom-file-label" for="archivoInput"
                                        data-browse="Subir Archivo">Seleccionar archivo...</label>
                                </div>
                            </div> --}}



                            {{-- <div class="form-group">
                                <label class="col-form-label">Archivo</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-primary" type="button"
                                            onclick="document.getElementById('archivoSubir').click();">
                                            <i class="fas fa-upload"></i> Subir Archivo
                                        </button>
                                    </div>
                                    <input type="text" class="form-control" id="nombreArchivo"
                                        placeholder="Ningún archivo seleccionado" readonly>
                                    <input type="file" name="file" id="archivoSubir" class="d-none"
                                        accept=".xls,.xlsx" required>
                                </div>
                            </div> --}}

                            {{-- <div class="form-group">
                                <label class="col-form-label">Archivo</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="nombreArchivo"
                                        placeholder="Ningún archivo seleccionado" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button"
                                            onclick="document.getElementById('archivoSubir').click();"> <i
                                                class="fas fa-upload"></i> Subir Archivo </button>
                                        <button class="btn btn-outline-primary" type="button"
                                            onclick="document.getElementById('archivoSubir').click();"> <i
                                                class="fas fa-upload"></i> Subir Archivo </button>
                                    </div>
                                    <input type="file" name="file" id="archivoSubir" class="d-none"
                                        accept=".xls,.xlsx" >
                                </div>
                            </div> --}}

                            {{-- <div class="form-group text-center">
                                <label class="col-form-label d-block">Archivo</label>
                                <button type="button" class="btn btn-primary btn-lg position-relative"
                                    onclick="document.getElementById('archivoSubir2').click();">
                                    <i class="fas fa-file-upload"></i> Seleccionar Archivo
                                    <span id="nombreArchivo2" class="d-block text-white mt-2">Ningún archivo
                                        seleccionado</span>
                                </button>
                                <input type="file" name="file" id="archivoSubir2" class="d-none"
                                    accept=".xls,.xlsx" >
                            </div> --}}

                            {{-- <div class="form-group">
                                <label class="col-form-label">Subir Archivo</label>
                                <div id="drop-area" class="border rounded p-4 text-center bg-light">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-primary"></i>
                                    <p class="mt-2">Arrastra y suelta tu archivo aquí o <span
                                            class="text-primary font-weight-bold">haz clic</span></p>
                                    <input type="file" name="file" id="archivoSubir3" class="d-none"
                                        accept=".xls,.xlsx" >
                                </div>
                                <p class="text-center mt-2 text-muted" id="nombreArchivo3">Ningún archivo seleccionado</p>
                            </div> --}}




                            <div class="form-group pwrapper" style="display:none;">
                                <div class="progress progress_wrapper">
                                    <div class="progress-bar progress-bar-striped bg-info progress-bar-animated progress_bar"
                                        role="progressbar" style="width:0%">0%</div>
                                </div>
                            </div>


                            <div class="form-group text-center">
                                <button class="btn btn-primary waves-effect waves-light" type="submit">
                                    <i class="ion ion-md-cloud-upload"></i> Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
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
                                                    value="PROGRAMAS">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <label class="col-form-label">Programa</label>
                                            <div class="">
                                                <select name="tipo" id="tipo" class="form-control">
                                                    <option value="0">SELECCIONAR</option>
                                                    <option value="1">CUNAMAS</option>
                                                    <option value="2">JUNTOS</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
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
                                                <input type="file" name="file" class="form-control"
                                                    accept=".xls,.xlsx" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row  mt-0 mb-0">
                                        <!--label class="col-md-2 col-form-label"></label-->
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
        </div> --}}

        <!-- Bootstrap modal -->
        {{-- <div id="modal-siagie-matricula" class="modal fade centrarmodal" tabindex="-1" role="dialog">
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
                            <table id="siagie-matricula" class="table font-12 table-striped table-bordered">
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

                                </tbody>

                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div> --}}

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
                            <table id="siagie-matricula" class="table font-12 table-striped table-bordered">
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
        <!-- End Bootstrap modal -->

    </div>
@endsection

@section('js')
    <script>
        var table_principal = '';
        $(document).ready(function() {
            $('.upload_file').on('submit', upload);

            // let dropArea = document.getElementById('drop-area');
            // let fileInput = document.getElementById('archivoSubir3');

            // dropArea.addEventListener('click', () => fileInput.click());
            // fileInput.addEventListener('change', function() {
            //     let fileName = this.files.length ? this.files[0].name : "Ningún archivo seleccionado";
            //     document.getElementById('nombreArchivo3').innerText = fileName;
            // });

            table_principal = $('#datatable').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: true,
                language: table_language,
                ajax: "{{ route('imporpadronprograma.listar.importados') }}",
                type: "POST",
            });
        });

        // document.querySelector("#archivoInput").addEventListener("change", function() {
        //     let fileName = this.files[0] ? this.files[0].name : "Seleccionar archivo...";
        //     this.nextElementSibling.innerText = fileName;
        // });

        // document.getElementById("archivoSubir").addEventListener("change", function() {
        //     document.getElementById("nombreArchivo").value = this.files[0] ? this.files[0].name : "";
        // });

        // document.getElementById('archivoSubir2').addEventListener('change', function() {
        //     let fileName = this.files.length ? this.files[0].name : "Ningún archivo seleccionado";
        //     document.getElementById('nombreArchivo2').innerText = fileName;
        // });

        document.getElementById('archivoSubir').addEventListener('change', function() {
            let fileName = this.files.length ? this.files[0].name : "Selecciona un archivo";
            document.getElementById('nombreArchivo').value = fileName;
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
    </script>
    <script src="{{ asset('/') }}public/assets/libs/jquery-validation/jquery.validate.min.js"></script>
    <!-- Validation init js-->
    <script src="{{ asset('/') }}public/assets/js/pages/form-validation.init.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>
@endsection
