@extends('layouts.main', ['activePage' => 'usuarios', 'titlePage' => ''])
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <style>
        .centrarmodal {
            display: flex;
            justify-content: center;
            align-items: center;
            background: #000000c9 !important;
        }

        .ui-autocomplete {
            z-index: 215000000 !important;
        }

        /*  formateando nav-tabs  */
        .nav-tabs .nav-link:not(.active) {
            /* border-color: transparent !important; */
        }

        .nav-link {
            /* color: #000; */
            font-weight: bold;
        }

        .nav-tabs .nav-item {
            color: #43beac;

            /* background-color: #43beac; */
            /* #0080FF; */
            /* color: #FFF; */
        }

        .nav-tabs .nav-item .nav-link.active {
            /* color: #43beac; */
            /* #0080FF; */

            background-color: #43beac;
            color: #FFF;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <h4 class="page-title font-16">HOMOLOGACION DE ACTAS</h4>
        </div>

        <div class="col-lg-3 col-md-2 col-sm-2">
            <select id="vmunicipio" name="vmunicipio" class="form-control btn-xs font-11"
                onchange="cargarred(),cargartabla()">

                @if ($muni->count() > 1)
                    <option value="0">MUNICIPIOS</option>
                @endif

                @foreach ($muni as $item)
                    <option value="{{ $item->id }}">
                        {{ $item->codigo }}|
                        {{ $item->nombre }}
                    </option>
                @endforeach

            </select>
        </div>
        <div class="col-lg-3 col-md-2 col-sm-2">
            <select id="vred" name="vred" class="form-control btn-xs font-11"
                onchange="cargarmicrored(),cargartabla()">
                <option value="0">RED</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            <select id="vmicrored" name="vmicrored" class="form-control btn-xs font-11" onchange="cargartabla()">
                <option value="0">MICRORED</option>
            </select>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1">
            <input type="date" id="vfechaf" name="vfechaf" class="form-control btn-xs font-11"
                value="{{ date('Y-m-d') }}" onchange="cargartabla()">
        </div>

    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-border">
                        <div class="card-header border-success-0 bg-transparent pb-2 pl-0">
                            {{-- <div class="card-widgets"><button type="button" class="btn btn-primary btn-xs" onclick="add()"><i class="fa fa-plus"></i> Nuevo</button></div> --}}
                            <h4 class="card-title">lista de actas</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table id="tabla" class="table table-sm table-striped table-bordered font-12">
                                    <thead class="cabecera-dataTable table-success-0 text-white">
                                        <tr>
                                            <th class="text-center">Nº</th>
                                            <th class="text-center">RED</th>
                                            <th class="text-center">MICRORED</th>
                                            <th class="text-center">CODIGO UNICO</th>
                                            <th class="text-center">ESTABLECIMIENTO</th>
                                            {{-- <th class="text-center">N° ARCHIVOS</th> --}}
                                            <th class="text-center">ACCIÓN</th>
                                        </tr>
                                    </thead>

                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div> <!-- End row -->
        </div>
    </div> <!-- End row -->

    <!-- Bootstrap modal -->
    <div id="modal_form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog">
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
                        <input type="hidden" id="mfubigeo" name="mfubigeo">
                        <input type="hidden" id="mfeess" name="mfeess">
                        <div class="form-body">
                            <div class="form-group">
                                <label>Fecha Envio<span class="required">*</span></label>
                                <input id="mffechae" name="mffechae" class="form-control" type="date" readonly>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label>Fecha Inicial<span class="required">*</span></label>
                                <input id="mffechai" name="mffechai" class="form-control" type="date"
                                    value="{{ date('Y-m-d') }}">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label>Fecha Final<span class="required">*</span></label>
                                <input id="mffechaf" name="mffechaf" class="form-control" type="date"
                                    value="{{ date('Y-m-d') }}">
                                <span class="help-block"></span>
                            </div>

                            <div class="form-group">
                                <label>Numero de Archivos<span class="required">*</span></label>
                                <input id="mfarchivos" name="mfarchivos" class="form-control" type="number"
                                    value="0">
                                <span class="help-block"></span>
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
@endsection
@section('js')
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
    <script type="text/javascript">
        var table_principal;
        var paleta_colores = ['#5eb9aa', '#F9FFFE', '#f5bd22', '#058DC7', '#50B432', '#9D561B', '#DDDF00', '#24CBE5',
            '#64E572', '#9F9655', '#FFF263', '#6AF9C4'
        ];
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
            Highcharts.setOptions({
                lang: {
                    thousandsSep: ","
                }
            });

            // getmes();
            cargarred();
            cargarmicrored();
            cargartabla();

        });

        function cargartabla() {
            table_principal = $('#tabla').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: false,
                destroy: true,
                language: table_language,
                destroy: true,
                ajax: {
                    "url": "{{ route('imporpadronactas.registro.listar') }}",
                    "type": "GET",
                    "data": {
                        'municipio': $('#vmunicipio').val(),
                        'red': $('#vred').val(),
                        'microred': $('#vmicrored').val(),
                        'fechaf': $('#vfechaf').val(),
                    },
                },
                columnDefs: [{
                        targets: 0,
                        className: 'text-center'
                    },
                    {
                        targets: 1,
                        className: 'text-center'
                    },
                    {
                        targets: 2,
                        className: 'text-center'
                    },
                    {
                        targets: 3,
                        className: 'text-center'
                    },
                    {
                        targets: 4,
                        className: 'text-left'
                    },
                    {
                        targets: 5,
                        className: 'text-center'
                    }
                ]
            });
        }

        function getmes() {
            var mesNombre = ["ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE",
                "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"
            ];
            var today = new Date();
            var ano = today.getFullYear();
            var mes = today.getMonth() + 1;

            var meses = [];
            mesA = mes;
            mes = $('#anio').val() == ano ? mes : 12;

            for (i = 1; i <= mes; i++) {
                meses.push({
                    'id': mes,
                    'nombre': mesNombre[i - 1]
                });
            }
            // console.log(meses);

            $("#mes option").remove();
            var options = '';
            $.each(meses, function(index, value) {
                ss = (mesA == value.id ? "selected" : "");
                options += `<option value = '${value.id}' ${ss}>${value.nombre}</option>`;
            });
            $("#mes").append(options);
        }

        function cargarred() {
            cargarmicrored();
            $.ajax({
                url: "{{ route('eess.cargarred') }}",
                data: {
                    'sector': 2,
                    'municipio': $('#vmunicipio').val(),
                },
                type: 'GET',
                success: function(data) {
                    $("#vred option").remove();
                    var options = data.red.length > 1 ? '<option value="0">RED</option>' : '';
                    $.each(data.red, function(index, value) {
                        //ss = (id == value.id ? "selected" : "");
                        options += `<option value='${value.id}'>${value.nombre}</option>`;
                    });
                    $("#vred").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarmicrored() {
            $.ajax({
                url: "{{ route('eess.cargarmicrored') }}",
                data: {
                    'sector': 2,
                    'municipio': $('#vmunicipio').val(),
                    'red': $('#vred').val(),
                },
                type: 'GET',
                success: function(data) {
                    $("#vmicrored option").remove();
                    var options = data.micro.length > 1 ? '<option value="0">MICRORED</option>' : '';
                    $.each(data.micro, function(index, value) {
                        //ss = (id == value.id ? "selected" : "");
                        options += `<option value='${value.id}'>${value.nombre}</option>`;
                    });
                    $("#vmicrored").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function datos(eess) {
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $.ajax({
                url: "{{ route('eess.find', '') }}/" + eess,
                type: 'GET',
                success: function(data) {
                    $('.modal-title').html('Registrar actas del EE.SS ' + data.eess.nombre_establecimiento);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
            $('#mfeess').val(eess);
            $('#mffechae').val($('#vfechaf').val());
            $('#mfubigeo').val($('#vmunicipio').val());
        }

        function save() {
            var save_method = 'add';
            $('#btnSave').text('guardando...');
            $('#btnSave').attr('disabled', true);
            var url;
            if (save_method == 'add') {
                url = "{{ route('imporpadronactas.registro..guardar') }}";
                msgsuccess = "El registro fue creado exitosamente.";
                msgerror = "El registro no se pudo crear verifique las validaciones.";
            } else {
                url = "{{ url('/') }}/Mantenimiento/RER/ajax_update";
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
                        table_principal.ajax.reload(null, false);
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

        function formatofechax(fechaISO) {
            var fecha = new Date(fechaISO); // usa zona colombiana
            var dia = String(fecha.getDate()).padStart(2, '0');
            var mes = String(fecha.getMonth() + 1).padStart(2, '0'); // Los meses empiezan desde 0
            var anio = fecha.getFullYear();
            var fechaFormateada = dia + '/' + mes + '/' + anio;
            return fechaFormateada;
        }

        function formatofecha(fechaISO) {
            var partesFecha = fechaISO.split('-');
            var anio = partesFecha[0];
            var mes = partesFecha[1];
            var dia = partesFecha[2];
            var fechaFormateada = dia + '/' + mes + '/' + anio;
            return fechaFormateada;
        }

        function cargarDistritos() {
            $.ajax({
                url: "{{ route('ubigeo.distrito.25', '') }}/" + $('#provincia').val(),
                type: 'GET',
                success: function(data) {
                    $("#distrito option").remove();
                    var options = '<option value="0">DISTRITO</option>';
                    $.each(data, function(index, value) {
                        //ss = (id == value.id ? "selected" : "");
                        options += "<option value='" + value.id + "'>" + value.nombre +
                            "</option>"
                    });
                    $("#distrito").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }
    </script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <!-- optional -->
    <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
@endsection
