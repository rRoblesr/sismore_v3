@extends('layouts.main',['activePage'=>'usuarios','titlePage'=>'GESTION DE ENTIDAD'])

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endsection

@section('content')
<div class="content">

    {{-- <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">FILTRO</h3>
                    </div>
                    <div class="card-body">
                        <div class="form">
                            <form>
                                <input type="hidden" name="fuenteImportacion" value="3">
                                <div class="col-lg-12">
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">SISTEMAS</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="sistema" id="sistema"
                                                onchange="listarDT();">
                                                <!--option value="0">Seleccionar</option-->
                                                @foreach ($sistemas as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nombre }}</option>
    @endforeach
    </select>
</div>
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
</div> --}}

<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    {{-- <div class="card-header card-header-primary">
                            <h4 class="card-title">Relacion de Usuarios </h4>
                        </div> --}}

                    <div class="card-body">
                        <div class="row justify-content-between ">
                            <div class="col-4 ">
                                <div class="row form-group">
                                    <label class="col-md-4 col-form-label">ENTIDAD</label>
                                    <div class="col-md-8">
                                        <select class="form-control" name="entidad" id="entidad" onchange="listarDT();cargar_gerencia('');">
                                            {{-- <option value="0">SELECCIONAR</option> --}}
                                            @foreach ($entidad as $item)
                                            <option value="{{ $item->id }}">{{ $item->unidad_ejecutora }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 ">
                                <div class="row form-group">
                                    <label class="col-md-4 col-form-label">GERENCIA</label>
                                    <div class="col-md-8">
                                        <select class="form-control" name="entidadgerencia" id="entidadgerencia" onchange="listarDT();">
                                            <option value="0">SELECCIONAR</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 ">
                                <div class="row justify-content-end">
                                    <button type="button" class="btn btn-primary" onclick="option_add()"><i class="fa fa-plus"></i> Nuevo</button>
                                </div>

                            </div>

                        </div>

                        <div class="table-responsive">
                            <br>
                            <table id="dtPrincipal" class="table table-striped table-bordered" style="width:100%">
                                <thead class="cabecera-dataTable" id="xxx">
                                    <!--th>Nº</th-->
                                    <th id="opcionesx">Gerencia</th>
                                    <th>Abreviado</th>
                                    <th>Aciones</th>
                                </thead>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div> <!-- End row -->
    </div>
</div> <!-- End row -->

</div>

<!-- Bootstrap modal -->
<div id="modal_form_gerencia" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" id="form_gerencia" class="form-horizontal" autocomplete="off">
                    @csrf
                    <input type="hidden" class="form-control" id="entidad_id" name="entidad_id">
                    <div class="form-body">
                        <div class="form-group">
                            <label>Entidad <span class="required">*</span></label>
                            <input type="text" id="entidad_nombre" name="entidad_nombre" class="form-control" readonly>
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                            <label>Gerencia <span class="required">*</span></label>
                            <input id="gerencia" name="gerencia" class="form-control" type="text" onkeyup="this.value=this.value.toUpperCase()">
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                            <label>Abreviatura <span class="required">*</span></label>
                            <input id="gerencia_abreviado" name="gerencia_abreviado" class="form-control" type="text" onkeyup="this.value=this.value.toUpperCase()">
                            <span class="help-block"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                <button type="button" id="btnSaveGerencia" onclick="savegerencia()" class="btn btn-primary">Guardar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<!-- Bootstrap modal -->
<div id="modal_form_oficina" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" id="form_oficina" class="form-horizontal" autocomplete="off">
                    @csrf
                    <input type="hidden" class="form-control" id="gerencia_id" name="gerencia_id">
                    <div class="form-body">
                        <div class="form-group">
                            <label>Gerencia<span class="required">*</span></label>
                            <input type="text" id="gerencia_nombre" name="gerencia_nombre" class="form-control" readonly>
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                            <label>Oficina<span class="required">*</span></label>
                            <input id="oficina" name="oficina" class="form-control" type="text" onkeyup="this.value=this.value.toUpperCase()">
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                            <label>Abreviatura <span class="required">*</span></label>
                            <input id="oficina_abreviado" name="oficina_abreviado" class="form-control" type="text" onkeyup="this.value=this.value.toUpperCase()">
                            <span class="help-block"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                <button type="button" id="btnSaveOficina" onclick="saveoficina()" class="btn btn-primary">Guardar</button>
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
        $("#entidad").change(function() {
            $("#opcionesx").html('Gerencia');
        });
        $("#entidadgerencia").change(function() {
            $("#opcionesx").html('Oficina');
            if ($(this).val() == 0) {
                $("#opcionesx").html('Gerencia');
            }
        });
        listarDT();
        cargar_gerencia('')
    });

    function listarDT() {
        table_principal = $('#dtPrincipal').DataTable({
            "ajax": "{{ url('/') }}/Entidad/listar/" + $('#entidad').val() + "/" + $('#entidadgerencia').val(),
            "columns": [{
                data: 'entidad',
            }, {
                data: 'abreviado',
            }, {
                data: 'action',
                orderable: false
            }],
            responsive: true,
            autoWidth: false,
            orderable: false,
            destroy: true,
            language: {
                "lengthMenu": "Mostrar " +
                    `<select class="custom-select custom-select-sm form-control form-control-sm">
                        <option value = '10'> 10</option>
                        <option value = '25'> 25</option>
                        <option value = '50'> 50</option>
                        <option value = '100'>100</option>
                        <option value = '-1'>Todos</option>
                        </select>` + " registros por página",
                "info": "Mostrando la página _PAGE_ de _PAGES_",
                "infoEmpty": "No records available",
                "infoFiltered": "(Filtrado de _MAX_ registros totales)",
                "emptyTable": "No hay datos disponibles en la tabla.",
                "info": "Del _START_ al _END_ de _TOTAL_ registros ",
                "infoEmpty": "Mostrando 0 registros de un total de 0. registros",
                "infoFiltered": "(filtrados de un total de _MAX_ )",
                "infoPostFix": "",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "searchPlaceholder": "Dato para buscar",
                "zeroRecords": "No se han encontrado coincidencias.",
                "paginate": {
                    "next": "siguiente",
                    "previous": "anterior"
                }
            }
        });
    }

    function reload_table_principal() {
        table_principal.ajax.reload(null, false);
    }

    function cargar_gerencia(id) {
        $.ajax({
            url: "{{ url('/') }}/Entidad/CargarGerencia/" + $('#entidad').val(),
            type: 'get',
            success: function(data) {
                $("#entidadgerencia option").remove();
                var options = '<option value="0">SELECCIONAR</option>';
                $.each(data.gerencias, function(index, value) {
                    ss = (id == "" ? "" : (id == value.id ? "selected" : ""));
                    options += "<option value='" + value.id + "' " + ss + ">" + value.entidad +
                        "</option>"
                });
                $("#entidadgerencia").append(options);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
            },
        });
    }
    /* function add() {
        save_method = 'add';
        $('#form')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('#modal_form').modal('show');
        $('.modal-title').text('Crear Nuevo');
    }; */
    function option_add() {
        ent = $('#entidad').val();
        ger = $('#entidadgerencia').val();
        if (ger == 0) add_gerencia();
        else add_oficina();

    }

    function add_gerencia() {
        entidadid = $('#entidad').val();
        if (entidadid == 0) {
            alert('ERROR: seleccionar una Entidad');
            return false;
        }
        entidadnombre = $('#entidad option:selected').text();

        $('#form_gerencia')[0].reset();
        $('#form_gerencia .form-group').removeClass('has-error');
        $('#form_gerencia .help-block').empty();
        $('#entidad_id').val(entidadid);
        $('#entidad_nombre').val(entidadnombre.trim());
        $('#modal_form_gerencia').modal('show');
        $('#modal_form_gerencia .modal-title').text('Crear Nueva Gerencia');

    };

    function savegerencia() {
        $('#btnSaveGerencia').text('guardando...');
        $('#btnSaveGerencia').attr('disabled', true);
        $.ajax({
            url: "{{route('entidad.ajax.addgerencia')}}",
            type: "POST",
            data: $('#form_gerencia').serialize(),
            dataType: "JSON",
            success: function(data) {
                console.log(data);
                if (data.status) {
                    $('#modal_form_gerencia').modal('hide');
                    reload_table_principal();
                    cargar_gerencia('');
                    toastr.success("El registro fue creado exitosamente.", 'Mensaje');
                } else {
                    for (var i = 0; i < data.inputerror.length; i++) {
                        $('[name="' + data.inputerror[i] + '"]').parent().addClass('has-error');
                        $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                    }
                }
                $('#btnSaveGerencia').text('Guardar');
                $('#btnSaveGerencia').attr('disabled', false);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error("El registro no se pudo crear verifique las validaciones.", 'Mensaje');
                $('#btnSaveGerencia').text('Guardar');
                $('#btnSaveGerencia').attr('disabled', false);
            }
        });
    };

    function add_oficina() {
        entidadid = $('#entidadgerencia').val();
        if (entidadid == 0) {
            alert('ERROR: seleccionar una Entidad');
            return false;
        }
        entidadnombre = $('#entidadgerencia option:selected').text();

        $('#form_oficina')[0].reset();
        $('#form_oficina .form-group').removeClass('has-error');
        $('#form_oficina .help-block').empty();
        $('#gerencia_id').val(entidadid);
        $('#gerencia_nombre').val(entidadnombre.trim());
        $('#modal_form_oficina').modal('show');
        $('#modal_form_oficina .modal-title').text('Crear Nueva Oficina');
    };

    function saveoficina() {
        $('#btnSaveOficina').text('guardando...');
        $('#btnSaveOficina').attr('disabled', true);
        $.ajax({
            url: "{{route('entidad.ajax.addoficina')}}",
            type: "POST",
            data: $('#form_oficina').serialize(),
            dataType: "JSON",
            success: function(data) {
                console.log(data);
                if (data.status) {
                    $('#modal_form_oficina').modal('hide');
                    reload_table_principal();
                    toastr.success("El registro fue creado exitosamente.", 'Mensaje');
                } else {
                    for (var i = 0; i < data.inputerror.length; i++) {
                        $('[name="' + data.inputerror[i] + '"]').parent().addClass('has-error');
                        $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                    }
                }
                $('#btnSaveOficina').text('Guardar');
                $('#btnSaveOficina').attr('disabled', false);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error("El registro no se pudo crear verifique las validaciones.", 'Mensaje');
                $('#btnSaveOficina').text('Guardar');
                $('#btnSaveOficina').attr('disabled', false);
            }
        });
    };
    /*
            function save() {
                $('#btnSave').text('guardando...');
                $('#btnSave').attr('disabled', true);
                var url;
                if (save_method == 'add') {
                    url = "{{ url('/') }}/Perfil/ajax_add";
                    msgsuccess = "El registro fue creado exitosamente.";
                    msgerror = "El registro no se pudo crear verifique las validaciones.";
                } else {
                    url = "{{ url('/') }}/Perfil/ajax_update";
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
                    url: "{{ url('/') }}/Perfil/ajax_edit/" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        $('[name="id"]').val(data.menu.id);
                        $('[name="sistema_id"]').val(data.menu.sistema_id);
                        $('[name="nombre"]').val(data.menu.nombre);
                        $('#modal_form').modal('show');
                        $('.modal-title').text('Modificar Perfil');
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
                            url: "{{ url('/') }}/Perfil/ajax_delete/" + id,
                            type: "GET",
                            dataType: "JSON",
                            success: function(data) {
                                $('#modal_form').modal('hide');
                                reload_table_principal(); //listarDT();
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
            }; */
</script>
@endsection
