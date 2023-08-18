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
                <div class="card card-border">
                    <div class="card-header border-success-0 bg-transparent pb-0">
                        <h3 class="card-title">Datos de importación
                        </h3>
                    </div>
                    <div class="card-body pb-0">
                        <form class="cmxform form-horizontal tasi-form upload_file">
                            @csrf
                            <input type="hidden" id="ccomment" name="comentario" value="">
                            <input type="hidden" id="anio" name="anio" value="{{ date('Y') }}">
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label class="col-form-label">Fuente de datos</label>
                                    <div class="">
                                        <input type="text" class="form-control btn-xs" readonly="readonly"
                                            value="SIAGIE - MATRICULA">
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
                                    <div class="pwrapper m-0" style="display:none;">
                                        <div class="progress progress_wrapper">
                                            <div class="progress-bar progress-bar-striped bg-info progress-bar-animated progress_bar"
                                                role="progressbar" style="width:0%">0%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row ">
                                <div class="offset-lg-2 col-lg-10 text-right">
                                    <button class="btn btn-primary waves-effect waves-light mr-1"
                                        type="submit">Importar</button>
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
                                    <th>DRE</th>
                                    <th>UGEL</th>
                                    <th>DEPARTAMENTO</th>
                                    <th>PROVINCIA</th>
                                    <th>DISTRITO</th>
                                    <th>CENTRO_POBLADO</th>
                                    <th>COD_MOD</th>
                                    <th>INSTITUCION_EDUCATIVA</th>
                                    <th>COD_NIVELMOD</th>
                                    <th>NIVEL_MODALIDAD</th>
                                    <th>COD_GES_DEP</th>
                                    <th>GESTION_DEPENDENCIA</th>
                                    <th>TOTAL_ESTUDIANTES</th>
                                    <th>MATRICULA_DEFINITIVA</th>
                                    <th>MATRICULA_PROCESO</th>
                                    <th>DNI_VALIDADO</th>
                                    <th>DNI_SIN_VALIDAR</th>
                                    <th>REGISTRADO_SIN_DNI</th>
                                    <th>TOTAL_GRADOS</th>
                                    <th>TOTAL_SECCIONES</th>
                                    <th>NOMINAS_GENERADAS</th>
                                    <th>NOMINAS_APROBADAS</th>
                                    <th>NOMINAS_POR_RECTIFICAR</th>
                                    <th>TRES_ANIOS_HOMBRE</th>
                                    <th>TRES_ANIOS_MUJER</th>
                                    <th>CUATRO_ANIOS_HOMBRE</th>
                                    <th>CUATRO_ANIOS_MUJER</th>
                                    <th>CINCO_ANIOS_HOMBRE</th>
                                    <th>CINCO_ANIOS_MUJER</th>
                                    <th>PRIMERO_HOMBRE</th>
                                    <th>PRIMERO_MUJER</th>
                                    <th>SEGUNDO_HOMBRE</th>
                                    <th>SEGUNDO_MUJER</th>
                                    <th>TERCERO_HOMBRE</th>
                                    <th>TERCERO_MUJER</th>
                                    <th>CUARTO_HOMBRE</th>
                                    <th>CUARTO_MUJER</th>
                                    <th>QUINTO_HOMBRE</th>
                                    <th>QUINTO_MUJER</th>
                                    <th>SEXTO_HOMBRE</th>
                                    <th>SEXTO_MUJER</th>
                                    <th>CERO_ANIOS_HOMBRE</th>
                                    <th>CERO_ANIOS_MUJER</th>
                                    <th>UN_ANIO_HOMBRE</th>
                                    <th>UN_ANIO_MUJER</th>
                                    <th>DOS_ANIOS_HOMBRE</th>
                                    <th>DOS_ANIOS_MUJER</th>
                                    <th>MAS_CINCO_ANIOS_HOMBRE</th>
                                    <th>MAS_CINCO_ANIOS_MUJER</th>
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
                ordered: true,
                language: table_language,
                ajax: "{{ route('ImporMatricula.listar.importados') }}",
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
                url: "{{ route('ImporMatricula.guardar') }}",
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
            $('#siagie-matricula').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "responsive": false,
                    "autoWidth": false,
                    "ordered": true,
                    "destroy": true,
                    "language": table_language,
                    "ajax": {
                        "headers": {
                            'X-CSRF-TOKEN': $('input[name=_token]').val()
                        },
                        "url": "{{ route('ImporMatricula.listarimportados') }}",
                        "data": {
                            "matricula_id": {{ $mat->id }}
                        },
                        "type": "POST",
                        "dataType": 'JSON',
                    },
                    "columns": [{
                            data: 'dre',
                            name: 'dre'
                        },
                        {
                            data: 'ugel',
                            name: 'ugel'
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
                            data: 'centro_poblado',
                            name: 'centro_poblado'
                        },
                        {
                            data: 'cod_mod',
                            name: 'cod_mod'
                        },
                        {
                            data: 'institucion_educativa',
                            name: 'institucion_educativa'
                        },
                        {
                            data: 'cod_nivelmod',
                            name: 'cod_nivelmod'
                        },
                        {
                            data: 'nivel_modalidad',
                            name: 'nivel_modalidad'
                        },
                        {
                            data: 'cod_ges_dep',
                            name: 'cod_ges_dep'
                        },
                        {
                            data: 'gestion_dependencia',
                            name: 'gestion_dependencia'
                        },
                        {
                            data: 'total_estudiantes',
                            name: 'total_estudiantes'
                        },
                        {
                            data: 'matricula_definitiva',
                            name: 'matricula_definitiva'
                        },
                        {
                            data: 'matricula_proceso',
                            name: 'matricula_proceso'
                        },
                        {
                            data: 'dni_validado',
                            name: 'dni_validado'
                        },
                        {
                            data: 'dni_sin_validar',
                            name: 'dni_sin_validar'
                        },
                        {
                            data: 'registrado_sin_dni',
                            name: 'registrado_sin_dni'
                        },
                        {
                            data: 'total_grados',
                            name: 'total_grados'
                        },
                        {
                            data: 'total_secciones',
                            name: 'total_secciones'
                        },
                        {
                            data: 'nominas_generadas',
                            name: 'nominas_generadas'
                        },
                        {
                            data: 'nominas_aprobadas',
                            name: 'nominas_aprobadas'
                        },
                        {
                            data: 'nominas_por_rectificar',
                            name: 'nominas_por_rectificar'
                        },
                        {
                            data: 'tres_anios_hombre',
                            name: 'tres_anios_hombre'
                        },
                        {
                            data: 'tres_anios_mujer',
                            name: 'tres_anios_mujer'
                        },
                        {
                            data: 'cuatro_anios_hombre',
                            name: 'cuatro_anios_hombre'
                        },
                        {
                            data: 'cuatro_anios_mujer',
                            name: 'cuatro_anios_mujer'
                        },
                        {
                            data: 'cinco_anios_hombre',
                            name: 'cinco_anios_hombre'
                        },
                        {
                            data: 'cinco_anios_mujer',
                            name: 'cinco_anios_mujer'
                        },
                        {
                            data: 'primero_hombre',
                            name: 'primero_hombre'
                        },
                        {
                            data: 'primero_mujer',
                            name: 'primero_mujer'
                        },
                        {
                            data: 'segundo_hombre',
                            name: 'segundo_hombre'
                        },
                        {
                            data: 'segundo_mujer',
                            name: 'segundo_mujer'
                        },
                        {
                            data: 'tercero_hombre',
                            name: 'tercero_hombre'
                        },
                        {
                            data: 'tercero_mujer',
                            name: 'tercero_mujer'
                        },
                        {
                            data: 'cuarto_hombre',
                            name: 'cuarto_hombre'
                        },
                        {
                            data: 'cuarto_mujer',
                            name: 'cuarto_mujer'
                        },
                        {
                            data: 'quinto_hombre',
                            name: 'quinto_hombre'
                        },
                        {
                            data: 'quinto_mujer',
                            name: 'quinto_mujer'
                        },
                        {
                            data: 'sexto_hombre',
                            name: 'sexto_hombre'
                        },
                        {
                            data: 'sexto_mujer',
                            name: 'sexto_mujer'
                        },
                        {
                            data: 'cero_anios_hombre',
                            name: 'cero_anios_hombre'
                        },
                        {
                            data: 'cero_anios_mujer',
                            name: 'cero_anios_mujer'
                        },
                        {
                            data: 'un_anio_hombre',
                            name: 'un_anio_hombre'
                        },
                        {
                            data: 'un_anio_mujer',
                            name: 'un_anio_mujer'
                        },
                        {
                            data: 'dos_anios_hombre',
                            name: 'dos_anios_hombre'
                        },
                        {
                            data: 'dos_anios_mujer',
                            name: 'dos_anios_mujer'
                        },
                        {
                            data: 'mas_cinco_anios_hombre',
                            name: 'mas_cinco_anios_hombre'
                        },
                        {
                            data: 'mas_cinco_anios_mujer',
                            name: 'mas_cinco_anios_mujer'
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
