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
                            <table id="siagie-matricula" class="table table-striped table-bordered" style="font-size:12px">
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
                                    <th>CODIGO_PLAZA</th>
                                    <th>TIPO_TRABAJADOR</th>
                                    <th>SUB_TIPO_TRABAJADOR</th>
                                    <th>CARGO</th>
                                    <th>SITUACION_LABORAL</th>
                                    <th>MOTIVO_VACANTE</th>
                                    <th>DOCUMENTO</th>
                                    <th>SEXO</th>
                                    <th>CODMOD_DOCENTE</th>
                                    <th>APELLIDO_PATERNO</th>
                                    <th>APELLIDO_MATERNO</th>
                                    <th>NOMBRES</th>
                                    <th>FECHA_INGRESO</th>
                                    <th>CATEGORIA_REMUNERATIVA</th>
                                    <th>JORNADA_LABORAL</th>
                                    <th>ESTADO</th>
                                    <th>FECHA_NACIMIENTO</th>
                                    <th>FECHA_INICIO</th>
                                    <th>FECHA_TERMINO</th>
                                    <th>TIPO_REGISTRO</th>
                                    <th>LEY</th>
                                    <th>PREVENTIVA</th>
                                    <th>ESPECIALIDAD</th>
                                    <th>TIPO_ESTUDIOS</th>
                                    <th>ESTADO_ESTUDIOS</th>
                                    <th>GRADO</th>
                                    <th>MENCION</th>
                                    <th>ESPECIALIDAD_PROFESIONAL</th>
                                    <th>FECHA_RESOLUCION</th>
                                    <th>NUMERO_RESOLUCION</th>
                                    <th>CENTRO_ESTUDIOS</th>
                                    <th>CELULAR</th>
                                    <th>EMAIL</th>
                                    <th>DESC_SUPERIOR</th>


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
                        url: "{{ url('/') }}/Importacion/GetEliminar/" + id,
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

        function monitor(id) {
            var url = "{{ route('cuadroasigpersonal.listarimportados', 55555) }}";
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
                            data: 'dre',
                            name: 'dre'
                        }, {
                            data: 'ugel',
                            name: 'ugel'
                        }, {
                            data: 'departamento',
                            name: 'departamento'
                        }, {
                            data: 'provincia',
                            name: 'provincia'
                        }, {
                            data: 'distrito',
                            name: 'distrito' /*  */
                        }, {
                            data: 'centropoblado',
                            name: 'centropoblado'
                        }, {
                            data: 'modular',
                            name: 'modular'
                        }, {
                            data: 'iiee',
                            name: 'iiee'
                        }, {
                            data: 'codnivel',
                            name: 'codnivel'
                        }, {
                            data: 'nivel',
                            name: 'nivel' /*  */
                        }, {
                            data: 'tiponivel',
                            name: 'tiponivel'
                        }, {
                            data: 'codgestion',
                            name: 'codgestion'
                        }, {
                            data: 'gestiondependencia',
                            name: 'gestiondependencia'
                        },
                        /* {
                                                   data: 'codtipogestion'
                                               },  */
                        {
                            data: 'tipogestion',
                            name: 'tipogestion'
                        }, {
                            data: 'total_estudiantes',
                            name: 'total_estudiantes' /*  */
                        }, {
                            data: 'matricula_definitiva',
                            name: 'matricula_definitiva'
                        }, {
                            data: 'matricula_proceso',
                            name: 'matricula_proceso'
                        }, {
                            data: 'dni_validado',
                            name: 'dni_validado'
                        }, {
                            data: 'dni_sin_validar',
                            name: 'dni_sin_validar'
                        }, {
                            data: 'registrado_sin_dni',
                            name: 'registrado_sin_dni' /*  */
                        }, {
                            data: 'total_grados',
                            name: 'total_grados'
                        }, {
                            data: 'total_secciones',
                            name: 'total_secciones'
                        }, {
                            data: 'nominas_generadas',
                            name: 'nominas_generadas'
                        }, {
                            data: 'nominas_aprobadas',
                            name: 'nominas_aprobadas'
                        }, {
                            data: 'nominas_por_rectificar',
                            name: 'nominas_por_rectificar' /*  */
                        }, {
                            data: 'tres_anios_hombre',
                            name: 'tres_anios_hombre'
                        }, {
                            data: 'tres_anios_mujer',
                            name: 'tres_anios_mujer'
                        }, {
                            data: 'cuatro_anios_hombre',
                            name: 'cuatro_anios_hombre'
                        }, {
                            data: 'cuatro_anios_mujer',
                            name: 'cuatro_anios_mujer'
                        }, {
                            data: 'cinco_anios_hombre',
                            name: 'cinco_anios_hombre' /*  */
                        }, {
                            data: 'cinco_anios_mujer',
                            name: 'cinco_anios_mujer'
                        }, {
                            data: 'primero_hombre',
                            name: 'primero_hombre'
                        }, {
                            data: 'primero_mujer',
                            name: 'primero_mujer'
                        }, {
                            data: 'segundo_hombre',
                            name: 'segundo_hombre'
                        }, {
                            data: 'segundo_mujer',
                            name: 'segundo_mujer' /*  */
                        }, {
                            data: 'tercero_hombre',
                            name: 'tercero_hombre'
                        }, {
                            data: 'tercero_mujer',
                            name: 'tercero_mujer'
                        }, {
                            data: 'cuarto_hombre',
                            name: 'cuarto_hombre'
                        }, {
                            data: 'cuarto_mujer',
                            name: 'cuarto_mujer'
                        }, {
                            data: 'quinto_hombre',
                            name: 'quinto_hombre' /*  */
                        }, {
                            data: 'quinto_mujer',
                            name: 'quinto_mujer'
                        }, {
                            data: 'sexto_hombre',
                            name: 'sexto_hombre'
                        }, {
                            data: 'sexto_mujer',
                            name: 'sexto_mujer'
                        }, {
                            data: 'cero_anios_hombre',
                            name: 'cero_anios_hombre'
                        }, {
                            data: 'cero_anios_mujer',
                            name: 'cero_anios_mujer' /*  */
                        }, {
                            data: 'un_anio_hombre',
                            name: 'un_anio_hombre'
                        }, {
                            data: 'un_anio_mujer',
                            name: 'un_anio_mujer'
                        }, {
                            data: 'dos_anios_hombre',
                            name: 'dos_anios_hombre'
                        }, {
                            data: 'dos_anios_mujer',
                            name: 'dos_anios_mujer'
                        }, {
                            data: 'mas_cinco_anios_hombre',
                            name: 'mas_cinco_anios_hombre' /*  */
                        }, {
                            data: 'mas_cinco_anios_mujer',
                            name: 'mas_cinco_anios_mujer'
                        },
                        /* {
                                               data: 'total_hombres'
                                           }, */
                        /*  {
                                                data: 'total_mujer'
                                            }, */
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
