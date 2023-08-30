@extends('layouts.main', ['titlePage' => 'IMPORTAR DATOS - ' . $fuente->formato])
@section('css')
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"> --}}
@endsection
@section('content')

    <div class="content">

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                Error al Cargar Archivo <br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($mensaje != '')
            <div class="alert alert-danger">
                <ul>
                    <li>{{ $mensaje }}</li>
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-widgets">
                            <button type="button" class="btn btn-warning btn-xs" onclick="location.reload()"><i
                                    class="fa fa-redo"></i> Actualizar</button>
                            <button type="button" class="btn btn-success btn-xs"
                                onclick="javascript:window.open('https://docs.google.com/spreadsheets/d/1g0vsLkpjOGlKs3VTbFdhQ4AKx-FT779h/edit?usp=sharing&ouid=108127328164905589197&rtpof=true&sd=true','_blank');"><i
                                    class="fa fa-file-excel"></i>
                                Plantilla</button>
                            <button type="button" class="btn btn-danger btn-xs"
                                onclick="javascript:window.open('https://1drv.ms/x/s!AgffhPHh-Qgo0ARVFDACt8-qpLyn?e=y0Ledg','_blank');"><i
                                    class="mdi mdi-file-pdf-outline"></i>
                                Manual</button>
                        </div>
                        <h3 class="card-title">Datos de importación</h3>
                    </div>

                    <div class="card-body">
                        <div class="form">

                            <form class="cmxform form-horizontal tasi-form upload_file">
                                @csrf
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <div class="col-md-3">
                                            <label class="col-form-label">Fuente de datos</label>
                                            <div class="">
                                                <input type="text" class="form-control" readonly="readonly"
                                                    value="{{ $fuente->nombre }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="col-form-label">Fecha Versión</label>
                                            <div class="">
                                                <input type="date" class="form-control" name="fechaActualizacion"
                                                    placeholder="Ingrese fecha actualizacion" autofocus required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
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
                                </div>

                                {{-- <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Fuente de datos</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" readonly="readonly" value="EMPACOPSA">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Fecha Versión</label>
                                    <div class="col-md-10">
                                        <input type="date" class="form-control" name="fechaActualizacion"
                                            placeholder="Ingrese fecha actualizacion" autofocus required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Comentario</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control" placeholder="comentario opcional" id="ccomment"
                                            name="comentario"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Archivo</label>
                                    <div class="col-md-10">
                                        <input type="file" name="file" class="form-control" required>
                                    </div>
                                </div> --}}

                                <div class="form-group row mb-0">
                                    {{-- <div class="offset-lg-2 col-lg-10"> --}}
                                    <div class="col-12 d-flex justify-content-end">
                                        <button class="btn btn-primary waves-effect waves-light mr-1"
                                            type="submit">Importar</button>
                                        {{-- <button class="btn btn-secondary waves-effect" type="button">Cancelar</button> --}}
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- .form -->
                    </div>
                    <!-- card-body -->
                </div>
                <!-- card -->
            </div>
            <!-- col -->
        </div>
        <!-- End row -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
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
                                style="font-size:10px;width:5000px;">
                                <thead class="text-primary">
                                    <td>COD_PLIEGO</td>
                                    <td>COD_UE</td>
                                    <td>NOTAS</td>
                                    <td>FECHA_SOLICITUD</td>
                                    <td>FECHA_APROBACION</td>
                                    <td>COD_TIPO_MOD</td>
                                    <td>TIPO_MODIFICACION</td>
                                    <td>DOCUMENTO</td>
                                    <td>DISPOSITIVO_LEGAL</td>
                                    <td>TIPO_INGRESO</td>
                                    <td>JUSTIFICACION</td>
                                    <td>ENTIDAD_ORIGEN</td>
                                    <td>TIPO_PRESUPUESTO</td>
                                    <td>SEC_FUNC</td>
                                    <td>COD_CAT_PRES</td>
                                    <td>TIPO_PROD_PROY</td>
                                    <td>COD_PROD_PROY</td>
                                    <td>TIPO_ACT_ACC_OBRA</td>
                                    <td>COD_ACT_ACC_OBRA</td>
                                    <td>META</td>
                                    <td>COD_FINA</td>
                                    <td>COD_RUB</td>
                                    <td>COD_CAT_GAS</td>
                                    <td>COD_TIPO_TRANS</td>
                                    <td>COD_GEN</td>
                                    <td>COD_SUBGEN</td>
                                    <td>COD_SUBGEN_DET</td>
                                    <td>COD_ESP</td>
                                    <td>COD_ESP_DET</td>
                                    <td>ANULACION</td>
                                    <td>CREDITO</td>
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
        <!-- End Bootstrap modal -->


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
                ajax: "{{ route('impormodificaciones.listar.importados') }}",
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
                url: "{{ route('impormodificaciones.guardar') }}",
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

        function geteliminar(id) {
            bootbox.confirm("Seguro desea Eliminar este IMPORTACION?", function(result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ url('/') }}/IMPORMODS/eliminar/" + id,
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
                        "url": "{{ route('impormodificaciones.listarimportados', '') }}/" + id,
                        "type": "POST",
                        "dataType": 'JSON',
                    },
                    "columns": [{
                            data: 'cod_pliego',
                            name: 'cod_pliego'
                        },
                        {
                            data: 'cod_ue',
                            name: 'cod_ue'
                        },
                        {
                            data: 'notas',
                            name: 'notas'
                        },
                        {
                            data: 'fecha_solicitud',
                            name: 'fecha_solicitud'
                        },
                        {
                            data: 'fecha_aprobacion',
                            name: 'fecha_aprobacion'
                        },
                        {
                            data: 'cod_tipo_mod',
                            name: 'cod_tipo_mod'
                        },
                        {
                            data: 'tipo_modificacion',
                            name: 'tipo_modificacion'
                        },
                        {
                            data: 'documento',
                            name: 'documento'
                        },
                        {
                            data: 'dispositivo_legal',
                            name: 'dispositivo_legal'
                        },
                        {
                            data: 'tipo_ingreso',
                            name: 'tipo_ingreso'
                        },
                        {
                            data: 'justificacion',
                            name: 'justificacion'
                        },
                        {
                            data: 'entidad_origen',
                            name: 'entidad_origen'
                        },
                        {
                            data: 'tipo_presupuesto',
                            name: 'tipo_presupuesto'
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
                            data: 'tipo_prod_proy',
                            name: 'tipo_prod_proy'
                        },
                        {
                            data: 'cod_prod_proy',
                            name: 'cod_prod_proy'
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
                            data: 'meta',
                            name: 'meta'
                        },
                        {
                            data: 'cod_fina',
                            name: 'cod_fina'
                        },
                        {
                            data: 'cod_rub',
                            name: 'cod_rub'
                        },
                        {
                            data: 'cod_cat_gas',
                            name: 'cod_cat_gas'
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
                            data: 'cod_subgen',
                            name: 'cod_subgen'
                        },
                        {
                            data: 'cod_subgen_det',
                            name: 'cod_subgen_det'
                        },
                        {
                            data: 'cod_esp',
                            name: 'cod_esp'
                        },
                        {
                            data: 'cod_esp_det',
                            name: 'cod_esp_det'
                        },
                        {
                            data: 'anulacion',
                            name: 'anulacion'
                        },
                        {
                            data: 'credito',
                            name: 'credito'
                        },
                    ],
                }

            );

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
