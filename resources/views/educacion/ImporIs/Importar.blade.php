@extends('layouts.main', ['titlePage' => 'IMPORTAR DATOS - INSTITUTOS SUPERIORES'])
@section('css')
    <!-- Table datatable css -->
    {{-- <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" /> --}}
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/buttons.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/fixedHeader.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/scroller.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />

    <style>
        .tablex thead th {
            padding: 6px;
            text-align: center;
        }

        .tablex thead td {
            padding: 6px;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
        }

        .tablex tbody td,
        .tablex tbody th,
        .tablex tfoot td,
        .tablex tfoot th {
            padding: 6px;
        }
    </style>
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
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Datos de importación
                        </h3>
                    </div>

                    <div class="card-body">
                        <div class="form">

                            <form class="cmxform form-horizontal tasi-form upload_file">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Fuente de datos</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" readonly="readonly"
                                            value="INSTITUTOS SUPERIORES">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Tipo</label>

                                    <div class="col-md-10">
                                        <select id="plantilla" name="plantilla" class="form-control" required>
                                            <option value="">SELECCIONAR PLANTILLA</option>
                                            <option value="30">ADMISION</option>
                                            <option value="29">MATRICULA</option>
                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Año Matricula</label>

                                    <div class="col-md-10">
                                        <select id="anio" name="anio" class="form-control">
                                            @foreach ($anios as $item)
                                                <option value="{{ $item->id }}"> {{ $item->anio }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}

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
                                        <textarea class="form-control" placeholder="comentario opcional" id="ccomment" name="comentario"></textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Archivo</label>
                                    <div class="col-md-10">
                                        <input type="file" name="file" class="form-control" required>
                                    </div>
                                </div>

                                <div class="form-group row  mt-0 mb-0">
                                    <label class="col-md-2 col-form-label"></label>
                                    <div class="col-md-10">
                                        <div class="pwrapper m-0" style="display:none;">
                                            <div class="progress progress_wrapper">
                                                <div class="progress-bar progress-bar-striped bg-info progress-bar-animated progress_bar"
                                                    role="progressbar" style="width:0%">0%</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row ">
                                    <div class="offset-lg-2 col-lg-10">
                                        <button class="btn btn-success waves-effect waves-light mr-1"
                                            type="submit">Importar</button>
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
                        <h3 class="card-title">HISTORIAL DE IMPORTACION</h3>
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
                                                <th>Version</th>
                                                <th>Fuente</th>
                                                <th>Plantilla</th>
                                                <th>Usuario</th>
                                                <th>Registro</th>
                                                <th>Comentario</th>
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
        <div id="modal-siagie-matricula1" class="modal fade centrarmodal" tabindex="-1" role="dialog">
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
                            <table id="siagie-matricula1" class="table table-striped table-bordered tablex"
                                style="font-size:10px">
                                {{-- width:7200px; --}}
                                <thead class="text-primary">
                                    <th>COD_MOD</th>
                                    <th>COD_LOCAL</th>
                                    <th>INSTITUTO_SUPERIOR</th>
                                    <th>COD_CARRERA</th>
                                    <th>CARRERA_ESPECIALIDAD</th>
                                    <th>MODALIDAD</th>
                                    <th>TIPO_MODALIDAD</th>
                                    <th>DOCUMENTO</th>
                                    <th>APELLIDO_PATERNO</th>
                                    <th>APELLIDO_MATERNO</th>
                                    <th>NOMBRES</th>
                                    <th>GENERO</th>
                                    <th>FECHA_NACIMIENTO</th>
                                    <th>NACIONALIDAD</th>
                                    <th>RAZA_ETNIA</th>
                                    <th>DEPARTAMENTO</th>
                                    <th>PROVINCIA</th>
                                    <th>DISTRITO</th>
                                    <th>CON_DISCAPACIDAD</th>
                                    <th>COD_MODULAR_IE</th>
                                    <th>INSTITUCION_EDUCATIVA</th>
                                    <th>ANIO_EGRESO</th>
                                    <th>INGRESO</th>
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
                            <table id="siagie-matricula" class="table table-striped table-bordered tablex"
                                style="font-size:10px">
                                {{-- width:7200px; --}}
                                <thead class="text-primary">
                                    <th>COD_MOD</th>
                                    <th>COD_LOCAL</th>
                                    <th>INSTITUTO_SUPERIOR</th>
                                    <th>COD_CARRERA</th>
                                    <th>CARRERA_ESPECIALIDAD</th>
                                    <th>TIPO_MATRICULA</th>
                                    <th>SEMESTRE</th>
                                    <th>CICLO</th>
                                    <th>TURNO</th>
                                    <th>SECCION</th>
                                    <th>CODIGO_ESTUDIANTE</th>
                                    <th>APELLIDO_PATERNO</th>
                                    <th>APELLIDO_MATERNO</th>
                                    <th>NOMBRES</th>
                                    <th>GENERO</th>
                                    <th>FECHA_NACIMIENTO</th>
                                    <th>NACIONALIDAD</th>
                                    <th>RAZA_ETNIA</th>
                                    <th>CON_DISCAPACIDAD</th>
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
                ordered: true,
                language: table_language,
                ajax: "{{ route('imporis.listar.importados') }}",
                type: "POST",
            });
        });

        function upload(e) {
            var plantilla = $('#plantilla').val();
            //if (plantilla == 0) alert('seleccione una tipo de plantilla');
            var url = plantilla == 30 ? "{{ route('imporis.guardar.admision') }}" :
                "{{ route('imporis.guardar.matricula') }}";
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
                url: url,
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
                        url: "{{ url('/') }}/ImporIS/eliminar/" + id,
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

        function monitor1(id) {
            $('#siagie-matricula1').DataTable({
                "processing": true,
                "serverSide": true,
                "responsive": false,
                "autoWidth": false,
                "ordered": true,
                "destroy": true,
                "language": table_language,
                "ajax": {
                    "url": "{{ route('imporis.listarimportados') }}",
                    "data": {
                        "importacion_id": id,
                        "fuenteImportacion_id": 30,
                    },
                    "type": "GET",
                    "dataType": 'JSON',
                },
                "columns": [{
                        data: 'cod_mod',
                        name: 'cod_mod'
                    },
                    {
                        data: 'cod_local',
                        name: 'cod_local'
                    },
                    {
                        data: 'instituto_superior',
                        name: 'instituto_superior'
                    },
                    {
                        data: 'cod_carrera',
                        name: 'cod_carrera'
                    },
                    {
                        data: 'carrera_especialidad',
                        name: 'carrera_especialidad'
                    },
                    {
                        data: 'modalidad',
                        name: 'modalidad'
                    },
                    {
                        data: 'tipo_modalidad',
                        name: 'tipo_modalidad'
                    },
                    {
                        data: 'documento',
                        name: 'documento'
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
                        data: 'genero',
                        name: 'genero'
                    },
                    {
                        data: 'fecha_nacimiento',
                        name: 'fecha_nacimiento'
                    },
                    {
                        data: 'nacionalidad',
                        name: 'nacionalidad'
                    },
                    {
                        data: 'raza_etnia',
                        name: 'raza_etnia'
                    },
                    {
                        data: 'departamento',
                        name: 'departamento'
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
                        data: 'con_discapacidad',
                        name: 'con_discapacidad'
                    },
                    {
                        data: 'cod_modular_ie',
                        name: 'cod_modular_ie'
                    },
                    {
                        data: 'institucion_educativa',
                        name: 'institucion_educativa'
                    },
                    {
                        data: 'anio_egreso',
                        name: 'anio_egreso'
                    },
                    {
                        data: 'ingreso',
                        name: 'ingreso'
                    },
                ],
                /* dom: 'Bfrtip',
                buttons: [{
                    extend: "excel",
                    title: null,
                    className: "btn-sm",
                    text: '<i class="fa fa-file-excel"></i> Excel',
                    titleAttr: 'Excel',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18],
                    },
                }], */
            });

            $('#modal-siagie-matricula1').modal('show');
            $('#modal-siagie-matricula1 .modal-title').text('Importado Admision');
        }

        function monitor2(id) {
            $('#siagie-matricula').DataTable({
                "processing": true,
                "serverSide": true,
                "responsive": false,
                "autoWidth": false,
                "ordered": true,
                "destroy": true,
                "language": table_language,
                "ajax": {
                    "url": "{{ route('imporis.listarimportados') }}",
                    "data": {
                        "importacion_id": id,
                        "fuenteImportacion_id": 29,
                    },
                    "type": "GET",
                    "dataType": 'JSON',
                },
                "columns": [{
                    data: 'cod_mod',
                    name: 'cod_mod'
                }, {
                    data: 'cod_local',
                    name: 'cod_local'
                }, {
                    data: 'instituto_superior',
                    name: 'instituto_superior'
                }, {
                    data: 'cod_carrera',
                    name: 'cod_carrera'
                }, {
                    data: 'carrera_especialidad',
                    name: 'carrera_especialidad'
                }, {
                    data: 'tipo_matricula',
                    name: 'tipo_matricula'
                }, {
                    data: 'semestre',
                    name: 'semestre'
                }, {
                    data: 'ciclo',
                    name: 'ciclo'
                }, {
                    data: 'turno',
                    name: 'turno'
                }, {
                    data: 'seccion',
                    name: 'seccion'
                }, {
                    data: 'codigo_estudiante',
                    name: 'codigo_estudiante'
                }, {
                    data: 'apellido_paterno',
                    name: 'apellido_paterno'
                }, {
                    data: 'apellido_materno',
                    name: 'apellido_materno'
                }, {
                    data: 'nombres',
                    name: 'nombres'
                }, {
                    data: 'genero',
                    name: 'genero' /*  */
                }, {
                    data: 'fecha_nacimiento',
                    name: 'fecha_nacimiento'
                }, {
                    data: 'nacionalidad',
                    name: 'nacionalidad'
                }, {
                    data: 'raza_etnia',
                    name: 'raza_etnia'
                }, {
                    data: 'con_discapacidad',
                    name: 'con_discapacidad'
                }, ],
                /* dom: 'Bfrtip',
                buttons: [{
                    extend: "excel",
                    title: null,
                    className: "btn-sm",
                    text: '<i class="fa fa-file-excel"></i> Excel',
                    titleAttr: 'Excel',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18],
                    },
                }], */
            });

            $('#modal-siagie-matricula').modal('show');
            $('#modal-siagie-matricula .modal-title').text('Importado');
        }
    </script>
    <script src="{{ asset('/') }}public/assets/libs/jquery-validation/jquery.validate.min.js"></script>
    <!-- Validation init js-->
    <script src="{{ asset('/') }}public/assets/js/pages/form-validation.init.js"></script>

    {{-- <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script> --}}

    <!-- third party js -->
    <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.buttons.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/buttons.bootstrap4.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/jszip/jszip.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/pdfmake/pdfmake.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/pdfmake/vfs_fonts.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/buttons.html5.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/buttons.print.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.fixedHeader.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.keyTable.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.scroller.min.js"></script>
@endsection
