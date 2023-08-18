@extends('layouts.main', ['activePage' => 'usuarios', 'titlePage' => 'Padron Educacion Intercultural Bilingue.'])

@section('css')
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> --}}

    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css"> --}}

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

    {{-- autocompletar --}}
    <link href="{{ asset('/') }}public/assets/jquery-ui/jquery-ui.css" rel="stylesheet" />

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
            padding: 5px;
        }

        .ui-autocomplete {
            z-index: 215000000 !important;
        }
    </style>
@endsection

@section('content')
    <div class="content">
        <form class="">
            @csrf

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-border">
                        <div class="card-header bg-transparent pb-0">
                            <div class="card-widgets">
                                <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()"><i
                                        class="fa fa-redo"></i> Actualizar</button>
                                <button type="button" class="btn btn-primary btn-xs" onclick="add()"><i
                                        class="fa fa-plus"></i>
                                    Asignar</button>
                            </div>
                            <h3 class="card-title">Institución Educativas </h3>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tbprincipal" class="table table-striped table-bordered tablex"
                                    style="font-size: 12px">
                                    <thead class="cabecera-dataTable">
                                        <tr class="bg-primary text-white">
                                            <th>Nº</th>
                                            <th>Ugel</th>
                                            <th>Cod.Modular</th>
                                            <th>Institución Educativa</th>
                                            <th>Nivel</th>
                                            <th>forma Atencion</th>
                                            <th>Lengua 1</th>
                                            <th>Aciones</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div> <!-- End row -->
        </form>
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
                    <form action="" id="form" class="form-horizontal" autocomplete="off">
                        @csrf
                        <input type="hidden" id="id" name="id" value="">
                        <input type="hidden" id="iiee_id" name="iiee_id" value="">
                        <input type="hidden" id="estado" name="estado" value="">
                        <input type="hidden" id="codigonivel" name="codigonivel" value="">
                        <div class="form-body">

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Institución Educativa <span class="required">*</span></label>
                                        <input type="text" id="iiee" name="iiee" class="form-control"
                                            placeholder="Buscar Institucion Educativa">
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Nivel Modalidad <span class="required">*</span></label>
                                        <input id="nivelmodalidad" name="nivelmodalidad" class="form-control" value=""
                                            readonly>
                                        <span class="help-block"></span>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Ugel <span class="required">*</span></label>
                                        <input id="ugel" name="ugel" class="form-control" type="text"
                                            value="" readonly>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Provincia <span class="required">*</span></label>
                                        <input id="provincia" name="provincia" class="form-control" type="text"
                                            value="" readonly>
                                        <span class="help-block"></span>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Distrito <span class="required">*</span></label>
                                        <input id="distrito" name="distrito" class="form-control" type="text"
                                            value="" readonly>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Centro Poblado <span class="required">*</span></label>
                                        <input id="centropoblado" name="centropoblado" class="form-control"
                                            type="text" onkeyup="this.value=this.value.toUpperCase()" value=""
                                            readonly>
                                        <span class="help-block"></span>
                                    </div>

                                </div>
                            </div>

                            {{-- <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Nombre IIEE<span class="required">*</span></label>
                                        <input id="iiee2" name="iiee2" class="form-control" type="text"
                                            onkeyup="this.value=this.value.toUpperCase()" value="" readonly>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Codigo Nivel<span class="required">*</span></label>
                                        <input id="codigonivel" name="codigonivel" class="form-control" type="text"
                                            value="">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div> --}}

                            {{-- <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Codigo Local <span class="required">*</span></label>
                                        <input id="codigolocal" name="codigolocal" class="form-control" type="text"
                                            onkeyup="this.value=this.value.toUpperCase()" value="" readonly>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Codigo Lengua<span class="required">*</span></label>
                                        <input id="codigolengua" name="codigolengua" class="form-control" type="text"
                                            onkeyup="this.value=this.value.toUpperCase()" value="">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Forma de Atencion<span class="required">*</span></label>
                                        <input id="formaatencion" name="formaatencion" class="form-control"
                                            type="text" onkeyup="this.value=this.value.toUpperCase()" value="">
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Lengua 1<span class="required">*</span></label>
                                        <select id="lengua1" name="lengua1" class="form-control">
                                            <option value="">SELECCIONAR</option>
                                            @foreach ($lenguas as $item)
                                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Lengua 2<span class="required">*</span></label>
                                        <select id="lengua2" name="lengua2" class="form-control">
                                            <option value="">SELECCIONAR</option>
                                            @foreach ($lenguas as $item)
                                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Lengua 3<span class="required">*</span></label>
                                        <select id="lengua3" name="lengua3" class="form-control">
                                            <option value="">SELECCIONAR</option>
                                            @foreach ($lenguas as $item)
                                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
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
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
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
                    <form action="" id="formver" class="form-horizontal" autocomplete="off">
                        @csrf
                        <div class="form-body">
                            <div class="form-group">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Institución Educativa <span class="required">*</span></label>
                                            <input type="text" id="viiee" name="viiee" class="form-control"
                                                readonly>
                                            <span class="help-block"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Nivel Modalidad<span class="required">*</span></label>
                                            <input id="vnivelmodalidad" name="vnivelmodalidad" class="form-control"
                                                type="text" value="" readonly>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Ugel<span class="required">*</span></label>
                                        <input id="vugel" name="vugel" class="form-control" type="text"
                                            value="" readonly>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Provincia <span class="required">*</span></label>
                                        <input id="vprovincia" name="vprovincia" class="form-control" type="text"
                                            value="" readonly>
                                        <span class="help-block"></span>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Distrito <span class="required">*</span></label>
                                        <input id="vdistrito" name="vdistrito" class="form-control" type="text"
                                            value="" readonly>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Centro Poblado <span class="required">*</span></label>
                                        <input id="vcentropoblado" name="vcentropoblado" class="form-control"
                                            type="text" value="" readonly>
                                        <span class="help-block"></span>
                                    </div>

                                </div>
                            </div>

                            {{-- <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Nombre IIEE<span class="required">*</span></label>
                                        <input id="viiee2" name="viiee2" class="form-control" type="text"
                                            onkeyup="this.value=this.value.toUpperCase()" value="" readonly>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Codigo Nivel<span class="required">*</span></label>
                                        <input id="vcodigonivel" name="vcodigonivel" class="form-control" type="text"
                                            onkeyup="this.value=this.value.toUpperCase()" value="" readonly>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div> --}}

                            {{-- <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Codigo Local <span class="required">*</span></label>
                                        <input id="vcodigolocal" name="vcodigolocal" class="form-control" type="text"
                                            value="" readonly>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Codigo Lengua<span class="required">*</span></label>
                                        <input id="vcodigolengua" name="vcodigolengua" class="form-control"
                                            type="text" value="" readonly>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="form-group">
                                <div class="row">

                                    <div class="col-md-6">
                                        <label>Forma de Atencion<span class="required">*</span></label>
                                        <input id="vformaatencion" name="vformaatencion" class="form-control"
                                            type="text" value="" readonly>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Lengua 1<span class="required">*</span></label>
                                        <select id="vlengua1" name="vlengua1" class="form-control" disabled>
                                            <option value="0">SELECCIONAR</option>
                                            @foreach ($lenguas as $item)
                                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Lengua 2<span class="required">*</span></label>
                                        <select id="vlengua2" name="vlengua2" class="form-control" disabled>
                                            <option value="0">SELECCIONAR</option>
                                            @foreach ($lenguas as $item)
                                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Lengua 3<span class="required">*</span></label>
                                        <select id="vlengua3" name="vlengua3" class="form-control" disabled>
                                            <option value="0">SELECCIONAR</option>
                                            @foreach ($lenguas as $item)
                                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
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

    {{-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> --}}

    {{-- <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script> --}}

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


    {{-- <script src="{{ asset('/') }}public/assets/jquery-ui/external/jquery/jquery.js"></script> --}}
    <script>
        var save_method = '';
        var table_principal;

        $(document).ready(function() {
            $('#iiee').autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('padroneib.completar.iiee') }}",
                        data: {
                            term: request.term
                        },
                        dataType: 'JSON',
                        success: function(data) {
                            response(data);
                        },
                    });
                },
                minLength: 2,
                select: function(event, ui) {
                    $("#iiee_id").val(ui.item.id);
                    $("#provincia").val(ui.item.provincia);
                    $("#distrito").val(ui.item.distrito);
                    $("#centropoblado").val(ui.item.centro_poblado);
                    $("#codigolocal").val(ui.item.codigo_local);
                    //$("#iiee2").val(ui.item.iiee);
                    //$("#codigonivel").val(ui.item.codigo_nivel);
                    $("#nivelmodalidad").val(ui.item.nivel_modalidad);
                    $("#estado").val(ui.item.estado);
                    $("#ugel").val(ui.item.ugel);
                },
            });

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

            table_principal = $('#tbprincipal').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: false,
                language: table_language,
                dom: 'Bfrtip',
                //dom: 'Blrtip',
                //buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                buttons: [
                    /* {
                                            extend: "copy",
                                            className: "btn-sm"
                                        }, {
                                            extend: "csv",
                                            className: "btn-sm"
                                        },  */
                    {
                        extend: "excel",
                        title: null,
                        className: "btn-sm",
                        text: '<i class="fa fa-file-excel"></i> Excel',
                        titleAttr: 'Excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6],
                            //columns: [0, ':visible'],
                            //columns: ':visible',
                        },
                    }, {
                        extend: "pdf",
                        className: "btn-sm",
                        title: "Padron Educacion Intercultural Billingue",
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6],
                            //columns: [0, ':visible'],
                            //columns: ':visible',
                        },
                        orientation: 'landscape',
                        /* customize: function(win) {
                            $(win.document.body)
                                .css('font-size', '10pt')
                                .prepend(
                                    '<img src="http://datatables.net/media/images/logo-fade.png" style="position:absolute; top:0; left:0;" />'
                                );

                            $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                        }, */
                        //messageTop:"Ronald" ,
                        text: '<i class="fa fa-file-pdf"></i> PDF',
                        titleAttr: 'PDF'
                    },
                    /*  {
                                        extend: "print",
                                        className: "btn-sm"
                                    } */
                ],
                //buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5'],
                ajax: {
                    "url": "{{ route('padroneib.listar.importados') }}",
                    "type": "GET",
                    //"dataType": 'JSON',
                },

            });
        });

        function add() {
            save_method = 'add';
            $('#form')[0].reset();
            $('.form-group').children().children().removeClass('has-error');
            $('.help-block').empty();
            $('#id').val("");
            $('#modal_form').modal('show');
            $('.modal-title').text('Asignar Institución Educativa');
        };

        function save() {
            $('#btnSave').text('guardando...');
            $('#btnSave').attr('disabled', true);
            var url;
            if (save_method == 'add') {
                url = "{{ url('/') }}/PadronEIB/ajax_add";
                msgsuccess = "El registro fue creado exitosamente.";
                msgerror = "El registro no se pudo crear verifique las validaciones.";
            } else {
                url = "{{ url('/') }}/PadronEIB/ajax_update";
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
                        reload_table_principal();
                        toastr.success(msgsuccess, 'Mensaje');
                    } else {
                        for (var i = 0; i < data.inputerror.length; i++) {
                            $('[name="' + data.inputerror[i] + '"]').parent().addClass('has-error'); //.parent()
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
            $('.form-group').children().children().removeClass('has-error');
            $('.help-block').empty();
            $.ajax({
                url: "{{ url('/') }}/PadronEIB/ajax_edit/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('[name="id"]').val(data.eib.id);
                    $('[name="estado"]').val(data.eib.estado);
                    $('[name="iiee_id"]').val(data.eib.institucioneducativa_id);
                    $('[name="iiee"]').val(data.eib.label);
                    $('[name="provincia"]').val(data.eib.provincia);
                    $('[name="distrito"]').val(data.eib.distrito);
                    $('[name="centropoblado"]').val(data.eib.centro_poblado);
                    $('[name="codigolocal"]').val(data.eib.codigo_local);
                    $('[name="ugel"]').val(data.eib.ugel);
                    $('[name="codigonivel"]').val(data.eib.codigo_nivel);
                    $('[name="nivelmodalidad"]').val(data.eib.nivel_modalidad);
                    $('[name="formaatencion"]').val(data.eib.forma_atencion);
                    $('[name="codigolengua"]').val(data.eib.cod_lengua);
                    $('[name="lengua1"]').val(data.eib.lengua1_id);
                    $('[name="lengua2"]').val(data.eib.lengua2_id);
                    $('[name="lengua3"]').val(data.eib.lengua3_id);
                    $('#modal_form').modal('show');
                    $('.modal-title').text('Modificar Asignacion');
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
                        url: "{{ url('/') }}/PadronEIB/ajax_delete/" + id,
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
        };

        function reload_table_principal() {
            table_principal.ajax.reload(null, false);
        }

        function ver(id) {
            $('#form')[0].reset();
            $('.form-group').children().children().removeClass('has-error');
            $('.help-block').empty();
            $.ajax({
                url: "{{ url('/') }}/PadronEIB/ajax_edit/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('[name="viiee"]').val(data.eib.label);
                    $('[name="vprovincia"]').val(data.eib.provincia);
                    $('[name="vdistrito"]').val(data.eib.distrito);
                    $('[name="vcentropoblado"]').val(data.eib.centro_poblado);
                    $('[name="vcodigolocal"]').val(data.eib.codigo_local);
                    $('[name="vugel"]').val(data.eib.ugel);
                    $('[name="vnivelmodalidad"]').val(data.eib.nivel_modalidad);
                    $('[name="vformaatencion"]').val(data.eib.forma_atencion);
                    $('[name="vcodigolengua"]').val(data.eib.cod_lengua);
                    $('[name="vlengua1"]').val(data.eib.lengua1_id);
                    $('[name="vlengua2"]').val(data.eib.lengua2_id);
                    $('[name="vlengua3"]').val(data.eib.lengua3_id);
                    $('#modal_ver').modal('show');
                    $('.modal-title').text('Vista General');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        };

        /* function estado(id, x) {
            bootbox.confirm("Seguro desea " + (x == 1 ? "desactivar" : "activar") + " este registro?", function(result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ url('/') }}/Mantenimiento/RER/ajax_estado/" + id,
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                            reload_table_principal();
                            if (data.estado)
                                toastr.success('El registro fue Activo exitosamente.', 'Mensaje');
                            else
                                toastr.success('El registro fue Desactivado exitosamente.', 'Mensaje');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            toastr.error(
                                'No se puede cambiar estado por seguridad de su base de datos, Contacte al Administrador del Sistema.',
                                'Mensaje');
                        }
                    });
                }
            });
        }; */
    </script>
@endsection
