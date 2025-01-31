@extends('layouts.main', ['activePage' => 'usuarios', 'titlePage' => ''])

@section('css')
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
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

        .centrarmodal {
            display: flex;
            justify-content: center;
            align-items: center;
            background: #000000c9 !important;
        }

        .ui-autocomplete {
            z-index: 215000000 !important;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div
            class="card-header bg-success-0 text-white d-flex flex-column flex-md-row justify-content-between align-items-md-center p-2">
            <!--h3 class="card-title"></h3-->
            <h6 class="card-title mb-2 mb-md-0 text-center text-white text-md-left text-wrap">
                <!--i class="fas fa-chart-bar"></i--> Directorio de municipalidades del Padron Nominal
            </h6>
            <div class="text-center text-md-right">
                <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()">
                    <i class="fa fa-redo"></i> Actualizar</button>
                <button type="button" class="btn btn-primary btn-xs" onclick="add()">
                    <i class="fa fa-plus"></i> Nuevo</button>
            </div>
        </div>
        <div class="card-body p-2">
            <div class="row mb-0">
                <div class="col-md-8"></div>
                {{-- <div class="col-md-4 my-1">
                    <div class="custom-select-container">
                        <label for="provincia">Provincia</label>
                        <select id="provincia" name="provincia" class="form-control form-control-sm font-11"
                            onchange="cargarDistrito('distrito');">
                            <option value="0">TODOS</option>
                            @foreach ($red as $item)
                                <option value="{{ $item->id }}">{{ $item->codigo }} {{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div> --}}
                {{-- <div class="col-md-4 my-1">
                    <div class="custom-select-container">
                        <label for="distrito">Distrito</label>
                        <select id="distrito" name="distrito" class="form-control form-control-sm font-11"
                            onchange="cargartableprincipal();" data-toggle="codigox">
                            <option value="0">TODOS</option>
                        </select>
                    </div>
                </div> --}}
                <div class="col-md-4 my-1">
                    <div class="custom-select-container">
                        <label for="municipalidad">Municipalidad</label>
                        <select id="municipalidad" name="municipalidad" class="form-control form-control-sm font-11" onchange="cargartableprincipal();">
                            <option value="0">TODOS</option>
                            @foreach ($municipalidad as $item)
                                {{-- <option value="{{ $item->id }}">{{ $item->codigo }} {{ $item->nombre }}</option> --}}
                                <option value="{{ $item->distrito_id }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- <div class="col-6">
                    <div class="custom-select-container">
                        <label for="distrito">Distrito</label>
                        <select id="distrito" name="distrito" class="form-control form-control-sm font-11"
                            onchange="cargartableprincipal();" data-toggle="codigox">
                            <option value="0">TODOS</option>
                        </select>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    <div class="card card-border">
        <div class="card-header border-success-0 bg-transparent p-0">
            <h3 class="card-title"></h3>
        </div>

        <div class="card-body p-2">
            <div class="table-responsive">
                <table id="tbprincipal" class="table table-striped table-bordered tablex" style="font-size: 12px">
                    <thead class="cabecera-dataTable">
                        <tr class="bg-success-0 text-white">
                            <th>Nº</th>
                            <th>Provincia</th>
                            <th>Distrito</th>
                            <th>Municipalidad</th>
                            <th>Responsable</th>
                            <th>Cargo</th>
                            <th>Celular</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Bootstrap modal -->
    <div id="modal_form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="form" name="form" class="form-horizontal" autocomplete="off">
                        @csrf
                        <input type="hidden" id="id" name="id" value="">
                        <div class="form-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>DNI<span class="required">*</span></label>
                                        <div class="input-group">
                                            {{-- <input type="number" id="dni" name="dni" class="form-control"
                                                placeholder="Numero de Documento" maxlength="8"> --}}
                                            <input type="text" id="dni" name="dni" class="form-control"
                                                placeholder="Numero de Documento" maxlength="8" size="8"
                                                oninput="this.value = this.value.replace(/\D/g, '').slice(0, 8)">
                                            <span class="help-block"></span>
                                            <span class="input-group-append">
                                                <button type="button" class="btn waves-effect waves-light btn-primary"
                                                    onclick="buscardni();" id="btnbuscardni">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Nombres<span class="required">*</span></label>
                                        <input id="nombres" name="nombres" class="form-control" type="text"
                                            oninput="convertToUppercase(this)" maxlength="150" placeholder="Nombres">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Apellido Paterno</label>
                                        <input id="apellido_paterno" name="apellido_paterno" class="form-control"
                                            type="text" oninput="convertToUppercase(this)" maxlength="100"
                                            placeholder="Apellido Paterno">
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Apellido Materno</label>
                                        <input id="apellido_materno" name="apellido_materno" class="form-control"
                                            type="text" oninput="convertToUppercase(this)" maxlength="100"
                                            placeholder="Apellido Materno">
                                        <span class="help-block"></span>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Sexo</label>
                                        <select id="sexo" name="sexo" class="form-control">
                                            <option value="0">SELECCIONAR</option>
                                            <option value="1">MASCULINO</option>
                                            <option value="2">FEMENINO</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label>Municipalidad</label>
                                        <select id="fmunicipalidad" name="fmunicipalidad" class="form-control">
                                            <option value="0">SELECCIONAR</option>
                                            @foreach ($municipalidad as $item)
                                                <option value="{{ $item->distrito_id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">

                                    <div class="col-md-6">
                                        <label>Cargo</label>
                                        <input id="cargo" name="cargo" class="form-control" type="text"
                                            oninput="convertToUppercase(this)" placeholder="Cargo">
                                        <span class="help-block"></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label>Condición Laboral</label>
                                        <input id="condicion_laboral" name="condicion_laboral" class="form-control"
                                            type="text" oninput="convertToUppercase(this)"
                                            placeholder="Condición Laboral">
                                        <span class="help-block"></span>
                                    </div>


                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Celular</label>
                                        <input id="celular" name="celular" class="form-control" type="number"
                                            placeholder="Celular">
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Correo Electronico</label>
                                        <input id="email" name="email" class="form-control" type="email"
                                            placeholder="Correo Electronico">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Bootstrap modal -->

    <!-- Bootstrap modal -->
    <div id="modal_ver" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="formver" name="formver" class="form-horizontal" autocomplete="off">
                        @csrf
                        <div class="form-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>DNI<span class="required">*</span></label>
                                        <div class="input-group">
                                            <input type="number" id="vdni" name="vdni" class="form-control"
                                                placeholder="Numero de Documento" maxlength="12" readonly>
                                            <span class="help-block"></span>
                                            <span class="input-group-append">
                                                <button type="button" class="btn waves-effect waves-light btn-primary"
                                                    id="btnbuscardni">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Nombres<span class="required">*</span></label>
                                        <input id="vnombres" name="vnombres" class="form-control" type="text"
                                            oninput="convertToUppercase(this)" maxlength="150"
                                            placeholder="Ingrese Nombres" readonly>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Apellido Paterno</label>
                                        <input id="vapellido_paterno" name="vapellido_paterno" class="form-control"
                                            type="text" oninput="convertToUppercase(this)" maxlength="100"
                                            placeholder="Imgrese Apellido Paterno" readonly>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Apellido Materno</label>
                                        <input id="vapellido_materno" name="vapellido_materno" class="form-control"
                                            type="text" oninput="convertToUppercase(this)" maxlength="100"
                                            placeholder="Ingrese apellido Materno" readonly>
                                        <span class="help-block"></span>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Sexo</label>
                                        <select id="vsexo" name="vsexo" class="form-control" disabled>
                                            <option value="1">MASCULINO</option>
                                            <option value="2">FEMENINO</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Cargo</label>
                                        <input id="vcargo" name="vcargo" class="form-control" type="text"
                                            oninput="convertToUppercase(this)" placeholder="Ingrese Cargo" readonly>
                                        <span class="help-block"></span>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">

                                    <div class="col-md-6">
                                        <label>Condición Laboral</label>
                                        <input id="vcondicion_laboral" name="vcondicion_laboral" class="form-control"
                                            type="text" oninput="convertToUppercase(this)"
                                            placeholder="Ingrese Condición Laboral" readonly>
                                        <span class="help-block"></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label>Municipalidad</label>
                                        <select id="vmunicipalidad" name="vmunicipalidad" class="form-control" disabled>
                                            <option value="0">SELECCIONAR</option>
                                            @foreach ($municipalidad as $item)
                                                <option value="{{ $item->distrito_id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">

                                    <div class="col-md-6">
                                        <label>Distrito</label>
                                        <select id="vmicrored" name="vmicrored" class="form-control" disabled>
                                            <option value="0">SELECCIONAR</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Establecimiento de Salud</label>
                                        <select id="veess" name="veess" class="form-control" disabled>
                                            <option value="0">SELECCIONAR</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Celular</label>
                                        <input id="vcelular" name="vcelular" class="form-control" type="number"
                                            placeholder="Ingrese Celular" readonly>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Correo Electronico</label>
                                        <input id="vemail" name="vemail" class="form-control" type="email"
                                            placeholder="Ingrese Correo Electronico" readonly>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Bootstrap modal -->
@endsection

@section('js')
    {{-- autocompletar --}}
    <script src="{{ asset('/') }}public/assets/jquery-ui/jquery-ui.js"></script>

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

    <script>
        var save_method = '';
        var table_principal;
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

            var tabla = $('#tablaDatos').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                }
            });

            $('#entidadn').autocomplete({
                minLength: 2,
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('eess.autocomplete') }}",
                        data: {
                            term: request.term,
                            dependencia: 0,
                            tipoentidad: $('#nivel').val()
                        },
                        dataType: "JSON",
                        success: function(data) {
                            response(data);
                        }
                    })
                },
                select: function(event, ui) {
                    $('#entidad').val(ui.item.id);
                }
            });

            $('#profesion').autocomplete({
                minLength: 2,
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('mantenimiento.directorio.municipal.autocomplete.profesion') }}",
                        data: {
                            term: request.term,
                        },
                        dataType: "JSON",
                        success: function(data) {
                            response(data);
                        }
                    })
                },
                select: function(event, ui) {
                    // $('#profesion').val(ui.item.id);
                }
            });

            $('#cargo').autocomplete({
                minLength: 2,
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('mantenimiento.directorio.municipal.autocomplete.cargo') }}",
                        data: {
                            term: request.term,
                        },
                        dataType: "JSON",
                        success: function(data) {
                            response(data);
                        }
                    })
                },
                select: function(event, ui) {
                    // $('#profesion').val(ui.item.id);
                }
            });

            $('#condicion_laboral').autocomplete({
                minLength: 2,
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('mantenimiento.directorio.municipal.autocomplete.condicion') }}",
                        data: {
                            term: request.term,
                        },
                        dataType: "JSON",
                        success: function(data) {
                            response(data);
                        }
                    })
                },
                select: function(event, ui) {
                    // $('#profesion').val(ui.item.id);
                }
            });

            cargartableprincipal();

        });

        function limitarDNI(input) {
            if (input.value.length > 8) {
                input.value = input.value.slice(0, 8); // Limita a 8 dígitos
            }
        }

        function cargartableprincipal() {
            table_principal = $('#tbprincipal').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: true,
                language: table_language,
                destroy: true,
                ajax: {
                    "headers": {
                        'X-CSRF-TOKEN': $('input[name=_token]').val()
                    },
                    "url": "{{ route('mantenimiento.directorio.municipal.listar.importados') }}",
                    "type": "POST",
                    "data": {
                        distrito: $('#municipalidad').val(),
                    },
                    //"dataType": 'JSON',
                },
                columnDefs: [{
                    targets: [0, 6, 7],
                    className: 'text-center'
                }],
            });
        }

        function convertToUppercase(input) {
            const start = input.selectionStart;
            const end = input.selectionEnd;
            input.value = input.value.toUpperCase();
            input.setSelectionRange(start, end);
        }

        function buscardni() {
            if ($('#dni').val().length == 8) {
                $('#btnbuscardni').html("<i class='fa fa-spinner fa-spin'></i>");
                $.ajax({
                    url: "https://apiperu.dev/api/dni/" + $('#dni').val() +
                        "?api_token={{ TOKEN_APIPERU }}",
                    type: 'GET',
                    beforeSend: function() {
                        $('[name="nombres"]').val("");
                        $('[name="apellido_paterno"]').val("");
                        $('[name="apellido_materno"]').val("");
                    },
                    success: function(data) {
                        if (data.success) {
                            $("#nombres").val(data.data.nombres);
                            $("#apellido_paterno").val(data.data.apellido_paterno);
                            $("#apellido_materno").val(data.data.apellido_materno);
                            //toastr.success("El registro fue creado exitosamente.", 'Mensaje');
                        } else {
                            alert('El DNI no existe');
                            toastr.error('El DNI no existe', 'Mensaje');
                        }
                        $('#btnbuscardni').html('<i class="fa fa-search"></i>');
                    },
                    error: function(data) {
                        $('#btnbuscardni').html('<i class="fa fa-search"></i>');
                        toastr.error('Error en la busqueda, Contacte al Administrador del Sistema', 'Mensaje');
                    }
                });
            } else {
                alert('INGRESE UN DNI DE 8 DIGITOS');
                toastr.error('INGRESE UN DNI DE 8 DIGITOS', 'Mensaje');
            }

        }

        function add() {
            save_method = 'add';
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#modal_form').modal('show');
            $('.modal-title').text('Crear Responsable ');
            $('#id').val("");
        };

        function save() {
            $('#btnSave').text('guardando...');
            $('#btnSave').attr('disabled', true);
            var url;
            if (save_method == 'add') {
                url = "{{ url('/') }}/Mantenimiento/Directorio/Municipal/ajax_add";
                msgsuccess = "El registro fue creado exitosamente.";
                msgerror = "El registro no se pudo crear verifique las validaciones.";
            } else {
                url = "{{ url('/') }}/Mantenimiento/Directorio/Municipal/ajax_update";
                msgsuccess = "El registro fue actualizado exitosamente.";
                msgerror = "El registro no se pudo actualizar. Verifique la operación";
            }
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function(data) {
                    console.log(data)
                    if (data.status) {
                        $('#modal_form').modal('hide');
                        reload_table_principal(); //listarDT();
                        toastr.success(msgsuccess, 'Mensaje');
                    } else {
                        for (var i = 0; i < data.inputerror.length; i++) {
                            $('[name="' + data.inputerror[i] + '"]').parent().addClass('has-error');
                            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                        }
                    }
                    $('#btnSave').text('Guardar');
                    $('#btnSave').attr('disabled', false);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error(msgerror, 'Mensaje');
                    $('#btnSave').text('Guardar');
                    $('#btnSave').attr('disabled', false);
                }
            });
        };

        function edit(id) {
            save_method = 'update';
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $.ajax({
                url: "{{ url('/') }}/Mantenimiento/Directorio/Municipal/ajax_edit/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('[name="id"]').val(data.dpn.id);
                    $('[name="dni"]').val(data.dpn.dni);
                    $('[name="nombres"]').val(data.dpn.nombres);
                    $('[name="apellido_paterno"]').val(data.dpn.apellido_paterno);
                    $('[name="apellido_materno"]').val(data.dpn.apellido_materno);
                    $('[name="sexo"]').val(data.dpn.sexo);
                    // $('[name="profesion"]').val(data.dpn.profesion);
                    $('[name="cargo"]').val(data.dpn.cargo);
                    $('[name="condicion_laboral"]').val(data.dpn.condicion_laboral);
                    $('[name="fmunicipalidad"]').val(data.dpn.distrito_id);
                    $('[name="celular"]').val(data.dpn.celular);
                    $('[name="email"]').val(data.dpn.email);
                    $('#modal_form').modal('show');
                    $('.modal-title').text('Modificar Responsable');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        };

        function borrar(id) {
            bootbox.confirm("Seguro desea Eliminar este registro?", function(result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ url('/') }}/Mantenimiento/Directorio/Municipal/ajax_delete/" + id,
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                            $('#modal_form').modal('hide');
                            reload_table_principal(); //listarDT();
                            toastr.success('El registro fue eliminado exitosamente.',
                                'Mensaje');
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

        function reload_table_principal() {
            table_principal.ajax.reload(null, false);
        }

        function estado(id, x) {
            bootbox.confirm("Seguro desea " + (x == '0' ? "desactivar" : "activar") + " este registro?", function(
                result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ url('/') }}/Mantenimiento/Directorio/Municipal/ajax_estado/" + id,
                        /* type: "POST", */
                        dataType: "JSON",
                        success: function(data) {
                            console.log(data);
                            reload_table_principal(); //listarDT();
                            if (data.estado)
                                toastr.success('El registro fue Activo exitosamente.',
                                    'Mensaje');
                            else
                                toastr.success('El registro fue Desactivado exitosamente.',
                                    'Mensaje');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            toastr.error(
                                'No se puede cambiar estado por seguridad de su base de datos, Contacte al Administrador del Sistema.',
                                'Mensaje');
                        }
                    });
                }
            });
        };

        function ver(id) {
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $.ajax({
                url: "{{ url('/') }}/Mantenimiento/Directorio/Municipal/ajax_edit/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('[name="vid"]').val(data.dpn.id);
                    $('[name="vdni"]').val(data.dpn.dni);
                    $('[name="vnombres"]').val(data.dpn.nombres);
                    $('[name="vapellido_paterno"]').val(data.dpn.apellido_paterno);
                    $('[name="vapellido_materno"]').val(data.dpn.apellido_materno);
                    $('[name="vsexo"]').val(data.dpn.sexo);
                    // $('[name="vprofesion"]').val(data.dpn.profesion);
                    $('[name="vcargo"]').val(data.dpn.cargo);
                    $('[name="vcondicion_laboral"]').val(data.dpn.condicion_laboral);
                    $('[name="vmunicipalidad"]').val(data.dpn.distrito_id);
                    $('[name="vcelular"]').val(data.dpn.celular);
                    $('[name="vemail"]').val(data.dpn.email);
                    $('#modal_ver').modal('show');
                    $('.modal-title').text('Vista General');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        };

        function cargarDistrito(select) {
            provincia = select == 'distrito' ? $('#provincia').val() : ('fdistrito' ? $('#fprovincia').val() : 0);
            $.ajax({
                url: "{{ route('ubigeo.distrito.25.select', ['provincia' => ':provincia']) }}"
                    .replace(':provincia', provincia),
                type: 'GET',
                success: function(data) {
                    $(`#${select} option`).remove();
                    var options = data.length > 1 ? '<option value="0">TODOS</option>' : '';
                    $.each(data, function(index, value) {
                        //ss = (id == value.id ? "selected" : "");
                        options +=
                            `<option value='${value.id}'>${value.codigo} ${value.nombre}</option>`;
                    });
                    $(`#${select}`).append(options);
                    if (select == 'provincia') cargartableprincipal();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarMunicipio(select) {
            distrito = select == 'municipalidad' ? $('#distrito').val() : ('fmunicipalidad' ? $('#fdistrito').val() : 0);
            $.ajax({
                url: "{{ route('ubigeo.distrito.25.select', ['provincia' => ':provincia']) }}"
                    .replace(':provincia', distrito),
                type: 'GET',
                success: function(data) {
                    $(`#${select} option`).remove();
                    var options = data.length > 1 ? '<option value="0">TODOS</option>' : '';
                    $.each(data, function(index, value) {
                        //ss = (id == value.id ? "selected" : "");
                        options +=
                            `<option value='${value.id}'>${value.codigo} ${value.nombre}</option>`;
                    });
                    $(`#${select}`).append(options);
                    if (select == 'municipalidad') cargartableprincipal();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        // function cargarSelectDis-trito(provincia, distrito) {
        //     $.ajax({
        //         url: "{{ route('ubigeo.distrito.25.select', ['provincia' => ':provincia']) }}"
        //             .replace(':provincia', provincia),
        //         type: 'GET',
        //         success: function(data) {
        //             $(`#fdistr-ito option`).remove();
        //             var options = data.length > 1 ? '<option value="0">TODOS</option>' : '';
        //             $.each(data, function(index, value) {
        //                 ss = (distrito == value.id ? "selected" : "");
        //                 options +=
        //                     `<option value='${value.id}' ${ss}>${value.codigo} ${value.nombre}</option>`;
        //             });
        //             $(`#fdistr-ito`).append(options);

        //         },
        //         error: function(jqXHR, textStatus, errorThrown) {
        //             console.log(jqXHR);
        //         },
        //     });
        // }

        // function cargarSelectEES-SVer(red, micro, eess) {
        //     $.ajax({
        //         url: "{{ route('microred.cargar.find', ['red' => ':red']) }}"
        //             .replace(':red', red),
        //         type: 'GET',
        //         success: function(data) {
        //             $(`#vmicrored option`).remove();
        //             var options = data.length > 1 ? '<option value="0">TODOS</option>' : '';
        //             $.each(data, function(index, value) {
        //                 ss = (micro == value.id ? "selected" : "");
        //                 options +=
        //                     `<option value='${value.id}' ${ss}>${value.codigo} ${value.nombre}</option>`;
        //             });
        //             $(`#vmicrored`).append(options);
        //             /////////////////////////////////////////////
        //             $.ajax({
        //                 url: "{{ route('eess.cargareess.select', ['microred' => ':microred']) }}"
        //                     .replace(':microred', micro),
        //                 type: 'GET',
        //                 success: function(data) {
        //                     $(`#veess option`).remove();
        //                     var options = data.length > 1 ? '<option value="0">TODOS</option>' : '';
        //                     $.each(data, function(index, value) {
        //                         ss = (eess == value.id ? "selected" : "");
        //                         options +=
        //                             `<option value='${value.id}' ${ss}>${value.codigo_unico} | ${value.nombre_establecimiento}</option>`;
        //                     });
        //                     $(`#veess`).append(options);
        //                 },
        //                 error: function(jqXHR, textStatus, errorThrown) {
        //                     console.log(jqXHR);
        //                 },
        //             });
        //             ////////////////////////////////////////////////
        //         },
        //         error: function(jqXHR, textStatus, errorThrown) {
        //             console.log(jqXHR);
        //         },
        //     });
        // }
    </script>
@endsection
