@extends('layouts.main', ['activePage' => 'usuarios', 'titlePage' => 'GESTION DE ENTIDAD'])

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <style>
        .form-control-x {
            /*  height: calc(1.5em + 0.9rem + 2px); */
            padding: 5px 5px;
            font-weight: 400;
            line-height: 1.5;
            border: 1px solid #ccc;
            border-radius: 0.2rem;
        }
    </style>
@endsection

@section('content')
    <div class="content">
        <input type="hidden" id="formato" name="formato" value="{{ $formato }}">
        <div class="row">
            @if ($formato == 1)
                <div class="col-md-6">
                    <form class="form-horizontal">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class=" col-form-label">Entidad</label>
                                <div class="">
                                    <select name="bentidad" id="bentidad" class="form-control btn-xs"
                                        onchange="listarDT();">
                                        @foreach ($entidades as $item)
                                            <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @elseif($formato == 2)
                <div class="col-md-12">
                    <form class="form-horizontal">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class=" col-form-label">Entidad</label>
                                <div class="">
                                    <select name="bentidad" id="bentidad" class="form-control btn-xs"
                                        onchange="listarDT();">
                                        @foreach ($entidades as $item)
                                            <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label">TODOS</label>
                                <div class="">
                                    <select name="bgerencia" id="bgerencia" class="form-control btn-xs">
                                        <option value="">Gerencias</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
            <div class="col-md-12">
                <div class="card card-border">
                    <div class="card-header border-success-0 bg-transparent pb-0">
                        <div class="card-widgets">
                            <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()"><i
                                    class="fa fa-redo"></i> Actualizar</button>
                            <button type="button" class="btn btn-primary btn-xs" onclick="add_entidad()"><i
                                    class="fa fa-plus"></i> Nuevo</button>
                        </div>
                        <h4 class="card-title">Lista de Entidades</h4>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <br>
                            <table id="dtPrincipal" class="table table-striped table-bordered" style="width:100%">
                                <thead class="cabecera-dataTable table-success-0 text-white" id="xxx">
                                    @if ($formato == 0)
                                        <th>Nº</th>
                                        <th>Entidad</th>
                                        <th>Abreviado</th>
                                        <th>Aciones</th>
                                    @elseif ($formato == 1)
                                        <th>Nº</th>
                                        <th>Entidad</th>
                                        <th>Gerencia</th>
                                        <th>Abreviado</th>
                                        <th>Aciones</th>
                                    @else
                                        <th>Nº</th>
                                        <th>Entidad</th>
                                        <th>Gerencia</th>
                                        <th>Abreviado</th>
                                        <th>Aciones</th>
                                    @endif

                                </thead>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div> <!-- End row -->
    </div>

    <!-- Bootstrap modal -->
    <div id="modal_form_entidad" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="form_entidad" class="form-horizontal" autocomplete="off">
                        @csrf
                        <input type="hidden" id="id" name="id">
                        <input type="hidden" id="dependencia" name="dependencia">
                        <div class="form-body">
                            @if ($formato == 1)
                                <div class="form-group">
                                    <label>Entidad<span class="required">*</span></label>
                                    <select name="entidad" id="entidad" class="form-control btn-xs">
                                        @foreach ($entidades as $item)
                                            <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            @endif
                            @if ($formato == 2)
                                <div class="form-group">
                                    <label>Gerencia<span class="required">*</span></label>
                                    <select name="gerencia" id="gerencia" class="form-control btn-xs">
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            @endif
                            <div class="form-group">
                                <label>Nombre <span class="required">*</span></label>
                                <input id="descripcion" name="descripcion" class="form-control btn-xs" type="text"
                                    onkeyup="this.value=this.value.toUpperCase()">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label>Abreviado <span class="required">*</span></label>
                                <input id="apodo" name="apodo" class="form-control btn-xs" type="text"
                                    onkeyup="this.value=this.value.toUpperCase()" maxlength="10">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnSaveentidad" onclick="saveentidad()"
                        class="btn btn-primary">Guardar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Bootstrap modal -->
@endsection

@section('js')
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> --}}
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>


    {{-- DATA TABLE --}}
    <script>
        var save_method = '';
        $(document).ready(function() {
            var save_method = '';
            var table_principal;
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
            listarDT();
        });

        function listarDT() {
            table_principal = $('#dtPrincipal').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: false,
                destroy: true,
                language: table_language,
                ajax: {
                    "url": "{{ route('entidad.listar.json') }}",
                    "type": "GET",
                    "data": {
                        'dependencia': 0,
                        'formato': '{{ $formato }}'
                    },
                },

            });
        }

        function reload_table_principal() {
            table_principal.ajax.reload(null, false);
        }

        function add_entidad() {
            var formato = $('#formato').val();
            save_method = 'add';
            $('#form_entidad')[0].reset();
            $('#form_entidad .form-group').removeClass('has-error');
            $('#form_entidad .help-block').empty();
            $('#id').val('');
            $('#modal_form_entidad').modal('show');
            $('#modal_form_entidad .modal-title').text('Crear Nueva Entidad');


        };

        function edit(id) {
            save_method = 'update';
            $('#form_entidad')[0].reset();
            $('#form_entidad .form-group').removeClass('has-error');
            $('#form_entidad .help-block').empty();
            $('#id').val(id);
            $.ajax({
                url: "{{ route('entidad.ajax.edit', '') }}/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $("#deppendencia").val(data.entidad.dependencia);
                    $("#descripcion").val(data.entidad.nombre);
                    $("#apodo").val(data.entidad.apodo);

                    $('#modal_form_entidad').modal('show');
                    $('#modal_form_entidad .modal-title').text('Modificar Entidad');

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error("El registro no se pudo crear verifique las validaciones.", 'Mensaje');
                }
            });
        };

        function saveentidad() {
            $('#btnSaveentidad').text('guardando...');
            $('#btnSaveentidad').attr('disabled', true);
            var url = save_method == "add" ? "{{ route('entidad.ajax.addentidad') }}" :
                "{{ route('entidad.ajax.updateentidad') }}";
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form_entidad').serialize(),
                dataType: "JSON",
                success: function(data) {
                    console.log(data);
                    if (data.status) {
                        $('#modal_form_entidad').modal('hide');
                        reload_table_principal();
                        toastr.success("El registro fue creado exitosamente.", 'Mensaje');
                    } else {
                        for (var i = 0; i < data.inputerror.length; i++) {
                            $('[name="' + data.inputerror[i] + '"]').parent().addClass('has-error');
                            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                        }
                    }
                    $('#btnSaveentidad').text('Guardar');
                    $('#btnSaveentidad').attr('disabled', false);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error("El registro no se pudo crear verifique las validaciones.", 'Mensaje');
                    $('#btnSaveentidad').text('Guardar');
                    $('#btnSaveentidad').attr('disabled', false);
                }
            });
        };

        function borrar(id) {
            bootbox.confirm("Seguro desea Eliminar este registro?", function(result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ route('entidad.ajax.delete', '') }}/" + id,
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                            $('#modal_form').modal('hide');
                            reload_table_principal();
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
@endsection
