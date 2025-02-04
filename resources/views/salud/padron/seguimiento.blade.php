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
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header bg-success-0">
                        <div class="card-widgets">
                            {{-- <button type="button" class="btn btn-orange-0 btn-xs"
                                onclick="location.href=`{{ route('matriculageneral.niveleducativo.eba.principal') }}`"
                                title=''><i class="fas fa-align-justify"></i> Nivel Educativo</button> --}}
                            {{-- <button type="button" class="btn btn-orange-0 btn-xs" onclick="location.reload()"
                                title='ACTUALIZAR'><i class=" fas fa-history"></i> Actualizar</button> --}}
                        </div>
                        <h3 class="card-title text-white font-12">SEGUIMIENTO DE NIÑOS [ {{ $entidad }} ]</h3>
                    </div>
                    <div class="card-body pb-0">
                        <div class="form-group row align-items-center vh-5">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <h5 class="page-title font-12">PADRON NOMINAL - DIRESA, {{ $actualizado }}</h5>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <select class="form-control" name="grupoEdad" id="grupoEdad"
                                    onchange="cargarListadoGrupoEdad();">
                                    <option value="0">GRUPO EDAD</option>
                                    @foreach ($grupo_edad as $item)
                                        <option {{ $id_grupo == $item['id'] ? 'selected' : '' }}
                                            value="{{ $item['id'] }}">
                                            {{ $item['nombre'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <select class="form-control" name="red" id="red"
                                    onchange="cargarListadoRed();">
                                    <option value="0">RED</option>
                                    @foreach ($grupo_red as $item)
                                        <option {{ $id_grupo == $item->cod_red ? 'selected' : '' }}
                                            value="{{ $item->cod_red }}">
                                            {{ $item->nom_red }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <select class="form-control" name="microred" id="microred"
                                    onchange="cargarListadoMicrored();">
                                    <option value="0">MICRORRED</option>
                                    @foreach ($grupo_microred as $item)
                                        <option {{ $id_grupo == $item->cod_mic ? 'selected' : '' }}
                                            value="{{ $item->cod_mic }}">
                                            {{ $item->nom_mic }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <select class="form-control" name="ipress" id="ipress"
                                    onchange="cargarListadoGrupoIpress();">
                                    <option value="0">EE.SS</option>
                                    @foreach ($grupo_ipress as $item)
                                        <option {{ $dato_ipress->cod_2000 == $item->cod_2000 ? 'selected' : '' }}
                                            value="{{ $item->cod_2000 }}">{{ $item->nom_est }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--  --}}
        {{--  --}}

        <div class="row">
            <div class="col-md-12">
                <div class="card card-border">

                    <div class="card-header border-success-0 bg-transparent pb-0">
                        <div class="card-widgets">
                            <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()">
                                <i class="fa fa-redo"></i> Actualizar</button>
                            <button type="button" class="btn btn-success-0 btn-xs"
                                onclick="descargarExcelSeguimiento()">{{-- fa fa-arrow-alt-circle-down --}}
                                <i class="fa fa-file-excel"></i> Descargar</button>
                        </div>
                        <h3 class="card-title font-12">Seguimiento de Niños(as) del Padron Nominal</h3>
                    </div>

                    <div class="card-body">
                        {{-- <div class="row justify-content-between ">
                            <div class="col-md-4">
                                <div class="row form-group">
                                    <label class="col-md-3 col-form-label">GRUPO EDAD</label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="grupoEdad" id="grupoEdad"
                                            onchange="cargarListadoGrupoEdad();">
                                            @foreach ($grupo_edad as $item)
                                                <option {{ $id_grupo == $item['id'] ? 'selected' : '' }}
                                                    value="{{ $item['id'] }}">{{ $item['nombre'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        {{-- <div class="row justify-content-between "> --}}
                        {{-- @if (session('usuario_sector') == '14')
                                <div class="col-md-3">
                                    <div class="row form-group">
                                        <label class="col-md-3 col-form-label">RED</label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="red" id="red"
                                                onchange="cargarListadoRed();">
                                                @foreach ($grupo_red as $item)
                                                    <option {{ $id_grupo == $item->cod_red ? 'selected' : '' }}
                                                        value="{{ $item->cod_red }}">{{ $item->nom_red }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif --}}
                        {{-- @if (session('usuario_sector') == '14')
                                <div class="col-md-4">
                                    <div class="row form-group">
                                        <label class="col-md-3 col-form-label">MICRORRED</label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="microred" id="microred"
                                                onchange="cargarListadoMicrored();">
                                                @foreach ($grupo_microred as $item)
                                                    <option {{ $id_grupo == $item->cod_mic ? 'selected' : '' }}
                                                        value="{{ $item->cod_mic }}">{{ $item->nom_mic }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif --}}
                        {{-- @if (session('usuario_sector') == '14')
                                <div class="col-md-4">
                                    <div class="row form-group">
                                        <label class="col-md-3 col-form-label">EE.SS</label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="ipress" id="ipress"
                                                onchange="cargarListadoGrupoIpress();">
                                                @foreach ($grupo_ipress as $item)
                                                    <option
                                                        {{ $dato_ipress->cod_2000 == $item->cod_2000 ? 'selected' : '' }}
                                                        value="{{ $item->cod_2000 }}">{{ $item->nom_est }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif --}}
                        {{-- </div> --}}
                        <div class="row">
                            <div class="col-md-12">
                                {{-- <div class="table-responsive"> --}}
                                <table id="datatableS"
                                    class="table table-sm-5 table-striped table-bordered dt-responsive nowrap font-11"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead class="text-white  bg-success-0">
                                        <tr>
                                            <th>#</th>
                                            <th>Distrito</th>
                                            <th>IPRESS</th>
                                            <th>Documento</th>
                                            <th>nino</th>
                                            <th>Edad</th>
                                            <th>Controles</th>
                                            <th>Suplemento</th>
                                            <th>hemoglobina</th>
                                            <th>Anemia</th>
                                            <th>Tratamiento</th>
                                            <th>Vacunas</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                </table>
                                {{-- </div> --}}
                            </div>
                        </div>


                    </div>
                    <!-- card-body -->

                </div>

            </div> <!-- End col -->
        </div> <!-- End row -->

        <!-- Bootstrap modal -->
        <div id="seguimientoModal" class="modal fade centrarmodal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content p-0">
                    <ul class="nav nav-tabs nav-justified" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab-1" data-toggle="tab" href="#home-1" role="tab"
                                aria-controls="home-1" aria-selected="true">
                                <span class="d-block d-sm-none"><i class="fa fa-home"></i></span>
                                <span class="d-none d-sm-block">Datos</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab-1" data-toggle="tab" href="#profile-1" role="tab"
                                aria-controls="profile-1" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-user"></i></span>
                                <span class="d-none d-sm-block">Controles</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="hemoglobina-tab-1" data-toggle="tab" href="#hemoglobina-1"
                                role="tab" aria-controls="hemoglobina-1" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-user"></i></span>
                                <span class="d-none d-sm-block">Hemoglobina</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="message-tab-1" data-toggle="tab" href="#message-1" role="tab"
                                aria-controls="message-1" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-envelope-o"></i></span>
                                <span class="d-none d-sm-block">Suplemento</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="setting-tab-1" data-toggle="tab" href="#setting-1" role="tab"
                                aria-controls="setting-1" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-cog"></i></span>
                                <span class="d-none d-sm-block">Vacuna</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content p-3">
                        <div class="tab-pane show active" id="home-1" role="tabpanel" aria-labelledby="home-tab-1">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group no-margin">

                                        <div class="alert alert-success">
                                            Datos del niñó(a).
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="field-1" class="control-label">Documento</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id='tipo_doc'>DNI</span>
                                            </div>
                                            <input type="text" id="num_doc" class="form-control" placeholder="-"
                                                disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="field-2" class="control-label">Nombres</label>
                                        <input type="text" class="form-control" id="nombre" placeholder="-"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="field-1" class="control-label">Mes/Año Nacimiento</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-calendar-alt"></i></span>
                                            </div>
                                            <input type="text" id="fecha_nacimiento" class="form-control"
                                                placeholder="-" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="field-2" class="control-label">Edad (Meses)</label>
                                        <input type="text" class="form-control" id="edad" placeholder="-"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="field-2" class="control-label">Celular</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                            </div>
                                            <input type="text" id="celular" class="form-control" placeholder="-"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="field-4" class="control-label">Micro Red</label>
                                        <input type="text" class="form-control" id="microrred" placeholder="-"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="field-5" class="control-label">Cod. RENIPRESS</label>
                                        <input type="text" class="form-control" id="cod_eess" placeholder="-"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="field-6" class="control-label">IPRESS</label>
                                        <input type="text" class="form-control" id="nom_eess" placeholder="-"
                                            readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="field-3" class="control-label">Distrito</label>
                                        <input type="text" class="form-control" id="distrito" placeholder="-"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="field-3" class="control-label">Dirección</label>
                                        <input type="text" class="form-control" id="direccion" placeholder="-"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="profile-1" role="tabpanel" aria-labelledby="profile-tab-1">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <div class="alert alert-success">
                                            Controles de Recién Nacido.
                                        </div>
                                        <div id="table-controlrn">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="hemoglobina-1" role="tabpanel" aria-labelledby="hemoglobina-tab-1">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <div class="alert alert-danger">
                                            Dosaje de hemoglobina.
                                        </div>
                                        <div id="table-hemoglobina">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="message-1" role="tabpanel" aria-labelledby="message-tab-1">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <div class="alert alert-warning">
                                            Suplemento a niños(as).
                                        </div>
                                        <div id="table-suplemento">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="setting-1" role="tabpanel" aria-labelledby="setting-tab-1">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <div class="alert alert-info">
                                            Vacuna de niños(as).
                                        </div>
                                        <div id="table-vacuna">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
            table_principal = $('#datatableS').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: true,
                language: table_language,
                ajax: "{{ route('salud.padron.seguimiento.listar', [$id_grupo, $codigo_institucion]) }}",
                type: "POST",
                columnDefs: [{
                    targets: [5, 6, 7, 8, 9, 10, 11],
                    className: 'text-center'
                }, ]
            });
        });

        function mostrarDatosSeguimiento(id) {

            // Obtener la URL utilizando la función route
            var url = "{{ route('salud.padron.seguimiento.mostrardato', ['id' => ':id']) }}";
            url = url.replace(':id', id);

            // Enviar solicitud AJAX
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    completarDatosSeguimiento(data);
                    $('#seguimientoModal').modal('show');
                }
            });

        };

        function completarDatosSeguimiento(data) {
            var tipo_doc = (data.dni == '') ? 'CNV' : 'DNI';
            var documento = (data.dni == '') ? data.cnv : data.dni;
            $('#tipo_doc').text(tipo_doc);
            $('#num_doc').val(documento);
            $('#nombre').val(data.nombre_nino);
            $('#fecha_nacimiento').val(data.fecha_nacimiento);
            $('#edad').val(parseInt(data.edad_anio) * 12 + parseInt(data.edad_mes));
            $('#dni_madre').val(data.dni_madre);
            $('#celular').val(data.celular);
            $('#distrito').val(data.distrito);
            $('#direccion').val(data.direccion);
            $('#microrred').val(data.microred);
            $('#cod_eess').val(data.renaes);
            $('#nom_eess').val(data.eess);
            $('#nombre_calidad').text(data.nombre_calidad);
            $('#descripcion_calidad').text(data.descripcion_calidad);

            var dataCtrl = [{
                    id: 1,
                    fechaAtencion: data.f_credrn1,
                    edadAtencion: data.edad_credrn1
                }, {
                    id: 2,
                    fechaAtencion: data.f_credrn2,
                    edadAtencion: data.edad_credrn2
                },
                {
                    id: 3,
                    fechaAtencion: data.f_credrn3,
                    edadAtencion: data.edad_credrn3
                }, {
                    id: 4,
                    fechaAtencion: data.f_credrn4,
                    edadAtencion: data.edad_credrn4
                },
            ];
            var tableHtml = '<table class="table table-bordered mb-0">' + '<thead>' + '<tr>' + '<th>#</th>' +
                '<th>Fecha de Atención</th>' + '<th>Edad</th>' + '</tr>' + '</thead>' + '<tbody>';
            dataCtrl.forEach(function(item, index) {
                if (item.fechaAtencion != '-')
                    tableHtml += '<tr>' + '<td>' + item.id + '</td>' + '<td>' + item.fechaAtencion + '</td>' +
                    '<td>' + item.edadAtencion + '</td>' + '</tr>';
            });
            tableHtml += '</tbody>' + '</table>';

            var dataCtrl = [{
                    id: 1,
                    fechaAtencion: data.f_cred01,
                    edadAtencion: data.edad_cred01
                }, {
                    id: 2,
                    fechaAtencion: data.f_cred02,
                    edadAtencion: data.edad_cred02
                },
                {
                    id: 3,
                    fechaAtencion: data.f_cred03,
                    edadAtencion: data.edad_cred03
                }, {
                    id: 4,
                    fechaAtencion: data.f_cred04,
                    edadAtencion: data.edad_cred04
                },
                {
                    id: 5,
                    fechaAtencion: data.f_cred05,
                    edadAtencion: data.edad_cred05
                }, {
                    id: 6,
                    fechaAtencion: data.f_cred06,
                    edadAtencion: data.edad_cred06
                },
                {
                    id: 7,
                    fechaAtencion: data.f_cred07,
                    edadAtencion: data.edad_cred07
                }, {
                    id: 8,
                    fechaAtencion: data.f_cred08,
                    edadAtencion: data.edad_cred08
                },
                {
                    id: 9,
                    fechaAtencion: data.f_cred09,
                    edadAtencion: data.edad_cred09
                }, {
                    id: 10,
                    fechaAtencion: data.f_cred010,
                    edadAtencion: data.edad_cred010
                },
                {
                    id: 11,
                    fechaAtencion: data.f_cred011,
                    edadAtencion: data.edad_cred011
                },
            ];
            tableHtml += '<br><div class="alert alert-success">Controles de Menor de 12 meses.   </div> ' +
                '<table class="table table-bordered mb-0">' + '<thead>' + '<tr>' + '<th>#</th>' +
                '<th>Fecha de Atención</th>' + '<th>Edad</th>' + '</tr>' + '</thead>' + '<tbody>';
            dataCtrl.forEach(function(item, index) {
                if (item.fechaAtencion != '-')
                    tableHtml += '<tr>' + '<td>' + item.id + '</td>' + '<td>' + item.fechaAtencion + '</td>' +
                    '<td>' + item.edadAtencion + '</td>' + '</tr>';
            });
            tableHtml += '</tbody>' + '</table>';
            var tableContainer = document.getElementById('table-controlrn');
            tableContainer.innerHTML = tableHtml;

            var dataHemo = [{
                    id: 1,
                    fechaAtencion: data.f_hb1,
                    resultados: data.Rhb1,
                    edadAtencion: data.edad_hb1
                }, {
                    id: 2,
                    fechaAtencion: data.f_hb2,
                    resultados: data.Rhb2,
                    edadAtencion: data.edad_hb2
                },
                {
                    id: 3,
                    fechaAtencion: data.f_hb3,
                    resultados: data.Rhb3,
                    edadAtencion: data.edad_hb3
                }, {
                    id: 4,
                    fechaAtencion: data.f_hb4,
                    resultados: data.Rhb4,
                    edadAtencion: data.edad_hb4
                },
                {
                    id: 5,
                    fechaAtencion: data.f_hb5,
                    resultados: data.Rhb5,
                    edadAtencion: data.edad_hb5
                }, {
                    id: 6,
                    fechaAtencion: data.f_hb6,
                    resultados: data.Rhb6,
                    edadAtencion: data.edad_hb6
                },
            ];
            tableHtml = '<table class="table table-bordered mb-0">' + '<thead>' + '<tr>' + '<th>#</th>' +
                '<th>Fecha de Atención</th>' + '<th>Resultados</th>' + '<th>Edad</th>' + '</tr>' + '</thead>' + '<tbody>';
            dataHemo.forEach(function(item, index) {
                if (item.fechaAtencion != '-')
                    tableHtml += '<tr>' + '<td>' + item.id + '</td>' + '<td>' + item.fechaAtencion + '</td>' +
                    '<td>' + item.resultados + '</td>' + '<td>' + item.edadAtencion + '</td>' + '</tr>';
            });
            tableHtml += '</tbody>' + '</table>';
            var tableContainer = document.getElementById('table-hemoglobina');
            tableContainer.innerHTML = tableHtml;


            var dataSuple = [{
                    id: 1,
                    fechaAtencion: data.f_sup1,
                    labSup: data.lab_sup1,
                    edadAtencion: data.edad_sup1
                }, {
                    id: 2,
                    fechaAtencion: data.f_sup2,
                    labSup: data.lab_sup2,
                    edadAtencion: data.edad_sup2
                },
                {
                    id: 3,
                    fechaAtencion: data.f_sup3,
                    labSup: data.lab_sup3,
                    edadAtencion: data.edad_sup3
                }, {
                    id: 4,
                    fechaAtencion: data.f_sup4,
                    labSup: data.lab_sup4,
                    edadAtencion: data.edad_sup4
                },
                {
                    id: 5,
                    fechaAtencion: data.f_sup5,
                    labSup: data.lab_sup5,
                    edadAtencion: data.edad_sup5
                }, {
                    id: 6,
                    fechaAtencion: data.f_sup6,
                    labSup: data.lab_sup6,
                    edadAtencion: data.edad_sup6
                },
                {
                    id: 7,
                    fechaAtencion: data.f_sup7,
                    labSup: data.lab_sup7,
                    edadAtencion: data.edad_sup7
                }, {
                    id: 8,
                    fechaAtencion: data.f_sup8,
                    labSup: data.lab_sup8,
                    edadAtencion: data.edad_sup8
                },
                {
                    id: 9,
                    fechaAtencion: data.f_sup9,
                    labSup: data.lab_sup9,
                    edadAtencion: data.edad_sup9
                }, {
                    id: 10,
                    fechaAtencion: data.f_sup10,
                    labSup: data.lab_sup10,
                    edadAtencion: data.edad_sup10
                },
                {
                    id: 11,
                    fechaAtencion: data.f_sup11,
                    labSup: data.lab_sup11,
                    edadAtencion: data.edad_sup11
                }, {
                    id: 12,
                    fechaAtencion: data.f_sup12,
                    labSup: data.lab_sup12,
                    edadAtencion: data.edad_sup12
                },
            ];
            tableHtml = '<table class="table table-bordered mb-0">' + '<thead>' + '<tr>' + '<th>#</th>' +
                '<th>Fecha de Atención</th>' + '<th>Lab Supl.</th>' + '<th>Edad</th>' + '</tr>' + '</thead>' + '<tbody>';
            dataSuple.forEach(function(item, index) {
                if (item.fechaAtencion != '-')
                    tableHtml += '<tr>' + '<td>' + item.id + '</td>' + '<td>' + item.fechaAtencion + '</td>' +
                    '<td>' + item.labSup + '</td>' + '<td>' + item.edadAtencion + '</td>' + '</tr>';
            });
            tableHtml += '</tbody>' + '</table>';
            var tableContainer = document.getElementById('table-suplemento');
            tableContainer.innerHTML = tableHtml;


            var dataVacuna = [{
                    id: 1,
                    fechaAtencion: data.f_vhep,
                    vacuna: 'HVB RN',
                    edadAtencion: data.edad_vhep
                }, {
                    id: 2,
                    fechaAtencion: data.f_vbcg,
                    vacuna: 'BCG RN',
                    edadAtencion: data.edad_vbcg
                },
                {
                    id: 3,
                    fechaAtencion: data.f_vpenta1,
                    vacuna: 'Vac. Pentavalente 1ra Dosis',
                    edadAtencion: data.edad_vpenta1
                }, {
                    id: 4,
                    fechaAtencion: data.f_vpenta2,
                    vacuna: 'Vac. Pentavalente 2da Dosis',
                    edadAtencion: data.edad_vpenta2
                },
                {
                    id: 5,
                    fechaAtencion: data.f_vpenta3,
                    vacuna: 'Vac. Pentavalente 3ra Dosis',
                    edadAtencion: data.edad_vpenta3
                }, {
                    id: 6,
                    fechaAtencion: data.f_vipv1,
                    vacuna: 'Vac. IPV 1ra Dosis',
                    edadAtencion: data.edad_vipv1
                },
                {
                    id: 7,
                    fechaAtencion: data.f_vipv2,
                    vacuna: 'Vac. IPV 2da Dosis',
                    edadAtencion: data.edad_vipv2
                }, {
                    id: 8,
                    fechaAtencion: data.f_vapo1,
                    vacuna: 'Vac. APO 1ra Dosis',
                    edadAtencion: data.edad_vapo1
                }, , {
                    id: 9,
                    fechaAtencion: data.f_vapo2,
                    vacuna: 'Vac. APO 2da Dosis',
                    edadAtencion: data.edad_vapo2
                }, , {
                    id: 10,
                    fechaAtencion: data.f_vapo3,
                    vacuna: 'Vac. APO 3ra Dosis',
                    edadAtencion: data.edad_vapo3
                },
            ];
            tableHtml = '<table class="table table-bordered mb-0">' + '<thead>' + '<tr>' + '<th>Vacuna</th>' +
                '<th>Fecha de Atención</th>' + '<th>Edad</th>' + '</tr>' + '</thead>' + '<tbody>';
            dataVacuna.forEach(function(item, index) {
                tableHtml += '<tr>' + '<td>' + item.vacuna + '</td>' + '<td>' + item.fechaAtencion + '</td>' +
                    '<td>' + item.edadAtencion + '</td>' + '</tr>';
            });
            tableHtml += '</tbody>' + '</table>';
            var tableContainer = document.getElementById('table-vacuna');
            tableContainer.innerHTML = tableHtml;


        }

        function cargarListadoGrupoEdad() {
            var grupoEdad = $('#grupoEdad').val();
            var cod_2000 = $('#ipress').val();
            var url = "{{ route('salud.padron.seguimiento.indexge', [':grupoEdad', ':cod_2000']) }}";
            url = url.replace(':grupoEdad', grupoEdad);
            url = url.replace(':cod_2000', cod_2000);
            // console.log(url);
            window.location.href = url;
        }

        function cargarListadoGrupoIpress() {
            var grupoEdad = $('#grupoEdad').val();
            var cod_2000 = $('#ipress').val();
            var url = "{{ route('salud.padron.seguimiento.indexge', [':grupoEdad', ':cod_2000']) }}";
            url = url.replace(':grupoEdad', grupoEdad);
            url = url.replace(':cod_2000', cod_2000);
            // console.log(url);
            window.location.href = url;
        }

        function descargarExcelSeguimiento() {
            var grupoEdad = $('#grupoEdad').val();
            var url = "{{ route('salud.padron.seguimiento.exportar', ':grupoEdad') }}";
            url = url.replace(':grupoEdad', grupoEdad);
            window.location.href = url;
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
