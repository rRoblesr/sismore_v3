@extends('layouts.main', ['titlePage' => 'IMPORTAR DATOS - SISTEMA NEXUS'])
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
            {{-- <div class="alert alert-danger"> --}}
            <div class="alert alert-{{ $tipo }}">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{ $mensaje }}
                {{-- <ul>
                    <li>{{ $mensaje }}</li>
                </ul> --}}
            </div>
        @endif

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-border">
                    <div class="card-header border-success-0 bg-transparent pb-0">
                        <h3 class="card-title">Datos de importación</h3>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('CuadroAsigPersonal.guardar') }}"
                            class="cmxform form-horizontal tasi-form upload_file">
                            @csrf
                            <input type="hidden" id="ccomment" name="comentario" value="">
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label class="col-form-label">Fuente de datos</label>
                                    <div class="">
                                        <input type="text" class="form-control btn-xs" readonly="readonly"
                                            value="NEXUS - MINEDU">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label class="col-form-label">Fecha Versión</label>
                                    <div class="">
                                        <input type="date" class="form-control btn-xs" name="fechaActualizacion"
                                            placeholder="Ingrese fecha actualizacion" autofocus required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="col-form-label">Archivo</label>
                                    <div class="">
                                        <input type="file" name="file" class="form-control btn-xs" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row  mt-0 mb-0">
                                <label class="col-md-2 col-form-label"></label>
                                <div class="col-md-10">
                                    <div class="pwrapper m-0 " style="display:none;">
                                        <div class="progress progress-lg progress_wrapper">
                                            <div class="progress-bar progress-bar-striped bg-info progress-bar-animated progress_bar"
                                                role="progressbar" style="width:0%">0%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="offset-lg-2 col-lg-10 text-right">
                                    <button class="btn btn-primary waves-effect waves-light mr-1"
                                        type="submit">Importar</button>
                                    {{-- <button class="btn btn-secondary waves-effect" type="button">Cancelar</button> --}}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-border">
                    <div class="card-header border-success-0 bg-transparent pb-0">
                        <h3 class="card-title">HISTORIAL DE IMPORTACION </h3>
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
                                                <th>Fecha Version</th>
                                                <th>Fuente</th>
                                                <th>Usuario</th>
                                                <th>Area</th>
                                                <th>Registro</th>
                                                <th>Estado</th>
                                                <th>Accion</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                                style="font-size:10px;width:10000px;">
                                <thead class="text-primary">
                                    <th>UNIDAD_EJECUTORA</th>
                                    <th>UGEL</th>
                                    <th>PROVINCIA</th>
                                    <th>DISTRITO</th>
                                    <th>TIPO_IE</th>
                                    <th>GESTION</th>
                                    <th>ZONA</th>
                                    <th>CODMOD_IE</th>
                                    <th>CODIGO_LOCAL</th>
                                    <th>CLAVE8</th>
                                    <th>NIVEL_EDUCATIVO</th>
                                    <th>INSTITUCION_EDUCATIVA</th>
                                    <th>JEC</th>
                                    <th>CODIGO_PLAZA</th>
                                    <th>TIPO_TRABAJADOR</th>
                                    <th>SUB_TIPO_TRABAJADOR</th>
                                    <th>CARGO</th>
                                    <th>SITUACION_LABORAL</th>
                                    <th>MOTIVO_VACANTE</th>
                                    <th>CATEGORIA_REMUNERATIVA</th>
                                    <th>DESCRIPCION_ESCALA</th>
                                    <th>JORNADA_LABORAL</th>
                                    <th>ESTADO</th>
                                    <th>FECHA_INICIO</th>
                                    <th>FECHA_TERMINO</th>
                                    <th>TIPO_REGISTRO</th>
                                    <th>LEY</th>
                                    <th>FECHA_INGRESO_NOMB</th>
                                    <th>DOCUMENTO</th>
                                    <th>CODMOD_DOCENTE</th>
                                    <th>APELLIDO_PATERNO</th>
                                    <th>APELLIDO_MATERNO</th>
                                    <th>NOMBRES</th>
                                    <th>FECHA_NACIMIENTO</th>
                                    <th>SEXO</th>
                                    <th>REGIMEN_PENSIONARIO</th>
                                    <th>FECHA_AFILIACION_RP</th>
                                    <th>CODIGO_ESSALUD</th>
                                    <th>AFP</th>
                                    <th>CODIGO_AFP</th>
                                    <th>FECHA_AFILIACION_AFP</th>
                                    <th>FECHA_DEVENGUE_AFP</th>
                                    <th>MENCION</th>
                                    <th>CENTRO_ESTUDIOS</th>
                                    <th>TIPO_ESTUDIOS</th>
                                    <th>ESTADO_ESTUDIOS</th>
                                    <th>ESPECIALIDAD_PROFESIONAL</th>
                                    <th>GRADO</th>
                                    <th>CELULAR</th>
                                    <th>EMAIL</th>
                                    <th>ESPECIALIDAD</th>
                                    <th>FECHA_RESOLUCION</th>
                                    <th>NUMERO_RESOLUCION</th>
                                    <th>DESC_SUPERIOR</th>
                                    <th>NUMERO_CONTRATO_CAS</th>
                                    <th>NUMERO_ADENDA_CAS</th>
                                    <th>PREVENTIVA</th>
                                    <th>REFERENCIA_PREVENTIVA</th>
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
                responsive: false,
                autoWidth: false,
                order: true,
                language: table_language,
                ajax: "{{ route('cuadroasigpersonal.listar.importados') }}",
                type: "POST",
            });

            /* table_principal = $('#datatable').DataTable({
                "ajax": "{{ route('cuadroasigpersonal.listar.importados') }}", //ece.listar.importados
                "columns": [{
                        data: 'fechaActualizacion'
                    },
                    {
                        data: 'fechaActualizacion'
                    },
                    {
                        data: 'fuente'
                    },
                    {
                        data: 'nombrecompleto'
                    },
                    {
                        data: 'created_at'
                    },
                    {
                        data: 'comentario'
                    },
                    {
                        data: 'estado'
                    },
                    {
                        data: 'accion'
                    },
                ],
                "responsive": true,
                "autoWidth": false,
                "order": false,
                "language": table_language,
            });
            table_principal.on('order.dt search.dt', function() {
                table_principal.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw(); */
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
                url: "{{ route('CuadroAsigPersonal.guardar') }}",
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
                        url: "{{ route('CuadroAsigPersonal.eliminar', '') }}/" + id,
                        type: "GET",
                        dataType: "JSON",
                        beforeSend: function() {
                            $('#eliminar' + id).html(
                                '<span><i class="fa fa-spinner fa-spin"></i></span>');
                        },
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
                        "url": "{{ route('CuadroAsigPersonal.listarimportados', '') }}/" + id,
                        "type": "POST",
                        "dataType": 'JSON',
                    },
                    "columns": [{
                            data: 'unidad_ejecutora',
                            name: 'unidad_ejecutora'
                        },
                        {
                            data: 'organo_intermedio',
                            name: 'organo_intermedio'
                        },
                        {
                            data: 'provincia',
                            name: 'provincia'
                        },
                        {
                            data: 'distrito',
                            name: 'distrito'
                        },
                        {
                            data: 'tipo_ie',
                            name: 'tipo_ie'
                        },
                        {
                            data: 'gestion',
                            name: 'gestion'
                        },
                        {
                            data: 'zona',
                            name: 'zona'
                        },
                        {
                            data: 'codmod_ie',
                            name: 'codmod_ie'
                        },
                        {
                            data: 'codigo_local',
                            name: 'codigo_local'
                        },
                        {
                            data: 'clave8',
                            name: 'clave8'
                        },
                        {
                            data: 'nivel_educativo',
                            name: 'nivel_educativo'
                        },
                        {
                            data: 'institucion_educativa',
                            name: 'institucion_educativa'
                        },
                        {
                            data: 'jec',
                            name: 'jec'
                        },
                        {
                            data: 'codigo_plaza',
                            name: 'codigo_plaza'
                        },
                        {
                            data: 'tipo_trabajador',
                            name: 'tipo_trabajador'
                        },
                        {
                            data: 'sub_tipo_trabajador',
                            name: 'sub_tipo_trabajador'
                        },
                        {
                            data: 'cargo',
                            name: 'cargo'
                        },
                        {
                            data: 'situacion_laboral',
                            name: 'situacion_laboral'
                        },
                        {
                            data: 'motivo_vacante',
                            name: 'motivo_vacante'
                        },
                        {
                            data: 'categoria_remunerativa',
                            name: 'categoria_remunerativa'
                        },
                        {
                            data: 'descripcion_escala',
                            name: 'descripcion_escala'
                        },
                        {
                            data: 'jornada_laboral',
                            name: 'jornada_laboral'
                        },
                        {
                            data: 'estado',
                            name: 'estado'
                        },
                        {
                            data: 'fecha_inicio',
                            name: 'fecha_inicio'
                        },
                        {
                            data: 'fecha_termino',
                            name: 'fecha_termino'
                        },
                        {
                            data: 'tipo_registro',
                            name: 'tipo_registro'
                        },
                        {
                            data: 'ley',
                            name: 'ley'
                        },
                        {
                            data: 'fecha_ingreso',
                            name: 'fecha_ingreso'
                        },
                        {
                            data: 'documento_identidad',
                            name: 'documento_identidad'
                        },
                        {
                            data: 'codigo_modular',
                            name: 'codigo_modular'
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
                            data: 'nombres',
                            name: 'nombres'
                        },
                        {
                            data: 'fecha_nacimiento',
                            name: 'fecha_nacimiento'
                        },
                        {
                            data: 'sexo',
                            name: 'sexo'
                        },
                        {
                            data: 'regimen_pensionario',
                            name: 'regimen_pensionario'
                        },
                        {
                            data: 'fecha_afiliacion_rp',
                            name: 'fecha_afiliacion_rp'
                        },
                        {
                            data: 'codigo_essalud',
                            name: 'codigo_essalud'
                        },
                        {
                            data: 'afp',
                            name: 'afp'
                        },
                        {
                            data: 'codigo_afp',
                            name: 'codigo_afp'
                        },
                        {
                            data: 'fecha_afiliacion_afp',
                            name: 'fecha_afiliacion_afp'
                        },
                        {
                            data: 'fecha_devengue_afp',
                            name: 'fecha_devengue_afp'
                        },
                        {
                            data: 'mencion',
                            name: 'mencion'
                        },
                        {
                            data: 'centro_estudios',
                            name: 'centro_estudios'
                        },
                        {
                            data: 'tipo_estudios',
                            name: 'tipo_estudios'
                        },
                        {
                            data: 'estado_estudios',
                            name: 'estado_estudios'
                        },
                        {
                            data: 'especialidad_profesional',
                            name: 'especialidad_profesional'
                        },
                        {
                            data: 'grado',
                            name: 'grado'
                        },
                        {
                            data: 'celular',
                            name: 'celular'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'especialidad',
                            name: 'especialidad'
                        },
                        {
                            data: 'fecha_resolucion',
                            name: 'fecha_resolucion'
                        },
                        {
                            data: 'numero_resolucion',
                            name: 'numero_resolucion'
                        },
                        {
                            data: 'desc_superior',
                            name: 'desc_superior'
                        },
                        {
                            data: 'numero_contrato_cas',
                            name: 'numero_contrato_cas'
                        },
                        {
                            data: 'numero_adenda_cas',
                            name: 'numero_adenda_cas'
                        },
                        {
                            data: 'preventiva',
                            name: 'preventiva'
                        },
                        {
                            data: 'referencia_preventiva',
                            name: 'referencia_preventiva'
                        }
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
