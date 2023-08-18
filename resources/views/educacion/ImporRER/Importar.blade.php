@extends('layouts.main', ['titlePage' => 'IMPORTAR DATOS - MATRICULAS SIAGIE'])
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
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Datos de importación
                        </h3>
                    </div>

                    <div class="card-body">
                        <div class="form">

                            <form class="cmxform form-horizontal tasi-form upload_file">
                                @csrf
                                <input type="hidden" id="ccomment" name="comentario" value="">
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Fuente de datos</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" readonly="readonly"
                                            value="RED EDUCATIVA RURAL">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Año Matricula</label>

                                    <div class="col-md-10">
                                        <select id="anio" name="anio" class="form-control">
                                            @foreach ($anios as $item)
                                                <option value="{{ $item->id }}"> {{ $item->anio }} </option>
                                            @endforeach
                                        </select>
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
                                    <th>region</th>
                                    <th>provincia</th>
                                    <th>distrito</th>
                                    <th>dre</th>
                                    <th>nombre_ugel</th>
                                    <th>codigo_modular</th>
                                    <th>area</th>
                                    <th>codigo_local</th>
                                    <th>institucion_educativa</th>
                                    <th>nivel_ciclo</th>
                                    <th>caracteristica</th>
                                    <th>estudantes</th>
                                    <th>docentes</th>
                                    <th>administrativos</th>
                                    <th>codigo_sede_rer</th>
                                    <th>nombre_rer</th>
                                    <th>tiempo_rer</th>
                                    <th>tiempo_rer_ugel</th>
                                    <th>tipo_transporte</th>
                                    <th>anio_creacion</th>
                                    <th>anio_implementacion</th>
                                    <th>resolucion</th>
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
                responsive: true,
                autoWidth: false,
                order: true,
                language: table_language,
                ajax: "{{ route('imporrer.importar') }}",
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
                url: "{{ route('imporrer.guardar') }}",
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
                        url: "{{ url('/') }}/ImporMatricula/eliminar/" + id,
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
            var url = "{{ route('ImporMatricula.listarimportados', 55555) }}";
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
