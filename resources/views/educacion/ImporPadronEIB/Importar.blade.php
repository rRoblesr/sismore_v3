@extends('layouts.main', ['titlePage' => 'IMPORTAR DATOS - PADRON EIB'])
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
                        <div class="card-widgets">
                            {{-- <button type="button" onclick="addpadronweb()" class="btn btn-primary btn-sm btn-xs"
                                    title="Agregar Servicio Educativo"><i class="fa fa-plus"></i> Padron Web</button> --}}
                            <button type="button" class="btn btn-warning btn-xs" onclick="location.reload()"><i
                                    class="fa fa-redo"></i> Actualizar</button>
                            <button type="button" class="btn btn-success btn-xs" onclick=""><i
                                    class="fa fa-file-excel"></i>
                                Plantilla</button>
                            <button type="button" class="btn btn-danger btn-xs" onclick=""><i
                                    class="mdi mdi-file-pdf-outline"></i>
                                Manual</button>
                        </div>
                        <h3 class="card-title">Datos de importación</h3>
                    </div>

                    <div class="card-body pb-0">
                        <form class="cmxform form-horizontal tasi-form upload_file">
                            @csrf
                            <input type="hidden" id="ccomment" name="comentario" value="">
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label class="col-form-label">Fuente de datos</label>
                                    <div class="">
                                        <input type="text" class="form-control" readonly="readonly"
                                            value="REGISTRO NACIONAL EIB - PADRON EIB">
                                    </div>
                                </div>

                                <div class="col-md-2">
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
                                    {{-- <th>dre</th> --}}
                                    <th>ugel</th>
                                    {{-- <th>departamento</th> --}}
                                    <th>provincia</th>
                                    <th>distrito</th>
                                    <th>centro_poblado</th>
                                    <th>cod_mod</th>
                                    <th>cod_local</th>
                                    <th>institucion_educativa</th>
                                    <th>cod_nivelmod</th>
                                    <th>nivel_modalidad</th>
                                    <th>forma_atencion</th>
                                    {{-- <th>cod_lengua</th> --}}
                                    <th>lengua_uno</th>
                                    <th>lengua_dos</th>
                                    <th>lengua_3</th>
                                    {{-- <th>Accion</th> --}}
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


        <!-- Bootstrap modal -->
        <div id="modal_padronweb" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="form_padronweb" class="form-horizontal" autocomplete="off">
                            @csrf
                            <input type="hidden" id="idiiee_padronweb" name="idiiee_padronweb" value="">
                            <input type="hidden" id="estado_padronweb" name="estado_padronweb" value="">
                            <div class="form-body">

                                <div class="form-group">
                                    <div class="row">
                                        {{-- <div class="col-md-6">
                                            <label>Codigo Modular<span class="required">*</span></label>
                                            <input type="text" id="entidad_codigo" name="entidad_codigo"
                                                class="form-control">
                                            <span class="help-block"></span>
                                        </div> --}}
                                        <div class="col-md-6">
                                            <label>Codigo Modular<span class="required">*</span></label>
                                            <div class="input-group">
                                                <input type="number" id="codigomodular_padronweb"
                                                    name="codigomodular_padronweb" class="form-control"
                                                    placeholder="Codigo Modular">
                                                <span class="help-block"></span>
                                                <span class="input-group-append">
                                                    <button type="button"
                                                        class="btn waves-effect waves-light btn-primary"
                                                        onclick="buscarcodmodular();" id="buscar_padronweb"
                                                        data-loading-text="<i class='fa fa-spinner fa-spin'></i>">
                                                        <i class="fa fa-search"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-6">
                                            <label>Codigo <span class="required">*</span></label>
                                            <input type="text" id="entidad_codigo" name="entidad_codigo"
                                                class="form-control">
                                            <span class="help-block"></span>
                                        </div> --}}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Provincia <span class="required">*</span></label>
                                            <input id="provincia_padronweb" name="provincia_padronweb"
                                                class="form-control" type="text"
                                                onkeyup="this.value=this.value.toUpperCase()" value="" readonly>
                                            <span class="help-block"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Distrito <span class="required">*</span></label>
                                            <input id="distrito_padronweb" name="distrito_padronweb" class="form-control"
                                                type="text" onkeyup="this.value=this.value.toUpperCase()"
                                                value="" readonly>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Centro Poblado <span class="required">*</span></label>
                                            <input id="centropoblado_padronweb" name="centropoblado_padronweb"
                                                class="form-control" type="text"
                                                onkeyup="this.value=this.value.toUpperCase()" value="" readonly>
                                            <span class="help-block"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Codigo Local <span class="required">*</span></label>
                                            <input id="codigolocal_padronweb" name="codigolocal_padronweb"
                                                class="form-control" type="text"
                                                onkeyup="this.value=this.value.toUpperCase()" value="" readonly>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Nombre IIEE<span class="required">*</span></label>
                                            <input id="iiee_padronweb" name="iiee_padronweb" class="form-control"
                                                type="text" onkeyup="this.value=this.value.toUpperCase()"
                                                value="" readonly>
                                            <span class="help-block"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Codigo Nivel<span class="required">*</span></label>
                                            <input id="codigonivel_padronweb" name="nivelmodalidad_padronweb"
                                                class="form-control" type="text"
                                                onkeyup="this.value=this.value.toUpperCase()" value="" readonly>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Nivel Modalidad<span class="required">*</span></label>
                                            <input id="nivelmodalidad_padronweb" name="nivelmodalidad_padronweb"
                                                class="form-control" type="text"
                                                onkeyup="this.value=this.value.toUpperCase()" value="" readonly>
                                            <span class="help-block"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Forma de Atencion<span class="required">*</span></label>
                                            <input id="formaatencion_padronweb" name="formaatencion_padronweb"
                                                class="form-control" type="text"
                                                onkeyup="this.value=this.value.toUpperCase()" value="">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Codigo Lengua<span class="required">*</span></label>
                                            <input id="codigolengua_padronweb" name="codigolengua_padronweb"
                                                class="form-control" type="text"
                                                onkeyup="this.value=this.value.toUpperCase()" value="">
                                            <span class="help-block"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Lengua uno<span class="required">*</span></label>
                                            <input id="lenguauno_padronweb" name="lenguauno_padronweb"
                                                class="form-control" type="text"
                                                onkeyup="this.value=this.value.toUpperCase()" value="">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Lengua dos<span class="required">*</span></label>
                                            <input id="lenguados_padronweb" name="lenguados_padronweb"
                                                class="form-control" type="text"
                                                onkeyup="this.value=this.value.toUpperCase()" value="">
                                            <span class="help-block"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Lengua 3<span class="required">*</span></label>
                                            <input id="lengua3_padronweb" name="lengua3_padronweb" class="form-control"
                                                type="text" onkeyup="this.value=this.value.toUpperCase()"
                                                value="">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        <button type="button" id="btnsavepadronweb" onclick="savepadronweb()"
                            class="btn btn-primary">Guardar</button>
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
        var table_padroneib = '';
        $(document).ready(function() {
            $("input").change(function() {
                $(this).parent().removeClass('has-error');
                $(this).next().empty();
            });
            $("textarea").change(function() {
                $(this).parent().removeClass('has-error');
                $(this).next().empty();
            });
            $("select").change(function() {
                $(this).parent().removeClass('has-error');
                $(this).next().empty();
            });

            $('.upload_file').on('submit', upload);

            table_principal = $('#datatable').DataTable({
                responsive: true,
                autoWidth: false,
                order: true,
                language: table_language,
                ajax: "{{ route('imporpadroneib.listar.importados') }}",
                type: "POST",
            });
        });
    </script>

    <script>
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
                url: "{{ route('imporpadroneib.guardar') }}",
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
                        url: "{{ url('/') }}/ImporPadronEIB/eliminar/" + id,
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
            var url = "{{ route('imporpadroneib.listarimportados', 55555) }}";
            url = url.replace('55555', id);
            table_padroneib = $('#siagie-matricula').DataTable({
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
                            data: 'ugel',
                            name: 'ugel'
                        }, {
                            data: 'provincia',
                            name: 'provincia'
                        }, {
                            data: 'distrito',
                            name: 'distrito'
                        }, {
                            data: 'centro_poblado',
                            name: 'centro_poblado'
                        }, {
                            data: 'cod_mod',
                            name: 'cod_mod' /*  */
                        }, {
                            data: 'cod_local',
                            name: 'cod_local'
                        }, {
                            data: 'institucion_educativa',
                            name: 'institucion_educativa'
                        }, {
                            data: 'cod_nivelmod',
                            name: 'cod_nivelmod'
                        }, {
                            data: 'nivel_modalidad',
                            name: 'nivel_modalidad'
                        }, {
                            data: 'forma_atencion',
                            name: 'forma_atencion' /*  */
                        }, {
                            data: 'lengua1',
                            name: 'lengua1'
                        }, {
                            data: 'lengua2',
                            name: 'lengua2' /*  */
                        }, {
                            data: 'lengua3',
                            name: 'lengua3'
                        },
                        /*  {
                                                data: 'accion',
                                                name: 'accion'
                                            }, */
                    ],
                }

            );

            $('#modal-siagie-matricula').modal('show');
            $('#modal-siagie-matricula .modal-title').text('Importado');
        }
    </script>

    <script>
        var save_method_nuevo = '';
        var save_method_padronweb = '';

        function addpadronweb() {
            save_method_padronweb = 'add';
            $('#form_padronweb')[0].reset();
            $('#form_padronweb .form-group').removeClass('has-error');
            $('#form_padronweb .help-block').empty();
            $('#modal_padronweb').modal('show');
            $('#modal_padronweb .modal-title').text('Crear Nuevo Servicio Educativo');
        };

        function addnuevo() {
            save_method_nuevo = 'add';
            $('#form_nuevo')[0].reset();
            $('#form_nuevo .form-group').removeClass('has-error');
            $('#form_nuevo .help-block').empty();
            $('#modal_nuevo').modal('show');
            $('#modal_nuevo .modal-title').text('Crear Nuevo Servicio Educativo');
        };

        function buscarcodmodular() {
            if ($('#codigomodular_padronweb').val() == '') {
                alert('FALTA INGRESAR CODIGO MODULAR');
            } else {

                $('#buscar_padronweb').html("<i class='fa fa-spinner fa-spin'></i>");
                $.ajax({
                    url: "{{ url('/') }}/PadronWeb/codigo_modular/" + $('#codigomodular_padronweb').val(),
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        //console.log(data[0]);
                        $('#form_padronweb')[0].reset();
                        if (data.status) {
                            var dd = data.info;
                            $('#provincia_padronweb').val(dd.provincia);
                            $('#distrito_padronweb').val(dd.distrito);
                            $('#centropoblado_padronweb').val(dd.centro_poblado);
                            $('#codigolocal_padronweb').val(dd.codigo_local); //codigo_nivel
                            $('#iiee_padronweb').val(dd.iiee);
                            $('#codigonivel_padronweb').val(dd.codigo_nivel);
                            $('#nivelmodalidad_padronweb').val(dd.nivel_modalidad);
                            $('#idiiee_padronweb').val(dd.idiiee);
                            $('#codigomodular_padronweb').val(dd.codigo_modular);
                            $('#estado_padronweb').val(dd.estado);
                            if (dd.estado) {
                                $('[name="codigomodular_padronweb"]').parent().addClass('has-error');
                                $('[name="codigomodular_padronweb"]').next().text(
                                    '<br> Servicio Educativo Registrado en el Padron EIB');
                            }
                        } else {
                            $('[name="' + data.inputerror + '"]').parent().addClass('has-error');
                            $('[name="' + data.inputerror + '"]').next().text('<br>' + data.error_string);
                        }
                        $('#buscar_padronweb').html('<i class="fa fa-search"></i>');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.error(
                            'No se puede eliminar este registro por seguridad de su base de datos, Contacte al Administrador del Sistema',
                            'Mensaje');
                        $('#buscar_padronweb').html('<i class="fa fa-search"></i>');
                    }
                });
            }
        }

        function savepadronweb() {
            $('#btnsavepadronweb').text('guardando...');
            $('#btnsavepadronweb').attr('disabled', true);
            $.ajax({
                url: "{{ route('padroneib.ajax.add.opt1') }}",
                data: $('#form_padronweb').serialize(),
                type: "POST",
                dataType: "JSON",
                success: function(data) {
                    console.log(data);
                    if (data.status) {
                        $('#modal_padronweb').modal('hide');
                        //table_padroneib.ajax.reload();
                        toastr.success("El registro fue creado exitosamente.", 'Mensaje');
                    } else {
                        for (var i = 0; i < data.inputerror.length; i++) {
                            $('[name="' + data.inputerror[i] + '"]').parent().addClass('has-error');
                            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                        }
                    }
                    $('#btnsavepadronweb').text('Guardar');
                    $('#btnsavepadronweb').attr('disabled', false);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error("El registro no se pudo crear verifique las validaciones.", 'Mensaje');
                    $('#btnsavepadronweb').text('Guardar');
                    $('#btnsavepadronweb').attr('disabled', false);
                }
            });
        };

        function borrarmanual(id) {
            bootbox.confirm("Seguro desea Eliminar el registro EIB del Padron?", function(result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ url('/') }}/PadronEIB/ajax_delete_opt1/" + id,
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                            table_padroneib.ajax.reload();
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
    </script>
    <script src="{{ asset('/') }}public/assets/libs/jquery-validation/jquery.validate.min.js"></script>
    <!-- Validation init js-->
    <script src="{{ asset('/') }}public/assets/js/pages/form-validation.init.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>
@endsection
