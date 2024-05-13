@extends('layouts.main', ['titlePage' => ''])
@section('css')
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"> --}}
@endsection
@section('content')
    <div class="content">


        <div class="row">
            <div class="col-md-10">
            </div>
            <div class="col-md-2">
                <select name="fuente1" id="fuente1" class="form-control btn-xs" onchange="cargarhistorial();">
                    @if ($fuentes->count() == 1)
                        <option value="{{ $fuentes[0]->id }}">{{ $fuentes[0]->nombre }}</option>
                    @else
                        {{-- <option value="">FUENTE DE DATOS</option> --}}
                        @foreach ($fuentes as $item)
                            <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
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
                        <h3 class="card-title">HISTORIAL DE IMPORTACIÓN DE PADRÓN ACTAS</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="datatable"
                                        class="table table-sm table-striped table-bordered dt-responsive nowrap font-12"
                                        style="font-size: 12px">
                                        <thead class="text-white  bg-success-0">
                                            <tr>
                                                <th>N°</th>
                                                <th>Versión</th>
                                                <th>Fuente</th>
                                                <th>Usuario</th>
                                                <th>Área</th>
                                                <th>Registro</th>
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
                                        <label class="col-form-label">Fuente de datos<span class="requerid">*</span>
                                        </label>
                                        <div class="">
                                            <select name="fuente2" id="fuente2" class="form-control btn-xs">
                                                @if ($fuentes->count() == 1)
                                                    <option value="{{ $fuentes[0]->id }}">{{ $fuentes[0]->nombre }}</option>
                                                @else
                                                    <option value="">SELECCIONAR</option>
                                                    @foreach ($fuentes as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Fecha Versión<span class="requerid">*</span></label>
                                        <div class="">
                                            <input type="date" class="form-control btn-xs" name="fechaActualizacion"
                                                placeholder="Ingrese fecha actualizacion" autofocus required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="">
                                            <label class="col-form-label">Archivo<span class="requerid">*</span></label>
                                            <div class="">
                                                <input type="file" name="file" class="form-control btn-xs" required>
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
                            <table id="siagie-matricula" class="table table-sm table-striped table-bordered font-11">
                                {{-- width:7200px; --}}
                                <thead class="text-white bg-success-0">
                                    <th>nombre_municipio</th>
                                    <th>departamento</th>
                                    <th>provincia</th>
                                    <th>distrito</th>
                                    <th>fecha_inicial</th>
                                    <th>fecha_final</th>
                                    <th>fecha_envio</th>
                                    <th>dni_usuario_envio</th>
                                    <th>primer_apellido</th>
                                    <th>segundo_apellido</th>
                                    <th>prenombres</th>
                                    <th>numero_archivos </th>
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

            // table_principal = $('#datatable').DataTable({
            //     responsive: true,
            //     autoWidth: false,
            //     ordered: true,
            //     language: table_language,
            //     ajax: "{{ route('imporpadronactas.listar.importados') }}",
            //     data: {
            //         fuente: $('#fuente').val(),
            //     },
            //     type: "POST",
            // });

            // fetch('https://www.highcharts.com/samples/data/nuclear-energy-production.json')
            //     .then((res) => res.json())
            //     .then((data) => {
            //         console.log(data);
            //         // dataset = data;
            //     });

            // fetch(
            //         "https://apiperu.dev/api/dni/45026462?api_token=95f88a81f2e812ab6c398d30776a74f03a6d21edb121457819c1d8c22095cbe0"
            //     )
            //     .then((res) => res.json())
            //     .then((data) => {
            //         console.log(data);
            //     });
            // fetch(
            //         "https://apiperu.dev/api/ruc/10450264623?api_token=95f88a81f2e812ab6c398d30776a74f03a6d21edb121457819c1d8c22095cbe0"
            //     )
            //     .then((res) => res.json())
            //     .then((data) => {
            //         console.log(data);
            //     });
            cargarhistorial();
        });

        function cargarhistorial() {
            console.log('ss');
            table_principal = $('#datatable').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: true,
                destroy: true,
                language: table_language,
                ajax: "{{ route('imporpadronactas.listar.importados') }}",
                data: {
                    fuente: $('#fuente1').val(),
                },
                type: "POST",
            });
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
                url: "{{ route('imporpadronactas.guardar') }}",
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
                        url: "{{ route('imporpadronactas.eliminar', '') }}/" + id,
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
                    "responsive": false,
                    "autoWidth": false,
                    "ordered": true,
                    "destroy": true,
                    "language": table_language,
                    "ajax": {
                        "headers": {
                            'X-CSRF-TOKEN': $('input[name=_token]').val()
                        },
                        "url": "{{ route('imporpadronactas.listarimportados') }}",
                        "data": {
                            "importacion_id": id
                        },
                        "type": "POST",
                        "dataType": 'JSON',
                    },
                    "columns": [{
                            data: 'nombre_municipio',
                            name: 'nombre_municipio'
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
                            data: 'fecha_inicial',
                            name: 'fecha_inicial'
                        },
                        {
                            data: 'fecha_final',
                            name: 'fecha_final'
                        },
                        {
                            data: 'fecha_envio',
                            name: 'fecha_envio'
                        },
                        {
                            data: 'dni_usuario_envio',
                            name: 'dni_usuario_envio'
                        },
                        {
                            data: 'primer_apellido',
                            name: 'primer_apellido'
                        },
                        {
                            data: 'segundo_apellido',
                            name: 'segundo_apellido'
                        },
                        {
                            data: 'prenombres',
                            name: 'prenombres'
                        },
                        {
                            data: 'numero_archivos',
                            name: 'numero_archivos'
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
